<?php
/**
 * This File for creating new lessons.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

include_once 'common.php';

$module_id = isset($_REQUEST['module_id']) ? intval($_REQUEST['module_id']) : 0;

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
?>
<div class="contaninerinner">
    <h4>Create New Lesson    
    </h4>

    <ol class="rtr-breadcrumb">
        <li class="rtr-breadcrumb-item"><a href="admin.php?page=trainingtool">Courses</a></li>
        <li class="rtr-breadcrumb-item"><a href="admin.php?page=rtr_course_detail&course_id=<?php echo $course->id; ?>"><?php echo $course->title; ?></a></li>
        <li class="rtr-breadcrumb-item"><a href="admin.php?page=rtr_module_detail&module_id=<?php echo $module->id; ?>"><?php echo $module->title; ?></a></li>
        <li class="rtr-breadcrumb-item active">Create New Lesson </li>
    </ol>



    <div class="rtr-panel rtr-panel-primary">
        <div class="rtr-pull-right"><a href="admin.php?page=rtr_module_detail&module_id=<?php echo $module->id; ?>" class="rtr-btn rtr-btn-danger bkbtn"><span class="rtr-glyphicon  " aria-hidden="true"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/back_arrow.svg" alt="Training plugin arrow image"></span>
 Back</a></div>
        <div class="rtr-panel-heading">New Lesson</div>
        <div class="rtr-panel-body">


            <form action="#" method="post" id="addlesson" name="addlesson" class="form-horizontal">

                <input type="hidden" id="course_id" name="course_id" value="<?php echo $course->id; ?>" />
                <input type="hidden" id="module_id" name="module_id" value="<?php echo $module_id; ?>" />
                <input type="hidden" id="lessid" name="lessid" value="0" />
                <div class="rtr-form-group">
                    <label for="title" class="rtr-col-lg-2 control-label">Name* :</label>
                    <div class="rtr-col-lg-8 cu-new-ex-in">
                        <input type="text" required class="rtr-form-control" id="title" name="title" placeholder="Title">
                    </div>
                </div>
                <div class="form-group" style="display: none; visibility: hidden">
                    <label for="title" class="rtr-col-lg-2 control-label">Time (Hrs) * :</label>
                    <div class="rtr-col-lg-8 cu-new-ex-in">
                        <input type="number" required class="rtr-form-control" id="hours" name="hours" placeholder="Time to complete Lesson (Hrs)">
                    </div>
                </div>
                <div class="form-group" style="display: none; visibility: hidden">
                    <label for="title" class="rtr-col-lg-2 control-label">External Link :</label>
                    <div class="rtr-col-lg-8 cu-new-ex-in">
                        <input type="text" class="rtr-form-control" id="link" name="link" placeholder="External Link">
                    </div>
                </div>

                <div class="rtr-form-group">
                    <label for="cat_name" class="rtr-col-lg-2 control-label">Description :</label>
                    <div class="rtr-col-lg-8 wpeditor">
                        <?php
                        wp_editor("", $id = 'description', $prev_id = 'title', $media_buttons = false, $tab_index = 1);
                        ?>
                    </div>
                </div>   
                <div class="rtr-form-group">
                    <label for="add_btn" class="rtr-col-lg-2 control-label"></label>
                    <div class="rtr-col-lg-8 cu-new-ex-in">
                        <button type="button" onclick="submitlesson();" class="rtr-float-right rtr-btn rtr-btn-primary" >Submit</button> 
                    </div>
                </div
            </form>
        </div>
    </div>
</div>
