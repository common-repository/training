<?php
/**
 * This File for editing resources.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

include_once 'common.php';
$lesson_id = isset($_REQUEST['lesson_id']) ? intval($_REQUEST['lesson_id']) : 0;

$lesson = $wpdb->get_row
        (
        $wpdb->prepare
                (
                "SELECT * FROM " . rtr_wpl_tr_lessons() . " WHERE id = %d", $lesson_id
        )
);
if (empty($lesson)) {
    die('Invalid Lesson');
}
$module_id = $lesson->module_id;

$module = $wpdb->get_row
        (
        $wpdb->prepare
                (
                "SELECT * FROM " . rtr_wpl_tr_modules() . " WHERE id = %d", $module_id
        )
);
if (empty($module)) {
    die('Invalid Module');
}

$course_id = $module->course_id;

$course = $wpdb->get_row
        (
        $wpdb->prepare
                (
                "SELECT * FROM " . rtr_wpl_tr_courses() . " WHERE id = %d", $course_id
        )
);
if (empty($course)) {
    die('Invalid Course');
}


$lesson_id = isset($_REQUEST['lesson_id']) ? intval($_REQUEST['lesson_id']) : 0;
?>
<div class="contaninerinner">


    <h4>Edit Lesson    
    </h4>
    <div class="bread_crumb">
        <ul>
            <li>
                <a href="admin.php?page=trainingtool">All Courses</a> >>
            </li>
            <li>
                <a href="admin.php?page=rtr_course_detail&course_id=<?php echo $course->id; ?>"><?php echo $course->title; ?></a> >>
            </li>
            <li>
                <a href="admin.php?page=rtr_module_detail&module_id=<?php echo $module->id; ?>"><?php echo $module->title; ?></a> >>
            </li>
            <li>
                <?php echo $lesson->title; ?>
            </li>
        </ul>
    </div>

    <div class="rtr-panel rtr-panel-primary">
        <div class="rtr-pull-right"><a href="admin.php?page=rtr_module_detail&module_id=<?php echo $module->id; ?>" class="rtr-btn rtr-btn-danger bkbtn"><span class="rtr-glyphicon  " aria-hidden="true"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/back_arrow.svg" alt="Training plugin arrow image"></span>
 Back</a></div>
        <div class="rtr-panel-heading">Edit Lesson - <?php echo $lesson->title; ?></div>
        <div class="rtr-panel-body">

            <form action="#" method="post" id="addlesson" name="addlesson" class="form-horizontal">

                <input type="hidden" id="course_id" name="course_id" value="<?php echo $course->id; ?>" />
                <input type="hidden" id="module_id" name="module_id" value="<?php echo $module_id; ?>" />
                <input type="hidden" id="lessid" name="lessid" value="<?php echo $lesson_id; ?>" />
                <div class="rtr-form-group">
                    <label for="title" class="rtr-col-lg-2 control-label">Name* :</label>
                    <div class="rtr-col-lg-8 cu-new-ex-in">
                        <input type="text" value="<?php echo $lesson->title; ?>" required class="rtr-form-control" id="title" name="title" placeholder="Title">
                    </div>
                </div>
                <div class="form-group" style="display: none;">
                    <label for="title" class="rtr-col-lg-2 control-label">Time (Hrs) * :</label>
                    <div class="rtr-col-lg-8 cu-new-ex-in">
                        <input type="number" required class="rtr-form-control" id="hours" name="hours" placeholder="Time to complete Lesson (Hrs)">
                    </div>
                </div>
                <div class="rtr-form-group">
                    <label for="title" class="rtr-col-lg-2 control-label">External Link :</label>
                    <div class="rtr-col-lg-8 cu-new-ex-in">
                        <input type="text" value="<?php echo $lesson->external_link; ?>" class="rtr-form-control" id="link" name="link" placeholder="External Link">
                    </div>
                </div>

                <div class="rtr-form-group">
                    <label for="cat_name" class="rtr-col-lg-2 control-label">Description :</label>
                    <div class="rtr-col-lg-8 wpeditor">
                        <?php
                        wp_editor(html_entity_decode($lesson->description), $id = 'description', $prev_id = 'title', $media_buttons = false, $tab_index = 1);
                        ?>
                    </div>
                </div>   
                <div class="rtr-form-group">
                    <label for="add_btn" class="rtr-col-lg-2 control-label"></label>
                    <div class="rtr-col-lg-8">
                        <button type="button" onclick="submitlesson();" class="rtr-btn rtr-btn-primary" >Update</button> 
                    </div>
                </div
            </form>

        </div>
    </div>
</div>
