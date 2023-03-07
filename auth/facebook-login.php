<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../scripts/db_con.php';
$fb = new Facebook\Facebook([
    'app_id' => '525836883062613',
    'app_secret' => '9cc99af630877cd1590258814e8169de',
    'default_graph_version' => 'v11.0',
]);
// Define the callback URL
$redirectUrl = 'http://localhost/getsu/auth/facebook-login.php';

// Get the access token from the callback URL
$accessToken = $fb->getRedirectLoginHelper()->getAccessToken($redirectUrl);

// Check if the access token exists
if (!$accessToken) {
    header('Location: ../login');
    exit;
}

// Set the access token
$fb->setDefaultAccessToken($accessToken);

// Get the user profile data
try {
    $response = $fb->get('/me?fields=id,name,email,picture');
    $userNode = $response->getGraphUser();
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    // Handle API errors
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    // Handle SDK errors
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}
$uid = $userNode->getId();
$get_user = $con->query("SELECT `facebook_id`, `id`, `username`, `role` FROM `accounts` WHERE `facebook_id`='$uid'");
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
    //$profile_pic = $userNode->getPicture()->getUrl();
    $insert = $con->Query("INSERT INTO `accounts`(`facebook_id`,`username`,`email`) VALUES('$uid','$full_name','$email')"); //$profile_pic
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
            header('Location: ../index.php');
            exit;
        }
    } else {
        echo "Location: ../login.php?e=error";
    }
}
