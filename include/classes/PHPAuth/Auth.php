<?php

namespace PHPAuth;

use Exception;
use PDO;
use PHPMailer\PHPMailer\PHPMailer;
use RuntimeException;


/**
 * Auth class
 *
 */
class Auth  /* implements AuthInterface */
{
    /**
     * @var PDO $dbh
     */
    protected $dbh;

    /**
     * @var \stdClass Config
     */
    public $config;

    /**
     * Public 'is_logged' field
     * @var bool
     */
    public $isAuthenticated = false;

    /**
     * @var null
     */
    protected $currentuser = null;

    /**
     * Initiates database connection
     *
     */
    public function __construct()
    {
        global $dbh;
        $this->dbh = $dbh;
        $this->config = new Config($dbh);

        $this->isAuthenticated = $this->isLogged();
    }

    /**
     * Logs a user in
     * @param string $email
     * @param string $password
     * @param int $remember
     * @return array $return
     */
    public function login($email, $password, $remember = 0)
    {
        $return['error'] = true;

        $uid = $this->getUID(strtolower($email));

        if (!$uid) {
            $return['message'] = "Account non trovato";
            return $return;
        }

        $user = $this->getBaseUser($uid);

        if (!password_verify($password, $user['password'])) {
            $return['message'] = "Email o Password errati";
            return $return;
        }

        $this->setSessionUID($uid);

        $return['error'] = false;
        $return['message'] = "Loggato con successo";
        return $return;
    }

    /**
     * Creates a new user, adds them to database
     * @param string $email
     * @param string $password
     * @param string $repeatpassword
     * @param array $params
     * @return array $return
     */
    //@todo: => registerUserAccount
    public function register($email, $password, $repeatpassword, $params = [])
    {
        $return['error'] = true;

        if ($password !== $repeatpassword) {
            $return['message'] = "Le password non corrispondono";
            return $return;
        }

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $return['message'] = "Email non valida";
            return $return;
        }

        if ($this->isEmailTaken($email)) {
            $return['message'] = "Esiste già un account con questa email";
            return $return;
        }

        $addUser = $this->addUser($email, $password, $params);

        if ($addUser['error'] != 0) {
            $return['message'] = $addUser['message'];
            return $return;
        }

        $return['error'] = false;
        $return['message'] = "Registrato con successo";
        $return['uid'] = $addUser['uid'];
        $return['token'] = $addUser['token'];

        return $return;
    }

    /**
     * Creates a reset key for an email address and sends email
     * @param string $email
     * @return array $return
     */
    public function requestReset($email)
    {
        $state['error'] = true;

        $query = "SELECT id, email FROM {$this->config->table_users} WHERE email = :email";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['email' => $email]);

        $row = $query_prepared->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            $state['message'] = "Email non esistente";
            return $state;
        }

        $addRequest = $this->addRequest($row['id'], $email);

        if ($addRequest['error'] == 1) {
            $state['message'] = $addRequest['message'];
            return $state;
        }

        $state['uid'] = $row['id'];
        $state['error'] = false;
        $state['message'] = "Richiesta inoltrata. Controlla la tua email";
        $state['token'] = $addRequest['token'];
        $state['expire'] = $addRequest["expire"];

        return $state;
    }

    /**
     * Logs out the current session
     * @return boolean
     */
    public function logout()
    {
        $this->isAuthenticated = false;
        $this->currentuser = null;
        return session_destroy();
    }

    /**
     * Hashes provided password with Bcrypt
     * @param string $password
     * @return string
     */
    public function getHash($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Gets UID for a given email address, return int
     * @param string $email
     * @return int $uid
     */
    public function getUID($email)
    {
        $query = "SELECT id FROM {$this->config->table_users} WHERE email = :email";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['email' => $email]);

        if ($query_prepared->rowCount() == 0) {
            return false;
        }

        return $query_prepared->fetchColumn();
    }

    /**
     * Retrieves the UID associated with the current session
     * @return int $uid
     */
    public function getSessionUID()
    {
        return $_SESSION['auth_uid'] ?? null;
    }

    public function setSessionUID($uid)
    {
        $_SESSION['auth_uid'] = $uid;
    }

    /**
     * Checks if an email is already in use
     * @param string $email
     * @return boolean
     */
    public function isEmailTaken($email)
    {
        $query = "SELECT count(*) FROM {$this->config->table_users} WHERE email = :email";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['email' => $email]);

        if ($query_prepared->fetchColumn() == 0) {
            return false;
        }

        return true;
    }

    /**
     * Adds a new user to database
     * @param string $email -- email
     * @param string $password -- password
     * @param array $params -- additional params
     * @return array
     */
    protected function addUser($email, $password, $params = [])
    {
        $return['error'] = true;

        $query = "INSERT INTO {$this->config->table_users}";
        $query_prepared = $this->dbh->prepare($query);

        if (!$query_prepared->execute()) {
            $return['message'] = "Errore del Sistema";
            return $return;
        }

        $uid = $this->dbh->lastInsertId("{$this->config->table_users}_id_seq");
        $email = strtolower($email);

        $password = $this->getHash($password);

        if (is_array($params) && count($params) > 0) {
            $customParamsQueryArray = [];

            foreach ($params as $paramKey => $paramValue) {
                $customParamsQueryArray[] = ['value' => $paramKey . ' = ?'];
            }

            $setParams = ', ' . implode(', ', array_map(function ($entry) {
                    return $entry['value'];
                }, $customParamsQueryArray));
        } else {
            $setParams = '';
        }

        $query = "UPDATE {$this->config->table_users} SET email = ?, password = ? {$setParams} WHERE id = ?";
        $query_prepared = $this->dbh->prepare($query);

        $bindParams = array_values(array_merge([$email, $password], $params, [$uid]));

        if (!$query_prepared->execute($bindParams)) {
            $query = "DELETE FROM {$this->config->table_users} WHERE id = ?";
            $query_prepared = $this->dbh->prepare($query);

            $query_prepared->execute([$uid]);
            $return['message'] = "Errore del sistema";

            return $return;
        }

        $return['uid'] = $uid;
        $return['error'] = false;
        return $return;
    }

    /**
     * Gets basic user data for a given UID and returns an array
     * @param int $uid
     * @return array|bool $data
     */
    protected function getBaseUser($uid){
        $query = "SELECT email, password FROM {$this->config->table_users} WHERE id = :id";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['id' => $uid]);

        $data = $query_prepared->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return false;
        }

        $data['uid'] = $uid;

        return $data;
    }

    /**
     * Gets public user data for a given UID and returns an array, password will be returned if param $withpassword is TRUE
     * @param int $uid
     * @param bool|false $withpassword
     * @return array $data
     */
    public function getUser($uid, $withpassword = false)
    {
        $query = "SELECT * FROM {$this->config->table_users} WHERE id = :id";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['id' => $uid]);

        $data = $query_prepared->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return false;
        }

        $data['uid'] = $uid;

        if (!$withpassword)
            unset($data['password']);

        return $data;
    }


    /**
     * Allows a user to delete their account
     * @param int $uid
     * @param string $password
     * @return array $return
     */
    public function deleteUser($uid, $password)
    {
        $return['error'] = true;

        $user = $this->getBaseUser($uid);

        if (!password_verify($password, $user['password'])) {
            $return['message'] = "Password non corretta";
            return $return;
        }

        $query = "DELETE FROM {$this->config->table_users} WHERE id = :uid";
        $query_prepared = $this->dbh->prepare($query);

        if (!$query_prepared->execute(['uid' => $uid])) {
            $return['message'] = "Errore del Sistema";

            return $return;
        }

        $return['error'] = false;
        $return['message'] = "Account eliminato con successo";

        return $return;
    }

    // protected function add

    /**
     * Creates a password-reset request entry and sends email to user
     * @param int $uid
     * @param string $email
     * @return array
     */
    protected function addRequest($uid, $email)
    {
        $return['error'] = true;

        // check id already exists a request, and delete it
        $query = "SELECT id, expire FROM {$this->config->table_requests} WHERE uid = :uid";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['uid' => $uid]);

        $row_count = $query_prepared->rowCount();

        if ($row_count > 0) {
            $row = $query_prepared->fetch(PDO::FETCH_ASSOC);
            $this->deleteRequest($row['id']);
        }

        $token = $this->getRandomKey();
        $expire = date("Y-m-d H:i:s", strtotime($this->config->request_key_expiration));

        $query = "INSERT INTO {$this->config->table_requests} (uid, token, expire) VALUES (:uid, :token, :expire)";
        $query_prepared = $this->dbh->prepare($query);

        $query_params = [
            'uid' => $uid,
            'token' => $token,
            'expire' => $expire,
        ];

        if (!$query_prepared->execute($query_params)) {
            $return['message'] = "Errore del Sistema";
            return $return;
        }

        $request_id = $this->dbh->lastInsertId();

        $sendmail_status = $this->do_SendMail($email, $token);

        if ($sendmail_status['error']) {
            $this->deleteRequest($request_id);
            $return['message'] = "Errore di Sistema: " . $sendmail_status['message'];
            return $return;
        }

        $return['error'] = false;
        $return['token'] = $token;
        $return['expire'] = $expire;

        return $return;
    }

    /**
     * Returns request data if key is valid
     * @param string $key: token
     * @return array $return
     */
    public function getRequest($key)
    {
        $return['error'] = true;

        $query = "SELECT id, uid, expire FROM {$this->config->table_requests} WHERE token = ?";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute([$key]);

        if ($query_prepared->rowCount() === 0) {
            $return['message'] = "Chiave non valida";
            return $return;
        }

        $row = $query_prepared->fetch(PDO::FETCH_ASSOC);

        $expiredate = strtotime($row['expire']);
        $currentdate = strtotime(date("Y-m-d H:i:s"));

        if ($currentdate > $expiredate) {
            $this->deleteRequest($row['id']);
            $return['message'] = "Chiave scaduta";
            return $return;
        }

        $return['error'] = false;
        $return['id'] = $row['id'];
        $return['uid'] = $row['uid'];

        return $return;
    }

    /**
     * Deletes request from database
     * @param int $id
     * @return boolean
     */
    protected function deleteRequest($id)
    {
        $query = "DELETE FROM {$this->config->table_requests} WHERE id = :id";
        $query_prepared = $this->dbh->prepare($query);
        return $query_prepared->execute(['id' => $id]);
    }


    /**
     * Allows a user to reset their password after requesting a reset key.
     * @param string $key
     * @param string $password
     * @param string $repeatpassword
     * @return array $return
     */
    public function resetPass($key, $password, $repeatpassword)
    {
        $state['error'] = true;

        if ($password !== $repeatpassword) {
            $state['message'] = "Le password inserite non sono uguali";
            return $state;
        }

        $data = $this->getRequest($key);
        if ($data['error'] == 1) {
            $state['message'] = $data['message'];
            return $state;
        }

        /* $user = $this->getBaseUser($data['uid']);

        if (!$user) {
            $this->deleteRequest($data['id']);
            $state['message'] = "Errore di Sistema";
            return $state;
        } */

        $password = $this->getHash($password);

        $query = "UPDATE {$this->config->table_users} SET password = :password WHERE id = :id";
        $query_prepared = $this->dbh->prepare($query);
        $query_params = [
            'password' => $password,
            'id' => $data['uid']
        ];
        $query_prepared->execute($query_params);

        if ($query_prepared->rowCount() == 0) {
            $state['message'] = "Errore di Sistema";

            return $state;
        }

        $this->deleteRequest($data['id']);
        $state['error'] = false;
        $state['message'] = "Password aggiornata con successo";

        return $state;
    }

    /**
     * Changes a user's password
     * @param int $uid
     * @param string $currpass
     * @param string $newpass
     * @param string $repeatnewpass
     * @return array $return
     */
    public function changePassword($uid, $currpass, $newpass, $repeatnewpass)
    {
        $return['error'] = true;

        if ($newpass !== $repeatnewpass) {
            $return['message'] = "Le password non corrispondono";
            return $return;
        }

        $user = $this->getBaseUser($uid);

        if (!$user) {
            $return['message'] = "Errore di Sistema";
            return $return;
        }

        if (!password_verify($currpass, $user['password'])) {
            $return['message'] = "Password errata";
            return $return;
        }

        $newpass = $this->getHash($newpass);

        $query = "UPDATE {$this->config->table_users} SET password = ? WHERE id = ?";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute([$newpass, $uid]);

        $return['error'] = false;
        $return['message'] = "Password cambiata con successo";

        return $return;
    }

    /**
     * Changes a user's email
     * @param int $uid
     * @param string $email
     * @param string $password
     * @return array $return
     */
    public function changeEmail($uid, $email, $password)
    {
        $return['error'] = true;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $return['message'] = "Email non valida";
            return $return;
        }

        if ($this->isEmailTaken($email)) {
            $return['message'] = "Esiste già un account con questa email";
            return $return;
        }

        $user = $this->getBaseUser($uid);

        if (!$user) {
            $return['message'] = "Errore di Sistema";
            return $return;
        }

        if (!password_verify($password, $user['password'])) {
            $return['message'] = "Password errata";
            return $return;
        }

        $query = "UPDATE {$this->config->table_users} SET email = ? WHERE id = ?";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute([$email, $uid]);

        if ($query_prepared->rowCount() == 0) {
            $return['message'] = "Errore di Sistema";
            return $return;
        }

        $return['error'] = false;
        $return['message'] = "Email cambiata con successo";

        return $return;
    }

    /**
     * Returns a random string of a specified length
     * @param int $length
     * @return string $key
     */
    public function getRandomKey($length = 40)
    {
        $dictionary = "A1B2C3D4E5F6G7H8I9J0K1L2M3N4O5P6Q7R8S9T0U1V2W3X4Y5Z6a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6";
        $dictionary_length = strlen($dictionary);
        $key = "";

        for ($i = 0; $i < $length; $i++) {
            $key .= $dictionary[mt_rand(0, $dictionary_length - 1)];
        }

        return $key;
    }

    /**
     * Returns is user logged in
     * @return boolean
     */
    public function isLogged()
    {
        if ($this->isAuthenticated === false) {
            return isset($_SESSION['auth_uid']);
        }
        return $this->isAuthenticated;
    }

    /**
     * Gets user data for current user and returns an array, password is not returned
     * @param bool $updateSession = false
     * @return array $data
     * @return boolean false if no current user
     */
    public function getCurrentUser()
    {
        if ($this->currentuser === null) {
            $uid = $this->getSessionUID();
            if ($uid === false) {
                return false;
            }

            $this->currentuser = $this->getUser($uid);
        }

        return $this->currentuser;
    }


    /**
     * Send email via PHPMailer
     *
     * @param $email
     * @param $key
     * @return array $return (contains error code and error message)
     */
    public function do_SendMail($email, $key)
    {
        $return = [
            'error' => true
        ];
        $mail = new PHPMailer();

        // Check configuration for custom SMTP parameters
        try {
            // Server settings
            if ($this->config->smtp) {

                if ($this->config->smtp_debug) {
                    $mail->SMTPDebug = $this->config->smtp_debug;
                }

                $mail->isSMTP();

                $mail->Host = $this->config->smtp_host;
                $mail->SMTPAuth = $this->config->smtp_auth;

                // set SMTP auth username/password
                if (!is_null($this->config->smtp_auth)) {
                    $mail->Username = $this->config->smtp_username;
                    $mail->Password = $this->config->smtp_password;
                }

                // set SMTPSecure (tls|ssl)
                if (!is_null($this->config->smtp_security)) {
                    $mail->SMTPSecure = $this->config->smtp_security;
                }

                $mail->Port = $this->config->smtp_port;
            } //without this params internal mailer will be used.

            //Recipients
            $mail->setFrom($this->config->site_email, $this->config->site_name);
            $mail->addAddress($email);

            $mail->CharSet = $this->config->mail_charset;

            //Content
            $mail->isHTML(true);

            $mail->Subject = 'reset della password';
            $mail->Body = "Per resettare la password clicca sul link seguente\n".
                "http://192.168.1.40/gabrtag/Web_2021/password_reset.php?key={$key}";

            if (!$mail->send())
                throw new Exception($mail->ErrorInfo);

            $return['error'] = false;

        } catch (Exception $e) {
            $return['message'] = $mail->ErrorInfo;
        }

        return $return;
    }

    /**
     * Update userinfo for user with given id = $uid
     * @param int $uid
     * @param array $params
     * @return array $return[error/message]
     */
    public function updateUser($uid, $params)
    {
        $setParams = '';

        //unset uid which is set in getUser(). array generated in getUser() is now usable as parameter for updateUser()
        unset($params['uid']);

        if (is_array($params) && count($params) > 0) {
            $setParams = implode(', ', array_map(function ($key, $value) {
                return $key . ' = ?';
            }, array_keys($params), $params));
        }

        $query = "UPDATE {$this->config->table_users} SET {$setParams} WHERE id = ?";

        $query_prepared = $this->dbh->prepare($query);
        $bindParams = array_values(array_merge($params, [$uid]));

        if (!$query_prepared->execute($bindParams)) {
            $return['message'] = "Errore di Sistema";
            return $return;
        }

        $return['error'] = false;
        $return['message'] = 'Ok.';

        return $return;
    }

    /**
     * Returns current user UID if logged or FALSE otherwise.
     *
     * @return int
     */
    public function getCurrentUID()
    {
        return $this->getSessionUID();
    }

    /**
     * Authorization of an action by a user
     * @param $uid: user ID to be authorized; 0 if not logged user
     * @param $action: action to be authorized
     * @return bool
     */
    public function is_authorized($uid, $action) {
        $query = "SELECT `utenti`.id FROM `utenti` ".
            " JOIN `utente_gruppo` ON {$this->config->table_users}.id = `utente_gruppo`.utente_id ".
            " JOIN `gruppi` ON `utente_gruppo`.gruppo_id = `gruppi`.id ".
            " JOIN `gruppo_servizio` ON `gruppi`.id = `gruppo_servizio`.gruppo_id ".
            " JOIN `servizi` ON `gruppo_servizio`.servizio_id = `servizi`.id ".
            " WHERE `utenti`.id = :uid AND `servizi`.nome = :action";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['uid' => $uid, 'action' => $action]);

        if ($query_prepared->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
