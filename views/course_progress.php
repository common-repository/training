<?php
/**
 * This File for tracking course progress.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

include_once 'common.php';
global $wpdb;
$c_id = get_current_user_id();
$userrole = new WP_User($c_id);
$u_role = $userrole->roles[0];

$user_id = isset($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0;

if ($u_role == "administrator") {
    $courses = $wpdb->get_results("SELECT id,title FROM " . rtr_wpl_tr_courses() . " ORDER BY ord");
} else {
    $courses = $wpdb->get_results("SELECT id,title FROM " . rtr_wpl_tr_courses() . " WHERE FIND_IN_SET($c_id,mentor_ids) ORDER BY ord" );
}

if ($course_id == 0) {
    if (!empty($courses)) {
        $course_id = $courses[0]->id;
    }
}

$mentorids = $wpdb->get_var
        (
        $wpdb->prepare
                (
                "SELECT mentor_ids FROM " . rtr_wpl_tr_courses() . " WHERE id = %d", $course_id
        )
);

$mentorids = trim($mentorids ?? '');
$pos = strpos($mentorids, ",");
if ($pos == 0) {
    $mentorids = ltrim($mentorids, ',');
}

$usertbl = $wpdb->prefix . "users";
$mentors = [];
if (!empty($mentorids)) {
    $mentors = $wpdb->get_results("SELECT u.* " . "FROM " . $usertbl . " u WHERE u.ID IN($mentorids) ORDER BY FIELD(ID, $mentorids) DESC"
    );
}
$flag = 0;
$txt = 'Users';
if ($user_id > 0) {
    $flag = 1;
    $txt = "Courses";

    $total_courses = $wpdb->get_results
            (
            $wpdb->prepare
                    (
                    "select distinct course_id FROM " . rtr_wpl_tr_resource_status() . " WHERE user_id = %d", $user_id
            )
    );
    $course_array = array();
    if (count($total_courses) > 0) {

        foreach ($total_courses as $cour) {
            $course_detail = $wpdb->get_row
                    (
                    $wpdb->prepare
                            (
                            "select c.id,c.title,c.total_hrs,c.total_resources,c.course_type,c.course_amount  FROM " . rtr_wpl_tr_courses() . " c WHERE id = %d", $cour->course_id
                    )
            );
           if(isset($course_detail)){
            $course_array[] = array(
                "id" => $course_detail->id,
                "title" => $course_detail->title,
                "hrs" => $course_detail->total_hrs,
                "resources" => $course_detail->total_resources,
                "type" => $course_detail->course_type,
                "amount" => $course_detail->course_amount
            );
        }
        }
    }
}

$users = $wpdb->get_results("SELECT u.* ". "FROM " . $usertbl . " u order by id DESC");

$base_url = site_url();
$slug = RTR_WPL_PAGE_SLUG;
?>
<?php
$blogusers = get_users(array('fields' => array('display_name', 'user_email')));
$wp_users = array();
if (count($blogusers) > 0) {
    foreach ($blogusers as $inx => $user) {
        $wp_users[] = $user->display_name;
        $wp_users[] = $user->user_email;
    }
}
?>
<div class="contaninerinner coursereportpage">     
    <h4><?php echo $txt; ?> List</h4>
    <?php
    if ($user_id > 0) {
        ?>
        <ol class="rtr-breadcrumb">
            <li class="rtr-breadcrumb-item"><a href="admin.php?page=rtr_progress_detail">Users List</a></li>
            <li class="rtr-breadcrumb-item active"><?php echo $txt; ?> List</li>
        </ol>
        <?php
    }
    ?>
    <div class="rtr-panel rtr-panel-primary mentorhandlepage">
        <?php
        if ($user_id > 0) {
            ?>
            <div class="rtr-pull-right"><a href="admin.php?page=rtr_progress_detail" class="rtr-btn rtr-btn-danger"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/back_arrow.svg" alt="Training plugin arrow image"> Back</a></div>
        <?php } ?> 
        <div class="rtr-panel-heading">
            <?php
            echo $txt;
            ?>
        </div>
        <div class="rtr-panel-body cust-course-pros-section">  
            <div class="row"> 
                <div class="rtr-col-lg-12">
                    <div class="reportarea">
                        <div class="coursementor 2">
                            <?php
                            if ($flag) {
                                ?>
                                <table class="tblenrolled display rtr-table-bordered table table-striped  " cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>SNo</th>
                                            <th>Name</th>
                                            <th>Resources</th>
                                            <th>Hours</th>
                                            <th>Type</th>
                                            <th>Amount</th>                                                        
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php
                                        $j = 0;
                                        foreach ($course_array as $cou) {

                                            $j++;
                                            ?>

                                            <tr class="mentorrow" data-uid="<?php echo $cou['id']; ?>">
                                                <td><?php echo $j; ?></td>
                                                <td>
                                                    <?php echo $cou['title']; ?>                                                                
                                                </td>  
                                                <td>
                                                    <?php echo $cou['resources']; ?>                                                                
                                                </td>  
                                                <td>
                                                    <?php echo $cou['hrs']; ?>                                                                
                                                </td> 
                                                <td>
                                                    <?php echo!empty($cou['type']) ? strtoupper($cou['type']) : strtoupper("free"); ?>                                                                
                                                </td>
                                                <td>
                                                    <?php echo "$" . $cou['amount']; ?>                                                                
                                                </td>                                                                                                                                                                                                                                          
                                                <td>
                                                    <!-- a href="javascript:;" data-id=" <?php //echo $user->ID; ?>" class="remove_mentor rtr-btn rtr-btn-danger">Remove</a -->
                                                    <a href="admin.php?page=rtr_progress_detail&show=progress&user_id=<?php echo $user_id; ?>&course_id=<?php echo $cou['id'] ?>" data-id="<?php echo $cou['id']; ?>" class="rtr-btn rtr-btn-primary"><i class="fa fa-line-chart" aria-hidden="true"></i> Progress</a>
                                                    <a href="admin.php?page=rtr_progress_detail&show=data&user_id=<?php echo $user_id; ?>&course_id=<?php echo $cou['id'] ?>" data-id="<?php echo $cou['id']; ?>" class="rtr-btn rtr-btn-success"> Course</a>
                                                </td>
                                            </tr>

                                            <?php
                                        }
                                        ?>

                                    </tbody>
                                </table>
                                <?php
                            } else {
                                ?>
                                <table class="tblenrolled display rtr-table-bordered table table-striped  " cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>SNo</th>
                                            <th>Name</th>
                                            <th>Login</th>
                                            <th>Email</th> 
                                            <th>Total Courses 1</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php
                                        $j = 0;
                                        foreach ($users as $user) {

                                            $j++;
                                            ?>

                                            <tr class="mentorrow" data-uid="<?php echo $user->ID; ?>">
                                                <td><?php echo $j; ?></td>
                                                <td>
                                                    <?php echo $user->display_name; ?>                                                                
                                                </td>  
                                                <td>
                                                    <?php echo $user->user_login; ?>                                                                
                                                </td>  
                                                <td>
                                                    <a href="mailto:<?php echo $user->user_email; ?>"><?php echo $user->user_email; ?></a>
                                                </td> 
                                                <td>
                                                    <?php
                                                    $total_courses = $wpdb->get_results
                                                            (
                                                            $wpdb->prepare
                                                                    (
                                                                    "select distinct course_id FROM " . rtr_wpl_tr_resource_status() . " WHERE user_id = %d", $user->ID
                                                            )
                                                    );
                                                    echo count($total_courses);
                                                    ?>
                                                </td>
                                                <td>
                                                    <!--a href="javascript:;" data-id="<?php echo $user->ID; ?>" class="remove_mentor rtr-btn rtr-btn-danger">Remove</a-->
                                                    <a href="admin.php?page=rtr_progress_detail&user_id=<?php echo $user->ID; ?>" data-id="<?php echo $user->ID; ?>" class="  rtr-btn rtr-btn-info"><img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL ?>assets/images/manage.svg" alt="Training course manage image"> Go to Course</a>
                                                </td>
                                            </tr>

                                            <?php
                                        }
                                        ?>

                                    </tbody>
                                </table>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>   

</div>
