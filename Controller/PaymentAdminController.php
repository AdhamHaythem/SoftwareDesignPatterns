<?php

require_once 'PaymentAdmin.php';
require_once 'Donor.php';

class PaymentController {
    private PaymentAdmin $paymentAdmin;

    public function __construct(PaymentAdmin $paymentAdmin) {
        $this->paymentAdmin = $paymentAdmin;
    }

    // Process payment for a donor
    public function handleProcessPayment(
        Donor $donor,
        float $amount
    ): void {
        try {
            if ($amount <= 0) {
                throw new RuntimeException("Payment amount must be greater than zero.");
            }

            // Process the payment through PaymentAdmin
            $success = $this->paymentAdmin->processPayment($donor, $amount);

            if ($success) {
                echo "Payment of $amount processed successfully for Donor ID: " . $donor->getDonorID() . "\\n";
            } else {
                echo "Payment failed for Donor ID: " . $donor->getDonorID() . "\\n";
            }
        } catch (RuntimeException $e) {
            echo "Error: " . $e->getMessage() . "\\n";
        }
    }

    // List all transactions
    public function listTransactions(): void {
        $transactions = $this->paymentAdmin->getTransactions();

        if (empty($transactions)) {
            echo "No transactions recorded.\\n";
            return;
        }

        foreach ($transactions as $transaction) {
            echo "Donor ID: " . $transaction['donorID'] . ", ";
            echo "Payment Method: " . $transaction['strategy'] . ", ";
            echo "Amount: " . $transaction['amount'] . ", ";
            echo "Status: " . $transaction['status'] . ", ";
            echo "Timestamp: " . $transaction['timestamp'] . "\\n";
        }
    }

    // Calculate total fees for Visa payments
    public function calculateTotalFees(): void {
        $totalFees = $this->paymentAdmin->calculateTotalFees();
        echo "Total fees for Visa transactions: $totalFees\\n";
    }
}

// Usage example
$paymentAdmin = new PaymentAdmin();
$paymentController = new PaymentController($paymentAdmin);

// Create a donor
$donor = new Donor(
    1,              // userID
    "john_doe",     // username
    "John",         // firstname
    "Doe",          // lastname
    "john@example.com", // email
    "securepass123", // password
    ["City", "Country"], // location
    1234567890,      // phoneNumber
    new Visa()       // Payment strategy
);

// Process a payment for the donor
$paymentController->handleProcessPayment($donor, 150.0);

// List all transactions
$paymentController->listTransactions();

// Calculate total fees for Visa transactions
$paymentController->calculateTotalFees();
?>

