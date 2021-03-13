<?php

/**
 * This File is Called in AJAX Request
 * This File is Responsable For Liking or Disliking Posts
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

// If The User Has Been Liked That Post Before
if ($post->user_liked_post($user_id, $post_id)) {
 // Remove Like From The post_likes table
 $stmt = "DELETE FROM post_likes WHERE user_id = '$user_id' AND post_id = '$post_id'";
 $connection->query($stmt);
 // For More Confortable UX
 $class = 'ri-heart-line';
} else {
 // Add New Like To The post_likes table
 $stmt = "INSERT INTO post_likes (`user_id`, `post_id`) VALUES ('$user_id', '$post_id') ";
 $connection->query($stmt);
 // For More Confortable UX
 $class = 'ri-heart-fill liked';
}

// Get The Number Of Post Likes
$result = DB::select('post_likes', 'post_id', $post_id);
$likes = $result->num_rows;

// Update Likes Column in The posts table
$stmt = "UPDATE posts SET likes = '$likes' WHERE id = '$post_id'";
$connection->query($stmt);

// Output Data in JSON Format
echo json_encode(array(
 'class' => $class,
 'likes' => $likes,
));
