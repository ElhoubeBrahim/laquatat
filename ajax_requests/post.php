<?php

/**
 * This File is Called in AJAX Request
 * This File is Responsable For Listing a Post Information, Basing on Given id
 */

// Require The Config File
require_once '../config/config.php';

// If The Request is Sent Without 'post_id' Parameter
if (!(isset($_POST['post_id']))) {
 // Resend The User Back One Step Using JS
 echo '<script>window.history.back();</script>';
 exit;
}

// Sanitize Sent 'post_id' Parameter and Assign it to a Variable
$post_id = filter_var($_POST['post_id'], FILTER_SANITIZE_NUMBER_INT);

// Get Post Info Basing on The id
$user_id = $post->get_post_info('user_id', $post_id);
$picture = $post->get_post_info('picture', $post_id);
$alt = $post->get_post_info('alt', $post_id);
$description = $post->get_post_info('description', $post_id);
$privacy = $post->get_post_info('privacy', $post_id);
$likes = $post->get_post_info('likes', $post_id);
$views = $post->get_post_info('views', $post_id);

// Get Logged in User id
if (isset($_SESSION['user_id'])) {
 $logged_user_id = $_SESSION['user_id'];
} else {
 $logged_user_id = '';
}

// Check if The Logged in User Has Been Liked That Post Before
$user_liked_post = $post->user_liked_post($logged_user_id, $post_id);

// Put All info Above in Array
$post = array(
 'id' => $post_id,
 'user_id' => $user_id,
 'picture' => $picture,
 'alt' => $alt,
 'description' => $description,
 'privacy' => $privacy,
 'likes' => $likes,
 'views' => $views,
 'logged_user_id' => $logged_user_id,
 'user_liked_post' => $user_liked_post,
);

// Output Data in JSON Format
echo json_encode($post);
