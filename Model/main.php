
<?php
include 'DonationModel.php';
class ConcreteDonation extends Donation {
    public function amountPaid(float $amount): float {
        $this->setAmount($this->getAmount() + $amount);
        return $this->getAmount();
    }
}

function main() {
    $donation1 = new ConcreteDonation(100.0, 1);
    $donation2 = new ConcreteDonation(50.0, 2);
    $donation3 = new ConcreteDonation(200.0, 3);

    echo "Donation 1 ID: " . $donation1->getDonationID() . ", Amount: " . $donation1->getAmount() . "\n";
    echo "Donation 2 ID: " . $donation2->getDonationID() . ", Amount: " . $donation2->getAmount() . "\n";
    echo "Donation 3 ID: " . $donation3->getDonationID() . ", Amount: " . $donation3->getAmount() . "\n";

    // Demonstrate updating the amount
    $donation1->amountPaid(25.0);
    echo "After additional payment, Donation 1 Amount: " . $donation1->getAmount() . "\n";
}

main();

?>
