<?php
$client = new Google_Client();
require_once './scripts/google_con.php';
$client->setClientId($clientId);
$client->setClientSecret($secret);
$client->setRedirectUri($redirect);
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
        $profile_pic = $con->real_escape_string(str_replace('s96-c', 's1000', $google_account_info->picture));
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
            $get_user = $con->query("SELECT `id`, `email` FROM `accounts` WHERE `email`='$email'");
            $result = $get_user->fetch_assoc();
            if ($get_user->num_rows > 0) {
                header('Location: login.php?e=emailexist');
                exit;
            }
            $insert = $con->Query("INSERT INTO `accounts`(`google_id`,`username`,`email`, `avatar`) VALUES('$id','$full_name','$email', '$profile_pic')");
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
                header("Location: login.php?e=error");
                exit;
            }
        }
    } else {
        header("Location: login.php?e=error");
    }
}
