<?php
if (!session_id()) {
    session_start();
}
// Front End
function job_single()
{

    $user_id = $_SESSION['user_id'];

    $user = get_user_by('ID', $user_id);
    if ($user->roles[0] == 'Freelancer') {

        $job_id = $_GET['job-id'];
?>

        <?php echo get_the_title($job_id);  ?><br>
        <?php echo get_the_time('m/j/y g:i A', $job_id) ?><br>
        <form action="/action_page.php">
            <label for="proposal">Proposal</label><br>
            <textarea id="txtArea" rows="3" cols="100"></textarea>
            <br>
            <input type="submit" value="Submit">
        </form>

<?php

    }
}

function register_job_single()
{
    add_shortcode('job_single', 'job_single');
}
add_action('init', 'register_job_single');
