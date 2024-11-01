<?php
/**
 * This File includes plugin's functions file
 * 
 * @author	Rudra Innnovative Software 
 * @package	training/inc 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

if (isset($_REQUEST['action'])) {
    switch (esc_attr($_REQUEST['action'])) {
        case "training_lib":
			  

            add_action('admin_init', 'rtr_wpl_training_lib');

            function rtr_wpl_training_lib()
            {
                global $wpdb;
				  $nonce = isset($_REQUEST['nonce']) ? $_REQUEST['nonce'] : '';    
				if (!wp_verify_nonce($nonce, 'rtr_wpl_secure')) {
					wp_send_json_error(array('message' => 'Nonce verification failed.'));
					wp_die(); // Always call wp_die() after an AJAX request
				}
                include_once RTR_WPL_COUNT_PLUGIN_DIR . '/library/training-lib.php';
            }

            break;
    }
}

function rtr_wpl_all_courses_shortcode()
{
    include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/all_courses.php';
}

add_shortcode('all_courses', 'rtr_wpl_all_courses_shortcode');

function rtr_wpl_course_detail_shortcode()
{
    include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/course_detail.php';
}

add_shortcode('course_detail', 'rtr_wpl_course_detail_shortcode');

add_filter('page_template', 'rtr_wpl_training_page_template_tool');

function rtr_wpl_training_page_template_tool($page_template)
{
    global $post;
    $post_slug = $post->post_name;
    if (rtr_wpl_the_slug_exists($post_slug) && $post_slug == 'training-tool') {
        $page_template = RTR_WPL_COUNT_PLUGIN_DIR . '/views/front-trining-template.php';
    }
    return $page_template;
}

function rtr_wpl_the_slug_exists($post_name)
{
    global $wpdb;
    $posts = $wpdb->prefix . "posts";
    $sql = "SELECT post_name FROM $posts WHERE post_name = '" . $post_name . "'";
    if ($wpdb->get_row($sql, 'ARRAY_A')) {
        return true;
    } else {
        return false;
    }
}

function rtr_wpl_create_pages()
{
    global $wpdb;
    $wp_rewrite = new WP_Rewrite();

    $slug = RTR_WPL_PAGE_SLUG;

    if (!rtr_wpl_the_slug_exists($slug)) {
        $_p = array();
        $_p['post_title'] = "Training Tool";
        $_p['post_content'] = "[course_detail]";
        $_p['post_status'] = 'publish';
        $_p['post_slug'] = $slug;
        $_p['post_type'] = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status'] = 'closed';
        $newvalue = wp_insert_post($_p);
        update_option('trainingtoolpage', $newvalue);
    }
}

// add a link to the WP Toolbar
function rtr_wpl_custom_toolbar_link($wp_admin_bar)
{

    $args = array(
        'id' => 'rtr-training',
        'title' => 'Training',
        'href' => "javascript:;",
        'meta' => array(
            'class' => 'rtr-training',
            'title' => 'Welcome to Training Courses',
        )
    );
    $wp_admin_bar->add_node($args);

    // Add the first child link 

    $args = array(
        'id' => 'rtr-course',
        'title' => 'Create Course',
        'href' => site_url() . '/wp-admin/admin.php?page=rtr_new_course',
        'parent' => 'rtr-training',
        'meta' => array(
            'class' => 'rtr-course',
            'title' => 'Navigate to Create Course Page'
        )
    );
    $wp_admin_bar->add_node($args);

    // Adding Settings Tab 

    $args = array(
        'id' => 'rtr-settings',
        'title' => 'Training Settings',
        'href' => site_url() . '/wp-admin/admin.php?page=rtr-settings',
        'parent' => 'rtr-training',
        'meta' => array(
            'class' => 'rtr-settings',
            'title' => 'Training Settings'
        )
    );
    $wp_admin_bar->add_node($args);

    $args = array(
        'id' => 'rtr-training-ui',
        'title' => 'Navigate to Training',
        'href' => site_url() . '/training-tool',
        'parent' => 'rtr-training',
        'meta' => array(
            'class' => 'rtr-training-ui',
            'title' => 'Navigate to Training',
            'target' => "_blank"
        )
    );
    $wp_admin_bar->add_node($args);
}

add_action('admin_bar_menu', 'rtr_wpl_custom_toolbar_link', 999);