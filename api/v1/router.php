<?php
// allowing all the headers to be sent to the server and return in json, which is easier to work with in javascript
header('Content-Type: application/json');
error_reporting(E_ALL); // displaying errors if any
require '../database.php';
require 'userApi.php';

// This class will handle the API responses and error messages
class ApiResponse {
    public static function success($message, $data = []) {
        http_response_code(200);
        echo json_encode(array_merge(["message" => $message, "success" => true], $data));
        exit;
    }

    public static function error($message, $code = 400, $data = []) {
        http_response_code($code);
        echo json_encode(array_merge(["message" => $message, "success" => false], $data));
        exit;
    }

    public static function notFound() {
        self::error("Not Found", 404);
    }
}

// Router class handles the API requests
class Router {
    private $routes = [];

    public function addRoute($method, $path, $handler) {
        $this->routes[$method][$path] = $handler;
    }

    public function handleRequest($method, $path) {
        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $routePath => $handler) {
                if (preg_match("#^$routePath$#", $path, $matches)) {
                    array_shift($matches); // Remove the full match from the matches array
                    call_user_func_array($handler, $matches);
                    return;
                }
            }
        }
        // Route not found
        ApiResponse::notFound();
    }
}

// Initialize router and database classes
$router = new Router();
$database = new Database();
$userApi = new userApi($database); // Pass database connection to userApi
$notificationApi = new notificationApi($database); // Pass database connection to notificationApi, this class handles sending out emails / notifications to users and staff
// Add routes for different functionalities

$router->addRoute('GET', '/test', function ()  {
    ApiResponse::success("Test successful");
});

#********** http://localhost/craydee-booking/api/router.php/login **********#
$router->addRoute('POST', '/login', function () use ($userApi) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['username'], $data['password'])) {
        ApiResponse::error("Missing username or password", 400);
    }

    $username = $data['username'];
    $password = $data['password'];

    if ($userApi->login($username, $password)) {
        ApiResponse::success("Login successful");
    } else {
        ApiResponse::error("Login failed", 401);
    }
});

#********** http://localhost/craydee-booking/api/router.php/register **********#
$router->addRoute('POST', '/register', function () use ($userApi) {
    $data = json_decode(file_get_contents('php://input'), true);

    if($data['password'] !== $data['confirm_password']){
        ApiResponse::error("Passwords do not match", 400);
    }

    if (!isset($data['username'], $data['email'], $data['password'])) {
        ApiResponse::error("Missing username, email, or password", 400);
    }

    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];

    if ($userApi->register($username, $email, $password)) {
        ApiResponse::success("Registration successful");
    } else {
        ApiResponse::error("Registration failed", 400);
    }
});

#********** http://localhost/craydee-booking/api/router.php/logout **********#
$router->addRoute('GET', '/logout', function () use ($userApi) {
    $userApi->logout();
});

// Handle the request
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];
$parsedUrl = parse_url($requestUri);
$requestPath = $parsedUrl['path'];

$basePath = 'craydee-booking/api/v1/router.php/'; // Adjust this to the base path of your API
$route = str_replace($basePath, '', $requestPath);
$router->handleRequest($requestMethod, $route);
