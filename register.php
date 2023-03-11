<?php
session_start();
if (isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}
require_once 'scripts/db_con.php';
require 'google-api/vendor/autoload.php';
require_once 'auth/google-login.php';
//require_once 'auth/facebook-login.php';
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style/auth-style.css">
    <link rel="icon" type="image/png" href="logo/favicon.png" />
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <link href="node_modules/noty/lib/noty.css" rel="stylesheet">
    <link href="node_modules/noty/lib/themes/relax.css" rel="stylesheet">
    <script src="node_modules/noty/lib/noty.js" type="text/javascript"></script>
    <script type="text/javascript" src="scripts/notifications.js"></script>
    <title>Getsu</title>
</head>

<body>
    <div class="wrapper vh100 v-mid h-mid">
        <div class="auth-card">
            <div class="auth-card-left">

            </div>
            <div class="auth-card-right">
                <h1>REJESTRACJA</h1>
                <form method="post" action="scripts/authenticate-reg.php" id="form-register">
                    <div class="form-container">
                        <label for="email">Email</label>
                        <input type="email" name="email" placeholder="Email" class="email" required>
                    </div>
                    <div class="form-container">
                        <label for="username">Nazwa użytkownika</label>
                        <input type="text" name="username" placeholder="Nazwa użytkownika" class="username" required>
                    </div>
                    <div class="form-container">
                        <label for="password">Hasło</label>
                        <input type="password" name="password" placeholder="Hasło" class="password" required>
                    </div>
                    <div class="form-container">
                        <label for="repassword">Powtórz hasło</label>
                        <input type="password" name="repassword" placeholder="Powtórz hasło" class="repassword" required>
                    </div>
                    <div class="trd-login">
                        <input type="submit" value="Rejestracja">
                        <!-- <a href="<?php //echo $loginUrl; 
                                        ?>">
                                        <div class="facebook-login"></div>
                                    </a> -->
                        <a href="<?php echo $client->createAuthUrl(); ?>">
                            <div class="google-login"></div>
                        </a>
                    </div>
                    <div class="switch-auth"><a href="login.php">Masz już konto? Zaloguj się</a></div>
                </form>
                <script src="scripts/register-validate.js"></script>
            </div>
        </div>
    </div>
</body>

</html>