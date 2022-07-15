<?php
if (!session_id()) {
  session_start();
}
// Login
function menu_function()
{
  $user_id = $_SESSION['user_id'];

  $user = get_user_by('ID', $user_id);
  if ($user->roles[0] == 'Freelancer') {

    $menuname = 'Freelancer Menu';
    $bpmenulocation = 'freelancer-menu';
    // Does the menu exist already?
    $menu_exists = wp_get_nav_menu_object($menuname);

    // If it doesn't exist, let's create it.
    if (!$menu_exists) {
      $menu_id = wp_create_nav_menu($menuname);

      // Set up default BuddyPress links and add them to the menu.
      wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' =>  __('Create a account'),
        'menu-item-classes' => 'create_a_account',
        'menu-item-url' => home_url('/freelancer/'),
        'menu-item-status' => 'publish'
      ));

      wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' =>  __('Freelancer Dashboard'),
        'menu-item-classes' => 'freelancer_dashboard',
        'menu-item-url' => home_url('/freelancer-dashboard/'),
        'menu-item-status' => 'publish'
      ));

      wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' =>  __('Login'),
        'menu-item-classes' => 'login',
        'menu-item-url' => home_url('/login/'),
        'menu-item-status' => 'publish'
      ));
      // Grab the theme locations and assign our newly-created menu
      // to the BuddyPress menu location.
      if (!has_nav_menu($bpmenulocation)) {
        $locations = get_theme_mod('nav_menu_locations');
        $locations[$bpmenulocation] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
      }
    }

    // 
  } else if ($user->roles[0] == 'Company') {

    $menuname = 'Company Menu';
    $bpmenulocation = 'company-menu';
    // Does the menu exist already?
    $menu_exists = wp_get_nav_menu_object($menuname);

    // If it doesn't exist, let's create it.
    if (!$menu_exists) {
      $menu_id = wp_create_nav_menu($menuname);

      // Set up default BuddyPress links and add them to the menu.
      wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' =>  __('Create a account'),
        'menu-item-classes' => 'create_a_account',
        'menu-item-url' => home_url('/company/'),
        'menu-item-status' => 'publish'
      ));

      wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' =>  __('Company Dashboard'),
        'menu-item-classes' => 'freelancer_dashboard',
        'menu-item-url' => home_url('/company-dashboard/'),
        'menu-item-status' => 'publish'
      ));

      wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' =>  __('Login'),
        'menu-item-classes' => 'login',
        'menu-item-url' => home_url('/login/'),
        'menu-item-status' => 'publish'
      ));
      // Grab the theme locations and assign our newly-created menu
      // to the BuddyPress menu location.
      if (!has_nav_menu($bpmenulocation)) {
        $locations = get_theme_mod('nav_menu_locations');
        $locations[$bpmenulocation] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
      }
    }
    // 
  }
}

add_action('init', 'menu_function');


// function my_wp_nav_menu_args($args = '')
// {

//   if (current_user_can('Company')) {
//     $args['menu'] = 'company-menu';
//     var_dump("dsfdsfs");
//   } else {
//     $args['menu'] = 'logged-in';
//   }
//   return $args;
// }

// add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args');
