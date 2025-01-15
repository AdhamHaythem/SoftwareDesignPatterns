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
            <link rel="stylesheet" href="style2.css">
            <link rel="stylesheet" href="style3.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        </head>
        <body>
            <div id="eventModal" class="modal" style="display:none;">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <h2>Event Options</h2>
                    <div class="modal-buttons">
                        <button onclick="campaignDetails()">View Campaign</button>
                        <button onclick="volunteerDetails()">View Volunteer</button>
                    </div>
                </div>
            </div>
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
            <script>
                function openModal(eventName) {
                    document.getElementById("eventModal").style.display = "block";
                    sessionStorage.setItem("selectedEvent", eventName);
                }

                function closeModal() {
                    document.getElementById("eventModal").style.display = "none";
                }
                    
            </script>
        </body>
        </html>';
    }

    public function displayDonorProfile($donor)
    {
        echo '<div class="container">
                <section>
                    <div class="lesson-details">
                        <h2>Donor Profile</h2>
                        <p><strong>Name:</strong> ' . $donor->name . '</p>
                        <p><strong>Email:</strong> ' . $donor->email . '</p>
                    </div>
                </section>
            </div>';
    }
    

    public function displayDonationHistory($donations)
    {
        echo '<div class="container">
        <section>
            <div class="lesson-details">
                <ul>';
        echo '<h2>Donation History</h2>';
        foreach ($donations as $donation) {
            echo '<li>Date: ' . $donation->date . ' - Amount: ' . $donation->donationsHistory . '</li>';
        }
        echo '</ul>
                </div>
            </section>
        </div>';
    }

    public function displayTotalDonations($total)
    {
        echo '<div class="container">
            <section>
                <div class="lesson-details">
                    <h2>Total Donations</h2>
                    <p><strong>Total Amount Donated:</strong> $' . $total->totalDonations . '</p>
                </div>
            </section>
        </div>';
    }
    

    public function displayEventList($donor)
    {
        echo '<div class="container">
        <section>
            <div class="lesson-details">
                <ul>';        
        echo '<h2>Event List for ' . $donor->name . '</h2>';
        foreach ($donor->events as $event) {
            echo '<li><a href="#" onclick="openModal(\'' . $event->name . '\')">' . $event->name . ' - Date: ' . $event->date . '</a></li>';
        }
        echo '</ul>
                </div>
            </section>
        </div>';
    }

}

$donor = (object)[
    'name' => 'John Doe',
    'events' => [
        (object)['name' => 'Charity Run', 'date' => '2024-11-01'],
        (object)['name' => 'Blood Donation Camp', 'date' => '2024-11-05']
    ]
];

$donorView = new DonorView();
$donorView->DonorViewDetails("Sample Data");
$donorView->displayEventList($donor);
$donorView->displayTotalDonations($donor)
?>
