<?php

require_once 'UserModel.php';

class Donation {
    private static int $counter = 1;
    private float $amount;
    private int $donationID;
    private int $donorID;
    private ?float $previousAmount = null;
    private IState $state;

    public function __construct(float $amount, int $donationID = 0, int $donorID) {
        $this->amount = $amount;
        $this->donationID = self::$counter;
        $this->donorID = $donorID;
        $this->state = new UnderReviewState();
        self::$counter++;
    }
    public function setAmount(float $amount): void {
        $this->previousAmount = $this->amount; 
        $this->amount = $amount;
        echo "Setting previous amount to: {$this->previousAmount}\n";
        echo "Donation updated: {$this->amount}\n";
    }
    public function amountPaid(float $amount): float {
        echo "Amount before update: {$this->amount}\n";  
        $this->previousAmount = $this->amount;
        $this->amount += $amount;
        echo "Amount after update: {$this->amount}\n";  
        return $this->amount;
    }


    public static function update(Donation $donation): bool {
        echo "Donation updated: {$donation->getAmount()}\n";
        return true;
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

    //for handling Stateeeeeeeeee
    public function setState(iState $state): void {
        $this->state = $state;
        echo "State changed to " . get_class($state) . "\n";
    }

    public function handleChange(): void {
        $this->state->handle($this);
    }



    public function getPreviousAmount(): ?float {
       // echo "Previous Amount: {$this->previousAmount}\n";
        return $this->previousAmount; 
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
    // public static function update(Donation $donation): bool {
    //     $dbConnection = UserModel::getDatabaseConnection();
    //     $sql = "UPDATE donations SET 
    //                 amount = :amount,
    //                 donorID = :donorID
    //             WHERE donationID = :donationID";
    //     $params = [
    //         ':amount' => $donation->getAmount(),
    //         ':donorID' => $donation->getDonorID(),
    //         ':donationID' => $donation->getDonationID()
    //     ];
    //     return $dbConnection->execute($sql, $params);
    // }

    // Delete
    public static function delete(int $donationID): bool {
        $dbConnection = UserModel::getDatabaseConnection();
        $sql = "DELETE FROM donations WHERE donationID = :donationID";
        $params = [':donationID' => $donationID];
        return $dbConnection->execute($sql, $params);
    }
}

?>
