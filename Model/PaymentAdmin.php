<?php

require_once 'IPaymentStrategy.php';
require_once 'DonorModel.php';

// Payment Admin -> For controlling transactions, processing payments, and refundsSSSS OF VISAA

class PaymentAdmin {
    private array $transactions = [];
    private static int $transactionCounter = 0;

    public function processPayment(Donor $donor, float $amount): bool {
        $paymentStrategy = $donor->getPaymentMethod();

        if ($paymentStrategy === null) {
            throw new RuntimeException("Donor has not set a payment method.");
        }

        $success = $paymentStrategy->pay($amount);
        $transactionID = self::$transactionCounter++;

        // Log the transaction
        $this->transactions[$transactionID] = [
            'transactionID' => $transactionID,
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


    public function getTransactionsByDonor(int $donorID): array {
        return array_filter($this->transactions, function ($transaction) use ($donorID) {
            return $transaction['donorID'] === $donorID;
        });
    }

    public function getTransactionByID(int $transactionID): ?array {
        return $this->transactions[$transactionID] ?? null;
    }

    public function clearTransactions(): void {
        $this->transactions = [];
    }

    public function calculateTotalFees(): float {
        $totalFees = 0;

        foreach ($this->transactions as $transaction) {
            if ($transaction['status'] === 'success' && $transaction['strategy'] === 'Visa') {
                $paymentStrategy = new Visa();
                $totalFees += $paymentStrategy->calculateFee($transaction['amount']);
            }
        }

        return $totalFees;
    }

    public function refundTransaction(int $transactionID): bool {
        if (!isset($this->transactions[$transactionID])) {
            throw new InvalidArgumentException("Transaction ID not found.");
        }

        $transaction = $this->transactions[$transactionID];

        if ($transaction['strategy'] !== 'Visa') {
            throw new RuntimeException("Refunds are only allowed for Visa transactions.");
        }

        if ($transaction['status'] !== 'success') {
            throw new RuntimeException("Cannot refund a failed transaction.");
        }
        $paymentStrategy = new Visa();
        $fee = $paymentStrategy->calculateFee($transaction['amount']);
        $refundAmount = $transaction['amount'] - $fee;

        echo "Refunded $" . $refundAmount . " using Visa (after deducting a fee of $" . $fee . ").\n";
        $this->transactions[$transactionID]['status'] = 'refunded';
        return true;
    }
}

?>