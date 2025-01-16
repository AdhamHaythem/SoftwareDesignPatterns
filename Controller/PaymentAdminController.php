<?php

require_once 'PaymentAdmin.php';
require_once 'Donor.php';

class PaymentController {
    private PaymentAdmin $paymentAdmin;

    public function __construct() {
        $this->paymentAdmin = new PaymentAdmin();
    }

    // Handle processing payment
    public function handleProcessPayment(Donor $donor, float $amount): void {
        try {
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
            echo "Transaction ID: " . $transaction['transactionID'] . ", ";
            echo "Donor ID: " . $transaction['donorID'] . ", ";
            echo "Payment Method: " . $transaction['strategy'] . ", ";
            echo "Amount: " . $transaction['amount'] . ", ";
            echo "Status: " . $transaction['status'] . ", ";
            echo "Timestamp: " . $transaction['timestamp'] . "\\n";
        }
    }

    // List transactions by donor ID
    public function listTransactionsByDonor(int $donorID): void {
        $transactions = $this->paymentAdmin->getTransactionsByDonor($donorID);

        if (empty($transactions)) {
            echo "No transactions recorded for Donor ID: $donorID.\\n";
            return;
        }

        foreach ($transactions as $transaction) {
            echo "Transaction ID: " . $transaction['transactionID'] . ", ";
            echo "Amount: " . $transaction['amount'] . ", ";
            echo "Status: " . $transaction['status'] . ", ";
            echo "Timestamp: " . $transaction['timestamp'] . "\\n";
        }
    }

    // Get a transaction by transaction ID
    public function getTransactionByID(int $transactionID): void {
        $transaction = $this->paymentAdmin->getTransactionByID($transactionID);

        if ($transaction === null) {
            echo "Transaction ID: $transactionID not found.\\n";
            return;
        }

        echo "Transaction Details:\\n";
        echo "Donor ID: " . $transaction['donorID'] . "\\n";
        echo "Payment Method: " . $transaction['strategy'] . "\\n";
        echo "Amount: " . $transaction['amount'] . "\\n";
        echo "Status: " . $transaction['status'] . "\\n";
        echo "Timestamp: " . $transaction['timestamp'] . "\\n";
    }

    // Clear all transactions
    public function clearTransactions(): void {
        $this->paymentAdmin->clearTransactions();
        echo "All transactions have been cleared.\\n";
    }

    // Calculate total fees for Visa payments
    public function calculateTotalFees(): void {
        $totalFees = $this->paymentAdmin->calculateTotalFees();
        echo "Total fees for Visa transactions: $totalFees\\n";
    }

    // Refund a transaction by transaction ID
    public function refundTransaction(int $transactionID): void {
        try {
            $success = $this->paymentAdmin->refundTransaction($transactionID);

            if ($success) {
                echo "Transaction ID: $transactionID has been refunded successfully.\\n";
            }
        } catch (InvalidArgumentException $e) {
            echo "Error: " . $e->getMessage() . "\\n";
        } catch (RuntimeException $e) {
            echo "Error: " . $e->getMessage() . "\\n";
        }
    }
}

// Example usage for handling POST requests
$PaymentAdminController = new PaymentController();

if (isset($_POST['HandlePayment'])) {
    if (!empty($_POST["donorID"]) && !empty($_POST["amount"])) {
        $donor = Donor::retrieve($_POST['donorID']);
        $PaymentAdminController->handleProcessPayment($donor, (float)$_POST['amount']);
    }
}

if (isset($_POST['ListTransactions'])) {
    $PaymentAdminController->listTransactions();
}

if (isset($_POST['ListTransactionsByDonor']) && !empty($_POST['donorID'])) {
    $PaymentAdminController->listTransactionsByDonor((int)$_POST['donorID']);
}

if (isset($_POST['GetTransactionByID']) && !empty($_POST['transactionID'])) {
    $PaymentAdminController->getTransactionByID((int)$_POST['transactionID']);
}

if (isset($_POST['ClearTransactions'])) {
    $PaymentAdminController->clearTransactions();
}

if (isset($_POST['CalculateFees'])) {
    $PaymentAdminController->calculateTotalFees();
}

if (isset($_POST['RefundTransaction']) && !empty($_POST['transactionID'])) {
    $PaymentAdminController->refundTransaction((int)$_POST['transactionID']);
}

?>


