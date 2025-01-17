<?php
// class DonationView
// {
//     public function DonationViewDetails($donationID)
//     { 
//         echo '<!DOCTYPE html>
//         <html lang="en" dir="ltr">
//         <head>
//             <meta charset="utf-8">
//             <title>Donation</title>
//             <meta name="viewport" content="width=device-width, initial-scale=1.0">
//             <link rel="stylesheet" href="style.css">
//             <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
//             <script src="javascript.js"></script>
//         </head>
//         <body>
//             <nav>
//                 <label class="logo">Donation Manager</label>
//                 <ul>
//                     <li><a href="#" onclick="donationDetails(1)">Donation Details</a></li>
//                 </ul>
//             </nav>
//             <div class="container">
//                 <section>
//                     <div id="home">
//                         <h2>Welcome to the Donation Details System</h2>
//                         <img src="assets/donation.jpg" alt="Welcome Image" style="max-width:100%; height:auto;">
//                     </div>
//                 </section>
//             </div>
//             <script src="script.js"></script>
//         </body>
//         </html>';
//     }

//     public function displayDonationDetails($donation)
//     {
//         echo '<!DOCTYPE html>
//         <html lang="en">
//         <head>
//             <meta charset="UTF-8">
//             <meta name="viewport" content="width=device-width, initial-scale=1.0">
//             <title>Donation Details</title>
//             <link rel="stylesheet" href="style.css">
//             <link rel="stylesheet" href="style3.css">
//         </head>
//         <body>
//             <nav>
//                 <label class="logo">Donation Payment</label>
//                 <ul>
//                     <li><a href="#">Home</a></li>
//                     <li><a href="#">Donations</a></li>
//                 </ul>
//             </nav>
//             <div class="container">
//                 <section>
//                     <div class="donation-details">
//                         <h2>Donation Details</h2>
//                         <ul>
//                             <li><strong>Amount:</strong> ' . $donation->amount . '</li>
//                             <li><strong>Date:</strong> ' . $donation->date . '</li>
//                             <li><strong>Donor Name:</strong> ' . $donation->donorName . '</li>
//                         </ul>
//                         <button onclick="makePayment()" class="payment-button">Make Payment</button>
//                     </div>
//                 </section>
//             </div>
            
//             <script>
//                 function makePayment() {
//                     alert("Payment process initiated for Donation: ' . $donation->donorName . '");
//                     // Add actual payment handling logic here
//                 }
//             </script>
            
//         </body>
//         </html>';
//     }
// }

// $donationView = new DonationView();
// $donationView->DonationViewDetails("Sample Data");
// $donation = (object)[
//     'amount' => 10000,
//     'date' => '2024-01-01',
//     'donorName' => 'Medhat',
// ];
// $donationView->displayDonationDetails($donation);
?>
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
            <style>
                .container {
                    max-width: 800px;
                    margin: 20px auto;
                    background: #fff;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                }
                form {
                    display: flex;
                    flex-direction: column;
                    gap: 15px;
                }
                label {
                    font-weight: bold;
                }
                input, button {
                    padding: 10px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    font-size: 1em;
                }
                button {
                    background-color: #28a745;
                    color: #fff;
                    border: none;
                    cursor: pointer;
                }
                button:hover {
                    background-color: #218838;
                }
                .confirmation {
                    margin-top: 20px;
                    padding: 20px;
                    background-color: #d4edda;
                    border: 1px solid #c3e6cb;
                    border-radius: 5px;
                    color: #155724;
                }
                .checkbox-group {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 10px;
                }
                .checkbox-group label {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    padding: 10px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    background-color: #f9f9f9;
                    cursor: pointer;
                }
                .checkbox-group input[type="checkbox"] {
                    display: none;
                }
                .checkbox-group input[type="checkbox"]:checked + label {
                    background-color: #28a745;
                    color: #fff;
                }
                .undo-button {
                    background-color: #17a2b8;
                }
                .undo-button:hover {
                    background-color: #138496;
                }
                .redo-button {
                    background-color: #17a2b8;
                }
                .redo-button:hover {
                    background-color: #138496;
                }
            </style>
        </head>
        <body>
            <nav>
                <label class="logo">Donation Manager</label>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Donations</a></li>
                </ul>
            </nav>
            <div class="container">
                <section>
                    <div id="home">
                        <h2>Welcome to the Donation Details System</h2>
                        <img src="assets/donation.jpg" alt="Welcome Image" style="max-width:100%; height:auto; border-radius: 8px;">
                    </div>
                </section>
            </div>
        </body>
        </html>';
    }

    public function displayDonationDetails($donor)
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
                <label class="logo">Donation Payment</label>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Donations</a></li>
                </ul>
            </nav>
            <div class="container">
                <section>
                    <div class="donation-details">
                        <h2>Enter Donation Details</h2>
                        <form method="POST" action="">
                            <label for="donationType">Donation Type: (select multiple donation if you want)</label>
                            <div class="checkbox-group">
                                <input type="checkbox" id="food" name="donationType[]" value="food">
                                <label for="food">Food (in kg)</label>
                                
                                <input type="checkbox" id="clothes" name="donationType[]" value="clothes">
                                <label for="clothes">Clothes (in LE)</label>
                                
                                <input type="checkbox" id="medical" name="donationType[]" value="medical">
                                <label for="medical">Medical Supplies (in LE)</label>
                                
                                <input type="checkbox" id="cash" name="donationType[]" value="cash">
                                <label for="cash">Cash Donation (in LE)</label>
                            </div>

                            <label for="amount">Amount:</label>
                            <input type="number" name="amount" id="amount" step="0.01" required placeholder="Enter amount or weight">

                            <label for="date">Date:</label>
                            <input type="date" name="date" id="date" required>

                            <label for="donorName">Donor Name:</label>
                            <div class="donor-name">$donor -> username</div>

                            <div class="action-buttons">
                                <button type="button" class="undo-button" onclick="undo(1)">Undo</button>
                                <button type="button" class="redo-button" onclick="redo(1)">Redo</button>
                            </div>

                            <button type="submit" class="submit-button" onClick="donationButton(1)" >Submit Donation</button>
                        </form>
                    </div>
                </section>
            </div>';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $donationTypes = $_POST['donationType'];
            $amount = $_POST['amount'];
            $date = $_POST['date'];
            $donorName = $_POST['donorName'];
            
            echo '<div class="container">
                <section>
                    <div class="confirmation">
                        <h2>Donation Confirmation</h2>
                        <p><strong>Donation Types:</strong> ' . implode(', ', array_map('ucfirst', $donationTypes)) . '</p>
                        <p><strong>Amount:</strong> ' . $amount . '</p>
                        <p><strong>Date:</strong> ' . $date . '</p>
                        <p><strong>Donor Name:</strong> ' . $donorName . '</p>
                        <button onclick=donationButton(1) class="confirm-button">Confirm</button>
                    </div>
                </section>
            </div>';
        }

        echo '</body>
        </html>';
    }
}

$donationView = new DonationView();
$donationView->DonationViewDetails("Sample Data");
$donationView->displayDonationDetails(1);
?>




