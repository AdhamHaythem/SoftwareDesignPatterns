
<?php
class CampaignView
{
    public function CampaignViewDetails($campaign)
    { 
        echo '<!DOCTYPE html>
        <html lang="en" dir="ltr">
        <head>
            <meta charset="utf-8">
            <title>Campaign</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="style.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
            <script src="javascript.js"></script>
        </head>
        <body>
            <nav>
                <label class="logo">Campaign Manager</label>
                <ul>
                    <li><a href="#" onclick="getCampaignData(1)">Campaign Details</a></li>
                    <li><a href="#" onclick="displayReports()">Event List</a></li>
                    <li><a href="#" onclick="displayDonations()">Fund Progress</a></li>
                    <li><a href="#" onclick="displayDonationStatistics()">All Campaigns</a></li>
                </ul>
            </nav>
            <div class="container">
                <section>
                        <div id="home">
                            <h2>Welcome to the Campaign System</h2>
                            <p>Select a menu item to view more details.</p>
                            <img src="assets/donation.jpg" alt="Welcome Image" style="max-width:100%; height:auto;">
                        </div>
                </section>
            </div>
            <script src="script.js"></script>
        </body>
        </html>';
    }
    public function displayCampaignDetails($campaign)
    {
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Campaign Details</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <!DOCTYPE html>
        < lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Campaign Manager</title>
            <style>
                /* Basic Reset */
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                /* Body Styling */
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f9;
                    color: #333;
                }

                /* Navigation Styling */
                nav {
                    background-color: #333;
                    color: #fff;
                    padding: 15px;
                    text-align: center;
                }

                nav .logo {
                    font-size: 24px;
                    font-weight: bold;
                }

                nav ul {
                    list-style: none;
                    margin-top: 10px;
                    display: flex;
                    justify-content: center;
                }

                nav ul li {
                    margin: 0 15px;
                }

                nav ul li a {
                    color: #fff;
                    text-decoration: none;
                    font-weight: bold;
                    transition: color 0.3s;
                }

                nav ul li a:hover {
                    color: #ffcc00;
                }

                /* Container and Section Styling */
                .container {
                    width: 80%;
                    max-width: 1200px;
                    margin: 20px auto;
                    padding: 20px;
                    background-color: #fff;
                    border-radius: 8px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                }

                section h2 {
                    font-size: 24px;
                    margin-bottom: 15px;
                    color: #333;
                }

                .campaign-details, .campaign-progress, .all-campaigns, .campaign-events {
                    margin-bottom: 20px;
                }

                .campaign-details ul, .campaign-events ul {
                    list-style: none;
                    padding: 0;
                }

                .campaign-details ul li, .campaign-events ul li {
                    margin-bottom: 10px;
                    font-size: 16px;
                }

                /* Button Styling */
                button {
                    padding: 10px 20px;
                    border: none;
                    background-color: #333;
                    color: #fff;
                    border-radius: 5px;
                    cursor: pointer;
                    transition: background-color 0.3s;
                }

                button:hover {
                    background-color: #ffcc00;
                }

                /* Progress Bar Styling */
                .progress-bar {
                    width: 100%;
                    background-color: #ddd;
                    border-radius: 5px;
                    overflow: hidden;
                }

                .progress-bar .progress {
                    height: 20px;
                    background-color: #28a745;
                    width: 0;
                    transition: width 0.5s;
                }

                /* Responsive Design */
                @media (max-width: 768px) {
                    nav ul {
                        flex-direction: column;
                    }

                    .container {
                        width: 95%;
                    }
                }
            </style>
        </head>
        <body>
            <nav>
                <label class="logo">Campaign Manager</label>
                <ul>
                    <li><a href="#" onclick="viewCampaignDetails(' . htmlspecialchars($campaign->id) . ')">Campaign Details</a></li>
                    <li><a href="#">Event List</a></li>
                    <li><a href="#">Fund Progress</a></li>
                    <li><a href="#">All Campaigns</a></li>
                </ul>
            </nav>
            <div class="content-container">
                <section>
                    <div class="campaign-details">
                        <h2>Campaign Details</h2>
                        <ul>
                            <li><strong>Campaign ID:</strong> ' . htmlspecialchars($campaign->id) . '</li>
                            <li><strong>Campaign Name:</strong> ' . htmlspecialchars($campaign->name) . '</li>
                            <li><strong>Target Amount:</strong> ' . htmlspecialchars($campaign->targetAmount) . '</li>
                            <li><strong>Date:</strong> ' . htmlspecialchars($campaign->startDate) . '</li>
                            <li><strong>Donation:</strong> ' . htmlspecialchars($campaign->Donation) . '</li>
                        </ul>
                    </div>
                </section>
            </div>
        </body>
        </html>';
    }

    // Method to display event list related to the campaign
    public function displayEventList($campaign)
    {
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Event List</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <nav>
                <label class="logo">Campaign Manager</label>
                <ul>
                    <li><a href="#" onclick="viewCampaignDetails(' . htmlspecialchars($campaign->id) . ')">Campaign Details</a></li>
                    <li><a href="#">Event List</a></li>
                    <li><a href="#">Fund Progress</a></li>
                    <li><a href="#">All Campaigns</a></li>
                </ul>
            </nav>
            <div class="container">
                <section>
                    <div class="campaign-events">
                        <h2>Event List for ' . htmlspecialchars($campaign->name) . '</h2>';
        foreach ($campaign->events as $event) {
            echo '<ul>
                    <li><strong>Event Name:</strong> ' . htmlspecialchars($event->name) . '</li>
                    <li><strong>Event Date:</strong> ' . htmlspecialchars($event->eventDate) . '</li>
                </ul>';
        }
        echo '</div>
                </section>
            </div>
            <script src="script.js"></script>
        </body>
        </html>';
    }

    // Method to display fund progress for the campaign
    public function displayFundProgress($campaign)
    {
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Fund Progress</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <nav>
                <label class="logo">Campaign Manager</label>
                <ul>
                    <li><a href="#" onclick="viewCampaignDetails(' . htmlspecialchars($campaign->id) . ')">Campaign Details</a></li>
                    <li><a href="#">Event List</a></li>
                    <li><a href="#">Fund Progress</a></li>
                    <li><a href="#">All Campaigns</a></li>
                </ul>
            </nav>
            <div class="container">
                <section>
                    <div class="campaign-progress">
                        <h2>Fund Progress for ' . htmlspecialchars($campaign->name) . '</h2>
                        <p><strong>Amount Raised:</strong> ' . htmlspecialchars($campaign->raisedAmount) . '</p>
                        <p><strong>Target Amount:</strong> ' . htmlspecialchars($campaign->targetAmount) . '</p>';
        $progress = ($campaign->raisedAmount / $campaign->targetAmount) * 100;
        echo '<p><strong>Progress:</strong> ' . number_format($progress, 2) . '%</p>';
        echo '</div>
                </section>
            </div>
            <script src="script.js"></script>
        </body>
        </html>';
    }

    // Method to display all campaigns
    public function displayAllCampaigns($campaigns)
    {
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>All Campaigns</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <nav>
                <label class="logo">Campaign Manager</label>
                <ul>
                    <li><a href="#">Campaign Details</a></li>
                    <li><a href="#">Event List</a></li>
                    <li><a href="#">Fund Progress</a></li>
                    <li><a href="#">All Campaigns</a></li>
                </ul>
            </nav>
            <div class="container">
                <section>
                    <div class="all-campaigns">
                        <h2>All Campaigns</h2>
                        <ul>';
        foreach ($campaigns as $campaign) {
            echo '<li>';
            echo '<strong>Campaign Name:</strong> ' . htmlspecialchars($campaign->name) . '<br>';
            echo '<strong>Target Amount:</strong> ' . htmlspecialchars($campaign->targetAmount) . '<br>';
            echo '<button onclick="viewCampaignDetails(' . htmlspecialchars($campaign->id) . ')">View Details</button>';
            echo '</li>';
        }
        echo '</ul>
                    </div>
                </section>
            </div>
            <script src="script.js"></script>
        </body>
        </html>';
    }
}

// Sample usage
$campaignManagerView = new CampaignView();


// Example data (replace with actual campaign data)
$campaign = (object)[
    'id' => 1,
    'name' => 'Campaign A',
    'targetAmount' => 10000,
    'raisedAmount' => 5000,
    'startDate' => '2024-01-01',
    'endDate' => '2024-12-31',
    'events' => [
        (object)['name' => 'Event 1', 'eventDate' => '2024-02-01'],
        (object)['name' => 'Event 2', 'eventDate' => '2024-03-01'],
    ]
];
$campaignManagerView->CampaignViewDetails("sample");
$campaignManagerView->displayCampaignDetails($campaign);
$campaignManagerView->displayEventList($campaign);
$campaignManagerView->displayFundProgress($campaign);

// Example for displaying all campaigns (replace with actual list of campaigns)
$campaigns = [
    (object)['id' => 1, 'name' => 'Campaign A', 'targetAmount' => 10000],
    (object)['id' => 2, 'name' => 'Campaign B', 'targetAmount' => 5000]
];
$campaignManagerView->displayAllCampaigns($campaigns);
?>
