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
if ($user->roles[0] == 'Freelancer') {

    $job_id = $_GET['job-id'];
?>

    <div class="wrapper">
        <div class="sidebar">
            <div class="title">Job Portal</div>
            <ul class="nav">
                <li>
                    <a href="<?php echo site_url() ?>/freelancer-dashboard/">My Feed</a>
                </li>
                <li>
                    <a href="<?php echo site_url() ?>/freelancer-dashboard/">All Jobs</a>
                </li>
                <li>
                    <a>All Proposal</a>
                </li>
                <li>
                    <a>Profile</a>
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
            <div class="box">
                <div class="box-col box-80">

                    <?php
                    $args = array(
                        'post_type' => 'jobs',
                        'p' => $job_id

                    );

                    $loop = new WP_Query($args);

                    while ($loop->have_posts()) : $loop->the_post();
                    ?>
                        <div class="box">
                            <div class="box-col box-100">
                                <h4><?php the_title();  ?></h4>
                                <span class="date"> <?php the_time('m/j/y g:i A') ?></span>
                                <span class="function"> <?php the_content();  ?></span>
                                <form autocomplete="off" id="proposalForm" name="proposalForm" role="form" novalidate="novalidate">
                                    <label for="proposal">Proposal</label><br>
                                    <textarea id="Proposal_text" rows="3" class="form-control" name="Proposal_text" cols="100"></textarea>
                                    <input type="hidden" value="<?php echo get_the_ID(); ?>" id="post_id">
                                    <input type="hidden" value="<?php echo $user_id; ?>" id="user_id">

                                    <br>

                                    <?php
                                    global $wpdb;

                                    $data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}proposal WHERE user_id='$user_id' AND post_id='$job_id'", 'ARRAY_A');

                                    if ($data) {
                                    ?>
                                        <input type="submit" value="Submitted" disabled>
                                    <?php
                                    } else {
                                    ?>
                                        <input type="submit" value="Submit">
                                    <?php
                                    }
                                    ?>
                                </form>
                            </div>



                        </div>

                    <?php
                    endwhile;

                    wp_reset_postdata();
                    ?>


                </div>


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