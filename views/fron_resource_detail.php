<?php
/**
 * This File for manage resources.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;
?>
<div id="single-exercise-div">
    <?php
    global $current_user;
    $current_user = wp_get_current_user();
    $user_id = isset($current_user->data->ID) ? $current_user->data->ID : 0;
    include 'hasright.php';

    $videos = $wpdb->get_row
    (
        $wpdb->prepare
        (
            "SELECT path,extra_info FROM " . rtr_wpl_tr_media() . " WHERE resource_id = %d AND type = 'video' ORDER BY created_dt DESC",
            $resource_id
        )
    );

    $img = $wpdb->get_row
    (
        $wpdb->prepare
        (
            "SELECT path,extra_info FROM " . rtr_wpl_tr_media() . " WHERE resource_id = %d AND type = 'image' ORDER BY created_dt DESC",
            $resource_id
        )
    );

    $docs = $wpdb->get_results
    (
        $wpdb->prepare
        (
            "SELECT path,extra_info FROM " . rtr_wpl_tr_media() . " WHERE resource_id = %d AND type = 'document'",
            $resource_id
        )
    );

    $helplinks = $wpdb->get_results
    (
        $wpdb->prepare
        (
            "SELECT path,extra_info  FROM " . rtr_wpl_tr_media() . " WHERE resource_id = %d AND type = 'link'",
            $resource_id
        )
    );

    $notes = $wpdb->get_results
    (
        $wpdb->prepare
        (
            "SELECT note FROM " . rtr_wpl_tr_lesson_notes() . " WHERE resource_id = %d",
            $resource_id
        )
    );


    $base_url = site_url();
    $slug = RTR_WPL_PAGE_SLUG;

    $res_sts = $wpdb->get_var
    (
        $wpdb->prepare
        (
            "SELECT count(id) FROM " . rtr_wpl_tr_resource_status() . " WHERE resource_id = %d AND user_id = %d",
            $resource_id,
            $user_id
        )
    );

    $hassubmitted = '';
    if ($resource->button_type == 'mark') {
        $classmsrk = 'unmarkeddiv';
        $txtmarked = 'unmarked';
        $marktxt = 'Mark Complete';
        if ($res_sts > 0) {
            $txtmarked = 'marked';
            $marktxt = 'Completed';
            $classmsrk = 'markeddiv';
        }
    } else {
        $classmsrk = 'unmarkeddiv';
        $txtmarked = 'unmarked';
        $marktxt = 'Submit Project';
        if ($res_sts > 0) {
            $txtmarked = 'marked';
            $marktxt = 'Submitted';
            $classmsrk = 'markeddiv';
            $hassubmitted = get_project_links($resource->id);
        }
    }
    ?>

    <div class="main-section frontlessonpage rtr-mt-2 rtr-mb-3">

        <h4 class="rtr-fs-20 rtr-mb-2">Exercise Details</h4>
        <ol
            class="rtr-fs-767-13px rtr-flex-wrap mydetails-crumb rtr-m-0 rtr-mb-3 rtr-py-1 rtr-px-2 rtr-rounded-1 rtr-bg-dark rtr-d-flex rtr-text-white rtr-list-unstyled">
            <li class="rtr-breadcrumb-item"><a class="rtr-text-white"
                    href="<?php echo site_url() . "/" . RTR_WPL_PAGE_SLUG ?>">All Courses</a>
            </li>
            <li class="rtr-px-1">/</li>
            <li class="rtr-breadcrumb-item"><a class="rtr-text-white"
                    href="<?php echo site_url() . "/" . RTR_WPL_PAGE_SLUG . "?course=" . $course_id; ?>"><?php echo $course->title; ?>
                    [<?php echo $module->title; ?>]</a></li>
            <li class="rtr-px-1">/</li>
            <li class="rtr-breadcrumb-item"><a class="rtr-text-white"
                    href="<?php echo site_url() . "/" . RTR_WPL_PAGE_SLUG . "?lesson_detail=" . $lesson->id; ?>"><?php echo $lesson->title; ?></a>
            </li>
            <li class="rtr-px-1">/</li>
            <li class="rtr-breadcrumb-item active"><?php echo $resource->title; ?></li>
        </ol>

        <div class="rtr-row rtr-mb-3 rtr-flex-767-column">
            <div class="rtr-col-md-2 left_pd rtr-pe-2" style="<?php
            if (empty($videos->path)) {
                echo 'display:none;';
            }
            ?>">
                <div class="sect_left">
                    <ul class="rtr-list-unstyled rtr-767-d-flex">
                        <li class="current licls rtr-my-3 rtr-pe-md-0 rtr-pe-3"><a
                                class="rtr-text-dark rtr-d-flex rtr-align-items-center rtr-flex-column"
                                data-type='dashboard' href="javascript:;">
                                <img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/home.svg"
                                    alt="Training plugin home image">
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="licls rtr-my-3"><a
                                class="rtr-d-flex rtr-align-items-center rtr-flex-column rtr-text-dark"
                                data-type='description' href="javascript:;">
                                <img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/eye.svg"
                                    alt="Training course eye image">
                                <span>Description</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>

            <div class="rtr-col-md-10 border rtr-p-3 rtr-sm-12 dashboard clscomman">
                <div class="video_out" style="padding:0;<?php
                if (empty($videos->path)) {
                    echo 'display:none;';
                }
                ?>">
                    <?php
                    if (empty($videos)) {
                        echo "<!--div class=''><img src='" . RTR_WPL_COUNT_PLUGIN_URL . "assets/images/novideo.jpg'></a></div--->";
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
            ?>" class="description trexcercise clscomman cu-ex-detail-sec rtr-col-md-10 rtr-col-sm-12 <?php
            if (empty($videos->path)) {
                echo 'rtr-col-sm-12';
            } else {
                echo 'rtr-col-sm-10 border rtr-p-3';
            }
            ?>  texteditor">
                <?php
                if (empty($resource->description)) {
                    echo "No Description Found";
                } else {
                    ?>
                    <h4 class="rtr-fs-4 rtr-mb-2"><?php echo $resource->title; ?></h4>
                    <p><?php echo html_entity_decode($resource->description); ?></p>
                    <?php
                }
                ?>
            </div>
        </div>



        <div class="clearfix"></div>
        <div class="rtr-row myleftpad">
            <?php if (empty($videos->path)) {
                ?>
                <div class="rtr-col-md-12" style="padding:0;">
                    <div class="buttonres ">
                        <div class="<?php echo $classmsrk; ?>" id="resource_<?php echo $resource->id; ?>">
                            <div class="submit_buttons">
                                <a class="sub_btn <?php
                                if ($user_id > 0) {
                                    echo 'markresource';
                                } else {
                                    echo 'loginplease';
                                }
                                ?> resource_<?php echo $resource->id; ?>" data-page="exercise"
                                    data-lesson-title="<?php echo $lesson->title; ?>"
                                    data-exercise="<?php echo $resource->title; ?>"
                                    data-buttontype="<?php echo $resource->button_type; ?>"
                                    data-status="<?php echo $txtmarked; ?>" data-attr="<?php echo $resource->id; ?>" href="<?php
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
                </div>
                <?php
            } else {
                ?>
                <div class="rtr-col-md-1"></div>
                <div class="rtr-col-md-11">
                    <div class="buttonres ">
                        <div class="<?php echo $classmsrk; ?>" id="resource_<?php echo $resource->id; ?>">
                            <div class="submit_buttons">
                                <a class="sub_btn <?php
                                if ($user_id > 0) {
                                    echo 'markresource';
                                } else {
                                    echo 'loginplease';
                                }
                                ?> resource_<?php echo $resource->id; ?>" data-page="exercise"
                                    data-lesson-title="<?php echo $lesson->title; ?>"
                                    data-exercise="<?php echo $resource->title; ?>"
                                    data-buttontype="<?php echo $resource->button_type; ?>"
                                    data-status="<?php echo $txtmarked; ?>" data-attr="<?php echo $resource->id; ?>" href="<?php
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
                </div>
            <?php }
            ?>

        </div>
        <div class="clearfix"></div>
        <div class="rtr-row mg_top cu-xe-det-rec rtr-d-flex rtr-mt-3 rtr-align-items-start rtr-flex-767-column">
            <?php
            if (empty($videos->path)) {
                ?>
                <div class="rtr-col-sm-8 tr-exercise-below rtr-pe-3" style="padding-right: 0">
                    <div class="notesdiv rtr-bg-secondary border rtr-rounded-1 rtr-p-3 ">
                        <h4 class="rtr-fs-20">Instructor Notes 1</h4>
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
                <div class="rtr-col-sm-4 colrights" style="padding-right:0px;">

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
                <?php
            } else {
                ?>
                <div class="rtr-col-md-1"></div>
                <div class="rtr-col-md-7 rtr-col-sm-12 rtr-pe-3 rtr-pe-767-0px rtr-767-mb-2">
                    <div class="notesdiv tr-exercise-below rtr-bg-secondary border rtr-rounded-1 rtr-p-3 ">
                        <h4 class=" rtr-fs-20">Instructor Notes </h4>
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
                <div class="rtr-col-md-4 rtr-col-sm-12 colrights ">
                    <div class="rtr-col-sm-12 docsdiv rtr-bg-secondary border rtr-rounded-1 rtr-p-3 rtr-mb-3">
                        <h4 class=" rtr-fs-20">Resources</h4>
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

                    <div class="rtr-col-sm-12 helpdiv rtr-bg-secondary border rtr-rounded-1 rtr-p-3 rtr-me-3">
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
                <?php
            }
            ?>
        </div>
    </div>

    <!-- submit project box -->
    <div class="arrow_box submit_project respage" style="display: none;">

        <span class="close-btn">
            <span class="rtr-glyphicon glyphicon-remove btnclospop" aria-hidden="true"></span>
        </span>

        <form enctype="multipart/form-data">
            <div class="controls">
                <div class="rtr-col-sm-10">
                    <input type="text" name="project_links"
                        placeholder="Project Links (Use comma to separate if multiple)"
                        class="wk_project_link form-control " data-project="220">

                    <br />
                    <input type="file" name="responsedoc[]" id="responsedoc" multiple="true"><br />
                    <div class="remove_project_ctn" style="display: none;">
                        <a href="javascript:void(0)" class="remove_project_link" target="_blank">Remove link / Files</a>
                    </div>
                    <br />
                    <a href="javascript:;" class="btn pink project-submit-btn sub_btn">Submit</a>
                </div>

            </div>
        </form>
    </div>
    <!-- submit project box -->
</div>