<?php require_once 'config/config.php'; // Require The Config File 
?>

<?php

// If User is Logged in
if ($user->is_user_logged_in()) {
  // If There is a Get Request With a User id Parameter
  if (isset($_GET['user_id'])) {
    $user_id = filter_var($_GET['user_id'], FILTER_SANITIZE_NUMBER_INT);
  } else {
    $user_id = $_SESSION['user_id'];
  }
} else {
  // Redirect To The Login Page
  header('location: ' . DB::MAIN_URL . 'login.php');
  exit;
}

// Create Random CSRF Token Key For Delete Post Filed
if (!isset($_SESSION['delete_token'])) {
  $_SESSION['delete_token'] = bin2hex(random_bytes(32));
}

$token = $_SESSION['delete_token'];

?>

<?php include 'layouts/header.php'; ?>
<script>
  var token = '<?php echo $token; ?>';
</script>

<div class="fixed profile">
  <?php include 'layouts/sidebar.php'; ?>
  <main class="container">

    <?php $user_exists = $user->get_user_info('id', $user_id); ?>
    <?php if ($user_exists) : ?>
      <div class="user-profile">
        <div class="cover-picture">
          <img src="assets/images/profile/<?php echo $user->get_user_info('cover', $user_id); ?>" alt=" ">
        </div>
        <div class="user-info">
          <div class="picture">
            <img src="assets/images/profile/<?php echo $user->get_user_info('avatar', $user_id); ?>" alt="">
          </div>
          <div class="info">
            <div class="username"><?php echo $user->get_user_info('name', $user_id); ?></div>
            <?php $bio = $user->get_user_info('bio', $user_id); ?>
            <?php if (!empty($bio)) { ?>
              <div class="bio"><?php echo $bio; ?></div>
            <?php } ?>
            <div class="likes">
              <i class="ri-heart-fill"></i>
              <span class="nth-likes"><?php echo $user->get_user_info('likes', $user_id); ?></span>
            </div>
          </div>
        </div>

        <?php

        if ($user->user_liked_profile($_SESSION['user_id'], $user_id)) {
          $class = 'action like liked';
        } else {
          $class = 'action like';
        }

        ?>

        <div class="actions">
          <a href="<?php echo $user->get_user_info('paypal', $user_id); ?>" class="action support">
            <i class="ri-hand-heart-line"></i>
            إدعمني
          </a>
          <a href="<?php echo $user->get_user_info('work', $user_id); ?>" class="action hire">
            <i class="ri-briefcase-2-line"></i>
            وظفني
          </a>
          <div class="<?php echo $class; ?>" onclick="love_profile(<?php echo $user_id; ?>)">
            <i class="ri-heart-line"></i>
            إعجاب
          </div>
        </div>
      </div>

      <div class="actions">
        <?php if (!(isset($_GET['user_id']) and $_GET['user_id'] != $_SESSION['user_id'])) : ?>
          <a href="add_post.php" class="add-post">
            <i class="ri-plus-line"></i>
            أضف إبداعا
          </a>
        <?php endif; ?>
      </div>

      <div class="posts">
        <?php if ($post->user_has_posts($user_id)) : ?>
          <?php $posts = !(isset($_GET['user_id']) and $_GET['user_id'] != $_SESSION['user_id']) ? $post->get_all_posts($user_id) : $post->get_public_posts($user_id); ?>
          <?php foreach ($posts as $post) : ?>
            <div class="post">
              <img src="assets/images/posts/<?php echo $post['picture']; ?>" alt="<?php echo $post['alt']; ?>" onclick="view_post(<?php echo $post['id']; ?>)">
              <?php if (!(isset($_GET['user_id']) and $_GET['user_id'] != $_SESSION['user_id'])) : ?>
                <ul class="tools">
                  <li onclick="confirm_post_delete(<?php echo $post['id']; ?>)">
                    <a><i class="ri-delete-bin-2-line"></i></a>
                  </li>
                  <li>
                    <a href="<?php echo $main_url; ?>edit_post.php?post_id=<?php echo $post['id']; ?>"><i class="ri-pencil-line"></i></a>
                  </li>
                </ul>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <div class="no-post">لا توجد صور لعرضها، بادر بنشر أول إبداع لك</div>
        <?php endif; ?>
      </div>
    <?php else : ?>
      <div class="no-content">
        لا يوجد مستخدم بالمعرف المطلوب ، يرجى الرجوع إلى إلى الصفحة الرئيسية
      </div>
    <?php endif; ?>

  </main>
</div>

<script src="assets/js/script.js"></script>
<script src="assets/js/validation.js"></script>