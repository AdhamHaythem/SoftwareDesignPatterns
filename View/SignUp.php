<?php
class SignUp
{
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
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="invalidCheck3" required>
                        <label class="form-check-label" for="invalidCheck3">Agree to terms and conditions</label>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit" name="signup">Sign Up</button>
            </form>
        </div>
        function sign(itemId) {
            $.ajax({
                url: <?php echo \"SoftwareDesignPatterns/View/UserController.php" ?>,
                type: \'POST\',
                data: {
                    username: \'<?php echo $username ?>\',
                    firstname: \'<?php echo $firstname ?>\',
                    lastname: \'<?php echo $lastname ?>\',
                    userId: \'<?php echo $userId ?>\',
                    email: \'<?php echo $email ?>\',
                    password: \'<?php echo $password ?>\',
                    location: \'<?php echo $location ?>\',
                    phoneNumber: \'<?php echo $phoneNumber ?>\',
                    signup: \'\',
                    },
                });
            };
        <\script>';
    }
}

$userView = new SignUp();
$userView->signUp();