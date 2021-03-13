<?php require 'config/config.php'; ?>

<?php

if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
} else {
  $user_id = '';
}

if (isset($_GET['category'])) {
  $category_id = filter_var($_GET['category'], FILTER_SANITIZE_NUMBER_INT);
}

// Create Random CSRF Token Key For Delete Post Filed
if (!isset($_SESSION['delete_token'])) {
  $_SESSION['delete_token'] = bin2hex(random_bytes(32));
}

$token = $_SESSION['delete_token'];

?>

<script>
  var token = '<?php echo $token; ?>';
</script>

<?php include 'layouts/header.php'; ?>

<div class="explore">
  <main class="container">

    <?php $categories = $post->get_all_categories(); ?>
    <div class="categories">
      <div class="content">
        <div>
          <a href="<?php echo $main_url . 'explore.php'; ?>">كل الصور</a>
        </div>
        <?php foreach ($categories as $category) : ?>
          <div>
            <a href="?category=<?php echo $category['id']; ?>"><?php echo $category['name']; ?></a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <?php if ($user->is_user_logged_in()) : ?>
      <div class="actions">
        <a href="add_post.php" class="add-post">
          <i class="ri-plus-line"></i>
          أضف إبداعا
        </a>
      </div>
    <?php endif; ?>

    <div class="posts">
      <?php $posts = isset($category_id) ? $post->get_posts_by_category_id($category_id) : $post->get_all_public_posts(); ?>
      <?php if (!empty($posts)) : ?>
        <?php foreach ($posts as $post) : ?>
          <div class="post">
            <div class="picture">
              <img src="assets/images/posts/<?php echo $post['picture']; ?>" alt="<?php echo $post['alt']; ?>" onclick="view_post(<?php echo $post['id']; ?>)">
              <?php if ($user->is_user_logged_in() and $post['user_id'] == $user_id) : ?>
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
          </div>
        <?php endforeach; ?>
      <?php else : ?>
        <div class="no-post">لا توجد صور لعرضها</div>
      <?php endif; ?>
    </div>

  </main>
</div>

<script src="assets/js/script.js"></script>
<script src="assets/js/validation.js"></script>
<script src="assets/vendor/glider/glider.min.js"></script>
<script>
  window.addEventListener('load', function() {
    new Glider(document.querySelector('.explore main.container .categories .content'), {
      slidesToShow: 5.5,
      draggable: true,
    })
  })
</script>