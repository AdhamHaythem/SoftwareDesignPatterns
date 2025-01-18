<?php

require_once 'IPaymentStrategy.php';
require_once 'DonorModel.php';
require_once 'TransactionsIterator.php';
require_once 'SpecificTransactionsIterator.php';
require_once 'Visa.php'; 

// Payment Admin -> For controlling transactions, processing payments, and refundsSSSS OF VISAA

class PaymentAdmin {
    private TransactionsIterator $transactions;
    private SpecificTransactionsIterator $visaTransactions;
    private SpecificTransactionsIterator $cashTransactions;
    private static int $transactionCounter = 0;

    public function processPayment(Donor $donor, float $amount): bool {
        $paymentStrategy = $donor->getPaymentMethod();

        if ($paymentStrategy === null) {
            throw new RuntimeException("Donor has not set a payment method.");
        }

        $success = $paymentStrategy->pay($amount);
        $transactionID = self::$transactionCounter++;

        foreach ($this->transactions as $key => $trans) {
            if ($trans['transactionID'] === $transactionID) {
                $transaction = $trans;
                $transactionKey = $key;
                break;
            }
        }

        // Log the transactionnnnnnn
        $transaction = [
            'transactionID' => $transactionID,
            'donorID' => $donor->getDonorID(),
            'strategy' => get_class($paymentStrategy),
            'amount' => $amount,
            'status' => $success ? 'success' : 'failed',
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        return $success;
    }

    public function getTransactions(): TransactionsIterator {
        return $this->transactions;
    }

    public function getVisaTransactions(): SpecificTransactionsIterator {
        return $this->visaTransactions;
    }

    public function getCashTransactions(): SpecificTransactionsIterator {
        return $this->cashTransactions;
    }

    public function getTransactionsByDonor(int $donorID): TransactionsIterator {
        $filteredTransactions = [];

        // Iterate through the TransactionsIterator
        foreach ($this->transactions as $transaction) {
            if ($transaction['donorID'] === $donorID) {
                $filteredTransactions[] = $transaction;
            }
        }
        // Return a new TransactionsIterator with the filtered transactions
        return new TransactionsIterator($filteredTransactions);
    }
    


    public function getTransactionByID(int $transactionID): mixed {
        return $this->transactions[$transactionID] ?? null;
    }

    public function clearTransactions(): void {
        $this->transactions->clear();
        $this->visaTransactions->clear();
        $this->cashTransactions->clear();
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
        $transaction = null;
    
        //bn- Iterate through the TransactionsIterator to find the transactionnn
        foreach ($this->transactions as $key => $trans) {
            if ($trans['transactionID'] === $transactionID) {
                $transaction = $trans;
                $transactionKey = $key;
                break;
            }
        }
    
        if ($transaction === null) {
            throw new InvalidArgumentException("Transaction ID not found.");
        }
    
        if ($transaction['strategy'] !== 'Visa') {
            throw new RuntimeException("Refunds are only allowed for Visa transactions.");
        }
    
        if ($transaction['status'] !== 'success') {
            throw new RuntimeException("Cannot refund a failed transaction.");
        }
    
        $paymentStrategy = new Visa();
        $fee = $paymentStrategy->calculateFee($transaction['amount']);
        $refundAmount = $transaction['amount'] - $fee;
        $this->transactions[$transactionKey]['status'] = 'refunded';
        
        return true;
    }

}

?>