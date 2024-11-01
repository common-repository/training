<?php
/**
 * This File check authenticate user.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

// $pusers = explode(",", $course->user_ids);
$userlog = new WP_User($user_id);
$ulog_role = isset($userlog->roles[0]) ? $userlog->roles[0] : 0;

$is_enrolled = $wpdb->get_var
        (
        $wpdb->prepare
                (
                "SELECT count(id) as enrolled FROM " . rtr_wpl_tr_enrollment() . " WHERE course_id = %d AND user_id = %d", $course_id, $user_id
        )
);

function auto_enroll_user($course_id, $user_id) {
    global $wpdb;
    $now = date("Y-m-d H:i:s");
    $is_enrolled = $wpdb->get_var
            (
            $wpdb->prepare
                    (
                    "SELECT count(id) as enrolled FROM " . rtr_wpl_tr_enrollment() . " WHERE course_id = %d AND user_id = %d", $course_id, $user_id
            )
    );

    if ($is_enrolled == 0) {
        $now = date("Y-m-d H:i:s");
        $wpdb->query
                (
                $wpdb->prepare
                        (
                        "INSERT INTO " . rtr_wpl_tr_enrollment() . " (course_id, user_id, created_dt) "
                        . "VALUES (%d, %d, '%s')", $course_id, $user_id, $now
                )
        );
        $is_enrolled = 1;
    }
    return $is_enrolled;
}

?>
