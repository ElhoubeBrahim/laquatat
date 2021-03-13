<?php require_once 'config/config.php'; // Require The Config File 
?>

<?php

// Check if The User is Logged in
if ($user->is_user_logged_in()) {
  // Redirect To The Login Page
  header('location: ' . DB::MAIN_URL . 'profile.php');
  exit;
}

// If The Login Request is Sent
if (isset($_POST['login'])) {
  // Login
  $user->login($_POST);
}

?>

<?php include 'layouts/header.php'; ?>

<div class="login-form">
  <div class="title">مسرورون بعودتك مجددا ^_^</div>
  <form method="post" name="login-form" novalidate onsubmit="validate_login_form()">
    <div class="input-gp">
      <label for="email">البريد الإلكتروني</label>
      <input type="email" placeholder="example@example.com" dir="ltr" name="email" class="<?php echo $user->error_exists('email') ? 'invalid' : ''; ?>" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
      <?php $user->check_error('email'); ?>
    </div>
    <div class="input-gp">
      <label for="password">كلمة المرور</label>
      <input type="password" placeholder="********" dir="auto" name="password" class="<?php echo $user->error_exists('password') ? 'invalid' : ''; ?>">
      <?php $user->check_error('password'); ?>
    </div>
    <!-- <a href="password_forgotten.php" class="forget">نسيت كلمة السر</a> -->
    <button type="submit" name="login">تسجيل الدخول</button>
  </form>
</div>

<?php include 'layouts/footer.php'; ?>