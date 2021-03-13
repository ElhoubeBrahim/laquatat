<?php require_once 'config/config.php'; // Require The Config File 
?>
<?php

// Check if The User is Logged in
if (!$user->is_user_logged_in()) {
  // Redirect To The Login Page
  header('location: ' . DB::MAIN_URL . 'login.php');
  exit;
}

// Create Random CSRF Token Key To Protect Against CSRF Attacks
if (!isset($_SESSION['token'])) {
  $_SESSION['token'] = bin2hex(random_bytes(32));
}

$token = $_SESSION['token'];

// If The Add Post Request is Sent
if (isset($_POST['add_post'])) {

  // Check CSRF Token Key
  if (isset($_POST['csrf']) and hash_equals($token, $_POST['csrf'])) {
    // Delete The Token
    unset($_SESSION['token']);

    // Add New Post
    $post->add_new_post($_POST, $_SESSION['user_id']);
  } else {
    // Delete The Token
    unset($_SESSION['token']);
    // Redirect To The Profile Page
    header('location: ' . $main_url . 'profile.php');
  }
}

?>

<?php include 'layouts/header.php'; // Include Header File 
?>

<div class="fixed add-post">
  <?php include 'layouts/sidebar.php'; // Include Sidebar File 
  ?>
  <main class="container">
    <div class="title">شاركنا إبداعك ^_^</div>
    <form method="post" enctype="multipart/form-data" name="new-post-form" novalidate onsubmit="return validate_new_post_form()" enctype="multipart/form-data">
      <div class="upload">
        <img src="" alt=" " id="post-picture">
        <div class="upload-form">
          <i class="ri-upload-2-line"></i>
          <input onchange="preview_picture(event, 'post-picture') // Js Function" type="file" accept="image/*" name="picture">
        </div>
        <?php $post->check_error('picture'); // Check if There is an Error and Print it 
        ?>
      </div>
      <div class="input-gp">
        <input type="text" placeholder="النص البديل ..." name="alternative-text" class=" <?php echo $post->error_exists('alternative-text') ? 'invalid' : ''; ?>" value="<?php echo isset($_POST['alternative-text']) ? $_POST['alternative-text'] : ''; ?>">
        <?php $post->check_error('alternative-text'); // Check if There is an Error and Print it 
        ?>
      </div>
      <div class="input-gp">
        <textarea oninput="count_words(50, this)" name="description" placeholder="وصف الصورة ..." class=" <?php echo $post->error_exists('description') ? 'invalid' : ''; ?>"><?php echo isset($_POST['description']) ? $_POST['description'] : ''; ?></textarea>
        <span class="rest" dir="ltr">50</span>
        <?php $post->check_error('description'); // Check if There is an Error and Print it 
        ?>
      </div>
      <div class="input-radio-gp">
        خيارات النشر
        <label><input type="radio" name="publish" value="public" checked=""> <span>عام</span></label>
        <label><input type="radio" name="publish" value="private"> <span>خاص</span></label>
      </div>
      <?php $categories = $post->get_all_categories(); ?>
      <select name="category" id="">
        <?php foreach ($categories as $category) : ?>
          <option value="<?php echo $category['id']; ?>">
            <?php echo $category['name']; ?>
          </option>
        <?php endforeach; ?>
      </select>
      <input type="hidden" name="csrf" value="<?php echo $token; ?>">
      <button type="submit" name="add_post">
        نشر
        <i class="ri-send-plane-fill"></i>
      </button>
    </form>
  </main>
</div>

<script src="assets/js/script.js"></script>
<script src="assets/js/validation.js"></script>