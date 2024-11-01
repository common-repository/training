<?php
/**
 * This File gives the definition of all functions of this plugin
 * 
 * @author	Rudra Innnovative Software 
 * @package	training/inc 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

function rtr_wpl_menus() {
    global $wpdb;
    $c_id = get_current_user_id();
    $user = new WP_User($c_id);
    $u_role = $user->roles[0];

    if ($u_role == 'administrator') {

        add_menu_page('Training', 'Training', $u_role, 'trainingtool', 'rtr_wpl_trainingtool', 'dashicons-welcome-learn-more');
        add_submenu_page('trainingtool', 'trainingtool', 'Courses', $u_role, 'trainingtool', 'rtr_wpl_trainingtool');
        add_submenu_page('trainingtool', 'trainingtool', 'New Course', $u_role, 'rtr_new_course', 'rtr_wpl_new_course');
        add_submenu_page('trainingtool', 'trainingtool', 'Manage Authors', $u_role, 'rtr-manage-authors', 'rtr_wpl_manage_author');
        add_submenu_page('trainingtool', 'trainingtool', 'Settings', $u_role, 'rtr-settings', 'rtr_wpl_settings');
        add_submenu_page('trainingtool', 'trainingtool', 'Image By Course', $u_role, 'rtr_course_images', 'rtr_wpl_course_images');
        add_submenu_page('trainingtool', 'trainingtool', 'Add Category', $u_role, 'rtr-manage-categories', 'rtr_wpl_manage_categories');
        add_submenu_page('trainingtool', 'trainingtool', 'Course Progress', $u_role, 'rtr_progress_detail', 'rtr_wpl_progress_detail');
        add_submenu_page('trainingtool', 'trainingtool', 'Pro Features', $u_role, 'rtr_pro_features', 'rtr_wpl_pro_features');
        add_submenu_page('trainingtool', '', '', $u_role, 'rtr_edit_course', 'rtr_wpl_edit_course');
        add_submenu_page('trainingtool', '', '', $u_role, 'rtr_course_detail', 'rtr_wpl_course_detail');
        add_submenu_page('trainingtool', '', '', $u_role, 'rtr_module_detail', 'rtr_wpl_module_detail');
        add_submenu_page('trainingtool', '', '', $u_role, 'rtr_lesson_detail', 'rtr_wpl_lesson_detail');
        add_submenu_page('trainingtool', '', '', $u_role, 'rtr_progress_detail', 'rtr_wpl_progress_detail');
        add_submenu_page('trainingtool', '', '', $u_role, 'rtr_add_module', 'rtr_wpl_add_module');
        add_submenu_page('trainingtool', '', '', $u_role, 'rtr_edit_module', 'rtr_wpl_edit_module');
        add_submenu_page('trainingtool', '', '', $u_role, 'rtr_add_lesson', 'rtr_wpl_add_lesson');
        add_submenu_page('trainingtool', '', '', $u_role, 'rtr_edit_lesson', 'rtr_wpl_edit_lesson');
        add_submenu_page('trainingtool', '', '', $u_role, 'rtr_add_exercise', 'rtr_wpl_add_exercise');
        add_submenu_page('trainingtool', '', '', $u_role, 'rtr_resource_detail', 'rtr_wpl_resource_detail');
    }
}
