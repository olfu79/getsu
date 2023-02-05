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
    <title>Project</title>
</head>

<body>
    <div class="wrapper vh100 v-mid h-mid">
        <div class="auth-card">
            <div class="auth-card-left">

            </div>
            <div class="auth-card-right">
                <h1>LOGIN</h1>
                <form method="post" action="scripts/authenticate-log.php">
                    <label for="username">Username</label>
                    <input type="text" name="username" placeholder="Username" class="username" required>
                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="Password" class="password" required>
                    <div class="remember-me">
                        <input type="checkbox" name="remember" class="remember">
                        <label for="remember">Stay logged in</label>
                    </div>
                    <div class="trd-login">
                        <input type="submit" value="Login">
                        <a href="http://www.google.com">
                            <div class="facebook-login"></div>
                        </a>
                        <a href="http://www.google.com">
                            <div class="google-login"></div>
                        </a>
                    </div>
                    <div class="switch-auth"><a href="register.php">Don't have account? Register</a></div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>