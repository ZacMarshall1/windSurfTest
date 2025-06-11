<?php

require_once __DIR__ . '/../models/Cart.php';

class CartController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit();
        }
    }

    public function index() {
        $this->checkAuth();
        $user_id = $_SESSION['user_id'];

        $cart = new Cart($this->db);
        $cart->user_id = $user_id;

        // Fetch cart items
        $cart_items = $cart->getCartItems();
        $total_price = 0;

        // Load the view and pass the cart items
        require __DIR__ . '/../views/cart/index.php';
    }

    public function update() {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cart = new Cart($this->db);
            $cart->user_id = $_SESSION['user_id'];

            if ($cart->updateQuantity($_POST['product_id'], $_POST['quantity'])) {
                set_flash_message('Cart updated successfully.', 'success');
            } else {
                set_flash_message('Failed to update cart.', 'danger');
            }
            header("Location: " . base_url('cart'));
            exit();
        }
    }

    public function remove() {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cart = new Cart($this->db);
            $cart->user_id = $_SESSION['user_id'];

            if ($cart->removeItem($_POST['product_id'])) {
                set_flash_message('Item removed from cart.', 'success');
            } else {
                set_flash_message('Failed to remove item from cart.', 'danger');
            }
            header("Location: " . base_url('cart'));
            exit();
        }
    }

    public function add() {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
                set_flash_message('Invalid request. Missing product information.', 'danger');
                header('Location: ' . base_url('products'));
                exit();
            }

            // Get product_id and quantity from the form
            $product_id = $_POST['product_id'];
            $quantity = (int)$_POST['quantity'];

            // Instantiate Cart object
            $cart = new Cart($this->db);
            $cart->user_id = $_SESSION['user_id'];
            $cart->product_id = $product_id;
            $cart->quantity = $quantity;

            // Add to cart
            if ($cart->addToCart()) {
                set_flash_message('Item added to cart successfully!', 'success');
            } else {
                set_flash_message('Error: Could not add item to cart.', 'danger');
            }
            header("Location: " . base_url('products'));
            exit();
        }
    }
}
