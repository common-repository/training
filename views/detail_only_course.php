<?php
/**
 * This File for frontend listing of all Courses.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

$course_id = isset($_REQUEST['course_description']) ? intval($_REQUEST['course_description']) : '';

$course = $wpdb->get_row
(
    $wpdb->prepare
    (
        "SELECT * FROM " . rtr_wpl_tr_courses() . " WHERE id = %d",
        $course_id
    )
);

$authordata = $wpdb->get_row(
    $wpdb->prepare(
        "SELECT * from " . rtr_wpl_tr_authors() . " WHERE id = %d",
        $course->assigned_author_id
    )
);

$current_user = wp_get_current_user();

if (isset($course_id) && !empty($course_id)) {
    //Fetching the course author id based on the course id
    $course_author_id = $wpdb->get_row
    (
        $wpdb->prepare
        (
            "SELECT assigned_author_id FROM " . rtr_wpl_tr_courses() . " WHERE id = %d",
            $course_id
        ),
        ARRAY_A
    );
    $author_id = $course_author_id['assigned_author_id'];

    //Getting the author information by using the author id
    $author = $wpdb->get_row
    (
        $wpdb->prepare
        (
            "SELECT * FROM " . rtr_wpl_tr_authors() . " WHERE id = %d",
            $author_id
        ),
        ARRAY_A
    );
    $author_name = $author['name'];
}

$user_id = isset($current_user->data->ID) ? $current_user->data->ID : 0;

$is_enrolled = $wpdb->get_var
(
    $wpdb->prepare
    (
        "SELECT count(id) as enrolled FROM " . rtr_wpl_tr_enrollment() . " WHERE course_id = %d AND user_id = %d",
        $course_id,
        $user_id
    )
);

$cType = isset($course->course_type) && !empty($course->course_type) ? strtolower($course->course_type) : 'free';
$cAmount = isset($course->course_amount) && !empty($course->course_amount) ? $course->course_amount : 0;

$base_url = site_url();
$slug = RTR_WPL_PAGE_SLUG;

$img_path = RTR_WPL_DEFAULT_IMAGE;
if(isset($course->imgpath) && !empty($course->imgpath)){
    $img_path = $course->imgpath;
}
?>
<div class="main-section well border rtr-p-3 rtr-mt-2 rtr-rounded-1 rtr-mb-4">

    <input type="hidden" name="url_redirect" id="url_redirect"
        value="<?php echo $base_url . '/' . $slug . '?course='; ?>" />

    <h4 class="rtr-fs-20 rtr-mb-2">Course Detail</h4>
    <ol
        class="rtr-flex-wrap rtr-fs-767-13px mydetails-crumb rtr-m-0 rtr-mb-3 rtr-py-1 rtr-px-2 rtr-rounded-1 rtr-bg-dark rtr-d-flex rtr-text-white rtr-list-unstyled">
        <li class="rtr-breadcrumb-item"><a class="rtr-text-white"
                href="<?php echo site_url() . "/" . RTR_WPL_PAGE_SLUG ?>">All Courses</a></li>
        <li class="rtr-px-1">/</li>
        <li class="rtr-breadcrumb-item active"><?php echo $course->title; ?></li>
    </ol>

    <div class="cource-detail-outter rtr-col-12 rtr-d-flex rtr-flex-lg-column">
        <div class="cource-detail-left rtr-col-5">
            <img class="rtr-img-fluid rtr-rounded-1 border"
                src="<?php echo $img_path; ?>">
        </div>

        <div class="cource-detail-right rtr-col-7 rtr-ps-4">

            <h1 class="h2main rtr-fw-bold rtr-mb-1">
                <?php echo $course->title; ?>

                <?php

                $category_name = $wpdb->get_var(
                    $wpdb->prepare(
                        "SELECT name from " . rtr_wpl_tr_categories() . " WHERE id = %d",
                        $course->category_id
                    )
                );
                if (!empty($category_name)) {
                    ?>


                    <?php

                }
                ?>
            </h1>
            <small
                class="small w-100 rtr-float-left"><?php echo $category_name . "(" . $course->subcategory . ")"; ?></small>
            <span class="fade_txt">Created by <?php echo $author_name; ?>,
                <?php echo date('D d M Y', strtotime($course->created_dt)); ?></span>
            <div class="rtr-col-sm-12 tr-author cu-tr-author-sec border rtr-rounded-1 rtr-p-3 rtr-mt-3">
                <div class="author-box-intro rtr-fs-5 rtr-fw-500 rtr-mb-2 ">About the Author</div>

                <div class="customrow rtr-d-flex rtr-col-12 rtr-flex-992-column">
                    <div class="rtr-col-sm-12 userImage rtr-col-lg-2 rtr-rounded-50">
                        <?php $imgae = !empty($authordata->profile_img) ? esc_attr(trim($authordata->profile_img)) : RTR_WPL_COUNT_PLUGIN_URL . "assets/images/blank.jpg"; ?>
                        <img class="rtr-rounded-50 border rtr-img-fluid img-responsive about-author-image img-circle center-block"
                            src="<?php echo $imgae; ?>">
                    </div>
                    <div class="rtr-col-sm-10">
                        <!-- .author-box-intro-->
                        <div class="author-inline-block">
                            <h4 class="author-box-title">
                                <span class="author-name rtr-fs-20 rtr-mb-2 w-100 rtr-float-left">
                                    <?php echo $authordata->name; ?></span>
                                <small
                                    class="rtr-fs-5 rtr-mb-2 w-100 rtr-float-left ">(<?php echo   (isset($authordata->post) && !empty($authordata->post)) ? $authordata->post . ',' : '' ; ?>
                                    <?php echo  (isset($authordata->phone) && !empty($authordata->phone)) ? $authordata->phone . ',' : '' ; ?> <?php echo $authordata->email; ?>)</small>
                                <small class="author-social-links rtr-fs-5 rtr-mb-1 w-100 rtr-float-left">
                                    Social Links:
                                    <a href="<?php echo $authordata->website; ?>" title="Website"
                                        class="author-social-link twitter small" target="_blank">
                                        <img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/website_icon.svg"
                                            alt="Training plugin browser icon image">
                                    </a>
                                    <a href="<?php echo $authordata->fb_url; ?>" class="author-social-link gplus small"
                                        title="Facebook" target="_blank">
                                        <img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/facebook.svg"
                                            alt="Training plugin facebook image">
                                    </a>
                                </small><!-- author-social-links -->
                            </h4>
                        </div>
                        <div class="author-box-content">
                            <?php echo $authordata->about; ?>
                        </div><!-- author-box-content -->
                    </div>
                </div>

            </div>

        </div>





    </div>





    <div class="rtr-row">
        <div class=" cust-cou-detail-div rtr-mt-4">


            <div class="rtr-row">
                <div class="">
                    <h3 class="rtr-text-primary rtr-fs-20 rtr-mb-2">Description</h3>
                    <div class="desccontent texteditor rtr-text-dark">
                        <?php echo html_entity_decode($course->description); ?>
                    </div>
                    <div class="enrollmentdiv">
                        <?php
                        $base_url = site_url();
                        $slug = RTR_WPL_PAGE_SLUG;
                        $url = "$base_url/$slug?course=$course->id";
                        $paid_status = 0;
                        ?>
                        <a href="<?php echo $url; ?>" class="rtr-btn rtr-btn-primary rtr-mt-2">Go To Curriculum</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>