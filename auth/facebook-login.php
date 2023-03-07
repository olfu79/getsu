<?php
session_start();
require_once '../vendor/autoload.php';
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

// Store the user profile data in the session
session_regenerate_id();
$_SESSION['loggedin'] = TRUE;
$_SESSION['name'] = 'totalny test';
$_SESSION['id'] =  4;
$_SESSION['role'] =  'user';
$_SESSION['user_id'] = $userNode->getId();
$_SESSION['user_name'] = $userNode->getName();
$_SESSION['user_email'] = $userNode->getEmail();
$_SESSION['user_picture'] = $userNode->getPicture()->getUrl();

// Redirect the user to the home page
header('Location: ../index.php');
