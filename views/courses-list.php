<?php
/**
 * This File for listing all Courses.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

include_once 'common.php';
global $wpdb;

$courses = $wpdb->get_results( "SELECT c.*, (SELECT count(id) as total FROM " . rtr_wpl_tr_enrollment() . " WHERE course_id = c.id) as enrolledby FROM " . rtr_wpl_tr_courses() . " c ORDER BY c.ord limit 5");

$base_url = site_url();
$slug = RTR_WPL_PAGE_SLUG;
?>
<div class="contaninerinner">     
    <h4 class="rtr-fs-4 rtr-m-0">Courses</h4>
    <div class="rtr-panel rtr-panel-primary">
        <div class="rtr-pull-right">
            <a class="rtr-btn rtr-btn-info" href="admin.php?page=rtr_course_images" data-type="courses" data-id="">Image By Course</a>
            <a class="rtr-btn rtr-btn-warning reorder" href="javascript:;" data-type="courses" data-id="">Re-Order Courses</a>
            <a class="rtr-btn rtr-btn-success" href="admin.php?page=rtr_new_course">Create New Course</a>
        </div>
        <div class="rtr-panel-heading">Courses</div>
        <div class="rtr-panel-body">
            <div class="rtr-alert rtr-alert-info give-bkg 1">
                <strong>Frontend URL: <a target='_blank' href="<?php echo $base_url . '/' . $slug; ?>"><?php echo $base_url . '/' . $slug; ?></a></strong>
            </div>
            <table class="rtr-table rtr-table-bordered" id="data_courses">
                <thead>
                    <tr>
                        <th>SNo</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Information</th>                                               
                        <th>Date</th>										
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($courses as $course) {

                        $title = "<a href='admin.php?page=rtr_course_detail&course_id=$course->id'>$course->title</a>";
                        $viewlink = "<a class='rtr-btn rtr-btn-primary' target='_blank' href='$base_url/$slug?course=$course->id'>View Course</a>";

                        $enroll_detail = $wpdb->get_results
                                (
                                $wpdb->prepare
                                        (
                                        "SELECT distinct user_id from " . rtr_wpl_tr_resource_status() . " WHERE course_id = %d", $course->id
                                )
                        );
                        $listenrolledby = count($enroll_detail);
                        if ($listenrolledby > 0)
                            $listenrolledby = "$listenrolledby <a href='javascript:;' total-users='$listenrolledby' data-title='$course->title' data-attr='$course->id' class='enrollbylist' > View List</a>"
                            ?>
                        <tr class="rowmod" data-id="<?php echo $course->id; ?>">
                            <td><?php echo $course->ord; ?></td> 
                            <td><?php echo $title; ?></td>
                            <td><?php echo limit_text(html_entity_decode($course->description), 10, false); ?></td>
                            <td>
                                <div class="infospan">
                                    <div>Enrolled By: <?php echo $listenrolledby; ?></div>
                                    <div>Total Hours: <?php echo $course->total_hrs; ?></div>
                                    <div>Total Exercises: <?php echo $course->total_resources; ?></div>                                                                            
                                </div>                                
                            </td>                                                                                                

                            <td><?php echo date("Y-m-d", strtotime($course->created_dt)); ?></td>                            
                            <td class="actiontd acttd">
                               
                                    <a data-id="<?php echo $course->id; ?>" href="admin.php?page=rtr_edit_course&course_id=<?php echo $course->id; ?>" class="rtr-btn rtr-btn-primary" title="Edit Course"><span class="rtr-glyphicon"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/edit.svg" alt="Training course edit image">
</span></a>                                    
                                    <a data-id="<?php echo $course->id; ?>" href="admin.php?page=rtr_course_detail&course_id=<?php echo $course->id; ?>" class="rtr-btn rtr-btn-success" title="Manage Course"><span class="rtr-glyphicon   "><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/manage.svg" alt="Training course manage image"></span></a>                                    
                                    <a class='rtr-btn rtr-btn-primary' target='_blank' href='<?php echo $base_url . "/" . $slug . "?course=" . $course->id; ?>' title="View course"><span class="rtr-glyphicon  "><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/search.svg" alt="Training course search image"></span></a>
                                    <a href="javascript:;" data-id="<?php echo $course->id; ?>" title="Delete Course" class="deletecou rtr-btn rtr-btn-danger"><span class="rtr-glyphicon"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/trash.svg" alt="Training course trash image"></span></a>                                    
                               
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


<div id="enrolled_dialog" class="rtr-modal rtr-bs-modal rtr-fade">
    <div class="rtr-modal-dialog rtr-modal-lg">
        <div class="rtr-modal-content">
            <div class="rtr-modal-header">
                <button type="button" class="rtr-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="rtr-modal-title">View List</h4>
            </div>
            <div class="rtr-modal-body" id="ernrolledlist">                
                <div class="loadergif">
                    <img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL; ?>/assets/css/images/loading.gif" />
                </div>
            </div>            
        </div>
    </div>
</div>


<div id="mentors_dialog" class="rtr-modal rtr-bs-modal rtr-fade">
    <div class="rtr-modal-dialog rtr-modal-lg">
        <div class="rtr-modal-content">
            <div class="rtr-modal-header">
                <button type="button" class="rtr-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title rtr-fs-4 rtr-m-0">Mentors</h4>
            </div>
            <div class="rtr-modal-body" id="mentorsid">                
                <div class="loadergif">
                    <img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL; ?>/assets/css/images/loading.gif" />
                </div>
            </div>            
        </div>
    </div>
</div>

<div style="display: none; visibility: hidden" class="gifhidden">
    <div class="loadergif">
        <img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL; ?>/assets/css/images/loading.gif" />
    </div>
</div>
