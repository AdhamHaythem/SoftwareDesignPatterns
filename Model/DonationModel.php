<?php

require_once 'UserModel.php';

class Donation {
    private  static int $counter =1;
    private float $amount;
    private int $donationID;
    private int $donorID;

    public function __construct(float $amount, int $donationID, int $donorID) {
        $this->amount = $amount;
        $this->donationID = $this->counter;
        $this->donorID = $donorID;
        $this->counter++;
    }

    // Getter and Setter Methods
    public function amountPaid(float $amount): float {
        $this->amount += $amount;
        return $this->amount;
    }

    public function getDonorID(): int { 
        return $this->donorID;
    }

    public function getDonationID(): int {
        return $this->donationID;
    }

    public function getAmount(): float {
        return $this->amount;
    }

    public function setAmount(float $amount): void {
        $this->amount = $amount;
    }

    // CRUD Operations

    // Create
    public static function create(Donation $donation): bool {
        $dbConnection = UserModel::getDatabaseConnection();
        $sql = "INSERT INTO donations (donationID, donorID, amount) 
                VALUES (:donationID, :donorID, :amount)";
        $params = [
            ':donationID' => $donation->getDonationID(),
            ':donorID' => $donation->getDonorID(),
            ':amount' => $donation->getAmount()
        ];
        return $dbConnection->execute($sql, $params);
    }

    // Read
    public static function retrieve(int $donationID): ?Donation {
        $dbConnection = UserModel::getDatabaseConnection();
        $sql = "SELECT * FROM donations WHERE donationID = :donationID";
        $params = [':donationID' => $donationID];
        $result = $dbConnection->query($sql, $params);

        if ($result) {
            return new Donation(
                $result['amount'],
                $result['donationID'],
                $result['donorID']
            );
        }
        return null;
    }

    // Update
    public static function update(Donation $donation): bool {
        $dbConnection = UserModel::getDatabaseConnection();
        $sql = "UPDATE donations SET 
                    amount = :amount,
                    donorID = :donorID
                WHERE donationID = :donationID";
        $params = [
            ':amount' => $donation->getAmount(),
            ':donorID' => $donation->getDonorID(),
            ':donationID' => $donation->getDonationID()
        ];
        return $dbConnection->execute($sql, $params);
    }

    // Delete
    public static function delete(int $donationID): bool {
        $dbConnection = UserModel::getDatabaseConnection();
        $sql = "DELETE FROM donations WHERE donationID = :donationID";
        $params = [':donationID' => $donationID];
        return $dbConnection->execute($sql, $params);
    }
}

?>
