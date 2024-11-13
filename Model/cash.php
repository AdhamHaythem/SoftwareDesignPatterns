<?php

class Cash implements IPaymentStrategy {
    public function pay(float $amount): bool
    {
        return true;
    }
}

?>
