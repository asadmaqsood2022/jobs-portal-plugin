<?php
// Front End
function freelancer_users()
{
	if (is_page('freelancer')) {
?>
		<script>
			jQuery('.site-header').hide();
		</script>
	<?php
	}

	?>
	<div class="wrapper">
		<form class="form" id="freelancer">
			<h3>Create an Account</h3>
			<p><label>First Name</label></p>
			<p><input type="text" value="" name="first_name" id="first_name" /></p>
			<p><label>Last Name</label></p>
			<p><input type="text" value="" name="last_name" id="last_name" /></p>
			<p><label>Email</label></p>
			<p><input type="text" value="" name="email" id="email" /></p>
			<p><label>Username</label></p>
			<p><input type="text" value="" name="username" id="username" /></p>
			<p><label>Skills</label></p>

			<?php
			global $post;

			$taxonomy = "skills";
			$terms = get_terms(
				$taxonomy,
				array(
					"orderby"    => "count",
					"hide_empty" => false
				)
			);
			?>
			<select id="skills" class="form-control select2 select2-hidden-accessible" multiple="" data-placeholder="Select a Skill" style="width: 100%;" tabindex="-1" aria-hidden="true">
				<option value="">Select Skills</option>
				<?php
				foreach ($terms as $term) {
					if ($term->parent) {
						continue;
					}
				?>
					<option value="<?php echo $term->name ?>"><?php echo $term->name ?></option>
				<?php
				}
				?>
			</select>

			<p><label>Password</label></p>
			<p><input type="password" value="" name="password" id="password" /></p>
			<p><label>Password again</label></p>
			<p><input type="password" value="" name="re_password" id="re_password" /></p>
			<button type="submit" id="submit-btn" class="btn-primary">ENTER</button>
			<input type="hidden" name="task" value="register" />
			<span class="check-error"> </span>
			<span class="message-show"></span>
		</form>

	</div>

<?php
}

function register_freelancer()
{
	add_shortcode('freelancer', 'freelancer_users');
}
add_action('init', 'register_freelancer');
