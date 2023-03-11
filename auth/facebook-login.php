<?php
include './scripts/facebook_con.php';

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email'];
try {
    if (isset($_SESSION['facebook_access_token'])) {
        $accessToken = $_SESSION['facebook_access_token'];
    } else {
        $accessToken = $helper->getAccessToken();
    }
} catch (Exception $e) {
    header('Location: login.php?e=error');
    exit;
}
if (isset($accessToken)) {
    if (isset($_SESSION['facebook_access_token'])) {
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    } else {
        $_SESSION['facebook_access_token'] = (string) $accessToken;
        $oAuth2Client = $fb->getOAuth2Client();
        $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
        $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }
    if (isset($_GET['code'])) {
        try {
            $profile_request = $fb->get('/me?fields=name,first_name,last_name,email');
            $requestPicture = $fb->get('/me/picture?redirect=false&height=800');
            $pictureTemp = $requestPicture->getGraphUser();
            $profile = $profile_request->getGraphUser();
            $picture = $pictureTemp['url'];             // To Get Facebook picture url
            $fbid = $profile->getProperty('id');        // To Get Facebook ID
            $fullname = $profile->getProperty('name');  // To Get Facebook full name
            $email = $profile->getProperty('email');    //  To Get Facebook email

            $get_user = $con->query("SELECT `facebook_id`, `id`, `username`, `role` FROM `accounts` WHERE `facebook_id`='$fbid'");
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
                $insert = $con->Query("INSERT INTO `accounts`(`facebook_id`,`username`,`email`, `avatar`) VALUES('$fbid','$fullname','$email', '$picture')");
                if ($insert) {
                    $get_user = $con->query("SELECT `facebook_id`, `id`, `username`, `role` FROM `accounts` WHERE `facebook_id`='$fbid'");
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
                }
            }
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            session_destroy();
            header("Location: login.php?e=error");
            exit;
        } catch (Exception $e) {
            header('Location: login.php?e=error');
            exit;
        }
    }
} else {
    $loginUrl = $helper->getLoginUrl('https://localhost/getsu/login.php', $permissions);
}
