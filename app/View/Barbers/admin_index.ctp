<script>
    var total_users =<?php echo $total_users; ?>;
    var total_data =<?php echo $total_users; ?>;
    var aTargets = [0, 5];
    var tOrder = [1, "asc"];
    var ajaxUrl = path + prefix + "/barbers/list";
    var TableCallback = function (ss) {
        $('.un-achive').parents('tr').addClass('danger');
    }

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
                    <a href="javascript:void(0);">Barbers</a>
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
                            <i class="fa fa-users"></i>Barbers Listing
                        </div>
                        <div class="actions">

                            <a href="<?php echo Router::url(array('admin' => true, 'controller' => 'barbers', 'action' => 'add')); ?>" class="btn green yellow-stripe">
                                <i class="fa fa-plus"></i>
                                <span class="hidden-480">
                                    New Barber </span>
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
                                        <th width="12%">
                                            Name
                                        </th>

                                        <th width="10%">
                                            Phone
                                        </th> 
                                        
                                        <th width="8%">
                                            Pin
                                        </th> 
                                        <th width="10%">
                                            Status
                                        </th>
                                        <th width="30%">
                                            Actions
                                        </th>
                                    </tr>
                                    <tr role="row" class="filter">
                                        <td></td>
                                        <td>
                                            <?php echo $this->Form->input('name', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'form-control form-filter input-sm')); ?>  
                                        </td>                                       

                                        <td>
                                            <?php echo $this->Form->input('phone', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'form-control form-filter input-sm')); ?>  
                                        </td>
                                                                             
                                        <td>
                                            <?php echo $this->Form->input('pin', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'form-control form-filter input-sm')); ?>  
                                        </td>                                    
                                        <td><?php echo $this->Form->input('status', array('options' => array(1 => 'Enabled', 0 => 'Disabled'), 'label' => false, 'div' => false, 'class' => 'form-control form-filter input-sm', 'empty' => 'Select')); ?></td>                                       
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
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
<?php echo $this->Common->loadJsClass('Barber'); ?>