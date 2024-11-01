<?php
/*
 * Plugin Name: Training
 * Plugin URI: https://www.rudrainnovative.com
 * Description: Training is a comprehensive Learning management system Plugin for WordPress. This Training Plugin can be used to easily create courses. Each course curriculum can be made with modules, lessons and exercises which can be managed by anyone.
 * Author: Rudra Innnovative Software
 * Author URI: http://www.rudrainnovative.com
 * Version: 2.0.1
 * License: GPLv3
 * Text Domain: training
 */
if (!defined("ABSPATH"))
    exit;

/* Variables used in Plugin */
if (!defined('RTR_WPL_COUNT_PLUGIN_DIR'))
    define('RTR_WPL_COUNT_PLUGIN_DIR', plugin_dir_path(__FILE__));
if (!defined('RTR_WPL_COUNT_PLUGIN_URL'))
    define('RTR_WPL_COUNT_PLUGIN_URL', plugins_url() . "/training/");
/*
 * @Training File which includes plugin used constants definition
 */
include_once RTR_WPL_COUNT_PLUGIN_DIR . 'inc/tr-constants.php';
/*
 * @Training File which installs table
 */
include_once RTR_WPL_COUNT_PLUGIN_DIR . 'inc/tr-db-install-scripts.php';
register_activation_hook(__FILE__, 'rtr_wpl_install_table');
/*
 * @Training File attach menu to plugin
 */
include_once RTR_WPL_COUNT_PLUGIN_DIR . 'inc/tr-menu.php';
add_action('admin_menu', 'rtr_wpl_menus');
/*
 * @Training File gives the definition of all functions of this plugin
 */
include_once RTR_WPL_COUNT_PLUGIN_DIR . 'inc/tr-pages.php';
/*
 * @Training File includes js ad style scripts to plugin
 */
include_once RTR_WPL_COUNT_PLUGIN_DIR . 'inc/tr-scripts.php';
/*
 * @Training File includes plugin's functions file
 */
include_once RTR_WPL_COUNT_PLUGIN_DIR . 'inc/tr-functions.php';
register_activation_hook(__FILE__, 'rtr_wpl_create_pages');


add_action('wp_head', 'rtr_notification_timeout_fun', true);

add_action('admin_head', 'rtr_notification_timeout_fun', true);
function rtr_notification_timeout_fun()
{
    global $wpdb;
    $notification_timeout = 2;
    $notification_timeout_ishow = 1;
    $isLesson_detailPage = 0;

    $toplinks = $wpdb->get_results
    (             
      "SELECT * FROM " . rtr_wpl_tr_setting() . " WHERE type = 'setting' AND keyname = 'Notification Timeout (Seconds)' AND is_show = 1"
   
    );

    if (isset($_GET['lesson_detail'])) {
        $isLesson_detailPage    = 1;
    }
    if(count($toplinks) > 0 && isset($toplinks[0])){
    if ($toplinks[0]->is_show != 1) {
        $notification_timeout = 0;
    } else {
        $notification_timeout = $toplinks[0]->keyvalue;
    }
}
else{
    $notification_timeout = 0;
}

    echo "<script>
     var notification_timeout = ($notification_timeout *1000) ;
     var notification_timeout_ishow =$notification_timeout_ishow ;
     var isLesson_detailPage =$isLesson_detailPage ;
     
     </script>";
}