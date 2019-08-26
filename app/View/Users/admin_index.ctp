<script>
    var total_users =<?php echo $total_users; ?>;
    var total_data =<?php echo $total_users; ?>;
    var aTargets = [0, 8];
    var tOrder = [1, "asc"];
    var ajaxUrl = path + prefix + "/users/list";

</script>
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
                    <i class="fa fa-users"></i>
                    <a href="javascript:void(0);">Admins</a>
                </li>
            </ul>

        </div>
        <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-md-12">

                <!-- Begin: life time stats -->
                <div class="portlet">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-users"></i>Admins Listing
                        </div>
                        <div class="actions">

                            <a href="<?php echo Router::url(array('admin' => true, 'controller' => 'users', 'action' => 'add')); ?>" class="btn green yellow-stripe">
                                <i class="fa fa-plus"></i>
                                <span class="hidden-480">
                                    New Admin </span>
                            </a>

                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">
                            <div class="table-actions-wrapper">
                                <span>
                                </span>
                                <select class="table-group-action-input form-control input-inline input-small input-sm">
                                    <option value="">Select...</option>
                                    <option value="1">Enabled</option>
                                    <option value="0">Disabled</option>
                                </select>
                                <button class="btn btn-sm yellow table-group-action-submit"><i class="fa fa-check"></i><span class="hidden-480">Submit</span> </button>
                            </div>
                            <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th width="2%">
                                            <input type="checkbox" class="group-checkable">
                                        </th>
                                        <th width="15%">
                                            Name
                                        </th>                                       
                                        <th width="12%">
                                            Email
                                        </th>
                                        <th width="15%">
                                            Shop Name
                                        </th>                                                                             
                                        <th width="10%">
                                            Country
                                        </th>                                                                             
                                        <th width="10%">
                                            Phone
                                        </th>                                                                      
                                        <th width="10%">
                                            Status
                                        </th>
										
										<th width="10%">
                                            Barber Access
                                        </th>
										
                                        <th width="18%">
                                            Actions
                                        </th>
                                    </tr>
                                    <tr role="row" class="filter">
                                        <td></td>
                                        <td>
                                            <?php echo $this->Form->input('name', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'form-control form-filter input-sm')); ?>  
                                        </td>
                                        <td>
                                            <?php echo $this->Form->input('email', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'form-control form-filter input-sm')); ?>   
                                        </td>
                                        <td> <?php echo $this->Form->input('shop_name', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'form-control form-filter input-sm')); ?>   </td>
                                        <td>
                                            <?php echo $this->Form->input('country_id', array('label' => false, 'div' => false, 'class' => 'form-control form-filter input-sm', 'empty' => 'Select')); ?>   
                                        </td>
                                        <td><?php echo $this->Form->input('phone', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'form-control form-filter input-sm')); ?> </td>                                      
                                       
                                        <td><?php echo $this->Form->input('status', array('options' => array(1 => 'Enabled', 0 => 'Disabled'), 'label' => false, 'div' => false, 'class' => 'form-control form-filter input-sm', 
										'empty' => 'Select')); ?></td>
										
										<td><?php echo $this->Form->input('unlimited_barber', array('options' => array(1 => 'Unlimited', 0 => 'Limited'), 'label' => false, 'div' => false, 'class' => 'form-control form-filter input-sm', 'empty' => 'Select')); ?></td>
										
                                        <td>
                                            <div class="pull-left margin-bottom-5">
                                                <button class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i> <span class="hidden-480">Search</span></button>
                                            </div>
                                            <button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i> <span class="hidden-480">Reset</span></button>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End: life time stats -->
            </div>
        </div>

    </div>
</div>
<div aria-hidden="true" role="basic" id="ajax-version-view" class="modal fade" data-keyboard="false" data-backdrop="static">
    <div class="page-loading page-loading-boxed">
        <img class="loading" alt="" src="<?php echo ASSETS_URL; ?>assets/global/img/loading-spinner-grey.gif">
        <span>
            &nbsp;&nbsp;Loading... </span>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>