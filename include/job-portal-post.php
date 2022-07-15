<?php
// Jobs post type 
function Jobs_post()
{

    register_post_type(
        'Jobs',
        // CPT Options
        array(
            'labels' => array(
                'name' => __('Jobs'),
                'singular_name' => __('Job')
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'jobs'),
            'show_in_rest' => true,

        )
    );
    $labels = array(
        'name' => _x('Skills', 'taxonomy general name'),
        'singular_name' => _x('Skills', 'taxonomy singular name'),
        'search_items' =>  __('Search Skill'),
        'all_items' => __('All Skills'),
        'parent_item' => __('Parent Skill'),
        'parent_item_colon' => __('Parent Skill:'),
        'edit_item' => __('Edit Skill'),
        'update_item' => __('Update Skill'),
        'add_new_item' => __('Add New Skill'),
        'new_item_name' => __('New Skill Name'),
        'menu_name' => __('Skills'),
    );

    // Now register the taxonomy
    register_taxonomy('skills', array('jobs'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_in_rest' => false,
        'show_admin_column' => true,
        'meta_box_cb' => false,
        'query_var' => true,
        'rewrite' => array('slug' => 'skills'),
    ));


    //Technology
    function add_technology_box()
    {
        $techs = ['jobs', 'tech_cpt'];
        foreach ($techs as $tech) {
            add_meta_box(
                'tech_box_id',
                'Skills',
                'technology_html',
                $tech
            );
        }
    }
    add_action('add_meta_boxes', 'add_technology_box');

    function technology_html($post)
    {
        $terms = get_the_terms($post->ID, 'skills');

        $slt_technology[] = "";
        if ($terms != "") {
            foreach ($terms as $term) {
                $slt_technology[] =  $term->name;
            }
        }


?>
        <select name="wporg_field[]" id="wporg_field" class="form-control wporg_field select2 select2-hidden-accessible" multiple="" data-placeholder="Select a Skill" style="width: 100%;" tabindex="-1" aria-hidden="true">
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

    <?php

    }
    function wporg_save_postdata($post_id)
    {
        // if (array_key_exists('wporg_field', $_POST)) {
        //     // update_post_meta(
        //     //     $post_id,
        //     //     'skills',
        //     //     $_POST['wporg_field']
        //     // );

        // }
        wp_set_object_terms($post_id, $_POST['wporg_field'], 'skills');
    }
    add_action('save_post', 'wporg_save_postdata');





    //Job Type

    function add_job__box()
    {
        $techs = ['jobs', 'type_cpt'];
        foreach ($techs as $tech) {
            add_meta_box(
                'type_box_id',
                'Job Type',
                'job_type_html',
                $tech
            );
        }
    }
    add_action('add_meta_boxes', 'add_job__box');

    function job_type_html($post)
    {
        $value = get_post_meta($post->ID, 'job_type', true);
    ?>
        <select name="job_type_id" id="job_type_id" class="postbox">
            <option value="">Select Job Type</option>
            <option value="Hourly" <?php selected($value, 'Hourly'); ?>>Hourly</option>
            <option value="Fixed" <?php selected($value, 'Fixed'); ?>>Fixed</option>
        </select>
<?php
    }
    function job_type_save_postdata($post_id)
    {
        if (array_key_exists('job_type_id', $_POST)) {
            update_post_meta(
                $post_id,
                'job_type',
                $_POST['job_type_id']
            );
        }
    }
    add_action('save_post', 'job_type_save_postdata');
}
add_action('init', 'Jobs_post');

// // User Role
function wps_add_role()
{
    add_role(
        'Freelancer',
        __('Freelancer'),
        array(
            'read'         => true,
            'edit_posts'   => false,
        )
    );
    add_role(
        'Company',
        __('Company'),
        array(
            'read'         => true,
            'edit_posts'   => true,
            'delete_posts'   => true,
        )
    );
}

add_action('init', 'wps_add_role');
?>