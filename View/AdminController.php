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
            <style>
                /* Reset styles */
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                    font-family: Arial, sans-serif;
                }
                
                body {
                    background-color: #f4f4f9;
                    color: #333;
                    font-family: Arial, sans-serif;
                }
                
                nav {
                    background-color: #343a40;
                    color: #ffffff;
                    padding: 1rem;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                }
                
                nav .logo {
                    font-size: 1.5rem;
                    font-weight: bold;
                }
                
                nav ul {
                    list-style: none;
                    display: flex;
                    gap: 1.5rem;
                }
                
                nav ul li a {
                    color: #ffffff;
                    text-decoration: none;
                    font-weight: 500;
                    transition: color 0.3s;
                }
                
                nav ul li a:hover {
                    color: #17a2b8;
                }
                
                .container {
                    padding: 2rem;
                    max-width: 900px;
                    margin: 0 auto;
                }
                
                h2 {
                    font-size: 1.75rem;
                    color: #343a40;
                    margin-bottom: 1rem;
                    font-weight: 600;
                }
                
                p {
                    line-height: 1.6;
                    color: #555;
                }
                
                ul {
                    list-style-type: none;
                    margin-top: 1rem;
                }
                
                li {
                    margin-bottom: 0.5rem;
                }
                
                section {
                    margin-top: 2rem;
                }
                
                .content-section {
                    display: none;
                    background-color: #ffffff;
                    padding: 1.5rem;
                    border-radius: 8px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                }
                
                #home {
                    text-align: center;
                    padding: 1.5rem;
                    background-color: #e9ecef;
                    border-radius: 8px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                }
                
                #home img {
                    margin-top: 1rem;
                    max-width: 100%;
                    border-radius: 8px;
                }
                
                .users-name, .donation-history, .donations-statistics, .event-report {
                    margin-top: 1rem;
                    padding: 1rem;
                    border-left: 5px solid #17a2b8;
                    background-color: #f8f9fa;
                    border-radius: 4px;
                }
                
                .event-report p {
                    margin-bottom: 0.5rem;
                    font-weight: 500;
                }
                
                /* Button styling */
                nav .checkbtn, .checkbtn {
                    display: none;
                }
                
                /* Responsive */
                @media (max-width: 768px) {
                    nav ul {
                        flex-direction: column;
                        display: none;
                        background-color: #343a40;
                    }
                
                    nav ul.show {
                        display: flex;
                    }
                
                    nav .checkbtn {
                        display: block;
                        font-size: 1.5rem;
                        color: #ffffff;
                        cursor: pointer;
                    }
                }
            </style>
        </head>
        <body>
            <nav>
                <input type="checkbox" id="check">
                <label for="check" class="checkbtn">
                    <i class="fas fa-bars"></i>
                </label>
                <label class="logo">Admin</label>
                <ul>
                    <li><a href="#" onclick="showSection(\'users-section\')">Users</a></li>
                    <li><a href="#" onclick="showSection(\'reports-section\')">Reports</a></li>
                    <li><a href="#" onclick="showSection(\'donations-section\')">Donations</a></li>
                    <li><a href="#" onclick="showSection(\'statistics-section\')">Statistics</a></li>
                </ul>
            </nav>
            <div class="container">
                <section>
                    <div id="home">
                        <h2>Welcome to the User Information System</h2>
                        <p>Select a menu item to view more details.</p>
                        <img src="assets/donation.jpg" alt="Welcome Image" style="max-width:100%; height:auto;">
                    </div>
                    <div id="content">
                        <div id="users-section" class="content-section">' . $this->displayUsers([]) . '</div>
                        <div id="donations-section" class="content-section">' . $this->displayDonations([]) . '</div>
                        <div id="statistics-section" class="content-section">' . $this->displayDonationStatistics([]) . '</div>
                        <div id="reports-section" class="content-section">' . $this->displayReports((object) []) . '</div>
                    </div>
                </section>
            </div>
            <script>
                function showSection(sectionId) {
                    // Hide the home section
                    document.getElementById("home").style.display = "none";
                    // Hide all content sections
                    document.querySelectorAll(".content-section").forEach(function(section) {
                        section.style.display = "none";
                    });
                    // Show the selected section
                    document.getElementById(sectionId).style.display = "block";
                }
            </script>
        </body>
        </html>';
    }


    public function displayUsers($users)
    {
        ob_start();
        echo '<div class="users-name">';
        echo '<h2>Users Name</h2>';
        echo '<ul>';
        foreach ($users as $user) {
            echo '<li>Name: ' . htmlspecialchars($user->name) . '</li>';
        }
        echo '</ul>';
        echo '</div>';
        return ob_get_clean();
    }

    public function displayDonations($donations)
    {
        ob_start();
        echo '<div class="donation-history">';
        echo '<h2>Donation History</h2>';
        echo '<ul>';
        foreach ($donations as $donation) {
            echo '<li>Date: ' . htmlspecialchars($donation->date) . ' - Amount: ' . htmlspecialchars($donation->amount) . '</li>';
        }
        echo '</ul>';
        echo '</div>';
        return ob_get_clean();
    }

    public function displayDonationStatistics($statistics)
    {
        ob_start();
        echo '<div class="donations-statistics">';
        echo '<h2>Donations Statistics</h2>';
        echo '<ul>';
        foreach ($statistics as $statistic) {
            echo '<li>Donation: ' . htmlspecialchars($statistic->amount) . '</li>';
        }
        echo '</ul>';
        echo '</div>';
        return ob_get_clean();
    }

    public function displayReports($event)
    {
        ob_start();
        echo '<div class="event-report">';
        echo '<h2>Event Report: ' . htmlspecialchars($event->name ?? 'N/A') . '</h2>';
        echo '<p><strong>Date:</strong> ' . htmlspecialchars($event->date ?? 'N/A') . '</p>';
        echo '<p><strong>Location:</strong> ' . htmlspecialchars($event->location ?? 'N/A') . '</p>';
        echo '<p><strong>Description:</strong> ' . htmlspecialchars($event->description ?? 'N/A') . '</p>';
        echo '<p><strong>Total Donations:</strong> ' . htmlspecialchars($event->totalDonations ?? 'N/A') . '</p>';
        echo '</div>';
        return ob_get_clean();
    }
}

// Sample data for testing the admin view
$sampleUsers = [
    (object) ['name' => 'Alice'],
    (object) ['name' => 'Bob'],
    (object) ['name' => 'Charlie']
];

$sampleDonations = [
    (object) ['date' => '2024-11-12', 'amount' => 100],
    (object) ['date' => '2024-11-10', 'amount' => 50]
];

$sampleStatistics = [
    (object) ['amount' => 100],
    (object) ['amount' => 50]
];

$sampleEvent = (object) [
    'name' => 'Charity Gala',
    'date' => '2024-11-12',
    'location' => 'New York',
    'description' => 'A charity event to raise funds.',
    'totalDonations' => 500
];

// Instantiate and display AdminView
$adminView = new AdminView();
$adminView->AdminViewDetails("Sample Data");


?>
