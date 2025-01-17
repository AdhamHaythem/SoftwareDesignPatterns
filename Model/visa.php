<?php

require_once 'IPaymentStrategy.php';

class Visa implements IPaymentStrategy {
    private const FEE_PERCENTAGE = 0.02; // For example, 2% fee

    public function pay(float $amount): bool {
        $fee = $this->calculateFee($amount);
        $totalAmount = $amount + $fee;
        // Process the Visa payment logic
        return true;
    }

    public function calculateFee(float $amount): float {
        return $amount * self::FEE_PERCENTAGE;
    }
}

?>
