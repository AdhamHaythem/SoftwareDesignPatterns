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
                    <li><a href="#" onclick="loadProfile()">Donor Information</a></li>
                    <li><a href="#" onclick="loadDonationHistory()">History</a></li>
                    <li><a href="#" onclick="loadTotalDonations()">Total Donations</a></li>
                    <li><a href="#" onclick="loadEventStatus()">Events</a></li>
                </ul>
            </nav>
            <div class="container">
                <section>
                    <div id="content">
                        <div id="home">
                            <h2>Welcome to the Donor Information System</h2>
                            <p>Select a menu item to view more details.</p>
                            <img src="assets/donation.jpg" alt="Welcome Image" style="max-width:100%; height:auto;">
                        </div>
                    </div>
                </section>
            </div>
            <script src="javascript.js"></script>
        </body>
        </html>';
    }

    // Display donor profile information
    public function displayDonorProfile($donor)
    {
        echo '<div class="donor-profile">';
        echo '<h2>Donor Profile</h2>';
        echo '<p><strong>Name:</strong> ' . htmlspecialchars($donor->name) . '</p>';
        echo '<p><strong>Email:</strong> ' . htmlspecialchars($donor->email) . '</p>';
        echo '</div>';
    }

    // Display donation history
    public function displayDonationHistory($donations)
    {
        echo '<div class="donation-history">';
        echo '<h2>Donation History</h2>';
        echo '<ul>';
        foreach ($donations as $donation) {
            echo '<li>Date: ' . htmlspecialchars($donation->date) . ' - Amount: ' . htmlspecialchars($donation->amount) . '</li>';
        }
        echo '</ul>';
        echo '</div>';
    }

    public function displayTotalDonations($total)
    {
        echo '<div class="total-donations">';
        echo '<h2>Total Donations</h2>';
        echo '<p><strong>Total Amount Donated:</strong> $' . htmlspecialchars($total) . '</p>';
        echo '</div>';
    }

    public function displayEventStatus($events)
    {
        echo '<div class="event-status">';
        echo '<h2>Event Participation Status</h2>';
        echo '<ul>';
        foreach ($events as $event) {
            echo '<li>' . htmlspecialchars($event->name) . ' - Status: ' . htmlspecialchars($event->status) . '</li>';
        }
        echo '</ul>';
        echo '</div>';

        echo "<script>function eventStatus(itemId) {
            $.ajax({
                url: <?php echo \"SoftwareDesignPatterns/Controller/DonorController.php\" ?>,
                type: \'POST\',
                data: {
                    eventStatus: \'\',
                    },
                });
            };<\script>";
    }
       
       public function displayEventList($donor)
       {
           echo '<div class="event-list">';
           echo '<h2>Event List for ' . htmlspecialchars($donor->name) . '</h2>';
           echo '<ul>';
           foreach ($donor->events as $event) {
               echo '<li>' . htmlspecialchars($event->name) . ' - Date: ' . htmlspecialchars($event->date) . '</li>';
           }
           echo '</ul>';
           echo '</div>';
           
           echo "<script>function eventList(itemId) {
            $.ajax({
                url: <?php echo \"SoftwareDesignPatterns/Controller/DonorController.php\" ?>,
                type: \'POST\',
                data: {
                    eventList: \'\',
                    },
                });
            };<\script>";
       }
   
    
       public function displayEventReport($event)
       {
           echo '<div class="event-report">';
           echo '<h2>Event Report: ' . htmlspecialchars($event->name) . '</h2>';
           echo '<p><strong>Date:</strong> ' . htmlspecialchars($event->date) . '</p>';
           echo '<p><strong>Location:</strong> ' . htmlspecialchars($event->location) . '</p>';
           echo '<p><strong>Description:</strong> ' . htmlspecialchars($event->description) . '</p>';
           echo '<p><strong>Total Donations:</strong> ' . htmlspecialchars($event->totalDonations) . '</p>';
           echo '</div>';

           echo "<script>function eventReport(itemId) {
            $.ajax({
                url: <?php echo \"SoftwareDesignPatterns/Controller/DonorController.php\" ?>,
                type: \'POST\',
                data: {
                    eventReport: \'\',
                    },
                });
            };<\script>";
       }

}

$donorView = new DonorView();
$donorView->DonorViewDetails("Sample Data");
?>
