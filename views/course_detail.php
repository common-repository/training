<?php
/**
 * This File for defining max logics.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;
?>

<style>
    .entry-title{
        visibility: hidden;
    }
    .subheader{
        display: none;
    }
</style>

<div id="training-ui-container" class="cu-rtr-frnt-ui">
    <?php
    include_once 'common.php';
    
    if (isset($_REQUEST['course_description']) && intval($_REQUEST['course_description']) != '') {
        include_once 'detail_only_course.php';
    } else {
        if ((isset($_REQUEST['lesson_detail']) && intval($_REQUEST['lesson_detail']) > 0) || isset($_REQUEST['exercise_detail']) && intval($_REQUEST['exercise_detail']) > 0) {
            if (isset($_REQUEST['exercise_detail']) && intval($_REQUEST['exercise_detail']) > 0) {
                $resource_id = intval($_REQUEST['exercise_detail']);
                $resource = $wpdb->get_row
                        (
                        $wpdb->prepare
                                (
                                "SELECT * FROM " . rtr_wpl_tr_resources() . " WHERE id = %d", $resource_id
                        )
                );
                if (empty($resource)) {
                    die('Invalid Exercise');
                }
                $lesson_id = $resource->lesson_id;
                $file = 'fron_resource_detail.php';
            } else {
                $lesson_id = intval($_REQUEST['lesson_detail']);
                $file = 'fron_lesson_detail.php';
            }

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
                            "SELECT id,title FROM " . rtr_wpl_tr_modules() . " WHERE id = %d", $module_id
                    )
            );

            $course = $wpdb->get_row
                    (
                    $wpdb->prepare
                            (
                            "SELECT * FROM " . rtr_wpl_tr_courses() . " WHERE id IN (SELECT course_id FROM " . rtr_wpl_tr_modules() . " WHERE id = %d) ", $module_id
                    )
            );
            $course_id = 0;
            if (!empty($course)) {
                $course_id = $course->id;
            }

            include_once $file;
        } else {

            $course_id = isset($_REQUEST['course']) ? intval($_REQUEST['course']) : 0;
            $course = $wpdb->get_row
                    (
                    $wpdb->prepare
                            (
                            "SELECT * FROM " . rtr_wpl_tr_courses() . " WHERE id =  %d", $course_id
                    )
            );
            if (!empty($course)) {
                
                include_once 'single_course_detail.php';
            } else {
                include_once 'all_courses.php';
            }
        }
    }
    wp_enqueue_media();
    ?>
<span id="training_ui_container_footer"></span>
</div>
<!-- Modal -->
<div id="subPrjModal" class="rtr-modal rtr-bs-modal rtr-fade cu-file-sub-popup" role="dialog">
    <div class="rtr-modal-dialog">
        <!-- Modal content-->
        <div class="rtr-modal-content">
            <div class="rtr-modal-header">
                <button type="button" class="rtr-close" data-dismiss="modal">&times;</button>
                <span class="myPrjheadline">Files Submission - <span id="prjHier"></span></span>
            </div>
            <div class="rtr-modal-body">
                <form name="frmProjectSubmit" id="frmProjectSubmit">
                    <input type="hidden" value="" name="resourse_id" id="resourse_id"/>
                    <div class="rtr-form-group">
                        <label for="email">Media Files</label>
                        <div id="alreadyfoundfiles"></div>
                        <span id="mylinkschoosen"></span>
                        <input type="hidden" name="mediafiles" id="mediafiles"/>
                        <input type="button" required class="rtr-btn rtr-btn-primary mybtnprj" value="Upload" name="btnPrjUploadMedia" id="btnPrjUploadMedia"/>
                    </div>
                    <input type="hidden" value="" name="page_type" id="page_type"/>
                    <div class="rtr-form-group">
                        <label for="email">Upload Links</label>
                        <table id="tbllinks">
                            <tr><td class=""><input type="url" class="rtr-form-control upd_links" name="upd_links[]" id="upd_links"/></td><td></td></tr>
                        </table>
                    </div>
                    <div class="rtr-form-group">
                        <button type="button" class="rtr-btn rtr-btn-success" id="addmorelinks"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Link</button>
                    </div>  
                    <button type="button" class="rtr-btn rtr-btn-default submitProject rtr-mt-1">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Ends -->
