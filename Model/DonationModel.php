<?php

require_once 'UserModel.php';
require_once 'UnderReviewState.php';

class Donation {
    private static int $counter = 1;
    private float $amount;
    private int $donationID;
    private int $donorID;
    private ?float $previousAmount = null;
    private IState $state;

    private DateTime $date;
    public function __construct(float $amount, int $donorID, DateTime $date, int $donationID = 0) {
        $this->amount = $amount;
        $this->donationID = $donationID === 0 ? Donation::useCounter() : $donationID;
        $this->donorID = $donorID;
        $this->date = $date;
        $this->state = new UnderReviewState();
    }
    public function setAmount(float $amount): void {
        $this->previousAmount = $this->amount; 
        $this->amount = $amount;
    }
    public function amountPaid(float $amount): float {
        $this->previousAmount = $this->amount;
        $this->amount += $amount;
        return $this->amount;
    }

    private static function useCounter(): int {
        $ID = self::$counter;
        self::$counter++;
        $db_connection = DatabaseConnection::getInstance();
        $sql = "UPDATE counters SET UserID = ? where CounterID = 1";
        $params = [self::$counter];
        $db_connection->execute($sql, $params);
        return $ID;
    }

    public static function setCounter(int $counter): void {
        self::$counter = $counter;
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
    }

    public function handleChange(): void {
        $this->state->handle($this);
    }

    public function setDate(DateTime $date): void {
        $this->date = $date;
    }
    


    public function getDate(): DateTime {
        return $this->date;
    }

    public function getPreviousAmount(): ?float {
       // echo "Previous Amount: {$this->previousAmount}\n";
        return $this->previousAmount; 
    }

    // CRUD Operations

    // Create
    public static function create($donation): bool {
        if (!$donation instanceof Donation) {
            throw new InvalidArgumentException("Expected instance of Donation");
        }
    
        $dbConnection = DatabaseConnection::getInstance();
    
        try {
            $sql = "INSERT INTO donation (donationID, donorID, amount, donation_date) 
                    VALUES (?, ?, ?, ?)";
            $params = [
                $donation->getDonationID(),
                $donation->getDonorID(),
                $donation->getAmount(),
                $donation->getDate()->format('Y-m-d H:i:s')
            ];
            if (!$dbConnection->execute($sql, $params)) {
                throw new Exception("Failed to insert into `donations` table.");
            }
            return true;
    
        } catch (Exception $e) {
            error_log("Error creating donation: " . $e->getMessage());
            return false;
        }
    }
    

    // Read
    public static function retrieve(int $donationID): ?Donation {
        $dbConnection = DatabaseConnection::getInstance();
        $sql = "SELECT * FROM donation WHERE donationID = ?";
        $params = [$donationID];
        $results = $dbConnection->query($sql, $params);
        if (!empty($results)) {
            $result = $results[0];
            return new Donation(
                (float) $result['amount'],               // amount
                (int) $result['donorID'],                // donorID
                new DateTime($result['donation_date']),  // date
                (int) $result['donationID']              // donationID
            );
        }
        return null; // Return null if no result found
    }
    
    

    // Update
    public static function update(Donation $donation): bool {
        $dbConnection = DatabaseConnection::getInstance();
    
        // SQL query to update the donation record
        $sql = "UPDATE donation SET 
                    amount = ?, 
                    donorID = ?,
                    donation_date = ?
                WHERE donationID = ?";
        
        // Positional parameters in the correct order
        $params = [
            $donation->getAmount(),    // New amount
            $donation->getDonorID(),   // Updated donorID
            $donation->getDate()->format('Y-m-d H:i:s'), // Updated date
            $donation->getDonationID() // The specific donation to update
        ];
    
        try {
            // Execute the query and return success/failure
            return $dbConnection->execute($sql, $params);
        } catch (Exception $e) {
            // Log the error for debugging purposes
            error_log("Error updating donation: " . $e->getMessage());
            return false;
        }
    }

    // Delete
    public static function delete(int $donationID): bool {
        $dbConnection = DatabaseConnection::getInstance();
        $sql = "DELETE FROM donation WHERE donationID = ?";
        $params = [$donationID];
        return $dbConnection->execute($sql, $params);
    }
}

?>
