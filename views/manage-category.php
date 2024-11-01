<?php
/**
 * This File for creating new lessons.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

include_once 'common.php';

wp_enqueue_media();
?>

<div class="contaninerinner">
    <div class="rtr-panel rtr-panel-primary">
        <div class="rtr-pull-right">
            <a href="javascript:void(0)" class="rtr-btn rtr-btn-info add_category_tr" data-toggle="modal" data-target="#addCategoryTr"><i class="fa fa-plus" aria-hidden="true"></i> Add Category</a>
            <a href="javascript:void(0)" class="rtr-btn rtr-btn-warning add_subcategory_tr" data-toggle="modal" data-target="#addSubcategoryTr"><i class="fa fa-plus" aria-hidden="true"></i> Add Subcategory</a>
        </div>
        <div class="rtr-panel-heading">Category List</div>
        <div class="rtr-panel-body">
            <div class="rtr-alert rtr-alert-info">
                <i><b>Note*</b>: You must have to add atleast one Subcategory each category.</i>
            </div>
            <table class="rtr-table rtr-table-bordered" id="data_categories">
                <thead>
                    <tr>
                        <th >SNo</th>
                        <th >Name</th> 
                        <th >Subcategory</th> 
                        <th >Created</th>										
                        <th >Action</th>
                    </tr>
                </thead>
                <tbody id="tmpl-category-list">
                    <?php
                    ob_start();
                    include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/tmpl/rtr-tmpl-load-categories.php';
                    $template = ob_get_contents();
                    ob_end_clean();
                    echo $template;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="addCategoryTr" class="rtr-modal bs-modal fade" role="dialog">
    <div class="rtr-modal-dialog">
        <!--Modal content-->
        <div class="rtr-modal-content cust-add-cat-pop">
            <div class="rtr-modal-header">
                <button type="button" class="rtr-close" data-dismiss="modal">&times;</button>
                <h4 class="rtr-modal-title">Adding Category...</h4>
            </div>
            <div class="rtr-modal-body">
                <form class="form-horizontal" action="javascript:void(0)" id="frmAddCatgeory">
                    <div class="rtr-form-group">
                        <label class="control-label rtr-col-sm-4" for="txtName">Name:</label>
                        <div class="rtr-col-sm-8">
                            <input type="text"required="" class="rtr-form-control" id="txtName" name="txtName" placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="rtr-form-group"> 
                        <div class="col-sm-offset-4 rtr-col-sm-8">
                            <button type="submit" class="rtr-btn rtr-btn-success">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="updateCategoryTr" class="rtr-modal rtr-bs-modal rtr-fade" role="dialog">
    <div class="rtr-modal-dialog">
        <!--Modal content-->
        <div class="rtr-modal-content">
            <div class="rtr-modal-header">
                <button type="button" class="rtr-close" data-dismiss="modal">&times;</button>
                <h4 class="rtr-modal-title">Update Category...</h4>
            </div>
            <div class="rtr-modal-body">
                <form class="form-horizontal" action="javascript:void(0)" id="frmUpdateCatgeory">

                    <input type="hidden" value="" name="category_id"/>

                    <div class="rtr-form-group">
                        <label class="control-label rtr-col-sm-4" for="txtName">Name:</label>
                        <div class="rtr-col-sm-8">
                            <input type="text"required="" class="rtr-form-control" id="txtUpdateName" name="txtUpdateName" placeholder="Enter Name">
                            <div class="rtr-form-group"> 
                        <div class="rtr-mt-1">
                            <button type="submit" class="rtr-btn rtr-btn-success">Submit</button>
                        </div>
                    </div>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="updateSubcategoryTr" class="rtr-modal rtr-bs-modal rtr-fade" role="dialog">
    <div class="rtr-modal-dialog">
        <!--Modal content-->
        <div class="rtr-modal-content cust-sub-cat-list-popup">
            <div class="rtr-modal-header">
                <button type="button" class="rtr-close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title rtr-fs-4 rtr-m-0">Subcategory list 1</h4>
            </div>
            <div class="rtr-modal-body">
                <input type="hidden" id="category_list_id"/>
                <table class="rtr-table rtr-table-bordered" id="data_categories">
                    <thead>
                        <tr>
                            <th class="rtr-col-2">SNo</th>
                            <th>Name</th>
                            <th class="rtr-col-2">Action</th>
                        </tr>
                    </thead>
                    <tbody id="subcategory-list">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="addSubcategoryTr" class="rtr-modal rtr-bs-modal rtr-fade" role="dialog">
    <div class="rtr-modal-dialog">
        <!--Modal content-->
        <div class="rtr-modal-content cust-add-subca-popup">
            <div class="rtr-modal-header">
                <button type="button" class="rtr-close" data-dismiss="modal">&times;</button>
                <h4 class="rtr-modal-title">Adding Subcategory...</h4>
            </div>
            <div class="rtr-modal-body">
                <form class="form-horizontal" action="javascript:void(0)" id="frmAddSubcatgeory">
                    <div class="rtr-form-group">
                        <label class="control-label rtr-col-sm-4" for="txtName">Choose Category:</label>
                        <div class="rtr-col-sm-8">
                            <select name="txtCategory" id="txtCategory" class="rtr-form-control">
                                <?php
                                $query =  "SELECT * from " . rtr_wpl_tr_categories();
                                $categories = $wpdb->get_results($query, ARRAY_A);
                                if (count($categories) > 0) {
                                    foreach ($categories as $inx => $stx) {
                                        ?>
                                        <option value="<?php echo $stx['id']; ?>"><?php echo $stx['name'] ?></option>
                                        <?php
                                    }
                                }
                                ?>

                            </select>
                        </div>
                    </div>
                    <div class="rtr-form-group">
                        <label class="control-label rtr-col-sm-4" for="txtName">Name:</label>
                        <div class="rtr-col-sm-8">
                            <input type="text"required="" class="rtr-form-control" id="txtName" name="txtName" placeholder="Enter Name">
                            <div class="rtr-mt-1">
                            <button type="submit" class="rtr-btn rtr-btn-success">Submit</button>
                        </div>
                        </div>
                    </div>
                       
                </form>
            </div>
        </div>
    </div>
</div>
