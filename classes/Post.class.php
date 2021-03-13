<?php

/**
 * This Class is Used To Deal With Posts Table Or Any Other Related
 * Tables
 */

class Post extends DB {
 // Use File Upload Tarit
 use Upload;

 // Infut Fields Errors Property
 private $errors = array();

 /**
  * This Function Will Check if Some User Has Posts on His Account
  *
  * @param [integer] $user_id
  * @return bool
  */
 public function user_has_posts($user_id) {
  // Get All Posts Where user_id Column is Equals To Given user_id
  $posts = $this->select('posts', 'user_id', $user_id);

  // If There is a Result
  if ($posts->num_rows > 0) {
   return true;
  }

  // If Not
  return false;
 }

 /**
  * This Function Will Validate Sent Data From Posts Related Forms
  * like 'add-post-form' and 'edit-post-form'
  * This Function Will :
  *   => Check The Giving Condition
  *   => If There is an Error, add it to 'errors' Property
  *
  * @param [array] $data
  * @return void
  */
 private function validate_data($data) {
  // Validate The 'alternative-text' Field
  if (empty($data['alternative-text'])) {
   $this->errors['alternative-text'] = 'رجاء لا تترك هذا الحقل فارغا';
  } elseif (str_word_count($data['alternative-text']) < 3) {
   $this->errors['alternative-text'] = 'يجب على النص البديل أن يتكون من 3 كلمات فأكثر';
  }

  // Validate The 'description' Field
  if (empty($data['description'])) {
   $this->errors['description'] = 'رجاء لا تترك هذا الحقل فارغا';
  } elseif (str_word_count($data['description']) > 50) {
   $this->errors['description'] = 'يسمح فقط بـ 50 كلمة أو أقل في وصف الصورة';
  }
 }

 /**
  * This Function is Used To Add a New Post
  *
  * @param [array] $data
  * @param [integer] $user_id
  * @return void
  */
 public function add_new_post($data, $user_id) {

  // If There is No Uploaded Picture
  if (empty($_FILES['picture']['tmp_name'])) {
   $this->errors['picture'] = 'المرجو تحميل صورة';
  }

  // Validate Entered Data Data
  $this->validate_data($data);

  // Validate Post Privacy Checkbox Input
  $privacy = !in_array($data['publish'], ['public', 'private']) ? 'public' : $data['publish'];

  // If There is No Error
  if (empty($this->errors)) {
   // Sanitize Data
   $alt = filter_var($data['alternative-text'], FILTER_SANITIZE_STRING);
   $desc = filter_var($data['description'], FILTER_SANITIZE_STRING);
   $category = filter_var($data['category'], FILTER_SANITIZE_NUMBER_INT);

   // Start Uploading The Picture
   $image = $_FILES['picture'];

   // Get The Picture Extension
   $ext = pathinfo($image['name'], PATHINFO_EXTENSION);

   // Set Up The Upload Trait Properties
   $this->extensions = ['png', 'jpg', 'jpeg', 'gif'];
   $this->upload_dir = 'assets/images/posts/';
   $this->upload_name = 'post_' . time() . '_' . $user_id . '.' . $ext;

   // If The Picture Was Uploaded Successfully
   if ($this->upload($image)) {
    // Get The Picture Name To Store in The Database
    $picture = $this->upload_name;

    // Connect To DB
    $connection = DB::connect();
    // Insert New Post Data Into posts Table
    $stmt = "INSERT INTO posts
          (`user_id`, `picture`, `alt`, `description`, `privacy`, `category_id`)
          VALUES
          ('$user_id', '$picture', '$alt', '$desc', '$privacy', '$category')";

    // if The Post Wasn't Uploaded Successfully
    if ($connection->query($stmt) === false) {
     echo '<script>alert("لا نستطيع إضافة إبداعك الآن، يمكنك الإتصال بفريق الدعم أو المحاولة لاحقا")</script>';
     return;
    }

    // Redirect To Profile Page
    $profile_url = DB::MAIN_URL . 'profile.php';
    header('location: ' . $profile_url);
   }

  }

 }

 /**
  * This Function Gets All Categories in The categories Table
  *
  * @return array
  */
 public function get_all_categories() {
  // Select All Categories From categories Table
  $result = $this->select_all('categories');
  // Init categories Variable
  $categories = array();
  // Push Selected Data in The Categories Array
  while ($row = $result->fetch_assoc()) {
   array_push($categories, $row);
  }

  // Return Categories
  return $categories;
 }

 /**
  * This Function is User To Get a User's Posts
  *
  * @param [integer] $user_id
  * @return array
  */
 public function get_all_posts($user_id) {
  // Select All Posts From posts Table Where user_id Column Equals To The Given User id
  $result = $this->select('posts', 'user_id', $user_id);

  // Init Posts Variable
  $posts = array();

  // Push Selected Rows into The posts Array
  while ($row = $result->fetch_assoc()) {
   array_push($posts, $row);
  }

  // Return The posts Array Reversed in Order To Get Them Ordered
  return array_reverse($posts);
 }

 /**
  * This Function is User To Get a User's Public Posts
  *
  * @param [integer] $user_id
  * @return array
  */
 public function get_public_posts($user_id) {
  // Select All Posts From posts Table Where user_id Column Equals To The Given User id
  $result = $result = $this->select('posts', 'user_id', $user_id);

  // Init Posts Variable
  $posts = array();

  // Loop Throught Selected Rows
  while ($row = $result->fetch_assoc()) {
   // Check if Post Privacy is Public
   if ($row['privacy'] === 'public') {
    // Append The Post To posts Array
    array_push($posts, $row);
   }
  }

  // Return The posts Array Reversed in Order To Get Them Ordered
  return array_reverse($posts);
 }

 /**
  * This Function is User To Get a All Public Posts in posts Table
  *
  * @return array
  */
 public function get_all_public_posts() {
  // Select All Posts From posts Table Where user_id Column Equals To The Given User id
  $result = $result = $this->select('posts', 'privacy', 'public');

  // Init Posts Variable
  $posts = array();

  // Append Selected Posts To The posts Array
  while ($row = $result->fetch_assoc()) {
   array_push($posts, $row);
  }

  // Return The posts Array Reversed in Order To Get Them Ordered
  return array_reverse($posts);
 }

 public function get_posts_by_category_id($id) {
  // Select All Posts From posts Table Where The Condition is Verified
  $result = $result = $this->select('posts', 'category_id', $id);

  // Init Posts Variable
  $posts = array();

  // Append Selected Posts To The posts Array
  while ($row = $result->fetch_assoc()) {
   // Check if Post Privacy is Public
   if ($row['privacy'] === 'public') {
    // Append The Post To posts Array
    array_push($posts, $row);
   }
  }

  // Return The posts Array Reversed in Order To Get Them Ordered
  return array_reverse($posts);
 }

 /**
  * This Function is Used To Return Given Post Info
  *
  * @param [string] $key
  * @param [integer] $id
  * @return string
  */
 public function get_post_info($key, $id) {
  // Connect To The Database
  $connection = DB::connect();

  // Select Given Column as 'key' From posts Table
  $stmt = "SELECT $key FROM posts WHERE `id` = '$id'";
  $result = $connection->query($stmt);

  // Return The Column Content
  return $result->fetch_assoc()[$key];
 }

 /**
  * This Function is Used To Update an Existing Post
  *
  * @param [array] $data
  * @param [integer] $id
  * @return void
  */
 public function update_post($data, $id) {

  // Validate Entered Data
  $this->validate_data($data);

  // Validate Post Privacy Checkbox Input
  $privacy = !in_array($data['publish'], ['public', 'private']) ? 'public' : $data['publish'];

  // If There is No Error
  if (empty($this->errors)) {
   // Sanitize Data
   $picture = $this->get_post_info('picture', $id);
   $alt = filter_var($data['alternative-text'], FILTER_SANITIZE_STRING);
   $desc = filter_var($data['description'], FILTER_SANITIZE_STRING);
   $category = filter_var($data['category'], FILTER_SANITIZE_NUMBER_INT);

   // If The User Uploads an Image
   if (!empty($_FILES['picture']['tmp_name'])) {

    // Start Uploading The Image
    $image = $_FILES['picture'];

    // Get The Extention
    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);

    // Set Up Upload Trait Properties
    $this->extensions = ['png', 'jpg', 'jpeg', 'gif'];
    $this->upload_dir = 'assets/images/posts/';
    $this->upload_name = 'post_' . time() . '_' . $this->get_post_info('user_id', $id) . '.' . $ext;

    // If The Image Was Uploaded Successfully
    if ($this->upload($image)) {
     // Put The Image Name in a Variable in Order To Store
     $picture = $this->upload_name;

     // Remove The Old Post Picture
     unlink($this->upload_dir . $this->get_post_info('picture', $id));
    }
   }

   // Connect To DB
   $connection = DB::connect();

   // Update posts Table
   $stmt = "UPDATE posts SET
     picture = '$picture',
     alt = '$alt',
     description = '$desc',
     privacy = '$privacy',
     category_id = '$category' WHERE id = '$id'";

   // If The Post Wans't Updated SuccessFully
   if ($connection->query($stmt) === false) {
    echo '<script>alert("لا نستطيع تحديث إبداعك الآن، يمكنك الإتصال بفريق الدعم أو المحاولة لاحقا")</script>';
    return;
   }

   // Redirect To Profile Page
   $profile_url = DB::MAIN_URL . 'profile.php';
   header('location: ' . $profile_url);

  }
 }

 /**
  * This Function is Used To Delete Posts
  *
  * @param [integer] $id
  * @return void
  */
 public function delete_post($id) {

  // Connect To The DataBase
  $connection = DB::connect();

  // Delete The Old Post Picture
  unlink('assets/images/posts/' . $this->get_post_info('picture', $id));

  // Delete Post
  $stmt = "DELETE FROM posts WHERE id = '$id'";
  $connection->query($stmt);

  // Delete Post Likes
  $stmt = "DELETE FROM post_likes WHERE post_id = '$id'";
  $connection->query($stmt);

  // Delete Post Views
  $stmt = "DELETE FROM post_views WHERE post_id = '$id'";
  $connection->query($stmt);
 }

 /**
  * This Function Will Check if The User Has Been Liked a Post Before
  *
  * @param [integer] $user_id
  * @param [integer] $post_id
  * @return boolean
  */
 public function user_liked_post($user_id, $post_id) {

  // Connect To The DataBase
  $connection = DB::connect();

  // Select All Rows From post_likes Table Where The Condition is True
  $stmt = "SELECT * FROM post_likes WHERE user_id = '$user_id' AND post_id = '$post_id'";
  $result = $connection->query($stmt);

  // If There is Result
  if ($result->num_rows > 0) {
   return true;
  }

  // If Not
  return false;
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

}
