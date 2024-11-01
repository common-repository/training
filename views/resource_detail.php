<?php
/**
 * This File for adding videos & Docs.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

include_once 'common.php';
global $wpdb;

$resource_id = isset($_REQUEST['resource_id']) ? intval($_REQUEST['resource_id']) : 0;
$resource = $wpdb->get_row
(
    $wpdb->prepare
    (
        "SELECT * FROM " . rtr_wpl_tr_resources() . " WHERE id = %d",
        $resource_id
    )
);

if (empty($resource)) {
    die('Invalid Exercise');
}

$lesson_id = $resource->lesson_id;

$lesson = $wpdb->get_row
(
    $wpdb->prepare
    (
        "SELECT * FROM " . rtr_wpl_tr_lessons() . " WHERE id = %d",
        $lesson_id
    )
);



$videos = $wpdb->get_row
(
    $wpdb->prepare
    (
        "SELECT * FROM " . rtr_wpl_tr_media() . " WHERE resource_id = %d AND type = 'video' ORDER BY created_dt DESC",
        $resource_id
    )
);

$docs = $wpdb->get_results
(
    $wpdb->prepare
    (
        "SELECT * FROM " . rtr_wpl_tr_media() . " WHERE resource_id = %d AND type = 'document'",
        $resource_id
    )
);

$helplinks = $wpdb->get_results
(
    $wpdb->prepare
    (
        "SELECT * FROM " . rtr_wpl_tr_media() . " WHERE resource_id = %d AND type = 'link'",
        $resource_id
    )
);

$notes = $wpdb->get_results
(
    $wpdb->prepare
    (
        "SELECT * FROM " . rtr_wpl_tr_lesson_notes() . " WHERE resource_id = %d",
        $resource_id
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


$base_url = site_url();
$slug = RTR_WPL_PAGE_SLUG;

$vidaddtxt = "Add Video";
if (!empty($videos)) {
    $vidaddtxt = "Edit Video";
}
?>
<div class="contaninerinner">
    <h4>Exercise - <?php echo $resource->title; ?> [ Manage Resources ]
        <a href="admin.php?page=rtr_lesson_detail&lesson_id=<?php echo $lesson_id; ?>"
            class="rtr-btn rtr-btn-danger pull-right"><span class="rtr-glyphicon  " aria-hidden="true"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/back_arrow.svg" alt="Training plugin arrow image"></span>
            Back</a>
    </h4>

    <ol class="rtr-breadcrumb">
        <li class="rtr-breadcrumb-item"><a href="admin.php?page=trainingtool">Courses</a></li>
        <li class="rtr-breadcrumb-item"><a
                href="admin.php?page=rtr_course_detail&course_id=<?php echo $course->id; ?>"><?php echo $course->title; ?></a>
        </li>
        <li class="rtr-breadcrumb-item"><a
                href="admin.php?page=rtr_module_detail&module_id=<?php echo $module->id; ?>"><?php echo $module->title; ?></a>
        </li>
        <li class="rtr-breadcrumb-item"><a
                href="admin.php?page=rtr_lesson_detail&lesson_id=<?php echo $lesson->id; ?>"><?php echo $lesson->title; ?></a>
        </li>
        <li class="rtr-breadcrumb-item active"><?php echo $resource->title; ?></li>
    </ol>

    <div class="clearfix"></div>
    <div class="rtr-alert rtr-alert-info">
        <strong>Frontend URL: <a target='_blank'
                href="<?php echo $base_url . '/' . $slug . "/?exercise_detail=" . $resource_id; ?>"><?php echo $base_url . '/' . $slug . "/?exercise_detail=" . $resource_id; ?></a></strong>
    </div>
    <input type="hidden" id="typematerial" name="typematerial" value="resource" />
    <div class="rtr-panel rtr-panel-primary">
        <div class="rtr-pull-right">
            <a class="rtr-btn rtr-btn-success" onclick="openvideodialog();" href="javascript:;"><?php echo $vidaddtxt; ?></a>
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
                        <div class="videotxt" style="display: none; visibility: hidden;"><?php echo $videos->path; ?></div>
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
            <a class="rtr-btn rtr-btn-success" onclick="reset_form(); open_modal('note_dialog');" href="javascript:;">Create New
                Note</a>
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
                                    <?php echo html_entity_decode($note->note); ?>
                                </div>
                                <?php echo limit_text(html_entity_decode($note->note), 10, false); ?>
                            </td>
                            <td><?php echo date("Y-m-d", strtotime($note->created_dt)); ?></td>
                            <td class="actiontd">
                                <a href="javascript:;" data-id="<?php echo $note->id; ?>" class="editnote rtr-btn rtr-btn-primary"
                                    title="Edit Note">Edit</a>
                                <a href="javascript:;" data-id="<?php echo $note->id; ?>" class="deletenote rtr-btn rtr-btn-danger"
                                    title="Delete Note">Delete</a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

    <div class="row cust-help-linke rtr-d-flex">
        <div class="rtr-col-lg-6 cust-help-link-left rtr-pe-2">
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
                                            class="editlink rtr-btn rtr-btn-primary" title="Edit Link">Edit</a>
                                        <a href="javascript:;" data-id="<?php echo $helplink->id; ?>"
                                            class="deletelink rtr-btn rtr-btn-danger" title="Delete Link">Delete</a>

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
        <div class="rtr-col-lg-6 cust-help-link-right rtr-ps-2">
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
                                        <a download
                                            href="<?php echo RTR_WPL_COUNT_PLUGIN_URL . "/" . $doc->path; ?>"><?php echo $doc->extra_info; ?></a>
                                    </td>
                                    <td class="actiontd">
                                        <a href="javascript:;" data-id="<?php echo $doc->id; ?>"
                                            class="deletedoc rtr-btn rtr-btn-danger" title="Delete Document">Delete</a>
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


<div id="video_dialog" class="rtr-modal rtr-bs-modal rtr-fade modealrealodonclose">
    <div class="rtr-modal-dialog rtr-modal-lg">
        <div class="rtr-modal-content">
            <div class="rtr-modal-header">
                <button type="button" class="rtr-close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title rtr-fs-4 rtr-m-0">Video</h4>
            </div>
            <div class="rtr-modal-body">

                <form action="#" method="post" id="addvideo" name="addvideo" class="form-horizontal">

                    <input type="hidden" id="resource_id" name="resource_id" value="<?php echo $resource->id; ?>" />


                    <div class="rtr-form-group">
                        <label for="title" class="rtr-col-lg-2 control-label">Embed Code * :</label>
                        <div class="rtr-col-lg-8 cu-new-ex-in">
                            <textarea rows="8" type="text" required class="rtr-form-control" id="embedcode" name="embedcode"
                                placeholder="Embed Your Code Here"></textarea>
                            <small><i>Height should be 500 px. Remove width from embede code, it automatically get
                                    width.
                                </i>
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

                    <input type="hidden" id="resourceid" name="resourceid" value="<?php echo $resource->id; ?>" />

                    <div class="form-group rtr-col-lg-12">
                        <div id="mediainfo">

                        </div>

                    </div>

                    <div class="form-group rtr-col-lg-12">
                        <div class="">
                            <!--input type="file" class="rtr-form-control" name="responsedoc[]" id="responsedoc" multiple="true" /-->
                            <input type="button" id="mediauploadbtn" class="  rtr-btn rtr-btn-info" value="Choose Files"
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

                    <input type="hidden" id="resourid" name="resourid" value="<?php echo $resource_id; ?>" />
                    <input type="hidden" id="noteid" name="noteid" value="0" />
                    <div class="rtr-form-group wpeditor">
                        <label for="title" class="rtr-col-lg-2 control-label">Enter Note * :</label>
                        <div class="rtr-col-lg-8">

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


<div id="help_dialog" class="rtr-modal rtr-bs-modal frtr-ade modealrealodonclose">
    <div class="rtr-modal-dialog ">
        <div class="rtr-modal-content">
            <div class="rtr-modal-header">
                <button type="button" class="rtr-close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="rtr-modal-title">Help Link</h4>
            </div>
            <div class="rtr-modal-body">

                <form action="#" method="post" id="addhlink" name="addhlink" class="form-horizontal">

                    <input type="hidden" id="resid" name="resid" value="<?php echo $resource_id; ?>" />
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