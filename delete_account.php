<?php

// Require The Config File
require_once 'config/config.php';

// if The user_id is Sent To This File
// if The Delete CSRF Token Key is Sent
// if Session Delete CSRF Token Key is Set
if (!isset($_POST['user_id']) or !isset($_POST['token']) or !isset($_SESSION['delete_token'])) {
 // Redirect To The Profile Page
 header('location: ' . $main_url . 'profile.php');
 exit();
}

// Get and Sanitize Sent User id
$user_id = filter_var($_POST['user_id'], FILTER_SANITIZE_NUMBER_INT);

// Get and Sanitize Sent Token
$token = filter_var($_POST['token'], FILTER_SANITIZE_STRING);

// Check if User is Logged in
if (!$user->is_user_logged_in()) {
 // Redirect To The Login Page
 header('location: ' . DB::MAIN_URL . 'login.php');
 exit();
}

// Check Token
if (hash_equals($_SESSION['delete_token'], $token)) {
 // Delete Token Key
 unset($_SESSION['delete_token']);

 // Delete Account
 $user->delete_account($user_id);
}

// Redirect To The Profile Page
header('location: ' . $main_url . 'logout.php');
