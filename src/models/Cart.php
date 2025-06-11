<?php

class Cart {
    private $conn;
    private $table_name = "shopping_cart";

    // Object Properties
    public $id;
    public $user_id;
    public $product_id;
    public $quantity;
    public $added_at;

    /**
     * Constructor with database connection
     * @param $db
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Add a product to the cart. If it already exists, update the quantity.
     * @return bool
     */
    public function addToCart() {
        $query = "SELECT id, quantity FROM " . $this->table_name . " WHERE user_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $this->user_id, $this->product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Product exists, add to the existing quantity
            $row = $result->fetch_assoc();
            $new_quantity = $row['quantity'] + $this->quantity;
            return $this->updateQuantity($this->product_id, $new_quantity);
        } else {
            // Product does not exist, insert new record
            $query = "INSERT INTO " . $this->table_name . " (user_id, product_id, quantity) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('iii', $this->user_id, $this->product_id, $this->quantity);
            return $stmt->execute();
        }
    }

    /**
     * Get all items from a user's cart, joining with products table for details.
     * @return mysqli_result
     */
    public function getCartItems() {
        $query = "SELECT p.id as product_id, p.name, p.price, sc.quantity 
                  FROM " . $this->table_name . " sc
                  JOIN products p ON sc.product_id = p.id
                  WHERE sc.user_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $this->user_id);
        $stmt->execute();
        
        return $stmt->get_result();
    }

    /**
     * Update the quantity of a specific item in the cart.
     * @param int $product_id
     * @param int $quantity
     * @return bool
     */
    public function updateQuantity($product_id, $quantity) {
        $query = "UPDATE " . $this->table_name . " SET quantity = ? WHERE user_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('iii', $quantity, $this->user_id, $product_id);
        return $stmt->execute();
    }

    /**
     * Remove an item from the cart.
     * @param int $product_id
     * @return bool
     */
    public function removeItem($product_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE user_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ii', $this->user_id, $product_id);
        return $stmt->execute();
    }

    /**
     * Clear all items from a user's cart.
     * @return bool
     */
    public function clearCart() {
        $query = "DELETE FROM " . $this->table_name . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $this->user_id);
        return $stmt->execute();
    }
}
