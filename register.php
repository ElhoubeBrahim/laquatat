<?php require_once 'config/config.php'; // Require The Config File 
?>

<?php

// Check if The User is Logged in
if ($user->is_user_logged_in()) {
  // Redirect To The Login Page
  header('location: ' . DB::MAIN_URL . 'profile.php');
  exit;
}

// If The Register Request is Sent
if (isset($_POST['register'])) {
  // Register New User
  $user->register_user($_POST);
}

?>

<?php include 'layouts/header.php'; ?>

<div class="register-form">
  <div class="title">أنشئ حسابك! وانشر إبداعك!</div>
  <form method="post" enctype="multipart/form-data" name="register-form" novalidate onsubmit="validate_register_form()" required>
    <div class="top-side">
      <div class="picture">
        <img src="assets/images/no-image.png" alt=" " id="profile-picture">
        <div class="upload">
          <i class="ri-upload-2-line"></i>
          <input onchange="preview_picture(event, 'profile-picture')" type="file" accept="image/*" name="avatar">
        </div>
      </div>
      <div class="inputs">
        <div class="input-gp">
          <label for="username">
            الإسم الكريم
            <span class="required">*</span>
          </label>
          <input type="text" placeholder="الإسم الكريم" name="name" class="<?php echo $user->error_exists('name') ? 'invalid' : ''; ?>" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>">
          <?php $user->check_error('name'); ?>
        </div>
        <div class="input-gp">
          <label for="bio">عرفنا بنفسك</label>
          <textarea oninput="count_words(150, this)" type="text" placeholder="اكتب نبذة مختصرة عنك" name="bio" class="<?php echo $user->error_exists('bio') ? 'invalid' : ''; ?>"><?php echo isset($_POST['bio']) ? $_POST['bio'] : ''; ?></textarea>
          <span class="rest" dir="ltr">150</span>
          <?php $user->check_error('bio'); ?>
        </div>
      </div>
    </div>
    <div class="input-gp">
      <label for="email">
        البريد الإلكتروني
        <span class="required">*</span>
      </label>
      <input type="email" placeholder="example@example.com" dir="ltr" name="email" class="<?php echo $user->error_exists('email') ? 'invalid' : ''; ?>" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
      <?php $user->check_error('email'); ?>
    </div>
    <div class="input-gp">
      <label for="password">
        كلمة المرور
        <span class="required">*</span>
      </label>
      <input type="password" placeholder="********" dir="auto" name="password" class="<?php echo $user->error_exists('password') ? 'invalid' : ''; ?>">
      <?php $user->check_error('password'); ?>
    </div>
    <div class="input-radio-gp">
      الجنس<span class="required">*</span>
      <label><input type="radio" name="sexe" value="male" checked> <span>ذكر</span></label>
      <label><input type="radio" name="sexe" value="female"> <span>أنثى</span></label>
    </div>
    <button type="submit" name="register">حساب جديد</button>
  </form>
</div>

<?php include 'layouts/footer.php'; ?>