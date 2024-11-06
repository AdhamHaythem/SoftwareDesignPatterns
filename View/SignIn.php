<?php
class SignIn
{
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
                <button type="submit" name="login" class="btn btn-primary">Sign In</button>
            </form>
        </div>
        <script>
        function login(itemId) {
            $.ajax({
                url: <?php echo \"SoftwareDesignPatterns/View/UserController.php" ?>,
                type: \'POST\',
                data: {
                    email: \'<?php echo $email ?>\',
                    password: \'<?php echo $password ?>\',
                    login: \'\',
                    },
                });
            };
        <\script>';
    }
}

$userView = new SignIn();
$userView->signIn();