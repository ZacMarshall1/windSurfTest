<?php
session_start();

// Main entry point and router

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/helpers/flash.php';
require_once __DIR__ . '/src/helpers/url_helper.php';
require_once __DIR__ . '/src/controllers/ProductController.php';
require_once __DIR__ . '/src/controllers/UserController.php';
require_once __DIR__ . '/src/controllers/CartController.php';
require_once __DIR__ . '/src/controllers/AdminController.php';

// Get database connection
$db = getDBConnection();

// Basic routing
$base_path = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');

$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];

// Remove base path from request URI
if ($base_path && strpos($request_uri, $base_path) === 0) {
    $request_uri = substr($request_uri, strlen($base_path));
}

if (empty($request_uri)) {
    $request_uri = '/';
}

switch ($request_uri) {
    case '/':
        // Home page
        require __DIR__ . '/src/views/layouts/header.php';
        echo "<h1>Welcome to the E-Shop</h1>";
        require __DIR__ . '/src/views/layouts/footer.php';
        break;
    case '/products':
        $controller = new ProductController($db);
        $controller->index();
        break;
    case '/register':
        $controller = new UserController($db);
        $controller->registerForm();
        break;
    case '/login':
        $controller = new UserController($db);
        $controller->loginForm();
        break;
    case '/users/register':
        $controller = new UserController($db);
        $controller->register();
        break;
    case '/users/login':
        $controller = new UserController($db);
        $controller->login();
        break;
    case '/logout':
        $controller = new UserController($db);
        $controller->logout();
        break;

    // Admin Routes
    case '/admin/products':
        $controller = new AdminController($db);
        $controller->index();
        break;
    case '/admin/products/create':
        $controller = new AdminController($db);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->create();
        } else {
            $controller->createForm();
        }
        break;
    case '/admin/products/edit':
        $controller = new AdminController($db);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->update();
        } else {
            $controller->editForm();
        }
        break;
    case '/admin/products/delete':
        $controller = new AdminController($db);
        $controller->delete();
        break;
    case '/cart':
        $controller = new CartController($db);
        $controller->index();
        break;
    case '/cart/add':
        $controller = new CartController($db);
        $controller->add();
        break;
    case '/cart/update':
        $controller = new CartController($db);
        $controller->update();
        break;
    case '/cart/remove':
        $controller = new CartController($db);
        $controller->remove();
        break;

    default:
        http_response_code(404);
        echo "<h1>404 Not Found</h1>";
        break;
}
