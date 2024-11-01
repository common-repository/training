<?php
/**
 * This File for module details.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

include_once 'common.php';
global $wpdb;

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
$course = $wpdb->get_row
        (
        $wpdb->prepare
                (
                "SELECT * FROM " . rtr_wpl_tr_courses() . " WHERE id = (SELECT course_id FROM " . rtr_wpl_tr_modules() . " WHERE id = %d)", $module_id
        )
);

$lessons = $wpdb->get_results
        (
        $wpdb->prepare
                (
                "SELECT * FROM " . rtr_wpl_tr_lessons() . " WHERE module_id = %d ORDER BY ord ASC", $module_id
        )
);
?>
<div class="contaninerinner">           
    <h4>Manage Module - <?php echo $module->title; ?></h4>
    <ol class="rtr-breadcrumb">
        <li class="rtr-breadcrumb-item"><a href="admin.php?page=trainingtool">Courses</a></li>
        <li class="rtr-breadcrumb-item"><a href="admin.php?page=rtr_course_detail&course_id=<?php echo $course->id; ?>"><?php echo $course->title; ?></a></li>
        <li class="rtr-breadcrumb-item active"><?php echo $module->title; ?> </li>
    </ol>

    <div class="rtr-panel rtr-panel-primary">
        <div class="rtr-pull-right">    
            <a class="rtr-btn rtr-btn-default" href="admin.php?page=rtr_add_exercise&module_id=<?php echo $module_id; ?>">Add New Exercise</a>
            <a class="rtr-btn rtr-btn-warning movelesson" href="javascript:;" data-type="lessons" data-id="<?php echo $module_id; ?>">Move Lessons</a>
            <a class="rtr-btn rtr-btn-warning reorder" href="javascript:;" data-type="lessons" data-id="<?php echo $module_id; ?>">Re-Order Lessons</a>
            <a class="rtr-btn rtr-btn-success" href="admin.php?page=rtr_add_lesson&module_id=<?php echo $module_id; ?>">Create New Lesson</a>            
            <a href="admin.php?page=rtr_course_detail&course_id=<?php echo $course->id; ?>" class="rtr-btn rtr-btn-danger"><span class="rtr-glyphicon  " aria-hidden="true"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/back_arrow.svg" alt="Training plugin arrow image"></span>
 Back</a>
        </div>
        <div class="rtr-panel-heading">List Of Lessons</div>
        <div class="rtr-panel-body">

            <table class="rtr-table rtr-table-bordered" id="data_lessons" >
                <thead>
                    <tr>
                        <th >SNo</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Information</th>
                        <th>Date</th>										
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($lessons as $lesson) {
                        $title = "<a href='admin.php?page=rtr_lesson_detail&lesson_id=$lesson->id'>$lesson->title</a>";
                        ?>
                        <tr class="rowmod" data-id="<?php echo $lesson->id; ?>">
                            <td><?php echo $lesson->ord; ?></td> 
                            <td class="title" data-txt="<?php echo $lesson->title; ?>" data-lnk="<?php echo $lesson->external_link; ?>" ><?php echo $title; ?></td>
                            <td class="text" >
                                <div style="display: none; visibility: hidden" class="textdiv"><?php echo html_entity_decode($lesson->description); ?></div>
                                <?php echo limit_text(html_entity_decode($lesson->description), 10, false); ?>
                            </td>

                            <td>
                                <div class="infospan">                                       
                                    <div>Total Hours: <?php echo $lesson->total_hrs; ?></div>
                                    <div>Total Exercises: <?php echo $lesson->total_resources; ?></div>

                                </div>

                            </td>   

                            <td><?php echo date("Y-m-d", strtotime($lesson->created_dt)); ?></td>                            
                            <td class="actiontd">
                                <a class="rtr-btn rtr-btn-primary" href="admin.php?page=rtr_edit_lesson&lesson_id=<?php echo $lesson->id; ?>" title="Edit Lesson"><span class="rtr-glyphicon"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/edit.svg" alt="Training course edit image">
</span></a>                                    

                                <a data-id="<?php echo $lesson->id; ?>" class="rtr-btn rtr-btn-success" href="admin.php?page=rtr_lesson_detail&lesson_id=<?php echo $lesson->id; ?>" title="Manage Lesson"><span class="rtr-glyphicon   "><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/manage.svg" alt="Training course manage image"></span></a>

                                <a href="javascript:;" data-id="<?php echo $lesson->id; ?>" class="deleteless rtr-btn rtr-btn-danger" title="Delete Lesson"><span class="rtr-glyphicon"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/trash.svg" alt="Training course trash image"></span></a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>

        </div>

    </div>
</div>


<div id="lesson_dialog" class="rtr-modal rtr-bs-modal rtr-fade">
    <div class="rtr-modal-dialog rtr-modal-lg">
        <div class="rtr-modal-content">
            <div class="rtr-modal-header">
                <button type="button" class="rtr-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title rtr-fs-4 rtr-m-0">Lesson</h4>
            </div>
            <div class="rtr-modal-body">

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
                    <div class="form-group" style="display: none;">
                        <label for="title" class="rtr-col-lg-2 control-label">Time (Hrs) * :</label>
                        <div class="rtr-col-lg-8 cu-new-ex-in">
                            <input type="number" required class="rtr-form-control" id="hours" name="hours" placeholder="Time to complete Lesson (Hrs)">
                        </div>
                    </div>
                    <div class="rtr-form-group">
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
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" onclick="submitlesson();" class="rtr-btn rtr-btn-primary" >Submit</button>
                <button type="button" data-dismiss="modal" class="rtr-btn">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div id="movemodal" class="rtr-modal rtr-bs-modal rtr-fade">
    <div class="rtr-modal-dialog rtr-modal-lg">
        <div class="rtr-modal-content">
            <div class="rtr-modal-header">
                <button type="button" class="rtr-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="rtr-modal-title reordertitl">Move Lessons </h4>
            </div>
            <div class="rtr-modal-body">                
                <form action="#" method="post" id="moverows" name="moverows" class="form-horizontal">
                    <div class="loadergif">
                        <img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL; ?>/assets/css/images/loading.gif" />
                    </div>                
                </form>
            </div>
            <div class="rtr-modal-footer">
                <button type="button" class="rtr-btn rtr-btn-primary movesave" >Move Selected Lessons</button>                
                <button type="button" data-dismiss="modal" class="rtr-btn rtr-movemodal-cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>
