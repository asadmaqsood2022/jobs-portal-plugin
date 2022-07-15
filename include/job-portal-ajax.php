<?php
//Freelancer form submit
add_action('wp_ajax_nopriv_get_freelancer_details', 'get_freelancer_details');
add_action('wp_ajax_get_freelancer_details', 'get_freelancer_details');

function get_freelancer_details()
{
   $error = '';
   $success = '';

   global $wpdb, $PasswordHash, $current_user, $user_ID;
   $password = $wpdb->escape(trim($_POST['password']));
   $re_password = $wpdb->escape(trim($_POST['re_password']));
   $first_name = $wpdb->escape(trim($_POST['first_name']));
   $last_name = $wpdb->escape(trim($_POST['last_name']));
   $email = $wpdb->escape(trim($_POST['email']));
   $username = $wpdb->escape(trim($_POST['username']));
   $Skills = $wpdb->escape($_POST['skills']);
   //var_dump($Skills);

   if ($email == "" || $password == "" || $re_password == "" | $username == "" || $first_name == "" || $last_name == "") {
      $error = 'Please don\'t leave the required fields.';
   } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = 'Invalid email address.';
   } else if (email_exists($email)) {
      $error = 'Email already exist.';
   } else if ($password <> $re_password) {
      $error = 'Password do not match.';
   } else {

      $user_id = wp_insert_user(array('first_name' => apply_filters('pre_user_first_name', $first_name), 'last_name' => apply_filters('pre_user_last_name', $last_name), 'user_pass' => apply_filters('pre_user_user_pass', $password), 'user_login' => apply_filters('pre_user_user_login', $username), 'user_email' => apply_filters('pre_user_user_email', $email), 'role' => 'Freelancer'));
      if (is_wp_error($user_id)) {
         $error = 'Error on user creation.';
      } else {
         do_action('user_register', $user_id);
         add_user_meta($user_id, 'skills', $Skills); // add the meta

         $success = 'You\'re successfully register';
      }
   }
   echo $error;
   echo $success;


   die();
}

//Freelancer form submit


//check user name
add_action('wp_ajax_nopriv_check_user_name', 'check_user_name');
add_action('wp_ajax_check_user_name', 'check_user_name');

function check_user_name()
{
   $username = $_POST['username'];
   $email = $_POST['email'];
   global $wpdb;

   $data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}users WHERE user_login = '$username' OR user_email='$email'", 'ARRAY_A');

   // var_dump($data);
   foreach ($data as $obj) {
      //  var_dump($obj['user_login']);
      if ($obj['user_login'] == $username) {
         echo "user_name";
      } else if ($obj['user_email'] == $email) {
         echo "user_email";
      }
   }

   die();
}



//Company form submit
add_action('wp_ajax_nopriv_get_company_details', 'get_company_details');
add_action('wp_ajax_get_company_details', 'get_company_details');

function get_company_details()
{
   $error = '';
   $success = '';

   global $wpdb, $PasswordHash, $current_user, $user_ID;
   $password = $wpdb->escape(trim($_POST['password']));
   $re_password = $wpdb->escape(trim($_POST['re_password']));
   $company_name = $wpdb->escape(trim($_POST['company_name']));
   $company_url = $wpdb->escape(trim($_POST['company_url']));
   $email = $wpdb->escape(trim($_POST['email']));

   if ($email == "" || $password == "" || $re_password == ""  || $company_name == "" || $company_url == "") {
      $error = 'Please don\'t leave the required fields.';
   } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = 'Invalid email address.';
   } else if (email_exists($email)) {
      $error = 'Email already exist.';
   } else if ($password <> $re_password) {
      $error = 'Password do not match.';
   } else {

      $user_id = wp_insert_user(array(
         'user_login' => $company_name,
         'user_url' => $company_url,
         'user_pass' => $password,
         'user_email' => $email,
         'role' => 'Company'

      ));
      if (is_wp_error($user_id)) {
         $error = 'Error on user creation.';
      } else {
         do_action('user_register', $user_id);

         $success = 'You\'re successfully register';
      }
   }
   echo $error;
   echo $success;


   die();
}




// login form function
add_action('wp_ajax_nopriv_get_login_details', 'get_login_details');
add_action('wp_ajax_get_get_login_details', 'get_login_details');

function get_login_details()
{
   if (isset($_POST) && !empty($_POST)) {

      global $wpdb;
      $user_email     = $_POST['user_email'];
      $user_password  = $_POST['user_password'];

      $user = get_user_by('email', $user_email);
      if ($user && wp_check_password($user_password, $user->data->user_pass, $user->ID)) {
         //   echo $user->roles[0];

         if (!session_id()) {
            session_start();
         }
         $_SESSION['user_id'] = $user->ID;

         //  var_dump($_SESSION['user_id']);

         if ($user->roles[0] == 'Freelancer') {
            echo get_bloginfo('wpurl') . '/freelancer-dashboard/';
         }
         if ($user->roles[0] == 'Company') {
            echo get_bloginfo('wpurl') . '/company-dashboard/';
         }
      }
   }

   die();
}


// logout form function
add_action('wp_ajax_nopriv_get_logout', 'get_logout');
add_action('wp_ajax_get_get_logout', 'get_logout');

function get_logout()
{
   if (!session_id()) {
      session_start();
   }

   if (isset($_SESSION["user_id"])) {
      session_destroy();
      echo get_bloginfo('wpurl') . '/login/';
   }



   die();
}


// proposal form function
add_action('wp_ajax_nopriv_get_proposal_details', 'get_proposal_details');
add_action('wp_ajax_get_proposal_details', 'get_proposal_details');

function get_proposal_details()
{
   if (isset($_POST['Proposal_text'])) {
      $proposal = $_POST['Proposal_text'];
      $bidding_price = $_POST['bidding_price'];
      $post_id = $_POST['post_id'];
      $user_id = $_POST['user_id'];
      $date = date("F j, Y, g:i a");



      global $wpdb;
      $tablename = $wpdb->prefix . 'proposal';

      $wpdb->insert(
         $tablename,
         array(
            'post_id' => $post_id,
            'user_id' => $user_id,
            'proposal' => $proposal,
            'proposal_date' => $date,
            'bidding_price' => $bidding_price,

         ),
      );
   }



   die();
}



// get feed jobs function
add_action('wp_ajax_nopriv_get_feed_jobs', 'get_feed_jobs');
add_action('wp_ajax_get_feed_jobs', 'get_feed_jobs');

function get_feed_jobs()
{
   $user_id = $_POST['user_id'];
   $key = 'skills';
   $single = true;
   $get_skills = get_user_meta($user_id, $key, $single);

   $terms = get_terms(array(
      'taxonomy' => 'skills',
      'hide_empty' => true,
   ));
   $match_skill[] = '';

   foreach ($terms as $term) {
      foreach ($get_skills as $key => $value) {
         // var_dump($get_skills[$index]);

         if ($term->name == $value) {
            $match_skill[] = $value;
         }
      }
   }
   //var_dump($match_skill);

?>

   <div class="jobs-list" id="jobs-list">
      <?php
      $args = array(
         'post_type' => 'jobs',
         'post_status' => 'publish',
         'posts_per_page' => -1,
         'orderby' => 'title',
         'order' => 'ASC',
         'tax_query' => array(
            array(
               'taxonomy' => 'skills',
               'field' => 'name',
               'terms' => $match_skill,
            )
         ),
      );

      $loop = new WP_Query($args);

      while ($loop->have_posts()) : $loop->the_post();
         global $wpdb;
         $fixed_price = get_post_meta(get_the_ID(), 'fixed_price');
         $min_price = get_post_meta(get_the_ID(), 'min_price');
         $max_price = get_post_meta(get_the_ID(), 'max_price');
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


               <?php
               $post_id = get_the_ID();
               $data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}proposal WHERE user_id='$user_id' AND post_id='$post_id'", 'ARRAY_A');
               if ($data) {
               ?>
                  <a class="button" disabled="disabled">Submitted</a>
               <?php
               } else {
               ?>
                  <a class="button apply_jobs" data-job_id="<?php the_ID() ?>" id="">Apply</a>
               <?php
               }
               ?>
            </div>

         </div>

      <?php
      endwhile;

      wp_reset_postdata();
      ?>
   </div>

<?php

   die();
}



// get all jobs function
add_action('wp_ajax_nopriv_get_all_jobs', 'get_all_jobs');
add_action('wp_ajax_get_all_jobs', 'get_all_jobs');

function get_all_jobs()
{
   $user_id = $_POST['user_id'];
?>
   <?php
   $args = array(
      'post_type' => 'jobs',
      'post_status' => 'publish',
      'posts_per_page' => -1,
      'orderby' => 'date',
      'order' => 'DESC',
   );

   $loop = new WP_Query($args);

   while ($loop->have_posts()) : $loop->the_post();
      global $wpdb;
      $fixed_price = get_post_meta(get_the_ID(), 'fixed_price');
      $min_price = get_post_meta(get_the_ID(), 'min_price');
      $max_price = get_post_meta(get_the_ID(), 'max_price');
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
            <?php
            $post_id = get_the_ID();
            $data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}proposal WHERE user_id='$user_id' AND post_id='$post_id'", 'ARRAY_A');
            if ($data) {
            ?>
               <a class="button" disabled="disabled">Submitted</a>
            <?php
            } else {
            ?>
               <a class="button apply_jobs" data-job_id="<?php the_ID() ?>" id="">Apply</a>
            <?php
            }
            ?>
         </div>

      </div>

   <?php
   endwhile;

   wp_reset_postdata();
   ?>

<?php

   die();
}


// single job proposal function
add_action('wp_ajax_nopriv_get_single_jobs', 'get_single_jobs');
add_action('wp_ajax_get_single_jobs', 'get_single_jobs');

function get_single_jobs()
{
   $job_id = $_POST['job_id'];
?>
   <?php
   if (!session_id()) {
      session_start();
   }
   $user_id = $_SESSION['user_id'];

   $user = get_user_by('ID', $user_id);
   if ($user->roles[0] == 'Freelancer') {

      // $job_id = $_GET['job-id'];
   ?>

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
                        <label for="proposal">bidding Price</label><br>
                        <input class="form-control" name="bidding_price" type="text" placeholder="$20" id="bidding_price">
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
                           <input id="pro_submit" type="submit" value="Apply">
                        <?php
                        }
                        ?>
                        <span id="proposal-success"></span>
                     </form>

                  </div>



               </div>

            <?php
            endwhile;

            wp_reset_postdata();
            ?>


         </div>


      </div>


   <?PHP

   }

   ?>

<?php
   die();
}



// updtae freelancer profile
add_action('wp_ajax_nopriv_update_freelancer_profile', 'update_freelancer_profile');
add_action('wp_ajax_update_freelancer_profile', 'update_freelancer_profile');

function update_freelancer_profile()
{
   $user_id = $_POST['user_id'];
   $user = get_user_by('id', $user_id);
   //  var_dump($user);
?>
   <form class="form" id="freelancer_profile_update">
      <h3>Update Profile</h3>
      <p><label>First Name</label></p>
      <p><input type="text" value="<?php echo $user->user_firstname; ?>" name="first_name" id="first_name" /></p>
      <p><label>Last Name</label></p>
      <p><input type="text" value="<?php echo $user->user_lastname; ?>" name="last_name" id="last_name" /></p>
      <p><label>Email</label></p>
      <p><input type="text" value="<?php echo $user->user_email; ?>" name="email" id="email" /></p>
      <p><label>Username</label></p>
      <p><input type="text" value="<?php echo $user->user_login; ?>" name="username" id="username" /></p>
      <p><label>Skills</label></p>


      <?php
      $key = 'skills';
      $single = true;
      $get_skills = get_user_meta($user_id, $key, $single);
      foreach ($get_skills as $key => $value) {
         $slt_technology[] = $value;
      }
      ?>

      <select name="wporg_field[]" id="skills" class="form-control select2 " multiple="" data-placeholder="Select a Skill" style="width: 100%;" tabindex="-1" aria-hidden="true">
         <option value="">Select Skills</option>
         <?php
         $taxonomy = "skills";
         $terms = get_terms(
            $taxonomy,
            array(
               "orderby"    => "count",
               "hide_empty" => false
            )
         );

         foreach ($terms as $term) {
            if ($term->parent) {
               continue;
            }
         ?>
            <option value="<?php echo $term->name; ?>" <?php
                                                         if (in_array($term->name, $slt_technology)) {
                                                            echo 'selected';
                                                         } ?>><?php echo $term->name; ?>
            </option>


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
      <input type="hidden" value="<?php echo $user_id; ?>" id="user_id" />
      <span class="check-error"> </span>
      <span class="message-show"></span>
   </form>

<?php
   die();
}



//Freelancer form submit
add_action('wp_ajax_nopriv_get_freelancer_profile_update', 'get_freelancer_profile_update');
add_action('wp_ajax_get_freelancer_profile_update', 'get_freelancer_profile_update');

function get_freelancer_profile_update()
{
   $user_id = $_POST['user_id'];
   $error = '';
   $success = '';

   global $wpdb, $PasswordHash, $current_user, $user_ID;
   $password = $wpdb->escape(trim($_POST['password']));
   $re_password = $wpdb->escape(trim($_POST['re_password']));
   $first_name = $wpdb->escape(trim($_POST['first_name']));
   $last_name = $wpdb->escape(trim($_POST['last_name']));
   $email = $wpdb->escape(trim($_POST['email']));
   $username = $wpdb->escape(trim($_POST['username']));
   $Skills = $wpdb->escape($_POST['skills']);
   //var_dump($Skills);

   if ($email == "" || $password == "" || $re_password == "" | $username == "" || $first_name == "" || $last_name == "") {
      $error = 'Please don\'t leave the required fields.';
   } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = 'Invalid email address.';
   } else if ($password <> $re_password) {
      $error = 'Password do not match.';
   } else {
      $data = wp_update_user(array(
         'ID' => $user_id,
         'user_email' => $email,
         'first_name' => $first_name,
         'last_name' => $last_name,
         'user_login' => $username,
         'user_pass' => $password,
      ));

      if (is_wp_error($data)) {
         $error = 'Error on user creation.';
      } else {
         do_action('user_register', $data);
         update_user_meta($data, 'skills', $Skills); // add the meta

         $success = 'You\'re successfully register';
      }
   }
   echo $error;
   echo $success;


   die();
}



//get_all_proposal

// proposal form function
add_action('wp_ajax_nopriv_get_all_proposal', 'get_all_proposal');
add_action('wp_ajax_get_all_proposal', 'get_all_proposal');

function get_all_proposal()
{
   global $wpdb;
   $user_id = $_POST['user_id'];
   $data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}proposal WHERE user_id='$user_id'", 'ARRAY_A');
   // var_dump($data);
?>


   <div class="table-responsive py-5">
      <table class="table table-bordered table-hover">
         <thead class="thead-dark">
            <tr>
               <th scope="col">Job Title</th>
               <th scope="col">My Proposal</th>
               <th scope="col">bidding Price</th>
               <th scope="col">Date</th>
               <th scope="col">Status</th>
            </tr>
         </thead>
         <tbody>
            <?php
            foreach ($data as  $value) {
            ?>
               <tr>
                  <th scope="row"><?php echo get_the_title($value['post_id']); ?></th>
                  <td><?php echo $value['proposal']; ?></td>
                  <td>
                     <?php if ($value['bidding_price']) {
                     ?>
                        $<?php echo $value['bidding_price']; ?>
                     <?php } ?>

                  </td>
                  <td><?php echo $value['proposal_date']; ?></td>
                  <td><?php
                        if ($value['status'] == 1) {
                           echo "Accept";
                        } else if ($value['status'] == 0) {
                           echo "Pending...";
                        }
                        if ($value['status'] == 2) {
                           echo "Reject";
                        }
                        ?></td>
               </tr>
            <?php
            }
            ?>

         </tbody>
      </table>
   </div>


<?php
   die();
}


// get_all_jobs_in_company function
add_action('wp_ajax_nopriv_get_all_jobs_in_company', 'get_all_jobs_in_company');
add_action('wp_ajax_get_all_jobs_in_company', 'get_all_jobs_in_company');

function get_all_jobs_in_company()
{
   $user_id = $_POST['user_id'];
   //  var_dump($user_id);
?>


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


<?php
   die();
}



// update_company_profile
add_action('wp_ajax_nopriv_update_company_profile', 'update_company_profile');
add_action('wp_ajax_update_company_profile', 'update_company_profile');

function update_company_profile()
{
   $user_id = $_POST['user_id'];
   $user = get_user_by('id', $user_id);
   // var_dump($user);
?>
   <form class="form" id="company_profile_update">
      <h3>Update Profile</h3>
      <p><label>Company Name</label></p>
      <p><input type="text" value="<?php echo $user->display_name; ?>" name="company_name" id="company_name" /></p>
      <p><label>Company URL</label></p>
      <p><input type="text" value="<?php echo $user->user_url; ?>" name="company_url" id="company_url" /></p>
      <p><label>Email</label></p>
      <p><input type="text" value="<?php echo $user->user_email ?>" name="email" id="email" /><span class="check-error"> </span></p>
      <p><label>Password</label></p>
      <p><input type="password" value="" name="password" id="password" /></p>
      <p><label>Password again</label></p>
      <p><input type="password" value="" name="re_password" id="re_password" /></p>

      <button type="submit" class="btn-primary">ENTER</button>
      <input type="hidden" name="task" value="register" />

      <span class="message-show"></span>
   </form>

<?php
   die();
}


//company form update
add_action('wp_ajax_nopriv_get_company_profile_update', 'get_company_profile_update');
add_action('wp_ajax_get_company_profile_update', 'get_company_profile_update');

function get_company_profile_update()
{
   $user_id = $_POST['user_id'];
   $error = '';
   $success = '';
   // var_dump($user_id);
   global $wpdb, $PasswordHash, $current_user, $user_ID;
   $password = $wpdb->escape(trim($_POST['password']));
   $re_password = $wpdb->escape(trim($_POST['re_password']));
   $company_name = $wpdb->escape(trim($_POST['company_name']));
   $company_url = $wpdb->escape(trim($_POST['company_url']));
   $email = $wpdb->escape(trim($_POST['email']));

   if ($email == "" || $password == "" || $re_password == ""  || $company_name == "" || $company_url == "") {
      $error = 'Please don\'t leave the required fields.';
   } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = 'Invalid email address.';
   } else if ($password <> $re_password) {
      $error = 'Password do not match.';
   } else {

      $data = wp_update_user(array(
         'ID' => $user_id,
         'user_email' => $email,
         'display_name' => $company_name,
         'user_url' => $company_url,
         'user_pass' => $password,
      ));

      if (is_wp_error($data)) {
         $error = 'Error on user creation.';
      } else {
         do_action('user_register', $data);
         $success = 'You\'re successfully register';
      }
   }
   echo $error;
   echo $success;

   die();
}


// create a job
add_action('wp_ajax_nopriv_create_a_job', 'create_a_job');
add_action('wp_ajax_create_a_job', 'create_a_job');

function create_a_job()
{
   $user_id = $_POST['user_id'];

?>
   <div class="col-sm-12">
      <form class="form-horizontal" name="form" method="post" id="submit_job">
         <input type="hidden" name="ispost" value="1" />
         <input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
         <div class="col-md-12">
            <label class="control-label">Job Title</label>
            <input type="text" class="form-control" name="job_title" id="job_title" />
         </div>

         <div class="col-md-12">
            <label class="control-label">Job Description</label>
            <textarea class="form-control" rows="8" name="job_description" id="job_description"></textarea>
         </div>
         <div class="col-md-12">
            <label class="control-label">Choose Job Type</label>
            <select name="job_type_dropdown" id="job_type_dropdown">
               <option value="">Please select</option>
               <option value="fixed">Fixed</option>
               <option value="hourly">Hourly</option>
            </select>
            <div class="fixed job-options">

               <div class="col-md-12">
                  <input type="text" placeholder="$20" class="form-control" name="fixed_price" id="fixed_price" />
               </div>

            </div>
            <div class="hourly job-options">

               <div class="col-md-6">
                  <input type="text" placeholder="Min Price" class="form-control" name="min_price" id="min_price" />
               </div>
               <div class="col-md-6">
                  <input type="text" placeholder="Max Price" class="form-control" name="max_price" id="max_price" />
               </div>
            </div>
         </div>

         <div class="col-md-12">
            <label class="control-label">Choose Skills</label>

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
            <select name="skills" id="skills" class="form-control select2 select2-hidden-accessible" multiple="" data-placeholder="Select a Skill" style="width: 100%;" tabindex="-1" aria-hidden="true">
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



         <div class="col-md-12">
            <br>
            <input type="submit" class="btn btn-primary" value="SUBMIT" name="submitpost" />
         </div>
         <span class="message-show"></span>
      </form>
      <div class="clearfix"></div>
   </div>

<?php
   die();
}



// job post submit function


//company form update
add_action('wp_ajax_nopriv_get_job_post_details', 'get_job_post_details');
add_action('wp_ajax_get_job_post_details', 'get_job_post_details');


function get_job_post_details()
{
   $user_id = $_POST['user_id'];
   $error = '';
   $success = '';
   // var_dump($user_id);
   global $wpdb, $PasswordHash, $current_user, $user_ID;
   $job_title = $_POST['job_title'];
   $job_description = $_POST['job_description'];
   $skills = $_POST['skills'];
   $fixed_price = $_POST['fixed_price'];
   $min_price = $_POST['min_price'];
   $max_price = $_POST['max_price'];


   if ($job_title == "" || $job_description == "" || $skills == "") {
      $error = 'Please don\'t leave the required fields.';
   } else {

      $new_post = array(
         'post_title' => $job_title,
         'post_content' => $job_description,
         'post_status' => 'Publish',
         'post_name' => $job_title,
         'post_type' => 'jobs',
         'post_author' => $user_id,
      );

      if (is_wp_error($new_post)) {
         $error = 'Error on user creation.';
      } else {
         $pid = wp_insert_post($new_post, false);
         wp_set_object_terms($pid, $skills, 'skills');
         add_post_meta($pid, 'fixed_price', $fixed_price);
         add_post_meta($pid, 'min_price', $min_price);
         add_post_meta($pid, 'max_price', $max_price);
         $success = 'Job Publish successfully';
      }
   }
   echo $error;
   echo $success;

   die();
}


// Get Proposals on single job

// proposal form function
add_action('wp_ajax_nopriv_get_proposals_details', 'get_proposals_details');
add_action('wp_ajax_get_proposals_details', 'get_proposals_details');

function get_proposals_details()
{
   global $wpdb;
   $user_id = $_POST['user_id'];
   $job_id = $_POST['job_id'];
   $data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}proposal WHERE post_id='$job_id'", 'ARRAY_A');
?>


   <div class="table-responsive py-5">
      <input type="hidden" value="<?php echo $user_id; ?>" id="user_id">
      <h3><?php echo get_the_title($job_id); ?></h3>
      <table class="table table-bordered table-hover">
         <thead class="thead-dark">
            <tr>
               <th scope="col">Freelancer Name</th>
               <th scope="col">Proposal</th>
               <th scope="col">bidding Price</th>
               <th scope="col">Date</th>
               <th scope="col">Action</th>
            </tr>
         </thead>
         <tbody>
            <?php
            foreach ($data as  $value) {
               $user = get_user_by('id', $value['user_id']);
            ?>
               <tr>
                  <th scope="row"><?php echo $user->user_firstname; ?></th>
                  <td><?php echo $value['proposal']; ?></td>
                  <td>
                     <?php if ($value['bidding_price']) {
                     ?>
                        $<?php echo $value['bidding_price']; ?>
                     <?php } ?>

                  </td>
                  <td><?php echo $value['proposal_date']; ?></td>
                  <td>
                     <?php
                     if ($value['status'] == 1) {
                     ?>
                        <button type="button" data-job_id="<?php echo $value['post_id'] ?>" data-user_id="<?php echo $value['user_id'] ?>" id="accept_proposal" class="btn btn-success" disabled>Accepted</button>
                        <button type="button" data-job_id="<?php echo $value['post_id'] ?>" data-user_id="<?php echo $value['user_id'] ?>" id="reject_proposal" id="reject_proposal" class="btn btn-danger">Reject</button>
                     <?php
                     } else if ($value['status'] == 2) {
                     ?>
                        <button type="button" data-job_id="<?php echo $value['post_id'] ?>" data-user_id="<?php echo $value['user_id'] ?>" id="accept_proposal" class="btn btn-success">Accept</button>
                        <button type="button" data-job_id="<?php echo $value['post_id'] ?>" data-user_id="<?php echo $value['user_id'] ?>" id="reject_proposal" id="reject_proposal" class="btn btn-danger" disabled>Rejected</button>

                     <?php
                     } else {
                     ?>
                        <button type="button" data-job_id="<?php echo $value['post_id'] ?>" data-user_id="<?php echo $value['user_id'] ?>" id="accept_proposal" class="btn btn-success">Accept</button>
                        <button type="button" data-job_id="<?php echo $value['post_id'] ?>" data-user_id="<?php echo $value['user_id'] ?>" id="reject_proposal" id="reject_proposal" class="btn btn-danger">Reject</button>
                     <?php } ?>
                  </td>
               </tr>
            <?php
            }
            ?>

         </tbody>
      </table>
   </div>


<?php
   die();
}



// Accept proposal function
add_action('wp_ajax_nopriv_accept_proposal_function', 'accept_proposal_function');
add_action('wp_ajax_accept_proposal_function', 'accept_proposal_function');

function accept_proposal_function()
{
   global $wpdb;
   $user_id = $_POST['user_id'];
   $job_id = $_POST['job_id'];
   $status = 1;
   $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}proposal SET status='$status' WHERE user_id=$user_id AND post_id=$job_id"));
   echo 1;
?>
<?php
   die();
}

// Reject proposal function
add_action('wp_ajax_nopriv_reject_proposal_function', 'reject_proposal_function');
add_action('wp_ajax_reject_proposal_function', 'reject_proposal_function');

function reject_proposal_function()
{
   global $wpdb;
   $user_id = $_POST['user_id'];
   $job_id = $_POST['job_id'];
   $status = 2;
   $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}proposal SET status='$status' WHERE user_id=$user_id AND post_id=$job_id"));
   echo 2;
?>
<?php
   die();
}
