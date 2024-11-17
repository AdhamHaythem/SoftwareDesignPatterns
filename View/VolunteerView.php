<?php

class VolunteerView
{
    public function VolunteerViewDetails($StdObj)
    { 
        echo '<!DOCTYPE html>
        <html lang="en" dir="ltr">
        <head>
            <meta charset="utf-8">
            <title>Volunteer</title>
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
                <label class="logo">Volunteer Manager</label>
                <ul>
                    <li><a href="#" onclick="volunteerDetails(1)">Volunteer Details</a></li>
                    <li><a href="#" onclick="volunteerDetails(1)">All Volunteers</a></li>
                </ul>
            </nav>
            <div class="container">
                <section>
                    <div id="home">
                        <h2>Welcome to the Volunteer System</h2>
                        <p>Select a menu item to view more details.</p>
                        <img src="assets/donation.jpg" alt="Welcome Image" style="max-width:100%; height:auto;">
                    </div>
                </section>
            </div>
        </body>
        </html>';
    }

    public function displayVolunteerDetails($volunteer)
    {
        echo '<div class="container">
            <div class="content-container">
                <section>
                    <div class="volunteer-details">
                        <h2>Volunteer Details</h2>
                        <ul>
                            <li><strong>Volunteer ID:</strong> ' . htmlspecialchars($volunteer->id) . '</li>
                            <li><strong>Name:</strong> ' . htmlspecialchars($volunteer->name) . '</li>
                            <li><strong>Age:</strong> ' . intval($volunteer->age) . '</li>
                            <li><strong>Role:</strong> ' . htmlspecialchars($volunteer->role) . '</li>
                            <li><strong>Joined Date:</strong> ' . htmlspecialchars($volunteer->joinedDate) . '</li>
                        </ul>
                    </div>
                </section>
            </div>
        </div>';
    }

    public function displayAllVolunteers($volunteers)
    {
        echo '<div class="container">
            <div class="content-container">
                <section>
                    <div class="all-volunteers">
                        <h2>All Volunteers</h2>
                        <ul>';
        foreach ($volunteers as $volunteer) {
            echo '<li>
                <strong>Name:</strong> ' . htmlspecialchars($volunteer->name) . '<br>
                <strong>Role:</strong> ' . htmlspecialchars($volunteer->role) . '<br>
                <button onclick="volunteerDetails(' . intval($volunteer->id) . ')">View Details</button>
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
$volunteer = (object)[
    'id' => 1,
    'name' => 'John Doe',
    'age' => 30,
    'role' => 'Team Leader',
    'joinedDate' => '2023-05-10',
];

$volunteers = [
    (object)['id' => 1, 'name' => 'John Doe', 'role' => 'Team Leader'],
    (object)['id' => 2, 'name' => 'Jane Smith', 'role' => 'Coordinator'],
];

$volunteerView = new VolunteerView();
$volunteerView->VolunteerViewDetails("");
$volunteerView->displayVolunteerDetails($volunteer);
$volunteerView->displayAllVolunteers($volunteers);

?>
