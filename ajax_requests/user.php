<?php

/**
 * This File is Called in AJAX Request
 * This File is Responsable For Listing Some User Info
 */

// Require The Config File
require_once '../config/config.php';

// If The Request is Sent Without 'user_id' Parameter
if (!(isset($_POST['user_id']))) {
  // Resend The User Back One Step Using JS
  echo '<script>window.history.back();</script>';
  exit;
}

// Sanitize Sent 'user_id' Parameter and Assign it to a Variable
$user_id = filter_var($_POST['user_id'], FILTER_SANITIZE_NUMBER_INT);

// Get User Info -- name && avatar --
$name = $user->get_user_info('name', $user_id);
$avatar = $user->get_user_info('avatar', $user_id);

// Put Info in Array
$user = array(
  'id' => $user_id,
  'name' => $name,
  'avatar' => $avatar,
);

// Output Data in JSON Format
echo json_encode($user);
