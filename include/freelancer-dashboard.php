<?php
if (!session_id()) {
    session_start();
}
// Front End
function freelancer_dashboard()
{
    $user_id = $_SESSION['user_id'];

    $user = get_user_by('ID', $user_id);
    if ($user->roles[0] == 'Freelancer') {
        echo 'Hi ' . $user->display_name;
?>
        <button id="logout" value="<?php echo $user->ID ?>" href="">Logout</button>
        <br><br><br><br>
        <?php
        $args = array(
            'post_type' => 'jobs',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        );

        $loop = new WP_Query($args);

        while ($loop->have_posts()) : $loop->the_post();
        ?>
            <?php the_title();  ?><br>
            <?php the_time('m/j/y g:i A') ?><br>
            <?php the_content();  ?><br>
            <a href="<?php echo get_bloginfo('wpurl'); ?>/job-single?job-id=<?php the_ID() ?>"> Send Propose </a><br>
            <br>

<?php
        endwhile;

        wp_reset_postdata();
    }
}

function register_freelancer_dashboard()
{
    add_shortcode('freelancer_dashboard', 'freelancer_dashboard');
}
add_action('init', 'register_freelancer_dashboard');
