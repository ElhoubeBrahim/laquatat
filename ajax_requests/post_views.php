<?php

/**
 * This File is Called in AJAX Request
 * This File is Responsable For Adding Post Views
 */

// Require The Config File
require_once '../config/config.php';

// If The Request is Sent Without 'post_id' Parameter
if (!(isset($_POST['post_id']))) {
 // Resend The User Back One Step Using JS
 echo '<script>window.history.back();</script>';
 exit;
}

// Prevent None Logged in Users To Access
if (!$user->is_user_logged_in()) {
 // Redirect To The Login Page
 header('location: ' . DB::MAIN_URL . 'login.php');
 exit;
}

// Sanitize Sent 'post_id' Parameter and Assign it to a Variable
$post_id = filter_var($_POST['post_id'], FILTER_SANITIZE_NUMBER_INT);
// Get The Logged in User id
$user_id = $_SESSION['user_id'];

// Establish New Connection With DataBase
$connection = DB::connect();

// Check if The User Has Been Viewed The Post Before
$stmt = "SELECT * FROM post_views WHERE user_id = '$user_id' AND post_id = '$post_id'";
$result = $connection->query($stmt);

// If Not
if (!($result->num_rows > 0)) {
 // Add New View To The post_views table
 $stmt = "INSERT INTO post_views (`user_id`, `post_id`) VALUES ('$user_id', '$post_id') ";
 $connection->query($stmt);
}

// Get The Number Of Post Views
$result = DB::select('post_views', 'post_id', $post_id);
$views = $result->num_rows;

// Update Views Column in The posts table
$stmt = "UPDATE posts SET views = '$views' WHERE id = '$post_id'";
$connection->query($stmt);

// Output The Post Views Number
echo $views;
