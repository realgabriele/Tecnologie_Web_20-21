<?php

class Cart {

    /**
     * An unique ID for the cart.
     *
     * @var string
     */
    protected $cartId;

    /**
     * Enable or disable cookie.
     *
     * @var bool
     */
    protected $useCookie = false;

    /**
     * Enable or disable DB.
     *
     * @var bool
     */
    protected $useDB = false;

    /**
     * A collection of cart items.
     *
     * @var array
     */
    private $items = [];

    /**
     * Initialize cart.
     * 
     * @param string $cart_id
     */
    public function __construct($cart_id = null) {
        if (!session_id()) {
            session_start();
        }

        if ($cart_id !== null) {
            $this->cartId = $cart_id;
            $this->useDB = true;
        } else {
            $this->cartId = "cart";
            $this->useCookie = true;
        }

        $this->read();
    }

    /**
     * Get items in  cart.
     *
     * @return array
     */
    public function getItems() {
        return $this->items;
    }

    /**
     * Check if the cart is empty.
     *
     * @return bool
     */
    public function isEmpty() {
        return empty(array_filter($this->items));
    }

    /**
     * Get the total of item in cart.
     *
     * @return int
     */
    public function getTotalItem() {
        $total = 0;

        foreach ($this->items as $item) {
            ++$total;
        }

        return $total;
    }

    /**
     * Get the total of item quantity in cart.
     *
     * @return int
     */
    public function getTotalQuantity() {
        $quantity = 0;

        foreach ($this->items as $item) {
            $quantity += $item['quantita'];
        }

        return $quantity;
    }

    /**
     * Get the total price of items in cart.
     *
     * @return int
     */
    public function getTotalPrice() {
        require 'dbms.inc.php';
        $price = 0;

        foreach ($this->items as $item) {
            $res = $mysqli->query("SELECT prezzo FROM articoli WHERE id={$item['id']}");
            $price += $item['quantita'] * $res->fetch_array()[0];
        }

        return $price;
    }

    /**
     * Get the sum of a attribute from cart.
     *
     * @param string $attribute
     * es. $attribute = 'colour'
     *
     * @return int
     */
    public function getAttributeTotal($attribute) {
        $total = 0;

        foreach ($this->items as $item) {
            if (isset($item['attributes'][$attribute])) {
                $total += $item['attributes'][$attribute] * $item['quantita'];
            }
        }

        return $total;
    }

    /**
     * Remove all items from cart.
     */
    public function clear() {
        $this->items = [];
        $this->write();
    }

    /**
     * Check if a item exist in cart.
     *
     * @param string $id
     * @param array  $attributes
     *
     * @return bool
     */
    public function isItemInCart($id) {
        if (isset($this->items[$id])) {
            return true;
        }

        return false;
    }

    /**
     * Add item to cart.
     *
     * @param string $id
     * @param int    $quantity
     * @param array  $attributes
     *
     * @return bool
     */
    public function add($id, $quantity = 1, $attributes = []) {
        $quantity = (preg_match('/^\d+$/', $quantity)) ? $quantity : 1;
        $attributes = (is_array($attributes)) ? array_filter($attributes) : [$attributes];

        if (isset($this->items[$id])) {
            $this->items[$id]['quantita'] += $quantity;

            $this->write();

            return true;
        }

        $this->items[$id] = [
            'id' => $id,
            'quantita' => $quantity,
            'attributes' => $attributes,
        ];

        $this->write();

        return true;
    }

    /**
     * Subtract item from cart.
     *
     * @param string $id
     * @param int    $quantity
     * @param array  $attributes
     *
     * @return bool
     */
    public function sub($id, $quantity = 1, $attributes = []) {
        $quantity = (preg_match('/^\d+$/', $quantity)) ? $quantity : 1;
        $attributes = (is_array($attributes)) ? array_filter($attributes) : [$attributes];

        if (isset($this->items[$id])) {

            if ($this->items[$id]['quantita'] > 1) {
                $this->items[$id]['quantita'] -= $quantity;
                $this->write();
                return true;
            } else {
                return $this->remove($id);
            }
        }

        return false;
    }

    /**
     * Update item quantity.
     *
     * @param string $id
     * @param int    $quantity
     * @param array  $attributes
     *
     * @return bool
     */
    public function update($id, $quantity = 1, $attributes = []) {
        $quantity = (preg_match('/^\d+$/', $quantity)) ? $quantity : 1;

        if ($quantity == 0) {
            $this->remove($id, $attributes);

            return true;
        }

        if (isset($this->items[$id])) {
            $this->items[$id]['quantita'] = $quantity;
            $this->items[$id]['attributes'] = $attributes;

            $this->write();

            return true;
        }

        return false;
    }

    /**
     * Remove item from cart.
     *
     * @param string $id
     * @param array  $attributes
     *
     * @return bool
     */
    public function remove($id) {
        if (!isset($this->items[$id])) {
            return false;
        }

        unset($this->items[$id]);

        $this->write();

        return true;
    }

    /**
     * Destroy cart session.
     */
    public function destroy() {
        $this->items = [];

        if ($this->useDB) {
            require "dbms.inc.php";
            $mysqli->query("DELETE FROM CartItem WHERE id=$this->cartId");
        }

        if ($this->useCookie) {
            setcookie($this->cartId, '', -1);
        } else {
            unset($_SESSION[$this->cartId]);
        }
    }

    /**
     * Read items from cart session.
     */
    private function read() {
        if ($this->useDB) {
            require "dbms.inc.php";

            $result = $mysqli->query("SELECT * FROM CartItem WHERE cart_id=$this->cartId");

            for ($i = 0; $i < $result->num_rows; $i++) {
                $data = $result->fetch_assoc();
                $this->items[$data['item_id']] = [
                    'id' => $data['item_id'],
                    'quantita' => $data['quantity'],
                    'attributes' => [],
                ];
            }

            $this->cookieToDB();

            return;
        }

        if ($this->useCookie) {
            if (isset($_COOKIE[$this->cartId])) {
                $this->items = json_decode($_COOKIE[$this->cartId], true);
            } else {
                $this->items = json_decode('[]', true);
            }

            return;
        }

        // default
        if (isset($_SESSION[$this->cartId])) {
            $this->items = json_decode($_SESSION[$this->cartId], true);
        } else {
            $this->items = json_decode('[]', true);
        }
    }

    /**
     * Write changes into cart session.
     */
    private function write() {
        if ($this->useDB) {
            require "dbms.inc.php";

            $mysqli->query("DELETE FROM CartItem WHERE cart_id=$this->cartId");
            foreach ($this->items as $item) {
                $mysqli->query("INSERT INTO CartItem(cart_id, item_id, quantity) VALUES ({$this->cartId}, {$item['id']}, {$item['quantita']})");
            }

            return;
        }

        if ($this->useCookie) {
            setcookie($this->cartId, json_encode(array_filter($this->items)), time() + 604800);

            return;
        }

        // default
        $_SESSION[$this->cartId] = json_encode(array_filter($this->items));
    }

    /*
     * transform every cookie to a DB entry
     */

    private function cookieToDB() {
        if (isset($_COOKIE["cart"])) {
            $items = json_decode($_COOKIE["cart"], true);

            foreach ($items as $item) {
                $this->add($item['id'], $item['quantita']);
            }

            unset($_COOKIE["cart"]);
            setcookie("cart", null, -1);
        }
    }

}
