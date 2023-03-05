<?php
session_start();
if (isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}
require_once 'scripts/db_con.php';
require 'google-api/vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('802212988337-o44ofop2oatohdm31ospavmnjm1p8hj0.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/getsu/login.php');
$client->addScope("email");
$client->addScope("profile");
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($token["error"])) {
        $client->setAccessToken($token['access_token']);
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        $id = $con->real_escape_string($google_account_info->id);
        $full_name = $con->real_escape_string(trim($google_account_info->name));
        $email = $con->real_escape_string($google_account_info->email);
        $profile_pic = $con->real_escape_string($google_account_info->picture);
        $get_user = $con->query("SELECT `google_id`, `id`, `username`, `role` FROM `accounts` WHERE `google_id`='$id'");
        $result = $get_user->fetch_assoc();
        if ($get_user->num_rows > 0) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $result['username'];
            $_SESSION['id'] =  $result['id'];
            $_SESSION['role'] =  $result['role'];
            if ($_POST['remember'] == "on") {
                setcookie('remember', '1', time() + (7 * 24 * 60 * 60), '/');
            }
            header('Location: index.php');
            exit;
        } else {
            $insert = $con->Query("INSERT INTO `accounts`(`google_id`,`username`,`email`) VALUES('$id','$full_name','$email')"); //$profile_pic
            if ($insert) {
                $get_user = $con->query("SELECT `google_id`, `id`, `username`, `role` FROM `accounts` WHERE `google_id`='$id'");
                $result = $get_user->fetch_assoc();
                if ($get_user->num_rows > 0) {
                    session_regenerate_id();
                    $_SESSION['loggedin'] = TRUE;
                    $_SESSION['name'] = $result['username'];
                    $_SESSION['id'] =  $result['id'];
                    $_SESSION['role'] =  $result['role'];
                    if ($_POST['remember'] == "on") {
                        setcookie('remember', '1', time() + (7 * 24 * 60 * 60), '/');
                    }
                    header('Location: index.php');
                    exit;
                }
            } else {
                echo "Sign up failed!(Something went wrong).";
            }
        }
    } else {
        header('Location: login.php');
        exit;
    }
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
                <h1>LOGIN</h1>
                <form method="post" action="scripts/authenticate-log.php" id="form-login">
                    <div class="form-container">
                        <label for="username">Nazwa użytkownika lub email</label>
                        <input type="text" name="username" placeholder="Nazwa użytkownika" class="username" required>
                    </div>
                    <div class="form-container">
                        <label for="password">Hasło</label>
                        <input type="password" name="password" placeholder="Hasło" class="password" required>
                    </div>
                    <div class="remember-me">
                        <input type="checkbox" name="remember" class="remember">
                        <label for="remember">Zapamiętaj mnie</label>
                    </div>
                    <div class="trd-login">
                        <input type="submit" value="Login">
                        <a href="http://www.google.com">
                            <div class="facebook-login"></div>
                        </a>
                        <a href="<?php echo $client->createAuthUrl(); ?>">
                            <div class="google-login"></div>
                        </a>
                    </div>
                    <div class="switch-auth"><a href="register.php">Nie masz konta? Zarejestruj się</a></div>
                </form>
                <script src="scripts/login-validate.js"></script>
            </div>
        </div>
    </div>
</body>

</html>