<?php
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
