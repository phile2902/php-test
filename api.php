<?php
/**
 * Task 3: Simple REST API to manage products
 * 
 * Endpoints:
 * - GET /products      → Returns JSON list of products
 * - POST /products     → Accepts JSON (name, price), adds new product
 * - GET /products/{id} → Returns details of a single product
 */

header('Content-Type: application/json');

// Store products in array (no database)
$products = [
    ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// Get request method and path
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove script name from path (e.g., /api.php/products -> /products)
$path = preg_replace('#^/api\.php#', '', $path);
$path = trim($path, '/');
$segments = $path ? explode('/', $path) : [];

// Route: GET /products
if ($method === 'GET' && count($segments) === 1 && $segments[0] === 'products') {
    echo json_encode($products);
    exit;
}

// Route: GET /products/{id}
if ($method === 'GET' && count($segments) === 2 && $segments[0] === 'products') {
    $id = (int) $segments[1];
    
    foreach ($products as $product) {
        if ($product['id'] === $id) {
            echo json_encode($product);
            exit;
        }
    }
    
    // Product not found
    http_response_code(404);
    echo json_encode(['error' => 'Product not found']);
    exit;
}

// Route: POST /products
if ($method === 'POST' && count($segments) === 1 && $segments[0] === 'products') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate input
    if (!$input || !isset($input['name']) || !isset($input['price'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid data: name and price are required']);
        exit;
    }
    
    if (empty($input['name']) || !is_numeric($input['price']) || $input['price'] <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid data: name must be non-empty and price must be positive']);
        exit;
    }
    
    // Create new product
    $newProduct = [
        'id' => count($products) + 1,
        'name' => $input['name'],
        'price' => (float) $input['price']
    ];
    
    $products[] = $newProduct;
    
    http_response_code(201);
    echo json_encode($newProduct);
    exit;
}

// No route matched
http_response_code(404);
echo json_encode(['error' => 'Not found']);
