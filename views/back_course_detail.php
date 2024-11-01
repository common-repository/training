<?php
/**
 * This File for back to course details page.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

include_once 'common.php';
global $wpdb;

$course_id = isset($_REQUEST['course_id']) ? intval($_REQUEST['course_id']) : 0;

$course = $wpdb->get_row
(
    $wpdb->prepare
    (
        "SELECT * FROM " . rtr_wpl_tr_courses() . " WHERE id = %d",
        $course_id
    )
);
if (empty($course)) {
    die('Invalid Course');
}
$modules = $wpdb->get_results
(
    $wpdb->prepare
    (
        "SELECT * FROM " . rtr_wpl_tr_modules() . " WHERE course_id = %d ORDER BY ord ASC",
        $course_id
    )
);

if (isset($_REQUEST['show'])) {
    // code for pro version
} else {
    ?>
    <div class="contaninerinner">

        <h4>Manage Course - <?php echo $course->title; ?></h4>
        <ol class="rtr-breadcrumb">
            <li class="rtr-breadcrumb-item"><a href="admin.php?page=trainingtool">Courses</a></li>
            <li class="rtr-breadcrumb-item active"><?php echo $course->title; ?></li>
        </ol>

        <div class="rtr-panel rtr-panel-primary">
            <div class="rtr-pull-right">
                <a class="rtr-btn rtr-btn-success"
                    href="admin.php?page=rtr_add_module&course_id=<?php echo $course_id; ?>">Create New Module</a>
                <a class="rtr-btn rtr-btn-warning reorder" href="javascript:;" data-type="modules"
                    data-id="<?php echo $course_id; ?>">Re-Order Modules</a>
                <a href="admin.php?page=trainingtool" class="rtr-btn rtr-btn-danger"><span
                        class="rtr-glyphicon  " aria-hidden="true"><img
                            src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/back_arrow.svg"
                            alt="Training plugin arrow image"></span>
                    Back</a>
            </div>
            <div class="rtr-panel-heading">List Of Modules</div>
            <div class="rtr-panel-body">
                <table class="rtr-table rtr-table-bordered" id="data_modules">
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
                        foreach ($modules as $module) {
                            $title = "<a href='admin.php?page=rtr_module_detail&module_id=$module->id'>$module->title</a>";
                            ?>
                            <tr class="rowmod" data-id="<?php echo $module->id; ?>">
                                <td><?php echo $module->ord; ?></td>
                                <td class="title" data-txt="<?php echo $module->title; ?>"
                                    data-lnk="<?php echo $module->external_link; ?>"><?php echo $title; ?></td>
                                <td class="text">
                                    <div style="display: none; visibility: hidden" class="textdiv">
                                        <?php echo html_entity_decode($module->description); ?></div>
                                    <?php echo limit_text(html_entity_decode($module->description), 10, false); ?>
                                </td>
                                <td>
                                    <div class="infospan">
                                        <div>Total Hours: <?php echo $module->total_hrs; ?></div>
                                        <div>Total Exercises: <?php echo $module->total_resources; ?></div>
                                    </div>
                                </td>
                                <td><?php echo date("Y-m-d", strtotime($module->created_dt)); ?></td>
                                <td class="actiontd">
                                    <a href="admin.php?page=rtr_edit_module&module_id=<?php echo $module->id; ?>"
                                        title="Edit Module" class="rtr-btn rtr-btn-primary"><span class="rtr-glyphicon"><img
                                                src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/edit.svg"
                                                alt="Training course edit image"></i>
                                        </span></a>
                                    <a data-id="<?php echo $module->id; ?>"
                                        href="admin.php?page=rtr_module_detail&module_id=<?php echo $module->id; ?>"
                                        class="rtr-btn rtr-btn-success" title="Manage Module"><span
                                            class="rtr-glyphicon   "><img
                                                src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/manage.svg"
                                                alt="Training course manage image"></span></a>
                                    <a href="javascript:;" data-id="<?php echo $module->id; ?>"
                                        class="deletemod rtr-btn rtr-btn-danger" title="Delete Module"><span
                                            class="rtr-glyphicon"><img
                                                src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/trash.svg"
                                                alt="Training course trash image"></span></a>
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

    <div id="confirm_dialog" class="rtr-modal rtr-bs-modal rtr-fade">
        <div class="rtr-modal-dialog rtr-modal-lg">
            <div class="rtr-modal-content">
                <div class="rtr-modal-header">
                    <button type="button" class="rtr-close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title rtr-fs-4 rtr-m-0">Module</h4>
                </div>
                <div class="rtr-modal-body">
                    <form action="#" method="post" id="addmodules" name="addmodules" class="form-horizontal">
                        <input type="hidden" id="course_id" name="course_id" value="<?php echo $course_id; ?>" />
                        <input type="hidden" id="id" name="id" value="0" />
                        <div class="rtr-form-group">
                            <label for="title" class="rtr-col-lg-2 control-label">Name* :</label>
                            <div class="rtr-col-lg-8 cu-new-ex-in">
                                <input type="text" required class="rtr-form-control" id="title" name="title"
                                    placeholder="Title">
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
                    <button type="button" onclick="submitmodle();" class="rtr-btn rtr-btn-primary">Submit</button>
                    <button type="button" data-dismiss="modal" class="rtr-btn">Cancel</button>
                </div>
            </div>
        </div>
    </div>
<?php }
?>