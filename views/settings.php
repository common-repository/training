<?php
/**
 * This File for plugin settings.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

include_once 'common.php';
global $wpdb;

$settings = $wpdb->get_results("SELECT * FROM " . rtr_wpl_tr_setting() . " WHERE keyname NOT IN('valid_licence')");

?>

<div class="rtr-mt-2">
    <ul class="rtr-nav rtr-nav-tabs tabcustom rtr-mb-0">
        <li class="active"><a href="javascript:;">Settings</a></li>

    </ul>
    <div class="rtr-panel tab-content cust-setting-sec rtr-rounded-0">
        <!-- Web Form --->
        <div class="rtr-panel-body webType">
            <form action="#" method="post" id="settingform" name="settingform" class="form-horizontal">
                <?php
                foreach ($settings as $setting) {
                  
                    $readonly = '';
                    $show_show_field = '';
                    if ($setting->keyname == 'Notification Timeout (Seconds)') {
                        $readonly = "readonly";
                        $show_show_field = 'style="z-index:-2;"';
                    }
                    ?>
                <input type="hidden" name="ids[]" value="<?php echo $setting->id; ?>" />
                <div class="rtr-form-group">
                    <div class="rtr-col-lg-1 cust-setting-chkbx-fld" <?php echo $show_show_field; ?>>
                        <label> <input class="form-control lblchksettings" <?php echo $setting->is_show == 1 ? 'checked="checked"' : ''; ?> type="checkbox" id="show_<?php echo $setting->id; ?>" name="show_<?php echo $setting->id; ?>" /> Show
                        </label>
                    </div>
                    <div class="rtr-col-lg-3 rtr-pe-2">
                        <input <?php echo $readonly; ?> class="rtr-form-control" placeholder="Enter Value" type="text"
                        id="key_<?php echo $setting->id; ?>" name="key_<?php echo $setting->id; ?>" value="<?php echo $setting->keyname; ?>" />
                    </div>
                    <div class="rtr-col-lg-8">
                        <input class="rtr-form-control" placeholder="Enter Value" type="text"
                            id="val_<?php echo $setting->id; ?>" name="val_<?php echo $setting->id; ?>"
                            value="<?php echo $setting->keyvalue; ?>" />
                    </div>
                </div>

                <?php
                }
                ?>
                <div class="form-group rowdiv">
                    <div class="rtr-col-lg-12 rtr-d-flex rtr-justify-content-end">
                        <a href="javascript:;" class="settingbtn rtr-btn rtr-btn-success">Save Settings</a>
                    </div>
                </div>
            </form>
        </div>
        <!---- Web Form Ends --->
    </div>
</div>