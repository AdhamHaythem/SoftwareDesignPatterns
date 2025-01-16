<?php
require_once 'IPaymentStrategy.php';
require_once 'DonorModel.php';

//Payment Admin -> For controlling transactions and process Payments

class PaymentAdmin{
    private array $transactions = [];


    public function processPayment(Donor $donor, float $amount): bool {
        $paymentStrategy = $donor->getPaymentMethod();

        if ($paymentStrategy === null) {
            throw new RuntimeException("Donor has not set a payment method.");
        }

        $success = $paymentStrategy->pay($amount);

        // Log the transaction
        $this->transactions[] = [
            'donorID' => $donor->getDonorID(),
            'strategy' => get_class($paymentStrategy),
            'amount' => $amount,
            'status' => $success ? 'success' : 'failed',
            'timestamp' => date('Y-m-d H:i:s')
        ];

        return $success;
    }

    public function getTransactions(): array {
        return $this->transactions;
    }

    public function calculateTotalFees(): float {
        $totalFees = 0;

        foreach ($this->transactions as $transaction) {
            if ($transaction['status'] === 'success' && $transaction ['strategy'] === 'Visa') {
                $paymentStrategy = new Visa();
                $totalFees += $paymentStrategy->calculateFee($transaction['amount']);
            }
        }

        return $totalFees;
    }
}




?>