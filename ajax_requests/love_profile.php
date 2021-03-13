<?php

/**
 * This File is Called in AJAX Request
 * This File is Responsable For Liking or Disliking Profiles
 */

// Require The Config File
require_once '../config/config.php';

// If The Request is Sent Without 'profile_id' Parameter
if (!(isset($_POST['profile_id']))) {
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

// Sanitize Sent 'profile_id' Parameter and Assign it to a Variable
$profile_id = filter_var($_POST['profile_id'], FILTER_SANITIZE_NUMBER_INT);
// Get The Logged in User id
$user_id = $_SESSION['user_id'];

// Establish New Connection With DataBase
$connection = DB::connect();

// If The User Has Been Liked That Post Before
if ($user->user_liked_profile($user_id, $profile_id)) {
 // Remove Like From The profile_likes table
 $stmt = "DELETE FROM profile_likes WHERE user_id = '$user_id' AND profile_id = '$profile_id'";
 $connection->query($stmt);
 // For More Confortable UX
 $class = 'action like';
} else {
 // Add New Like To The profile_likes table
 $stmt = "INSERT INTO profile_likes (`user_id`, `profile_id`) VALUES ('$user_id', '$profile_id') ";
 $connection->query($stmt);
 // For More Confortable UX
 $class = 'action like liked';
}

// Get The Number Of Profile Likes
$result = DB::select('profile_likes', 'profile_id', $profile_id);
$likes = $result->num_rows;

// Update Likes Column in The users table
$stmt = "UPDATE users SET likes = '$likes' WHERE id = '$profile_id'";
$connection->query($stmt);

// Output Data in JSON Format
echo json_encode(array(
 'class' => $class,
 'likes' => $likes,
));
