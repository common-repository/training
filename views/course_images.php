<?php
/**
 * This File for upload images for image by course.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

include_once 'common.php';
global $wpdb;

$courses = $wpdb->get_results("SELECT c.*, (SELECT count(id) as total FROM " . rtr_wpl_tr_enrollment() . " WHERE course_id = c.id) as enrolledby FROM " . rtr_wpl_tr_courses() . " c ORDER BY c.ord");

$base_url = site_url();
$slug = RTR_WPL_PAGE_SLUG;

wp_enqueue_media(); // This will enqueue the Media Uploader script
?>
<div class="contaninerinner"> 

    <ol class="rtr-breadcrumb">
        <li class="rtr-breadcrumb-item"><a href="admin.php?page=trainingtool">Courses</a></li>
        <li class="rtr-breadcrumb-item active"> Image By Course</li>
    </ol>

    <div class="panel tab-content">

        <div class="rtr-panel rtr-panel-primary rtrp-def-img-stg">
            <div class="rtr-panel-heading">Default image setting</div>
            <div class="rtr-panel-body">
                <p><i><b>Note:</b> This image will be shown as default course image if no image will be set.</i></p>
                <form class="form-horizontal" id="frm-default-image">
                    <div class="rtr-form-group rtr-d-flex">
                        <label class="control-label rtr-col-sm-2" for="image">Choose image:</label>
                        <div class="rtrp-upload-cou-img rtr-col-sm-10">
                            <input type="button" class="rtr-btn rtr-btn-success defaultuploadimg" value='Upload Course Image'/>                            
                            <input type='hidden' class='defaultCourseImgUrl' name="defaultCourseImgUrl"/>
                            <div class="uploadCourseImage"> 
                                <?php $def_image = get_option('tr_def_course_image'); ?>
                                <?php if(!empty($def_image)){ ?>
                                    <img src="<?php echo get_option('tr_def_course_image') ?>"/>
                                <?php } else{ ?>
                                     <img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL . 'assets/images/default-course.jpg' ?>"/>
                                <?php } ?>
                            </div> 
                            <div class="rtr-form-group"> 
                        <div class="rtrp-btn-sub">
                            <button type="submit" class="rtr-btn rtr-btn-primary">Submit</button>
                        </div>
                    </div>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>


        <div class="rtr-panel rtr-panel-primary">
            <div class="rtr-pull-right">
                <a class="rtr-btn rtr-btn-danger" href="admin.php?page=trainingtool"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/back_arrow.svg" alt="Training plugin arrow image"> Back</a>
            </div>

            <div class="rtr-panel-heading">
                Image By Course
            </div>

            <div class="rtr-panel-body">

                <table class="rtr-table rtr-table-bordered" id="coursesimages" >
                    <thead>
                        <tr>
                            <th >SNo</th>
                            <th>Course</th>
                            <th>Image</th>                        
                            <th >Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($courses as $course) {

                            $title = "$course->title";
                            $img = "<i>Not uploaded</i>";
                            $path = '';
                            if ($course->imgpath != '') {
                                $path = $course->imgpath;
                                $img = "<a target='_blank' href='javascript:;'><img src='$path' /></a>";
                            }
                            ?>
                            <tr class="rowmod" data-id="<?php echo $course->id; ?>">
                                <td><?php echo $course->ord; ?></td> 
                                <td><?php echo $title; ?></td>
                                <td class="imgtd" data-img="<?php echo $path; ?>" data-link="<?php echo $course->link; ?>"><?php echo $img; ?></td>                                
                                <td class="actiontd acttd">
                                    <a data-id="<?php echo $course->id; ?>" href="javascript:;" class="uploadcourseimg rtr-btn rtr-btn-primary" title="Upload Course Image">Upload Course Image</a>                                    
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
</div>

<div id="image_dialog" class="rtr-modal rtr-bs-modal rtr-fade modealrealodonclose">
    <div class="rtr-modal-dialog rtr-modal-lg">
        <div class="rtr-modal-content">
            <div class="rtr-modal-header">
                <button type="button" class="rtr-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title rtr-fs-4 rtr-m-0">Course image</h4>
            </div>
            <div class="rtr-modal-body">

                <form action="#" method="post" id="addimg" name="addimg" class="form-horizontal">

                    <input type="hidden" id="course_id" name="course_id" value="0" />

                    <div class="rtr-form-group">   
                        <label for="title" class="rtr-col-lg-3 control-label">Upload Image <br/>(Image Dimension: 275 X 500):</label>      
                        <div class="rtr-col-lg-8">
                            <input type="button" class="rtr-btn rtr-btn-success meduploadimg" value='Upload Media Image'/>                            
                            <input type='hidden' class='mediaImgUrl'/>
                            <div class="uploadedimg">

                            </div>                                                        
                        </div>
                    </div>

                    <!-- <div class="rtr-form-group">   
                        <label for="title" class="col-lg-3 control-label">Url (Call to action)  :</label>      
                        <div class="col-lg-8">
                            <input type="text" class="rtr-form-control" url='true' value="" id="urlimg" name="urlimg" placeholder="Enter URL" />
                        </div>
                    </div> -->

                </form>
            </div>
            <div class="rtr-modal-footer">
                <button type="button" onclick='$("#addimg").submit();' class="rtr-btn rtr-btn-primary" >Submit</button>
                <button type="button" data-dismiss="modal" class="rtr-btn rtr-image-dialog-cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>
