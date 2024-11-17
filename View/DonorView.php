<?php
class DonorView
{
    public function DonorViewDetails($StdObj)
    { 
        echo '<!DOCTYPE html>
        <html lang="en" dir="ltr">
        <head>
            <meta charset="utf-8">
            <title>Donor</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="style.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        </head>
        <body>
            <nav>
                <label class="logo">Donor Information</label>
                <ul>
                    <li><a href="#" onclick="loadHome()">Home</a></li>
                    <li><a href="#" onclick="donorProfile()">Donor Information</a></li>
                    <li><a href="#" onclick="donationHistory()">History</a></li>
                    <li><a href="#" onclick="totalDonations()">Total Donations</a></li>
                    <li><a href="#" onclick="eventList()">Events</a></li>
                </ul>
            </nav>
            <div class="container">
                <section>
                        <div id="home">
                            <h2>Welcome to the Donor Information System</h2>
                            <p>Select a menu item to view more details.</p>
                            <img src="assets/donation.jpg" alt="Welcome Image" style="max-width:100%; height:auto;">
                        </div>
                </section>
            </div>
            <script src="javascript.js"></script>
        </body>
        </html>';
    }

    public function displayDonorProfile($donor)
    {
        echo '<div class="donor-profile">';
        echo '<h2>Donor Profile</h2>';
        echo '<p><strong>Name:</strong> ' . $donor->name . '</p>';
        echo '<p><strong>Email:</strong> ' . $donor->email . '</p>';
        echo '</div>';
    }

    public function displayDonationHistory($donations)
    {
        echo '<div class="donation-history">';
        echo '<h2>Donation History</h2>';
        echo '<ul>';
        foreach ($donations as $donation) {
            echo '<li>Date: ' . $donation->date . ' - Amount: ' . $donation->donationsHistory . '</li>';
        }
        echo '</ul>';
        echo '</div>';
    }

    public function displayTotalDonations($total)
    {
        echo '<div class="total-donations">';
        echo '<h2>Total Donations</h2>';
        echo '<p><strong>Total Amount Donated:</strong> $' . $total->totalDonations . '</p>';
        echo '</div>';
    }

       
    public function displayEventList($donor)
    {
        echo '<div class="event-list">';
        echo '<h2>Event List for ' . htmlspecialchars($donor->name) . '</h2>';
        echo '<ul>';
        foreach ($donor->events as $event) {
            echo '<li>' . $event->name . ' - Date: ' . $event->date . '</li>';
        }
        echo '</ul>';
        echo '</div>';
    }


}

$donorView = new DonorView();
$donorView->DonorViewDetails("Sample Data");
?>
