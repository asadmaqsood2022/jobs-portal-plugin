<?php
if (!session_id()) {
    session_start();
}

// Front End
function job_proposal()
{

    $user_id = $_SESSION['user_id'];

    $user = get_user_by('ID', $user_id);
    if ($user->roles[0] == 'Company') {
        $job_id = $_GET['job-id'];
?>

        <?php echo get_the_title($job_id);  ?><br>
        <?php echo get_the_time('m/j/y g:i A', $job_id) ?><br>

<?php

    }
}

function register_job_proposal()
{
    add_shortcode('job_proposal', 'job_proposal');
}
add_action('init', 'register_job_proposal');
