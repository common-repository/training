<div class="allcourses rtr-col-12 rtr-d-flex rtr-flex-wrap">
    <?php
    global $current_user;
    global $user_ID;
    $current_user = wp_get_current_user();
    $user_id = isset($current_user->data->ID) ? $current_user->data->ID : 0;
    $category_id = isset($cid) ? intval($cid) : 0;
    $multiple_subcat = isset($subCategories) ? esc_attr($subCategories) : '';
    $subcat = isset($sub) ? $sub : "";

       if (!empty($subcat)) {

        $courses = $wpdb->get_results
                (
                $wpdb->prepare
                        (
                        "SELECT * FROM " . rtr_wpl_tr_courses() . " WHERE subcategory = %s ORDER BY id asc", $subcat
                )
        );
    } elseif (!empty($multiple_subcat)) {

        $subcatArray = explode(",", $multiple_subcat);
        $data = array();
        foreach ($subcatArray as $inx => $stx) {
            $data[] = "'$stx'";
        }

        $subCategories = implode(",", $data);

        $courses = $wpdb->get_results("SELECT * FROM " . rtr_wpl_tr_courses() . " WHERE subcategory IN ($subCategories) ORDER BY id asc");
        
    } else {
        $courses = $wpdb->get_results("SELECT * FROM " . rtr_wpl_tr_courses() . " ORDER BY id asc");
    }

    $user = new WP_User($user_id);
    $u_role = isset($user->roles[0]) ? $user->roles[0] : 0;

    foreach ($courses as $course) {

        $paid_status = 0;

        // $url = "$base_url/$slug?course=$course->id";
        $title = "$course->title";

        $total_resources = $wpdb->get_var
        (
            $wpdb->prepare
            (
                "Select count(id) as total FROM " . rtr_wpl_tr_resources() . " WHERE course_id = %d",
                $course->id
            )
        );

        $percent = 0;
        if ($total_resources > 0) {
            $total_covered = $wpdb->get_var
            (
                $wpdb->prepare
                (
                    "select count(id) as covered FROM " . rtr_wpl_tr_resource_status() . " WHERE course_id = %d AND user_id = %d",
                    $course->id,
                    $user_id
                )
            );
            $percent = floor(($total_covered / $total_resources) * 100);
        }

        $cType = isset($course->course_type) && !empty($course->course_type) ? strtoupper($course->course_type) : '<i>FREE</i>';
        $cAmount = isset($course->course_amount) && !empty($course->course_amount) ? $course->course_amount : 0;
        ?>
        <div class="rtr-col-md-4 rtr-px-0 rtr-px-md-1 rtr-mb-3 rtr-col-sm-12">

       
        <div class=" my-course-div rtr-rounded-1 border ">
            <div class="borderdvcourse">
                <div class="innerpanel">
                    <div class="imgdivcorse rtr-p-1 position-relative">
                        <?php
                        $img_path = RTR_WPL_DEFAULT_IMAGE;
                        if (!empty($course->imgpath)) {
                            $img_path = $course->imgpath;
                        }
                        ?>
                        <img class="rtr-img-fluid rtr-rounded-1" src="<?php echo $img_path; ?>" />
                        <div class="btnurriculum">
                        <a href='<?php
                        echo site_url() . "/" . RTR_WPL_PAGE_SLUG . "?course_description=" . $course->id;
                        ?>' class="rtr-btn rtr-btn-dark rtr-text-white"
                            data-item-user-id="<?php echo isset($user_id) ? $user_id : 0; ?>">Go To Curriculum
                        </a>
                    </div>
                    </div>
                   
                </div>
                <div class="lowerpanel rtr-p-3">
                    <h4 class="rtr-mb-0 rtr-fw-normal">
                        <small class="cus-cour-frnt small">
                            <?php
                            $category_data = $wpdb->get_row(
                                $wpdb->prepare(
                                    "SELECT * from " . rtr_wpl_tr_categories() . " WHERE id = %d",
                                    $course->category_id
                                ),
                                ARRAY_A
                            );
                            $catname = '';
                            if (!empty($category_data)) {
                                $catname = $category_data['name'];
                                echo $catname . " (" . $course->subcategory . ")";
                            }
                            ?>
                        </small>
                        <br />
                        
                    </h4>
                    <a href="<?php
                        echo site_url() . "/" . RTR_WPL_PAGE_SLUG . "?course_description=" . $course->id;
                        ?>" data-item-user-id="<?php echo isset($user_id) ? $user_id : 0; ?>" class="rtr-fs-5 text-dark">
                            <?php
                            echo $title;
                            ?>
                        </a>

                    <div class="rtr-row rtr-mb-1 rtr-flex-1400-column">
                        <div class="rtr-col-xl-6 cu-ex-sec exercise-time">
                            
                            <div class="exercise-time-text rtr-fs-6 rtr-d-flex rtr-align-items-center">
                            <img height="12px" width="12px" class="rtr-me-1" src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/exercise_icon.svg"
                            alt="Training plugin exercise image">
                                <?php
                                if (!empty($course->total_resources)) {
                                    echo $course->total_resources . " Exercises";
                                } else {
                                    echo 'No Exercises';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="rtr-col-xl-6 cu-ex-sec exercise-time ex-time-rght rtr-d-flex rtr-justify-content-end rtr-justify-1440-content-start">
                          
                            <div class="exercise-time-text rtr-fs-6 rtr-d-flex rtr-align-items-center">
                            <img height="12px" width="12px" class="rtr-me-1" src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/clock.svg"
                            alt="Training plugin clock image">
                                <?php
                                if (!empty($course->total_hrs)) {
                                    echo $course->total_hrs . "+ Hours";
                                } else {
                                    echo 'No Hours';
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="free-cont">
                        <div class="rtr-row">
                            <div class="rtr-col-sm-4 free-cont-left">
                                <span class="rtr-fs-6 rtr-fw-bolder">
                                    <?php
                                    echo "" . $cType . "";
                                    ?>
                                </span>
                            </div>

                            <div class="rtr-col-sm-8 free-cont-right rtr-d-flex rtr-justify-content-end">

                                <div id="share-buttons">

                                    <!-- Facebook -->
                                    <a href="javascript:void(0)"
                                        onclick='window.open("http://www.facebook.com/sharer.php?u=<?php echo site_url() . "/training-tool/?course_description=" . $course->id ?>", "", "width=700,height=500,top=100,left=100");'>
                                        <img class="rtr-img-fluid"
                                            src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/facebook.svg"
                                            alt="Facebook" />
                                    </a>

                                    <!-- Twitter -->
                                    <a href='javascript:void(0)'
                                        onclick='window.open("https://twitter.com/share?url=<?php echo site_url() . "/training-tool/?course_description=" . $course->id ?>&amp;text=Training%20Courses&amp;hashtags=trainingcourses", "", "width=700,height=500,top=100,left=100");'>
                                        <img class="rtr-img-fluid"
                                            src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/twitter.svg"
                                            alt="Twitter" />
                                    </a>
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
    ?>
</div>