<?php
class DonationView
{
    public function DonationViewDetails($donationID)
    { 
        echo '<!DOCTYPE html>
        <html lang="en" dir="ltr">
        <head>
            <meta charset="utf-8">
            <title>Donation</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="style.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
            <script src="javascript.js"></script>
        </head>
        <body>
            <nav>
                <label class="logo">Donation Manager</label>
                <ul>
                    <li><a href="#" onclick="donationDetails(1)">Donation Details</a></li>
                </ul>
            </nav>
            <div class="container">
                <section>
                    <div id="home">
                        <h2>Welcome to the Donation Details System</h2>
                        <img src="assets/donation.jpg" alt="Welcome Image" style="max-width:100%; height:auto;">
                    </div>
                </section>
            </div>
            <script src="script.js"></script>
        </body>
        </html>';
    }

    public function displayDonationDetails($donation)
    {
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Donation Details</title>
            <link rel="stylesheet" href="style.css">
            <link rel="stylesheet" href="style3.css">
        </head>
        <body>
            <nav>
                <label class="logo">Campaign Manager</label>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Campaigns</a></li>
                    <li><a href="#">Donations</a></li>
                </ul>
            </nav>
            <div class="container">
                <section>
                    <div class="donation-details">
                        <h2>Donation Details</h2>
                        <ul>
                            <li><strong>Donation ID:</strong> ' . $donation->id . '</li>
                            <li><strong>Amount:</strong> ' . $donation->amount . '</li>
                            <li><strong>Date:</strong> ' . $donation->date . '</li>
                            <li><strong>Donor Name:</strong> ' . $donation->donorName . '</li>
                        </ul>
                        <button onclick="makePayment()" class="payment-button">Make Payment</button>
                    </div>
                </section>
            </div>
            
            <script>
                function makePayment() {
                    alert("Payment process initiated for Donation ID: ' . $donation->id . '");
                    // Add actual payment handling logic here
                }
            </script>
            
        </body>
        </html>';
    }
}

$donationView = new DonationView();
$donationView->DonationViewDetails("Sample Data");
$donation = (object)[
    'id' => 1,
    'amount' => 10000,
    'date' => '2024-01-01',
    'donorName' => 'la',
];
$donationView->displayDonationDetails($donation);
?>
