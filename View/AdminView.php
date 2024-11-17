<?php
class AdminView
{
    public function AdminViewDetails($StdObj)
    { 
        echo '<!DOCTYPE html>
        <html lang="en" dir="ltr">
        <head>
            <meta charset="utf-8">
            <title>Admin</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="style.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="javascript.js"></script>
        </head>
        <body>
            <nav>
                <label class="logo">Admin</label>
                <ul>
                    <li><a href="#" onclick="users(1)">Users</a></li>
                    <li><a href="#" onclick="donationHistory(1)">Donations</a></li>
                    <li><a href="#" onclick="viewDonationStatistics(1)">Statistics</a></li>
                </ul>
            </nav>
            <div class="container">
                <section>
                        <div id="home">
                            <h2>Welcome to the User Information System</h2>
                            <p>Select a menu item to view more details.</p>
                            <img src="assets/donation.jpg" alt="Welcome Image" style="max-width:100%; height:auto;">
                        </div>
                </section>
            </div>

        </body>
        </html>';
    }

    public function displayUsers($users)
    {
        echo '<div class="users-name">';
        echo '<h2>Users Name</h2>';
        echo '<ul>';
        foreach ($users as $user) {
            echo '<li>Name: ' . $user->username . '</li>';
        }
        echo '</ul>';
        echo '</div>';
    }

    public function displayDonations($donations)
    {
        echo '<div class="donation-history">';
        echo '<h2>Donation History</h2>';
        echo '<ul>';
        foreach ($donations as $donation) {
            echo '<li>Date: ' . $donation->date . ' - Amount: ' . $donation->amount . '</li>';
        }
        echo '</ul>';
        echo '</div>';
    }

    public function displayDonationStatistics($statistics)
    {
        echo '<div class="donations-statistics">';
        echo '<h2>Donations Statistics</h2>';
        foreach ($statistics as $statistic) {
            echo '<li>Donation: ' . $statistic->amount . ' - Statistics: ' . $statistic->statistic . '</li>';
        }
        echo '</div>';
    }

}

$adminView = new AdminView();
$adminView->AdminViewDetails("Sample Data");
$adminView->displayUsers("s");
?>
