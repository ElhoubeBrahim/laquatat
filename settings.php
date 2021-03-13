<?php require_once 'config/config.php';?>

<?php

// Check if The User is Logged in
if (!$user->is_user_logged_in()) {
 // Redirect To The Login Page
 header('location: ' . DB::MAIN_URL . 'login.php');
 exit;
}

$user_id = $_SESSION['user_id'];

// Create Random CSRF Token Key To Protect Against CSRF Attacks
if (!isset($_SESSION['token'])) {
 $_SESSION['token'] = bin2hex(random_bytes(32));
}

$token = $_SESSION['token'];

// If The Settings Request is Sent
if (isset($_POST['update'])) {

 // Check CSRF Token Key
 if (isset($_POST['csrf']) and hash_equals($token, $_POST['csrf'])) {
  // Delete The Token
  unset($_SESSION['token']);

  // Update User info
  $user->update_user_profile($_POST, $_SESSION['user_id']);
 } else {
  // Delete The Token
  unset($_SESSION['token']);
  // Redirect To The Profile Page
  header('location: ' . $main_url . 'profile.php');
 }

}

if (isset($_POST['change-password'])) {
  $user->change_password($_POST, $_SESSION['user_id']);
}

// Create Random CSRF Token Key For Delete Fileds
if (!isset($_SESSION['delete_token'])) {
 $_SESSION['delete_token'] = bin2hex(random_bytes(32));
}

$delete_token = $_SESSION['delete_token'];

?>

<?php include 'layouts/header.php';?>

<script>
  var token = '<?php echo $delete_token; ?>';
</script>

<div class="fixed settings">
  <?php include 'layouts/sidebar.php';?>
  <main class="container">

    <nav class="navigate-settings">
      <ul>
        <li class="active" id="personal">
          <a>الإعدادات الشخصية</a>
        </li> |
        <li id="account">
          <a>إعدادات الحساب</a>
        </li> |
        <li id="security">
          <a>إعدادات الحماية</a>
        </li>
        </li>
      </ul>
    </nav>

    <div class="settings-list">
      <form method="post" enctype="multipart/form-data" id="personal" name="personal-settings-form" class="settings-form active" novalidate="" required="">
        <div class="pictures">
          <div class="cover-picture">
            <img id="cover-picture" src="assets/images/profile/<?php echo $user->get_user_info('cover', $user_id); ?>" alt=" ">
          </div>
          <div class="profile-picture">
            <img id="profile-picture" src="assets/images/profile/<?php echo $user->get_user_info('avatar', $user_id); ?>" alt="">
          </div>
          <div class="change-cover">
              <input type="file" name="cover" id="" accept="image/*" onchange="preview_picture(event, 'cover-picture')">
              <span>تغيير صورة الغلاف</span>
            </div>
          <div class="change-profile">
            <input type="file" name="avatar" id="" accept="image/*" onchange="preview_picture(event, 'profile-picture')">
            <span>تغيير الصورة الشخصية</span>
          </div>
        </div>
        <br>
        <br>
        <br>
        <div class="input-gp">
          <label for="username">
            تغيير الإسم
          </label>
          <input type="text" placeholder="الإسم الكريم" name="name" value="<?php echo $user->get_user_info('name', $user_id); ?>">
          <?php $user->check_error('name');?>
        </div>
        <div class="input-gp">
          <label for="email">
            تغيير البريد الإلكتروني
          </label>
          <input type="text" placeholder="البريد الإلكتروني" name="email" value="<?php echo $user->get_user_info('email', $user_id); ?>">
          <?php $user->check_error('email');?>
        </div>
        <div class="input-gp">
          <label for="bio">عرفنا بنفسك</label>
          <textarea oninput="count_words(150, this)" type="text" placeholder="اكتب نبذة مختصرة عنك" name="bio"><?php echo $user->get_user_info('bio', $user_id); ?></textarea>
          <span class="rest" dir="ltr">150</span>
          <?php $user->check_error('bio');?>
        </div>
        <input type="hidden" name="csrf" value="<?php echo $token; ?>">
        <button type="submit" name="update">حفظ الإعدادات</button>
      </form>
      <form method="post" enctype="multipart/form-data" id="account" name="account-settings-form" class="settings-form" novalidate="" required="">
        <div class="input-gp">
          <button type="submit" onclick="confirm_all_posts_delete(event, <?php echo $_SESSION['user_id']; ?>)">حذف كل المنشورات</button>
        </div>
        <div class="input-gp">
          <button type="submit" onclick="confirm_account_delete(event, <?php echo $_SESSION['user_id']; ?>)">حذف الحساب نهائيا</button>
        </div>
      </form>
      <form method="post" enctype="multipart/form-data" id="security" name="security-settings-form" class="settings-form" novalidate="" onsubmit="return true //validate_security_settings_form()" required="">
        <div class="input-gp">
          <label for="username">
            كلمة السر القديمة
          </label>
          <input type="password" placeholder="*********" name="old-pwd">
          <?php $user->check_error('old-pwd');?>
        </div>
        <div class="input-gp">
          <label for="username">
            كلمة السر الجديدة
          </label>
          <input type="password" placeholder="*********" name="new-pwd">
          <?php $user->check_error('new-pwd');?>
        </div>
        <div class="input-gp">
          <label for="username">
            تأكيد كلمة السر الجديدة
          </label>
          <input type="password" placeholder="*********" name="confirm-pwd">
          <?php $user->check_error('confirm-pwd');?>
        </div>
        <button type="submit" name="change-password">تغيير كلمة السر</button>
      </form>
    </div>

  </main>
</div>

<script src="assets/js/script.js"></script>
<script src="assets/js/validation.js"></script>
