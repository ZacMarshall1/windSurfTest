<?php

class User {
    private $conn;
    private $table_name = "users";

    // Object Properties
    public $id;
    public $username;
    public $email;
    public $password;
    public $created_at;

    /**
     * Constructor with database connection
     * @param $db
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Create a new user
     * @return bool
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET username = ?, email = ?, password = ?";
        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));

        // Hash the password
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

        // Bind parameters
        $stmt->bind_param('sss', $this->username, $this->email, $password_hash);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Find a user by their username
     * @return array|null
     */
    public function findByUsername() {
        $query = "SELECT id, username, password, role FROM " . $this->table_name . " WHERE username = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->username = htmlspecialchars(strip_tags($this->username));

        // Bind username
        $stmt->bind_param('s', $this->username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
}
