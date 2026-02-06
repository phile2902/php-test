<?php
/**
 * Task 1: ExpressOrder - subclass of Order
 * 
 * Allows an optional express delivery fee to be added to the total price.
 */
require_once __DIR__ . '/Order.php';

class ExpressOrder extends Order {
    public float $expressFee = 0;
    
    public function __construct(string $customerName, float $expressFee = 0) {
        parent::__construct($customerName);
        $this->expressFee = $expressFee;
        $this->totalPrice += $expressFee;
    }
    
    /**
     * Set express delivery fee
     */
    public function setExpressFee(float $fee): void {
        // Remove old fee, add new fee
        $this->totalPrice -= $this->expressFee;
        $this->expressFee = $fee;
        $this->totalPrice += $fee;
    }
    
    /**
     * Print order details with express fee
     */
    public function printDetails(): void {
        echo "Order ID: {$this->id}\n";
        echo "Customer: {$this->customerName}\n";
        echo "Items: " . implode(', ', $this->items) . "\n";
        echo "Express Fee: $" . number_format($this->expressFee, 2) . "\n";
        echo "Total Price: $" . number_format($this->totalPrice, 2) . "\n";
        echo "Status: {$this->status}\n";
    }
}
