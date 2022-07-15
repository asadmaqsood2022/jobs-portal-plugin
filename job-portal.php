<?php
/*
Plugin Name: Job Portal
Plugin URI: 
Description: 
Version: 1.0
Author: Asad
Author URI: 
*/

register_activation_hook(__FILE__, 'activate_job_portal');
register_deactivation_hook(__FILE__, 'deactivate_job_portal');

function activate_job_portal()
{

  if (!current_user_can('activate_plugins')) return;

  global $wpdb;

  if (null === $wpdb->get_row("SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'freelancer'", 'ARRAY_A')) {

    $current_user = wp_get_current_user();

    // create post object
    $freelancer = array(
      'post_title'  => __('Freelancer'),
      'post_content' => '[freelancer]',
      'post_status' => 'publish',
      'post_author' => $current_user->ID,
      'post_type'   => 'page',
    );

    // insert the post into the database
    if ($created_page_id = wp_insert_post($freelancer)) {
      update_option('freelancer', $created_page_id);
    }

    if (null === $wpdb->get_row("SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'company'", 'ARRAY_A')) {

      $current_user = wp_get_current_user();

      // create post object
      $company = array(
        'post_title'  => __('Company'),
        'post_content' => '[company]',
        'post_status' => 'publish',
        'post_author' => $current_user->ID,
        'post_type'   => 'page',
      );

      // insert the post into the database
      if ($created_page_id = wp_insert_post($company)) {
        update_option('company', $created_page_id);
      }
    }
    // login page

    if (null === $wpdb->get_row("SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'login'", 'ARRAY_A')) {

      $current_user = wp_get_current_user();

      // create post object
      $login = array(
        'post_title'  => __('Login'),
        'post_content' => '[login]',
        'post_status' => 'publish',
        'post_author' => $current_user->ID,
        'post_type'   => 'page',
      );

      // insert the post into the database
      if ($created_page_id = wp_insert_post($login)) {
        update_option('login', $created_page_id);
      }
    }

    // freelancer dashboard page

    if (null === $wpdb->get_row("SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'freelancer-dashboard'", 'ARRAY_A')) {

      $current_user = wp_get_current_user();

      // create post object
      $freelancer_dashboard = array(
        'post_title'  => __('Freelancer Dashboard'),
        'post_content' => '[freelancer_dashboard]',
        'post_status' => 'publish',
        'post_author' => $current_user->ID,
        'post_type'   => 'page',
      );

      // insert the post into the database
      if ($created_page_id = wp_insert_post($freelancer_dashboard)) {
        update_option('freelancer_dashboard', $created_page_id);
      }
    }


    // Company dashboard page

    if (null === $wpdb->get_row("SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'company-dashboard'", 'ARRAY_A')) {

      $current_user = wp_get_current_user();

      // create post object
      $company_dashboard = array(
        'post_title'  => __('Company Dashboard'),
        'post_content' => '[company_dashboard]',
        'post_status' => 'publish',
        'post_author' => $current_user->ID,
        'post_type'   => 'page',
      );

      // insert the post into the database
      if ($created_page_id = wp_insert_post($company_dashboard)) {
        update_option('company_dashboard', $created_page_id);
      }
    }


    // 
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . "proposal";
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        post_id int(20) NOT NULL,
        user_id int(20) NOT NULL,
        proposal text NOT NULL,
        bidding_price text NOT NULL,
        proposal_date text NOT NULL,
        status text NOT NULL,
        PRIMARY KEY  (id)
      ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
  }
}

function deactivate_job_portal()
{
  global $wpdb;
  $the_page_ids = array(intval(get_option('freelancer')), intval(get_option('company')), intval(get_option('login')), intval(get_option('freelancer_dashboard')), intval(get_option('company_dashboard')));
  foreach ($the_page_ids as $the_page_id) {
    wp_delete_post($the_page_id, true);
  }
  remove_role('Freelancer');
  remove_role('Company');
}

function job_portal_enqueue_script()
{
  // css
  wp_enqueue_style('wpse_89494_style_1', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css');
  wp_enqueue_style('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
  wp_enqueue_style('job-portal-css', plugin_dir_url(__FILE__) . 'assets/css/job-portal.css');


  // js
  wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), null, true);
  wp_enqueue_script('validate', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.14.0/jquery.validate.js', array(), null, true);
  wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array(), null, true);
  wp_enqueue_script('bootstrap-JS', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array(), null, true);
  wp_enqueue_script('bootstrap',  'https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js');
  wp_enqueue_script('ajax-js', plugin_dir_url(__FILE__) . 'assets/js/job-portal-ajax.js');
  wp_localize_script('ajax-js',    'ajax_params', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'job_portal_enqueue_script');

function load_custom_wp_admin_style()
{
  // css
  wp_enqueue_style('wpse_89494_style_1', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css');

  wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array(), null, true);

  wp_enqueue_script('admin-js', plugin_dir_url(__FILE__) . 'assets/js/admin.js');
}
add_action('admin_enqueue_scripts', 'load_custom_wp_admin_style');


require plugin_dir_path(__FILE__) . 'include/job-portal-post.php';
require plugin_dir_path(__FILE__) . 'include/job-portal-ajax.php';
require plugin_dir_path(__FILE__) . 'include/freelancer.php';
require plugin_dir_path(__FILE__) . 'include/freelancer-dashboard.php';
require plugin_dir_path(__FILE__) . 'include/company-dashboard.php';
require plugin_dir_path(__FILE__) . 'include/job-single.php';
require plugin_dir_path(__FILE__) . 'include/job-proposal.php';
require plugin_dir_path(__FILE__) . 'include/company.php';
require plugin_dir_path(__FILE__) . 'include/login.php';
//require plugin_dir_path(__FILE__) . 'include/job-portal-menu.php';


// freelancer registraion page
add_filter('page_template', 'wp_page_template_freelancer');
function wp_page_template_freelancer($page_template)
{
  if (is_page('freelancer')) {
    $page_template = plugin_dir_path(__FILE__) . 'templates/freelancer-content.php';
  }
  return $page_template;
}

// company registration page
add_filter('page_template', 'wp_page_template_company');
function wp_page_template_company($page_template)
{
  if (is_page('company')) {
    $page_template = plugin_dir_path(__FILE__) . 'templates/company-content.php';
  }
  return $page_template;
}

// company dashboard page
add_filter('page_template', 'wp_page_template_company_dashboard');
function wp_page_template_company_dashboard($page_template)
{
  if (is_page('company-dashboard')) {
    $page_template = plugin_dir_path(__FILE__) . 'templates/company-dashboard.php';
  }
  return $page_template;
}

// freelancer dashboard page
add_filter('page_template', 'wp_page_template_freelancer_dashboard');
function wp_page_template_freelancer_dashboard($page_template)
{
  if (is_page('freelancer-dashboard')) {
    $page_template = plugin_dir_path(__FILE__) . 'templates/freelancer-dashboard.php';
  }
  return $page_template;
}

// login page
add_filter('page_template', 'wp_page_template_login');
function wp_page_template_login($page_template)
{
  if (is_page('login')) {
    $page_template = plugin_dir_path(__FILE__) . 'templates/login.php';
  }
  return $page_template;
}
