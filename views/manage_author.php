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
        <div class="rtr-pull-right"><a href="javascript:void(0)" class="rtr-btn rtr-btn-info add_author_tr" data-toggle="modal" data-target="#addAuthorTr"><i class="fa fa-plus" aria-hidden="true"></i> Add New</a></div>
        <div class="rtr-panel-heading">Authors List</div>
        <div class="rtr-panel-body">
            <table class="rtr-table rtr-table-bordered" id="data_courses">
                <thead>
                    <tr>
                        <th>SNo</th>
                        <th >Name</th>
                        <th >Email</th>
                        <th >Phone</th>
                        <th >Photo</th> 
                        <th >Designation</th>  
                        <th >Date</th>										
                        <th >Action</th>
                    </tr>
                </thead>
                <tbody id="tmpl-author-list">
                    <?php
                    ob_start();
                    include_once RTR_WPL_COUNT_PLUGIN_DIR . '/views/tmpl/rtr-tmpl-load-authors.php';
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
<div id="addAuthorTr" class="rtr-modal rtr-bs-modal rtr-fade cust-author-popup" role="dialog">
  <div class="rtr-modal-dialog">

    <!-- Modal content-->
    <div class="rtr-modal-content">
      <div class="rtr-modal-header">
        <button type="button" class="rtr-close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title rtr-fs-4 rtr-m-0">Adding Author...</h4>
      </div>
      <div class="rtr-modal-body">
         <form class="form-horizontal" action="javascript:void(0)" id="frmAddAuthor">
                    <input type="hidden" id="txt_type" name="txt_type" value="add"/>
                    <input type="hidden" id="txt_id" name="txt_id"/>
                    <div class="rtr-form-group">
                        <label class="control-label rtr-col-sm-4" for="txtName">Name:</label>
                        <div class="rtr-col-sm-8">
                            <input type="text" required="" class="rtr-form-control" id="txtName" name="txtName" placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="rtr-form-group">
                        <label class="control-label rtr-col-sm-4" for="txtEmail">Email:</label>
                        <div class="rtr-col-sm-8">
                            <input type="email" required class="rtr-form-control" id="txtEmail" name="txtEmail" placeholder="Enter Email">
                        </div>
                    </div>
                    <div class="rtr-form-group">
                        <label class="control-label rtr-col-sm-4" for="txtPost">Designation:</label>
                        <div class="rtr-col-sm-8">
                            <input type="text" class="rtr-form-control" id="txtPost" name="txtPost" placeholder="Enter Post">
                            <p>
                                <b>Ex: </b>Principal Investigator, Head of department 
                            </p>
                        </div>
                    </div>
                    
                    <div class="rtr-form-group">
                        <label class="control-label rtr-col-sm-4" for="txtEmail">About Author:</label>
                        <div class="rtr-col-sm-8">
                            <textarea id="txtAbout" name="txtAbout" class="rtr-form-control"></textarea>
                        </div>
                    </div>
                    <div class="rtr-form-group">
                        <label class="control-label rtr-col-sm-4" for="txtWeb">Author Website:</label>
                        <div class="rtr-col-sm-8">
                            <input type="url" class="rtr-form-control" id="txtWeb" name="txtWeb" placeholder="Enter Website">
                        </div>
                    </div>
                    <div class="rtr-form-group">
                        <label class="control-label rtr-col-sm-4" for="txtPhone">Phone no:</label>
                        <div class="rtr-col-sm-8">
                            <input type="tel" class="rtr-form-control" id="txtPhone" name="txtPhone" placeholder="Enter Phone">
                        </div>
                    </div>
                    <div class="rtr-form-group">
                        <label class="control-label rtr-col-sm-4" for="txtFacebook">Facebook URL:</label>
                        <div class="rtr-col-sm-8">
                            <input type="website" class="rtr-form-control" id="txtFacebook" name="txtFacebook" placeholder="Enter Facebook URL">
                        </div>
                    </div>
                    <div class="rtr-form-group">
                        <label class="control-label rtr-col-sm-4" for="btnImageUpload">Profile Photo:</label>
                        <div class="rtr-col-sm-8">
                            <input type="button" class="form-control defaultuploadimg" value="Upload Image">
                            <div class="uploadCourseImage"></div>
                            <input type="hidden" class="defaultCourseImgUrl" name="defaultCourseImgUrl" value=""/>
                        </div>
                    </div>
                    <div class="rtr-form-group"> 
                        <div class="col-sm-offset-4 rtr-col-sm-12">
                            <button type="submit" class="rtr-btn rtr-btn-success rtr-float-right">Submit</button>
                        </div>
                    </div>
                </form>
      </div>
      
    </div>

  </div>
</div>
