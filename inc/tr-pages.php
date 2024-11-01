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

function rtr_wpl_trainingtool() {
    global $wpdb;
    include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/courses-list.php';
    echo'</div>';
}

function rtr_wpl_new_course() {
    global $wpdb;
    include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/new_course.php';
    echo'</div>';
}

function rtr_wpl_manage_categories() {
    global $wpdb;
    include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/manage-category.php';
    echo'</div>';
}

function rtr_wpl_edit_course() {
    global $wpdb;
    include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/edit_course.php';
    echo'</div>';
}

function rtr_wpl_course_detail() {
    global $wpdb;
    include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/back_course_detail.php';
    echo'</div>';
}

function rtr_wpl_module_detail() {
    global $wpdb;
    include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/module_detail.php';
    echo'</div>';
}

function rtr_wpl_lesson_detail() {
    global $wpdb;
    include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/lesson_detail.php';
    echo'</div>';
}

function rtr_wpl_settings() {
    global $wpdb;
    include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/settings.php';
    echo'</div>';
}

function rtr_wpl_add_module() {
    global $wpdb;
    include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/add_module.php';
    echo'</div>';
}

function rtr_wpl_edit_module() {
    global $wpdb;
    include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/edit_module.php';
    echo'</div>';
}

function rtr_wpl_add_lesson() {
    global $wpdb;
    include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/add_lesson.php';
    echo'</div>';
}

function rtr_wpl_edit_lesson() {
    global $wpdb;
    include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/edit_lesson.php';
    echo'</div>';
}

function rtr_wpl_add_exercise() {
    global $wpdb;
    include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/add_resource.php';
    echo'</div>';
}

function rtr_wpl_resource_detail() {
    global $wpdb;
    include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/resource_detail.php';
    echo'</div>';
}

function rtr_wpl_course_images() {
    global $wpdb;
    include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/course_images.php';
    echo'</div>';
}

function rtr_wpl_progress_detail() {
    global $wpdb;
    include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/progress_detail.php';
    echo'</div>';
}

function rtr_wpl_pro_features() {
    global $wpdb;
    include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/pro_features.php';
    echo'</div>';
}

function rtr_wpl_manage_author() {
    global $wpdb;
    include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/manage_author.php';
    echo'</div>';
}
