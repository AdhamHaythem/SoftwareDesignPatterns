<?php

require_once 'userModel.php';
require_once 'DonationModel.php';
require_once 'CampaignStrategy.php';
require_once 'IPaymentStrategy.php';
require_once 'ISubject.php';
require_once 'IObserver.php';
require_once 'IEvent.php';
require_once 'DonationUndoCommand.php';

class Donor extends UserModel implements IObserver {
    private static int $counter = 1;
    private int $donorID;
    private array $donationsHistory;
    private float $totalDonations;
    private array $campaignsJoined = [];
    private ?IPaymentStrategy $paymentMethod;
    private ?ISubject $eventData;
    private ?Event $eventStrategy;

    private array $undoStack = [];  // Stack -> undo commands
    private array $redoStack = [];  // Stack -> redo commands
    private ?float $previousAmount = null;
    private ?Donation $donation = null;
    private ?float $previousDonationAmount = null;
    private ?Event $event = null;

    public function __construct(
        string $username,
        string $firstname,
        string $lastname,
        string $email,
        string $password,
        array $location,
        int $phoneNumber,
        IPaymentStrategy $paymentMethod = null,
        Event $event = null,
        int $userID=0

    ) {
        parent::__construct($username, $firstname, $lastname, $email, $password, $location, $phoneNumber,$userID);
        $this->donorID = Donor::useCounter() ;     //lazem yb2a hwa w el user same IDDDDDDDDDDD
        $this->donationsHistory = [];
        $this->totalDonations = 0.0;
        if ($event !== null) {
            $this->campaignsJoined[] = $event;
        }
        $this->paymentMethod = $paymentMethod;
    }


    private static function useCounter(): int {
        $ID = self::$counter;
        self::$counter++;
        $db_connection = DatabaseConnection::getInstance();
        $sql = "UPDATE counters SET DonorID = ? where CounterID = 1";
        $params = [self::$counter];
        $db_connection->execute($sql, $params);
        return $ID;
    }

    public static function setCounter(int $counter): void {
        self::$counter = $counter;
    }

    //WORKEDDDDDDDD WITH DONATION AND AMOUNT AS A PARAMETER

    // public function setCommand(ICommand $command): void {
    //     echo "Executing command...\n";
    //     $command->execute();
    //     $this->undoStack[] = $command;
    //     $this->redoStack = []; // Clear redo stack after a new command
    //     echo "Command executed and added to undo stack. Current undo stack size: " . count($this->undoStack) . "\n";
    // }

    // public function undo(): void {
    //     if (count($this->undoStack) > 0) {
    //         $command = array_pop($this->undoStack);
    //         echo "Undoing last command...\n";
    
    //         if ($command instanceof DonationUndoCommand) {
    //             $redoCommand = new DonationRedoCommand($command->getDonation(), $command->getAmount()); //Reverseeeee and put in  stack
    //         } else {
    //             echo "Command type not recognized for undo.\n";
    //             return;
    //         }
    
    //         $command->execute(); 
    //         $this->redoStack[] = $redoCommand;
    
    //         echo "Undo completed. Undo stack size: " . count($this->undoStack) . ", Redo stack size: " . count($this->redoStack) . "\n";
    //     } else {
    //         echo "Nothing to undo.\n";
    //     }
    // }
    
    // public function redo(): void {
    //     if (count($this->redoStack) > 0) {
    //         $command = array_pop($this->redoStack);
    //         echo "Redoing last undone command...\n";
     
    //         if ($command instanceof DonationRedoCommand) {
    //             $undoCommand = new DonationUndoCommand($command->getDonation(), $command->getAmount()); //Reverseeeee and put in undo stack
    //         } else {
    //             echo "Command type not recognized for redo.\n";
    //             return;
    //         }
    
    //         $command->execute();
    //         $this->undoStack[] = $undoCommand;
    
    //         echo "Redo completed. Undo stack size: " . count($this->undoStack) . ", Redo stack size: " . count($this->redoStack) . "\n";
    //     } else {
    //         echo "Nothing to redo.\n";
    //     }
    // }
    // In the Donor class

    //.....................................................................................................
    public function setEvent(Event $event): void {
        $this->event = $event;
    }

    public function setCommand(ICommand $command): void {
        
        if ($command instanceof DonationUndoCommand) {
            $command->setDonation($this->donation);
            $command->setPreviousAmount($this->previousAmount);
        } elseif ($command instanceof DonationRedoCommand) {
            $command->setDonation($this->donation);
            $command->setNextAmount($this->donation->getAmount());
        } elseif ($command instanceof EventUndoCommand) {
            $command->setDonor($this);
            $command->setEvent($this->event);
        } elseif ($command instanceof EventRedoCommand) {
            $command->setDonor($this);
            $command->setEvent($this->event);
        }

        $this->undoStack[] = $command;
        $this->redoStack = [];
       // echo "Command added to undo stack. Current undo stack size: " . count($this->undoStack) . "\n";
    }

    public function undo(): void {
        if (count($this->undoStack) > 0) {
            $command = array_pop($this->undoStack);
         //   echo "Undoing last command...\n";

            if ($command instanceof DonationUndoCommand) {
                $redoCommand = new DonationRedoCommand();
                $redoCommand->setDonation($this->donation);
                $redoCommand->setNextAmount($this->donation->getAmount());
                $this->redoStack[] = $redoCommand;
            } elseif ($command instanceof EventUndoCommand) {
                $redoCommand = new EventRedoCommand();
                $redoCommand->setDonor($this);
                $redoCommand->setEvent($this->event);
                $this->redoStack[] = $redoCommand;
            }

            $command->execute();
         //   echo "Undo completed. Undo stack size: " . count($this->undoStack) . ", Redo stack size: " . count($this->redoStack) . "\n";
        } else {
         //   echo "Nothing to undo.\n";
        }
    }

    public function redo(): void {
        if (count($this->redoStack) > 0) {
            $command = array_pop($this->redoStack);
        //    echo "Redoing last undone command...\n";

            if ($command instanceof DonationRedoCommand) {
                $undoCommand = new DonationUndoCommand();
                $undoCommand->setDonation($this->donation);
                $undoCommand->setPreviousAmount($this->donation->getAmount());
                $this->undoStack[] = $undoCommand;
            } elseif ($command instanceof EventRedoCommand) {
                $undoCommand = new EventUndoCommand();
                $undoCommand->setDonor($this);
                $undoCommand->setEvent($this->event);
                $this->undoStack[] = $undoCommand;
            }

            $command->execute();
      //      echo "Redo completed. Undo stack size: " . count($this->undoStack) . ", Redo stack size: " . count($this->redoStack) . "\n";
        } else {
       //     echo "Nothing to redo.\n";
        }
    }

    public function addEvent(Event $event): void {
        $this->campaignsJoined[] = $event;
       // echo "Event added: {$event->getName()}\n";
    }

    public function removeEvent(Event $event): void {
        $index = array_search($event, $this->campaignsJoined, true);
        if ($index !== false) {
            array_splice($this->campaignsJoined, $index, 1);
       //     echo "Event removed: {$event->getName()}\n";
        } else {
        //    echo "Event not found in donor's joined events.\n";
        }
    }

    public function getDonation(): ?Donation {
        return $this->donation;
    }

    public function getPreviousDonationAmount(): float {
        return $this->previousAmount ?? 0.0;

    }

    //......................................................................................................

    // public function getPreviousDonationAmount(): ?float {
    //     return $this->previousDonationAmount;  // Return the saved previous donation amount
    // }

    // public function setDonation(Donation $donation): void {
    //     $this->donation = $donation;
    //     $this->previousDonationAmount = $donation->getAmount();  // Save the current amount before any changes
    // }

    // // Get the donation amount
    // public function getDonation(): ?Donation {
    //     return $this->donation;
    // }

    // // Command methods
    // public function setCommand(ICommand $command): void {
    //     $command->setDonor($this);
    //     echo "Executing command...\n";
    //     $command->execute();  // No parameters passed
    //     $this->undoStack[] = $command;
    //     $this->redoStack = [];
    //     echo "Command executed and added to undo stack. Current undo stack size: " . count($this->undoStack) . "\n";
    // }

    // public function undo(): void {
    //     if (count($this->undoStack) > 0) {
    //         $command = array_pop($this->undoStack);
    //         echo "Undoing last command...\n";

    //         if ($command instanceof DonationUndoCommand) {
    //             $redoCommand = new DonationRedoCommand();  // Prepare redo command
    //         } else {
    //             echo "Command type not recognized for undo.\n";
    //             return;
    //         }

    //         $command->execute();  // Execute without parameters
    //         $this->redoStack[] = $redoCommand;
    //         echo "Undo completed. Undo stack size: " . count($this->undoStack) . ", Redo stack size: " . count($this->redoStack) . "\n";
    //     } else {
    //         echo "Nothing to undo.\n";
    //     }
    // }

    // public function redo(): void {
    //     if (count($this->redoStack) > 0) {
    //         $command = array_pop($this->redoStack);
    //         echo "Redoing last undone command...\n";

    //         if ($command instanceof DonationRedoCommand) {
    //             $undoCommand = new DonationUndoCommand();  // Prepare undo command
    //         } else {
    //             echo "Command type not recognized for redo.\n";
    //             return;
    //         }

    //         $command->execute();  // Execute without parameters
    //         $this->undoStack[] = $undoCommand;
    //         echo "Redo completed. Undo stack size: " . count($this->undoStack) . ", Redo stack size: " . count($this->redoStack) . "\n";
    //     } else {
    //         echo "Nothing to redo.\n";
    //     }
    // }
    

    //..................sha8aaaaaaaaaaal bas mn 8er access undo/redo fl donor



    // public function addDonation(Donation $donation): void {
    //     if (count($this->donationsHistory) > 0) {
    //         $this->previousAmount = $this->donationsHistory[count($this->donationsHistory) - 1]->getAmount();
    //     }

    //     $this->donationsHistory[] = $donation;
    // }

    // public function getPreviousAmount(): ?float {
    //     return $this->previousAmount;  // Return the last saved amount
    // }

    // public function getCurrentAmount(): float {
    //     return end($this->donationsHistory)->getAmount();  // Return the last donation amount
    // }

    // public function undo(): void {
    //     if (count($this->undoStack) > 0) {
        
    //         $lastDonation = array_pop($this->undoStack);
    //         $this->redoStack[] = $lastDonation;
    //         echo "Undo: Donation amount set to " . $lastDonation . "\n";
    //     } else {
    //         echo "Nothing to undo.\n";
    //     }
    // }

 
    // public function redo(): void {
    //     if (count($this->redoStack) > 0) {
    //         // Retrieve last undone donation
    //         $redoAmount = array_pop($this->redoStack);
    //         $this->donationsHistory[] = $redoAmount;
    //         echo "Redo: Donation amount set to " . $redoAmount . "\n";
    //     } else {
    //         echo "Nothing to redo.\n";
    //     }
    // }

    // public function getPreviousDonationAmount(): float {
    //     return end($this->donationsHistory); // Get last amount in the history
    // }

    // public function setCommand(ICommand $command): void {
    //     echo "Executing command...\n";
    //     $command->execute(); // Just call execute with no parameters
        
    // }



    public static function create($donor): bool {
        $dbConnection = DatabaseConnection::getInstance();
    
        try {
            $userSql = "INSERT INTO user (userID, username, firstName, lastName, email, password, locationList, phoneNumber, isActive)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE username = VALUES(username), email = VALUES(email)";
    
            $userParams = [
                $donor->getUserID(), // userID
                $donor->getUsername(), // username
                $donor->getFirstname(), // firstName
                $donor->getLastname(), // lastName
                $donor->getEmail(), // email
                password_hash($donor->getPassword(), PASSWORD_DEFAULT), // hashed password
                json_encode($donor->getLocation()), // location as JSON
                $donor->getPhoneNumber(), // phoneNumber
                true // isActive
            ];
    
            if (!$dbConnection->execute($userSql, $userParams)) {
                throw new Exception("Failed to insert or update user record.");
            }
    
            $donorSql = "INSERT INTO donor (donorID,userID, donationHistory, totalDonations)
                         VALUES (?, ?, ?, ?)
                         ON DUPLICATE KEY UPDATE totalDonations = VALUES(totalDonations)";
    
            $donorParams = [
                $donor->getUserID(),
                $donor->getUserID(), // userID
                json_encode($donor->getDonationHistory()), // donationHistory as JSON
                $donor->getTotalDonationsStrategy() // totalDonations
            ];
    
            if (!$dbConnection->execute($donorSql, $donorParams)) {
                throw new Exception("Failed to insert or update donor record.");
            }
    
            return true;
        } catch (Exception $e) {
        
            error_log("Error creating donor: " . $e->getMessage());
            return false;
        }
    }
    


    public static function retrieve($donorID): ?Donor {
        $dbConnection = DatabaseConnection::getInstance();
        $sql = "SELECT * FROM donor d
                JOIN user u ON d.userID = u.userID
                WHERE d.donorID = ?";
        $params = [$donorID];
    
        $result = $dbConnection->query($sql, $params);

      //  echo "Query Result:\n";
        print_r($result);
    
        if ($result && !empty($result)) {

            $row = $result[0];

            if (
                isset($row['userID'], $row['username'], $row['firstName'], $row['lastName'], $row['email'], $row['password'], $row['locationList'], $row['phoneNumber'])
            ) {
                $donor = new Donor(
                    $row['username'],
                    $row['firstName'],
                    $row['lastName'],
                    $row['email'],
                    $row['password'],
                    json_decode($row['locationList'], true),
                    (int)$row['phoneNumber'],
                    null, // paymentMethod
                    null,
                    (int)$row['userID'], 
                );
    
                $donor->donorID = (int)$row['donorID'];
                $donor->donationsHistory = json_decode($row['donationHistory'], true) ?? [];
                $donor->totalDonations = (float)$row['totalDonations'];
                
    
                return $donor;
            } else {
                throw new Exception("Missing required fields in the query result.");
            }
        }
        return null;
    }
    
    public static function update($donor): bool {
        $dbConnection = DatabaseConnection::getInstance();

        try {
            $userSql = "UPDATE user SET 
                        username = ?, 
                        firstName = ?, 
                        lastName = ?, 
                        email = ?, 
                        password = ?, 
                        locationList = ?, 
                        phoneNumber = ? 
                    WHERE userID = ?";

            $userParams = [
                $donor->getUsername(),
                $donor->getFirstname(),
                $donor->getLastname(),
                $donor->getEmail(),
                password_hash($donor->getPassword(), PASSWORD_DEFAULT),
                json_encode($donor->getLocation()),
                $donor->getPhoneNumber(),
                $donor->getDonorID()
            ];

      //      echo "User SQL Query: $userSql\n";
       //     echo "User Parameters: " . print_r($userParams, true) . "\n";
    
            if (!$dbConnection->execute($userSql, $userParams)) {
                throw new Exception("Failed to update user record.");
            }

            $donorSql = "UPDATE donor SET 
                        donationHistory = ?, 
                        totalDonations = ? 
                     
                    WHERE donorID = ?";

            $donorParams = [
                json_encode($donor->getDonationHistory()),
                $donor->getTotalDonationsStrategy(),
                $donor->getDonorID()
            ];

       //     echo "Donor SQL Query: $donorSql\n";
        //    echo "Donor Parameters: " . print_r($donorParams, true) . "\n";

            if (!$dbConnection->execute($donorSql, $donorParams)) {
                throw new Exception("Failed to update donor record.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Error updating donor: " . $e->getMessage());
            return false;
        }
    }

    public static function delete($donorID): bool {
        $dbConnection = DatabaseConnection::getInstance();

        try {
            $donorSql = "DELETE FROM donor WHERE donorID = ?";
            $donorParams = [$donorID];

            if (!$dbConnection->execute($donorSql, $donorParams)) {
                throw new Exception("Failed to delete donor record.");
            }

        
            $userSql = "DELETE FROM user WHERE userID = ?";
            $userParams = [$donorID];

            if (!$dbConnection->execute($userSql, $userParams)) {
                throw new Exception("Failed to delete user record.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Error deleting donor: " . $e->getMessage());
            return false;
        }
    }


    public function getDonationHistory(): array {
        return $this->donationsHistory;
    }

    public function addDonation(Donation $donation): bool {
        $this->donationsHistory[] = $donation;
        $this->totalDonations += $donation->getAmount();
        return true;
    }

    // public function addEvent(Event $event): void {
    //     $this->campaignsJoined[] = $event;
    // }    

    public function getEvents(): array {
        return $this->campaignsJoined;
    }


    //StrategyMethods
    
    public function joinEvent() {
        $this->eventStrategy->signUp($this->donorID);
    } 

    public function getAllEventsStrategy(){
        $this->eventStrategy->getAllEvents();
    }


    public function checkEventStatusStrategy(){

        return $this->eventStrategy->checkEventStatus();
    }

    public function generateEventReportStrategy(){
        $this->eventStrategy->generateEventReport();
    }

    public function getTotalDonationsStrategy(): float {
        return $this->totalDonations;
    }

    // Switching between strategies
    public function setPaymentMethod(IPaymentStrategy $paymentMethod): void {
        $this->paymentMethod = $paymentMethod;
    }

    public function setEventMethod(Event $eventStrategy): void {
        $this->eventStrategy = $eventStrategy;
    }

    // Observer status update
    public function UpdateStatus(string $status): string {
        return $status;
    }

    public function getDonorID(): int {
        return $this->donorID;
    }

    public function getPaymentMethod(): ?IPaymentStrategy {
        return $this->paymentMethod;
    }


    public function getEventMethod(): Event{
        return $this->eventStrategy;
    }

    public function getEventData(): ISubject {
        return $this->eventData;
    }
}
?>
