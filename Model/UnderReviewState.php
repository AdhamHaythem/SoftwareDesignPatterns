<?php
require_once 'IState.php';
require_once 'InProgressState.php';

class UnderReviewState implements IState {
    public function handle(Donation $donation) {
        $donation->setState(new InProgressState());
    }
}

?>