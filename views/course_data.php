<?php
/**
 * This File for aditional data like videos and images.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

$total_courses = $wpdb->get_results
        (
        $wpdb->prepare
                (
                "select distinct course_id FROM " . rtr_wpl_tr_resource_status() . " WHERE user_id = %d", $user_id
        )
);

$course_ids = array();
if (count($total_courses) > 0) {
    foreach ($total_courses as $inx => $tc) {
        $course_ids[] = $tc->course_id;
    }
}

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

$assets = array();
$images_path = array();
if (count($innerarr) > 0) {

    foreach ($innerarr as $lesson) {

        $hassubmitted = get_project_links_back($user_id, $lesson->resource_id);

        $doc_file = $wpdb->get_results(
                $wpdb->prepare(
                        "select * from " . rtr_wpl_tr_projects() . " where resource_id = %d and user_id = %d", $lesson->resource_id, $user_id
                )
        );
        // print_r($doc_file);
        $files = '';
        $links = '';
        foreach ($doc_file as $key => $value) {
            $image_arr = unserialize($value->doc_files);
            $link_arr = unserialize($value->links);
            // print_r($link_arr);
            $i = 1;
            foreach($link_arr as $link){
                $links .= "<b>$i</b>.<a href = '". $link . "'> $link</a>.<br/>";
                $i++;
            }
            $l = 1;
            $img['coure_image_' . $lesson->resource_id] = [];
            foreach ($image_arr as $image) {
                if (!empty(trim($image))) {
                    $img['coure_image_' . $lesson->resource_id][] = $image;
                    $imgSepArray = explode("/", $image);
                    $getName = $imgSepArray[count($imgSepArray) - 1];
                    $files .= "<b>$l</b>. <a href='" . trim($image) . "' download target='_blank' class='coure_image_$lesson->resource_id' id='lesson_id_" . $lesson->lesson_id . "_$l'> /$getName <i class='fa fa-download' aria-hidden='true'></i></a><br/>";
                    $l++;
                }
            }
            $images_path['coure_image_' . $lesson->resource_id] = $img['coure_image_' . $lesson->resource_id];
        }
    //    print_r($lesson->file);
        if (!empty($hassubmitted) || !empty($files)) {
            
            array_push($assets, array(
                "course_id" => $lesson->course_id,
                "resource_id" => $lesson->resource_id,
                "lesson_title" => $lesson->title,
                "module_id" => $lesson->module_id,
                "links" => $links,
                "files" => $files
            ));
            // print_r($assets);
        }
    }

    if (isset($_POST['frmImagesDownload'])) {
        $resource_img_id = isset($_REQUEST['resource_id']) ? intval($_REQUEST['resource_id']) : '';
        $all_files = $images_path[$resource_img_id];
        if (count($all_files) > 0) {
            foreach ($all_files as $file) {
                
            }
        }
    }
    ?>

    <div class="contaninerinner coursereportpage"> 

        <h4>Course Data & Information</h4>
        <ol class="rtr-breadcrumb">
            <li class="rtr-breadcrumb-item"><a href="admin.php?page=rtr_progress_detail">Users List</a></li>
            <li class="rtr-breadcrumb-item"><a href="admin.php?page=rtr_progress_detail&user_id=<?php echo $user_id; ?>">Course List</a></li>
            <li class="rtr-breadcrumb-item active">Course Data</li>
        </ol>

        <div class="rtr-panel rtr-panel-primary mentorhandlepage"> 

            <?php
            if ($user_id > 0) {
                ?>
                <div class="rtr-pull-right"><a href="admin.php?page=rtr_progress_detail&user_id=<?php echo $user_id; ?>" class="rtr-btn rtr-btn-danger"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/back_arrow.svg" alt="Training plugin arrow image"> Back</a></div>
            <?php } ?> 
            <div class="rtr-panel-heading">
                Course Data
            </div>

            <div class="rtr-panel-body">  
                <div class="row"> 
                    <div class="rtr-col-lg-12">
                        <div class="reportarea">

                            <div class="coursementor">

                                <table class="tblenrolled display table table-striped rtr-table-bordered " cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>SNo</th>
                                            <th>Course</th>
                                            <th>Module</th>
                                            <th>Lesson</th>
                                            <th>Links</th>
                                            <th>Files</th>         
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php
                                        $count = 1;

                                        foreach ($assets as $data) {


                                            $course = $wpdb->get_var
                                                    (
                                                    $wpdb->prepare
                                                            (
                                                            "select title FROM " . rtr_wpl_tr_courses() . " WHERE id = %d", $data['course_id']
                                                    )
                                            );

                                            $module = $wpdb->get_var
                                                    (
                                                    $wpdb->prepare
                                                            (
                                                            "select title FROM " . rtr_wpl_tr_modules() . " WHERE id = %d", $data['module_id']
                                                    )
                                            );
                                            ?>
                                            <tr class="mentorrow">
                                                <td><?php echo $count; ?></td>
                                                <td>
                                                    <?php echo $course; ?>                                                                
                                                </td>  
                                                <td>
                                                    <?php echo $module; ?>                                                                
                                                </td> 
                                                <td>
                                                    <?php echo $data['lesson_title']; ?>                                                                
                                                </td> 
                                                <td>
                                                    <?php
                                                    echo $data['links'];
                                                    ?>                                                                
                                                </td> 
                                                <td>
                                                    <?php echo $data['files']; ?>                                                                
                                                </td>      
                                            </tr>
                                            <?php
                                            $count++;
                                        }
                                        ?>

                                    </tbody>
                                </table>

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
