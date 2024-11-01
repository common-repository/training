<?php
/**
 * This File for editing Courses.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

include_once 'common.php';
$course_id = isset($_REQUEST['course_id']) ? intval($_REQUEST['course_id']) : 0;
$course = $wpdb->get_row
        (
        $wpdb->prepare
                (
                "SELECT * FROM " . rtr_wpl_tr_courses() . " WHERE id = %d", $course_id
        )
);
if (empty($course)) {
    die('Invalid Course');
}

$users = get_users(array());

// $args = array(
//     'role' => MENTOR_ROLE,
//     'fields' => 'all'
// );

// $mentors = get_users($args);
// print_r($mentors);  die;
?>
<div class="contaninerinner">

    <h4>Edit Course</h4>
    <ol class="rtr-breadcrumb">
        <li class="rtr-breadcrumb-item"><a href="admin.php?page=trainingtool">Courses</a></li>
        <li class="rtr-breadcrumb-item active"> Edit Course - <?php echo $course->title; ?></li>
    </ol>


    <div class="rtr-panel rtr-panel-primary">
        <div class="rtr-pull-right"><a href="admin.php?page=trainingtool" class="rtr-btn rtr-btn-danger"><span class="rtr-glyphicon  " aria-hidden="true"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/back_arrow.svg" alt="Training plugin arrow image"></span>
 Back</a></div>
        <div class="rtr-panel-heading">Edit Course - <?php echo $course->title; ?></div>
        <div class="rtr-panel-body">
            <form action="#" method="post" id="add_course" name="add_course" class="form-horizontal">
                <input type="hidden" id="course_id" name="course_id" value="<?php echo $course_id; ?>" />
                <input type="hidden" value="free" class="rtr-form-control" name="course_type" id="course_type"/>

                <div class="rtr-form-group">
                    <label for="title" class="rtr-col-lg-2 control-label">Name* :</label>
                    <div class="rtr-col-lg-8 cu-new-ex-in">
                        <input type="text" value="<?php echo $course->title; ?>" required class="rtr-form-control" id="title" name="title" placeholder="Title">
                    </div>
                </div>
                <div class="rtr-form-group">
                    <label for="title" class="rtr-col-lg-2 control-label">Select Author* :</label>
                    <div class="rtr-col-lg-8 cu-new-ex-in">
                        <select id="slct_author" class="rtr-form-control" name="slct_author">
                            <option value="-1"> -- choose author --</option>
                            <?php
                            $all_authors = $wpdb->get_results("SELECT * from " . rtr_wpl_tr_authors(), ARRAY_A);
                            if (count($all_authors) > 0) {
                                $found = 0;
                                foreach ($all_authors as $inx => $stx) {
                                    $selected = '';
                                    if ($stx['id'] == $course->assigned_author_id) {
                                        $found = 1;
                                        $selected = "selected";
                                    }
                                    ?>
                                    <option value="<?php echo $stx['id']; ?>" <?php echo $selected; ?>><?php echo $stx['name'] . " (" . $stx['email'] . ")"; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="rtr-form-group">
                    <label for="title" class="rtr-col-lg-2 control-label">Select Category* :</label>
                    <div class="rtr-col-lg-8 cu-new-ex-in">
                        <select id="slct_category" class="rtr-form-control" name="slct_category">
                            <option value="-1"> -- choose category --</option>
                            <?php
                            $all_authors = $wpdb->get_results("SELECT * from " . rtr_wpl_tr_categories(), ARRAY_A);
                            if (count($all_authors) > 0) {
                                $found = 0;
                                foreach ($all_authors as $inx => $stx) {
                                    $selected = "";
                                    if ($stx['id'] == $course->category_id) {
                                        $found = 1;
                                        $selected = "selected";
                                    }
                                    ?>
                                    <option value="<?php echo $stx['id']; ?>" <?php echo $selected; ?>><?php echo $stx['name']; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="rtr-form-group">
                    <label for="title" class="rtr-col-lg-2 control-label">Select Subcategory* : </label>
                    <div class="rtr-col-lg-8 cu-new-ex-in">


                        <select id="slct_subcategory" class="rtr-form-control" name="slct_subcategory">
                            <option value="-1"> -- choose subcategory -- </option>
                            <?php
                            $all_subcategories = $wpdb->get_row(
                                    $wpdb->prepare(
                                            "SELECT subcategories from " . rtr_wpl_tr_categories() . " WHERE id = %d", $course->category_id
                                    )
                            );

                            if (!empty($all_subcategories)) {

                                $all_subcategories = (array) json_decode($all_subcategories->subcategories);
                                $found = 0;
                                foreach ($all_subcategories as $val) {
                                    $selected = "";
                                    if ($val == $course->subcategory) {
                                        $selected = "selected";
                                        $found = 1;
                                    }
                                    ?>
                                    <option value="<?php echo $val; ?>" <?php echo $selected; ?>><?php echo $val; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="rtr-form-group">
                    <label for="cat_name" class="rtr-col-lg-2 control-label">Description :</label>
                    <div class="rtr-col-lg-8 wpeditor">
                        <?php
                        wp_editor(html_entity_decode($course->description), $id = 'description', $prev_id = 'title', $media_buttons = false, $tab_index = 1);
                        ?>
                    </div>
                </div>


                <div class="rtr-form-group">
                    <label for="add_btn" class="rtr-col-lg-2 control-label"></label>
                    <div class="rtr-col-lg-8 ">
                        <input type="submit" id="add_btn" value="Update" class="rtr-btn rtr-btn-primary"/>           
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
