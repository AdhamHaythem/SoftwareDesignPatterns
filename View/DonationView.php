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
                            <li><strong>Donation ID:</strong> ' . htmlspecialchars($donation->id) . '</li>
                            <li><strong>Amount:</strong> ' . htmlspecialchars($donation->amount) . '</li>
                            <li><strong>Date:</strong> ' . htmlspecialchars($donation->date) . '</li>
                            <li><strong>Donor Name:</strong> ' . htmlspecialchars($donation->donorName) . '</li>
                        </ul>
                        <button onclick="makePayment()" class="payment-button">Make Payment</button>
                    </div>
                </section>
            </div>
            <style>
                /* Styling for the payment button */
                .payment-button {
                    display: inline-block;
                    padding: 10px 20px;
                    font-size: 16px;
                    color: #fff;
                    background-color: #28a745;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    margin-top: 20px;
                }
                
                .payment-button:hover {
                    background-color: #218838;
                }
                
                /* Other styles */
                /* Reset some basic styling */
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                /* Set a background color for the body and font settings */
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    background-color: #f4f4f4;
                    color: #333;
                    padding: 20px;
                }

                /* Container for main content */
                .container {
                    max-width: 1200px;
                    margin: 0 auto;
                    padding: 20px;
                }

                /* Style the navigation bar */
                nav {
                    background-color: #333;
                    padding: 10px 0;
                    margin-bottom: 30px;
                }

                nav .logo {
                    color: #fff;
                    font-size: 24px;
                    font-weight: bold;
                    padding-left: 20px;
                }

                nav ul {
                    list-style-type: none;
                    float: right;
                    margin-right: 20px;
                }

                nav ul li {
                    display: inline;
                    margin-left: 20px;
                }

                nav ul li a {
                    color: #fff;
                    text-decoration: none;
                    font-size: 18px;
                }

                nav ul li a:hover {
                    text-decoration: underline;
                }

                /* Style for the donation details section */
                .donation-details {
                    background-color: #fff;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                    padding: 30px;
                    margin-bottom: 30px;
                }

                .donation-details h2 {
                    font-size: 28px;
                    color: #333;
                    margin-bottom: 20px;
                }

                .donation-details ul {
                    list-style-type: none;
                }

                .donation-details ul li {
                    font-size: 18px;
                    margin-bottom: 10px;
                }

                .donation-details ul li strong {
                    color: #555;
                }

                /* Styling for mobile responsiveness */
                @media (max-width: 768px) {
                    .container {
                        padding: 10px;
                    }

                    nav ul {
                        float: none;
                        text-align: center;
                    }

                    nav ul li {
                        display: block;
                        margin-left: 0;
                        margin-top: 10px;
                    }

                    .donation-details {
                        padding: 20px;
                    }
                }
            </style>,;
            
            <script>
                function makePayment() {
                    alert("Payment process initiated for Donation ID: ' . htmlspecialchars($donation->id) . '");
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
