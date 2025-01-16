<?php

require_once 'PaymentAdmin.php';
require_once 'Donor.php';

class PaymentController {

    public function handleProcessPayment(Donor $donor,float $amount): void {

            $paymentAdmin = new PaymentAdmin();
            $success = $this->paymentAdmin->processPayment($donor, $amount);        
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

$PaymentAdminController = new PaymentController();

if(isset($_POST['HandlePayment']))
{
    if(!empty($_POST["donorID"]))
    {
        $donor = Donor::retrieve($_POST['donorID']);
        $PaymentAdminController->handleProcessPayment($donor,$_Post['amount']);
    }
}


?>

