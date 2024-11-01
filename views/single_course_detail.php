<?php
/**
 * This File for frontend single course page.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;
?>

<div id="single-course-div" class="cust-less-sec">
    <?php
    global $current_user, $wpdb;
    $current_user = wp_get_current_user();
    $user_id = isset($current_user->data->ID) ? $current_user->data->ID : 0;

    $query_show_get = isset($_GET['show']) ? esc_attr($_GET['show']) : '';

    $now = date("Y-m-d H:i:s");
    $usertbl = $wpdb->prefix . "users";


    $toplinks = $wpdb->get_results("SELECT * FROM " . rtr_wpl_tr_setting() . " WHERE type = 'link' AND is_show = 1");

    $lessons = $wpdb->get_results
            (
            $wpdb->prepare
                    (
                    "SELECT l.*,m.ord as module_ord, m.id as module_id,m.course_id,m.title as mtitle, m.description as mdescription, m.external_link as mexternal_link,"
                    . "m.total_hrs as mtotal_hrs, m.total_resources as mtotal_resources "
                    . "FROM " . rtr_wpl_tr_lessons() . " l LEFT JOIN " . rtr_wpl_tr_modules() . " m ON l.module_id = m.id "
                    . "WHERE m.course_id = %d ORDER BY m.ord,l.ord", $course_id
            )
    );

    $innerarr = $wpdb->get_results
            (
            $wpdb->prepare
                    (
                    "SELECT l.*, r.ord as resource_ord, r.id as resource_id, r.lesson_id, r.button_type, r.module_id, r.course_id,r.title as rtitle, r.description as rdescription, r.external_link as rexternal_link,"
                    . "r.total_hrs as rtotal_hrs, m.ord as module_ord, m.id as module_id, m.course_id,m.title as mtitle, m.description as mdescription,"
                    . "m.external_link as mexternal_link,m.total_hrs as mtotal_hrs, m.total_resources as mtotal_resources "
                    . "FROM " . rtr_wpl_tr_lessons() . " l LEFT JOIN " . rtr_wpl_tr_modules() . " m ON l.module_id = m.id "
                    . "LEFT JOIN " . rtr_wpl_tr_resources() . " r ON l.id = r.lesson_id WHERE m.course_id = %d "
                    . " ORDER BY m.ord,l.ord,r.ord", $course_id
            )
    );

    $res_sts = $wpdb->get_results
            (
            $wpdb->prepare
                    (
                    "SELECT rs.resource_id FROM " . rtr_wpl_tr_resource_status() . " rs INNER JOIN " . rtr_wpl_tr_resources() . " r ON "
                    . "rs.resource_id = r.id WHERE rs.user_id = %d AND rs.course_id = %d", $user_id, $course_id
            )
    );

    $arr_resoucesmark = array();
    foreach ($res_sts as $res_st) {
        array_push($arr_resoucesmark, $res_st->resource_id);
    }
    $user = get_user_by('id', $course->created_by);

    $percent = 0;
    $completed_resources = 0;
    $total_resources = 0;

    if (empty($lessons)) {
        ?>

        <div class="main-section rtr-mt-3">
            <h4 class="rtr-fs-20 rtr-mb-2">Course Detail</h4>
            <div class="bread_crumb">
                <ol class="rtr-flex-wrap rtr-fs-767-13px mydetails-crumb rtr-m-0 rtr-mb-3 rtr-py-1 rtr-px-2 rtr-rounded-1 rtr-bg-dark rtr-d-flex rtr-text-white rtr-list-unstyled">
                    <li class="rtr-breadcrumb-item"><a class="rtr-text-white" href="<?php echo site_url() . "/" . RTR_WPL_PAGE_SLUG ?>">All Courses</a></li>
                    <li class="rtr-px-1">/</li>
                    <li class="rtr-breadcrumb-item active"><?php echo $course->title; ?></li>
                </ol>
            </div>
            <div class="rtr-col-sm-12 rtr-no-data">

                <div class="rtr-alert rtr-alert-success">
                    <strong>Note: </strong>
                    No Data Added Yet In this Course
                </div>

            </div>
        </div>

        <?php
    } else {

        $globalmodid = $lessons[0]->module_id;
        ?>

        <div class="main-section singlepagecourse rtr-mt-3">

            <h4 class="rtr-fs-20 rtr-mb-2">Course Detail</h4>

            <ol class="rtr-flex-wrap rtr-fs-767-13px mydetails-crumb rtr-m-0 rtr-mb-3 rtr-py-1 rtr-px-2 rtr-rounded-1 rtr-bg-dark rtr-d-flex rtr-text-white rtr-list-unstyled">
        <li class="rtr-breadcrumb-item"><a class="rtr-text-white" href="<?php echo site_url() . "/" . RTR_WPL_PAGE_SLUG ?>">All Courses</a></li>
        <li class="rtr-px-1">/</li>
        <li class="rtr-breadcrumb-item active"><?php echo $course->title; ?></li>
    </ol>


        <div class="rtr-d-flex rtr-flex-992-column">
            <div class="rtr-col-lg-3 tr-fixsidebar rtr-992-mb-4">
                <div class="sidebar-left" style="<?php echo $query_show_get == 'recorded_calls' ? 'display:none' : ''; ?>">                    
                    <?php
                    $get_project = get_project('course', $lessons[0]->course_id, 'check');
                    $flg = 0;
                    $inc = 1;
                    $headerpt = 0;
                    $innercnt = 0;
                    foreach ($lessons as $lesson) {

                        $isheader = 0;
                        if ($flg == 0) {
                            $isheader = 1;
                        } else {
                            if ($globalmodid != $lesson->module_id) {
                                $isheader = 1;
                                $globalmodid = $lesson->module_id;
                            }
                        }
                        $innercnt++;
                        if ($isheader == 1) {
                            $headerpt++;
                            ?>
                            <ul class="rtr-list-unstyled rtr-mb-2">
                                <li class="module" data-attr="mod<?php echo $headerpt; ?>" id="limodule<?php echo $inc; ?>"><a class="rtr-text-dark" href="#module<?php echo $lesson->id; ?>"><?php echo $headerpt; ?>. <?php echo $lesson->mtitle; ?></a></li>

                            </ul>
                            <ul class="rtr-list-unstyled rtr-mb-0 subheader modulelesson<?php echo $inc; ?>">
                                <?php
                            }
                            ?>
                            <li data-attr="mod<?php echo $headerpt; ?>" class="leson rtr-text-dark"><a href="#lesson<?php echo $lesson->id; ?>"><span class="sub_point"><?php echo $headerpt . '.' . $innercnt; ?></span><span class="point_title"> <?php echo $lesson->title; ?></span></a></li>
                            <?php
                            $oldid = $lesson->module_id;
                            $flg++;
                            $inc++;
                            // End od subheader ul
                            $lesModId = isset($lessons[$flg]->module_id) ? $lessons[$flg]->module_id : '';
                            if ($lesModId != $oldid) {

                                $get_mod = get_project('module', $oldid);
                                if (!empty($get_mod)) {
                                    ?>
                                    <li data-attr="mod<?php echo $headerpt; ?>" class="leson"><a href="#proj<?php echo $get_mod->id; ?>"><span class="sub_point"><?php echo $headerpt . '.' . ($innercnt + 1); ?></span><span class="point_title"> <?php echo $get_mod->title; ?></span></a></li>

                                    <?php
                                }
                                $innercnt = 0;
                                ?>
                            </ul>
                            <?php
                        }
                    }

                    if (!empty($get_project)) {
                        $headerpt = $headerpt + 1;
                        ?>

                        <!-- for last project part -->

                        <ul class="rtr-list-unstyled">
                            <li class="module" data-attr="mod<?php echo $headerpt; ?>" id="limodule<?php echo $inc++; ?>"><a href="#proj<?php echo $get_project->id; ?>"><?php echo $headerpt; ?>. <?php echo $get_project->title; ?></a></li>
                        </ul>                                    

                        <!-- for last project part -->
                    <?php } ?>
                </div>
            </div>

            <div class="rtr-col-lg-9">
                <div id="contentheader" class="content_header rtr-d-flex rtr-flex-992-column" style="">
                    <div class="rtr-col-lg-7 cust-exr-div-s">
                        
                        <h2 class="h2main rtr-fs-4 rtr-mb-2"><?php echo $course->title; ?></h2>

                        <?php if ($user_id > 0) { 
                            $authorInfo = get_author_info($course->assigned_author_id);
                   
                            ?>
                            <div class="content_info">
                                <h5>

                                    <div class="info_inner rtr-d-flex rtr-align-items-center rtr-992-mb-4">
                                        
                                        <div class="info_pic rtr-me-1">
                                            <img class="border rtr-rounded-50" src="<?php echo isset($authorInfo['profile_img']) && $authorInfo['profile_img'] != ""?$authorInfo['profile_img']:plugins_url("training/assets/images/blank.jpg"); ?>" style="height: 40px;width:40px;"/>
                                        </div>

                                        <span style="text-transform: none;font-size: 15px;" rel="author"><?php echo ucwords($authorInfo['name']); ?></span><?php echo $authorInfo['post'] != "" ? "," : ""; ?>

<span style="text-transform: none;font-size: 15px;" class="fade_txt"><?php echo ucwords($authorInfo['post']); ?></span>

                                    </div>

                                </h5>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="rtr-align-items-992-start rtr-col-lg-5 rtr-col-sm-12 rtr-d-flex rtr-flex-column rtr-align-items-end ">
                        <div class="resources_out">
                            <img class="rtr-me-1" src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/exercise_icon.svg" alt="Training plugin exercise image"> <?php echo $course->total_resources; ?> Exercises
                        </div>

                        <div class="tme_out rtr-992-mb-10px">
                        <img class="rtr-me-1" src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/clock.svg" alt="Training plugin clock image"> <?php echo $course->total_hrs; ?>+ Hours
                        </div>

                        <div class="share_out rtr-float-right rtr-fs-6 rtr-fw-500">
                         <?php 
                            $visit = get_author_info($course->assigned_author_id);
                            $visit = isset($visit['website']) ? $visit['website'] : '';
                            
                            if(!empty($visit)){
                            ?>
                            <a class="rtr-text-dark" href="<?php echo $visit; ?>" target="_blank">Visit Author</a>
                            
                            <?php
                            }
                        }

                            foreach ($toplinks as $link) {
                                if(isset($link->keyvalue) && !empty($link->keyvalue)){
                                ?>   
                                   
                                <a class="rtr-text-dark" target="_blank" href="<?php echo $link->keyvalue; ?>">
                                    <?php echo $link->keyname; ?>
                                </a>  
                                <?php
                                }
                            }
                            ?>                                
                        </div>

                    </div>

                </div>


                <div class="content_main rtr-p-3 rtr-bg-light rtr-mt-3 rtr-rounded-1 rtr-mb-4">
                    <div class="progress_outer rtr-d-flex rtr-align-items-center rtr-col-12 rtr-flex-992-column ">
                        <?php
                        $cname = $wpdb->get_row
                                (
                                $wpdb->prepare
                                        (
                                        "SELECT * FROM " . rtr_wpl_tr_courses() . " WHERE id = %d", intval($_REQUEST['course'])
                                )
                        );
                        $append = "";
                        $href = "href='#'";
                        ?>
                        <?php
                        if ($user_id > 0) {
                            ?>
                            <h2 class="up_title rtr-col-lg-6 rtr-col-12 rtr-992-mb-10px"><?php echo $query_show_get == 'recorded_calls' ? "" . $append : 'Hello, ' . $current_user->data->display_name; ?></h2>
                            <?php
                        }
                        ?>

                        <div class="progress_inner rtr-col-lg-6 rtr-col-12 rtr-d-flex rtr-justify-content-end" style="">
                            <div class="bar_info w-100">
                                <?php
                                $courseID = $course->id;
                                //count total resource of current res id
                                $totalRes = $wpdb->get_var(
                                        $wpdb->prepare(
                                                "SELECT count(id) from " . rtr_wpl_tr_resources() . " WHERE course_id = %d", $courseID
                                        )
                                );
                                //find done statuse of resources
                                $totalCompletedStatus = $wpdb->get_var(
                                        $wpdb->prepare(
                                                "SELECT count(id) from " . rtr_wpl_tr_resource_status() . " WHERE course_id = %d and user_id = %d", $courseID, $user_id
                                        )
                                );

                                $percent = 0;
                                if ($totalCompletedStatus > 0) {
                                    $percent = floor(($totalCompletedStatus / $totalRes) * 100);
                                }
                                ?>
                                <span class="mypercentage"><?php echo $percent; ?> % Complete</span>
                                <div class="bar-progress">
                                    <div class="perdiv" style="width:<?php echo $percent; ?>%" class="bar wip"></div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="rtr-col-sm-12 innerdata cust-innd-sec" style="<?php echo $query_show_get == 'recorded_calls' ? 'display:none' : ''; ?>">

                        <?php
                        $globalmodid = (isset($innerarr[0]) && isset($innerarr[0]->module_id)) ? $innerarr[0]->module_id : '';
                        $flg = 0;
                        $inc = 1;
                        $headerpt = 0;
                        $inclesson = 1;


                        $globallesson = (isset($innerarr[0]) && isset($innerarr[0]->id)) ? $innerarr[0]->id : '';

                        foreach ($innerarr as $lesson) {

                            $isheader = 0;
                            $islesson = 0;
                            if ($flg == 0) {
                                $isheader = 1;
                                $islesson = 1;
                            } else {
                                if ($globalmodid != $lesson->module_id) {
                                    $isheader = 1;
                                    $globalmodid = $lesson->module_id;
                                }

                                if ($globallesson != $lesson->id) {
                                    $islesson = 1;
                                    $globallesson = $lesson->id;
                                }
                            }

                            if ($isheader == 1) {
                                $tit = $lesson->mtitle;
                                if ($lesson->mexternal_link != '') {
                                    $tit = "<a target='_blank' href='$lesson->mexternal_link'>$tit</a>";
                                }
                                ?>
                                <div class="first_block blockcontent" id="module<?php echo $lesson->id; ?>">

                                    <header>
                                        <div class="rtr-d-flex rtr-justify-content-between rtr-mt-4 border-bottom rtr-pb-3 rtr-flex-992-column">
                                        <h2 class="h2main rtr-fs-20 rtr-col-md-7 rtr-mb-0 rtr-992-mb-10px"><?php echo $tit; ?></h2>
                                        <span class="block_time cu-block-time-mr tr-col-md-5 rtr-d-flex rtr-justify-content-end rtr-align-items-center rtr-justify-content-992-start">
                                        <img class="rtr-me-1" src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/clock.svg" alt="Training plugin clock image">   <?php echo $lesson->mtotal_hrs; ?>+ Hours
                                        </span>

                                        </div>

                                       
                                       
                                        <div class="descrp_main rtr-pe-767-0px">
                                            <p>
                                            <div class="smallinfo">
                                                <?php echo html_entity_decode($lesson->mdescription); ?>
                                            </div>   

                                            </p>
                                        </div>

                                        <?php
                                    }


                                    if ($islesson == 1) {

                                        $titt = $lesson->title;
                                        $lessonurl = site_url() . "/" . RTR_WPL_PAGE_SLUG . "?lesson_detail=" . $lesson->id;
                                        $titt = '<a target="_blank" href="' . $lessonurl . '&course=' . intval($_REQUEST['course']) . '">' . $titt . '</a>';
                                        ?>

                                        <div class="sub_block blockcontent rtr-pe-767-0px" id="lesson<?php echo $lesson->id; ?>">
                                            <header class="rtr-d-flex rtr-justify-content-between rtr-mt-4 border-bottom rtr-pb-2 rtr-flex-wrap">
                                            <h4 class="h4main rtr-fs-4 rtr-col-md-7"><?php echo $titt; ?></h4>
                                                <span class="block_time rtr-col-md-5 rtr-d-flex rtr-justify-content-end rtr-align-items-center" >
                                                <img class="rtr-me-1" src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/clock.svg" alt="Training plugin clock image">  <?php echo $lesson->total_hrs; ?>+ Hours
                                                </span>
                                                <div class="w-100">
                                                <div class="smallinfo">
                                                    <?php echo limit_text(html_entity_decode($lesson->description), 30); ?>                                           
                                                </div>
                                                <div class="largeinofinfo">
                                                    <?php echo full_text(html_entity_decode($lesson->description)); ?>                                                
                                                </div>
                                                </div>
                                                

                                            </header>

                                            <div class="descrp_main texteditor rtr-fs-6 rtr-pe-767-0px">


                                                


                                                <?php
                                            }
                                            ?>
                                            <?php
                                            if ($lesson->resource_id != '') {

                                                $resourceurl = site_url() . "/" . RTR_WPL_PAGE_SLUG . "?exercise_detail=" . $lesson->resource_id;
                                                $rtitt = "<a target='_blank' href='$resourceurl'>$lesson->rtitle</a>";
                                                $hassubmitted = '';
                                                if ($lesson->button_type == 'mark') {
                                                    $classmsrk = 'unmarkeddiv';
                                                    $txtmarked = 'unmarked';
                                                    $marktxt = 'Mark Complete';
                                                    if (in_array($lesson->resource_id, $arr_resoucesmark)) {
                                                        $txtmarked = 'marked';
                                                        $marktxt = 'Completed';
                                                        $classmsrk = 'markeddiv';
                                                        $completed_resources++;
                                                    }
                                                } else {

                                                    $classmsrk = 'unmarkeddiv';
                                                    $txtmarked = 'unmarked';
                                                    $marktxt = 'Submit Project';
                                                    if (in_array($lesson->resource_id, $arr_resoucesmark)) {
                                                        $txtmarked = 'marked';
                                                        $marktxt = 'Submitted';
                                                        $classmsrk = 'markeddiv';
                                                        $completed_resources++;
                                                        $hassubmitted = get_project_links($lesson->resource_id);
                                                    }
                                                }


                                                $total_resources++;
                                                ?>
                                                <div class="block_resources border-bottom rtr-pb-3 rtr-mb-3 <?php echo $classmsrk; ?>" id="resource_<?php echo $lesson->resource_id; ?>">
                                                    
                                                    <div class="block_main rtr-d-flex rtr-justify-content-between rtr-mb-1 rtr-flex-992-column">
                                                        <div class="rtr-d-flex rtr-align-items-center">
                                                        <span class="block_left">
                                                        <img class="rtr-me-1" src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/exercise_icon.svg" alt="Training plugin exercise image"> 
                                                        </span>
                                                        <div class="block_txt rtr-fs-4">
                                                                <?php echo $rtitt; ?>
                                                        </div>
                                                        </div>
                                                        <div class="sub-block_time rtr-fs-5 rtr-justify-content-992-start">
                                                            <img class="rtr-me-1" src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/clock.svg" alt="Training plugin clock image">  <?php echo $lesson->rtotal_hrs; ?> Hours
                                                        </div>
                                                    </div>
                                                                                                   
                                                    <div class="full_descrp texteditor">

                                                                <section class="smallinfo">
                                                                    <?php echo limit_text(html_entity_decode($lesson->rdescription), 30); ?>
                                                                </section>
                                                                <section class="largeinofinfo">
                                                                    <?php echo full_text(html_entity_decode($lesson->rdescription)); ?>
                                                                </section>

                                                    </div>
                                                    <div class="submit_buttons rtr-d-inline-flex">
                                                        <a class="sub_btn <?php
                                                        if ($user_id > 0) {
                                                            echo 'markresource';
                                                        }
                                                        ?> resource_<?php echo $lesson->resource_id; ?>" data-page="course" data-lesson-title="<?php echo $lesson->title; ?>" data-exercise="<?php echo $lesson->rtitle; ?>" data-buttontype="<?php echo $lesson->button_type; ?>" data-status = "<?php echo $txtmarked; ?>" data-attr="<?php echo $lesson->resource_id; ?>" href="<?php
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
                                                                   echo 'login to mark';
                                                               }
                                                               ?></a>
                                                    </div>                                                              </div>
                                            <?php } ?>

                                            <?php
                                            $flg++;
                                            $inc++;

                                            $oldlessid = $lesson->id;
                                            // end of lesson
                                            $cmpId = isset($innerarr[$flg]->id) ? $innerarr[$flg]->id : '';
                                            if ($cmpId != $oldlessid) {
                                                $inclesson++;
                                                ?>
                                            </div>                                            
                                        </div>
                                        <?php
                                    }

                                    $oldid = $lesson->module_id;
                                    // End od subheader ul
                                    $modId = isset($innerarr[$flg]->module_id) ? $innerarr[$flg]->module_id : '';
                                    if ($modId != $oldid) {

                                        $get_mod = get_project('module', $oldid, 'check');
                                        if (!empty($get_mod)) {

                                            $total_resources++;
                                            $clstop = '';
                                            $txtsum = 'Submit Project';
                                            $txtsumcls = '';
                                            if (isset($get_mod->links) && $get_mod->links != '') {
                                                $clstop = 'submittedproj';
                                                $txtsum = 'Submitted';
                                                $txtsumcls = 'linksumitted';
                                                $completed_resources++;
                                            }
                                            ?>

                                            <div class="sub_block blockcontent rtr-pe-767-0px <?php echo $clstop; ?>" id="proj<?php echo $get_mod->id; ?>">
                                                <header>
                                                    <span class="block_time">
                                                    <img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/clock.svg" alt="Training plugin clock image"> <?php echo $get_mod->total_hrs; ?>+ Hours
                                                    </span>
                                                    <h4 class="h4main"><?php echo $get_mod->title; ?></h4>

                                                </header>

                                                <div class="descrp_main">

                                                    <div class="full_descrp texteditor">
                                                        <div class="smallinfo">
                                                            <?php echo limit_text(html_entity_decode($get_mod->description), 30); ?>
                                                        </div>
                                                        <div class="largeinofinfo">
                                                            <?php echo full_text(html_entity_decode($get_mod->description)); ?>                                                
                                                        </div>

                                                    </div>

                                                    <div class="block_resources">
                                                        <div class="submit_buttons">
                                                            <a class="sub_btn submitproj rtr-btn <?php echo $txtsumcls; ?>" data-id="<?php echo $get_mod->id; ?>" href="javascript:;"><?php echo $txtsum; ?></a>
                                                        </div>

                                                        <div class="block_main">
                                                            <span class="block_left">
                                                            <img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/exercise_icon.svg" alt="Training plugin exercise image">
                                                            </span>
                                                            <div class="block_info">                                            
                                                                <div class="block_txt projlnk">

                                                                    <?php if (isset($get_mod->links) && $get_mod->links != '') { ?>

                                                                        <?php
                                                                        $linkssp = explode(",", $get_mod->links);

                                                                        foreach ($linkssp as $links) {
                                                                            echo "<a target='_blank' href='$links'>$links</a> <br/>";
                                                                        }
                                                                        ?>                                                            
                                                                    <?php } else {
                                                                        ?>
                                                                        <a target="_blank" href="javascript:;">Submit project for this module</a>
                                                                    <?php }
                                                                    ?>

                                                                </div>                                        
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>

                                            <?php
                                        }
                                        ?>
                                    </header>
                                </div>
                                <?php
                            }
                        }
                        ?>

                        <?php
                        if (!empty($get_project)) {
                            $total_resources++;
                            $clstop = '';
                            $txtsum = 'Submit Project';
                            $txtsumcls = '';
                            if (isset($get_project->links) && $get_project->links != '') {
                                $clstop = 'submittedproj';
                                $txtsum = 'Submitted';
                                $txtsumcls = 'linksumitted';
                                $completed_resources++;
                            }
                            ?>    

                            <div class="first_block blockcontent lastproj <?php echo $clstop; ?>" id="proj<?php echo $get_project->id; ?>">
                                <header>

                                    <span class="block_time">
                                    <img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/clock.svg" alt="Training plugin clock image"> <?php echo $get_project->total_hrs; ?>+ Hours
                                    </span>
                                    <h2 class="h2main"><?php echo $get_project->title; ?></h2>

                                    <div class="descrp_main">
                                        <?php echo html_entity_decode($get_project->description); ?>

                                        <div class="block_resources">
                                            <div class="submit_buttons">
                                                <a class="sub_btn submitproj rtr-btn <?php echo $txtsumcls; ?>" data-id="<?php echo $get_project->id; ?>" href="javascript:;"><?php echo $txtsum; ?></a>
                                            </div>

                                            <div class="block_main">
                                                <span class="block_left">
                                                <img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/exercise_icon.svg?>" alt="Training plugin exercise image">
                                                </span>
                                                <div class="block_info">                                                                                                    
                                                    <div class="lastfinal block_txt projlnk">
                                                        <?php if (isset($get_project->links) && $get_project->links != '') { ?>

                                                            <?php
                                                            $linkssp = explode(",", $get_project->links);

                                                            foreach ($linkssp as $links) {
                                                                echo "<a target='_blank' href='$links'>$links</a> <br/>";
                                                            }
                                                            ?>                                                            
                                                        <?php } else {
                                                            ?>
                                                            <a target="_blank" href="javascript:;">Complete final project</a>
                                                        <?php }
                                                        ?>
                                                    </div>                                        
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </header>
                            </div>

                            <?php
                        }

                        if ($completed_resources > 0) {
                            $percent = floor(($completed_resources / $total_resources) * 100);
                        }
                        ?>         

                        <input type="hidden" name="percent_bar" id="percent_bar" value="<?php echo $percent; ?>" />
                        <input type="hidden" name="total_resources" id="total_resources" value="<?php echo $total_resources; ?>" />
                        <input type="hidden" name="completed_resources" id="completed_resources" value="<?php echo $completed_resources; ?>" />

                    </div>                    

                </div>

            </div>
        </div>

            <!-- submit project box -->
            <div class="arrow_box submit_project" style="display: none;">
                <span class="close-btn">
                    <span class="rtr-glyphicon glyphicon-remove btnclospop" aria-hidden="true"></span>
                </span>
                <form enctype="multipart/form-data">
                    <div class="controls">
                        <div class="row">
                            <div class="col-sm-10">
                                <input type="text" name="project_links" placeholder="Project Links (Use comma to separate if multiple)" class="wk_project_link form-control " data-project="220">

                                <br/>
                                <input type="file"  name="responsedoc[]" id="responsedoc" multiple="true"><br/>
                                <div class="remove_project_ctn" style="display: none;">
                                    <a href="javascript:void(0)" class="remove_project_link" target="_blank">Remove link / Files</a>
                                </div>
                                <br/>
                                <a href="javascript:;" class="btn pink project-submit-btn sub_btn">Submit</a>
                            </div>

                        </div>

                    </div>
                </form>
            </div>
            <!-- submit project box -->

            <!-- Submit project Modal -->
            <div class="fixed_header cu-header-fx w-100 rtr-bg-body rtr-py-3 rtr-px-3" style="display: none;">
                <div class="rtr-container">

                    <div class="content_header rtr-d-flex rtr-flex-992-column">
                        <div class="rtr-col-lg-5 scroll-class-left">
                            <h2 class="h2main rtr-fs-4 rtr-mb-1 rtr-767-mb-2 "><?php echo $course->title; ?></h2>

                            <div class="content_info">
                                <h5>
                                    <?php $authorInfo = get_author_info($course->assigned_author_id); ?>

                                    <div class="info_inner rtr-d-flex rtr-align-items-center rtr-992-mb-10px">
                                        <div class="info_pic rtr-me-1">
                                            <img class="border rtr-rounded-50" src="<?php echo isset($authorInfo['profile_img']) && $authorInfo['profile_img'] != ""?$authorInfo['profile_img']:plugins_url("training/assets/images/blank.jpg"); ?>" style="height: 40px;width:40px;"/>
                                        </div>


                                        <?php if ($user_id > 0) { ?>
                                            <span style="text-transform: none;font-size: 15px;" rel="author"><?php echo ucwords($authorInfo['name']); ?></span><?php echo $authorInfo['post'] != ""?",":""; ?>

                                            <span style="text-transform: none;font-size: 15px;" class="fade_txt"> <?php echo ucwords($authorInfo['post']); ?></span>
                                        <?php } else { ?>
                                            <span style="text-transform: none;font-size: 15px;" rel="author">Login to your account</span>,
                                        <?php } ?>
                                    </div>


                                </h5>
                            </div>


                        </div>
                        <div class="rtr-col-lg-7 scroll-class-right">

                            <div class="rtr-row">
                                <div class="rtr-col-md-12 rtr-mb-2">
                                    <div class="share_out rtr-float-right rtr-fs-6 rtr-fw-500">
                                        <a class="rtr-text-dark" href="<?php echo get_author_info($course->assigned_author_id)['website']; ?>" target="_blank">Visit Author</a>
                                        <?php
                                        foreach ($toplinks as $link) {
                                            ?>

                                            <a class="rtr-text-dark" target="_blank" href="<?php echo $link->keyvalue; ?>">
                                                <?php echo $link->keyname; ?>
                                            </a>    		
                                            <?php
                                        }
                                        ?>                                
                                    </div>
                                </div>
                            </div>
                            <div class="rtr-row rtr-flex-767-column">
                                <div class="rtr-col-md-6 rtr-pe-2 rtr-767-mb-2 rtr-pe-767-0px">
                                    <div class="progress_inner">
                                        <div class="bar_info">
                                            <input type="hidden" value="<?php echo $percent; ?>" id="hiddenpercentage"/>
                                            <span class="mypercentage"><?php echo $percent; ?> % Complete</span>
                                            <div class="bar-progress">
                                                <div class="perdiv" class="bar wip" style="width:<?php echo $percent; ?>%"></div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="rtr-col-md-6 cu-res-out rtr-d-flex rtr-justify-content-between">
                                    <div class="rtr-col-md-6">
                                        <div class="resources_out">
                                        <img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/exercise_icon.svg" alt="Training plugin exercise image"><?php echo $course->total_resources; ?> Exercises
                                        </div>
                                    </div>
                                    <div class="rtr-col-md-6 rtr-d-flex rtr-justify-content-end">
                                        <div class="tme_out">
                                        <img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/clock.svg" alt="Training plugin clock image"> <?php echo $course->total_hrs; ?>+ Hours
                                        </div>
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

function get_author_info($author_id) {
    global $wpdb;
    $author_details = $wpdb->get_row(
            $wpdb->prepare(
                    "SELECT * from " . rtr_wpl_tr_authors() . " WHERE id = %d", $author_id
            ), ARRAY_A
    );

    if (!empty($author_details)) {
        return $author_details;
    }
    return '';
}
?>
