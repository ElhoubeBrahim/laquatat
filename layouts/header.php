<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <title>لقطات | معرض الأعمال البصرية الإبداعية</title>

  <link rel="stylesheet" href="assets/vendor/remixicon/remixicon.css">
  <link rel="stylesheet" href="assets/vendor/glider/glider.min.css">
  <link rel="stylesheet" href="assets/css/style.css">

</head>

<body onload="setup_profile_area()">

  <header class="header">
    <div class="container">
      <div class="brand">
        <a href="<?php echo $main_url; ?>">لقطات</a>
      </div>
      <?php if (User::is_user_logged_in()) : ?>
        <ul class="user-actions">
          <li class="account">
            <a href="profile.php">
              <img src="assets/images/profile/<?php echo $user->get_user_info('avatar', $_SESSION['user_id']); ?>" alt="">
            </a>
          </li>
          <li class="logout">
            <a href="logout.php">
              <i class="ri-shut-down-line"></i>
            </a>
          </li>
          <?php if (pathinfo($_SERVER["REQUEST_URI"], PATHINFO_FILENAME) != 'explore') : ?>
            <li class="menu" onclick="toggle_sidebar()">
              <i class="ri-menu-line"></i>
            </li>
          <?php endif; ?>
        </ul>
      <?php else : ?>
        <div class="buttons">
          <button class="login">
            <a href="login.php">
              تسجيل الدخول
            </a>
          </button>
          <button class="signup">
            <a href="register.php">
              حساب جديد
            </a>
          </button>
        </div>
      <?php endif; ?>
    </div>
  </header>