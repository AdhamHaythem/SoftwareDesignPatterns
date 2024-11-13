<?php
class UserView
{
    public function userViewDetials($StdObj)
    { 
        echo '<!DOCTYPE html>
        <html lang="en" dir="ltr">
        <head>
            <meta charset="utf-8">
            <title>User</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="style.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        </head>
        <body>
            <nav>
                <label class="logo">Engedny</label>
                <ul>
                    <li><a href="#" onclick="loadHome()">Home</a></li>
                    <li><a href="#" onclick="loadProfile()">sign in</a></li>
                    <li><a href="#" onclick="loadDonationHistory()">sign up</a></li>
                </ul>
            </nav>
            <div class="container">
                <div id="home">
                    <h2>Welcome to Engedny website</h2>
                    <img src="assets\donation.jpg" alt="Welcome Image" style="max-width:100%;">
                </div>
            </div>
            </script>
        </body>
        </html>';
    }

        
    // Display user information
    public function displayUserInfo($user)
    {
        echo '<div class="user-info">';
        echo '<h2>User Information</h2>';
        echo '<p><strong>Name:</strong> ' . htmlspecialchars($user->name) . '</p>';
        echo '<p><strong>Email:</strong> ' . htmlspecialchars($user->email) . '</p>';
        echo '</div>';
    }

    // Display user's location
    public function displayLocation($user)
    {
        echo '<div class="user-location">';
        echo '<h2>User Location</h2>';
        echo '<p><strong>Location:</strong> ' . htmlspecialchars($user->location) . '</p>';
        echo '</div>';
    }

    // Display a list of all users
    public function displayAllUsers($users)
    {
        echo '<div class="user-list">';
        echo '<h2>All Users</h2>';
        echo '<ul>';
        foreach ($users as $user) {
            echo '<li>' . htmlspecialchars($user->name) . ' - ' . htmlspecialchars($user->email) . '</li>';
        }
        echo '</ul>';
        echo '</div>';
    }

}

$userView = new UserView();
$userView->userViewDetials("sample");
?>
