<?php

require_once __DIR__ . '/../models/Product.php';

class AdminController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    private function checkAdmin() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            // Redirect non-admins to the home page or show an error
            header('Location: ' . base_url('/'));
            exit();
        }
    }

    public function index() {
        $this->checkAdmin();
        $product = new Product($this->db);
        $products = $product->read();
        require __DIR__ . '/../views/admin/index.php';
    }

    public function createForm() {
        $this->checkAdmin();
        require __DIR__ . '/../views/admin/create.php';
    }

    public function create() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product = new Product($this->db);
            $product->name = $_POST['name'];
            $product->description = $_POST['description'];
            $product->price = $_POST['price'];

            if ($product->create()) {
                set_flash_message('Product created successfully.', 'success');
                header('Location: ' . base_url('admin/products'));
                exit();
            } else {
                set_flash_message('Error creating product.', 'danger');
                header('Location: ' . base_url('admin/products/create'));
                exit();
            }
        }
    }

    public function editForm() {
        $this->checkAdmin();
        $product_model = new Product($this->db);
        $product_model->id = $_GET['id'];
        $product = $product_model->readOne(); // This returns an array
        require __DIR__ . '/../views/admin/edit.php';
    }

    public function update() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product = new Product($this->db);
            $product->id = $_POST['id'];
            $product->name = $_POST['name'];
            $product->description = $_POST['description'];
            $product->price = $_POST['price'];

            if ($product->update()) {
                set_flash_message('Product updated successfully.', 'success');
                header('Location: ' . base_url('admin/products'));
                exit();
            } else {
                set_flash_message('Error updating product.', 'danger');
                header('Location: ' . base_url('admin/products/edit?id=' . $_POST['id']));
                exit();
            }
        }
    }

    public function delete() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product = new Product($this->db);
            $product->id = $_POST['product_id'];

            if ($product->delete()) {
                set_flash_message('Product deleted successfully.', 'success');
            } else {
                set_flash_message('Error deleting product.', 'danger');
            }
            header('Location: ' . base_url('admin/products'));
            exit();
        }
    }

}
