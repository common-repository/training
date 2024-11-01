<?php

/**
 * This File for handilling all ajax request.
 * @author	Rudra Innnovative Software 
 * @package	training/library 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

global $wpdb;
global $current_user;
$current_user = wp_get_current_user();
$user_id = $current_user->ID;

if($_REQUEST["param"] === 'filter_course' && $_REQUEST['subcat'] == ''){

}
else{
if ($user_id == 0) {
    $actual_link = "$_SERVER[HTTP_REFERER]";
    $login_url = site_url() . '/wp-login.php?redirect_to=' . urlencode($actual_link);
    echo  json(0, '<a href=" ' . $login_url . ' ">Login</a> is required');
}
}

/*
 * Library file to handle all ajax request
 */

if (isset($_REQUEST["param"])) {

    if (esc_attr($_REQUEST['param']) == "default_course_image") {
        $default_image = isset($_REQUEST['default_image']) ? esc_attr($_REQUEST['default_image']) : "";
        if (!empty($default_image)) {
            update_option("tr_def_course_image", $default_image);
            json(1, "Image uploaded successfully");
        } else {
            json(0, "Please select an image to upload");
        }
    } elseif (esc_attr($_REQUEST['param']) == "get_category_course") {

        $cid = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

        if (intval($cid) > 0) {

            $get_courses = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * from " . rtr_wpl_tr_courses() . " WHERE category_id = %d",
                    $cid
                )
            );

            if (count($get_courses) > 0) {

                ob_start(); // start buffer
                include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/tmpl/rtr-tmpl-load-front-courses.php';
                $template = ob_get_contents();
                ob_end_clean();
                json(1, "courses found", array("template" => $template));
            } else {
                json(0, "No course found in this category");
            }
        } else {
            json(0, "Category id is not valid");
        }
    } elseif (esc_attr($_REQUEST["param"]) == "show_subcategory_list") {
        $category_id = isset($_REQUEST['category_id']) ? intval($_REQUEST['category_id']) : 0;
        $getsubcategory = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT subcategories from " . rtr_wpl_tr_categories() . " WHERE id = %d",
                $category_id
            ),
            ARRAY_A
        );
        json(1, "Subcategory found", array("subcategory" => (array) json_decode($getsubcategory['subcategories'])));
    } elseif (esc_attr($_REQUEST['param']) == "save_author_tr") {
        // print_r($_REQUEST);die;
        $id = isset($_REQUEST['txt_id']) ? intval($_REQUEST['txt_id']) : 0;
        $type = isset($_REQUEST['txt_type']) ? esc_attr(trim($_REQUEST['txt_type'])) : "";
        $name = isset($_REQUEST['txtName']) ? esc_attr(trim($_REQUEST['txtName'])) : "";
        $post = isset($_REQUEST['txtPost']) ? esc_attr(trim($_REQUEST['txtPost'])) : "";
        $email = isset($_REQUEST['txtEmail']) ? esc_attr(trim($_REQUEST['txtEmail'])) : "";
        $website = isset($_REQUEST['txtWeb']) ? esc_attr(trim($_REQUEST['txtWeb'])) : "";
        $phone = isset($_REQUEST['txtPhone']) ? esc_attr(trim($_REQUEST['txtPhone'])) : "";
        $fb = isset($_REQUEST['txtFacebook']) ? esc_attr(trim($_REQUEST['txtFacebook'])) : "";
        $about = isset($_REQUEST['txtAbout']) ? htmlspecialchars(esc_attr(trim($_REQUEST['txtAbout']))) : "";

        $profile_img = !empty($_REQUEST['defaultCourseImgUrl']) ? esc_attr(trim($_REQUEST['defaultCourseImgUrl'])) : "";

        if ($type == "update" && $id > 0) {

            $is_author_exists = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * from " . rtr_wpl_tr_authors() . " WHERE id = %d",
                    $id
                )
            );
            $is_author_exists = isset($is_author_exists) ? $is_author_exists : array();
            if (count((array) $is_author_exists) > 0) {

                $wpdb->update(
                    rtr_wpl_tr_authors(),
                    array(
                        "name" => $name,
                        "email" => $email,
                        "about" => $about,
                        "phone" => $phone,
                        "fb_url" => $fb,
                        "post" => $post,
                        "website" => $website,
                        "profile_img" => $profile_img
                    ),
                    array(
                        "id" => $id
                    )
                );

                ob_start(); // start buffer
                include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/tmpl/rtr-tmpl-load-authors.php';
                $template = ob_get_contents();
                ob_end_clean();

                json(
                    1,
                    "Data updated successfully",
                    array(
                        "template" => $template
                    )
                );
            } else {
                json(0, "Author not found");
            }
        } else {

            $wpdb->insert(
                rtr_wpl_tr_authors(),
                array(
                    "name" => $name,
                    "email" => $email,
                    "about" => $about,
                    "phone" => $phone,
                    "fb_url" => $fb,
                    "post" => $post,
                    "website" => $website,
                    "profile_img" => $profile_img
                )
            );

            if ($wpdb->insert_id > 0) {

                ob_start(); // start buffer
                include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/tmpl/rtr-tmpl-load-authors.php';
                $template = ob_get_contents();
                ob_end_clean();

                json(
                    1,
                    "Data inserted successfully",
                    array(
                        "template" => $template
                    )
                );
            } else {
                json(0, "Failed to insert");
            }
        }
    } elseif (esc_attr($_REQUEST['param']) == "load_all_courses") {

        ob_start(); // start buffer
        include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/tmpl/rtr-tmpl-load-front-courses.php';
        $template = ob_get_contents();
        ob_end_clean();

        json(1, "course filtered", array("template" => $template));
    } elseif (esc_attr($_REQUEST["param"]) == "update_subcategory_item") {
        $old_value = isset($_REQUEST['old_value']) ? trim($_REQUEST['old_value']) : "";
        $udpated_value = isset($_REQUEST['updated_value']) ? trim($_REQUEST['updated_value']) : "";
        $category_id = isset($_REQUEST['category_id']) ? trim($_REQUEST['category_id']) : "";
        if($udpated_value === ''){
                json(0,"Please enter a value");
        }
        if (trim(strtolower($old_value)) == trim(strtolower($udpated_value))) {
            json(0, "No change found");
        }
        $get_category_info = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT subcategories from " . rtr_wpl_tr_categories() . " WHERE id = %d",
                $category_id
            ),
            ARRAY_A
        );
        $is_updated = false;
        if (!empty($get_category_info) > 0) {
            $subcategories = $get_category_info['subcategories'];
            $subcategories = (array) json_decode($subcategories);

            $lower_subcategories = array_map('strtolower', $subcategories);
            $lower_updated_value = strtolower($udpated_value);
           
            if (in_array( $lower_updated_value, $lower_subcategories)) {
                json(0, "Subcategory already exists with this title");
            }

            foreach ($subcategories as $index => $value) {

                if (trim($value) == trim($old_value)) {

                    $subcategories[$index] = $udpated_value;
                    $is_updated = true;
                }
            }
            $wpdb->update(
                rtr_wpl_tr_categories(),
                array(
                    "subcategories" => json_encode($subcategories)
                ),
                array(
                    "id" => $category_id
                )
            );

            if ($is_updated) {

                $wpdb->update(
                    rtr_wpl_tr_courses(),
                    array(
                        "subcategory" => $udpated_value
                    ),
                    array(
                        "category_id" => $category_id,
                        "subcategory" => $old_value
                    )
                );
            }

            json(1, "Subcategory details updated");
        } else {

        }
    } elseif (esc_attr($_REQUEST["param"]) == "update_category_info") {
        $category_id = isset($_REQUEST['category_id']) ? intval($_REQUEST['category_id']) : 0;
        $category_name = isset($_REQUEST['txtUpdateName']) ? trim($_REQUEST['txtUpdateName']) : "";
        // if (trim(strtolower($old_value)) == trim(strtolower($udpated_value))) {
        //     json(0, "No change found");
        // }
        if ($category_id > 0) {
            // $is_category_exists = $wpdb->get_row(
            //     $wpdb->prepare(
            //         "SELECT * from " . rtr_wpl_tr_categories() . " WHERE id = %d",
            //         $category_id
            //     )
            // );
            // $is_category_exists = $wpdb->get_row(
            //     $wpdb->prepare(
            //         "SELECT * from " . rtr_wpl_tr_categories() . " WHERE id = %d",
            //         $category_id
            //     )
            // );
            $is_category_exists = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * from " . rtr_wpl_tr_categories() . " WHERE LOWER(TRIM(name)) = %s",
                    strtolower(trim($category_name))
                ),
                ARRAY_A
            );
            
            if(isset($is_category_exists['id']) && (int)$is_category_exists['id'] === $category_id){
                json(0, "No change Found");
            }
            if ($is_category_exists > 0) {
                json(0, "Category already exist");
            } else {
                $wpdb->update(
                    rtr_wpl_tr_categories(),
                    array(
                        "name" => $category_name
                    ),
                    array(
                        "id" => $category_id
                    )
                );
                json(1, "Category details updated");
            }
        } else {
            json(0, "Invalid category id");
        }
    } elseif (esc_attr($_REQUEST['param']) == "filter_course") {

        $subCategories = isset($_REQUEST['subcat']) ? esc_attr($_REQUEST['subcat']) : "";
        $cid = isset($_REQUEST['category_id']) ? intval($_REQUEST['category_id']) : 0;
        
        if (!empty($subCategories) && $cid > 0) {

            $subcatArray = explode(",", $subCategories);
            $dataArr = array();
            foreach ($subcatArray as $inx => $stx) {
                $dataArr[] = "'$stx'";
            }

            $subCategoriesData = implode(",", $dataArr);

            $courses = $wpdb->get_results
            ("SELECT * FROM " . rtr_wpl_tr_courses() . " WHERE subcategory IN ($subCategoriesData) ORDER BY id asc");

            if (count($courses) > 0) {

                ob_start(); // start buffer
                include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/tmpl/rtr-tmpl-load-front-courses.php';
                $template = ob_get_contents();
                ob_end_clean();

                json(1, "course filtered", array("template" => $template));
            } else {

                json(0, "No course found");
            }
        } else {
            ob_start(); // start buffer
            include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/tmpl/rtr-tmpl-load-front-courses.php';
            $template = ob_get_contents();
            ob_end_clean();
    
            json(1, "course filtered", array("template" => $template));            
        }
    } elseif (esc_attr($_REQUEST['param'] == "delete_author_tr")) {

        $id = isset($_REQUEST['delid']) ? intval($_REQUEST['delid']) : 0;

        if ($id > 0) {

            $is_author_has_course = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT count(id) from " . rtr_wpl_tr_courses() . " WHERE assigned_author_id = %d",
                    $id
                )
            );

            if ($is_author_has_course > 0) {
                json(0, "Author has course assigned, please delete course first");
            }

            $is_author_exists = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * from " . rtr_wpl_tr_authors() . " WHERE id = %d",
                    $id
                )
            );

            isset($is_author_exists) ? $is_author_exists : array();
            if (count((array) $is_author_exists) > 0) {

                ob_start(); // start buffer
                include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/tmpl/rtr-tmpl-load-authors.php';
                $template = ob_get_contents();
                ob_end_clean();

                $wpdb->delete(rtr_wpl_tr_authors(), [
                    "id" => $id
                ]);
                json(1, "Author deleted successfully", array("template" => $template));
            } else {
                json(0, "Requested author doesn't find in database");
            }
        }
    } elseif (esc_attr($_REQUEST['param'] == "add_category_tr")) {

        $title = isset($_REQUEST['txtName']) ? esc_attr(trim($_REQUEST['txtName'])) : "";

        $is_category_exists = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * from " . rtr_wpl_tr_categories() . " WHERE LOWER(TRIM(name)) = %s",
                strtolower(trim($title))
            ),
            ARRAY_A
        );
        if ($is_category_exists > 0) {
            json(0, "Category already created");
        } else {
            $wpdb->insert(
                rtr_wpl_tr_categories(),
                array(
                    "name" => $title,
                    "subcategories" => "",
                    "status" => 1
                )
            );
            if ($wpdb->insert_id > 0) {
                ob_start(); // start buffer
                include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/tmpl/rtr-tmpl-load-categories.php';
                $template = ob_get_contents(); // store template in buffer
                ob_end_clean();
                json(
                    1,
                    "Category created",
                    array(
                        "template" => $template
                    )
                );
            } else {
                json(0, "Failed to create category");
            }
        }
    } elseif (esc_attr($_REQUEST['param'] == "del_subcategory_tr")) {

        $category_id = isset($_REQUEST['id']) ? intval(esc_attr(trim($_REQUEST['id']))) : "";

        $is_category_has_course = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT count(id) from " . rtr_wpl_tr_courses() . " WHERE category_id = %d",
                $category_id
            )
        );

        if ($is_category_has_course > 0) {
            json(0, "Category has course assigned, please delete course first");
        }

        $getSubcategories = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT subcategories from " . rtr_wpl_tr_categories() . " WHERE id = %d",
                $category_id
            ),
            ARRAY_A
        );

        if (count($getSubcategories) > 0) {

            $wpdb->delete(
                rtr_wpl_tr_categories(),
                array(
                    "id" => $category_id
                )
            );

            json(1, "Category deleted");
        } else {
            json(0, "Invalid Category found to delete");
        }
    } elseif (esc_attr($_REQUEST['param'] == "add_subcategory_tr")) {

        $title = isset($_REQUEST['txtName']) ? esc_attr(trim($_REQUEST['txtName'])) : "";
        $category_id = isset($_REQUEST['txtCategory']) ? intval(esc_attr(trim($_REQUEST['txtCategory']))) : -1;

        if ($category_id == -1) {
            json(0, "Please choose category");
        }
      
        $getSubcategories = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT subcategories from " . rtr_wpl_tr_categories() . " WHERE id = %d",
                $category_id
            ),
            ARRAY_A
        );
        
        $subcategories = array(json_decode($getSubcategories['subcategories']));
    
        //Making the subcategories and title in lowercase to check for the existing subcategories.
        $lower_case_subcategories = isset($subcategories[0]) && !empty($subcategories[0]) ? array_map('strtolower', $subcategories[0]) : array();
        $lower_case_title = strtolower($title);
       
        if ((isset($subcategories[0]) && $subcategories[0]) && in_array($lower_case_title, $lower_case_subcategories)) {
            json(0, "Subcategory already created");
        } else {
          ;
            if ((isset($subcategories[0]) && $subcategories[0]) && count($subcategories[0]) > 0) {

                array_push($subcategories[0], $title);
                $wpdb->update(
                    rtr_wpl_tr_categories(),
                    array(
                        "subcategories" => json_encode($subcategories[0])
                    ),
                    array(
                        "id" => $category_id
                    )
                );
                json(1, "Subcategory added successfully");
            } else {

                $wpdb->update(
                    rtr_wpl_tr_categories(),
                    array(
                        "subcategories" => json_encode(array($title))
                    ),
                    array(
                        "id" => $category_id
                    )
                );

                json(1, "Subcategory added successfully");
            }
        }
    } elseif (esc_attr($_REQUEST['param'] == "get_subcategories")) {

        $catid = isset($_REQUEST['catid']) ? intval($_REQUEST['catid']) : 0;
        if ($catid > 0) {
            $allcategories = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT subcategories from " . rtr_wpl_tr_categories() . " WHERE id = %d",
                    $catid
                )
            );
            if (count((array) $allcategories) > 0) {

                json(
                    1,
                    "Category found",
                    array(
                        "categories" => (array) json_decode($allcategories->subcategories)
                    )
                );
            } else {
                json(0, "No subcategory found");
            }
        } else {
            json(0, "Invalid category ID");
        }
    } elseif (esc_attr($_REQUEST['param'] == "remove_subcategory")) {

        $subcat = isset($_REQUEST['subcat']) ? esc_attr(trim($_REQUEST['subcat'])) : "";
        $category = isset($_REQUEST['category']) ? intval($_REQUEST['category']) : '';
        if (!empty($subcat)) {

            $is_subcategory_has_course = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT count(id) from " . rtr_wpl_tr_courses() . " WHERE LOWER(TRIM(subcategory)) = %s",
                    trim(strtolower($subcat))
                )
            );

            if ($is_subcategory_has_course > 0) {
                json(0, "Subcategory has course assigned, please delete course first");
            }

            $allcategories = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT subcategories from " . rtr_wpl_tr_categories() . " WHERE id = %d",
                    $category
                )
            );

            $subcategories = $allcategories->subcategories;
            $updatedSubcategory = array();
            if (!empty($subcategories)) {
                $data = (array) json_decode($subcategories);
                if (($key = array_search($subcat, $data)) !== false) {
                    unset($data[$key]);
                }

                foreach ($data as $key => $val) {
                    array_push($updatedSubcategory, $val);
                }
            }

            $wpdb->update(
                rtr_wpl_tr_categories(),
                array(
                    "subcategories" => json_encode($updatedSubcategory)
                ),
                array(
                    "id" => $category
                )
            );

            json(1, "Subcategory removed");
        } else {
            json(0, "Invalid category");
        }
    }

    /* get users survey basis mentors - custom */
    if (esc_attr($_REQUEST['param']) == "getCourseDetails") {
        $course_id = isset($_REQUEST['course_id']) ? intval($_REQUEST['course_id']) : '';

        if ($course_id == 0) {
            json(0, "Invalid course id");
        }
        $course_details = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM " . rtr_wpl_tr_courses() . " WHERE id = %d",
                $course_id
            )
        );

        if (empty($course_details)) {
            json(0, "No Course data availabale");
        }
        json(1, "course details found", $course_details);
    } elseif (esc_attr($_REQUEST['param']) == "add_course") {

        $user_id = $current_user->ID;
        $now = date("Y-m-d H:i:s");
        $course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
        $course_type = isset($_POST['course_type']) ? trim(esc_attr($_POST['course_type'])) : 'free';
        $course_amount = isset($_POST['course_amount']) && !empty($_POST['course_amount']) ? trim(esc_attr($_POST['course_amount'])) : 0;
        $title = isset($_POST['title']) ? esc_attr(trim($_POST['title'])) : '';
        $description = isset($_POST['description']) ? esc_attr($_REQUEST["description"]) : '';
        $description = stripcslashes($description);
        $author_id = isset($_REQUEST['slct_author']) ? intval($_REQUEST['slct_author']) : '';
        $category_id = isset($_REQUEST['slct_category']) ? intval($_REQUEST['slct_category']) : 0;
        $subcategory = isset($_REQUEST['slct_subcategory']) ? esc_attr(trim($_REQUEST['slct_subcategory'])) : "";

        if ($title == '') {
            json(0, 'Title is required');
        }

        if ($author_id == -1) {
            json(0, 'Author is required');
        }

        if ($category_id == -1) {
            json(0, 'Category is required');
        }

        if ($subcategory == -1) {
            json(0, 'Subcategory is required');
        }

        $course = $wpdb->get_row
        (
            $wpdb->prepare
            (
                "SELECT id,user_ids FROM " . rtr_wpl_tr_courses() . " WHERE id = %d",
                $course_id
            )
        );

        if (!empty($course)) {

            $wpdb->query
            (
                $wpdb->prepare
                (
                    "UPDATE " . rtr_wpl_tr_courses() . " SET title = %s, description = %s,assigned_author_id = %d, category_id = %d, subcategory = %s, course_type = %s, course_amount = %s WHERE id = %d",
                    $title,
                    $description,
                    $author_id,
                    $category_id,
                    $subcategory,
                    $course_type,
                    $course_amount,
                    $course_id
                )
            );

            json(1, 'Course Updated');
        } else {

            $ord = $wpdb->get_var("SELECT MAX(ord) FROM " . rtr_wpl_tr_courses());

            $ord = $ord + 1;

            $wpdb->query
            (
                $wpdb->prepare
                (
                    "INSERT INTO " . rtr_wpl_tr_courses() . " (course_type,course_amount,ord, title,assigned_author_id,category_id,subcategory, description, created_by, created_dt, updated_by) "
                    . "VALUES (%s,%s,%d, %s,%d,%d,%s, %s, %d, '%s', %d)",
                    $course_type,
                    $course_amount,
                    $ord,
                    $title,
                    $author_id,
                    $category_id,
                    $subcategory,
                    $description,
                    $user_id,
                    $now,
                    $user_id
                )
            );

            $lastid = $wpdb->insert_id;
            $arr = array("lastid" => $lastid);
            json(1, 'Course Created', $arr);
        }
    } elseif (esc_attr($_REQUEST['param']) == "delete_course") {
        $id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;

        deletemediacourse($id);

        $wpdb->query
        (
            $wpdb->prepare
            (
                "DELETE FROM " . rtr_wpl_tr_resources() . " WHERE course_id = %d",
                $id
            )
        );

        $wpdb->query
        (
            $wpdb->prepare
            (
                "DELETE FROM " . rtr_wpl_tr_lessons() . " WHERE module_id IN (SELECT id FROM " . rtr_wpl_tr_modules() . " WHERE course_id = %d)",
                $id
            )
        );

        $wpdb->query
        (
            $wpdb->prepare
            (
                "DELETE FROM " . rtr_wpl_tr_modules() . " WHERE course_id = %d",
                $id
            )
        );

        $wpdb->query
        (
            $wpdb->prepare
            (
                "DELETE FROM " . rtr_wpl_tr_courses() . " WHERE id = %d",
                $id
            )
        );

        json(1, 'Course Deleted');
    } elseif (esc_attr($_REQUEST['param']) == "add_module") {

        $user_id = $current_user->ID;
        $now = date("Y-m-d H:i:s");
        $module_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
        $title = isset($_POST['title']) ? esc_attr(trim($_POST['title'])) : '';
        $link = isset($_POST['link']) ? esc_attr(trim($_POST['link'])) : '';

        $description = isset($_POST['description']) ? esc_attr($_REQUEST["description"]) : '';
        $description = stripcslashes($description);

        if ($title == '' || $course_id == 0) {
            json(0, 'Title and course id are required');
        }

        if ($module_id > 0) {
            $wpdb->query
            (
                $wpdb->prepare
                (
                    "UPDATE " . rtr_wpl_tr_modules() . " SET title = %s, description = %s, external_link = %s WHERE id = %d",
                    $title,
                    $description,
                    $link,
                    $module_id
                )
            );
            json(1, 'Module Updated');
        } else {

            $ord = $wpdb->get_var
            (
                $wpdb->prepare
                (
                    "SELECT MAX(ord) FROM " . rtr_wpl_tr_modules() . " WHERE course_id = %d",
                    $course_id
                )
            );

            $ord = $ord + 1;
            $wpdb->query
            (
                $wpdb->prepare
                (
                    "INSERT INTO " . rtr_wpl_tr_modules() . " (ord, course_id, title, description, external_link, created_by, created_dt, updated_by) "
                    . "VALUES (%d, %d, %s, %s, %s, %d, '%s', %d)",
                    $ord,
                    $course_id,
                    $title,
                    $description,
                    $link,
                    $user_id,
                    $now,
                    $user_id
                )
            );

            json(1, 'Module Created');
        }
    } elseif (esc_attr($_REQUEST['param']) == "delete_module") {
        $id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
        $course_id = isset($_POST["course_id"]) ? intval($_POST["course_id"]) : 0;

        deletemediamodule($id);
        $wpdb->query
        (
            $wpdb->prepare
            (
                "DELETE FROM " . rtr_wpl_tr_resources() . " WHERE module_id = %d",
                $id
            )
        );

        $wpdb->query
        (
            $wpdb->prepare
            (
                "DELETE FROM " . rtr_wpl_tr_lessons() . " WHERE module_id = %d",
                $id
            )
        );

        $wpdb->query
        (
            $wpdb->prepare
            (
                "DELETE FROM " . rtr_wpl_tr_modules() . " WHERE id = %d",
                $id
            )
        );

        updatehours_and_resources($id, $course_id, 0);

        json(1, 'Module Deleted');
    } elseif (esc_attr($_REQUEST['param']) == "add_lesson") {

        $user_id = $current_user->ID;
        $now = date("Y-m-d H:i:s");
        $lesson_id = isset($_POST['lessid']) ? intval($_POST['lessid']) : 0;
        $module_id = isset($_POST['module_id']) ? intval($_POST['module_id']) : 0;
        $course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
        $title = isset($_POST['title']) ? esc_attr(trim($_POST['title'])) : '';
        //$hours = isset($_POST['hours'])?esc_attr(trim($_POST['hours'])):0;            
        $link = isset($_POST['link']) ? esc_attr(trim($_POST['link'])) : '';
        $description = isset($_POST['description']) ? esc_attr($_REQUEST["description"]) : '';
        $description = stripcslashes($description);

        if ($title == '' || $module_id == 0) {
            json(0, 'Title, Time and module id are required');
        }

        if ($lesson_id > 0) {
            $wpdb->query
            (
                $wpdb->prepare
                (
                    "UPDATE " . rtr_wpl_tr_lessons() . " SET title = %s, description = %s, external_link = %s WHERE id = %d",
                    $title,
                    $description,
                    $link,
                    $lesson_id
                )
            );

            json(1, 'Lesson Updated');
        } else {

            $ord = $wpdb->get_var
            (
                $wpdb->prepare
                (
                    "SELECT MAX(ord) FROM " . rtr_wpl_tr_lessons() . " WHERE module_id = %d",
                    $module_id
                )
            );

            $ord = $ord + 1;

            $wpdb->query
            (
                $wpdb->prepare
                (
                    "INSERT INTO " . rtr_wpl_tr_lessons() . " (ord, module_id, title, description, external_link, created_by, created_dt, updated_by) "
                    . "VALUES (%d, %d, %s,  %s, %s, %d, '%s', %d)",
                    $ord,
                    $module_id,
                    $title,
                    $description,
                    $link,
                    $user_id,
                    $now,
                    $user_id
                )
            );

            json(1, 'Lesson Created');
        }
    } elseif (esc_attr($_REQUEST['param']) == "delete_lesson") {
        $id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
        $module_id = isset($_POST['module_id']) ? intval($_POST['module_id']) : 0;
        $course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
        deletemedia($id);
        $wpdb->query
        (
            $wpdb->prepare
            (
                "DELETE FROM " . rtr_wpl_tr_resources() . " WHERE lesson_id = %d",
                $id
            )
        );

        $wpdb->query
        (
            $wpdb->prepare
            (
                "DELETE FROM " . rtr_wpl_tr_lessons() . " WHERE id = %d",
                $id
            )
        );
        updatehours_and_resources($module_id, $course_id, $id);
        json(1, 'Lesson Deleted');
    } elseif (esc_attr($_REQUEST['param']) == "add_resource") {

        $user_id = $current_user->ID;
        $now = date("Y-m-d H:i:s");
        $resource_id = isset($_POST['resid']) ? intval($_POST['resid']) : 0;
        $lesson_id = isset($_POST['lesson_id']) ? intval($_POST['lesson_id']) : 0;
        $module_id = isset($_POST['module_id']) ? intval($_POST['module_id']) : 0;
        $course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
        $title = isset($_POST['title']) ? esc_attr(trim($_POST['title'])) : '';
        $button_type = isset($_POST['button_type']) ? esc_attr(trim($_POST['button_type'])) : 'mark';

        $hours = isset($_POST['hours']) ? esc_attr(trim($_POST['hours'])) : 0;
        $link = isset($_POST['link']) ? esc_attr(trim($_POST['link'])) : '';

        $description = isset($_POST['description']) ? esc_attr($_REQUEST["description"]) : '';
        $description = stripcslashes($description);

        if ($title == '' || $module_id == 0 || $lesson_id == 0) {
            json(0, 'Title, module id, Lesson Id are required');
        }

        if ($resource_id > 0) {
            $wpdb->query
            (
                $wpdb->prepare
                (
                    "UPDATE " . rtr_wpl_tr_resources() . " SET title = %s, description = %s, total_hrs = %s, external_link = %s, "
                    . "button_type = %s WHERE id = %d",
                    $title,
                    $description,
                    $hours,
                    $link,
                    $button_type,
                    $resource_id
                )
            );
            updatehours_and_resources($module_id, $course_id, $lesson_id);
            json(1, 'Exercise Updated');
        } else {

            $ord = $wpdb->get_var
            (
                $wpdb->prepare
                (
                    "SELECT MAX(ord) FROM " . rtr_wpl_tr_resources() . " WHERE lesson_id = %d",
                    $lesson_id
                )
            );

            $ord = $ord + 1;

            $result = $wpdb->query
            (
                $wpdb->prepare
                (
                    "INSERT INTO " . rtr_wpl_tr_resources() . " (ord, course_id, module_id, lesson_id, title, description, total_hrs, external_link, button_type, created_by, created_dt, updated_by) "
                    . "VALUES (%d, %d, %d, %d, %s,  %s, %s, %s, %s, %d, '%s', %d)",
                    $ord,
                    $course_id,
                    $module_id,
                    $lesson_id,
                    $title,
                    $description,
                    $hours,
                    $link,
                    $button_type,
                    $user_id,
                    $now,
                    $user_id
                )
            );
            updatehours_and_resources($module_id, $course_id, $lesson_id);
            json(1, 'Exercise Created', $result);
        }
    } elseif (esc_attr($_REQUEST['param']) == "delete_resource") {
        $id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
        $module_id = isset($_POST['module_id']) ? intval($_POST['module_id']) : 0;
        $course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
        $lesson_id = isset($_POST['lesson_id']) ? intval($_POST['lesson_id']) : 0;

        $wpdb->query
        (
            $wpdb->prepare
            (
                "DELETE FROM " . rtr_wpl_tr_resources() . " WHERE id = %d",
                $id
            )
        );

        updatehours_and_resources($module_id, $course_id, $lesson_id);
        json(1, 'Exercise Deleted');
    } elseif (esc_attr($_REQUEST['param']) == "save_settings") {
        $arr = array_map('sanitize_text_field', wp_unslash($_POST));
        $i = 0;
        $ids = isset($_REQUEST['ids']) ? array_map('sanitize_text_field', wp_unslash($_REQUEST['ids'])) : 0;

        if (count($ids) > 0) {
            foreach ($ids as $id) {

                $key = "key_$id";
                $keyname = $_POST["$key"];

                $valkey = "val_$id";
                $value = $_POST["$valkey"];

                $show = 0;
                $showelement = "show_$id";
                if (isset($_POST["$showelement"])) {
                    $show = 1;
                }

                $wpdb->query
                (
                    $wpdb->prepare
                    (
                        "UPDATE " . rtr_wpl_tr_setting() . " SET keyname = %s, keyvalue = %s, is_show = %d WHERE id = %d",
                        $keyname,
                        $value,
                        $show,
                        $id
                    )
                );
                $i++;
            }

            if ($i > 0)
                json(1, 'Settings Saved');
        }
        json(0, 'Problem In Saving. Please try after again.');
    } elseif (esc_attr($_REQUEST['param']) == "mark_resource") {

        $user_id = get_current_user_id();


        $userrole = new WP_User($user_id);
        $u_role = $userrole->roles[0];
        $uid = isset($_POST['uidadmincase']) ? intval(($_POST['uidadmincase'])) : 0;
        if ($uid > 0) {
            $user_id = $uid;
        }

        $resource_id = isset($_POST["resource_id"]) ? intval($_POST["resource_id"]) : 0;
        $status = isset($_POST["resource_id"]) ? esc_attr($_POST["status"]) : 'unmarked';
        $sts = 0;
        if ($status == 'unmarked') {
            $sts = 1;
        }

        $resource = $wpdb->get_row(
            $wpdb->prepare
            (
                "SELECT r.course_id, r.title as resource_title, c.title as course_title
						FROM " . rtr_wpl_tr_resources() . " r INNER JOIN " . rtr_wpl_tr_courses() . " c ON r.course_id
						= c.id WHERE r.id = %d",
                $resource_id
            )
        );

        if (empty($resource)) {
            json(0, 'Invalid Exercise');
        }

        $enroll_id = $wpdb->get_var
        (
            $wpdb->prepare
            (
                "SELECT id FROM " . rtr_wpl_tr_enrollment() . " WHERE course_id = %d AND user_id = %d",
                $resource->course_id,
                $user_id
            )
        );


        $now = date("Y-m-d H:i:s");
        // insert
        if ($sts == 1) {
        
            $res = $wpdb->query
            (
                $wpdb->prepare
                (
                    "INSERT INTO " . rtr_wpl_tr_resource_status() . " (course_id, resource_id, user_id, created_dt) "
                    . "VALUES ( %d, %d, %d, '%s')",
                    $resource->course_id,
                    $resource_id,
                    $user_id,
                    $now
                )
            );


            $url = site_url() . "/" . RTR_WPL_PAGE_SLUG . "?exercise_detail=" . $resource_id;

            /* sending mail code */

                $from_id = $user_id;            
                $admin_email = get_option('admin_email');
                $get_admin_id = $wpdb->get_var
                (
                    $wpdb->prepare
                    (
                        "SELECT ID FROM $wpdb->users WHERE user_email = %s",
                        $admin_email
                    )
                );
                $to_id = $get_admin_id;
           
                rtr_wpl_training_admin_notify($to_id, $from_id, array("files" => '', "links" => '', "mail_for" => "mark", "resource_title" => $resource->resource_title, "course_title" => $resource->course_title, "status" => "Marked", "url" => $url));
           

            $courseID = $resource->course_id;
            //count total resource of current res id
            $totalRes = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT count(id) from " . rtr_wpl_tr_resources() . " WHERE course_id = %d",
                    $courseID
                )
            );
            //find done statuse of resources
            $totalCompletedStatus = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT count(id) from " . rtr_wpl_tr_resource_status() . " WHERE course_id = %d and user_id = %d",
                    $courseID,
                    $user_id
                )
            );

            $percent = 0;
            if ($totalCompletedStatus > 0) {
                $percent = floor(($totalCompletedStatus / $totalRes) * 100);
            }

            json(1, 'Exercise marked', array('percent' => $percent));
        } else {
            // delete
            $res = $wpdb->query
            (
                $wpdb->prepare
                (
                    "DELETE FROM " . rtr_wpl_tr_resource_status() . " WHERE user_id = %d AND resource_id = %d",
                    $user_id,
                    $resource_id
                )
            );
            $courseID = $resource->course_id;
            //count total resource of current res id
            $totalRes = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT count(id) from " . rtr_wpl_tr_resources() . " WHERE course_id = %d",
                    $courseID
                )
            );
            //find done statuse of resources
            $totalCompletedStatus = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT count(id) from " . rtr_wpl_tr_resource_status() . " WHERE course_id = %d and user_id = %d",
                    $courseID,
                    $user_id
                )
            );

            $percent = 0;
            if ($totalCompletedStatus > 0) {
                $percent = floor(($totalCompletedStatus / $totalRes) * 100);
            }

            json(1, 'Exercise unmarked', array('percent' => $percent));
        }
    } elseif (esc_attr($_REQUEST['param']) == "listenrolled") {

        $course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;

        $enroll_detail = $wpdb->get_results
        (
            $wpdb->prepare
            (
                "SELECT distinct user_id from " . rtr_wpl_tr_resource_status() . " WHERE course_id = %d",
                $course_id
            )
        );
        $enrolledby = array();

        if (count($enroll_detail) > 0) {

            foreach ($enroll_detail as $key => $val) {
                $userdata = get_userdata($val->user_id);
                array_push(
                    $enrolledby,
                    array(
                        "display_name" => $userdata->display_name,
                        "user_email" => $userdata->user_email
                    )
                );
            }
        }
        json(1, '', $enrolledby);
    } elseif (esc_attr($_REQUEST['param']) == "add_projectexcersie") {

        $user_id = $current_user->ID;
        $now = date("Y-m-d H:i:s");
        $exid = isset($_POST['exid']) ? intval($_POST['exid']) : 0;
        $course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
        $module_id = isset($_POST['module_id']) ? intval($_POST['module_id']) : 0;

        $type = isset($_POST['type']) ? esc_attr(trim($_POST['type'])) : '';
        if ($type == '') {
            json(0, 'Something going wrong. please refresh page and try again.');
        }

        if ($type != 'module' && $type != 'course') {
            json(0, 'Type must be module or course.');
        }

        $title = isset($_POST['title']) ? esc_attr(trim($_POST['title'])) : '';
        $hours = isset($_POST['hours']) ? esc_attr(trim($_POST['hours'])) : '';
        $description = isset($_POST['description']) ? esc_attr($_REQUEST["description"]) : '';
        $description = stripcslashes($description);

        if ($title == '') {
            json(0, 'Title is required');
        }
        $status = 0;

        if (isset($_POST['isenabled'])) {
            $status = 1;
        }


        if ($exid == 0) {
            $rs = $wpdb->query
            (
                $wpdb->prepare
                (
                    "INSERT INTO " . rtr_wpl_tr_project_exercise() . " (type, status, module_id, course_id, title, description, total_hrs, created_by, created_dt, updated_by) "
                    . "VALUES (%s, %d, %d, %d, %s, %s, %s, %d, '%s', %d)",
                    $type,
                    $status,
                    $module_id,
                    $course_id,
                    $title,
                    $description,
                    $hours,
                    $user_id,
                    $now,
                    $user_id
                )
            );
            if (isset($_POST['isenabled'])) {
                update_hours($module_id, $course_id, 0, $hours);
            }
        } else {

            if ($module_id > 0) {
                $projex = $wpdb->get_row(
                    $wpdb->prepare
                    (
                        "SELECT total_hrs,status FROM " . rtr_wpl_tr_project_exercise() . " WHERE module_id = %d",
                        $module_id
                    )
                );
            } else {

                $projex = $wpdb->get_row(
                    $wpdb->prepare
                    (
                        "SELECT total_hrs,status FROM " . rtr_wpl_tr_project_exercise() . " WHERE course_id = %d",
                        $course_id
                    )
                );
            }

            $projhrs = $projex->total_hrs;

            $projsts = $projex->status;
            $rs = $wpdb->query
            (
                $wpdb->prepare
                (
                    "UPDATE " . rtr_wpl_tr_project_exercise() . " SET status = %d, title = %s, description = %s, total_hrs = %s "
                    . "WHERE id = %d",
                    $status,
                    $title,
                    $description,
                    $hours,
                    $exid
                )
            );

            if (isset($_POST['isenabled'])) {
                if ($projsts == 0)
                    update_hours($module_id, $course_id, 0, $hours);
                else
                    update_hours($module_id, $course_id, $projhrs, $hours);
            } else {
                if ($projsts == 1)
                    update_hours($module_id, $course_id, $projhrs, 0);
            }
        }

        json(1, 'Project Exercise Saved');
    } elseif (esc_attr($_REQUEST['param']) == "get_exercise") {

        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        $type = isset($_REQUEST['type']) ? esc_attr($_REQUEST['type']) : '';
        if ($type == 'course') {
            $detail = $wpdb->get_row(
                $wpdb->prepare
                (
                    "SELECT * FROM " . rtr_wpl_tr_project_exercise() . " WHERE course_id = %d AND module_id = 0",
                    $id
                )
            );
        } else {
            $detail = $wpdb->get_row(
                $wpdb->prepare
                (
                    "SELECT * FROM " . rtr_wpl_tr_project_exercise() . " WHERE module_id = %d",
                    $id
                )
            );
        }

        $exid = 0;
        if (!empty($detail)) {
            $exid = $detail->id;
        }

        $usertbl = $wpdb->prefix . "users";
        $pojects = $wpdb->get_results
        (
            $wpdb->prepare
            (
                "SELECT u.user_email,u.display_name,pe.type,pe.title,pe.description,pe.total_hrs,p.links "
                . " FROM " . rtr_wpl_tr_projects() . " p INNER JOIN " . rtr_wpl_tr_project_exercise() . " pe ON p.exercise_id = pe.id INNER JOIN "
                . "$usertbl u ON p.user_id = u.ID WHERE p.exercise_id = %d",
                $exid
            )
        );

        if (!empty($detail)) {
            $desc = html_entity_decode($detail->description);
            $detail->desc = $desc;
        }

        $ar = array('info' => $detail, "projects" => $pojects);

        json(1, 'detail', $ar);
    } elseif (esc_attr($_REQUEST['param']) == "get_submissions") {

        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

        $usertbl = $wpdb->prefix . "users";
        $pojects = $wpdb->get_results
        (
            $wpdb->prepare
            (
                "SELECT u.user_email,u.display_name,pe.type,pe.title,pe.description,pe.total_hrs,p.links,p.doc_files"
                . " FROM " . rtr_wpl_tr_projects() . " p LEFT JOIN " . rtr_wpl_tr_project_exercise() . " pe ON p.exercise_id = pe.id INNER JOIN "
                . "$usertbl u ON p.user_id = u.ID WHERE p.resource_id = %d",
                $id
            )
        );

        $ar = array("projects" => $pojects);

        $sr = 0;
        foreach ($ar['projects'] as $project_data) {
            $links = unserialize($project_data->links);
            $doc_files = unserialize($project_data->doc_files);
            $ar['projects'][$sr]->links = $links;
            $ar['projects'][$sr]->doc_files = $doc_files;
        }
        json(1, 'detail', $ar);
    } elseif (esc_attr($_REQUEST['param']) == "submit_project") {

        
        $updLinks = isset($_POST["upd_links"]) ? esc_attr($_POST["upd_links"]) : '';
        $updMedia = isset($_REQUEST["mediafiles"]) ? esc_attr($_REQUEST["mediafiles"]) : '';
        $resource_id = isset($_REQUEST['resourse_id']) ? intval($_REQUEST['resourse_id']) : '';
        $pageType = isset($_REQUEST['page_type']) ? esc_attr(trim($_REQUEST['page_type'])) : '';

        $user_id = get_current_user_id();
        $now = date("Y-m-d H:i:s");
        $linksArray = array();
        $mediaArray = array();
     
        if (!empty($updLinks)) {
            $updLinks = explode(",", $updLinks);

            foreach ($updLinks as $link) {
                if (!empty($link)) {
                    $linksArray[] = $link;
                }
            }
        }

        if (!empty($updMedia)) {
            $updMedia = explode(",", $updMedia);

            foreach ($updMedia as $media) {
                if (!empty($media)) {
                    $mediaArray[] = $media;
                }
            }
        }


        $user_id = get_current_user_id();
        $admin_email = get_option('admin_email');
        $from_id = get_current_user_id();
        $url = site_url() . "/" . RTR_WPL_PAGE_SLUG . "?exercise_detail=" . $resource_id;
        $now = date("Y-m-d H:i:s");
       
        $resource = $wpdb->get_row(
            $wpdb->prepare
            (
                "SELECT * FROM " . rtr_wpl_tr_resources() . " WHERE id = %d",
                $resource_id
            )
        );


        $resourc_data = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT links,doc_files FROM " . rtr_wpl_tr_projects() . " WHERE resource_id = %d AND user_id = %d",
                $resource_id,
                $user_id
            )
        );

        $msg = '';

        $resource_desc = $wpdb->get_row(
            $wpdb->prepare
            (
                "SELECT r.course_id, r.title as resource_title, c.title as course_title
						FROM " . rtr_wpl_tr_resources() . " r INNER JOIN " . rtr_wpl_tr_courses() . " c ON r.course_id
						= c.id WHERE r.id = %d",
                $resource_id
            ),
            ARRAY_A
        );
        
        
        if (!empty($resourc_data)) {
            $existing_links = unserialize($resourc_data->links);
            if (!empty($existing_links) && !empty($linksArray)) {
                $updatedLinks = serialize(array_unique(array_merge($existing_links, $linksArray), SORT_REGULAR));
            } elseif (empty($linksArray)) {
                $updatedLinks = serialize($existing_links);
            } else {
                $updatedLinks = serialize($linksArray);
            }

            $existing_medias = unserialize($resourc_data->doc_files);
            if (!empty($existing_medias) && !empty($mediaArray)) {
                $updatedMedias = serialize(array_unique(array_merge($existing_medias, $mediaArray), SORT_REGULAR));
            } elseif (empty($mediaArray)) {
                $updatedMedias = serialize($existing_medias);
            } else {
                $updatedMedias = serialize($mediaArray);
            }

            $wpdb->query
            (
                $wpdb->prepare
                (
                    "UPDATE " . rtr_wpl_tr_projects() . " SET links = '%s', doc_files = '%s' WHERE resource_id = %d and user_id = %d",
                    $updatedLinks,
                    $updatedMedias,
                    $resource_id,
                    $user_id
                )
            );


            // rtr_wpl_training_admin_notify(1, $from_id, array("files" => $updMedia, "links" => $updLinks, "mail_for" => "project", "resource_title" => $resource_desc['resource_title'], "course_title" => $resource_desc['course_title'], "status" => "Updated Project Files", "url" => $url));

            $msg = "Project files updated";
        } else {

            $updLinks = serialize($linksArray);
            $updMedia = serialize($mediaArray);
            
            $wpdb->query
            (
                $wpdb->prepare
                (
                    "INSERT INTO " . rtr_wpl_tr_projects() . " (user_id, resource_id, links,doc_files, created_by, created_dt, updated_by) "
                    . "VALUES (%d, %d, %s,%s, %d, '%s', %d)",
                    $user_id,
                    $resource_id,
                    $updLinks,
                    $updMedia,
                    $user_id,
                    $now,
                    $user_id
                )
            );

            $wpdb->query
            (
                $wpdb->prepare
                (
                    "INSERT INTO " . rtr_wpl_tr_resource_status() . " (course_id, resource_id, user_id, created_dt) "
                    . "VALUES (%d, %d, %d, '%s')",
                    $resource->course_id,
                    $resource_id,
                    $user_id,
                    $now
                )
            );

            $admin_email = get_option('admin_email');
            $get_admin_id = $wpdb->get_var
            (
                $wpdb->prepare
                (
                    "SELECT ID FROM $wpdb->users WHERE user_email = %s",
                    $admin_email
                )
            );
            $to_id = $get_admin_id;


            rtr_wpl_training_admin_notify( $to_id, $from_id, array("files" => $mediaArray, "links" => $linksArray, "mail_for" => "project", "resource_title" => $resource_desc['resource_title'], "course_title" => $resource_desc['course_title'], "status" => "Submitted Project Files", "url" => $url));

            $msg = "Project files submitted";
        }

        ob_start(); // start output buffer

        if ($pageType == "course") {
            $_REQUEST['course'] = $resource->course_id;
        } elseif ($pageType == "lesson") {
            $_REQUEST['lesson_detail'] = $resource->lesson_id;
            $_REQUEST['course'] = $resource->course_id;
        } elseif ($pageType == "exercise") {
            $_REQUEST['exercise_detail'] = $resource->id;
        }

        $file = RTR_WPL_COUNT_PLUGIN_DIR . '/views/course_detail.php';
        include $file;
        $template = ob_get_contents(); // get contents of buffer
        ob_end_clean();

        $courseID = $resource->course_id;
        //count total resource of current res id
        $totalRes = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT count(id) from " . rtr_wpl_tr_resources() . " WHERE course_id = %d",
                $courseID
            )
        );

        //find done statuse of resources
        $totalCompletedStatus = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT count(id) from " . rtr_wpl_tr_resource_status() . " WHERE course_id = %d and user_id = %d",
                $courseID,
                $user_id
            )
        );
        $percent = 0;
        if ($totalCompletedStatus > 0) {
            $percent = floor(($totalCompletedStatus / $totalRes) * 100);
        }

        json(1, $msg, array("template" => $template, 'percent' => $percent));
    } elseif (esc_attr($_REQUEST['param']) == "submit_links") {

        $user_id = get_current_user_id();

        $userrole = new WP_User($user_id);
        $u_role = $userrole->roles[0];

        if (esc_attr($_REQUEST['do']) == "noupdate") {

            if ($u_role == 'administrator') {
                $uid = isset($_POST['uidadmincase']) ? intval(($_POST['uidadmincase'])) : 0;
                if ($uid > 0) {
                    $user_id = $uid;
                }
            }
            $now = date("Y-m-d H:i:s");
            $exe_id = isset($_POST['proj']) ? intval(trim($_POST['proj'])) : 0;
            $links = isset($_POST['links']) ? esc_attr(trim($_POST['links'])) : '';
            $dattyp = isset($_POST['dattyp']) ? esc_attr(trim($_POST['dattyp'])) : '';
            $resourceurl = site_url() . "/" . RTR_WPL_PAGE_SLUG . "?exercise_detail=" . $exe_id;

            if ($links == '') {
                //json(0,'Please submit links');
            }
            if ($dattyp == 'exercise') {
                $proj_exe = $wpdb->get_var(
                    $wpdb->prepare
                    (
                        "SELECT count(id) as total FROM " . rtr_wpl_tr_project_exercise() . " WHERE id = %d",
                        $exe_id
                    )
                );
                if ($proj_exe == 0)
                    json(0, 'Invalid Project');

                $wpdb->query(
                    $wpdb->prepare
                    (
                        "DELETE FROM " . rtr_wpl_tr_projects() . " WHERE exercise_id = %d AND user_id = %d",
                        $exe_id,
                        $user_id
                    )
                );


                $wpdb->query
                (
                    $wpdb->prepare
                    (
                        "INSERT INTO " . rtr_wpl_tr_projects() . " (user_id, exercise_id, links, created_by, created_dt, updated_by) "
                        . "VALUES (%d, %d, %s, %d, '%s', %d)",
                        $user_id,
                        $exe_id,
                        $links,
                        $user_id,
                        $now,
                        $user_id
                    )
                );
            } else {

                $resource_id = $exe_id;
                $resource = $wpdb->get_row(
                    $wpdb->prepare
                    (
                        "SELECT * FROM " . rtr_wpl_tr_resources() . " WHERE id = %d",
                        $resource_id
                    )
                );
                if (empty($resource))
                    json(0, 'Invalid Resource');

                $wpdb->query(
                    $wpdb->prepare
                    (
                        "DELETE FROM " . rtr_wpl_tr_projects() . " WHERE resource_id = %d AND user_id = %d",
                        $resource_id,
                        $user_id
                    )
                );


                $wpdb->query
                (
                    $wpdb->prepare
                    (
                        "INSERT INTO " . rtr_wpl_tr_projects() . " (user_id, resource_id, links, created_by, created_dt, updated_by) "
                        . "VALUES (%d, %d, %s, %d, '%s', %d)",
                        $user_id,
                        $resource_id,
                        $links,
                        $user_id,
                        $now,
                        $user_id
                    )
                );

                $enroll_id = $wpdb->get_var
                (
                    $wpdb->prepare
                    (
                        "SELECT id FROM " . rtr_wpl_tr_enrollment() . " WHERE course_id = %d AND user_id = %d",
                        $resource->course_id,
                        $user_id
                    )
                );

                $wpdb->query
                (
                    $wpdb->prepare
                    (
                        "DELETE FROM " . rtr_wpl_tr_resource_status() . " WHERE course_id = %d AND resource_id = %d AND user_id = %d",
                        $resource->course_id,
                        $resource_id,
                        $user_id
                    )
                );

                $wpdb->query
                (
                    $wpdb->prepare
                    (
                        "INSERT INTO " . rtr_wpl_tr_resource_status() . " (enrollment_id, course_id, resource_id, user_id, created_dt) "
                        . "VALUES (%d, %d, %d, %d, '%s')",
                        $enroll_id,
                        $resource->course_id,
                        $resource_id,
                        $user_id,
                        $now
                    )
                );
            }

            $get_prj_details = $wpdb->get_results("select * from " . rtr_wpl_tr_projects() . " order by id desc limit 1");

            $last_id = '';
            foreach ($get_prj_details as $detail) {
                $last_id = $detail->resource_id;
            }

            if (intval($_REQUEST['fstatus']) == 1) {

                json(1, "resource id found", array("id" => $last_id));
            } else {

                /* getting resource details */
                $resource_id = $exe_id;
                $resource = $wpdb->get_row(
                    $wpdb->prepare
                    (
                        "SELECT r.course_id, r.title as resource_title, c.title as course_title
								FROM " . rtr_wpl_tr_resources() . " r INNER JOIN " . rtr_wpl_tr_courses() . " c ON r.course_id
								= c.id WHERE r.id = %d",
                        $resource_id
                    )
                );

                json(1, 'Project Submitted', "");
            }
        } elseif (esc_attr($_REQUEST['do']) == "update") {
			if ( ! current_user_can( 'upload_files' ) ) { 
			wp_send_json_error(array('message' => 'You do not have sufficient permissions to perform this action. Please check your user role.'));
					wp_die(); // Always call wp_die() after an AJAX request
			}

            /* getting resource details */
            $resource_id = intval($_REQUEST['resourceid']);
            $url = site_url() . "/" . RTR_WPL_PAGE_SLUG . "?exercise_detail=" . $resource_id;

            $resource = $wpdb->get_row(
                $wpdb->prepare
                (
                    "SELECT r.course_id, r.title as resource_title, c.title as course_title
								FROM " . rtr_wpl_tr_resources() . " r INNER JOIN " . rtr_wpl_tr_courses() . " c ON r.course_id
								= c.id WHERE r.id = %d",
                    $resource_id
                )
            );

            $links = isset($_REQUEST['links']) ? esc_attr(trim($_REQUEST['links'])) : '';

            /* custom code to take project_id after insert */

            /* Sending Mail to Coach Code - custom */

            $user_id = get_current_user_id();


            /* ../Code ends */

            $names = '';
            $file_links = '';
            $other_links = '';

            $filepath = RTR_WPL_COUNT_PLUGIN_URL . "/assets/files/";
            $directrypath = RTR_WPL_COUNT_PLUGIN_DIR . "/assets/files/";
            foreach ($_FILES as $image) {
                @move_uploaded_file($image['tmp_name'], $directrypath . $image['name']);
                $names .= $filepath . $image['name'] . " , ";
                $file_links .= "<a href='" . $filepath . $image['name'] . "'>" . $image['name'] . "</a> , ";
                $other_links .= "<a href='" . $filepath . $image['name'] . "' target='_blank'>" . $image['name'] . "</a><br/>";
            }


            $updated = $wpdb->update(rtr_wpl_tr_projects(), array("doc_files" => trim($names, ",")), array("resource_id" => $resource_id, "user_id" => $user_id));

            if ($updated) {

                $email_data = $wpdb->get_results("select * from wp_mentor_assign where user_id='" . $user_id . "'");
                $to_id = 0;
                $from_id = $user_id;

                if (!empty($email_data)) {
                    foreach ($email_data as $results) {
                        $to_id = $results->mentor_id;
                        if ($to_id > 0) {
                            //emailtocoachnotify($to_id, $from_id, "submitted", $resource->course_title, $resource->resource_title, $links, trim($file_links, ","), "Project", $url);
                        }
                    }
                }

                if ($to_id == 0) {

                    $admin_email = get_option('admin_email');
                    $get_admin_id = $wpdb->get_var
                    (
                        $wpdb->prepare
                        (
                            "SELECT ID FROM $wpdb->users WHERE user_email = %s",
                            $admin_email
                        )
                    );
                    $to_id = $get_admin_id;
                    //emailtocoachnotify($to_id, $from_id, "submitted", $resource->course_title, $resource->resource_title, $links, trim($file_links, ","), "Project", $url);
                }

                json(1, 'Project Submitted', $other_links);
            } else {
                json(0, "Failed 1");
            }
        } else {
            json(0, "Failed 2");
        }
    } elseif (esc_attr($_REQUEST['param']) == "delete_project_links") {

        $link = isset($_REQUEST['link']) ? trim($_REQUEST['link']) : "";
        $res = isset($_REQUEST['res']) ? intval($_REQUEST['res']) : "";
        $percent = 0;
        $visible = 0;

        $user_id = get_current_user_id();

        $slct_links = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * from " . rtr_wpl_tr_projects() . " WHERE resource_id = %d AND user_id = %d",
                $res,
                $user_id
            )
        );

        $courseID = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT course_id from " . rtr_wpl_tr_resources() . " WHERE id = %d",
                $res
            )
        );

        //count total resource of current res id
        $totalRes = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT count(id) from " . rtr_wpl_tr_resources() . " WHERE course_id = %d",
                $courseID
            )
        );

        //find done statuse of resources
        $totalCompletedStatus = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT count(id) from " . rtr_wpl_tr_resource_status() . " WHERE course_id = %d and user_id = %d",
                $courseID,
                $user_id
            )
        );

        if ($totalCompletedStatus > 0) {
            $percent = floor(($totalCompletedStatus / $totalRes) * 100);
        }

        if (!empty($slct_links)) {
            $links = unserialize($slct_links->links);
            foreach ($links as $index => $lnk) {
                if ($link == trim($lnk)) {
                    unset($links[$index]);
                }
            }
            if (!empty($links)) {
                $links = serialize($links);
                $wpdb->update(
                    rtr_wpl_tr_projects(),
                    array(
                        "links" => $links
                    ),
                    array(
                        "resource_id" => $res,
                        "user_id" => $user_id
                    )
                );
            } else {
                $doc_fls = unserialize($slct_links->doc_files);
                if (empty($doc_fls)) {
                    $wpdb->delete(
                        rtr_wpl_tr_projects(),
                        array(
                            "resource_id" => $res,
                            "user_id" => $user_id
                        )
                    );
                    $wpdb->delete(
                        rtr_wpl_tr_resource_status(),
                        array(
                            "resource_id" => $res,
                            "user_id" => $user_id
                        )
                    );

                    $percent = 0;

                    //find done statuse of resources
                    $totalCompletedStatus = $wpdb->get_var(
                        $wpdb->prepare(
                            "SELECT count(id) from " . rtr_wpl_tr_resource_status() . " WHERE course_id = %d and user_id = %d",
                            $courseID,
                            $user_id
                        )
                    );

                    if ($totalCompletedStatus > 0) {
                        $percent = floor(($totalCompletedStatus / $totalRes) * 100);
                    }

                    $visible = 1;
                } else {
                    $links = serialize($links);
                    $wpdb->update(
                        rtr_wpl_tr_projects(),
                        array(
                            "links" => $links
                        ),
                        array(
                            "resource_id" => $res,
                            "user_id" => $user_id
                        )
                    );
                }
            }
        }
        json(1, "files deleted", array("percent" => $percent, "visible" => $visible));
    } elseif (esc_attr($_REQUEST['param']) == "delete_project_media") {

        $link = isset($_REQUEST['media']) ? trim($_REQUEST['media']) : "";
        $res = isset($_REQUEST['res']) ? intval($_REQUEST['res']) : "";
        $percent = 0;
        $visible = 0;
        $user_id = get_current_user_id();

        $slct_links = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * from " . rtr_wpl_tr_projects() . " WHERE resource_id = %d AND user_id = %d",
                $res,
                $user_id
            )
        );

        $courseID = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT course_id from " . rtr_wpl_tr_resources() . " WHERE id = %d",
                $res
            )
        );

        //count total resource of current res id
        $totalRes = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT count(id) from " . rtr_wpl_tr_resources() . " WHERE course_id = %d",
                $courseID
            )
        );
        //find done statuse of resources
        $totalCompletedStatus = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT count(id) from " . rtr_wpl_tr_resource_status() . " WHERE course_id = %d and user_id = %d",
                $courseID,
                $user_id
            )
        );

        if ($totalCompletedStatus > 0) {
            $percent = floor(($totalCompletedStatus / $totalRes) * 100);
        }

        if (!empty($slct_links)) {
            $links = unserialize($slct_links->doc_files);
            foreach ($links as $index => $lnk) {
                if ($link == trim($lnk)) {
                    unset($links[$index]);
                }
            }
            if (!empty($links)) {
                $links = serialize($links);
                $wpdb->update(
                    rtr_wpl_tr_projects(),
                    array(
                        "doc_files" => $links
                    ),
                    array(
                        "resource_id" => $res,
                        "user_id" => $user_id
                    )
                );
            } else {
                $doc_fls = unserialize($slct_links->links);
                if (empty($doc_fls)) { // completly delete
                    $wpdb->delete(
                        rtr_wpl_tr_projects(),
                        array(
                            "resource_id" => $res,
                            "user_id" => $user_id
                        )
                    );
                    $wpdb->delete(
                        rtr_wpl_tr_resource_status(),
                        array(
                            "resource_id" => $res,
                            "user_id" => $user_id
                        )
                    );
                    $percent = 0;
                    //find done statuse of resources
                    $totalCompletedStatus = $wpdb->get_var(
                        $wpdb->prepare(
                            "SELECT count(id) from " . rtr_wpl_tr_resource_status() . " WHERE course_id = %d and user_id = %d",
                            $courseID,
                            $user_id
                        )
                    );

                    if ($totalCompletedStatus > 0) {
                        $percent = floor(($totalCompletedStatus / $totalRes) * 100);
                    }
                    $visible = 1;
                } else { // update
                    $links = serialize($links);
                    $wpdb->update(
                        rtr_wpl_tr_projects(),
                        array(
                            "doc_files" => $links
                        ),
                        array(
                            "resource_id" => $res,
                            "user_id" => $user_id
                        )
                    );
                }
            }
        }
        json(1, "files deleted", array("percent" => $percent, "visible" => $visible));
    } elseif (esc_attr($_REQUEST['param']) == "get_links") {

        $user_id = get_current_user_id();
        $userrole = new WP_User($user_id);
        $u_role = $userrole->roles[0];
        if ($u_role == 'administrator') {
            $uid = isset($_POST['uidadmincase']) ? intval(($_POST['uidadmincase'])) : 0;
            if ($uid > 0) {
                $user_id = $uid;
            }
        }

        $typ = isset($_POST['typ']) ? esc_attr(trim($_POST['typ'])) : 'exercise';
        if ($typ == 'resource') {
            $resource_id = isset($_POST['resource_id']) ? intval(trim($_POST['resource_id'])) : 0;
            $proj_links = $wpdb->get_row(
                $wpdb->prepare
                (
                    "SELECT links,doc_files FROM " . rtr_wpl_tr_projects() . " WHERE resource_id = %d AND user_id = %d",
                    $resource_id,
                    $user_id
                )
            );
            if (!empty($proj_links)) {
                $links = $proj_links->links;
                $links = unserialize($links);
                $datalinks = '';
                $medialinks = '';

                if (count($links) > 0) {

                    foreach ($links as $link) {
                        $datalinks .= "<a href='" . $link . "' target='_blank'>" . $link . "</a> &nbsp;&nbsp;&nbsp;<span  class='delete-link' data-res='" . $resource_id . "' title='delete' data-link='" . $link . "'><i class='fa fa-trash' aria-hidden='true'></i></span><br/>";
                    }
                }

                $docfiles = unserialize($proj_links->doc_files);
                if (!empty($docfiles)) {
                    foreach ($docfiles as $doc) {
                        $d = explode("/", $doc);
                        $filedoc = $d[count($d) - 1];
                        $medialinks .= "<a href='" . $doc . "' download>" . $filedoc . "</a> &nbsp;&nbsp;&nbsp;<span class='delete-media' title='delete' data-res='" . $resource_id . "' data-link='" . $doc . "'><i class='fa fa-trash' aria-hidden='true'></i></span><br/>";
                    }
                }

                $proj_links = array("links" => $datalinks, "files" => $medialinks);
            }
        } else {
            $exe_id = isset($_POST['proj']) ? intval(trim($_POST['proj'])) : 0;
            $proj_links = $wpdb->get_row(
                $wpdb->prepare
                (
                    "SELECT links FROM " . rtr_wpl_tr_projects() . " WHERE exercise_id = %d AND user_id = %d",
                    $exe_id,
                    $user_id
                )
            );
        }

        if (empty($proj_links))
            json(0, 'Not Submitted');
        else
            json(1, 'Submitted', $proj_links);
    } elseif (esc_attr($_REQUEST['param']) == "remove_project_resources") {

        $user_id = get_current_user_id();

        $resource_id = isset($_REQUEST['resource_id']) ? intval($_REQUEST['resource_id']) : '';
        $resource_type = isset($_REQUEST['type']) ? esc_attr($_REQUEST['type']) : '';
        $pageType = isset($_REQUEST['page']) ? esc_attr($_REQUEST['page']) : '';

        if (!empty($resource_id) && !empty($resource_type)) {

            $resource = $wpdb->get_row(
                $wpdb->prepare
                (
                    "SELECT * FROM " . rtr_wpl_tr_resources() . " WHERE id = %d ",
                    $resource_id
                )
            );
            if (empty($resource)) {
                json(0, "Invalid id");
            }

            $resource_avail = $wpdb->get_row(
                $wpdb->prepare
                (
                    "SELECT links,doc_files FROM " . rtr_wpl_tr_projects() . " WHERE resource_id = %d AND user_id = %d",
                    $resource_id,
                    $user_id
                )
            );
            $removed_status = 0;
            if ($resource_type == "file") {

                $wpdb->query
                (
                    $wpdb->prepare
                    (
                        "UPDATE " . rtr_wpl_tr_projects() . " SET doc_files = '' WHERE resource_id = %d and user_id = %d",
                        $resource_id,
                        $user_id
                    )
                );
                if (empty($resource_avail->links)) {
                    $wpdb->query(
                        $wpdb->prepare
                        (
                            "DELETE FROM " . rtr_wpl_tr_projects() . " WHERE resource_id = %d AND user_id = %d",
                            $resource_id,
                            $user_id
                        )
                    );
                    $wpdb->query(
                        $wpdb->prepare
                        (
                            "DELETE FROM " . rtr_wpl_tr_resource_status() . " WHERE resource_id = %d AND user_id = %d",
                            $resource_id,
                            $user_id
                        )
                    );
                    $removed_status = 1;
                }
                ob_start(); // start output buffer
                if ($pageType == "course") {
                    $_REQUEST['course'] = $resource->course_id;
                } elseif ($pageType == "lesson") {
                    $_REQUEST['lesson_detail'] = $resource->lesson_id;
                    $_REQUEST['course'] = $resource->course_id;
                } elseif ($pageType == "exercise") {
                    $_REQUEST['exercise_detail'] = $resource->id;
                }
                $file = RTR_WPL_COUNT_PLUGIN_DIR . '/views/course_detail.php';
                include $file;
                $template = ob_get_contents(); // get contents of buffer
                ob_end_clean();
                json(1, "File remved", array("all" => $removed_status, "template" => $template));
            } elseif ($resource_type == "link") {
                $wpdb->query
                (
                    $wpdb->prepare
                    (
                        "UPDATE " . rtr_wpl_tr_projects() . " SET links = '' WHERE resource_id = %d and user_id = %d",
                        $resource_id,
                        $user_id
                    )
                );
                if (empty($resource_avail->doc_files)) {
                    $wpdb->query(
                        $wpdb->prepare
                        (
                            "DELETE FROM " . rtr_wpl_tr_projects() . " WHERE resource_id = %d AND user_id = %d",
                            $resource_id,
                            $user_id
                        )
                    );
                    $wpdb->query(
                        $wpdb->prepare
                        (
                            "DELETE FROM " . rtr_wpl_tr_resource_status() . " WHERE resource_id = %d AND user_id = %d",
                            $resource_id,
                            $user_id
                        )
                    );
                    $removed_status = 1;
                }
                ob_start(); // start output buffer
                if ($pageType == "course") {
                    $_REQUEST['course'] = $resource->course_id;
                } elseif ($pageType == "lesson") {
                    $_REQUEST['lesson_detail'] = $resource->lesson_id;
                    $_REQUEST['course'] = $resource->course_id;
                } elseif ($pageType == "exercise") {
                    $_REQUEST['exercise_detail'] = $resource->id;
                }
                $file = RTR_WPL_COUNT_PLUGIN_DIR . '/views/course_detail.php';
                include $file;
                $template = ob_get_contents(); // get contents of buffer
                ob_end_clean();
                json(1, "Links removed", array("all" => $removed_status, "template" => $template));
            }
        } else {
            json(0, "Resource id is empty");
        }
    } elseif (esc_attr($_REQUEST['param']) == "remove_links") {
        $user_id = get_current_user_id();
        $userrole = new WP_User($user_id);
        $u_role = $userrole->roles[0];
        if ($u_role == 'administrator') {
            $uid = isset($_POST['uidadmincase']) ? intval(($_POST['uidadmincase'])) : 0;
            if ($uid > 0) {
                $user_id = $uid;
            }
        }
        $exe_id = isset($_POST['proj']) ? intval(trim($_POST['proj'])) : 0;
        $typ = isset($_POST['datatyp']) ? esc_attr(trim($_POST['datatyp'])) : 'exercise';
        if ($typ == 'resource') {

            $resource_id = $exe_id;
            $wpdb->query(
                $wpdb->prepare
                (
                    "DELETE FROM " . rtr_wpl_tr_projects() . " WHERE resource_id = %d AND user_id = %d",
                    $resource_id,
                    $user_id
                )
            );

            /// also set completed


            $wpdb->query
            (
                $wpdb->prepare
                (
                    "DELETE FROM " . rtr_wpl_tr_resource_status() . " WHERE user_id = %d AND resource_id = %d",
                    $user_id,
                    $resource_id
                )
            );


            /// also set completed
        } else {
            $wpdb->query(
                $wpdb->prepare
                (
                    "DELETE FROM " . rtr_wpl_tr_projects() . " WHERE exercise_id = %d AND user_id = %d",
                    $exe_id,
                    $user_id
                )
            );
        }
        json(1, 'Project Links Deleted');
    } elseif (esc_attr($_REQUEST['param']) == "add_video") {
        $typematerial = isset($_REQUEST['typematerial']) ? esc_attr($_REQUEST['typematerial']) : "lesson";
        $code = isset($_POST['embedcode']) ? esc_attr($_POST['embedcode']) : 0;
        $code = stripslashes($code);
        $user_id = $current_user->ID;

        $resource_id = isset($_POST['resource_id']) ? intval($_POST['resource_id']) : 0;
        $lesson_id = isset($_POST['lesson_id']) ? intval($_POST['lesson_id']) : 0;


        if ($typematerial == "lesson") {
            $lesson = $wpdb->get_var(
                $wpdb->prepare
                (
                    "SELECT count(*) as totaL FROM " . rtr_wpl_tr_lessons() . " WHERE id = %d ",
                    $lesson_id
                )
            );

            if ($lesson == 0)
                json(0, 'Invalid Lesson');
        } else {

            $resource = $wpdb->get_var(
                $wpdb->prepare
                (
                    "SELECT count(*) as totaL FROM " . rtr_wpl_tr_resources() . " WHERE id = %d ",
                    $resource_id
                )
            );

            if ($resource == 0)
                json(0, 'Invalid Exercise');
        }

        if ($typematerial == "lesson") {
            $video = $wpdb->get_row(
                $wpdb->prepare
                (
                    "SELECT id FROM " . rtr_wpl_tr_media() . " WHERE lesson_id = %d AND type= 'video' ",
                    $lesson_id
                )
            );
        } else {
            $video = $wpdb->get_row(
                $wpdb->prepare
                (
                    "SELECT id FROM " . rtr_wpl_tr_media() . " WHERE resource_id = %d AND type= 'video' ",
                    $resource_id
                )
            );
        }

        // insert else update
        if (empty($video)) {

            if ($typematerial == "lesson") {
                $wpdb->query
                (
                    $wpdb->prepare
                    (
                        "INSERT INTO " . rtr_wpl_tr_media() . " (lesson_id, type, source, path, created_by) "
                        . "VALUES (%d, %s, %s, %s, %d)",
                        $lesson_id,
                        'video',
                        'embed',
                        $code,
                        $user_id
                    )
                );
            } else {
                $wpdb->query
                (
                    $wpdb->prepare
                    (
                        "INSERT INTO " . rtr_wpl_tr_media() . " (resource_id, type, source, path, created_by) "
                        . "VALUES (%d, %s, %s, %s, %d)",
                        $resource_id,
                        'video',
                        'embed',
                        $code,
                        $user_id
                    )
                );
            }
            json(1, 'Video Added');
        } else {

            $wpdb->query
            (
                $wpdb->prepare
                (
                    "UPDATE " . rtr_wpl_tr_media() . " SET path = %s WHERE id = %d",
                    $code,
                    $video->id
                )
            );
            json(1, 'Video Updated');
        }
    } elseif (esc_attr($_REQUEST['param']) == "save_media_doc") {

        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        $typematerial = isset($_REQUEST['typematerial']) ? esc_attr($_REQUEST['typematerial']) : "lesson";
        $links = isset($_REQUEST['links']) ? esc_attr($_REQUEST['links']) : '';
        $names = isset($_REQUEST['names']) ? esc_attr($_REQUEST['names']) : '';
        $now = time();
        $x = 0;

        if ($typematerial == 'lesson') {
            $colname = 'lesson_id';
            $tbl = rtr_wpl_tr_lessons();
        } else {
            $colname = 'resource_id';
            $tbl = rtr_wpl_tr_resources();
        }

        $user_id = $current_user->ID;

        $res = $wpdb->get_var(
            $wpdb->prepare
            (
                "SELECT count(*) as totaL FROM " . $tbl . " WHERE id = %d ",
                $id
            )
        );

        if ($res == 0)
            json(0, 'Invalid ' . $typematerial);


        if (!empty($links)) {
            $links = explode(",", $links);
            $names = explode(",", $names);
            for ($i = 0; $i < count($links); $i++) {
                $wpdb->query
                (
                    $wpdb->prepare
                    (
                        "INSERT INTO " . rtr_wpl_tr_media() . " ($colname, type, source, path, extra_info, created_by) "
                        . "VALUES (%d, %s, %s, %s, %s, %d)",
                        $id,
                        'document',
                        'upload',
                        $links[$i],
                        $names[$i],
                        $user_id
                    )
                );
            }

            json(1, 'Documents Uploaded', '');
        }
    } elseif (esc_attr($_REQUEST['param']) == "save_doc") {

        if (isset($_FILES) && count($_FILES) > 0) {
            $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
            $typematerial = isset($_REQUEST['typematerial']) ? esc_attr($_REQUEST['typematerial']) : "lesson";
            $now = time();
            $x = 0;
            $ids = array();
            $pos = array();
            $file_links = '';

            if ($typematerial == 'lesson') {
                $colname = 'lesson_id';
                $tbl = rtr_wpl_tr_lessons();
            } else {
                $colname = 'resource_id';
                $tbl = rtr_wpl_tr_resources();
            }

            $user_id = $current_user->ID;

            $res = $wpdb->get_var(
                $wpdb->prepare
                (
                    "SELECT count(*) as totaL FROM " . $tbl . " WHERE id = %d ",
                    $id
                )
            );

            if ($res == 0)
                json(0, 'Invalid ' . $typematerial);


            for ($flag = 0; $flag < count($_FILES); $flag++) {

                if ($_FILES["file-" . $flag]["name"] != "") {

                    if ($_FILES["file-" . $flag]["error"] > 0) {
                        continue;
                    } else {

                        $ext = trim(strtolower(pathinfo($_FILES["file-" . $flag]["name"], PATHINFO_EXTENSION)));

                        if ($ext == "php" || $ext == "sql" || $ext == "js") {
                            continue;
                        }

                        $othername = $_FILES["file-" . $flag]["name"];

                        $filenm = str_replace(" ", "_", $_FILES["file-" . $flag]["name"]);
                        // Path to upload file

                        if ($typematerial == 'lesson') {
                            $filename = $now . '_lesson' . $id . '_' . $filenm;
                        } else {
                            $filename = $now . '_resource' . $id . '_' . $filenm;
                        }


                        $baefilepath = '/assets/docs/' . $filename;

                        $dir = RTR_WPL_COUNT_PLUGIN_DIR . $baefilepath;

                        $result = move_uploaded_file($_FILES["file-" . $flag]["tmp_name"], $dir);

                        $wpdb->query
                        (
                            $wpdb->prepare
                            (
                                "INSERT INTO " . rtr_wpl_tr_media() . " ($colname, type, source, path, extra_info, created_by) "
                                . "VALUES (%d, %s, %s, %s, %s, %d)",
                                $id,
                                'document',
                                'upload',
                                $baefilepath,
                                $othername,
                                $user_id
                            )
                        );
                        $lastid = $wpdb->insert_id;
                        array_push($ids, $lastid);
                        array_push($pos, $flag);
                        $x++;
                    }
                }
            }
            if ($x > 0) {
                $arfinal = array('ids' => $ids, 'pos' => $pos);
                json(1, 'Documents Uploaded', $arfinal);
            }

            json(0, 'Failed To Upload');
        }
    } elseif (esc_attr($_REQUEST['param']) == "save_doc_titles") {

        $ids = isset($_REQUEST['ids']) ? esc_attr($_REQUEST['ids']) : "";
        $ids = array_map('intval', explode(',', $ids));

        $pos = isset($_REQUEST['pos']) ? esc_attr($_REQUEST['pos']) : "";
        $pos = array_map('intval', explode(',', $pos));

        $i = 0;
        if (count($ids) == 0) {
            json(0, 'Failed to upload..');
        }

        foreach ($ids as $id) {

            if (in_array($i, $pos)) {

                $title = $_POST['doctitles'][$i];
                if ($title != '') {
                    $wpdb->query
                    (
                        $wpdb->prepare
                        (
                            "UPDATE " . rtr_wpl_tr_media() . " SET extra_info = %s WHERE id = %d",
                            $title,
                            $id
                        )
                    );
                }
            }
            $i++;
        }
        if ($i > 0) {
            json(1, 'Document(s) uploaded');
        } else {
            json(0, 'Failed to upload');
        }
    } elseif (esc_attr($_REQUEST['param']) == "delete_doc") {

        $doc_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

        $doc = $wpdb->get_row(
            $wpdb->prepare
            (
                "SELECT path FROM " . rtr_wpl_tr_media() . " WHERE id = %d AND type = 'document'",
                $doc_id
            )
        );
        if (empty($doc)) {
            json(0, 'Invalid Document');
        }

        $path = RTR_WPL_COUNT_PLUGIN_DIR . "/" . $doc->path;
        @unlink($path);
        $wpdb->query
        (
            $wpdb->prepare
            (
                "DELETE FROM " . rtr_wpl_tr_media() . " WHERE id = %d",
                $doc_id
            )
        );
        json(1, 'Document Deleted');
    } elseif (esc_attr($_REQUEST['param']) == "add_note") {

        $typematerial = isset($_REQUEST['typematerial']) ? esc_attr($_REQUEST['typematerial']) : "lesson";
        $now = date('Y-m-d H:i:s');
        $user_id = $current_user->ID;
        $notetxt = isset($_POST['notetxt']) ? esc_attr($_POST['notetxt']) : '';
        $notetxt = html_entity_decode($notetxt);

        $resource_id = isset($_POST['resourid']) ? intval($_POST['resourid']) : 0;
        $lesson_id = isset($_POST['lesonid']) ? intval($_POST['lesonid']) : 0;
        $note_id = isset($_POST['noteid']) ? intval($_POST['noteid']) : 0;

        if ($typematerial == 'lesson') {
            $lesson = $wpdb->get_var(
                $wpdb->prepare
                (
                    "SELECT count(*) as total FROM " . rtr_wpl_tr_lessons() . " WHERE id = %d ",
                    $lesson_id
                )
            );

            if ($lesson == 0)
                json(0, 'Invalid Lesson');
        } else {
            $resource = $wpdb->get_var(
                $wpdb->prepare
                (
                    "SELECT count(*) as total FROM " . rtr_wpl_tr_resources() . " WHERE id = %d ",
                    $resource_id
                )
            );

            if ($resource == 0)
                json(0, 'Invalid Exercise');
        }

        $has_note = $wpdb->get_var(
            $wpdb->prepare
            (
                "SELECT count(*) as total FROM " . rtr_wpl_tr_lesson_notes() . " WHERE id = %d ",
                $note_id
            )
        );

        if ($has_note > 0) {

            $wpdb->query
            (
                $wpdb->prepare
                (
                    "UPDATE " . rtr_wpl_tr_lesson_notes() . " SET note = %s WHERE id = %d",
                    $notetxt,
                    $note_id
                )
            );
            json(1, 'Notes Updated');
        } else {

            if ($typematerial == 'lesson') {
                $res = $wpdb->query
                (
                    $wpdb->prepare
                    (
                        "INSERT INTO " . rtr_wpl_tr_lesson_notes() . " (lesson_id, note, created_by, created_dt, updated_by) "
                        . "VALUES (%d, %s, %d, '%s', %d)",
                        $lesson_id,
                        $notetxt,
                        $user_id,
                        $now,
                        $user_id
                    )
                );
            } else {

                $res = $wpdb->query
                (
                    $wpdb->prepare
                    (
                        "INSERT INTO " . rtr_wpl_tr_lesson_notes() . " (resource_id, note, created_by, created_dt, updated_by) "
                        . "VALUES (%d, %s, %d, '%s', %d)",
                        $resource_id,
                        $notetxt,
                        $user_id,
                        $now,
                        $user_id
                    )
                );
            }
            json(1, 'Notes Added');
        }
    } elseif (esc_attr($_REQUEST['param']) == "delete_note") {

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $wpdb->query(
            $wpdb->prepare
            (
                "DELETE FROM " . rtr_wpl_tr_lesson_notes() . " WHERE id = %d",
                $id
            )
        );

        json(1, 'Note Deleted');
    } elseif (esc_attr($_REQUEST['param']) == "delete_hlink") {

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        $wpdb->query
        (
            $wpdb->prepare
            (
                "DELETE FROM " . rtr_wpl_tr_media() . " WHERE id = %d",
                $id
            )
        );

        json(1, 'Link Deleted');
    } elseif (esc_attr($_REQUEST['param']) == "save_img") {

        if (isset($_FILES) && count($_FILES) > 0) {

            $typematerial = isset($_REQUEST['typematerial']) ? esc_attr($_REQUEST['typematerial']) : "lesson";

            $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
            $urlimg = isset($_REQUEST['urlimg']) ? esc_attr($_REQUEST['urlimg']) : "";

            if ($typematerial == "lesson") {
                $tbl = rtr_wpl_tr_lessons();
                $txt = "Lesson";
                $col = "lesson_id";
            } else {
                $tbl = rtr_wpl_tr_resources();
                $txt = "Resource";
                $col = "resource_id";
            }

            $user_id = $current_user->ID;

            $res = $wpdb->get_var(
                $wpdb->prepare
                (
                    "SELECT count(*) as totaL FROM " . $tbl . " WHERE id = %d ",
                    $id
                )
            );
            if ($res == 0)
                json(0, 'Invalid ' . $txt);

            $now = time();

            $x = 0;
            for ($flag = 0; $flag < count($_FILES); $flag++) {

                if ($_FILES["file-" . $flag]["name"] != "") {

                    if ($_FILES["file-" . $flag]["error"] > 0) {
                        json(0, $_FILES["file-" . $flag]["error"]);
                    } else {

                        $ext = trim(strtolower(pathinfo($_FILES["file-" . $flag]["name"], PATHINFO_EXTENSION)));

                        if ($ext == "php" || $ext == "sql" || $ext == "js") {
                            json(0, 'You are not allowed to add ' . $ext . ' files');
                        }

                        $filenm = str_replace(" ", "_", $_FILES["file-" . $flag]["name"]);
                        $filename = $now . '_' . $typematerial . '_image_' . $id . '_' . $filenm;

                        // Path to upload file

                        $baefilepath = '/assets/docs/' . $filename;

                        $dir = RTR_WPL_COUNT_PLUGIN_DIR . $baefilepath;

                        $result = move_uploaded_file($_FILES["file-" . $flag]["tmp_name"], $dir);

                        $wpdb->query
                        (
                            $wpdb->prepare
                            (
                                "INSERT INTO " . rtr_wpl_tr_media() . " ($col, type, source, path, extra_info, created_by) "
                                . "VALUES (%d, %s, %s, %s, %s, %d)",
                                $id,
                                'image',
                                'upload',
                                $baefilepath,
                                $urlimg,
                                $user_id
                            )
                        );
                        $x++;
                    }
                }
            }
            if ($x > 0) {
                json(1, 'Video & Image Saved');
            }
        }
        json(0, 'Failed To Upload Image');
    } elseif (esc_attr($_REQUEST['param']) == "save_urlimg") {

        $imageid = isset($_REQUEST['imageid']) ? intval($_REQUEST['imageid']) : 0;
        $urlimg = isset($_REQUEST['urlimg']) ? esc_attr($_REQUEST['urlimg']) : "";

        $wpdb->query
        (
            $wpdb->prepare
            (
                "UPDATE " . rtr_wpl_tr_media() . " SET extra_info = %s WHERE id = %d",
                $urlimg,
                $imageid
            )
        );

        json(1, 'Video & Image Url Saved');
    } elseif (esc_attr($_REQUEST['param']) == "add_hlink") {

        $typematerial = isset($_REQUEST['typematerial']) ? esc_attr($_REQUEST['typematerial']) : "lesson";
        $resource_id = isset($_POST['resid']) ? intval($_POST['resid']) : 0;
        $lesson_id = isset($_POST['lessid']) ? intval($_POST['lessid']) : 0;
        $helpnk_id = isset($_POST['helpnkid']) ? intval($_POST['helpnkid']) : 0;

        $user_id = $current_user->ID;
        $now = date('Y-m-d H:i:s');

        if ($typematerial == 'lesson') {
            $lesson = $wpdb->get_var(
                $wpdb->prepare
                (
                    "SELECT count(*) as total FROM " . rtr_wpl_tr_lessons() . " WHERE id = %d ",
                    $lesson_id
                )
            );

            if ($lesson == 0)
                json(0, 'Invalid Lesson');
        } else {
            $resource = $wpdb->get_var(
                $wpdb->prepare
                (
                    "SELECT count(*) as total FROM " . rtr_wpl_tr_resources() . " WHERE id = %d ",
                    $resource_id
                )
            );

            if ($resource == 0)
                json(0, 'Invalid Exercise');
        }

        $has_lnk = $wpdb->get_var(
            $wpdb->prepare
            (
                "SELECT count(*) as total FROM " . rtr_wpl_tr_media() . " WHERE id = %d ",
                $helpnk_id
            )
        );

        $linktitle = isset($_POST['linktitle']) ? esc_attr($_POST['linktitle']) : '';
        $linkurl = isset($_POST['linkurl']) ? esc_attr($_POST['linkurl']) : '';


        if ($has_lnk > 0) {

            $wpdb->query
            (
                $wpdb->prepare
                (
                    "UPDATE " . rtr_wpl_tr_media() . " SET path = %s, extra_info = %s WHERE id = %d",
                    $linkurl,
                    $linktitle,
                    $helpnk_id
                )
            );
            json(1, 'Link Updated');
        } else {

            if ($typematerial == 'lesson') {
                $wpdb->query
                (
                    $wpdb->prepare
                    (
                        "INSERT INTO " . rtr_wpl_tr_media() . " (lesson_id, type, source, path, extra_info, created_by) "
                        . "VALUES (%d, %s, %s, %s, %s, %d)",
                        $lesson_id,
                        'link',
                        'upload',
                        $linkurl,
                        $linktitle,
                        $user_id
                    )
                );
            } else {
                $wpdb->query
                (
                    $wpdb->prepare
                    (
                        "INSERT INTO " . rtr_wpl_tr_media() . " (resource_id, type, source, path, extra_info, created_by) "
                        . "VALUES (%d, %s, %s, %s, %s, %d)",
                        $resource_id,
                        'link',
                        'upload',
                        $linkurl,
                        $linktitle,
                        $user_id
                    )
                );
            }
            json(1, 'Link Added');
        }
    } elseif (esc_attr($_REQUEST['param']) == "get_rows") {

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $type = isset($_POST['type']) ? esc_attr($_POST['type']) : "resources";
        $tbl = rtr_wpl_tr_resources();
        $col = 'lesson_id';
        if ($type == 'modules') {
            $tbl = rtr_wpl_tr_modules();
            $col = 'course_id';
        } elseif ($type == 'lessons') {
            $tbl = rtr_wpl_tr_lessons();
            $col = 'module_id';
        }

        if ($type == 'courses') {
            $rows = $wpdb->get_results(   "SELECT id,title,ord FROM " . rtr_wpl_tr_courses() . " ORDER BY ord ASC limit 3");
        } else {
            $rows = $wpdb->get_results(
                $wpdb->prepare
                (
                    "SELECT id,title,ord FROM " . $tbl . " WHERE $col = %d ORDER BY ord ASC",
                    $id
                )
            );
        }
        if (!empty($rows))
            json(1, 'Rows Found', $rows);
        else
            json(0, 'No Row Found');
    } elseif (esc_attr($_REQUEST['param']) == "get_moverows") {

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $type = "lessons";
        $tbl = rtr_wpl_tr_resources();
        $col = 'lesson_id';
        if ($type == 'modules') {
            $tbl = rtr_wpl_tr_modules();
            $col = 'course_id';
        } elseif ($type == 'lessons') {
            $tbl = rtr_wpl_tr_lessons();
            $col = 'module_id';
        }

        $rows = $wpdb->get_results(
            $wpdb->prepare
            (
                "SELECT id,title,ord,module_id FROM " . $tbl . " WHERE $col = %d ORDER BY ord ASC",
                $id
            )
        );

        if (!empty($rows)) {
            $module_id = $rows[0]->module_id;

            $rows_modules = $wpdb->get_results(
                $wpdb->prepare
                (
                    "SELECT id,title,ord FROM " . rtr_wpl_tr_modules() . " WHERE course_id = (SELECT course_id FROM " . rtr_wpl_tr_modules() . " WHERE id = %d)",
                    $module_id
                )
            );

            $ars = array("rows" => $rows, "rows_modules" => $rows_modules);
            json(1, 'Rows Found', $ars);
        } else
            json(0, 'No Row Found');
    } elseif (esc_attr($_REQUEST['param']) == "save_rows") {

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $type = isset($_POST['type']) ? esc_attr($_POST['type']) : "resources";
        $rows = isset($_POST['armult']) ? esc_attr($_POST['armult']) : "";
        $rows = explode(",", $rows);
        $tbl = rtr_wpl_tr_resources();
        if ($type == 'modules') {
            $tbl = rtr_wpl_tr_modules();
        } elseif ($type == 'lessons') {
            $tbl = rtr_wpl_tr_lessons();
        } elseif ($type == 'courses') {
            $tbl = rtr_wpl_tr_courses();
        }
        $i = 1;

        foreach ($rows as $row) {
            $wpdb->query(
                $wpdb->prepare
                (
                    "UPDATE " . $tbl . " SET ord = %d WHERE id = %d",
                    $i,
                    $row
                )
            );
            $i++;
        }

        if ($i > 1)
            json(1, 'Order Updated');
        else
            json(0, 'Order Not Updated');
    } elseif (esc_attr($_REQUEST['param']) == "move_rows") {

        $id = isset($_POST['modid']) ? intval($_POST['modid']) : 0;
        $rows = isset($_POST['armult']) ? esc_attr($_POST['armult']) : "";
        $rows = explode(",", $rows);
        $i = 1;
        foreach ($rows as $row) {
            $wpdb->query(
                $wpdb->prepare
                (
                    "UPDATE " . rtr_wpl_tr_lessons() . " SET module_id = %d WHERE id = %d",
                    $id,
                    $row
                )
            );
            $i++;
        }

        if ($i > 1)
            json(1, 'Selected Lesson(s) Moved ');
        else
            json(1, 'Lesson(s) Not Moved ');
    } elseif (esc_attr($_REQUEST['param']) == "save_courseimg") {

        $course_id = isset($_REQUEST['course_id']) ? intval($_REQUEST['course_id']) : 0;
        $urlimg = isset($_REQUEST['urlimg']) ? esc_attr($_REQUEST['urlimg']) : "";
        $imgPath = isset($_REQUEST['imgpath']) ? esc_attr($_REQUEST['imgpath']) : "";
        $now = time();
        $total = $wpdb->get_var(
            $wpdb->prepare
            (
                "SELECT count(id) FROM " . rtr_wpl_tr_courses() . " WHERE id = %d",
                $course_id
            )
        );
        if ($total == 0) {
            json(0, 'Invalid Course', $total);
        }

        $wpdb->query
        (
            $wpdb->prepare
            (
                "UPDATE " . rtr_wpl_tr_courses() . " SET imgpath = %s, link = %s WHERE id = %d",
                $imgPath,
                $urlimg,
                $course_id
            )
        );

        json(1, 'Image Uploaded');
    } elseif (esc_attr($_REQUEST['param']) == "update__template") {

        $template_id = isset($_POST['template_id']) ? intval($_POST['template_id']) : 0;

        $template = $wpdb->get_row
        (
            $wpdb->prepare
            (
                "SELECT * FROM " . rtr_wpl_tr_email_templates() . " WHERE id = %d ",
                $template_id
            )
        );

        if (empty($template)) {
            json(0, 'Invalid Template ID');
        }

        $sub = isset($_POST['sub']) ? esc_attr($_POST['sub']) : "";
        $content = isset($_POST['content']) ? html_entity_decode(urldecode(esc_attr($_POST['content']))) : "";
        $content = stripcslashes($content);
        if (trim($sub) == '' || trim($content) == '') {
            json(0, 'Both Subject and Email content are required');
        }

        $wpdb->query
        (
            $wpdb->prepare
            (
                "UPDATE " . rtr_wpl_tr_email_templates() . " SET subject = %s, content = %s WHERE id = %d",
                $sub,
                $content,
                $template_id
            )
        );

        json(1, 'Email template updated successfully.');
    }
}

function emailforsurvey($guid, $id, $email, $name, $form, $mentor)
{

    global $wpdb;

    $slug = RTR_WPL_PAGE_SLUG;
    $btn_url = site_url() . "/$slug?survey=" . $id . "&guid=" . $guid;

    $date = date("Y-m-d H:i:s");
    $site_name = RTR_WPL_TR_SITE_NAME;
    $admin_email = get_option('admin_email');
    $headers = 'From: ' . $admin_email . "\r\n" .
        'Reply-To: ' . $admin_email . "\r\n" .
        'MIME-Version: 1.0' . "\r\n" .
        'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    $template = tt_get_template("survey_send");
    $subj = $template->subject;
    $subj = str_replace("{{mentor_name}}", $mentor->data->display_name, $subj);

    $msg = $template->content;
    $msg = str_replace(array('{{username}}', '{{mentor_name}}', '{{url}}', '{{site_name}}'), array($name, $mentor->data->display_name, $btn_url, $site_name), $msg);

    custom_mail($email, $subj, $msg, RTR_WPL_EMAIL_TYPE, "");
}

/*
  Email Survey for Course - custom
 */

function emailforsurveyCourse($guid, $id, $email, $name, $form, $mentor)
{

    global $wpdb;

    $slug = RTR_WPL_PAGE_SLUG;
    $btn_url = site_url() . '/' . $slug . '?survey=' . $id . '&guid=' . $guid;

    $date = date("Y-m-d H:i:s");
    $site_name = RTR_WPL_TR_SITE_NAME;
    $admin_email = get_option('admin_email');
    $headers = 'From: ' . $admin_email . "\r\n" .
        'Reply-To: ' . $admin_email . "\r\n" .
        'MIME-Version: 1.0' . "\r\n" .
        'Content-type: text/html; charset=utf-8' . '\r\n' .
        'X-Mailer: PHP/' . phpversion();

    $template = tt_get_template("survey_send_course");
    $subj = $template->subject;
    $subj = str_replace("{{course_name}}", $mentor, $subj);
    $msg = $template->content;
    $msg = str_replace(array('{{username}}', '{{course_name}}', '{{url}}', '{{site_name}}'), array(strtoupper($name), $mentor, $btn_url, $site_name), $msg);
    custom_mail($email, $subj, $msg, RTR_WPL_EMAIL_TYPE, "");
}

/* ../ custom code ends */

/* custom code to send email notification to coach */

function emailtocoachnotify($to_id, $from_id, $status, $course, $exercise, $links, $files, $type, $url)
{

    try {
        global $wpdb;
        /* coach */
        $coach_details = get_userdata($to_id);
        $coach_name = $coach_details->display_name;
        $coach_email = $coach_details->user_email;
        /* student */
        $student_details = get_userdata($from_id);

        $student_name = strtoupper($student_details->display_name); /* student_name */
        $student_email = $student_details->user_email;  /* student email */

        $date = date("Y-m-d H:i:s");
        $site_name = RTR_WPL_TR_SITE_NAME;
        $headers = 'From: ' . $student_email . "\r\n" .
            'Reply-To: ' . $student_email . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        $template = tt_get_template("project_submission");

        $subj = $site_name . " " . $template->subject;
        $subj = str_replace("{{mentor_name}}", $coach_name, $subj);

        $msg = $template->content;

        /* sending mail in case of project submission */

        if ($coach_email == get_option('admin_email') && $type == "Project") {

            if (empty($files)) {
                $msg = str_replace(array('{{mentor_name}}', '{{student_name}}', '{{student_email}}', '{{status}}', '{{exercise_name}}', '{{course_name}}', '{{work_files}}', '{{site_name}}'), array($coach_name, $student_name, $student_email, $status . " work , below you can find work details" . "<br/>", $exercise, $course, "<br/>Submitted Work <br/> Links are: " . $links . "<br/><br/>For more information , please go to this link<br/>" . $url . "<br/><br/>*Note : You have recieved this mail because user has no mentor assigned yet.", $site_name), $msg);
            } else {
                $msg = str_replace(array('{{mentor_name}}', '{{student_name}}', '{{student_email}}', '{{status}}', '{{exercise_name}}', '{{course_name}}', '{{work_files}}', '{{site_name}}'), array($coach_name, $student_name, $student_email, $status . " work , below you can find work details" . "<br/>", $exercise, $course, "<br/>Submitted Work <br/>Links are : " . $links . "<br/>Files are :" . $files . "<br/><br/>For more information , please go to this link<br/>" . $url . "<br/><br/>*Note : You have recieved this mail because user has no mentor assigned yet.", $site_name), $msg);
            }
        } elseif ($type == "Project") {

            if (empty($files)) {
                $msg = str_replace(array('{{mentor_name}}', '{{student_name}}', '{{student_email}}', '{{status}}', '{{exercise_name}}', '{{course_name}}', '{{work_files}}', '{{site_name}}'), array($coach_name, $student_name, $student_email, $status . " work , below you can find work details.<br/>", $exercise . "<br/>", $course, "<br/>Submitted Work <br/> Links are :" . $links . "<br/><br/>For more information , please go to this link<br/>" . $url, $site_name), $msg);
            } else {
                $msg = str_replace(array('{{mentor_name}}', '{{student_name}}', '{{student_email}}', '{{status}}', '{{exercise_name}}', '{{course_name}}', '{{work_files}}', '{{site_name}}'), array($coach_name, $student_name, $student_email, $status . " work , below you can find work details .<br/>", $exercise, $course, "<br/>Submitted Work  <br/>Links are :" . $links . "<br/>Files are : " . $files . "<br/><br/>For more information , please go to this link<br/>" . $url, $site_name), $msg);
            }
        } elseif ($coach_email == get_option('admin_email') && $type == "Marked") {

            $msg = str_replace(array('{{mentor_name}}', '{{student_name}}', '{{student_email}}', '{{status}}', '{{exercise_name}}', '{{course_name}}', '{{work_files}}', '{{site_name}}'), array($coach_name, $student_name, $student_email, $status . " work , below you can find work details .<br/>", $exercise, $course, "<br/><br/>For more information , please go to this link<br/>" . $url, $site_name), $msg);
        } elseif ($type == "Marked") {

            $msg = str_replace(array('{{mentor_name}}', '{{student_name}}', '{{student_email}}', '{{status}}', '{{exercise_name}}', '{{course_name}}', '{{work_files}}', '{{site_name}}'), array($coach_name, $student_name, $student_email, $status . " work , below you can find work details .<br/>", $exercise, $course, "<br/><br/>For more information , please go to this link<br/>" . $url, $site_name), $msg);
        }

        custom_mail($coach_email, $subj, $msg, RTR_WPL_EMAIL_TYPE, "");
    } catch (Exception $ex) {
        print_r($ex);
    }
}

/* ../coach email ends */

function updatlesson($lesson_id)
{

    global $wpdb;
    $total_lesshrs = $wpdb->get_var
    (
        $wpdb->prepare
        (
            "SELECT sum(total_hrs) as totalhrs FROM " . rtr_wpl_tr_resources() . " WHERE lesson_id = %d",
            $lesson_id
        )
    );

    $total_lessresource = $wpdb->get_var
    (
        $wpdb->prepare
        (
            "SELECT count(id) as totalresource FROM " . rtr_wpl_tr_resources() . " WHERE lesson_id = %d",
            $lesson_id
        )
    );
    $wpdb->query
    (
        $wpdb->prepare
        (
            "UPDATE " . rtr_wpl_tr_lessons() . " SET total_hrs = %s, total_resources = %d WHERE id = %d",
            $total_lesshrs,
            $total_lessresource,
            $lesson_id
        )
    );
}

function updatehours_and_resources($module_id, $course_id, $lesson_id)
{
    global $wpdb;

    if ($lesson_id > 0) {
        updatlesson($lesson_id);
    } elseif ($lesson_id == 0) {

        if ($module_id > 0) {
            $lessons = $wpdb->get_results
            (
                $wpdb->prepare
                (
                    "SELECT id FROM " . rtr_wpl_tr_lessons() . " WHERE module_id = %d",
                    $module_id
                )
            );

            foreach ($lessons as $lesson) {
                updatlesson($lesson->id);
            }
        }
    }

    if ($module_id > 0) {

        $total_modulehrs = $wpdb->get_var
        (
            $wpdb->prepare
            (
                "SELECT sum(total_hrs) as totalhrs FROM " . rtr_wpl_tr_lessons() . " WHERE module_id = %d",
                $module_id
            )
        );

        $hrm = updatehrsfrominner('module_id', $module_id);
        $total_modulehrs = $total_modulehrs + $hrm;

        $total_moduleresource = $wpdb->get_var
        (
            $wpdb->prepare
            (
                "SELECT sum(total_resources) as totalresource FROM " . rtr_wpl_tr_lessons() . " WHERE module_id = %d",
                $module_id
            )
        );

        $wpdb->query
        (
            $wpdb->prepare
            (
                "UPDATE " . rtr_wpl_tr_modules() . " SET total_hrs = %s, total_resources = %d WHERE id = %d",
                $total_modulehrs,
                $total_moduleresource,
                $module_id
            )
        );
    }

    if ($course_id > 0) {


        $total_coursehrs = $wpdb->get_var
        (
            $wpdb->prepare
            (
                "SELECT sum(total_hrs) as totalhrs FROM " . rtr_wpl_tr_modules() . " WHERE course_id = %d",
                $course_id
            )
        );

        $hr = updatehrsfrominner('course_id', $course_id);
        $total_coursehrs = $total_coursehrs + $hr;

        $total_courseresource = $wpdb->get_var
        (
            $wpdb->prepare
            (
                "SELECT sum(total_resources) as totalresource FROM " . rtr_wpl_tr_modules() . " WHERE course_id = %d",
                $course_id
            )
        );

        $wpdb->query
        (
            $wpdb->prepare
            (
                "UPDATE " . rtr_wpl_tr_courses() . " SET total_hrs = %s, total_resources = %d WHERE id = %d",
                $total_coursehrs,
                $total_courseresource,
                $course_id
            )
        );
    }
}

function json($sts, $msg, $arr = array())
{
    $ar = array('sts' => $sts, 'msg' => $msg, 'arr' => $arr);
    print_r(json_encode($ar));
    die;
}

function updatehrsfrominner($column, $value)
{

    global $wpdb;

    if ($column == 'course_id') {
        $projhrsm = $wpdb->get_row(
            $wpdb->prepare
            (
                "SELECT total_hrs,status FROM " . rtr_wpl_tr_project_exercise() . " WHERE course_id = %d AND module_id = 0",
                $value
            )
        );
    } else {
        $projhrsm = $wpdb->get_row(
            $wpdb->prepare
            (
                "SELECT total_hrs,status FROM " . rtr_wpl_tr_project_exercise() . " WHERE module_id = %d",
                $value
            )
        );
    }

    $ret = 0;
    if (!empty($projhrsm)) {
        if ($projhrsm->status == 1) {
            $ret = $projhrsm->total_hrs;
        } else {
            $ret = -($projhrsm->total_hrs);
        }
    }

    return $ret;
}

function update_hours($module_id, $course_id, $removehrs, $hrsadd)
{
    global $wpdb;
    if ($module_id > 0) {


        $total_modulehrs = $wpdb->get_var
        (
            $wpdb->prepare
            (
                "SELECT total_hrs FROM " . rtr_wpl_tr_modules() . " WHERE id = %d",
                $module_id
            )
        );
        $total_modulehrs = ($total_modulehrs + $hrsadd) - $removehrs;

        $wpdb->query
        (
            $wpdb->prepare
            (
                "UPDATE " . rtr_wpl_tr_modules() . " SET total_hrs = %s WHERE id = %d",
                $total_modulehrs,
                $module_id
            )
        );



        $total_coursehrs = $wpdb->get_var
        (
            $wpdb->prepare
            (
                "SELECT total_hrs FROM " . rtr_wpl_tr_courses() . " WHERE id = %d",
                $course_id
            )
        );

        $total_coursehrs = ($total_coursehrs + $hrsadd) - $removehrs;
        $wpdb->query
        (
            $wpdb->prepare
            (
                "UPDATE " . rtr_wpl_tr_courses() . " SET total_hrs = %s WHERE id = %d",
                $total_coursehrs,
                $course_id
            )
        );
    } else {

        $total_coursehrs = $wpdb->get_var
        (
            $wpdb->prepare
            (
                "SELECT total_hrs FROM " . rtr_wpl_tr_courses() . " WHERE id = %d",
                $course_id
            )
        );


        $total_coursehrs = ($total_coursehrs + $hrsadd) - $removehrs;

        $wpdb->query
        (
            $wpdb->prepare
            (
                "UPDATE " . rtr_wpl_tr_courses() . " SET total_hrs = %s WHERE id = %d",
                $total_coursehrs,
                $course_id
            )
        );
    }
}

function deletemedia($lesson_id)
{

    global $wpdb;
    $lessonmedia = $wpdb->get_results
    (
        $wpdb->prepare
        (
            "SELECT path FROM " . rtr_wpl_tr_media() . " WHERE type IN('document','image') AND (lesson_id = %d OR resource_id IN(SELECT id FROM " . rtr_wpl_tr_resources() . " WHERE lesson_id = %d))",
            $lesson_id,
            $lesson_id
        )
    );

    foreach ($lessonmedia as $media) {
        $path = $media->path;
        $path = RTR_WPL_COUNT_PLUGIN_DIR . $path;
        @unlink($path);
    }

    $wpdb->query(
        $wpdb->prepare
        (
            "DELETE FROM " . rtr_wpl_tr_media() . " WHERE lesson_id = %d OR resource_id IN(SELECT id FROM " . rtr_wpl_tr_resources() . " WHERE lesson_id = %d)",
            $lesson_id,
            $lesson_id
        )
    );
}

function deletemediacourse($id)
{
    global $wpdb;
    /* deleting media files of current course */
    $lessons = $wpdb->get_results
    (
        $wpdb->prepare
        (
            "SELECT id FROM " . rtr_wpl_tr_lessons() . " WHERE module_id IN(SELECT id FROM " . rtr_wpl_tr_modules() . " WHERE course_id = %d) ",
            $id
        )
    );
    foreach ($lessons as $lesson) {
        /* delete lesson data */
        deletemedia($lesson->id);
    }
}

function deletemediamodule($id)
{
    global $wpdb;
    $lessons = $wpdb->get_results
    (
        $wpdb->prepare
        (
            "SELECT id FROM " . rtr_wpl_tr_lessons() . " WHERE module_id = %d",
            $id
        )
    );
    foreach ($lessons as $lesson) {
        ($lesson->id);
    }
}

function tt_get_template($template_name)
{
    global $wpdb;
    $template = $wpdb->get_row
    (
        $wpdb->prepare
        (
            "SELECT subject, content FROM " . rtr_wpl_tr_email_templates() . " WHERE template = %s",
            $template_name
        )
    );
    return $template;
}

function custom_mail_header($fromcntmail = '')
{
    $additional_parameters = '-f ' . get_option('admin_email');
    $fromcntmail = get_bloginfo('admin_email');
    return "Reply-To: $fromcntmail\r\n"
        . "Return-Path: " . get_bloginfo('name') . " <" . get_bloginfo('admin_email') . ">\r\n"
        . "From: " . get_bloginfo('name') . " <" . get_bloginfo('admin_email') . ">\r\n"
        . "Return-Receipt-To: " . get_bloginfo('admin_email') . "\r\n"
        . "MIME-Version: 1.0\r\n"
        . "Content-type: text/html; charset=utf-8 " . "\r\n"
        . "X-Priority: 3\r\n"
        . "X-Mailer: PHP" . phpversion() . "\r\n";
}

function custom_mail($user_email, $setup_sub, $body, $email_type, $reason)
{

    $email_template_body = email_template_body($body, $user_email, $email_type);
    wp_mail($user_email, $setup_sub, $email_template_body, custom_mail_header(), '');
}

function email_template_body($body, $user_email, $email_type)
{
    $Tmplt_General = file_get_contents(RTR_WPL_COUNT_PLUGIN_URL . "/email-templates/templates.php");
    $body = str_replace('~~EMAIL_BODY~~', html_entity_decode($body), $Tmplt_General);
    return $body;
}

function getacids()
{

    $AccountIds = array();

    $accountsItems = $GLOBALS["Analytics"]->management_accounts->listManagementAccounts()->getItems();

    if (count($accountsItems) > 0)
        foreach ($accountsItems as $curAccountItem)
            $AccountIds[] = array($curAccountItem->getId(), $curAccountItem->name);

    return $AccountIds;
}

function rtr_wpl_training_admin_notify($to, $from, $course_details)
{
    
    

    try {
        /* coach */
        $coach_details = get_userdata($to);
        $coach_name = $coach_details->display_name;
        $coach_email = $coach_details->user_email;
        /* student */
        $student_details = get_userdata($from);

        $student_name = strtoupper($student_details->display_name); /* student_name */
        $student_email = $student_details->user_email;  /* student email */

        $date = date("Y-m-d H:i:s");
        $site_name = RTR_WPL_TR_SITE_NAME;
        $headers = 'From: ' . $student_email . "\r\n" .
            'Reply-To: ' . $student_email . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        $template = tt_get_template("project_submission");

        $subj = $site_name . " " . $template->subject;
        $subj = str_replace("{{mentor_name}}", $coach_name, $subj);
        $msg = $template->content;
        if ($course_details['mail_for'] == "survey") {

        } elseif ($course_details['mail_for'] == "project") {
            // when resource will submitted by user
            $links = implode("<br/>", $course_details['links']);
            $files = implode("<br/>", $course_details['files']);
            $exercise_title = $course_details['resource_title'];
            $course_title = $course_details['course_title'];
            $msg = str_replace(array('{{mentor_name}}', '{{student_name}}', '{{student_email}}', '{{status}}', '{{exercise_name}}', '{{course_name}}', '{{work_files}}', '{{site_name}}'), array($coach_name, $student_name, $student_email, $course_details['status'] . " work , below you can find work details.<br/>", $exercise_title . "<br/>", $course_title, "<br/>Submitted Work <br/> Links are :" . $links . "<br/>Files are: " . $files . "<br/><br/>For more information , please go to this link<br/>" . $course_details['url'], "Training"), $msg);
        } elseif ($course_details['mail_for'] == "mark") {
            // when resource will mark by user
            $msg = str_replace(array('{{mentor_name}}', '{{student_name}}', '{{student_email}}', '{{status}}', '{{exercise_name}}', '{{course_name}}', '{{work_files}}', '{{site_name}}'), array($coach_name, $student_name, $student_email, $course_details['status'] . " work , below you can find work details.", $course_details['resource_title'], $course_details['course_title'], "<br/><br/>For more information , please go to this link<br/>" . $course_details['url'], "Training"), $msg);
        }
        //wp_mail("sanjay@rudrainnovatives.com", "Test data", json_encode(array("template" => $msg, "course" => $course_details)));
        custom_mail($coach_email, $subj, $msg, RTR_WPL_EMAIL_TYPE, "");
    } catch (Exception $ex) {
        json(0, "exception", $ex);
    }
}
