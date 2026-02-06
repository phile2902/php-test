<?php
/**
 * Task 2: MySQL & PHP
 * 
 * Script to connect MySQL, create customers table, insert data and fetch.
 */

// ============================================
// 1. DATABASE CONNECTION
// ============================================
$host = 'localhost';
$username = 'root';
$password = 'root';
$database = 'php_test';

// Connect to MySQL
$conn = new mysqli($host, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$conn->query("CREATE DATABASE IF NOT EXISTS `$database`");
$conn->select_db($database);

echo "✓ Connected to MySQL successfully\n\n";

// ============================================
// 2. CREATE TABLE
// ============================================
$createTableSQL = "
CREATE TABLE IF NOT EXISTS customers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($createTableSQL)) {
    echo "✓ Table 'customers' ready\n\n";
} else {
    die("Error creating table: " . $conn->error);
}

// ============================================
// 3. INSERT SAMPLE CUSTOMERS
// ============================================
echo "Inserting sample customers...\n";

$customers = [
    ['name' => 'Alice Johnson', 'email' => 'alice@example.com'],
    ['name' => 'Bob Smith', 'email' => 'bob@example.com'],
    ['name' => 'Charlie Brown', 'email' => 'charlie@example.com'],
];

foreach ($customers as $customer) {
    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO customers (name, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $customer['name'], $customer['email']);
    
    if ($stmt->execute()) {
        echo "  ✓ Inserted: {$customer['name']}\n";
    } else {
        // Email already exists (UNIQUE constraint)
        if ($conn->errno === 1062) {
            echo "  - Skipped (already exists): {$customer['email']}\n";
        } else {
            echo "  ✗ Error: " . $stmt->error . "\n";
        }
    }
    $stmt->close();
}

echo "\n";

// ============================================
// 4. FETCH ALL CUSTOMERS
// ============================================
echo "All customers:\n";
echo str_repeat("-", 60) . "\n";

$result = $conn->query("SELECT * FROM customers ORDER BY id");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "ID: {$row['id']} | Name: {$row['name']} | Email: {$row['email']} | Created: {$row['created_at']}\n";
    }
} else {
    echo "No customers found.\n";
}

echo "\n";

// ============================================
// 5. BONUS: getCustomerByEmail function
// ============================================
function getCustomerByEmail($conn, $email) {
    $stmt = $conn->prepare("SELECT * FROM customers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();
    $stmt->close();
    return $customer;
}

// Test function
echo "Bonus - getCustomerByEmail('alice@example.com'):\n";
echo str_repeat("-", 60) . "\n";

$customer = getCustomerByEmail($conn, 'alice@example.com');
if ($customer) {
    print_r($customer);
} else {
    echo "Customer not found.\n";
}

// ============================================
// CLOSE CONNECTION
// ============================================
$conn->close();
echo "\n✓ Done!\n";
