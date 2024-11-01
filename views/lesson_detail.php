<?php
/**
 * This File for frontend lesson.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;
?>

<div id="lesson-div">
    <?php
    include_once 'common.php';
    global $wpdb;

    $lesson_id = isset($_REQUEST['lesson_id']) ? intval($_REQUEST['lesson_id']) : 0;

    $lesson = $wpdb->get_row
    (
        $wpdb->prepare
        (
            "SELECT * FROM " . rtr_wpl_tr_lessons() . " WHERE id = %d",
            $lesson_id
        )
    );

    if (empty($lesson)) {
        die('Invalid Lesson');
    }

    $videos = $wpdb->get_row
    (
        $wpdb->prepare
        (
            "SELECT * FROM " . rtr_wpl_tr_media() . " WHERE lesson_id = %d AND type = 'video' ORDER BY created_dt DESC",
            $lesson_id
        )
    );


    $docs = $wpdb->get_results
    (
        $wpdb->prepare
        (
            "SELECT * FROM " . rtr_wpl_tr_media() . " WHERE lesson_id = %d AND type = 'document'",
            $lesson_id
        )
    );

    $helplinks = $wpdb->get_results
    (
        $wpdb->prepare
        (
            "SELECT * FROM " . rtr_wpl_tr_media() . " WHERE lesson_id = %d AND type = 'link'",
            $lesson_id
        )
    );

    $notes = $wpdb->get_results
    (
        $wpdb->prepare
        (
            "SELECT * FROM " . rtr_wpl_tr_lesson_notes() . " WHERE lesson_id = %d",
            $lesson_id
        )
    );


    $module = $wpdb->get_row
    (
        $wpdb->prepare
        (
            "SELECT * FROM " . rtr_wpl_tr_modules() . " WHERE id = %d",
            $lesson->module_id
        )
    );

    $module_id = $module->id;
    $course = $wpdb->get_row
    (
        $wpdb->prepare
        (
            "SELECT * FROM " . rtr_wpl_tr_courses() . " WHERE id = %d",
            $module->course_id
        )
    );

    $resources = $wpdb->get_results
    (
        $wpdb->prepare
        (
            "SELECT * FROM " . rtr_wpl_tr_resources() . " WHERE lesson_id = %d ORDER BY ord ASC",
            $lesson_id
        )
    );

    $base_url = site_url();
    $slug = RTR_WPL_PAGE_SLUG;

    $vidaddtxt = "Add Video";
    if (!empty($videos)) {
        $vidaddtxt = "Edit Video";
    }
    ?>
    <div class="contaninerinner">
        <h4>Manage Lesson - <?php echo $lesson->title; ?>
            <a href="admin.php?page=rtr_module_detail&module_id=<?php echo $module_id; ?>"
                class="rtr-btn rtr-btn-danger pull-right"><span class="rtr-glyphicon  " aria-hidden="true"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/back_arrow.svg" alt="Training plugin arrow image"></span>
                Back</a>
        </h4>

        <ol class="rtr-breadcrumb">
            <li class="rtr-breadcrumb-item"><a href="admin.php?page=trainingtool">Courses</a></li>
            <li class="rtr-breadcrumb-item"><a
                    href="admin.php?page=rtr_course_detail&course_id=<?php echo $course->id; ?>"><?php echo $course->title; ?></a></a>
            </li>
            <li class="rtr-breadcrumb-item"><a
                    href="admin.php?page=rtr_module_detail&module_id=<?php echo $module->id; ?>"><?php echo $module->title; ?></a></a>
            </li>
            <li class="rtr-breadcrumb-item active"><?php echo $lesson->title; ?></li>
        </ol>

        <div class="clearfix"></div>
        <div class="rtr-alert rtr-alert-info">
            <strong>Frontend URL: <a target='_blank'
                    href="<?php echo $base_url . '/' . $slug . "/?lesson_detail=" . $lesson_id; ?>"><?php echo $base_url . '/' . $slug . "/?lesson_detail=" . $lesson_id; ?></a></strong>
        </div>
        <input type="hidden" id="typematerial" name="typematerial" value="lesson" />
        <div class="rtr-panel rtr-panel-primary">
            <div class="rtr-pull-right">
                <a class="rtr-btn rtr-btn-success" onclick="openvideodialog();"
                    href="javascript:;"><?php echo $vidaddtxt; ?></a>
            </div>
            <div class="rtr-panel-heading">Video

            </div>
            <div class="rtr-panel-body">
                <div class="row">
                    <div class="videospace rtr-col-lg-12">
                        <?php
                        if (empty($videos)) {
                            echo "Video Not Added";
                        } else {
                            ?>
                            <div class="videotxt" style="display: none; visibility: hidden;"><?php echo $videos->path; ?>
                            </div>
                            <?php
                            echo html_entity_decode($videos->path);
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div>
        <div class="rtr-panel rtr-panel-primary">
            <div class="rtr-pull-right">
                <a class="rtr-btn rtr-btn-success rtr-create-exercise" onclick="reset_form(); open_modal('lesson_dialog');"
                    href="javascript:;">Create New Exercise</a>
                <a class="rtr-btn rtr-btn-warning reorder" href="javascript:;" data-type="resources"
                    data-id="<?php echo $lesson_id; ?>">Re-Order Exercises</a>
            </div>
            <div class="rtr-panel-heading">Exercises

            </div>
            <div class="rtr-panel-body">

                <table class="rtr-table rtr-table-bordered" id="data_resources">
                    <thead>
                        <tr>
                            <th >SNo</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Button type</th>
                            <th>Hours</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($resources as $resource) {
                            $title = $resource->title;
                            if (trim($resource->external_link) != '') {
                                $title = "$resource->title";
                            }
                            ?>
                            <tr class="rowmod" data-id="<?php echo $resource->id; ?>">
                                <td><?php echo $resource->ord; ?></td>
                                <td class="title" data-btn="<?php echo $resource->button_type; ?>"
                                    data-txt="<?php echo $resource->title; ?>"
                                    data-lnk="<?php echo $resource->external_link; ?>"><?php echo $title; ?></td>
                                <td class="text">
                                    <div style="display: none; visibility: hidden" class="textdiv">
                                        <?php echo html_entity_decode($resource->description); ?></div>
                                    <?php echo limit_text(html_entity_decode($resource->description), 10, false); ?>
                                </td>
                                <td><?php echo $resource->button_type == 'mark' ? 'Mark Complete' : 'Submit Project'; ?>

                                    <?php
                                    if ($resource->button_type == "submit") {
                                        ?>
                                        <div><a data-id="<?php echo $resource->id; ?>" class="sumitted_projs"
                                                href="javascript:;" title="View Submitted Projects">View Submissions</a></div>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td class="hrs"><?php echo $resource->total_hrs; ?></td>
                                <td class="actiontd">
                                    <a data-id="<?php echo $resource->id; ?>" class="editres rtr-btn rtr-btn-primary"
                                        href="javascript:;" title="Edit Exercise"><span class="rtr-glyphicon"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/edit.svg" alt="Training course edit image">
                                        </span> </a>
                                    <a class="rtr-btn rtr-btn-success"
                                        href="admin.php?page=rtr_resource_detail&resource_id=<?php echo $resource->id; ?>"
                                        title="Manage Exercise"><span class="rtr-glyphicon   "><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/manage.svg" alt="Training course manage image"></span></a>
                                    <a href="javascript:;" data-id="<?php echo $resource->id; ?>"
                                        class="deleteres rtr-btn rtr-btn-danger" title="Delete Exercise"><span class="rtr-glyphicon"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/trash.svg" alt="Training course trash image"></span></a>

                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rtr-panel rtr-panel-primary">
            <div class="rtr-pull-right">
                <a class="rtr-btn rtr-btn-success" onclick="reset_form(); open_modal('note_dialog');" href="javascript:;">Create
                    New Note</a>
            </div>
            <div class="rtr-panel-heading">Notes

            </div>
            <div class="rtr-panel-body">
                <table class="rtr-table rtr-table-bordered" id="data_notes">
                    <thead>
                        <tr>
                            <th>SNo</th>
                            <th>Note</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $f = 0;
                        foreach ($notes as $note) {
                            $f++;
                            ?>
                            <tr class="rowmodnote" data-id="<?php echo $note->id; ?>">
                                <td><?php echo $f; ?></td>
                                <td class="title">
                                    <div style="display: none; visibility: hidden" class="notetext">
                                        <?php echo html_entity_decode($note->note); ?></div>
                                    <?php echo limit_text(html_entity_decode($note->note), 10, false); ?>
                                </td>
                                <td><?php echo date("Y-m-d", strtotime($note->created_dt)); ?></td>
                                <td class="actiontd">
                                    <a href="javascript:;" data-id="<?php echo $note->id; ?>"
                                        class="editnote rtr-btn rtr-btn-primary" title="Edit Note"><span class="rtr-glyphicon"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/edit.svg" alt="Training course edit image">
                                        </span></a>
                                    <a href="javascript:;" data-id="<?php echo $note->id; ?>"
                                        class="deletenote rtr-btn rtr-btn-danger" title="Delete Note"><span class="rtr-glyphicon"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/trash.svg" alt="Training course trash image"></span></a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>

        <div class="row cust-help-linke">
            <div class="col-lg-6 cust-help-link-left">
                <div class="rtr-panel rtr-panel-primary">
                    <div class="rtr-pull-right">
                        <a class="rtr-btn rtr-btn-success" onclick="reset_form(); open_modal('help_dialog');"
                            href="javascript:;">Add Help Link</a>
                    </div>
                    <div class="rtr-panel-heading">Help Links

                    </div>
                    <div class="rtr-panel-body">
                        <table class="rtr-table rtr-table-bordered" id="data_links">
                            <thead>
                                <tr>
                                    <th>SNo</th>
                                    <th>Link</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $f = 0;
                                foreach ($helplinks as $helplink) {
                                    $f++;
                                    ?>

                                    <tr class="rowmodlink" data-id="<?php echo $helplink->id; ?>">
                                        <td><?php echo $f; ?></td>
                                        <td class="title" data-link="<?php echo $helplink->path; ?>"
                                            data-title="<?php echo $helplink->extra_info; ?>">
                                            <a target="_blank"
                                                href="<?php echo $helplink->path; ?>"><?php echo $helplink->extra_info; ?></a>
                                        </td>
                                        <td class="actiontd">
                                            <a href="javascript:;" data-id="<?php echo $helplink->id; ?>"
                                                class="editlink rtr-btn rtr-btn-primary" title="Edit Link"><span
                                                    class="rtr-glyphicon"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/edit.svg" alt="Training course edit image">
                                                </span></a>
                                            <a href="javascript:;" data-id="<?php echo $helplink->id; ?>"
                                                class="deletelink rtr-btn rtr-btn-danger" title="Delete Link"><span
                                                    class="rtr-glyphicon"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/trash.svg" alt="Training course trash image"></span></a>
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
            <div class="col-lg-6 cust-help-link-right">
                <div class="rtr-panel rtr-panel-primary">
                    <div class="rtr-pull-right">
                        <a class="rtr-btn rtr-btn-success" onclick="reset_form(); open_modal('download_dialog');"
                            href="javascript:;">Upload New Document</a>
                    </div>
                    <div class="rtr-panel-heading">Document & Files</div>
                    <div class="rtr-panel-body">
                        <table class="rtr-table rtr-table-bordered" id="data_docs">
                            <thead>
                                <tr>
                                    <th>SNo</th>
                                    <th>Path</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $f = 0;
                                foreach ($docs as $doc) {
                                    $f++;
                                    ?>
                                    <tr class="rowmoddoc" data-id="<?php echo $doc->id; ?>">
                                        <td><?php echo $f; ?></td>
                                        <td class="title">
                                            <a download href="<?php echo $doc->path; ?>"><?php echo $doc->extra_info; ?></a>
                                        </td>
                                        <td class="actiontd">
                                            <a href="javascript:;" data-id="<?php echo $doc->id; ?>"
                                                class="deletedoc rtr-btn rtr-btn-danger" title="Delete Document"><span
                                                    class="rtr-glyphicon"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/trash.svg" alt="Training course trash image"></span></a>
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
    </div>


    <div id="lesson_dialog" class="rtr-modal rtr-bs-modal rtr-fade">
        <div class="rtr-modal-dialog rtr-modal-lg">
            <div class="rtr-modal-content">
                <div class="rtr-modal-header">
                    <button type="button" class="rtr-close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title rtr-fs-4 rtr-m-0">Exercise</h4>
                </div>
                <div class="rtr-modal-body">

                    <form action="#" method="post" id="addresource" name="addresource" class="form-horizontal">

                        <input type="hidden" id="typerescreated" name="typerescreated" value="direct" />
                        <input type="hidden" id="course_id" name="course_id" value="<?php echo $course->id; ?>" />
                        <input type="hidden" id="module_id" name="module_id" value="<?php echo $module_id; ?>" />
                        <input type="hidden" id="lesson_id" name="lesson_id" value="<?php echo $lesson->id; ?>" />

                        <input type="hidden" id="resid" name="resid" value="0" />
                        <div class="rtr-form-group">
                            <label for="title" class="rtr-col-lg-2 control-label">Name* :</label>
                            <div class="rtr-col-lg-8 cu-new-ex-in">
                                <input type="text" required class="rtr-form-control" id="title" name="title"
                                    placeholder="Title">
                            </div>
                        </div>
                        <div class="rtr-form-group">
                            <label for="title" class="rtr-col-lg-2 control-label">Time (Hrs) * :</label>
                            <div class="rtr-col-lg-8 cu-new-ex-in">
                                <input type="number" required class="rtr-form-control" id="hours" name="hours"
                                    placeholder="Time to complete Exercise (Hrs)">
                            </div>
                        </div>
                        <div class="rtr-form-group">
                            <label for="title" class="rtr-col-lg-2 control-label">Button Type :</label>
                            <div class="rtr-col-lg-8 cu-new-ex-in">
                                <select class="rtr-form-control" name="button_type" id="button_type">
                                    <option value="mark">Mark Complete</option>
                                    <option value="submit">Submit Project</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="display: none; visibility: hidden;">
                            <label for="title" class="rtr-col-lg-2 control-label">External Link :</label>
                            <div class="rtr-col-lg-8 cu-new-ex-in">
                                <input type="text" class="rtr-form-control" id="link" name="link"
                                    placeholder="External Link">
                            </div>
                        </div>

                        <div class="rtr-form-group">
                            <label for="cat_name" class="rtr-col-lg-2 control-label">Description :</label>
                            <div class="rtr-col-lg-8 wpeditor">
                                <?php
                                wp_editor("", $id = 'description', $prev_id = 'title', $media_buttons = false, $tab_index = 1);
                                ?>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="rtr-modal-footer">
                    <button type="button" onclick="submitres();" class="btnupdt rtr-btn rtr-btn-primary">Submit</button>
                    <button type="button" data-dismiss="modal" class="rtr-btn rtr-lesson-dialog-cancel">Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <div id="video_dialog" class="rtr-modal rtr-bs-modal rtr-fade modealrealodonclose">
        <div class="rtr-modal-dialog rtr-modal-lg">
            <div class="rtr-modal-content">
                <div class="rtr-modal-header">
                    <button type="button" class="rtr-close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="rtr-modal-title">Video</h4>
                </div>
                <div class="rtr-modal-body">

                    <form action="#" method="post" id="addvideo" name="addvideo" class="form-horizontal">

                        <input type="hidden" id="lesson_id" name="lesson_id" value="<?php echo $lesson->id; ?>" />

                        <div class="rtr-form-group">
                            <label for="title" class="rtr-col-lg-2 control-label">Embed Code * :</label>
                            <div class="rtr-col-lg-8 cu-new-ex-in">
                                <textarea rows="8" type="text" required class="rtr-form-control" id="embedcode"
                                    name="embedcode" placeholder="Embed Your Code Here"></textarea>
                                <small>
                                    <i>Height should be 500 px. Remove width from embede code, it automatically get
                                        width.</i>
                                    For eg:
                                    <div>
                                        <code><?php echo htmlspecialchars('<script charset="ISO-8859-1" src="//fast.wistia.com/assets/external/E-v1.js" async></script><div class="wistia_embed wistia_async_j38ihh83m5" style="height:500px;"></div>'); ?></code>
                                    </div>
                                </small>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="rtr-modal-footer">
                    <button type="button" onclick='jQuery("#addvideo").submit();'
                        class="btnupdt rtr-btn rtr-btn-primary">Submit</button>
                    <button type="button" data-dismiss="modal" class="rtr-btn rtr-video-dialog-cancel">Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <div id="download_dialog" class="rtr-modal rtr-bs-modal rtr-fade modealrealodonclose">
        <div class="rtr-modal-dialog">
            <div class="rtr-modal-content">
                <div class="rtr-modal-header">
                    <button type="button" class="rtr-close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="rtr-modal-title">Upload Documents & Files</h4>
                </div>
                <div class="rtr-modal-body">

                    <form action="#" method="post" id="adddoc" name="adddoc" class="form-horizontal">

                        <input type="hidden" id="lessonid" name="lessonid" value="<?php echo $lesson->id; ?>" />

                        <div class="form-group rtr-col-lg-12">
                            <div id="mediainfo">

                            </div>

                        </div>

                        <div class="form-group rtr-col-lg-12">
                            <div class="">
                                <!--input type="file" class="rtr-form-control" name="responsedoc[]" id="responsedoc" multiple="true" /-->
                                <input type="button" id="mediauploadbtn" class="rtr-btn rtr-btn-info" value="Choose Files"
                                    name="mediauploadbtn" />
                            </div>

                        </div>
                        <div class="clear"></div>
                        <ul id="fileList" class="list-group">
                            <li class="rtr-list-group-item">No Files Selected</li>
                        </ul>
                    </form>

                </div>
                <div class="rtr-modal-footer">
                    <!--button type="button" onclick='uploaddocs();' class="rtr-btn rtr-btn-primary" >Upload</button-->
                    <button type="button" class="rtr-btn rtr-btn-primary document-upload">Upload</button>
                    <button type="button" data-dismiss="modal" class="rtr-btn rtr-download-dialog-cancel">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div id="note_dialog" class="rtr-modal rtr-bs-modal rtr-fade modealrealodonclose">
        <div class="rtr-modal-dialog rtr-modal-lg">
            <div class="rtr-modal-content">
                <div class="rtr-modal-header">
                    <button type="button" class="rtr-close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="rtr-modal-title">Notes</h4>
                </div>
                <div class="rtr-modal-body">

                    <form action="#" method="post" id="addnote" name="addnote" class="form-horizontal">

                        <input type="hidden" id="lesonid" name="lesonid" value="<?php echo $lesson_id; ?>" />
                        <input type="hidden" id="noteid" name="noteid" value="0" />
                        <div class="rtr-form-group wpeditor rtr-d-flex">
                            <label for="title" class="rtr-col-lg-2 control-label">Enter Note * :</label>
                            <div class="rtr-col-lg-10">

                                <?php wp_editor("", $id = 'descriptionnote', $prev_id = 'title', $media_buttons = false, $tab_index = 1); ?>
                            </div>

                        </div>

                    </form>

                </div>
                <div class="rtr-modal-footer">
                    <button type="button" onclick='jQuery("#addnote").submit();'
                        class="btnupdt rtr-btn rtr-btn-primary">Submit</button>
                    <button type="button" data-dismiss="modal" class="rtr-btn rtr-note-dialog-cancel">Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <div id="help_dialog" class="rtr-modal rtr-bs-modal rtr-fade modealrealodonclose">
        <div class="rtr-modal-dialog ">
            <div class="rtr-modal-content">
                <div class="rtr-modal-header">
                    <button type="button" class="rtr-close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="rtr-modal-title">Help Link</h4>
                </div>
                <div class="rtr-modal-body">

                    <form action="#" method="post" id="addhlink" name="addhlink" class="form-horizontal">

                        <input type="hidden" id="lessid" name="lessid" value="<?php echo $lesson_id; ?>" />
                        <input type="hidden" id="helpnkid" name="helpnkid" value="0" />
                        <div class="rtr-form-group">
                            <label for="title" class="rtr-col-lg-2 control-label">Title * :</label>
                            <div class="rtr-col-lg-8 cu-new-ex-in">
                                <input type="text" required class="rtr-form-control" id="linktitle" name="linktitle"
                                    placeholder="Enter Link Title" />
                            </div>

                        </div>
                        <div class="rtr-form-group">
                            <label for="title" class="rtr-col-lg-2 control-label">Link * :</label>
                            <div class="rtr-col-lg-8 cu-new-ex-in">
                                <input type="text" required url='true' class="rtr-form-control" id="linkurl" name="linkurl"
                                    placeholder="Enter Link Here" />
                            </div>

                        </div>

                    </form>

                </div>
                <div class="rtr-modal-footer">
                    <button type="button" onclick='jQuery("#addhlink").submit();'
                        class="btnupdt rtr-btn rtr-btn-primary">Submit</button>
                    <button type="button" data-dismiss="modal" class="rtr-btn rtr-help-dialog-cancel">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div id="project_summitted" class="rtr-modal rtr-bs-modal rtr-fade">
        <div class="rtr-modal-dialog rtr-modal-lg">
            <div class="rtr-modal-content cu-work-sub">
                <div class="rtr-modal-header">
                    <button type="button" class="rtr-close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="rtr-modal-title">Work Submissions By Users</h4>
                </div>
                <div class="rtr-modal-body">
                    <div id="listusersdiv">
                        <div class="loadergif">
                            <img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL; ?>/assets/css/images/loading.gif" />
                        </div>
                        <table class="rtr-table rtr-table-bordered tbluserdv" style="display: none;">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Links</th>
                                    <th>Submitted Files</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>