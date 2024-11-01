<?php

/**
 * This File which includes plugin used constants definition
 * 
 * @author	Rudra Innnovative Software 
 * @package	training/inc 
 * @version	2.0.1
 */
if (!defined("ABSPATH"))
    exit;

if (!defined('RTR_WPL_PAGE_SLUG'))
    define('RTR_WPL_PAGE_SLUG', 'training-tool');
if (!defined('RTR_WPL_EMAIL_TYPE'))
    define('RTR_WPL_EMAIL_TYPE', 'training_tool');
if (!defined('RTR_WPL_TT_VERSION'))
    define('RTR_WPL_TT_VERSION', '2.0.1');
if (!defined('RTR_WPL_FREE_AVAIL_COURSE'))
    define('RTR_WPL_FREE_AVAIL_COURSE', 5);
if (!defined('RTR_WPL_TR_SITE_NAME'))
    define('RTR_WPL_TR_SITE_NAME', get_bloginfo('name'));
if (!defined("RTR_WPL_DEFAULT_IMAGE")) {
    $default_image = get_option('tr_def_course_image');
    if (empty($default_image)) {
        define("RTR_WPL_DEFAULT_IMAGE", RTR_WPL_COUNT_PLUGIN_URL . "/assets/images/default-course.jpg");
    } else {
        define("RTR_WPL_DEFAULT_IMAGE", $default_image);
    }


}