<?php
/**
 * This File for manage lessons.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;
?>

<div id="front-lesson-detail">
    <?php
    global $current_user;
    $current_user = wp_get_current_user();
    $user_id = isset($current_user->data->ID) ? $current_user->data->ID : 0;
    include 'hasright.php';
    $completed_resources = 0;

    if (empty($lesson)) {
        ?>

        <div class="main-section">
            <div class="container">
                <h4>Lesson Detail</h4>
                <div class="bread_crumb">
                    <ul>
                        <li title="All Courses List">
                            <a href="<?php echo site_url() . "/" . RTR_WPL_PAGE_SLUG ?>">All Courses</a> >>
                        </li>
                        <li title="Course">
                            <a
                                href="<?php echo site_url() . "/" . RTR_WPL_PAGE_SLUG . "?course=" . $course_id; ?>"><?php echo $course->title; ?></a>
                            >>
                        </li>
                        <li title="Lesson">
                            No Lesson Detail
                        </li>
                    </ul>
                </div>
                <div class="row">
                    <div class="rtr-col-sm-12">

                        <div class="rtr-alert rtr-alert-success">
                            <strong>Note: </strong>
                            Lesson Not Found
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <?php
    } else {

        $videos = $wpdb->get_row
        (
            $wpdb->prepare
            (
                "SELECT path,extra_info FROM " . rtr_wpl_tr_media() . " WHERE lesson_id = %d AND type = 'video' ORDER BY created_dt DESC",
                $lesson_id
            )
        );

        $img = $wpdb->get_row
        (
            $wpdb->prepare
            (
                "SELECT path,extra_info FROM " . rtr_wpl_tr_media() . " WHERE lesson_id = %d AND type = 'image' ORDER BY created_dt DESC",
                $lesson_id
            )
        );

        $docs = $wpdb->get_results
        (
            $wpdb->prepare
            (
                "SELECT path,extra_info FROM " . rtr_wpl_tr_media() . " WHERE lesson_id = %d AND type = 'document'",
                $lesson_id
            )
        );

        $helplinks = $wpdb->get_results
        (
            $wpdb->prepare
            (
                "SELECT path,extra_info  FROM " . rtr_wpl_tr_media() . " WHERE lesson_id = %d AND type = 'link'",
                $lesson_id
            )
        );

        $notes = $wpdb->get_results
        (
            $wpdb->prepare
            (
                "SELECT note FROM " . rtr_wpl_tr_lesson_notes() . " WHERE lesson_id = %d",
                $lesson_id
            )
        );

        $resources = $wpdb->get_results
        (
            $wpdb->prepare
            (
                "SELECT * FROM " . rtr_wpl_tr_resources() . " WHERE lesson_id = %d ORDER BY ord",
                $lesson_id
            )
        );

        $res_sts = $wpdb->get_results
        (
            $wpdb->prepare
            (
                "SELECT rs.resource_id FROM " . rtr_wpl_tr_resource_status() . " rs INNER JOIN " . rtr_wpl_tr_resources() . " r ON "
                . "rs.resource_id = r.id WHERE rs.user_id = %d AND r.lesson_id = %d",
                $user_id,
                $lesson_id
            )
        );

        $arr_resoucesmark = array();
        foreach ($res_sts as $res_st) {
            array_push($arr_resoucesmark, $res_st->resource_id);
        }


        $base_url = site_url();
        $slug = RTR_WPL_PAGE_SLUG;
        ?>

        <div class="main-section frontlessonpage cu-les-det-dec rtr-mt-3">

            <h4 class="rtr-fs-20 rtr-mb-2">Lesson Details</h4>
            <ol
                class="rtr-flex-wrap rtr-fs-767-13px mydetails-crumb rtr-m-0 rtr-mb-3 rtr-py-1 rtr-px-2 rtr-rounded-1 rtr-bg-dark rtr-d-flex rtr-text-white rtr-list-unstyled rtr-767-mb-0">
                <li class="rtr-breadcrumb-item"><a class="rtr-text-white"
                        href="<?php echo site_url() . "/" . RTR_WPL_PAGE_SLUG ?>">All Courses</a>
                </li>
                <li class="rtr-px-1">/</li>
                <li class="rtr-breadcrumb-item"><a class="rtr-text-white"
                        href="<?php echo site_url() . "/" . RTR_WPL_PAGE_SLUG . "?course=" . $course_id; ?>"><?php echo $course->title; ?>
                        [<?php echo $module->title; ?>]</a></li>
                <li class="rtr-px-1">/</li>
                <li class="rtr-breadcrumb-item active"> <?php echo $lesson->title; ?></li>
            </ol>

            <div class="rtr-row rtr-flex-767-column">
                <div class="rtr-col-md-2 left_pd rtr-pe-2 rtr-pe-767-0px">
                    <div class="sect_left 1">
                        <ul class="rtr-list-unstyled rtr-767-d-flex 1">
                            <?php
                            if (empty($videos)) {
                                ?>
                                <li class="licls current rtr-my-3 rtr-pe-md-0 rtr-pe-3">
                                    <a data-type='description' href="javascript:;"
                                        class="rtr-d-flex rtr-text-dark rtr-align-items-center rtr-flex-column">
                                        <img class="rtr-me-1" src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/eye.svg "
                                            alt="Training course eye image">
                                        <span>Description</span>
                                    </a>
                                </li>

                                <li class="licls rtr-my-3">
                                    <a data-type='resource' href="javascript:;"
                                        class="rtr-d-flex rtr-text-dark rtr-align-items-center rtr-flex-column">
                                        <!-- <i class="fa fa-suitcase rtr-me-1" aria-hidden="true"></i> -->

                                        <img class="rtr-me-1"
                                            src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/exercise_icon.svg"
                                            alt="Training course exercise image">
                                        <span>Exercises</span>
                                    </a>

                                    </a>
                                </li>
                                <?php
                            } else {
                                ?>
                                <li class="current licls rtr-my-3">
                                    <a class="rtr-text-dark rtr-d-flex rtr-flex-column rtr-align-items-center"
                                        data-type='dashboard' href="javascript:;">
                                        <img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/home.svg"
                                            alt="Training plugin home image">
                                        <span>Dashboard</span>
                                    </a>
                                </li>

                                <li class="licls rtr-my-3">
                                    <a class="rtr-text-dark rtr-d-flex rtr-flex-column rtr-align-items-center"
                                        data-type='description' href="javascript:;">
                                        <img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/eye.svg"
                                            alt="Training course eye image">
                                        <span>Description</span>
                                    </a>
                                </li>

                                <li class="licls rtr-my-3">
                                    <a data-type='resource' href="javascript:;"
                                        class="rtr-text-dark rtr-d-flex rtr-flex-column rtr-align-items-center">
                                        <img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/exercise_icon.svg"
                                            alt="Training course eye image">
                                        <!-- <i aria-hidden="true" class="icon-ic_card_travel_black_24px"></i> -->
                                        <span>Exercises</span>
                                    </a>
                                </li>
                                <?php
                            }
                            ?>

                        </ul>
                    </div>
                </div>

                <div class="rtr-col-md-10 border">
                    <div class="dashboard clscomman rtr-p-2" style="<?php
                    if (empty($videos->path)) {
                        echo 'display:none;';
                    }
                    ?>">
                        <div class="rtr-col-sm-11 video_out" style="padding:0;">
                            <?php
                            if (empty($videos)) {
                                echo "<div class='' style='display:none;'><img src='" . RTR_WPL_COUNT_PLUGIN_URL . "assets/images/novideo.jpg'></a></div>";
                            } else {
                                echo html_entity_decode($videos->path);
                            }
                            ?>
                        </div>
                    </div>
                    <div style="<?php
                    if (empty($videos->path)) {
                        echo 'display:block;';
                    } else {
                        echo 'display:none;';
                    }
                    ?>" class="description clscomman texteditor rtr-p-3">


                        <?php
                        if (empty($lesson->description)) {
                            echo "No Description Found";
                        } else {
                            echo html_entity_decode($lesson->description);
                        }
                        ?>

                    </div>
                    <div style="display: none;" class="resource clscomman rtr-p-3">
                        <div class="rtr-col-sm-12 cu-ex-btn-sec">
                            <?php
                            if (empty($resources)) {
                                echo "No Exercises Found";
                            }
                            foreach ($resources as $resource) {
                                $hassubmitted = '';
                                if ($resource->button_type == 'mark') {
                                    $classmsrk = 'unmarkeddiv';
                                    $txtmarked = 'unmarked';
                                    $marktxt = 'Mark Complete';
                                    if (in_array($resource->id, $arr_resoucesmark)) {
                                        $txtmarked = 'marked';
                                        $marktxt = 'Completed';
                                        $classmsrk = 'markeddiv';
                                        $completed_resources++;
                                    }
                                } else {

                                    $classmsrk = 'unmarkeddiv';
                                    $txtmarked = 'unmarked';
                                    $marktxt = 'Submit Project';
                                    if (in_array($resource->id, $arr_resoucesmark)) {
                                        $txtmarked = 'marked';
                                        $marktxt = 'Submitted';
                                        $classmsrk = 'markeddiv';
                                        $completed_resources++;
                                        $hassubmitted = get_project_links($resource->id);
                                    }
                                }

                                $resourceurl = site_url() . "/" . RTR_WPL_PAGE_SLUG . "?exercise_detail=" . $resource->id;
                                $rtitt = "<a target='_blank' href='$resourceurl'>$resource->title</a>";
                                ?>

                                <div class="block_resources border-bottom rtr-pb-3 rtr-mb-3 <?php echo $classmsrk; ?>"
                                    id="resource_<?php echo $resource->id; ?>">


                                    <div class="block_main">
                                        <span class="block_left">
                                            <i class="icon-scales"></i>
                                        </span>
                                        <div class="11 block_info rtr-d-flex rtr-justify-content-between">

                                            <div class="block_txt rtr-fs-4">
                                                <img class="rtr-me-1"
                                                    src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/exercise_icon.svg"
                                                    alt="Training plugin exercise image">
                                                <?php echo $rtitt; ?>

                                            </div>
                                            <div class="sub-block_time">
                                                <img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/clock.svg"
                                                    alt="Training plugin clock image"> <?php echo $resource->total_hrs; ?> Hours
                                            </div>
                                        </div>

                                        <div class="full_descrp texteditor">
                                            <div class="smallinfo  rtr-fs-6">
                                                <?php echo limit_text(html_entity_decode($resource->description), 30); ?>
                                            </div>
                                            <div class="largeinofinfo  rtr-fs-6">
                                                <?php echo full_text(html_entity_decode($resource->description)); ?>
                                            </div>

                                        </div>
                                        <div class="submit_buttons" mklll__>
                                            <a class="sub_btn <?php
                                            if ($user_id > 0) {
                                                echo 'markresource';
                                            } else {
                                                echo 'loginplease';
                                            }
                                            ?> resource_<?php echo $resource->id; ?>" data-page="lesson"
                                                data-lesson-title="<?php echo $lesson->title; ?>"
                                                data-exercise="<?php echo $resource->title; ?>"
                                                data-buttontype="<?php echo $resource->button_type; ?>"
                                                data-status="<?php echo $txtmarked; ?>" data-attr="<?php echo $resource->id; ?>"
                                                href="<?php
                                                if ($user_id > 0) {
                                                    echo 'javascript:;';
                                                } else {
                                                    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                                    echo site_url() . '/wp-login.php?redirect_to=' . $actual_link;
                                                }
                                                ?>"><?php
                                                if ($user_id > 0) {
                                                    echo $marktxt;
                                                } else {
                                                    echo 'Login to mark';
                                                }
                                                ?></a>
                                        </div>


                                    </div>


                                </div>
                                <?php
                            }
                            ?>

                        </div>

                    </div>
                </div>
            </div>






            <div class="clearfix"></div>

            <div
                class="mg_top mg-left cu-ins-nostes rtr-d-flex rtr-mt-3 rtr-align-items-start rtr-mb-3 rtr-flex-767-column">
                <div class="rtr-col-sm-1"></div>

                <div class="rtr-col-md-7 rtr-col-12 rtr-bg-secondary border rtr-rounded-1 rtr-p-3 rtr-767-mb-2">
                    <div class="notesdiv">
                        <h4 class="rtr-fs-20">Instructor Notes</h4>
                        <?php if (!empty($notes)) { ?>
                            <?php
                            foreach ($notes as $note) {
                                ?>
                                <div>
                                    <?php echo html_entity_decode($note->note); ?>
                                </div>

                                <?php
                            }
                            ?>
                            <?php
                        } else {
                            echo "<i class='rtr-fs-6'>Notes Not Available</i>";
                        }
                        ?>

                    </div>
                </div>

                <div class="rtr-col-md-4 rtr-col-12 colrights rtr-ps-3 rtr-pe-767-0px" style="padding-right: 0;">

                    <div class="rtr-col-sm-12 docsdiv rtr-bg-secondary border rtr-rounded-1 rtr-p-3 rtr-mb-3">

                        <h4 class="rtr-fs-20">Resources</h4>
                        <?php if (!empty($docs)) { ?>
                            <ul class="help_links rtr-list-unstyled">
                                <?php
                                foreach ($docs as $doc) {
                                    ?>
                                    <li><a download href="<?php echo $doc->path; ?>"><?php echo $doc->extra_info; ?></a></li>
                                    <?php
                                }
                                ?>
                            </ul>
                            <?php
                        } else {
                            echo "<i class='rtr-fs-6'>Downloads Not Available</i>";
                        }
                        ?>
                    </div>

                    <div class="rtr-col-sm-12 helpdiv rtr-bg-secondary border rtr-rounded-1 rtr-p-3">
                        <h4 class="rtr-fs-20">Get Help</h4>
                        <?php if (!empty($helplinks)) { ?>
                            <ul class="help_links rtr-list-unstyled">
                                <?php
                                foreach ($helplinks as $helplink) {
                                    ?>
                                    <li><a target="_blank"
                                            href="<?php echo $helplink->path; ?>"><?php echo $helplink->extra_info; ?></a></li>
                                    <?php
                                }
                                ?>

                            </ul>
                            <?php
                        } else {
                            echo "<i class='rtr-fs-6'>Not Available</i>";
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div>

        <?php
    }
    ?>
</div>