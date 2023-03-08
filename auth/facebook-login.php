<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../scripts/db_con.php';
$fb = new Facebook\Facebook([
    'app_id' => '525836883062613',
    'app_secret' => '9cc99af630877cd1590258814e8169de',
    'default_graph_version' => 'v16.0',
]);
$helper = $fb->getRedirectLoginHelper();
$redirectUrl = 'http://localhost/getsu/auth/facebook-login.php';
$accessToken = $helper->getAccessToken($redirectUrl);
$logoutUrl = $helper->getLogoutUrl($accessToken, "http://localhost/getsu/login.php");

if (!$accessToken) {
    header('Location: ../login.php');
    exit;
}

$fb->setDefaultAccessToken($accessToken);

try {
    $response = $fb->get('/me?fields=id,name,email,picture');
    $userNode = $response->getGraphUser();
    $pictureResponse = $fb->get('/me/picture?redirect=false&height=1000&type=large&width=1000');
    $picture = $pictureResponse->getGraphUser();
    $profile_pic = $picture['url'];
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    header("Location: ../login.php?e=error");
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    header("Location: ../login.php?e=error");
    exit;
}
$uid = $userNode->getId();
$get_user = $con->query("SELECT `facebook_id`, `id`, `username`, `role` FROM `accounts` WHERE `facebook_id`='$uid'");
$result = $get_user->fetch_assoc();
if ($get_user->num_rows > 0) {
    session_regenerate_id();
    $_SESSION['loggedin'] = TRUE;
    $_SESSION['fblogout'] = $logoutUrl;
    $_SESSION['name'] = $result['username'];
    $_SESSION['id'] =  $result['id'];
    $_SESSION['role'] =  $result['role'];
    if ($_POST['remember'] == "on") {
        setcookie('remember', '1', time() + (7 * 24 * 60 * 60), '/');
    }
    header('Location: ../index.php');
    exit;
} else {
    $email = $userNode->getEmail();
    $get_user = $con->query("SELECT `id`, `email` FROM `accounts` WHERE `email`='$email'");
    $result = $get_user->fetch_assoc();
    if ($get_user->num_rows > 0) {
        header('Location: ../login.php?e=emailexist');
        exit;
    }
    $full_name = $userNode->getName();
    $insert = $con->Query("INSERT INTO `accounts`(`facebook_id`,`username`,`email`, `avatar`) VALUES('$uid','$full_name','$email', '$profile_pic')");
    if ($insert) {
        $get_user = $con->query("SELECT `facebook_id`, `id`, `username`, `role` FROM `accounts` WHERE `facebook_id`='$uid'");
        $result = $get_user->fetch_assoc();
        if ($get_user->num_rows > 0) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['fblogout'] = $logoutUrl;
            $_SESSION['name'] = $result['username'];
            $_SESSION['id'] =  $result['id'];
            $_SESSION['role'] =  $result['role'];
            if ($_POST['remember'] == "on") {
                setcookie('remember', '1', time() + (7 * 24 * 60 * 60), '/');
            }
            header('Location: ../index.php');
            exit;
        }
    } else {
        header("Location: ../login.php?e=error");
    }
}
