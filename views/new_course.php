<?php
/**
 * This File for adding new course.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

include_once 'common.php';
?>
<div class="contaninerinner">

    <h4>Create New Course</h4>

    <ol class="rtr-breadcrumb">
        <li class="rtr-breadcrumb-item"><a href="admin.php?page=trainingtool">Courses</a></li>
        <li class="rtr-breadcrumb-item active"> Create New Course</li>
    </ol>

    <div class="rtr-panel rtr-panel-primary">
        <div class="rtr-pull-right"><a href="admin.php?page=trainingtool" class="rtr-btn rtr-btn-danger"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/back_arrow.svg" alt="Training plugin arrow image">
         Back</a></div>
        <div class="rtr-panel-heading">New Course</div>
        <div class="rtr-panel-body">

            <?php
            $created_courses = get_count_courses();
            if ($created_courses >= RTR_WPL_FREE_AVAIL_COURSE):
                ?> 
                <div class="rtr-alert rtr-alert-danger">
                <img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/manage.svg" alt="Training course manage image"> YOUR FREE COURSES LIMIT ENDED.<br/><strong>Note*:</strong> Under Free Courses surveillance you can create only 3 Courses.
                </div>
                <?php
            else:
                ?>

                <form action="#" method="post" id="add_course" name="add_course" class="form-horizontal cust-curcose-form">

                    <div class="rtr-form-group">
                        <label for="title" class="rtr-trng-cour-left control-label rtr-col-lg-2">Name* :</label>
                        <div class="rtr-trng-cour-right rtr-col-lg-8">
                            <input type="text" required class="rtr-form-control" id="title" name="title" placeholder="Title">
                        </div>
                    </div>
                     
                    <div class="rtr-form-group">
                        <label for="title" class="rtr-trng-cour-left control-label rtr-col-lg-2">Select Author* :</label>
                        <div class="rtr-trng-cour-right rtr-col-lg-8">
                            <select id="slct_author" class="rtr-form-control" name="slct_author">
                                <option value="-1"> -- choose author -- </option>
                                <?php
                                $all_authors = $wpdb->get_results("SELECT * from " . rtr_wpl_tr_authors(), ARRAY_A);
                                if (count($all_authors) > 0) {
                                    foreach ($all_authors as $inx => $stx) {
                                        ?>
                                        <option value="<?php echo $stx['id']; ?>"><?php echo $stx['name'] . " (" . $stx['email'] . ")"; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="rtr-form-group">
                        <label for="title" class="rtr-trng-cour-left control-label rtr-col-lg-2">Select Category* :</label>
                        <div class="rtr-trng-cour-right rtr-col-lg-8">
                            <select id="slct_category" class="rtr-form-control" name="slct_category">
                                <option value="-1"> -- choose category -- </option>
                                <?php
                                $all_authors = $wpdb->get_results("SELECT * from " . rtr_wpl_tr_categories(), ARRAY_A);
                                if (count($all_authors) > 0) {
                                    foreach ($all_authors as $inx => $stx) {
                                        ?>
                                        <option value="<?php echo $stx['id']; ?>"><?php echo $stx['name']; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="rtr-form-group">
                        <label for="title" class="rtr-trng-cour-left control-label rtr-col-lg-2">Select Subcategory* :</label>
                        <div class="rtr-trng-cour-right rtr-col-lg-8">
                            <select id="slct_subcategory" class="rtr-form-control" name="slct_subcategory">
                                <option> -- choose subcategory -- </option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" value="free" class="rtr-form-control" name="course_type" id="course_type"/>

                    <div class="rtr-form-group">
                        <label for="cat_name" class="rtr-trng-cour-left control-label rtr-col-lg-2">Description :</label>
                        <div class="rtr-trng-cour-right rtr-col-lg-8 wpeditor">
                            <?php
                            wp_editor("", $id = 'description', $prev_id = 'title', $media_buttons = false, $tab_index = 1);
                            ?>
                            <div class="rtr-trng-cour-btn w-100 rtr-mt-1">
                            <input type="submit" id="add_btn" value="Next" class="rtr-float-right rtr-btn rtr-btn-primary" style="">           
                        </div>
                            
                        </div>
                    </div>                   

                    <!-- <div class="rtr-form-group">
                        <label for="add_btn" class="rtr-trng-cour-left control-label rtr-col-lg-2"></label>
                        <div class="rtr-trng-cour-btn">
                            <input type="submit" id="add_btn" value="Next" class="rtr-btn rtr-btn-primary"/>           
                        </div>
                    </div> -->
                </form>
            <?php
            endif;
            ?>
    

        </div>
    </div>
</div>
