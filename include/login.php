<?php
// Login
if (!session_id()) {
  session_start();
}
function login_function()
{
  if ($_SESSION['user_id'] == '') {
?>
    <div class="login-from">
      <form autocomplete="off" id="signinForm" name="signinForm" role="form" novalidate="novalidate">
        <div class="form-group">
          <label>Email ID</label>
          <input type="text" name="email" id="user_email" class="form-control">
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" id="user_password" class="form-control" name="password">
        </div>
        <div class="checkbox form-group">
          <label><input type="checkbox" name="remember"><i>Keep me logged in</i></label>
        </div>
        <div class="form-group">
          <button class="btn" type="submit">LOG IN</button>
          <label class="error-msg"></label>
        </div>
      </form>
    </div>

<?php
  }
}

function login_action()
{
  add_shortcode('login', 'login_function');
}
add_action('init', 'login_action');
