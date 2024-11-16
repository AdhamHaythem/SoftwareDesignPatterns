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
                    <li><a href="#" onclick="displaylogin()">sign in</a></li>
                    <li><a href="#" onclick="displaysignUp()">sign up</a></li>
                </ul>
            </nav>
            <div class="container">
                <div id="home">
                    <h2>Welcome to Engedny website</h2>
                    <img src="assets\donation.jpg" alt="Welcome Image" style="max-width:100%;">
                </div>
            </div>
            <script src="javascript.js"></script>
        </body>
        </html>';
    }

    public function signIn()
    {
        echo '
        <link rel="stylesheet" href="style.css">
        <div class="container">
            <form action="UserController.php" method="POST">
                <h2>Sign In</h2>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control" id="exampleInputEmail1" required>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="exampleInputPassword1" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Remember me</label>
                </div>
                <button type="submit" name="login" class="btn btn-primary" onclick= login()>Sign In</button>
            </form>
        </div>';
    }

public function signUp()
{
    echo '
    <link rel="stylesheet" href="style.css">
    <div class="container">
        <form class="row g-3" action="UserController.php" method="POST">
            <h2>Sign Up</h2>
            <div class="col-md-6">
                <label for="validationServer01" class="form-label">First name</label>
                <input type="text" name="firstname" class="form-control" id="validationServer01" required>
            </div>
            <div class="col-md-6">
                <label for="validationServer02" class="form-label">Last name</label>
                <input type="text" name="lastname" class="form-control" id="validationServer02" required>
            </div>
            <div class="col-md-12">
                <label for="validationServerEmail" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="validationServerEmail" required>
            </div>
            <div class="col-md-12">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" id="username" required>
            </div>
            <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
            </div>
            <div class="col-md-6">
                <label for="phoneNumber" class="form-label">Phone Number</label>
                <input type="text" name="phoneNumber" class="form-control" id="phoneNumber" required>
            </div>
            <div class="col-md-6">
                <label for="location" class="form-label">Location</label>
                <input type="text" name="location" class="form-control" id="location" required>
            </div>
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="invalidCheck3" required>
                    <label class="form-check-label" for="invalidCheck3">Agree to terms and conditions</label>
                </div>
            </div>
            <button class="btn btn-primary" type="submit" name="signup" onclick= signup()>Sign Up</button>
        </form>
    </div>';
    }

}

$userView = new UserView();
$userView->userViewDetials("sample");
?>
