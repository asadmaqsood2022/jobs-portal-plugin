<?php get_header(); ?>
<script>
  jQuery('.site-header').hide();
</script>

<?php
// Login
if (!session_id()) {
  session_start();
}
if ($_SESSION['user_id'] == '') {
?>
  <div class="container login-form">
    <div class="login-form">
      <form class="box" autocomplete="off" id="signinForm" name="signinForm" role="form" novalidate="novalidate">
        <h3>Login</h3>
        <div class="form-group">
          <input placeholder="Email ID*" type="text" name="email" id="user_email" class="form-control">
        </div>
        <div class="form-group">
          <input placeholder="Password*" type="password" id="user_password" class="form-control" name="password">
        </div>
        <div class="checkbox form-group">
          <label><input class="remember-me" type="checkbox" name="remember"><i>Keep me logged in</i></label>
        </div>

        <div class="form-group">
          <button class="btn login-btn" type="submit">LOG IN</button>
          <label class="error-msg"></label>
        </div>
        <div class="form-group">
          <a href="<?php echo site_url(); ?>/freelancer" class="btn freelan-btn" type="submit">Register as a Freelancer</a>
          <a href="<?php echo site_url(); ?>/company" class="btn company-btn" type="submit">Register as a Company</a>

        </div>
      </form>
    </div>
  </div>

<?php
}
?>
<?php get_footer(); ?>
<script>
  jQuery('.site-footer').hide();
</script>