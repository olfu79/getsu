<?php
session_start();
if (isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style/auth-style.css">
    <link rel="icon" type="image/png" href="logo/favicon.png" />
    <title>Getsu</title>
</head>

<body>
    <div class="wrapper vh100 v-mid h-mid">
        <div class="auth-card">
            <div class="auth-card-left">

            </div>
            <div class="auth-card-right">
                <h1>REGISTER</h1>
                <form method="post" action="scripts/authenticate-reg.php">
                    <label for="email">Email</label>
                    <input type="email" name="email" placeholder="Email" class="email" required>
                    <label for="username">Username</label>
                    <input type="text" name="username" placeholder="Username" class="username" required>
                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="Password" class="password" required>
                    <label for="repassword">Repeat password</label>
                    <input type="password" name="repassword" placeholder="Repeat password" class="repassword" required>
                    <div class="trd-login">
                        <input type="submit" value="Register">
                        <a href="http://www.google.com">
                            <div class="facebook-login"></div>
                        </a>
                        <a href="http://www.google.com">
                            <div class="google-login"></div>
                        </a>
                    </div>
                    <div class="switch-auth"><a href="login.php">Already have account? Login</a></div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>