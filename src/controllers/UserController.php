<?php

require_once __DIR__ . '/../models/User.php';

class UserController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function registerForm() {
        require __DIR__ . '/../views/users/register.php';
    }

    public function loginForm() {
        require __DIR__ . '/../views/users/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User($this->db);
            $user->username = $_POST['username'];
            $user->email = $_POST['email'];
            $user->password = $_POST['password'];

            if ($user->create()) {
                set_flash_message('Registration successful. Please log in.', 'success');
                header("Location: " . base_url('login'));
                exit();
            } else {
                set_flash_message('Error: Could not register user.', 'danger');
                header("Location: " . base_url('register'));
                exit();
            }
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User($this->db);
            $user->username = $_POST['username'];
            $password = $_POST['password'];

            $user_data = $user->findByUsername();

            if ($user_data && password_verify($password, $user_data['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user_data['id'];
                $_SESSION['username'] = $user_data['username'];
                $_SESSION['role'] = $user_data['role'];

                // Redirect to the products page
                set_flash_message('Welcome back, ' . htmlspecialchars($user_data['username']) . '!', 'success');
                header("Location: " . base_url('products'));
                exit();
            } else {
                set_flash_message('Invalid username or password.', 'danger');
                header("Location: " . base_url('login'));
                exit();
            }
        }
    }

    public function logout() {
        // Unset all of the session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        // Restart session to store flash message
        session_start();
        set_flash_message('You have been logged out successfully.', 'success');

        // Redirect to home page
        header("Location: " . base_url('/'));
        exit();
    }
}
