<?php

/**
 * This Class Allows Us To Manage Users & Their Actions -- Register, Login, Logout --
 */

class User extends DB {

 use Upload;

 // Stores All Unexpected Validation Errors
 public $errors = array();

 /**
  * This Function Runs When The User Tries To Create New Account :
  *   => Validate Sent Data
  *   => If There is No Error
  *     => Create New Account
  *     => Store Sterilized Data
  *     => Start New User Session
  *     => Redirect The User To The Dashboard
  *
  * @param [array] $user_data
  * @return void
  */
 public function register_user($user_data) {

  // Start Backend Validation
  if (empty($user_data['name'])) {
   $this->errors['name'] = 'رجاء لا تترك هذا الحقل فارغا';
  } elseif (strlen($user_data['name']) < 5) {
   $this->errors['name'] = 'يجب على اسم المستخدم أن يتكون من 5 أحرف فأكثر';
  }

  if (str_word_count($user_data['bio']) > 150) {
   $this->errors['bio'] = 'يسمح فقط بـ 150 كلمة أو أقل في النبذة الخاصة بك';
  }

  if (!filter_var($user_data['email'], FILTER_VALIDATE_EMAIL)) {
   $this->errors['email'] = 'رجاء أدخل بريدا إلكترونيا صالحا';
  }

  if (empty($user_data['password'])) {
   $this->errors['password'] = 'رجاء لا تترك هذا الحقل فارغا';
  } elseif (strlen($user_data['password']) < 8) {
   $this->errors['password'] = 'يجب على كلمة السر أن تتكون من 8 أحرف فأكثر';
  }

  $sexe = !in_array($user_data['sexe'], ['male', 'female']) ? 'male' : $user_data['sexe'];

  if (empty($this->errors)) { // If There is No Error
   // Sanitize Data
   $name = filter_var($user_data['name'], FILTER_SANITIZE_STRING);
   $bio = filter_var($user_data['bio'], FILTER_SANITIZE_STRING);
   $email = filter_var($user_data['email'], FILTER_SANITIZE_EMAIL);
   $password = sha1(filter_var($user_data['password'], FILTER_SANITIZE_STRING));
   $avatar = $sexe . '.png';

   if (!empty($_FILES['avatar']['tmp_name'])) {
    $image = $_FILES['avatar'];
    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    $user_dir = str_replace(' ', '_', $name) . '_' . time() . '/';

    $this->extensions = ['png', 'jpg', 'jpeg', 'gif'];
    $this->upload_dir = 'assets/images/profile/' . $user_dir;
    $this->upload_name = 'profile.' . $ext;
    if ($this->upload($image)) {
     $avatar = $user_dir . $this->upload_name;
    }
   }

   // Check if The User Email Exited BEfore
   $result = $this->select('users', 'email', $email);
   if ($result->num_rows > 0) {
    $this->errors['email'] = 'لقد تم إنشاء حساب بهذا البريد الإلكتروني من قبل، جرب بريدا إلكترونيا جديدا';
    return;
   }

   // Create New Account
   $this->add_new_user($name, $email, $avatar, $bio, $password, $sexe);

  }

 }

 /**
  * This Function Runs When User Tries To Log into His Account :
  *   => Validate Sent Data
  *   => If There is No Error:
  *     => Sanitize Data
  *     => Check Password
  *     => Start New User Session
  *     => Redirect User To His Profile
  *
  * @param [array] $user_data
  * @return void
  */
 public function login($user_data) {
  // Start Backend Validation
  if (!filter_var($user_data['email'], FILTER_VALIDATE_EMAIL)) {
   $this->errors['email'] = 'رجاء أدخل بريدا إلكترونيا صالحا';
  }

  if (empty($user_data['password'])) {
   $this->errors['password'] = 'رجاء لا تترك هذا الحقل فارغا';
  } elseif (strlen($user_data['password']) < 8) {
   $this->errors['password'] = 'يجب على كلمة السر أن تتكون من 8 أحرف فأكثر';
  }

  if (empty($this->errors)) {
   // Sanitize Data
   $email = filter_var($user_data['email'], FILTER_SANITIZE_EMAIL);
   $password = sha1(filter_var($user_data['password'], FILTER_SANITIZE_STRING));

   if ($this->check_password($email, $password)) {
    $connection = DB::connect();

    // Get User ID
    $stmt = "SELECT id FROM users WHERE `email` = '$email'";
    $result = $connection->query($stmt);

    // Create New Session User Variable
    $_SESSION['user_id'] = $result->fetch_assoc()['id'];

    // Redirect To Profile Page
    $profile_url = DB::MAIN_URL . 'profile.php';
    header('location: ' . $profile_url);
   }
  }
 }

 /**
  * This Function Will Check If The Email Existed in The DB & If The Password is Correct
  *
  * @param [string] $email
  * @param [string] $password
  * @return void
  */
 protected function check_password($email, $password) {

  // Check if The User Email Exited Before
  $result = $this->select('users', 'email', $email);
  if ($result->num_rows <= 0) {
   $this->errors['email'] = 'لا يوجد حساب بهذا البريد الإلكتروني، جرب إنشاء حساب جديد';
   return false;
  }

  // Check The Password
  if (!($password === $result->fetch_assoc()['password'])) {
   $this->errors['password'] = 'كلمة السر غير صحيحة';
   return false;
  }

  return true;
 }

 /**
  * Check If There is Error in The Errors Array With The Entered Key
  *
  * @param [string] $key
  * @return void
  */
 public function error_exists($key) {

  if (!array_key_exists($key, $this->errors)) {
   return false;
  }

  return true;
 }

 /**
  * Output The Error If Exists in The Errors Array
  *
  * @param [string] $key
  * @return void
  */
 public function check_error($key) {
  if ($this->error_exists($key)) {
   echo '<div class="error">' . $this->errors[$key] . '</div>';
  }
 }

 /**
  * Add New User To The Database, According To The Entered Data
  *
  * @param [string] $name
  * @param [string] $email
  * @param [string] $avatar
  * @param [string] $bio
  * @param [string] $password
  * @param [string] $sexe
  * @return void
  */
 protected function add_new_user($name, $email, $avatar, $bio, $password, $sexe) {

  $activation_key = '';

  // Generate Email Activation Key
  for ($i = 0; $i < 6; $i++) {
   $activation_key = $activation_key . rand(0, 9);
  }

  // Connect To DB
  $connection = DB::connect();
  // Insert New User Data Into Users Table
  $stmt = "INSERT INTO users
  (`name`, `email`, `avatar`, `bio`, `password`, `sexe`, `theme`, `account_type`, `activation_key`)
  VALUES
  ('$name', '$email', '$avatar', '$bio', '$password', '$sexe', 'light', 'user', '$activation_key')";

  if ($connection->query($stmt) === false) {
   echo '<script>alert("لا نستطيع إضافة مستخدم جديد الآن، يمكنك الإتصال بفريق الدعم أو المحاولة لاحقا")</script>';
   return;
  }

  // Get User ID
  $stmt = "SELECT id FROM users WHERE `email` = '$email'";
  $result = $connection->query($stmt);

  // Create New Session User Variable
  $_SESSION['user_id'] = $result->fetch_assoc()['id'];

  // Redirect To Profile Page
  $profile_url = DB::MAIN_URL . 'profile.php';
  header('location: ' . $profile_url);

 }

 /**
  * This Function Will Get Any Column Passed as $key Parameter, When The user id is $id Parameter
  *
  * @param [String] $key
  * @param [Integer] $id
  * @return void
  */
 public function get_user_info($key, $id) {
  $connection = DB::connect();
  $stmt = "SELECT $key FROM users WHERE `id` = '$id'";
  $result = $connection->query($stmt);

  return $result->fetch_assoc()[$key];
 }

 /**
  * Check If The User is Logged in By Checking Session Variable
  *
  * @return boolean
  */
 public static function is_user_logged_in() {
  if (isset($_SESSION['user_id'])) {
   return true;
  }

  return false;
 }

 /**
  * Destroy The Session & Redirect The User To The Website's Main Page
  *
  * @return void
  */
 public function logout() {
  session_unset();
  session_destroy();
  header('location: ' . DB::MAIN_URL);
 }

 /**
  * This Function Will Check if a User Likes Given Profile
  *
  * @param [integer] $user_id
  * @param [integer] $profile_id
  * @return bool
  */
 public function user_liked_profile($user_id, $profile_id) {

  // Connect To The DataBase
  $connection = DB::connect();

  // Select All Rows From profile_likes Table Where Coditions Are Verified
  $stmt = "SELECT * FROM profile_likes WHERE user_id = '$user_id' AND profile_id = '$profile_id'";
  $result = $connection->query($stmt);

  // If There is Some Results
  if ($result->num_rows > 0) {
   return true;
  }

  // If Not
  return false;
 }

 /**
  * This Function is Called When The User Sent a Change Password Request
  *
  * @param [array] $data
  * @param [integer] $user_id
  * @return void
  */
 public function change_password($data, $user_id) {

  // Start Backend Validation
  if (empty($data['old-pwd'])) {
   $this->errors['old-pwd'] = 'رجاء لا تترك هذا الحقل فارغا';
  }

  if (empty($data['new-pwd'])) {
   $this->errors['new-pwd'] = 'رجاء لا تترك هذا الحقل فارغا';
  } elseif (strlen($data['new-pwd']) < 8) {
   $this->errors['new-pwd'] = 'يجب على كلمة السر أن تتكون من 8 أحرف فأكثر';
  }

  if ($data['confirm-pwd'] != $data['new-pwd']) {
   $this->errors['confirm-pwd'] = 'كلمتا السر غير متطابقتان';
  }

  if (empty($this->errors)) {
   // Sanitize Data
   $old_pwd = sha1(filter_var($data['old-pwd'], FILTER_SANITIZE_STRING));
   $new_pwd = sha1(filter_var($data['new-pwd'], FILTER_SANITIZE_STRING));

   $user = $this->select('users', 'id', $user_id)->fetch_assoc();

   if ($user['password'] === $old_pwd) { // If Passwords Are Mached
    $connection = DB::connect();

    // Update The Password
    $stmt = "UPDATE users SET password = '$new_pwd' WHERE id = '$user_id'";
    $result = $connection->query($stmt);

    // Redirect To Profile Page
    $profile_url = DB::MAIN_URL . 'profile.php';
    header('location: ' . $profile_url);

   } else {
    $this->errors['old-pwd'] = 'كلمة السر غير صحيحة';
   }

  }

 }

 /**
  * This Function is Used To Update User Profile
  *
  * @param [array] $user_data
  * @param [integer] $user_id
  * @return void
  */
 public function update_user_profile($user_data, $user_id) {

  // Start Backend Validation
  if (empty($user_data['name'])) {
   $this->errors['name'] = 'رجاء لا تترك هذا الحقل فارغا';
  } elseif (strlen($user_data['name']) < 5) {
   $this->errors['name'] = 'يجب على اسم المستخدم أن يتكون من 5 أحرف فأكثر';
  }

  if (str_word_count($user_data['bio']) > 150) {
   $this->errors['bio'] = 'يسمح فقط بـ 150 كلمة أو أقل في النبذة الخاصة بك';
  }

  if (!filter_var($user_data['email'], FILTER_VALIDATE_EMAIL)) {
   $this->errors['email'] = 'رجاء أدخل بريدا إلكترونيا صالحا';
  }

  if (empty($this->errors)) { // If There is No Error
   // Sanitize Data
   $name = filter_var($user_data['name'], FILTER_SANITIZE_STRING);
   $bio = filter_var($user_data['bio'], FILTER_SANITIZE_STRING);
   $email = filter_var($user_data['email'], FILTER_SANITIZE_EMAIL);

   // Get Old User Pictures
   $avatar = $this->get_user_info('avatar', $user_id);
   $cover = $this->get_user_info('cover', $user_id);

   // If The User Uploaded a New Avatar
   if (!empty($_FILES['avatar']['tmp_name'])) {
    // Get The Avatar
    $image = $_FILES['avatar'];
    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);

    // If The User Has Not an Image Folder Before
    if (in_array($avatar, ['male.png', 'female.png'])) {
     // Create a New One
     $user_dir = str_replace(' ', '_', $name) . '_' . time() . '/';
    } else { // If Not
     // Use The Old One
     $user_dir = pathinfo($avatar, PATHINFO_DIRNAME) . '/';
    }

    // Fill Important Properties
    $this->extensions = ['png', 'jpg', 'jpeg', 'gif'];
    $this->upload_dir = 'assets/images/profile/' . $user_dir;
    $this->upload_name = 'profile.' . $ext;

    // Upload The New Image
    if ($this->upload($image)) {
     $avatar = $user_dir . $this->upload_name;
    }
   }

   // If The User Uploaded a New Cover Photo
   if (!empty($_FILES['cover']['tmp_name'])) {
    // Get The Image
    $image = $_FILES['cover'];
    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);

    // If The User Has Not an Image Folder Before
    if (in_array($avatar, ['male.png', 'female.png'])) {
     // Create a New One
     $user_dir = str_replace(' ', '_', $name) . '_' . time() . '/';
    } else { // If Not
     // Use The Old One
     $user_dir = pathinfo($avatar, PATHINFO_DIRNAME) . '/';
    }

    // Fill Important Properties
    $this->extensions = ['png', 'jpg', 'jpeg', 'gif'];
    $this->upload_dir = 'assets/images/profile/' . $user_dir;
    $this->upload_name = 'cover.' . $ext;

    // Upload The New Image
    if ($this->upload($image)) {
     $cover = $user_dir . $this->upload_name;
    }
   }

   // Connect To DB
   $connection = DB::connect();

   // Update users Table
   $stmt = "UPDATE users SET
     name = '$name',
     email = '$email',
     bio = '$bio',
     avatar = '$avatar',
     cover = '$cover' WHERE id = '$user_id'";

   // If The User Profile Wans't Updated SuccessFully
   if ($connection->query($stmt) === false) {
    echo '<script>alert("لا نستطيع تحديث معلوماتك الآن، يمكنك الإتصال بفريق الدعم أو المحاولة لاحقا")</script>';
    return;
   }

   // Redirect To Profile Page
   $profile_url = DB::MAIN_URL . 'profile.php';
   //header('location: ' . $profile_url);

  }
 }

 /**
  * This Function is Called To Delete a User Account Immidiatly
  *
  * @param [integer] $user_id
  * @return void
  */
 public function delete_account($user_id) {
  // Connect To The DataBase
  $connection = DB::connect();

  // Delete Account
  $stmt = "DELETE FROM users WHERE id = '$user_id'";
  $connection->query($stmt);

  // Delete Profile Likes
  $stmt = "DELETE FROM profile_likes WHERE user_id = '$user_id'";
  $connection->query($stmt);

  $stmt = "DELETE FROM profile_likes WHERE profile_id = '$user_id'";
  $connection->query($stmt);

  // Delete Post Likes
  $stmt = "DELETE FROM post_likes WHERE user_id = '$user_id'";
  $connection->query($stmt);

  // Delete Post Views
  $stmt = "DELETE FROM post_views WHERE user_id = '$user_id'";
  $connection->query($stmt);

  $post = new Post();

  // Delete All User Posts
  $posts = $post->get_all_posts($user_id);

  foreach ($posts as $single) {
   $post->delete_post($single['id']);
  }
 }

}
