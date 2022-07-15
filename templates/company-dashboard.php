<?php get_header(); ?>
<script>
    jQuery('.site-header').hide();
</script>

<?php
if (!session_id()) {
    session_start();
}
$user_id = $_SESSION['user_id'];

$user = get_user_by('ID', $user_id);
if ($user->roles[0] == 'Company') {
?>

    <div class="wrapper">
        <input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
        <div class="sidebar">
            <div class="title">Job Portal</div>
            <ul class="nav">
                <li>
                    <a id="company_all_jobs">My Jobs</a>
                </li>
                <li>
                    <a id="create_jobs">Create a Job</a>
                </li>
                <li>
                    <a id="company_profile">Profile</a>
                </li>

                <li>
                    <a id="logout" value="<?php echo $user->ID ?>">Logout</a>
                </li>
            </ul>
        </div>
        <div class="content content-is-open">
            <span class='side-panel-toggle'>
                <i class="fa fa-bars"></i>
            </span>
            <?php
            echo 'Hi ' . $user->display_name;
            ?>
            <br>
            <hr>
            <div class="company-list" id="company-content">
                <?php
                $args = array(
                    'post_type' => 'jobs',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'author' => $user_id,
                );

                $loop = new WP_Query($args);

                while ($loop->have_posts()) : $loop->the_post();
                    $fixed_price = get_post_meta(get_the_ID(), 'fixed_price');
                    $min_price = get_post_meta(get_the_ID(), 'min_price');
                    $max_price = get_post_meta(get_the_ID(), 'max_price');

                    // var_dump($fixed_price);
                ?>
                    <div class="box">
                        <div class="box-col box-80">
                            <h4><?php the_title();  ?></h4>
                            <span class="date"> <?php the_time('m/j/y g:i A') ?></span>
                            <span class="function"> <?php the_content();  ?></span>
                        </div>

                        <div class="box-col box-20 text-center">
                            <?php if ($fixed_price['0']) { ?>
                                <h6>Price: <span style="font-size: 22px;">$<?php echo $fixed_price['0']; ?></span></h6>
                            <?php } else if ($min_price['0']) { ?>
                                <h6>Price: <span style="font-size: 22px;">$<?php echo $min_price['0']; ?></span> - <span style="font-size: 22px;">$<?php echo $max_price['0']; ?></span></h6>
                            <?php } ?>
                            <a class="button view_proposal" data-job_id="<?php the_ID() ?>" id="">View Proposal</a>
                        </div>

                    </div>

                <?php
                endwhile;

                wp_reset_postdata();
                ?>


            </div>
        </div>
    </div>


<?PHP

}

?>

<?php get_footer(); ?>
<script>
    jQuery('.site-footer').hide();
</script>