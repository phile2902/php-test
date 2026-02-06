<?php
/**
 * Task 1: Order Management System
 * 
 * Order class with properties: id, customerName, items, totalPrice, status
 */
class Order {
    private static int $idCounter = 1;
    
    public int $id;
    public string $customerName;
    public array $items = [];
    public float $totalPrice = 0;
    public string $status = 'pending'; // pending, shipped, delivered
    
    public function __construct(string $customerName) {
        $this->id = self::$idCounter++;
        $this->customerName = $customerName;
    }
    
    /**
     * Add an item to the order
     */
    public function addItem(string $item, float $price): void {
        $this->items[] = $item;
        $this->totalPrice += $price;
    }
    
    /**
     * Change order status
     */
    public function changeStatus(string $status): void {
        $validStatuses = ['pending', 'shipped', 'delivered'];
        if (!in_array($status, $validStatuses)) {
            throw new InvalidArgumentException("Invalid status: $status");
        }
        $this->status = $status;
    }
    
    /**
     * Print order details
     */
    public function printDetails(): void {
        echo "Order ID: {$this->id}\n";
        echo "Customer: {$this->customerName}\n";
        echo "Items: " . implode(', ', $this->items) . "\n";
        echo "Total Price: $" . number_format($this->totalPrice, 2) . "\n";
        echo "Status: {$this->status}\n";
    }
}
