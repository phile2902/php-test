<?php
/**
 * Task 1: Test script for Order Management System
 */
require_once __DIR__ . '/../Order.php';
require_once __DIR__ . '/../ExpressOrder.php';

echo "=== Order Management System ===\n\n";

// Test 1: Create an Order, add items, change status, print details
echo "--- Regular Order ---\n";
$order = new Order("John Doe");
$order->addItem("Laptop", 1200.00);
$order->addItem("Mouse", 25.00);
$order->addItem("Keyboard", 75.00);
$order->changeStatus("shipped");
$order->printDetails();

echo "\n";

// Test 2: Create an ExpressOrder, add items, apply express fee, print details
echo "--- Express Order ---\n";
$expressOrder = new ExpressOrder("Jane Smith", 50.00);
$expressOrder->addItem("Phone", 800.00);
$expressOrder->addItem("Case", 20.00);
$expressOrder->printDetails();

echo "\n=== Done ===\n";
