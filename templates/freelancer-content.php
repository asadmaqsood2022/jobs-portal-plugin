<?php get_header(); ?>
<script>
    jQuery('.site-header').hide();
</script>

<div class="container freelancer-signup">
    <form class="form box" id="freelancer">
        <h3>Register as a Freelancer</h3>
        <div class="form-group ">
            <p><input placeholder="First Name*" type="text" value="" name="first_name" id="first_name" /></p>
        </div>
        <div class="form-group">
            <p><input placeholder="Last Name*" type="text" value="" name="last_name" id="last_name" /></p>
        </div>
        <div class="form-group">
            <p><input placeholder="Email*" type="text" value="" name="email" id="email" /></p>
        </div>
        <div class="form-group">
            <p><input placeholder="Username*" type="text" value="" name="username" id="username" /></p>
        </div>
        <div class="form-group">

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
            <select name="skills[]" id="skills" class="form-control select2 select2-hidden-accessible" multiple="" data-placeholder="Select a Skill" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
        </div>
        <div class="form-group">
            <p><input placeholder="Password*" type="password" value="" name="password" id="password" /></p>
        </div>
        <div class="form-group">
            <p><input placeholder="Password Again*" type="password" value="" name="re_password" id="re_password" /></p>
        </div>
        <div class="form-group">
            <button type="submit" id="submit-btn" class="btn-primary btn">SignUp</button>
            <input type="hidden" name="task" value="register" />
            <span class="check-error"> </span>
            <span class="message-show"></span>
    </form>

</div>


<?php get_footer(); ?>
<script>
    jQuery('.site-footer').hide();
</script>