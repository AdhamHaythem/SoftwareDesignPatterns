<?php
require_once 'IState.php';
require_once 'PaidState.php';

class InProgressState implements IState {
    public function handle(Donation $donation) {
        $donation->setState(new PaidState());
    }
}
?>