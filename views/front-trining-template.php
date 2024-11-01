<?php
/**
 * This File for frontend course display template.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
/*
  Template Name: Training Tool
 */

get_header();

if (!defined("ABSPATH"))
    exit;
?>

<?php include_once RTR_WPL_COUNT_PLUGIN_DIR . '/library/training-init.php'; ?>
<div class="templatemain maintrainingclass rtr-col-12">
    <?php
    echo do_shortcode("[course_detail]");
    ?>
</div>
<?php get_footer(); ?>
