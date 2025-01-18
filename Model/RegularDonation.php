<?php
require_once 'DonationModel.php';

class RegularDonation extends Donation {

    public function __construct(float $amount, int $donorID,DateTime $date, int $donationID = 0) {
        parent::__construct($amount, $donorID, $date, $donationID);
    }

    public function amountPaid(float $amount): float {
        $this->setAmount($this->getAmount());
        return $this->getAmount();
    }

    public static function retrieve(int $donationID): ?Donation {
        $dbConnection = DatabaseConnection::getInstance();
        $sql = "SELECT * FROM donation WHERE donationID = ?";
        $params = [$donationID];
        $results = $dbConnection->query($sql, $params);
        if (!empty($results)) {
            $result = $results[0];
            return new RegularDonation(
                (float) $result['amount'],               // amount
                (int) $result['donorID'],                // donorID
                new DateTime($result['donation_date']),  // date
                (int) $result['donationID']              // donationID
            );
        }
        return null; // Return null if no result found
    }
}
?>
