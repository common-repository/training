<?php
/**
 * This File for display all course lists.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

global $current_user;
global $user_ID;
$current_user = wp_get_current_user();
$user_id = isset($current_user->data->ID) ? $current_user->data->ID : 0;

$courses = $wpdb->get_results("SELECT * FROM " . rtr_wpl_tr_courses() . " ORDER BY id DESC",ARRAY_A);

       

if (count($courses) > 0) {
    $base_url = site_url();
    $slug = RTR_WPL_PAGE_SLUG;
    ?>

    

    <div class="tab-content training-cr-tabs col-12">

        <div class="rtr-row rtr-flex-sm-column rtr-flex-lg-row">
            <div class="rtr-sidebar-sticky rtr-col-sm-12 rtr-col-lg-3 rtr-pt-3 rtr-pe-3 border-right">
            <ul class="rtr-nav rtr-nav-tabs rtr-navtabs rtr-ms-0 rtr-d-flex rtr-mb-3">
                <li class="active rtr-me-2">
                    <a data-toggle="tab"  id="rtr-allcourse-btn" class="rtr-py-1 rtr-px-2 rtr-fs-7 rtr-cursor-pointer rtr-text-dark rtr-rounded-50">
                        All Courses
                    </a>
                </li>
                <?php
                if ($user_ID > 0) {
                    ?>
                    <li>
                        <a data-toggle="tab" id="rtr-mycourse-btn" class="rtr-py-1 rtr-px-2 rtr-fs-7 rtr-cursor-pointer rtr-text-dark rtr-rounded-50">
                            My Courses
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
                <!-- menu -->
                <div id="MainMenu" class="training-cat">
                    <div id="applied-filters">
                        <h3 id="titleCour" class="rtr-d-flex rtr-align-items-center rtr-justify-content-between rtr-mb-2">Filter <small><a href="javascript:void(0);" id="allcourse-tab" class="pull-right">Clear all</a></small></h3>
                        <div class="rtr-list-group cust-filter-div" id="filter-div" style="margin-left: 14px;">

                        </div>
                    </div>
                    <h2 id="titleCour" class="rtr-mb-2">Categories</h2>
                    <div class="rtr-list-group rtr-d-flex rtr-flex-column">
                        <?php
                        $trainingCategories = $wpdb->get_results("SELECT * from " . rtr_wpl_tr_categories(), ARRAY_A);

                        if (count($trainingCategories) > 0) {
                            foreach ($trainingCategories as $inx => $stx) {
                                $catSubcategories = $stx['subcategories'];
                                ?>
                                <a class="rtr-ps-0 rtr-py-1 rtr-lh-1 rtr-cursor-pointer rtr-list-group-item border-bottom rtr-text-dark rtr-list-group-item-success list-item-course" data-id="<?php echo $stx['id']; ?>" data-toggle="collapse" data-parent="#MainMenu"><?php echo $stx['name']; ?> <?php if (!empty($catSubcategories)) { ?><img class="rtr-float-right" src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/down_arrow.svg" alt=""><?php
                                    }
                                    ?></a>
                                <?php
                                if (!empty($catSubcategories)) {
                                    $subcatTr = json_decode($catSubcategories);
                                    ?>
                                    <div class="collapse rtr-list-group-submenu parent-div-chk" data-id="<?php echo $stx['id']; ?>" id="<?php echo str_replace(" ", "_", $stx['name']) . "_" . $inx; ?>">
                                        <?php
                                        foreach ($subcatTr as $subT) {
                                            ?>
                                            <label class="cr-subcat w-100 rtr-d-block rtr-mb-2"><input type="checkbox" name="cr-subcat" value="<?php echo $subT; ?>" class="cr-subcat"/> <?php echo $subT; ?></label>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <?php
                                }
                            }
                        }
                        ?>

                    </div>
                </div>
            </div>

            <div class="rtr-body-content rtr-col-lg-9 rtr-col-sm-12 rtr-pt-3 rtr-ps-3">

                <div id="allcourse" class="tab-pane rtr-fade in active">
                    <div class="main-section homeallpage">
                        <input type="hidden" name="url_redirect" id="url_redirect" value="<?php echo $base_url . '/' . $slug . '?course='; ?>"/>
                        <div id="tmpl-courses" class="cu-all-cour-sc">           
                            
                            <div class="mytab-course">
                                <?php
                                ob_start(); // start buffer
                                include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/tmpl/rtr-tmpl-load-front-courses.php';
                                $template = ob_get_contents();
                                ob_end_clean();
                                echo $template;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="mycourse" class="tab-pane rtr-fade rtr-section-mycourse">

                    <div class="main-section homeallpage ">

                        <input type="hidden" name="url_redirect" id="url_redirect" value="<?php echo $base_url . '/' . $slug . '?course='; ?>"/>

                        <div class="">

                            <div class="mytab-course">    

                                <div class="allcourses ">
                                    <?php
                                    $user = new WP_User($user_id);
                                    $course_enrolled = 0;
                                    $u_role = isset($user->roles[0]) ? $user->roles[0] : 0;

                                    if (count($courses) > 0) {

                                        foreach ($courses as $course) {

                                            $paid_status = 0;

                                            $url = "$base_url/$slug?course=$course->id";
                                            $title = "$course->title";

                                            $total_resources = $wpdb->get_var
                                                    (
                                                    $wpdb->prepare
                                                            (
                                                            "select count(id) as total FROM " . rtr_wpl_tr_resources() . " WHERE course_id = %d", $course->id
                                                    )
                                            );

                                            $percent = 0;
                                            if ($total_resources > 0) {
                                                $total_covered = $wpdb->get_var
                                                        (
                                                        $wpdb->prepare
                                                                (
                                                                "select count(id) as covered FROM " . rtr_wpl_tr_resource_status() . " WHERE course_id = %d AND user_id = %d", $course->id, $user_id
                                                        )
                                                );
                                                $percent = floor(($total_covered / $total_resources) * 100);
                                            }

                                            $cType = isset($course->course_type) && !empty($course->course_type) ? strtoupper($course->course_type) : '<i>FREE</i>';
                                            $cAmount = isset($course->course_amount) && !empty($course->course_amount) ? $course->course_amount : 0;

                                            // course progress
                                            if ($percent == 0) {
                                                continue;
                                            }

                                            $course_enrolled += 1;
                                            ?>

                                            <div class="rtr-col-md-4 rtr-px-1 rtr-mb-3 ">
                        
                                            <div class="my-course-div rtr-rounded-1 border">      
                                                <div class="borderdvcourse">
                                                    <div class="innerpanel">
                                                        <?php
                                                        $img_path = RTR_WPL_DEFAULT_IMAGE;
                                                        if (!empty($course->imgpath)) {
                                                            $img_path = $course->imgpath;
                                                        }
                                                        ?>
                                                        <div class="imgdivcorse rtr-p-1 position-relative">
                                                            <img class=" rtr-rounded-1" src="<?php echo $img_path; ?>" />
                                                            <div class="btnurriculum">
                                                            <a href='<?php echo $url; ?>' class="rtr-btn rtr-btn-dark rtr-text-white" data-item-user-id="<?php echo isset($user_id) ? $user_id : 0; ?>" >Go To Curriculum
                                                            </a>
                                                        </div>
                                                        </div>
                                                       
                                                    </div>
                                                    <div class="lowerpanel rtr-p-3">

                                                        <h4>
                                                            <small class="small">
                                                                <?php
                                                                $category_data = $wpdb->get_row(
                                                                        $wpdb->prepare(
                                                                                "SELECT * from " . rtr_wpl_tr_categories() . " WHERE id = %d", $course->category_id
                                                                        ), ARRAY_A
                                                                );
                                                                $catname = '';
                                                                if (!empty($category_data)) {
                                                                    $catname = $category_data['name'];
                                                                    echo $catname . " (" . $course->subcategory . ")";
                                                                }
                                                                ?>
                                                            </small>
                                                           
                                                        </h4>
                                                        <a class="rtr-fs-5 rtr-text-dark" href="<?php echo site_url() . "/" . RTR_WPL_PAGE_SLUG . "?course_description=" . $course->id; ?>" data-item-user-id="<?php echo isset($user_id) ? $user_id : 0; ?>">
                                                                <?php
                                                                echo $title;
                                                                ?>
                                                            </a>

                                                        <div class="rtr-row">

                                                            <div class="rtr-col-md-6 rtr-fs-6">
                                                                <i class=" icon-three"></i> <?php
                                                                if (!empty($course->total_resources)) {
                                                                    echo $course->total_resources . " Exercises";
                                                                } else {
                                                                    echo 'No Exercises';
                                                                }
                                                                ?>
                                                            </div>

                                                            <div class="rtr-col-md-6 rtr-fs-6 rtr-d-flex rtr-justify-content-end">
                                                                <i class="icon-time"></i> <?php
                                                                if (!empty($course->total_hrs)) {
                                                                    echo $course->total_hrs . "+ Hours";
                                                                } else {
                                                                    echo 'No Hours';
                                                                }
                                                                ?>
                                                            </div>

                                                            <div class="rtr-col-sm-12">
                                                                <div class="progressallinner">
                                                                    <div class="rtr-bar_info 1">
                                                                        <span class="rtr-fs-6 rtr-fw-bold"><?php echo $percent; ?> % Complete</span>
                                                                        <div class="rtr-bar-progress">
                                                                            <div class="rtr-perdiv" class="bar wip" style="width:<?php echo $percent; ?>%"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>  
                                                    </div>
                                                </div>
                                            </div>
                                            </div>


                                            <?php
                                        }

                                        if ($course_enrolled > 0) {
                                            // courses enrolled by user
                                        } else {
                                            ?>
                                            <div class="rtr-alert rtr-alert-danger mycourse-alert" style="margin-top:10px;">
                                                <p>No course found</p>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="rtr-alert rtr-alert-danger mycourse-alert" style="margin-top:10px;">
                                            <p>No course found</p>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
} else {
    ?>
    <div class="main-section homeallpage"> 
        <div class="rtr-d-flex rtr-p-4 rtr-align-items-center">
        <h4 class="h4tagfront rtr-col-6 rtr-m-0 rtr-fs-20">My Courses</h4> 
        <div class="btncours rtr-col-6 rtr-justify-content-end rtr-d-flex">
            <a href="?view=all_courses" class="rtr-btn rtr-btn-primary" >All Courses</a>
        </div>
        

        </div>               
           

        <div class="rtr-col-sm-12 rtr-px-3">
            <div class="rtr-alert rtr-alert-success">
                <strong>Note: </strong>
                No Course Created by Admin
            </div>
        </div>
    </div>
    <?php
}
?>