<?php

require_once 'PaymentAdmin.php';
require_once 'DonorModel.php';

class PaymentController {
    private PaymentAdmin $paymentAdmin;

    public function __construct(PaymentAdmin $paymentAdmin) {
        $this->paymentAdmin = $paymentAdmin;
    }

    // Handle payment processing for a donor
    public function handleProcessPayment(Donor $donor, float $amount): void {
        try {
            if ($amount <= 0) {
                throw new RuntimeException("Payment amount must be greater than zero.");
            }

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

    // Get a summary of all transactions
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

// Create a donor with a payment method
$donor = new Donor();
$donor->setDonorID(1);
$donor->setPaymentMethod(new Visa());

// Process a payment
$paymentController->handleProcessPayment($donor, 100);

// List all transactions
$paymentController->listTransactions();

// Calculate total fees for Visa payments
$paymentController->calculateTotalFees();

?>
