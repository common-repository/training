<?php
/**
 * This File which uninstalls table
 * 
 * @author	Rudra Innnovative Software 
 * @package	training/inc 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

function rtr_wpl_drop_training_tables() {
    global $wpdb;
    $wpdb->query("DROP TABLE IF EXISTS " . rtr_wpl_tr_courses());
    $wpdb->query("DROP TABLE IF EXISTS " . rtr_wpl_tr_resources());
    $wpdb->query("DROP TABLE IF EXISTS " . rtr_wpl_tr_lesson_notes());
    $wpdb->query("DROP TABLE IF EXISTS " . rtr_wpl_tr_lessons());
    $wpdb->query("DROP TABLE IF EXISTS " . rtr_wpl_tr_projects());
    $wpdb->query("DROP TABLE IF EXISTS " . rtr_wpl_tr_project_exercise());
    $wpdb->query("DROP TABLE IF EXISTS " . rtr_wpl_tr_modules());
    $wpdb->query("DROP TABLE IF EXISTS " . rtr_wpl_tr_media());
    $wpdb->query("DROP TABLE IF EXISTS " . rtr_wpl_tr_enrollment());
    $wpdb->query("DROP TABLE IF EXISTS " . rtr_wpl_tr_resource_status());
    $wpdb->query("DROP TABLE IF EXISTS " . rtr_wpl_tr_setting());
    $wpdb->query("DROP TABLE IF EXISTS " . rtr_wpl_tr_email_templates());
    $wpdb->query("DROP TABLE IF EXISTS " . rtr_wpl_tr_authors());
    $wpdb->query("DROP TABLE IF EXISTS " . rtr_wpl_tr_categories());

   $wp_rewrite = new WP_Rewrite();
   $the_page_id = get_option( "trainingtoolpage" );
   if( $the_page_id ) {
       wp_delete_post( $the_page_id, true );
    }
}
