<?php

require_once 'PaymentAdmin.php';
require_once 'Donor.php';

class PaymentController {
    private PaymentAdmin $paymentAdmin;

    public function __construct() {
        $this->paymentAdmin = new PaymentAdmin();
    }

    // Handle processing payment
    public function handleProcessPayment(Donor $donor, float $amount): bool {
        try {
            return $this->paymentAdmin->processPayment($donor, $amount);
        } catch (RuntimeException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    // List all transactions
    public function listTransactions(): MyIterator {
        return $this->paymentAdmin->getTransactions();
    }
    public function listVisaTransactions(): MyIterator {
        return $this->paymentAdmin->getVisaTransactions();
    }
    public function listCashTransactions(): MyIterator {
        return $this->paymentAdmin->getCashTransactions();
    }

    // List transactions by donor ID
    public function listTransactionsByDonor(int $donorID): MyIterator {
        return $this->paymentAdmin->getTransactionsByDonor($donorID);
    }

    // Get a transaction by transaction ID
    public function getTransactionByID(int $transactionID): ?array {
        return $this->paymentAdmin->getTransactionByID($transactionID);
    }

    // Clear all transactions
    public function clearTransactions(): void {
        $this->paymentAdmin->clearTransactions();
    }

    // Calculate total fees for Visa payments
    public function calculateTotalFees(): float {
        return $this->paymentAdmin->calculateTotalFees();
    }

    // Refund a transaction by transaction ID
    public function refundTransaction(int $transactionID): bool {
        try {
            return $this->paymentAdmin->refundTransaction($transactionID);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage());
        } catch (RuntimeException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}

// Example usage for handling POST requests
$PaymentAdminController = new PaymentController();

if (isset($_POST['HandlePayment'])) {
    if (!empty($_POST["donorID"]) && !empty($_POST["amount"])) {
        $donor = Donor::retrieve($_POST['donorID']);
        $success = $PaymentAdminController->handleProcessPayment($donor, (float)$_POST['amount']);
        // Handle success or failure in the caller
    }
}

if (isset($_POST['ListTransactions'])) {
    $transactions = $PaymentAdminController->listTransactions();
    // Handle transactions array in the caller
}

if (isset($_POST['ListSpecificTransactions'])) {
    if (isset($_POST['Visa'])) {
        $transactions = $PaymentAdminController->listVisaTransactions();
    } else if (isset($_POST['Cash'])) {
        $transactions = $PaymentAdminController->listCashTransactions();
    }
}

if (isset($_POST['ListCashTransactions'])) {
    $transactions = $PaymentAdminController->listCashTransactions();
    // Handle transactions array in the caller
}

if (isset($_POST['ListTransactionsByDonor']) && !empty($_POST['donorID'])) {
    $transactions = $PaymentAdminController->listTransactionsByDonor((int)$_POST['donorID']);
    // Handle transactions array in the caller
}

if (isset($_POST['GetTransactionByID']) && !empty($_POST['transactionID'])) {
    $transaction = $PaymentAdminController->getTransactionByID((int)$_POST['transactionID']);
    // Handle transaction array in the caller
}

if (isset($_POST['ClearTransactions'])) {
    $PaymentAdminController->clearTransactions();
    // Handle confirmation in the caller
}

if (isset($_POST['CalculateFees'])) {
    $totalFees = $PaymentAdminController->calculateTotalFees();
    // Handle total fees in the caller
}

if (isset($_POST['RefundTransaction']) && !empty($_POST['transactionID'])) {
    $success = $PaymentAdminController->refundTransaction((int)$_POST['transactionID']);
    // Handle success or failure in the caller
}

?>
