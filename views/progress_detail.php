<?php
/**
 * This File for learning progress of any user.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

include_once 'common.php';
$user_id = isset($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0;
$c_id = get_current_user_id();
$user = new WP_User($c_id);
$u_role = $user->roles[0];

$course_id = isset($_REQUEST['course_id']) ? intval($_REQUEST['course_id']) : 0;
$show = isset($_REQUEST['show']) ? trim(esc_attr($_REQUEST['show'])) : '';
if ($user_id > 0 && $course_id > 0) {

    if ($show == "progress") {

        global $wpdb;
        $current_user = get_user_by('id', $user_id);

        $usertbl = $wpdb->prefix . "users";

        $course = $wpdb->get_row
                (
                $wpdb->prepare
                        (
                        "SELECT id,title FROM " . rtr_wpl_tr_courses() . " WHERE id = %d", $course_id
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
        $courseCreatedBy = isset($course->created_by) ? $course->created_by : '';

        $user = get_user_by('id', $courseCreatedBy);

        $percent = 0;
        $completed_resources = 0;
        $total_resources = 0;

        if (empty($innerarr)) {
            ?>

            <div class="main-section">
                <div class="container">  
                    <div class="row">
                        <div class="rtr-col-sm-12">

                            <div class="rtr-alert rtr-alert-success">
                                <strong>Note: </strong>
                                No Data Added Yet In this Course
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <?php
        } else {

            $globalmodid = $innerarr[0]->module_id;
            ?>

            <div class="main-section singlepagecourse detailprogress rtr-mt-2">
                <h4> <?php echo $course->title; ?> Progress</h4>
                <ol class="rtr-breadcrumb rtr-align-items-center rtr-rounded-1">
                    <li class="rtr-breadcrumb-item"><a href="admin.php?page=rtr_progress_detail">Users List</a></li>
                    <li class="rtr-breadcrumb-item"><a href="admin.php?page=rtr_progress_detail&user_id=<?php echo $user_id; ?>">Course List</a></li>
                    <li class="rtr-breadcrumb-item active">Progress</li>
                    <a class='rtr-btn rtr-btn-danger rtr-pull-right rtr-mt-0 rtr-ms-auto ' href='admin.php?page=rtr_progress_detail&user_id=<?php echo $user_id; ?>'><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/back_arrow.svg" alt="Training plugin arrow image"> Back</a>
                </ol>
                <input type="hidden" id="uidused" name="uidused" value="<?php echo $user_id; ?>" />
                <div class="rtr-col-sm-12 rtr-mt-2">
                        <div>
                            <div class="content_main rtr-p-3 rtr-bg-light rtr-mt-3 cu-process-sec rtr-rounded-1">
                                <div class="progress_outer rtr-d-flex rtr-justify-content-between rtr-flex-992-column">

                                    <h4 class="up_title rtr-col-7">Course Progress of User: <?php echo $current_user->data->display_name; ?> [<?php echo $current_user->data->user_email; ?>]</h4>
                                    <div class="progress_inner rtr-col-4">
                                        <div class="bar_info">
                                            <span><label class="perint"><?php echo $percent; ?></label></span>
                                            <div class="bar-progress">
                                                <div class="perdiv" style="width:<?php echo $percent; ?>%" class="bar wip"></div>
                                            </div>

                                        </div>
                                    </div>

                                </div>

                                <div class="rtr-col-sm-12 innerdata">

                                    <?php
                                    $globalmodid = $innerarr[0]->module_id;
                                    $flg = 0;
                                    $inc = 1;
                                    $headerpt = 0;
                                    $inclesson = 1;


                                    $globallesson = $innerarr[0]->id;

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
                                                <div class="rtr-flex-wrap rtr-d-flex rtr-justify-content-between rtr-mt-4 border-bottom rtr-pb-3">
                                                <h2 class="h2main h2main rtr-fs-4 rtr-m-0"><?php echo $tit; ?></h2>
                                                <span class="rtr-fs-5 rtr-lh-0 block_time rtr-d-flex rtr-align-items-center">
                                                    <img class="rtr-me-1" src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/clock.svg"> <?php echo $lesson->mtotal_hrs; ?>+ Hours
                                                    </span>
                                                    <div class="w-100">
                                                        <p>
                                                        <div class="smallinfo">
                                                            <?php echo html_entity_decode($lesson->mdescription); ?>
                                                        </div>  
                                                        </p>
                                                    </div>


                                                </div>

                                                    
                                                    
                                                    

                                                    <?php
                                                }


                                                if ($islesson == 1) {

                                                    $titt = $lesson->title;
//                                                            
                                                    $lessonurl = site_url() . "/" . RTR_WPL_PAGE_SLUG . "?lesson_detail=" . $lesson->id;
                                                    $titt = "<a target='_blank' href='$lessonurl'>$titt</a>";
                                                    ?>

                                                    <div class="sub_block blockcontent" id="lesson<?php echo $lesson->id; ?>">
                                                        <header class="rtr-d-flex rtr-justify-content-between rtr-mt-4 border-bottom rtr-flex-wrap">
                                                        <h4 class="h4main rtr-m-0"><?php echo $titt; ?></h4>
                                                            <span class="rtr-fs-5 rtr-lh-0 block_time rtr-d-flex rtr-align-items-center">
                                                            <img class="rtr-me-1" src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/clock.svg" alt="Training plugin clock image"><?php echo $lesson->total_hrs; ?>+ Hours
                                                            </span>
                                                            <div class="">
                                                            <div class="smallinfo">
                                                                <?php echo limit_text(html_entity_decode($lesson->description), 30); ?>                                           
                                                            </div>
                                                            <div class="largeinofinfo">
                                                                <?php echo full_text(html_entity_decode($lesson->description)); ?>                                                
                                                            </div>
                                                            </div>
                                                            

                                                        </header>

                                                        <div class="descrp_main texteditor">
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
                                                                    $hassubmitted = get_project_links_back($user_id, $lesson->resource_id);
                                                                }
                                                            }


                                                            $total_resources++;
                                                            ?>
                                                            <div class="block_resources <?php echo $classmsrk; ?>" id="resource_<?php echo $lesson->resource_id; ?>">
                                                              

                                                                <div class="block_main">
                                                                    <span class="block_left">
                                                                        <i class="icon-scales"></i>
                                                                    </span>
                                                                    <div class="block_info">
                                                                        <div class="rtr-d-flex rtr-justify-content-between rtr-mt-4 border-bottom rtr-pb-2 rtr-mb-2">
                                                                    <div class="block_txt rtr-fs-4">
                                                                            <?php echo $rtitt; ?>
                                                                    </div>
                                                                        <div class="rtr-fs-5 sub-block_time rtr-lh-0 rtr-d-flex rtr-align-items-center">
                                                                        <img class="rtr-me-1" src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/clock.svg" alt="Training plugin clock image"> <?php echo $lesson->rtotal_hrs; ?> Hours
                                                                        </div>
                                                                        </div>    

                                                                       
                                                                        <div class="full_descrp texteditor">
                                                                            <div class="smallinfo">
                                                                                <?php echo limit_text(html_entity_decode($lesson->rdescription), 30); ?>
                                                                            </div>
                                                                            <div class="largeinofinfo">
                                                                                <?php echo full_text(html_entity_decode($lesson->rdescription)); ?>                                                
                                                                            </div>

                                                                        </div>
                                                                        <div class="submit_buttons">
                                                                    <a class="sub_btn markresource rtr-btn" data-buttontype="<?php echo $lesson->button_type; ?>" data-status = "<?php echo $txtmarked; ?>" data-attr="<?php echo $lesson->resource_id; ?>" href="javascript:;"><?php echo $marktxt; ?></a>
                                                                </div>

                                                                    </div>
                                                                </div>


                                                            </div>
                                                        <?php } ?>

                                                        <?php
                                                        $flg++;
                                                        $inc++;

                                                        $oldlessid = $lesson->id;
                                                        // end of lesson
                                                        $crseID = isset($innerarr[$flg]->id) ? $innerarr[$flg]->id : '';
                                                        if ($crseID != $oldlessid) {
                                                            $inclesson++;
                                                            ?>
                                                        </div>                                            
                                                    </div>
                                                    <?php
                                                }

                                                $oldid = $lesson->module_id;
                                                // End od subheader ul
                                                $arrModId = isset($innerarr[$flg]->module_id) ? $innerarr[$flg]->module_id : '';
                                                if ($arrModId != $oldid) {

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


                                                        <div class="sub_block blockcontent<?php echo $clstop; ?>" id="proj<?php echo $get_mod->id; ?>">
                                                            <header class="rtr-d-flex rtr-justify-content-between rtr-mt-4 border-bottom">
                                                            <h4 class="h4main"><?php echo $get_mod->title; ?></h4>
                                                                <span class="block_time">
                                                                    <i class="icon-time"></i> <?php echo $get_mod->total_hrs; ?>+ Hours
                                                                </span>
                                                                

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
                                                                        <a class="sub_btn submitproj <?php echo $txtsumcls; ?>" data-id="<?php echo $get_mod->id; ?>" href="javascript:;"><?php echo $txtsum; ?></a>
                                                                    </div>

                                                                    <div class="block_main">
                                                                        <span class="block_left">
                                                                            <i class="icon-scales"></i>
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
                                                    <i class="icon-time"></i> <?php echo $get_project->total_hrs; ?>+ Hours
                                                </span>
                                                <h2 class="h2main"><?php echo $get_project->title; ?></h2>

                                                <div class="descrp_main">
                                                    <?php echo html_entity_decode($get_project->description); ?>

                                                    <div class="block_resources">
                                                        <div class="submit_buttons">
                                                            <a class="sub_btn submitproj <?php echo $txtsumcls; ?>" data-id="<?php echo $get_project->id; ?>" href="javascript:;"><?php echo $txtsum; ?></a>
                                                        </div>

                                                        <div class="block_main">
                                                            <span class="block_left">
                                                                <i class="icon-scales"></i>
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


                <?php
            }
        } elseif ($show == "data") {
            include_once 'course_data.php';
        } else {
            
        }
    } else {
        include_once 'course_progress.php';
    }
    ?>
