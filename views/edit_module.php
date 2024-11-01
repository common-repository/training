<?php
/**
 * This File for editing modules.
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
?>
<div class="contaninerinner">


    <h4>Edit Module</h4>
    <ol class="rtr-breadcrumb">
        <li class="rtr-breadcrumb-item"><a href="admin.php?page=trainingtool">Courses</a></li>
        <li class="rtr-breadcrumb-item"><a href="admin.php?page=rtr_course_detail&course_id=<?php echo $course->id; ?>"><?php echo $course->title; ?></a></li>
        <li class="rtr-breadcrumb-item active"><?php echo $module->title; ?></li>
    </ol>
    

    <div class="rtr-panel rtr-panel-primary">
        <div class="rtr-pull-right"><a href="admin.php?page=rtr_course_detail&course_id=<?php echo $course->id; ?>" class="rtr-btn rtr-btn-danger bkbtn"><span class="rtr-glyphicon  " aria-hidden="true"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/back_arrow.svg" alt="Training plugin arrow image"></span>
 Back</a></div>
        <div class="rtr-panel-heading">Edit Module11 - <?php echo $module->title; ?></div>
        <div class="rtr-panel-body">
            <form action="#" method="post" id="addmodules" name="addmodules" class="form-horizontal">

                <input type="hidden" id="course_id" name="course_id" value="<?php echo $course_id; ?>" />
                <input type="hidden" id="id" name="id" value="<?php echo $module_id; ?>" />
                <div class="rtr-form-group">
                    <label for="title" class="rtr-col-lg-2 control-label">Name* :</label>
                    <div class="rtr-col-lg-8 cu-new-ex-in">
                        <input type="text" required value="<?php echo $module->title; ?>" class="rtr-form-control" id="title" name="title" placeholder="Title">
                    </div>
                </div>
                <div class="rtr-form-group">
                    <label for="title" class="rtr-col-lg-2 control-label">External Link :</label>
                    <div class="rtr-col-lg-8 cu-new-ex-in">
                        <input type="text" url='true' value="<?php echo $module->external_link; ?>" class="rtr-form-control" id="link" name="link" placeholder="External Link">
                    </div>
                </div>

                <div class="rtr-form-group">
                    <label for="cat_name" class="rtr-col-lg-2 control-label">Description :</label>
                    <div class="rtr-col-lg-8 wpeditor">
                        <?php
                        wp_editor(html_entity_decode($module->description), $id = 'description', $prev_id = 'title', $media_buttons = false, $tab_index = 1);
                        ?>
                    </div>
                </div>
                <div class="rtr-form-group">
                    <label for="add_btn" class="rtr-col-lg-2 control-label"></label>
                    <div class="rtr-col-lg-8">
                        <button type="button" onclick="submitmodle();" class="rtr-float-right rtr-btn rtr-btn-primary" >Update</button> 
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
