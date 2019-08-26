<div class="page-content-wrapper">
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <h3 class="page-title">
            <?php echo $title_for_layout; ?>
        </h3>
        <?php echo $this->Common->sessionFlash(); ?>
        <div class="page-bar">
            <ul class="page-breadcrumb">                            
                <li>
                    <i class="fa fa-film"></i>
                    <a href="javascript:void(0);">Advertisement</a>                    
                </li>                
            </ul>
        </div>
        <!-- END PAGE HEADER-->
        <div class="row">

            <div class="col-md-12">
                <?php echo $this->Common->sessionFlash(); ?>
                <div class="portlet" id="form_wizard_1">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-film"></i>Advertisement Listing
                        </div>       
                        <div class="actions">
                            <?php if (count($data) <= 10) { ?>
                                <a href="<?php echo Router::url(array('admin' => true, 'controller' => 'users', 'action' => 'add_advertisement')); ?>" class="btn green yellow-stripe">
                                    <i class="fa fa-plus"></i>
                                    <span class="hidden-480">
                                        Add </span>
                                </a>
                            <?php } ?>

                        </div>
                    </div>

                    <div class="portlet-body">
                        <div class="table-container">                             
                            <table class="table table-striped table-bordered table-hover table-schedule">
                                <thead>
                                    <tr role="row" class="heading">                                       
                                        <th width="20%">
                                            Advertisement Image
                                        </th>
                                        <th width="20%">
                                            Status
                                        </th>
                                        <th width="15%">
                                            Created On
                                        </th>
                                        <th width="8%">
                                            Updated On
                                        </th>
                                        <th width="20%">
                                            Actions
                                        </th>
                                    </tr>                                    
                                </thead>
                                <tbody>
                                    <?php
                                    $w = 0;
                                    foreach ($data as $Data) {
                                        ?>
                                        <tr role="row" class="<?php echo ($w % 2 == 0) ? 'even' : 'odd'; ?>">
                                            <td>
                                                <?php
                                                if ($Data['Advertisement']['image'] != "") {
                                                    if (file_exists(WWW_ROOT . 'uploads' . DS . 'advertisement' . DS . $Data['Advertisement']['image'])) {
                                                        ?>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label"></label>
                                                            <div class="col-md-4">
                                                                <img  src="<?php echo SITE_URL . 'uploads/advertisement/' . $Data['Advertisement']['image']; ?>" width="100" />
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                ?>

                                            </td> 
                                            <td>
                                                <?php if($Data['Advertisement']['status']==1) { ?>
                                                <a class="btn btn-xs" href="<?php echo Router::url(array('admin' => true, 'controller' => 'users', 'action' => 'changes_status_advertisement', $Data['Advertisement']['id'],0))?>">
                                                    <span class="hidden-480 label label-success">Enabled</span>
                                                   
                                                </a>
                                                <?php } else {?>
                                                  <a class="btn btn-xs" href="<?php echo Router::url(array('admin' => true, 'controller' => 'users', 'action' => 'changes_status_advertisement', $Data['Advertisement']['id'],1))?>">
                                                    <span class="hidden-480 label label-danger">Disabled</span>
                                                   
                                                </a> 
                                                <?php  } ?>
                                              
                                              

                                            </td>
                                            <td>
                                                <?php echo date('m/d/Y', strtotime($Data['Advertisement']['created'])); ?>
                                            </td>

                                            <td>
                                                <?php echo date('m/d/Y', strtotime($Data['Advertisement']['updated'])); ?>
                                            </td> 
                                            <td> 
                                                <?php
                                                echo
                                                '<a class="btn default btn-xs purple" href="' . Router::url(array('admin' => true, 'controller' => 'users', 'action' => 'add_advertisement', $Data['Advertisement']['id'])) . '"><i class="fa fa-edit"></i> <span class="hidden-480">Edit</span> </a>';
                                                echo $delete = $this->BootForm->postLink('<i class="fa fa-trash-o"></i> <span class="hidden-480">Delete</span>', array('admin' => true, 'controller' => 'users', 'action' => 'delete_advertisement', $Data['Advertisement']['id']), array('confirm' => __('Are you sure you want to delete record?'), 'class' => 'btn default btn-xs red', 'escape' => false));
                                                ?>
                                            </td> 


                                        </tr>   
                                        <?php
                                        $w++;
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
<div aria-hidden="true" role="basic" id="lunch-ajax" class="modal fade" style="display: none;">    
    <div class="modal-dialog">
        <div class="modal-content">

        </div>
    </div>
</div> 