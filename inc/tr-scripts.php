<?php

/**
 * This File includes js ad style scripts to plugin
 * 
 * @author	Rudra Innnovative Software 
 * @package	training/inc 
 * @version	1.0
 */
if (!defined("ABSPATH"))
    exit;

function rtr_wpl_scriptsstyles_function() {

    $slug = '';
    if (isset($_REQUEST['page']) && esc_attr($_REQUEST['page']) != '') {
        $slug = trim(esc_attr($_REQUEST['page']));
    }
    // for frontend action of pages
    if (empty($slug)) {
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        if (preg_match("/" . RTR_WPL_PAGE_SLUG . "/", $actual_link)) {
            $slug = "frontend_scripts";
        }
    }

    $arr = array("trainingtool", "rtr_new_course", "rtr-settings", "rtr_edit_course", "rtr_course_detail", "rtr_add_module",
        "rtr_edit_module", "rtr_module_detail", "rtr_add_lesson", "rtr_edit_lesson", "rtr_lesson_detail", "rtr_add_exercise", "rtr_resource_detail",
        "rtr_course_images", "course_admin", "rtr_progress_detail", 'rtr_pro_features', 'frontend_scripts', 'rtr-manage-authors', 'rtr-manage-categories');

    if (in_array($slug, $arr)) {
        //script files
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery.validate.js', RTR_WPL_COUNT_PLUGIN_URL . 'assets/js/jquery.validate.js', '', RTR_WPL_TT_VERSION, true);
        wp_enqueue_script('jquery.visible.min.js', RTR_WPL_COUNT_PLUGIN_URL . 'assets/js/jquery.visible.min.js');
        wp_enqueue_script('jquery.dataTables.js', RTR_WPL_COUNT_PLUGIN_URL . 'assets/js/jquery.dataTables.js', '', RTR_WPL_TT_VERSION, true);
        wp_localize_script('jquery.dataTables.js', 'trdata', array("is_admin" => intval(is_admin() == true ? 1 : 0)));
        wp_enqueue_script('script.js', RTR_WPL_COUNT_PLUGIN_URL . 'assets/js/script.js?ver=', '', RTR_WPL_TT_VERSION, true);
		 // Localize the script with the nonce
        wp_localize_script('script.js', 'rtr_script_data', array(   
            'nonce'   => wp_create_nonce('rtr_wpl_secure') // Create the nonce
        ));
        //style files
        wp_enqueue_style('style.css', RTR_WPL_COUNT_PLUGIN_URL . 'assets/css/style.css', '', RTR_WPL_TT_VERSION);

        wp_enqueue_style('jquery.dataTables.css', RTR_WPL_COUNT_PLUGIN_URL . 'assets/css/jquery.dataTables.css');
        wp_enqueue_style('font-awesome.min.css', RTR_WPL_COUNT_PLUGIN_URL . 'assets/css/font-awesome.min.css');
    }

// Hook to enqueue admin font styles
function rtr_training_admin_enqueue_styles() {
    wp_enqueue_style('rtr-training-admin-fonts', 'https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap');
}
add_action('admin_enqueue_scripts', 'rtr_training_admin_enqueue_styles');

// Hook to add custom preconnect links to the admin head
function rtr_training_admin_head() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
}
add_action('admin_head', 'rtr_training_admin_head');

// Hook to enqueue frontend font styles
function rtr_training_frontend_enqueue_styles() {
    wp_enqueue_style('rtr-training-front-fonts', 'https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap');
}
add_action('wp_enqueue_scripts', 'rtr_training_frontend_enqueue_styles');

// Hook to add custom preconnect links to the frontend head
function rtr_training_frontend_head() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
}
add_action('wp_head', 'rtr_training_frontend_head');

}

add_action('init', 'rtr_wpl_scriptsstyles_function');


