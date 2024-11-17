<?php
require_once 'configurations.php';
require_once 'db_connection.php';
require_once 'data.php';

function main() {
    $config = require 'configurations.php';
    $db = new DatabaseConnection($config);

    try {
        createTables($db);
    } catch (Exception $e) {
        echo "Error creating tables: " . $e->getMessage() . "<br>";
    }

    try {
        echo "Users inserted successfully.<br>";
        $users = $db->query("SELECT * FROM user");
        if ($users) {
            foreach ($users as $user) {
                echo "UserID: {$user['userID']}, Username: {$user['username']}, Email: {$user['email']}<br>";
            }
        } else {
            echo "No users found.<br>";
        }
    } catch (Exception $e) {
        echo "Error fetching users: " . $e->getMessage() . "<br>";
    }

    try {
        $donors = [
            ['userID' => 18, 'donationHistory' => '[{"donationID": 1, "amount": 100}]', 'totalDonations' => 100, 'goalAmount' => 500],
            ['userID' => 22, 'donationHistory' => '[{"donationID": 2, "amount": 200}]', 'totalDonations' => 200, 'goalAmount' => 1000],
            ['userID' => 19, 'donationHistory' => '[{"donationID": 3, "amount": 2000}]', 'totalDonations' => 2000, 'goalAmount' => 1000],
            ['userID' => 23, 'donationHistory' => '[{"donationID": 4, "amount": 2000}]', 'totalDonations' => 3000, 'goalAmount' => 1000]
        ];
    
        foreach ($donors as $donor) {
            $existingDonor = $db->query("SELECT userID FROM donor WHERE userID = ?", [$donor['userID']]);
            if (!$existingDonor) {
                // Insert new donor
                $db->execute(
                    "INSERT INTO donor (userID, donationHistory, totalDonations, goalAmount) VALUES (?, ?, ?, ?)",
                    [$donor['userID'], $donor['donationHistory'], $donor['totalDonations'], $donor['goalAmount']]
                );
                echo "Donor added successfully: UserID {$donor['userID']}<br>";
            } else {
                echo "Donor with UserID {$donor['userID']} already exists.<br>";
            }
        }
    } catch (Exception $e) {
        echo "Error inserting/updating donor data: " . $e->getMessage() . "<br>";
    }
    
    try {
        $donations = [
            ['amount' => 100, 'donorID' => 107],
            ['amount' => 200, 'donorID' => 108]
        ];

        foreach ($donations as $donation) {
            $existingDonation = $db->query(
                "SELECT * FROM donation WHERE donorID = ? AND amount = ?",
                [$donation['donorID'], $donation['amount']]
            );

            if (empty($existingDonation)) {
                $db->execute(
                    "INSERT INTO donation (amount, donorID) VALUES (?, ?)",
                    [$donation['amount'], $donation['donorID']]
                );
                echo "Donation added successfully for donorID {$donation['donorID']}<br>";
            } else {
                echo "Duplicate donation prevented for donorID {$donation['donorID']} with amount {$donation['amount']}<br>";
            }
        }
    } catch (Exception $e) {
        echo "Error inserting donations: " . $e->getMessage() . "<br>";
    }

    
    
    
    try {
        $donationManagers = [
            [
                'donations' => '[{"donationID": 1, "amount": 100}, {"donationID": 2, "amount": 200}]',
                'totalDonations' => 300,
                'goalAmount' => 1000,
                'campaigns' => '[{"campaignID": 1, "target": 500}, {"campaignID": 2, "target": 500}]'
            ]
        ];

        foreach ($donationManagers as $manager) {
            $existingManager = $db->query(
                "SELECT * FROM donationmanager WHERE donations = ? AND campaigns = ?",
                [$manager['donations'], $manager['campaigns']]
            );

            if (empty($existingManager)) {
                $db->execute(
                    "INSERT INTO donationmanager (donations, totalDonations, goalAmount, campaigns) VALUES (?, ?, ?, ?)",
                    [$manager['donations'], $manager['totalDonations'], $manager['goalAmount'], $manager['campaigns']]
                );
                echo "DonationManager added successfully.<br>";
            } else {
                echo "Duplicate DonationManager entry prevented.<br>";
            }
        }
    } catch (Exception $e) {
        echo "Error inserting DonationManager data: " . $e->getMessage() . "<br>";
    }


    try {
        $events = [
            ['name' => 'Charity Run', 'time' => '2024-11-25 10:00:00', 'location' => 'Park A', 'volunteers_needed' => 50],
            ['name' => 'Blood Donation Drive', 'time' => '2024-11-26 09:00:00', 'location' => 'Hospital B', 'volunteers_needed' => 30],
            ['name' => 'Charity Run', 'time' => '2024-11-25 10:00:00', 'location' => 'Park A', 'volunteers_needed' => 50] // Duplicate event
        ];

        foreach ($events as $event) {
            $existingEvent = $db->query(
                "SELECT * FROM Event WHERE name = ? AND time = ?",
                [$event['name'], $event['time']]
            );

            if (count($existingEvent) == 0) {
                $db->execute(
                    "INSERT INTO Event (name, time, location, volunteers_needed) VALUES (?, ?, ?, ?)",
                    [$event['name'], $event['time'], $event['location'], $event['volunteers_needed']]
                );
                echo "Event '{$event['name']}' added successfully.<br>";
            } else {
                echo "Duplicate event prevented: '{$event['name']}' at {$event['time']}<br>";
            }
        }
    } catch (Exception $e) {
        echo "Error inserting events: " . $e->getMessage() . "<br>";
    }


    try {
        $campaigns = [
            ['title' => 'Charity Marathon', 'target' => 10000, 'donations' => '[{"donationID": 1, "amount": 500}, {"donationID": 2, "amount": 200}]', 'moneyEarned' => 700],
            ['title' => 'Blood Donation Campaign', 'target' => 5000, 'donations' => '[{"donationID": 3, "amount": 1000}, {"donationID": 4, "amount": 500}]', 'moneyEarned' => 1500],
            ['title' => 'Charity Marathon', 'target' => 10000, 'donations' => '[{"donationID": 5, "amount": 1000}]', 'moneyEarned' => 1000] // Duplicate campaign
        ];

        foreach ($campaigns as $campaign) {
            $existingCampaign = $db->query(
                "SELECT * FROM CampaignStrategy WHERE title = ?",
                [$campaign['title']]
            );

            if (count($existingCampaign) == 0) {
                $db->execute(
                    "INSERT INTO CampaignStrategy (title, target, donations, moneyEarned) VALUES (?, ?, ?, ?)",
                    [$campaign['title'], $campaign['target'], $campaign['donations'], $campaign['moneyEarned']]
                );
                echo "Campaign '{$campaign['title']}' added successfully.<br>";
            } else {
                echo "Duplicate campaign prevented: '{$campaign['title']}'<br>";
            }
        }
    } catch (Exception $e) {
        echo "Error inserting campaigns: " . $e->getMessage() . "<br>";
    }


   
    $db->close();
}

main();
?>
