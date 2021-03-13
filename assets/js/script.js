// To Prevent CSRF Attaks in The Delete Post Form
if (typeof token === 'undefined') {
  token = null;
}

/**
 * Add auto margin to the body according to the height of fixed header
 */
let height = document.querySelector('header.header').offsetHeight
document.body.style.marginTop = height + 'px'


/**
 * Start The Slide Show Every 3 Seconds In The Home Page, Intro Section
 */

// Target All Slides
var slider_images = document.querySelectorAll('.intro .slider img');
// Reset The Current Slide Index
var current_slide = 0;

if (slider_images.length > 0) {
  setInterval(() => {
    // Check If The Current Slide Index is Out Of Range
    if (current_slide > (slider_images.length - 1)) {
      // Reset The Current Slide Index
      current_slide = 0;
    }

    // Remove The Active Class From All Slides
    slider_images.forEach(image => {
      image.classList.remove('active');
    });

    // Add The Active Class To The Current Slide
    slider_images[current_slide].classList.add('active');
    // Change The Current Slide Index
    current_slide = current_slide + 1;

  }, 5000);
}

/**
 * Start The Accordion in The Home Page Question Section
 */
// Target The Accordions Headers
var accordion_header = document.querySelectorAll('.questions .question .header');

// Listen To The Events For Each Header
accordion_header.forEach(el => {
  el.onclick = function () {
    // Remove The Active Class From All Sections
    accordion_header.forEach(el => {
      el.parentElement.classList.remove('active');
    });
    // Add Active Class To The Clicked Section
    this.parentElement.classList.add('active');
  }
});

/**
 * Start Testimonials Actions
 */
// Get Testimonials
var testimonials = document.querySelectorAll('.testimonials .testimonial')

// Get Indexes Dots
var dots = document.querySelectorAll('.testimonials .dots .dot');

// Listen To The Events For Each Dot
dots.forEach(dot => {
  dot.onclick = function () {
    // Remove The Class Active For All Dots
    dots.forEach(dot => {
      dot.classList.remove('active');
    });
    // Add Active Class To The Clicked Dot
    this.classList.add('active');
    // Hide All Testimonials
    testimonials.forEach(testimonial => {
      testimonial.classList.remove('active');
    });
    // Show The Targeted Testimonial
    var id = this.getAttribute('data-index');
    testimonials[id].classList.add('active');
  }
});


/**
 * Preview The Uploaded User Picture
 */
function preview_picture(event, img) {
  // Target The img Tag To Preview The Uploaded Image
  var image_node = document.querySelector('img#' + img);

  // Instantiate FileReader
  var reader = new FileReader();

  // If The User Loaded an Image
  reader.onload = function () {
    // Get The Image src From The FileReader and Show it into The Targeted img Tag
    image_node.src = reader.result;
    image_node.removeAttribute('class');
  };

  // Read The Uploaded File
  reader.readAsDataURL(event.target.files[0]);
}

/**
 * Setup The Profile Page After Loading
 */
function setup_profile_area() {
  // Target Needed Elements
  var header = document.querySelector('header.header');
  var profile_area = document.querySelector('.fixed');
  var sidebar = document.querySelector('aside.sidebar');

  // If The Profile Area and The Side Bar Are Defined
  if (profile_area != undefined && sidebar != undefined) {
    // Translate Both Of Them From The Top Because Their Position is Set To Absolute
    profile_area.style.marginTop = header.clientHeight + 'px';
    sidebar.style.top = header.clientHeight + 'px';
  }
}

/**
 * Show / Hide sidebar
 */
function toggle_sidebar() {
  // Target Needed Elements
  var sidebar = document.querySelector('aside.sidebar');

  // If The Side Bar is Defined
  if (sidebar != undefined) {
    sidebar.classList.toggle('show')
  }
}

/**
 * Show The View Post Modal When a Post is Clicked
 */
function view_post(picture_id) {

  // Setup Main Variables
  var html = '';
  var main_area = document.querySelector('main.container');

  // AJAX Requests
  var post_request = new XMLHttpRequest(); // Get Post Info
  post_request.onreadystatechange = function () {
    // If The Request is Done Successfully
    if (this.readyState == 4 && this.status == 200) {
      // Get Post info in a JSON Format
      var post = JSON.parse(this.responseText);

      var user_request = new XMLHttpRequest(); // Get User Info
      user_request.onreadystatechange = function () {
        // If The Request is Done Successfully
        if (this.readyState == 4 && this.status == 200) {
          // Get User info in a JSON Format
          var user = JSON.parse(this.responseText);

          // Check if The Logged in User Has Been Liked The Post Before
          if (post.user_liked_post) {
            var _class = 'ri-heart-fill liked';
          } else {
            var _class = 'ri-heart-line';
          }

          // If The User is Allowed To See The Post
          if (post.privacy == 'public' || (post.privacy == 'private' && post.user_id == post.logged_user_id)) {
            // Modal HTML
            html += '<div class="view-modal">';
            html += '<div class="content">';
            html += '<div class="header">';
            html += `<span class="close" onclick="close_modal('main.container .view-modal')"><i class="ri-close-line"></i></span>`;
            if (post.user_id == post.logged_user_id) {
              html += '<ul class="tools">';
              html += '<li onclick="confirm_post_delete(' + post.id + ')"><a><i class="ri-delete-bin-2-line"></i></a></li>';
              html += '<li><a href="' + window.location.origin + '/laquatat/edit_post.php?post_id=' + post.id + '"><i class="ri-pencil-line"></i></a></li>';
              html += '</ul>'; // Close '.tools'
            }
            html += '</div>'; // Close '.header'
            html += '<img src="assets/images/posts/' + post.picture + '" alt="' + post.alt + '">';
            html += '<div class="info">';
            html += '<a href="' + window.location.origin + '/laquatat/profile.php?user_id=' + user.id + '" class="user">';
            html += '<img src="assets/images/profile/' + user.avatar + '" alt="' + user.name + '">';
            html += '<div class="username">' + user.name + '</div>';
            html += '</a>' // Close '.user'
            if (post.logged_user_id != '') {
              html += '<div class="modal-actions">';
              html += '<div class="likes">';
              html += '<span onclick="love_post(' + picture_id + ')"><i class="' + _class + '"></i></span>';
              html += '<div class="nth-likes">' + post.likes + '</div>';
              html += '</div>'; // Close '.likes'
              html += '<div class="views">';
              html += '<span><i class="ri-eye-line"></i></span>';
              html += '<div class="nth-views">' + post.views + '</div>';
              html += '</div>'; // Close '.views'
              html += '</div>'; // Close '.modal-actions'
            }
            html += '</div >'; // Close '.info'
            html += '<div class="description">' + post.description + '</div>';
            html += '</div>'; // Close '.content'
            html += '</div>'; // Close '.view-modal'

            // Append The Modal HTML To The Profile Body
            main_area.innerHTML += html;

            // Show The Modal After 300 ms
            setTimeout(() => {
              document.querySelector('main.container .view-modal').classList.add('shown');

              // if User is Logged in, Add a Post View
              post.logged_user_id != '' ? add_view(post.id, user.id) : '';
            }, 300);
          }
        }
      };

      // Send The User Request
      user_request.open("POST", window.location.origin + "/laquatat/ajax_requests/user.php", true);
      user_request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      user_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      user_request.send('user_id=' + post.user_id);
    }
  };

  // Send The Post Request
  post_request.open("POST", window.location.origin + "/laquatat/ajax_requests/post.php", true);
  post_request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  post_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  post_request.send('post_id=' + picture_id);

}


/**
 * Add a Post View When The User Viewed Post
 */
function add_view(post_id) {
  // Send a Request to add view Page
  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    // If The Request Has Been Sent Successfully
    if (this.readyState == 4 && this.status == 200) {
      // Change The Views Number in The Modal
      document.querySelector('.view-modal .content .info .modal-actions .views .nth-views').innerHTML = this.responseText;
    }
  }

  // Send The Request
  request.open("POST", window.location.origin + "/laquatat/ajax_requests/post_views.php", true);
  request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.send('post_id=' + post_id);
}

/**
 * This Function is Called When The User Pressed The Love Post Button
 */
function love_post(post_id) {

  // Send a New Request To Love Post Page
  var request = new XMLHttpRequest();

  request.onreadystatechange = function () {
    // If The Request is OK
    if (this.readyState == 4 && this.status == 200) {
      // Get Returned Data in a JSON Format
      var response = JSON.parse(this.responseText);
      // Change The Likes Number
      document.querySelector('.view-modal .content .info .modal-actions .likes .nth-likes').innerHTML = response.likes;
      // Add Class to The Like Button According to The User Action
      document.querySelector('.view-modal .content .info .modal-actions .likes i').setAttribute('class', response.class);
    }
  }

  // Send The Request
  request.open('POST', window.location.origin + '/laquatat/ajax_requests/love_post.php', true);
  request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.send('post_id=' + post_id);
}

/**
 * Close Opened Modals 
 */
function close_modal(modal) {
  // Target The Modal
  modal = document.querySelector(modal);

  // Hide Modal
  modal.classList.remove('shown');

  // Remove The Modal HTML From The DOM After 300 ms
  setTimeout(() => {
    modal.remove();
  }, 300);
}

/**
 * Toggle Settings Tabs When Navigation List is Clicked
 */

var links = document.querySelectorAll('.settings .navigate-settings ul li');

if (links != undefined) {
  links.forEach(link => {
    link.onclick = function () {
      var id = this.getAttribute('id');

      links.forEach(link => { link.classList.remove('active'); });
      this.classList.add('active');

      document.querySelectorAll('.settings .settings-list form').forEach(form => { form.classList.remove('active'); });
      document.querySelector('form#' + id).classList.add('active');
    }
  });
}

/**
 * This Function is Called When The User Pressed The Love Profile Button
 */
function love_profile(profile_id) {

  // Send a Request To The Love Profile Page
  var request = new XMLHttpRequest();

  request.onreadystatechange = function () {
    // if The Request is OK
    if (this.readyState == 4 && this.status == 200) {
      // Get Returned Data in a JSON Format
      var response = JSON.parse(this.responseText);
      // Change Likes Number
      document.querySelector('.profile main.container .user-profile .user-info .likes .nth-likes').innerHTML = response.likes;
      // Add Class to The Like Button According to The User Action
      document.querySelector('.profile main.container .user-profile .actions .action.like').setAttribute('class', response.class);
    }
  }

  // Send The Request
  request.open('POST', window.location.origin + '/laquatat/ajax_requests/love_profile.php', true);
  request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.send('profile_id=' + profile_id);

}

/**
 * Show The Confirm Delete, When The Post Delete Button is Clicked
 */
function confirm_post_delete(post_id) {

  // Setup Needed Variables
  var main_area = document.querySelector('main.container');
  var html = '';

  // Modal HTML
  html += '<div class="confirm-modal">';
  html += '<div class="content">';
  html += '<p>';
  html += 'هل أنت متأكد من حذف المنشور ؟';
  html += '</p>';
  html += '<ul class="confirm-actions">';
  html += '<li class="delete">';
  html += '<form method="POST" action="' + window.location.origin + '/laquatat/delete_post.php">';
  html += '<input type="hidden" name="post_id" value="' + post_id + '">';
  html += '<input type="hidden" name="token" value="' + token + '">';
  html += '<button type="submit">حذف</button>';
  html += '</form>';
  html += '</li>'; // Close '.delete'
  html += `<li class="cancel" onclick="close_modal('main.container .confirm-modal')"><a>إلغاء</a></li>`;
  html += '</ul>'; // Close '.confirm-actions'
  html += '</div>'; // Close '.content'
  html += '</div>'; // Clase '.confirm-modal'

  // Append Modal HTML To The Main Area
  main_area.innerHTML += html;

  // Show The Modal After 300 ms
  setTimeout(() => {
    document.querySelector('main.container .confirm-modal').classList.add('shown');
  }, 300);
}

function confirm_all_posts_delete(event, user_id) {
  event.preventDefault();

  // Setup Needed Variables
  var main_area = document.querySelector('main.container');
  var html = '';

  // Modal HTML
  html += '<div class="confirm-modal">';
  html += '<div class="content">';
  html += '<p>';
  html += 'هل أنت متأكد من حذف كل المنشورات ؟';
  html += '</p>';
  html += '<ul class="confirm-actions">';
  html += '<li class="delete">';
  html += '<form method="POST" action="' + window.location.origin + '/laquatat/delete_all_posts.php">';
  html += '<input type="hidden" name="user_id" value="' + user_id + '">';
  html += '<input type="hidden" name="token" value="' + token + '">';
  html += '<button type="submit">حذف</button>';
  html += '</form>';
  html += '</li>'; // Close '.delete'
  html += `<li class="cancel" onclick="close_modal('main.container .confirm-modal')"><a>إلغاء</a></li>`;
  html += '</ul>'; // Close '.confirm-actions'
  html += '</div>'; // Close '.content'
  html += '</div>'; // Clase '.confirm-modal'

  // Append Modal HTML To The Main Area
  main_area.innerHTML += html;

  // Show The Modal After 300 ms
  setTimeout(() => {
    document.querySelector('main.container .confirm-modal').classList.add('shown');
  }, 300);
}

function confirm_account_delete(event, user_id) {
  event.preventDefault();

  // Setup Needed Variables
  var main_area = document.querySelector('main.container');
  var html = '';

  // Modal HTML
  html += '<div class="confirm-modal">';
  html += '<div class="content">';
  html += '<p>';
  html += 'هل أنت متأكد من حذف حسابك ؟';
  html += '<br>';
  html += 'لا يمكنك استعادة حسابك بعد حذفه، لذا كن متأكدا جدا';
  html += '</p>';
  html += '<ul class="confirm-actions">';
  html += '<li class="delete">';
  html += '<form method="POST" action="' + window.location.origin + '/laquatat/delete_account.php">';
  html += '<input type="hidden" name="user_id" value="' + user_id + '">';
  html += '<input type="hidden" name="token" value="' + token + '">';
  html += '<button type="submit">حذف</button>';
  html += '</form>';
  html += '</li>'; // Close '.delete'
  html += `<li class="cancel" onclick="close_modal('main.container .confirm-modal')"><a>إلغاء</a></li>`;
  html += '</ul>'; // Close '.confirm-actions'
  html += '</div>'; // Close '.content'
  html += '</div>'; // Clase '.confirm-modal'

  // Append Modal HTML To The Main Area
  main_area.innerHTML += html;

  // Show The Modal After 300 ms
  setTimeout(() => {
    document.querySelector('main.container .confirm-modal').classList.add('shown');
  }, 300);
}

