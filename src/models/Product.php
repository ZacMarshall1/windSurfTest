<?php

class Product {
    private $conn;
    private $table_name = "products";

    // Object Properties
    public $id;
    public $name;
    public $description;
    public $price;
    public $created_at;

    /**
     * Constructor with database connection
     * @param $db
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Read all products from the database
     * @return mysqli_result
     */
    public function read() {
        $query = "SELECT id, name, price, description, created_at FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * Read a single product from the database by its ID.
     * @return array|null
     */
    public function readOne() {
        $query = "SELECT id, name, price, description FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Search for products by keyword.
     * @param string $keywords
     * @return mysqli_result
     */
    public function search($keywords) {
        // Sanitize the keywords
        $keywords = htmlspecialchars(strip_tags($keywords));
        $search_term = "%{$keywords}%"; // Add wildcards for LIKE search

        $query = "SELECT id, name, price, description, created_at 
                  FROM " . $this->table_name . " 
                  WHERE name LIKE ? OR description LIKE ? 
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);

        // Bind the keywords
        $stmt->bind_param("ss", $search_term, $search_term);

        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * Create a new product
     * @return bool
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (name, price, description) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->description = htmlspecialchars(strip_tags($this->description));

        // Bind values
        $stmt->bind_param("sds", $this->name, $this->price, $this->description);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Update an existing product
     * @return bool
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET name = ?, price = ?, description = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind parameters
        $stmt->bind_param('sdsi', $this->name, $this->price, $this->description, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Delete a product
     * @return bool
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind id of record to delete
        $stmt->bind_param('i', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
