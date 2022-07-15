<?php
if (!session_id()) {
    session_start();
}

// Front End
function company_dashboard()
{
    $user_id = $_SESSION['user_id'];

    $user = get_user_by('ID', $user_id);
    if ($user->roles[0] == 'Company') {
        echo 'Hi ' . $user->display_name;
?>
        <button id="logout" value="<?php echo $user->ID ?>" href="">Logout</button>
        <br><br><br><br>
        <?php
        $args = array(
            'post_type' => 'jobs',
            'post_status' => 'publish',
            'posts_per_page' => 8,
            'orderby' => 'date',
            'order' => 'DESC',
        );

        $loop = new WP_Query($args);

        while ($loop->have_posts()) : $loop->the_post();
        ?>
            <?php the_title();  ?><br>
            <?php the_time('m/j/y g:i A') ?><br>
            <?php the_content();  ?><br>
            <a href="<?php echo get_bloginfo('wpurl'); ?>/job-proposal?job-id=<?php the_ID() ?>"> View Propose </a><br>
            <br>

<?php
        endwhile;

        wp_reset_postdata();
    }
}

function register_company_dashboard()
{
    add_shortcode('company_dashboard', 'company_dashboard');
}
add_action('init', 'register_company_dashboard');
