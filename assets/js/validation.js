// Validate Email Field
function email_is_valid(field) {
  var email = field.value;
  var pattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

  if (!pattern.test(email)) {
    return false;
  }

  return true;
}

// Check if Some Field is Empty
function is_empty(field) {
  if (field.value == '') {
    return true;
  }
  return false;
}

// Check if Some Field is Short
function is_short(limit, field) {
  var value = field.value;

  if (value.length < limit) {
    return true;
  }

  return false;

}

// Create Error Nodes
function create_error_node(error_text, el) {
  // Create Error Node HTML
  var error_node = document.createElement('div');
  error_node.classList.add('error');
  var txt = document.createTextNode(error_text);
  error_node.appendChild(txt);

  // Append The Error Node To The Targeted Element
  el.parentElement.appendChild(error_node);
  el.classList.add('invalid');

}

// Remove All Error Messages To Start Validation From Scratch
function remove_error_nodes() {
  var errors_area = document.querySelectorAll('form .input-gp .error');
  if (errors_area.length > 0) {
    errors_area.forEach(area => {
      area.remove();
    });
  }

  document.querySelectorAll('.input-gp .invalid').forEach(el => {
    el.classList.remove('invalid');
  });
}

function count_words(limit, field) {
  var content = field.value.trim().split(/\s+/);
  var words = 0;

  if (!is_empty(field)) {
    words = content.length;
  }

  document.querySelector('form .rest').innerHTML = limit - words;

  if (limit < words) {
    document.querySelector('.input-gp .rest').classList.add('invalid');
    field.classList.add('invalid');
  } else {
    document.querySelector('.input-gp .rest').classList.remove('invalid');
    field.classList.remove('invalid');
  }

  return limit - words;

}

// Start Validation

function validate_login_form() {
  // Remove All Error Messages To Start Validation From Scratch
  remove_error_nodes();

  // Get The Form
  var login_form = document.forms['login-form'];
  var errors = 0;

  // Validate Email Field
  if (!email_is_valid(login_form['email'])) {
    create_error_node('رجاء أدخل بريدا إلكترونيا صالحا', login_form['email']);
    errors++;
  }

  // Validate Password Field
  if (is_empty(login_form['password'])) {
    create_error_node('رجاء لا تترك هذا الحقل فارغا', login_form['password']);
    errors++;
  }

  if (errors > 0) {
    return false;
  }

  return true;
}

function validate_register_form() {
  // Remove All Error Messages To Start Validation From Scratch
  remove_error_nodes();

  // Get The Form
  var register_form = document.forms['register-form'];
  var errors = 0;

  // Validate Email Field
  if (!email_is_valid(register_form['email'])) {
    create_error_node('رجاء أدخل بريدا إلكترونيا صالحا', register_form['email']);
    errors++;
  }

  // Validate Password Field
  if (is_empty(register_form['password'])) {
    create_error_node('رجاء لا تترك هذا الحقل فارغا', register_form['password']);
    errors++;
  } else if (is_short(8, register_form['password'])) {
    create_error_node('يجب أن تتكون كلمة السر من 8 أحرف على الأقل', register_form['password']);
    errors++;
  }

  // Validate Username Field
  if (is_empty(register_form['name'])) {
    create_error_node('رجاء لا تترك هذا الحقل فارغا', register_form['name']);
    errors++;
  } else if (is_short(5, register_form['name'])) {
    create_error_node('يجب على اسم المستخدم أن يتكون من 5 أحرف فأكثر', register_form['name']);
    errors++;
  }

  if (count_words(150, register_form['bio']) < 0) {
    create_error_node('يسمح فقط بـ 150 كلمة أو أقل في النبذة الخاصة بك', register_form['bio']);
    errors++;
  }

  if (errors > 0) {
    return false;
  }

  return true;
}

function validate_new_post_form() {
  // Remove All Error Messages To Start Validation From Scratch
  remove_error_nodes();

  // Get The Form
  var new_post_form = document.forms['new-post-form'];
  var errors = 0;

  // Validate Username Field
  if (is_empty(new_post_form['alternative-text'])) {
    create_error_node('رجاء لا تترك هذا الحقل فارغا', new_post_form['alternative-text']);
    errors++;
  } else if (is_short(5, new_post_form['alternative-text'])) {
    create_error_node('يجب على النص البديل أن يتكون من 5 أحرف فأكثر', new_post_form['alternative-text']);
    errors++;
  }

  if (is_empty(new_post_form['description'])) {
    create_error_node('رجاء لا تترك هذا الحقل فارغا', new_post_form['description']);
    errors++;
  }

  if (count_words(50, new_post_form['description']) < 0) {
    create_error_node('يسمح فقط بـ 50 كلمة أو أقل في الوصف الخاص بالصورة', new_post_form['description']);
    errors++;
  }

  if (errors > 0) {
    return false;
  }

  return true;
}

function validate_personal_settings_form() {
  // Remove All Error Messages To Start Validation From Scratch
  remove_error_nodes();

  // Get The Form
  var form = document.forms['personal-settings-form'];
  var errors = 0;

  // Validate Email Field
  if (!email_is_valid(form['email'])) {
    create_error_node('رجاء أدخل بريدا إلكترونيا صالحا', form['email']);
    errors++;
  }

  // Validate Username Field
  if (is_empty(form['name'])) {
    create_error_node('رجاء لا تترك هذا الحقل فارغا', form['name']);
    errors++;
  } else if (is_short(5, form['name'])) {
    create_error_node('يجب على اسم المستخدم أن يتكون من 5 أحرف فأكثر', form['name']);
    errors++;
  }

  if (count_words(150, form['bio']) < 0) {
    create_error_node('يسمح فقط بـ 150 كلمة أو أقل في النبذة الخاصة بك', form['bio']);
    errors++;
  }

  if (errors > 0) {
    return false;
  }

  return true;
}

function validate_security_settings_form() {
  // Remove All Error Messages To Start Validation From Scratch
  remove_error_nodes();

  // Get The Form
  var form = document.forms['security-settings-form'];
  var errors = 0;

  // Validate Password Field
  if (is_empty(form['old-pwd'])) {
    create_error_node('رجاء لا تترك هذا الحقل فارغا', form['old-pwd']);
    errors++;
  }

  if (is_empty(form['new-pwd'])) {
    create_error_node('رجاء لا تترك هذا الحقل فارغا', form['new-pwd']);
    errors++;
  } else if (is_short(8, form['new-pwd'])) {
    create_error_node('يجب أن تتكون كلمة السر من 8 أحرف على الأقل', form['new-pwd']);
    errors++;
  }

  if (form['new-pwd'].value != form['confirm-pwd'].value) {
    create_error_node('كلمتا السر غير متطابقتان', form['confirm-pwd']);
    errors++;
  }

  if (errors > 0) {
    return false;
  }

  return true;
}