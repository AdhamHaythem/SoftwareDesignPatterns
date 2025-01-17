<?php
require_once 'IState.php';

class PaidState implements IState {
    public function handle(Donation $donation) {
       // echo "Donation is paid.\n";
    }
}

?>