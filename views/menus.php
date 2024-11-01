<?php
/**
 * This File for plugin subMenu.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

$uu_id = get_current_user_id();
$userlog = new WP_User($uu_id);
$ulog_role = $userlog->roles[0];

$couse_tab_active_cases = array('rtr_course_images', 'rtr_add_lesson', 'rtr_new_course', 'rtr_add_exercise', 'rtr_module_detail', 'rtr_add_module', 'rtr_edit_module', 'trainingtool', 'rtr_edit_course', 'rtr_course_detail', 'lesson_detail', 'rtr_resource_detail', 'rtr_edit_lesson');
$page_data = isset($_GET['page']) ? esc_attr($_GET['page']) : '';

$authors_array = array("rtr-manage-authors", "add-author");

if ($ulog_role == 'administrator') {
    ?>
    <div class="rtrp-training-sec rtr-pe-3">
        <div class="">
            <div class="">
                <ul class="rtr-nav rtr-nav-tabs rtr-tabstop rtr-d-flex">
                    <li class="<?php echo in_array($page_data, $couse_tab_active_cases) ? 'active' : ''; ?>"><a
                            href="admin.php?page=trainingtool">Courses</a></li>
                    <li class="<?php echo in_array($page_data, $authors_array) ? 'active' : ''; ?>"><a
                            href="admin.php?page=rtr-manage-authors">Manage Authors</a></li>
                    <li
                        class="<?php echo (isset($_GET['page']) && esc_attr($_GET['page']) == 'rtr-manage-categories') ? 'active' : ('' ? 'active' : ''); ?>">
                        <a href="admin.php?page=rtr-manage-categories">Manage Category</a>
                    </li>
                    <li
                        class="<?php echo (isset($_GET['page']) && esc_attr($_GET['page']) == 'rtr-settings') ? 'active' : ('' ? 'active' : ''); ?>">
                        <a href="admin.php?page=rtr-settings">Settings</a>
                    </li>
                    <li
                        class="<?php echo isset($_GET['page']) && esc_attr($_GET['page']) == 'rtr_progress_detail' ? 'active' : ''; ?>">
                        <a href="admin.php?page=rtr_progress_detail">Course Progress</a>
                    </li>
                </ul>
            </div>
        </div>
        <?php
}
?>