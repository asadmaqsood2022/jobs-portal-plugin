<?php get_header(); ?>
<script>
    jQuery('.site-header').hide();
</script>
<div class="container company-signup">
    <form class="form box" id="company">
        <h3>Register as a Company</h3>
        <div class="form-group">
            <p><input placeholder="Company Name*" type="text" value="" name="company_name" id="company_name" /></p>
        </div>
        <div class="form-group">
            <p><input placeholder="Company URL*" type="text" value="" name="company_url" id="company_url" /></p>
        </div>
        <div class="form-group">
            <p><input placeholder="Email*" type="text" value="" name="email" id="email" /><span class="check-error"> </span></p>
        </div>
        <div class="form-group">
            <p><input placeholder="Password*" type="password" value="" name="password" id="password" /></p>
        </div>
        <div class="form-group">
            <p><input placeholder="Password Again*" type="password" value="" name="re_password" id="re_password" /></p>
        </div>
        <div class="form-group">
            <button type="submit" class="btn-primary btn">SignUp</button>
            <input type="hidden" name="task" value="register" />
        </div>
        <span class="message-show"></span>
    </form>

</div>


<?php get_footer(); ?>
<script>
    jQuery('.site-footer').hide();
</script>