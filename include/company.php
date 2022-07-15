<?php
// Front End
function company_users()
{
?>
	<div class="wrapper">
		<form class="form" id="company">
			<h3>Create an Account</h3>
			<p><label>Company Name</label></p>
			<p><input type="text" value="" name="company_name" id="company_name" /></p>
			<p><label>Company URL</label></p>
			<p><input type="text" value="" name="company_url" id="company_url" /></p>
			<p><label>Email</label></p>
			<p><input type="text" value="" name="email" id="email" /><span class="check-error"> </span></p>
			<p><label>Password</label></p>
			<p><input type="password" value="" name="password" id="password" /></p>
			<p><label>Password again</label></p>
			<p><input type="password" value="" name="re_password" id="re_password" /></p>

			<button type="submit" class="btn-primary">ENTER</button>
			<input type="hidden" name="task" value="register" />

			<span class="message-show"></span>
		</form>

	</div>

<?php
}

function register_company()
{
	add_shortcode('company', 'company_users');
}
add_action('init', 'register_company');
