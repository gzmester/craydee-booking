<?php
// allowing all the headers to be sent to the server and return in json, which is easier to work with in javascript
header("Access-Control-Allow-Origin: *"); // Allow all origins
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Allow specific methods
header("Access-Control-Allow-Headers: Content-Type"); // Allow specific headers
header('Content-Type: application/json');
error_reporting(E_ALL); // displaying errors if any
require '../database.php';
require 'userApi.php';
require 'notificationApi.php';
// This class will handle the API responses and error messages

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
        http_response_code(404);
        echo "Not Found";
    }
}

// Initialize router and database classes
$router = new Router();
$database = new Database();

$userApi = new userApi($database); // Pass database connection to userApi
$notificationApi = new notificationApi($database); // Pass database connection to notificationApi, this class handles sending out emails / notifications to users and staff
// Add routes for different functionalities

$router->addRoute('GET', '/test', function ()  {
  echo json_encode(array('message' => 'API is working'));   
});


#********** http://localhost/craydee-booking/api/v1/router.php/login **********#
$router->addRoute('POST', '/login', function () use ($userApi) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['username'], $data['password'])) {
        echo json_encode(array('success' => false, 'message' => 'Username and password are required'));
    }

    $username = $data['username'];
    $password = $data['password'];

    if ($userApi->login($username, $password)) {
        echo json_encode(array('success' => true, 'message' => 'Login successful'));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Login failed'));
    }
});

$router->addRoute('POST', '/register', function () use ($userApi) {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data['password'] !== $data['confirm_password']) {
        echo json_encode(array('success' => false, 'message' => 'Passwords do not match'));
    }

    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];
    //$csrf_token = $data['csrf_token'];

    // Register the user
    $result = $userApi->register($username, $email, $password);

    if ($result) {
         echo json_encode(array('success' => true, 'message' => 'Registration successful'));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Registration failed'));
    }
});


#********** http://localhost/craydee-booking/api/v1/router.php/logout **********#
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
