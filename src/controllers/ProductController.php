<?php

require_once __DIR__ . '/../models/Product.php';

class ProductController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        // Instantiate product object
        $product = new Product($this->db);

        $search_query = isset($_GET['q']) ? $_GET['q'] : '';

        if (!empty($search_query)) {
            $products = $product->search($search_query);
        } else {
            // Query products
            $products = $product->read(); // This returns the mysqli_result
        }

        // Load the view and pass the products
        require __DIR__ . '/../views/products/index.php';
    }

    // Other methods like show(), create(), store(), edit(), update(), destroy() will go here
}
