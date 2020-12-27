-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Dic 22, 2020 alle 11:14
-- Versione del server: 8.0.22-0ubuntu0.20.04.3
-- Versione PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tdw2021`
--
CREATE DATABASE IF NOT EXISTS `tdw2021` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `tdw2021`;

-- --------------------------------------------------------

--
-- Struttura della tabella `articoli`
--

DROP TABLE IF EXISTS `articoli`;
CREATE TABLE `articoli` (
  `id` int NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione_lunga` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantita` int NOT NULL,
  `prezzo` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `articoli`
--

INSERT INTO `articoli` (`id`, `nome`, `descrizione`, `descrizione_lunga`, `foto`, `quantita`, `prezzo`) VALUES
(1, 'Mascherina\r\nChirurgica', 'mascherina chirurgica bellissima', 'Una maschera (o mascherina) chirurgica, nota anche come maschera medica,\r\n                            o maschera facciale per uso medico,o mascherina igienica (soprattutto tra gli italiani svizzeri),\r\n                            è un dispositivo destinato a essere indossato dagli operatori sanitari durante un intervento chirurgico\r\n                            o altre attività in ambito sanitario al fine di evitare la dispersione di agenti patogeni.', 'https://i.postimg.cc/kDP21W0R/DEFDEF.png', 24, 0.5),
(2, 'Amuchina Mani', 'Gel disinfettante mani', 'Amuchina Gel X-GERM Disinfettante Mani è un gel antisettico, studiato per disinfettare a fondo la pelle delle mani. La sua formulazione è in grado di ridurre efficacemente in pochi secondi germi e batteri presenti sulla cute. Amuchina Gel X-GERM Disinfettante Mani è attivo su virus, funghi e batteri.', 'https://i.postimg.cc/W4YHndfV/DEFDEF-copia.png', 2, 2.75);

-- --------------------------------------------------------

--
-- Struttura della tabella `articolo_carrello`
--

DROP TABLE IF EXISTS `articolo_carrello`;
CREATE TABLE `articolo_carrello` (
  `carrello_id` int NOT NULL,
  `articolo_id` int NOT NULL,
  `quantita` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `articolo_carrello`
--

INSERT INTO `articolo_carrello` (`carrello_id`, `articolo_id`, `quantita`) VALUES
(1, 1, 21),
(1, 2, 12),
(2, 1, 1),
(2, 2, 4),
(3, 1, 2),
(3, 2, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `gruppi`
--

DROP TABLE IF EXISTS `gruppi`;
CREATE TABLE `gruppi` (
  `id` int NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `gruppi`
--

INSERT INTO `gruppi` (`id`, `nome`, `descrizione`) VALUES
(1, 'admin', 'administrator');

-- --------------------------------------------------------

--
-- Struttura della tabella `gruppo_servizio`
--

DROP TABLE IF EXISTS `gruppo_servizio`;
CREATE TABLE `gruppo_servizio` (
  `gruppo_id` int NOT NULL,
  `servizio_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `gruppo_servizio`
--

INSERT INTO `gruppo_servizio` (`gruppo_id`, `servizio_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9);

-- --------------------------------------------------------

--
-- Struttura della tabella `indirizzi`
--

DROP TABLE IF EXISTS `indirizzi`;
CREATE TABLE `indirizzi` (
  `id` int NOT NULL,
  `utente_id` int NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cognome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `via` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `citta` varchar(127) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provincia` varchar(31) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cap` varchar(31) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `indirizzi`
--

INSERT INTO `indirizzi` (`id`, `utente_id`, `alias`, `nome`, `cognome`, `via`, `citta`, `provincia`, `cap`) VALUES
(1, 3, 'casa mia 1', 'Nomignolo', 'Cognomignolo', 'via Fasulla, 123 - sc. B', 'Springfield', 'SP', '50123'),
(2, 1, 'casa altra', 'Carolina', 'Vissuti', 'viale dei Giradini', 'L\'Aquila', 'AQ', '67100'),
(5, 3, 'Nuova casa', 'Amministra', 'Tore', 'via comunale, 4', 'Roma', 'RM', '55001');

-- --------------------------------------------------------

--
-- Struttura della tabella `metodipagamento`
--

DROP TABLE IF EXISTS `metodipagamento`;
CREATE TABLE `metodipagamento` (
  `id` int NOT NULL,
  `utente_id` int NOT NULL,
  `intestatario_carta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_carta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scadenza_carta` date DEFAULT NULL,
  `cvv_carta` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `metodipagamento`
--

INSERT INTO `metodipagamento` (`id`, `utente_id`, `intestatario_carta`, `numero_carta`, `scadenza_carta`, `cvv_carta`) VALUES
(1, 3, 'Administratore', '4537890672066', '2021-08-01', '123'),
(2, 3, 'lallallero', '47820951702', '2020-01-13', '111');

-- --------------------------------------------------------

--
-- Struttura della tabella `phpauth_attempts`
--

DROP TABLE IF EXISTS `phpauth_attempts`;
CREATE TABLE `phpauth_attempts` (
  `id` int NOT NULL,
  `ip` char(39) NOT NULL,
  `expiredate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `phpauth_config`
--

DROP TABLE IF EXISTS `phpauth_config`;
CREATE TABLE `phpauth_config` (
  `setting` varchar(100) NOT NULL,
  `value` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `phpauth_config`
--

INSERT INTO `phpauth_config` (`setting`, `value`) VALUES
('allow_concurrent_sessions', '0'),
('attack_mitigation_time', '+30 minutes'),
('attempts_before_ban', '30'),
('attempts_before_verify', '5'),
('bcrypt_cost', '10'),
('cookie_domain', NULL),
('cookie_forget', '+30 minutes'),
('cookie_http', '0'),
('cookie_name', 'phpauth_session_cookie'),
('cookie_path', '/'),
('cookie_remember', '+1 month'),
('cookie_renew', '+5 minutes'),
('cookie_secure', '0'),
('custom_datetime_format', 'Y-m-d H:i'),
('emailmessage_suppress_activation', '0'),
('emailmessage_suppress_reset', '0'),
('mail_charset', 'UTF-8'),
('password_min_score', '0'),
('recaptcha_enabled', '0'),
('recaptcha_secret_key', ''),
('recaptcha_site_key', ''),
('request_key_expiration', '+10 minutes'),
('site_activation_page', 'activate'),
('site_activation_page_append_code', '0'),
('site_email', 'no-reply@phpauth.cuonic.com'),
('site_key', 'fghuior.)/!/jdUkd8s2!7HVHG7777ghg'),
('site_language', 'en_GB'),
('site_name', 'PHPAuth'),
('site_password_reset_page', 'reset'),
('site_password_reset_page_append_code', '0'),
('site_timezone', 'Europe/Paris'),
('site_url', 'https://github.com/PHPAuth/PHPAuth'),
('smtp', '0'),
('smtp_auth', '1'),
('smtp_debug', '0'),
('smtp_host', 'smtp.example.com'),
('smtp_password', 'password'),
('smtp_port', '25'),
('smtp_security', NULL),
('smtp_username', 'email@example.com'),
('table_attempts', 'phpauth_attempts'),
('table_emails_banned', 'phpauth_emails_banned'),
('table_requests', 'phpauth_requests'),
('table_sessions', 'phpauth_sessions'),
('table_translations', 'phpauth_translation_dictionary'),
('table_users', 'utenti'),
('translation_source', 'php'),
('verify_email_max_length', '100'),
('verify_email_min_length', '5'),
('verify_email_use_banlist', '1'),
('verify_password_min_length', '3');

-- --------------------------------------------------------

--
-- Struttura della tabella `phpauth_emails_banned`
--

DROP TABLE IF EXISTS `phpauth_emails_banned`;
CREATE TABLE `phpauth_emails_banned` (
  `id` int NOT NULL,
  `domain` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `phpauth_requests`
--

DROP TABLE IF EXISTS `phpauth_requests`;
CREATE TABLE `phpauth_requests` (
  `id` int NOT NULL,
  `uid` int NOT NULL,
  `token` char(20) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `expire` datetime NOT NULL,
  `type` enum('activation','reset') CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `phpauth_sessions`
--

DROP TABLE IF EXISTS `phpauth_sessions`;
CREATE TABLE `phpauth_sessions` (
  `id` int NOT NULL,
  `uid` int NOT NULL,
  `hash` char(40) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `expiredate` datetime NOT NULL,
  `ip` varchar(39) NOT NULL,
  `device_id` varchar(36) DEFAULT NULL,
  `agent` varchar(200) NOT NULL,
  `cookie_crc` char(40) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `phpauth_sessions`
--

INSERT INTO `phpauth_sessions` (`id`, `uid`, `hash`, `expiredate`, `ip`, `device_id`, `agent`, `cookie_crc`) VALUES
(15, 1, 'dc723af031542e56f1c567c6c1a05907ddaae9a0', '2021-01-06 18:14:08', '192.168.1.125', NULL, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.66 Safari/537.36', 'd910ac0fee5c86a63534ff5acaedd5f4fe891460'),
(36, 3, '31eb5b11fa93df93d77facf89da83279a575de29', '2021-01-22 11:11:58', '192.168.1.125', NULL, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.66 Safari/537.36', '25540db60b49a562e4171e82b1005c725eafd419');

-- --------------------------------------------------------

--
-- Struttura della tabella `servizi`
--

DROP TABLE IF EXISTS `servizi`;
CREATE TABLE `servizi` (
  `id` int NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `servizi`
--

INSERT INTO `servizi` (`id`, `nome`, `descrizione`) VALUES
(1, 'articolo.update', 'update di articolo'),
(2, 'indirizzi.show', NULL),
(3, 'indirizzi.edit', NULL),
(4, 'indirizzi.create', NULL),
(5, 'indirizzi.delete', NULL),
(6, 'metodipagamento.show', NULL),
(7, 'metodipagamento.edit', NULL),
(8, 'metodipagamento.create', NULL),
(9, 'metodipagamento.delete\r\n', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `utente_gruppo`
--

DROP TABLE IF EXISTS `utente_gruppo`;
CREATE TABLE `utente_gruppo` (
  `utente_id` int NOT NULL,
  `gruppo_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `utente_gruppo`
--

INSERT INTO `utente_gruppo` (`utente_id`, `gruppo_id`) VALUES
(1, 1),
(3, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

DROP TABLE IF EXISTS `utenti`;
CREATE TABLE `utenti` (
  `id` int NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `isactive` tinyint(1) NOT NULL DEFAULT '0',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id`, `nome`, `email`, `password`, `isactive`, `dt`) VALUES
(1, 'Giovanni Spada', 'giavannino@gmail.com', '$2y$10$EAhLhUi0SsKAa5/shXAnXOHePRVmGjvOjp7UYLERqDC1xI5BBaso2', 1, '2020-12-04 11:35:09'),
(2, 'Rita Calamita', 'Xx_rita_xX@yahoo.it', 'Questa Psw deve essere un hash', 1, '2020-12-03 15:16:44'),
(3, 'admin', 'admin@admin.net', '$2y$10$iJcIRXGq21lUfMBwiXrrYevzrRy5WjXlve2QdGM2Gx2zbqjBZFUxa', 1, '2020-12-16 17:39:43');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `articoli`
--
ALTER TABLE `articoli`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `articolo_carrello`
--
ALTER TABLE `articolo_carrello`
  ADD PRIMARY KEY (`carrello_id`,`articolo_id`);

--
-- Indici per le tabelle `gruppi`
--
ALTER TABLE `gruppi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Indici per le tabelle `gruppo_servizio`
--
ALTER TABLE `gruppo_servizio`
  ADD PRIMARY KEY (`gruppo_id`,`servizio_id`);

--
-- Indici per le tabelle `indirizzi`
--
ALTER TABLE `indirizzi`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `metodipagamento`
--
ALTER TABLE `metodipagamento`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `phpauth_attempts`
--
ALTER TABLE `phpauth_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ip` (`ip`);

--
-- Indici per le tabelle `phpauth_config`
--
ALTER TABLE `phpauth_config`
  ADD UNIQUE KEY `setting` (`setting`);

--
-- Indici per le tabelle `phpauth_emails_banned`
--
ALTER TABLE `phpauth_emails_banned`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `phpauth_requests`
--
ALTER TABLE `phpauth_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `token` (`token`),
  ADD KEY `uid` (`uid`);

--
-- Indici per le tabelle `phpauth_sessions`
--
ALTER TABLE `phpauth_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `servizi`
--
ALTER TABLE `servizi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Indici per le tabelle `utente_gruppo`
--
ALTER TABLE `utente_gruppo`
  ADD PRIMARY KEY (`utente_id`,`gruppo_id`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `articoli`
--
ALTER TABLE `articoli`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `gruppi`
--
ALTER TABLE `gruppi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `indirizzi`
--
ALTER TABLE `indirizzi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `metodipagamento`
--
ALTER TABLE `metodipagamento`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `phpauth_attempts`
--
ALTER TABLE `phpauth_attempts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT per la tabella `phpauth_emails_banned`
--
ALTER TABLE `phpauth_emails_banned`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `phpauth_requests`
--
ALTER TABLE `phpauth_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `phpauth_sessions`
--
ALTER TABLE `phpauth_sessions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT per la tabella `servizi`
--
ALTER TABLE `servizi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
