# PHP Developer Test - Solution

## Overview

This project implements 3 tasks demonstrating PHP skills:
- **Task 1**: Object-Oriented PHP (Order Management System)
- **Task 2**: MySQL & PHP (Database operations)
- **Task 3**: REST API Development (Product API)

---

## Requirements

- **PHP**: 8.3.24 (minimum 8.0+)
- **MySQL**: 9.4.0 (minimum 5.7+)
- **OS**: macOS / Linux / Windows
- **Terminal**: bash/zsh for running test scripts

---

## Project Structure

```
php-test/
├── Order.php           # Task 1: Order class
├── ExpressOrder.php    # Task 1: ExpressOrder subclass
├── database.php        # Task 2: MySQL script
├── api.php             # Task 3: REST API
├── tests/
│   ├── order_test.php  # Task 1: Test script
│   └── api_test.sh     # Task 3: API test script
└── README.md           # Original problem statement
```

---

## Task 1: Object-Oriented PHP

### What it does
A simple Order Management System using OOP principles.

### Files
- `Order.php` - Main Order class with properties: id, customerName, items, totalPrice, status
- `ExpressOrder.php` - Subclass that adds express delivery fee
- `tests/order_test.php` - Test script demonstrating usage

### How to run
```bash
php tests/order_test.php
```

### Expected output
- Creates a regular order with items and changes status
- Creates an express order with express delivery fee
- Prints details of both orders

---

## Task 2: MySQL & PHP

### What it does
Connects to MySQL, creates a `customers` table, inserts sample data, and fetches all customers.

### Files
- `database.php` - Single script handling all database operations

### Database configuration
Edit these variables in `database.php` if needed:
```php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'php_test';
```

### How to run
```bash
# Make sure MySQL is running first
php database.php
```

### What it creates
- Database: `php_test`
- Table: `customers` (id, name, email, created_at)
- Sample data: 3 customers (Alice, Bob, Charlie)

### Bonus feature
`getCustomerByEmail($conn, $email)` - Function to find customer by email

---

## Task 3: REST API

### What it does
A simple REST API to manage products using an in-memory array (no database).

### Files
- `api.php` - Single file REST API
- `tests/api_test.sh` - Automated test script

### Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /products | List all products |
| GET | /products/{id} | Get single product |
| POST | /products | Create new product (JSON: name, price) |

### How to run

**Step 1: Start PHP built-in server**
```bash
php -S localhost:8080
```

**Step 2: Test with curl (in another terminal)**
```bash
# Get all products
curl http://localhost:8080/api.php/products

# Get single product
curl http://localhost:8080/api.php/products/1

# Create new product
curl -X POST -H "Content-Type: application/json" \
  -d '{"name": "Monitor", "price": 350}' \
  http://localhost:8080/api.php/products
```

### How to run automated tests
```bash
# Start server first (in one terminal)
php -S localhost:8080

# Run tests (in another terminal)
bash tests/api_test.sh
```

---

## Quick Test All Tasks

```bash
# Task 1: OOP
php tests/order_test.php

# Task 2: Database (requires MySQL running)
php database.php

# Task 3: API
php -S localhost:8080 &
sleep 2
bash tests/api_test.sh
pkill -f "php -S localhost:8080"
```

---

## Notes

- Task 1 and Task 3 use in-memory storage (no database required)
- Task 2 requires MySQL to be running
- All code follows PHP best practices with proper error handling
- Each task is independent and can be tested separately
