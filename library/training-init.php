<?php

/**
 * This File which tells assigned courses to each user.
 * @author	Rudra Innnovative Software 
 * @package	training/library 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

global $current_user;
global $wpdb;
$current_user = wp_get_current_user();
$user_id = isset($current_user->data->ID) ? $current_user->data->ID : 0;

function get_course_info($wpdb, $course_id) {
    /* Get Course data via course id */
    $course_data = $wpdb->get_row(
            $wpdb->prepare(
                    "SELECT * from " . rtr_wpl_tr_courses() . " WHERE id = %d", $course_id
            )
    );
    return $course_data;
}

function total_courses_enrolled($wpdb, $user_id) {

    $total_courses = $wpdb->get_results
            (
            $wpdb->prepare
                    (
                    "select distinct course_id FROM " . rtr_wpl_tr_resource_status() . " WHERE user_id = %d", $user_id
            )
    );
    return count($total_courses);
}

//function get_author_info($author_id) {
//    global $wpdb;
//    $author_details = $wpdb->get_row(
//            $wpdb->prepare(
//                    "SELECT * from " . rtr_wpl_tr_authors() . " WHERE id = %d", $author_id
//            ), ARRAY_A
//    );
//    if (!empty($author_details)) {
//        return $author_details;
//    }
//    return '';
//}
