<?php
class DonationManagerView
{
    public function DonationManagerViewDetails($donationID)
    { 
        echo '<!DOCTYPE html>
        <html lang="en" dir="ltr">
        <head>
            <meta charset="utf-8">
            <title>Donation Manager</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="style.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        </head>
        <body>
            <nav>
                <label class="logo">Donation Manager</label>
                <ul>
                    <li><a href="#" onclick="displayDonationDetails()">Donation Details</a></li>
                    <li><a href="#" onclick="displayDonationStatistics()">Donation Statistics</a></li>
                    <li><a href="#" onclick="displayCampaignDetails()">Campaign Details</a></li>
                    <li><a href="#" onclick="displayDonationReport()">Donation Report</a></li>
                </ul>
            </nav>
            <div class="container">
                <section>
                    <div id="content">
                        <div id="home">
                            <h2>Welcome to the Donation Management System</h2>
                            <p>Select a menu item to view more details.</p>
                            <img src="assets/donation.jpg" alt="Welcome Image" style="max-width:100%; height:auto;">
                        </div>
                    </div>
                </section>
            </div>
            <script src="script.js"></script>
        </body>
        </html>';
    }

    public function displayDonationDetails($donation)
    {
        echo '<div class="donation-details">';
        echo '<h2>Donation Details</h2>';
        echo '<ul>';
        echo '<li><strong>Donation ID:</strong> ' . htmlspecialchars($donation->id) . '</li>';
        echo '<li><strong>Amount:</strong> ' . htmlspecialchars($donation->amount) . '</li>';
        echo '<li><strong>Date:</strong> ' . htmlspecialchars($donation->date) . '</li>';
        echo '<li><strong>Donor Name:</strong> ' . htmlspecialchars($donation->donorName) . '</li>';
        echo '</ul>';
        echo '</div>';

        echo "<script>function donationDetails(itemId) {
            $.ajax({
                url: 'SoftwareDesignPatterns/Controller/DonationManagerController.php',
                type: 'POST',
                data: {
                    donationId: \'<?php echo $donation -> donationId ?>\',
                    donationDetails: \'\',
                },
            });
        };</script>";
    }

    public function displayDonationStatistics($statistics)
    {
        echo '<div class="donations-statistics">';
        echo '<h2>Donation Statistics</h2>';
        foreach ($statistics as $statistic) {
            echo '<li>Donation: ' . htmlspecialchars($statistic->amount) . ' - Statistics: ' . htmlspecialchars($statistic->statistic) . '</li>';
        }
        echo '</div>';

        echo "<script>function viewDonationStatistics(itemId) {
            $.ajax({
                url: 'SoftwareDesignPatterns/Controller/DonationManagerController.php',
                type: 'POST',
                data: {
                    viewDonationStatistics: \'\',
                },
            });
        };</script>";
    }

    public function displayCampaignDetails($campaignID)
    {
        echo '<div class="campaign-details">';
        echo '<h2>Campaign Details</h2>';
        echo '<p>Details about campaign ID: ' . htmlspecialchars($campaignID) . '</p>';
        echo '</div>';

        echo "<script>function viewCampaignDetails(itemId) {
            $.ajax({
                url: 'SoftwareDesignPatterns/Controller/DonationManagerController.php',
                type: 'POST',
                data: {
                    campaignsID: \'<?php echo $campaignID -> campaignsID ?>\',
                    viewCampaignDetails: \'\',
                },
            });
        };</script>";
    }

    public function displayDonationReport()
    {
        echo '<div class="donation-report">';
        echo '<h2>Donation Report</h2>';
        echo '<p>Summary of donation activities.</p>';
        echo '</div>';

        echo "<script>function generateDonationReport(itemId) {
            $.ajax({
                url: 'SoftwareDesignPatterns/Controller/DonationManagerController.php',
                type: 'POST',
                data: {
                    generateDonationReport: \'\',
                },
            });
        };</script>";
    }
}

$donationManagerView = new DonationManagerView();
$donationManagerView->DonationManagerViewDetails("Sample Data");
?>
