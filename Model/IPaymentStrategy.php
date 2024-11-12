<?php

interface IPaymentStrategy {
    public function pay(float $amount): bool;
}

?>
