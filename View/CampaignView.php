<?php
require_once "../Model/CampaignStrategy.php";
class CampaignView
{
    public function CampaignViewDetails($StdObj)
    { 
        echo '<!DOCTYPE html>
        <html lang="en" dir="ltr">
        <head>
            <meta charset="utf-8">
            <title>Campaign</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="style.css">
            <link rel="stylesheet" href="style2.css">
            <link rel="stylesheet" href="style3.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
            <script src="javascript.js"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        </head>
        <body>
            <nav>
                <label class="logo">Campaign Manager</label>
                <ul>
                    <li><a href="#" onclick="campaignDetails(2)">Campaign Details</a></li>
                    <li><a href="#" onclick="fundProgress(1)">Fund Progress</a></li>
                    <li><a href="#" onclick="campaignDetails(1)">All Campaigns</a></li>
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
        </body>
        </html>';
    }

    public function displayCampaignDetails(CampaignStrategy $campaign)
    {
        echo '<div class="container">
            <div class="content-container">
                <section>
                    <div class="campaign-details">
                        <h2>Campaign Details</h2>
                        <ul>
                            <li><strong>Campaign ID:</strong> ' . $campaign->getCampaignID() . '</li>
                            <li><strong>Campaign Name:</strong> ' . $campaign->getTitle() . '</li>
                            <li><strong>Money Earned:</strong> $' . number_format($campaign->getMoneyEarned(), 2) . '</li>
                            <li><strong>Start Date:</strong> ' . date('d/m/Y H:i:s', $campaign->getTime()->getTimestamp()) . '</li>
                            <li><strong>Location:</strong> $' . $campaign->getLocation(). '</li>
                        </ul>
                    </div>
                </section>
            </div>
        </div>';
    }

    public function displayFundProgress($campaign)
    {
        echo '<div class="container">
            <div class="content-container">
                <section>
                    <div class="campaign-progress" style="max-width: 600px; margin: auto; text-align: center; background-color: #f9f9f9; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                        <h2 style="font-family: Arial, sans-serif; color: #333;">Fund Progress for ' . htmlspecialchars($campaign->name) . '</h2>
                        <p style="margin-bottom: 15px; font-family: Arial, sans-serif; color: #555;">
                            <strong>Amount Raised:</strong> 
                            <input type="text" id="amountRaised" value="' . htmlspecialchars(number_format($campaign->moneyEarned, 2)) . '" 
                            style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; margin-top: 5px;" />
                        </p>
                        <button type="submit" class="submit-button" 
                            style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;"
                            onClick="addfundProgress(1)">Submit Fund</button>
                    </div>
                </section>
            </div>
        </div>';
    }
    


    public function displayAllCampaigns($campaigns)
    {
        echo '<div class="container">
            <div class="content-container">
                <section>
                    <div class="all-campaigns">
                        <h2>All Campaigns</h2>
                        <ul>';
        foreach ($campaigns as $campaign) {
            echo '<li>
                <strong>Campaign Name:</strong> ' . $campaign->name . '<br>
                <strong>Money Earned:</strong> $' . number_format($campaign->moneyEarned, 2) . '<br>
                <button onclick="campaignDetails(' . intval($campaign->id) . ')">View Details</button>
                <button onclick="joinCampaign(1,1)">Join Campaign</button>
            </li>';
        }
        echo '</ul>
                    </div>
                </section>
            </div>
        </div>';
    }

}

// Example Usage
$campaign = (object)[
    'id' => 1,
    'name' => 'Campaign A',
    'moneyEarned' => 10000,
    'startDate' => '2024-01-01',
    'donation' => 500,
];

$campaigns = [
    (object)['id' => 1, 'name' => 'Campaign A', 'moneyEarned' => 10000],
    (object)['id' => 2, 'name' => 'Campaign B', 'moneyEarned' => 5000],
];

$events = [
    (object)['name' => 'Event A', 'status' => 'Active'],
    (object)['name' => 'Event B', 'status' => 'Completed'],
];

$campaignManagerView = new CampaignView();
$campaignManagerView->CampaignViewDetails("");
// $campaignManagerView->displayCampaignDetails($campaign);
$campaignManagerView->displayFundProgress($campaign);
$campaignManagerView->displayAllCampaigns($campaigns);
