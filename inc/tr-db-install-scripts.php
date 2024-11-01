<?php

/**
 * This File which installs table
 * 
 * @author	Rudra Innnovative Software 
 * @package	training/inc 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

function rtr_wpl_tr_courses() {
    global $wpdb;
    return $wpdb->prefix . 'rtr_courses';
}

function rtr_wpl_tr_modules() {
    global $wpdb;
    return $wpdb->prefix . 'rtr_modules';
}

function rtr_wpl_tr_project_exercise() {
    global $wpdb;
    return $wpdb->prefix . 'rtr_project_exercise';
}

function rtr_wpl_tr_projects() {
    global $wpdb;
    return $wpdb->prefix . 'rtr_projects';
}

function rtr_wpl_tr_lessons() {
    global $wpdb;
    return $wpdb->prefix . 'rtr_lessons';
}

function rtr_wpl_tr_resources() {
    global $wpdb;
    return $wpdb->prefix . 'rtr_resource_list';
}

function rtr_wpl_tr_media() {
    global $wpdb;
    return $wpdb->prefix . 'rtr_media';
}

function rtr_wpl_tr_enrollment() {
    global $wpdb;
    return $wpdb->prefix . 'rtr_enrollment';
}

function rtr_wpl_tr_resource_status() {
    global $wpdb;
    return $wpdb->prefix . 'rtr_resource_status';
}

function rtr_wpl_tr_setting() {
    global $wpdb;
    return $wpdb->prefix . 'rtr_setting';
}

function rtr_wpl_tr_lesson_notes() {
    global $wpdb;
    return $wpdb->prefix . 'rtr_lesson_notes';
}

function rtr_wpl_tr_email_templates() {
    global $wpdb;
    return $wpdb->prefix . 'rtr_email_templates';
}

function rtr_wpl_tr_authors() {
    global $wpdb;
    return $wpdb->prefix . 'rtr_authors';
}

function rtr_wpl_tr_categories(){
    global $wpdb;
    return $wpdb->prefix . 'rtr_categories';
}

function rtr_wpl_install_table() {
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    // Training Tool tables

    $table_author = rtr_wpl_tr_authors();      
    if ($wpdb->get_var('SHOW TABLES LIKE "' . $table_author . '"') !== $table_author) {
        $sql = 'CREATE TABLE ' . $table_author . '(
		   `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(150) NOT NULL,
                    `email` varchar(150) NOT NULL,
                    `about` TEXT NULL,
                    `phone` varchar(15) NOT NULL,
                    `fb_url` text NOT NULL,
                    `post` text NOT NULL,
                    `website` varchar(200) NOT NULL,
                    `profile_img` TEXT NOT NULL,
                    `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8';
        dbDelta($sql);
    }
    
    $table_categories = rtr_wpl_tr_categories();
    if ($wpdb->get_var('SHOW TABLES LIKE "' . $table_categories . '"') !== $table_categories) {
        $sql = 'CREATE TABLE ' . $table_categories . '(
		    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(250) DEFAULT NULL,
                    `subcategories` text,
                    `status` int(11) DEFAULT "1",
                    `created_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8';
        dbDelta($sql);
    }

    $table_courses = rtr_wpl_tr_courses();
    if ($wpdb->get_var('SHOW TABLES LIKE "' . $table_courses . '"') !== $table_courses) {
        $sql = 'CREATE TABLE ' .  $table_courses . '(
		 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		 `ord` int(11) NOT NULL,
		 `title` text NOT NULL,
                 `assigned_author_id` INT NULL,
                 `category_id` INT NULL,
                 `subcategory` VARCHAR( 150 ) NULL, 
		 `description` text NOT NULL,
		 `total_hrs` varchar(20) DEFAULT NULL,
		 `total_resources` varchar(20) DEFAULT NULL,
		 `imgpath` varchar(500) DEFAULT NULL,
		 `course_type` varchar(15) DEFAULT NULL,
		 `course_amount` varchar(10) NOT NULL DEFAULT "0",
		 `link` varchar(500) DEFAULT NULL,
		 `mentor_ids` text,
		 `enable_permission` int(1) DEFAULT "0",
		 `user_ids` text,
		 `created_by` int(11) DEFAULT NULL,
		 `created_dt` datetime DEFAULT NULL,
		 `updated_by` int(11) DEFAULT NULL,
		 `updated_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		 PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8';
        dbDelta($sql);
    }

    $table_modules = rtr_wpl_tr_modules();
    if ($wpdb->get_var('SHOW TABLES LIKE "' . $table_modules . '"') !== $table_modules) {
        $sql = 'CREATE TABLE ' . $table_modules . '(
		 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		 `ord` int(11) NOT NULL,
		 `course_id` int(11) NOT NULL,
		 `title` text NOT NULL,
		 `description` text NOT NULL,
		 `external_link` text NOT NULL,
		 `total_hrs` varchar(20) DEFAULT NULL,
		 `total_resources` varchar(20) DEFAULT NULL,
		 `created_by` int(11) DEFAULT NULL,
		 `created_dt` datetime DEFAULT NULL,
		 `updated_by` int(11) DEFAULT NULL,
		 `updated_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		 PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8';
        dbDelta($sql);
    }

    $table_project_exercise = rtr_wpl_tr_project_exercise();
    if ($wpdb->get_var('SHOW TABLES LIKE "' . $table_project_exercise . '"') !== $table_project_exercise) {
        $sql = 'CREATE TABLE ' . $table_project_exercise . '(
		 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		 `type` enum("course","module") NOT NULL,
		 `status` int(11) DEFAULT "1",
		 `module_id` int(11) NOT NULL,
		 `course_id` int(11) NOT NULL,
		 `title` text NOT NULL,
		 `description` text NOT NULL,
		 `total_hrs` varchar(20) DEFAULT NULL,
		 `created_by` int(11) DEFAULT NULL,
		 `created_dt` datetime DEFAULT NULL,
		 `updated_by` int(11) DEFAULT NULL,
		 `updated_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		 PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8';
        dbDelta($sql);
    }

    $table_projects = rtr_wpl_tr_projects();
    if ($wpdb->get_var('SHOW TABLES LIKE "' . $table_projects . '"') !== $table_projects ) {
        $sql = 'CREATE TABLE ' . $table_projects . '(
		 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		 `user_id` int(11) NOT NULL,
		 `resource_id` int(11) NOT NULL,
		 `exercise_id` int(11) NOT NULL,
		 `links` text,
		 `doc_files` text,
		 `docs` text,
		 `created_by` int(11) DEFAULT NULL,
		 `created_dt` datetime DEFAULT NULL,
		 `updated_by` int(11) DEFAULT NULL,
		 `updated_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		 PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8';
        dbDelta($sql);
    }

        $table_lessons = rtr_wpl_tr_lessons();
    if ($wpdb->get_var('SHOW TABLES LIKE "' . $table_lessons . '"') !== $table_lessons) {
        $sql = 'CREATE TABLE ' . $table_lessons . '(
		 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		 `ord` int(11) NOT NULL,
		 `module_id` int(11) NOT NULL,
		 `title` text NOT NULL,
		 `description` text NOT NULL,
		 `external_link` text NOT NULL,
		 `total_hrs` varchar(20) DEFAULT NULL,
		 `total_resources` varchar(20) DEFAULT NULL,
		 `created_by` int(11) DEFAULT NULL,
		 `created_dt` datetime DEFAULT NULL,
		 `updated_by` int(11) DEFAULT NULL,
		 `updated_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		 PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8';
        dbDelta($sql);
    }

    $table_lesson_notes = rtr_wpl_tr_lesson_notes();
    if ($wpdb->get_var('SHOW TABLES LIKE "' . $table_lesson_notes . '"') !== $table_lesson_notes) {
        $sql = 'CREATE TABLE ' . $table_lesson_notes . '(
		 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		 `lesson_id` int(11) DEFAULT NULL,
		 `resource_id` int(11) DEFAULT NULL,
		 `note` text NOT NULL,
		 `created_by` int(11) DEFAULT NULL,
		 `created_dt` datetime DEFAULT NULL,
		 `updated_by` int(11) DEFAULT NULL,
		 `updated_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		 PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8';
        dbDelta($sql);
    }

    $table_resources = rtr_wpl_tr_resources();

    if ($wpdb->get_var('SHOW TABLES LIKE "' . $table_resources . '"') !== $table_resources) {
        $sql = 'CREATE TABLE ' . $table_resources . '(
		 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		 `ord` int(11) NOT NULL,
		 `button_type` enum("mark","submit") DEFAULT "mark",
		 `course_id` int(11) NOT NULL,
		 `module_id` int(11) NOT NULL,
		 `lesson_id` int(11) NOT NULL,
		 `title` text NOT NULL,
		 `description` text NOT NULL,
		 `external_link` text NOT NULL,
		 `total_hrs` varchar(20) DEFAULT NULL,
		 `created_by` int(11) DEFAULT NULL,
		 `created_dt` datetime DEFAULT NULL,
		 `updated_by` int(11) DEFAULT NULL,
		 `updated_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		 PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8';
        dbDelta($sql);
    }

    $table_media = rtr_wpl_tr_media();
    if ($wpdb->get_var('SHOW TABLES LIKE "' . $table_media . '"') !== $table_media) {
        $sql = 'CREATE TABLE ' . $table_media . '(
		 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		 `lesson_id` int(11) DEFAULT NULL,
		 `resource_id` int(11) DEFAULT NULL,
		 `type` enum("document","video","image","link") DEFAULT "document",
		 `source` enum("embed","iframe","upload") DEFAULT "embed",
		 `path` text,
		 `extra_info` text,
		 `created_by` int(11) DEFAULT NULL,
		 `created_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		 PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8';
        dbDelta($sql);
    }

    $table_enrollment = rtr_wpl_tr_enrollment();
    if ($wpdb->get_var('SHOW TABLES LIKE "' . $table_enrollment. '"') !== $table_enrollment) {
        $sql = 'CREATE TABLE ' . $table_enrollment . '(
		 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		 `course_id` int(11) NOT NULL,
		 `user_id` int(11) DEFAULT NULL,
		 `status` enum("inprogress","completed","incompleted","closed") DEFAULT "inprogress",
		 `created_dt` datetime DEFAULT NULL,
		 `updated_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		 PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8';
        dbDelta($sql);
    }

    $table_resource_status = rtr_wpl_tr_resource_status();
    if ($wpdb->get_var('SHOW TABLES LIKE "' . $table_resource_status . '"') !== $table_resource_status) {
        $sql = 'CREATE TABLE ' . $table_resource_status . '(
		 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		 `enrollment_id` int(11) NOT NULL,
		 `course_id` int(11) NOT NULL,
		 `resource_id` int(11) NOT NULL,
		 `user_id` int(11) DEFAULT NULL,
		 `status` int(1) DEFAULT "0",
		 `created_dt` datetime DEFAULT NULL,
		 `updated_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		 PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=79 DEFAULT CHARSET=utf8';
        dbDelta($sql);
    }

    $table_setting = rtr_wpl_tr_setting();
    if ($wpdb->get_var('SHOW TABLES LIKE "' . $table_setting . '"') !== $table_setting) {
        $sql = 'CREATE TABLE ' . $table_setting . '(
		 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		 `keyname` varchar(250) NOT NULL,
		 `keyvalue` varchar(250) NOT NULL,
		 `type` varchar(100) NOT NULL,
		 `is_show` int(1) DEFAULT "1",
		 `updated_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		 PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8';
        dbDelta($sql);

        $author_uri = site_url();

       /* $wpdb->query(
                $wpdb->prepare
                        (
                        "INSERT INTO " . rtr_wpl_tr_setting() . "(keyname,keyvalue,type)VALUES(%s, %s, %s)", "Visit Author", $author_uri, "link"
                )
        );*/

        $wpdb->query(
                $wpdb->prepare
                        (
                        "INSERT INTO " . $table_setting . "(keyname,keyvalue,type)VALUES(%s, %s, %s)", "Google+ Community", "https://www.google.co.in", "link"
                )
        );

        $wpdb->query(
                $wpdb->prepare
                        (
                        "INSERT INTO " . $table_setting . "(keyname,keyvalue,type)VALUES(%s, %s, %s)", "Help", "", "link"
                )
        );

        $wpdb->query(
                $wpdb->prepare
                        (
                        "INSERT INTO " . $table_setting . "(keyname,keyvalue,type)VALUES(%s, %s, %s)", "Notification Timeout (Seconds)", "3", "setting"
                )
        );


    }else{

$results = $wpdb->get_results( "SELECT * FROM `". $table_setting ."` WHERE `keyname` = 'Notification Timeout (Seconds)'", OBJECT );

if($results){

    $table_name   = $table_setting;
    $data		  = array(
        'keyvalue' => 5,
    );
    $format       = array('%d'); 
    $where 		  = array(  'keyname' => 'Notification Timeout (Seconds)',);
    $where_format =array( '%s' );
    $wpdb->update( $table_name,$data, $where , $format,$where_format);

}else{
        $wpdb->query(
            $wpdb->prepare
                    (
                    "INSERT INTO " . $table_setting . "(keyname,keyvalue,type)VALUES(%s, %s, %s)", "Notification Timeout (Seconds)", "4", "setting"
            )
    );
}
            

    }

    $table_email_templates = rtr_wpl_tr_email_templates();
    if ($wpdb->get_var('SHOW TABLES LIKE "' .  $table_email_templates . '"') !==  $table_email_templates) {
        $sql = 'CREATE TABLE ' .  $table_email_templates . '(
		 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		 `template` varchar(100) NOT NULL,
		 `subject` varchar(500) NOT NULL,
		 `content` text NOT NULL,
		 `created_dt` datetime DEFAULT NULL,
		 `updated_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		 PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8';
        dbDelta($sql);

        $now = date("Y-m-d H:i:s");

        $wpdb->query(
                $wpdb->prepare
                        (
                        "INSERT INTO " . $table_email_templates  . "(template,subject,content,created_dt)"
                        . "VALUES(%s, %s, %s, '%s')", "course_permission_granted", "Permisssion granted for {{course_title}} course", '<div>Hi {{username}},</div>
                        <br class="" /><br class="" />
                        <div>The team at {{blogname}} has granted you access to {{course_title}} course.</div><br class="" />
                        <div><a href="{{url}}">Click here to view your course</a></div>
                        <br class="" />
                        <br class="" />Thanks<br class="" />
                        {{site_name}}</div>', $now
                )
        );

        $wpdb->query(
                $wpdb->prepare
                        (
                        "INSERT INTO " . $table_email_templates  . "(template,subject,content,created_dt)"
                        . "VALUES(%s, %s, %s, '%s')", "survey_send", "Survey sent regarding your mentor {{mentor_name}}", '<div>Hi {{username}},</div>
                        <br class="" /><br class="" />
                        <div>This is the survey regarding your mentor {{mentor_name}}.</div><br class="" />
                        <div><a href="{{url}}">Please Click Here Fill Survey</a></div>
                        <div>
                        <br class="" />
                        <div><br class="" />Thanks<br class="" />
                        {{site_name}}</div>
                        </div>', $now
                )
        );


        $wpdb->query(
                $wpdb->prepare
                        (
                        "INSERT INTO " . $table_email_templates  . "(template,subject,content,created_dt)"
                        . "VALUES(%s, %s, %s, '%s')", "survey_result_user", "Thanks for Survey", '<div>Hi {{username}},</div>
                        <br class="" />
                        <br class="" />
                        <div>

                        Thanks for submitting survey {{survey_title}} regarding your Course {{course_name}}.<br class="" />
                        <a href="{{url}}">Click Here To Check Your Survey</a>

                        <br class="" /><br class="" />Thanks<br/>
                        {{site_name}}

                        </div>', $now
                )
        );

        /* Submitting Servey regarding Course - custom */
        $wpdb->query(
                $wpdb->prepare
                        (
                        "INSERT INTO " . $table_email_templates  . "(template,subject,content,created_dt)"
                        . "VALUES(%s, %s, %s, '%s')", "survey_send_course", "Survey sent regarding your Course {{course_name}}", '<div>Hi {{username}},</div>
                        <br class="" />
                        <div>This is the survey regarding your course {{course_name}}.</div><br class="" />
                        <div><a href="{{url}}">Please Click Here Fill Survey</a></div>
                        <br class="" />
                        <div>
                        <br class="" />Thanks<br class="" />
                        {{site_name}}</div>
                        </div>', $now
                )
        );
        /* custom template for sending mail to notify mentor , project submission */
        $wpdb->query(
                $wpdb->prepare
                        (
                        "INSERT INTO " . $table_email_templates  . "(template,subject,content,created_dt)"
                        . "VALUES(%s, %s, %s, '%s')", "project_submission", "Work Status Notification", '<div>Hi {{mentor_name}},</div>
						<div></div>
						<div></div>
						<div><br/>

						User {{student_name}} ( {{student_email}} ) has {{status}} 
						Exercise : {{exercise_name}}
						Course : {{course_name}}
						{{work_files}}

						</div>
						<div>
						<div><br/><br/>Thanks<br/>
						{{site_name}}</div>
						</div>', $now
                )
        );
    }
}
