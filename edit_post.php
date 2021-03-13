<?php

require_once 'config/config.php'; // Require The Config File

// If There is Post id Parameter Sent With a Get Request
if (!isset($_GET['post_id'])) {
  // Redirect To The Profile Page
  header('location: ' . $main_url . 'profile.php');
  exit();
}

// Get and Sanitize The ID
$post_id = filter_var($_GET['post_id'], FILTER_SANITIZE_NUMBER_INT);

// Check If User is Logged in
if (!$user->is_user_logged_in()) {
  // Redirect To The Login Page
  header('location: ' . DB::MAIN_URL . 'login.php');
  exit;
}

// Check If The Owner of The Targeted Post is The Logged in User
if ($post->get_post_info('user_id', $post_id) != $_SESSION['user_id']) {
  // Redirect To The Profile Page
  header('location: ' . $main_url . 'profile.php');
  exit();
}

// Create Random CSRF Token Key
if (!isset($_SESSION['token'])) {
  $_SESSION['token'] = bin2hex(random_bytes(32));
}

$token = $_SESSION['token'];

// If The Update Request is Sent
if (isset($_POST['update_post'])) {

  // Check CSRF Token Key
  if (isset($_POST['csrf']) and hash_equals($_POST['csrf'], $token)) {
    // Delete The Token
    unset($_SESSION['token']);

    // Update The Post
    $post->update_post($_POST, $post_id);
  } else {
    // Delete The Token
    unset($_SESSION['token']);

    // Redirect To The Profile Page
    header('location: ' . $main_url . 'profile.php');
  }
}

?>

<?php include 'layouts/header.php'; ?>

<div class="fixed add-post">
  <?php include 'layouts/sidebar.php'; ?>
  <main class="container">
    <div class="title">شاركنا إبداعك ^_^</div>
    <form method="post" enctype="multipart/form-data" name="new-post-form" novalidate onsubmit="return validate_new_post_form()" enctype="multipart/form-data">
      <div class="upload">
        <img src="assets/images/posts/<?php echo $post->get_post_info('picture', $post_id); ?>" alt="" id="post-picture">
        <div class="upload-form">
          <i class="ri-upload-2-line"></i>
          <input onchange="preview_picture(event, 'post-picture')" type="file" accept="image/*" name="picture">
        </div>
      </div>
      <div class="input-gp">
        <input type="text" placeholder="النص البديل ..." name="alternative-text" class=" <?php echo $post->error_exists('alternative-text') ? 'invalid' : ''; ?>" value="<?php echo $post->get_post_info('alt', $post_id); ?>">
        <?php $post->check_error('alternative-text'); ?>
      </div>
      <div class="input-gp">
        <textarea oninput="count_words(50, this)" name="description" placeholder="وصف الصورة ..." class=" <?php echo $post->error_exists('description') ? 'invalid' : ''; ?>"><?php echo $post->get_post_info('description', $post_id); ?></textarea>
        <span class="rest" dir="ltr">50</span>
        <?php $post->check_error('description'); ?>
      </div>
      <div class="input-radio-gp">
        خيارات النشر
        <label><input type="radio" name="publish" value="public" <?php echo $post->get_post_info('privacy', $post_id) == 'public' ? 'checked' : ''; ?>> <span>عام</span></label>
        <label><input type="radio" name="publish" value="private" <?php echo $post->get_post_info('privacy', $post_id) == 'private' ? 'checked' : ''; ?>> <span>خاص</span></label>
      </div>
      <?php $categories = $post->get_all_categories(); ?>
      <select name="category" id="">
        <?php foreach ($categories as $category) : ?>
          <option value="<?php echo $category['id']; ?>" <?php echo $post->get_post_info('category_id', $post_id) == $category['id'] ? 'selected' : ''; ?>>
            <?php echo $category['name']; ?>
          </option>
        <?php endforeach; ?>
      </select>
      <input type="hidden" name="csrf" value="<?php echo $token; ?>">
      <button type="submit" name="update_post">
        تحديث
        <i class="ri-send-plane-fill"></i>
      </button>
    </form>
  </main>
</div>

<script src="assets/js/script.js"></script>
<script src="assets/js/validation.js"></script>