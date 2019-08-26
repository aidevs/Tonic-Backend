<script>
    var total_users =<?php echo $total_service; ?>;
    var total_data =<?php echo $total_service; ?>;
    var aTargets = [5];
    var tOrder = [1, "asc"];

    var ajaxUrl = path + prefix + "/services/list";

</script>
<div class="page-content-wrapper">
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <h3 class="page-title">
            <?php echo $title_for_layout; ?>
        </h3>
        <?php echo $this->Common->sessionFlash(); ?>
        <div class="page-bar" id="form_wizard_1">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-list"></i>
                    <a href="javascript:void(0);">Services</a>
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
                            <i class="fa fa-list"></i>Services Listing
                        </div>
                        <div class="actions">
                            
                            <a href="<?php echo Router::url(array('admin'=>true,'controller' => 'services', 'action' => 'add')); ?>" class="btn green yellow-stripe">
                                <i class="fa fa-plus"></i>
                                <span class="hidden-480">
                                    New Service </span>
                            </a>

                        </div>

                    </div>
                    <div class="portlet-body">
                        <div class="table-container">                             
                            <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                                <thead>
                                    <tr role="row" class="heading">                                        
                                        <th width="15%">
                                            Name 
                                        </th>
                                        <th width="15%">
                                            Description
                                        </th>
                                        <th width="15%">
                                            Cost
                                        </th>
                                        <th width="15%">
                                            Time
                                        </th>                                        
                                        <th width="8%">
                                            Status
                                        </th>
                                        <th width="15%">
                                            Actions
                                        </th>
                                    </tr>
                                    <tr role="row" class="filter">

                                        <td>
                                            <?php echo $this->Form->input('name', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'form-control form-filter input-sm')); ?>  
                                        </td>                                       
                                        <td>
                                            <?php echo $this->Form->input('description', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'form-control form-filter input-sm')); ?>  
                                        </td> 
                                        <td>
                                            <?php echo $this->Form->input('cost', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'form-control form-filter input-sm')); ?>  
                                        </td>
                                        <td>
                                            <?php echo $this->Form->input('time', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'form-control form-filter input-sm')); ?>  
                                        </td>
                                        
                                        <td> &nbsp;</td>
                                        
                                        <td>
                                            <div class="pull-left margin-bottom-5">
                                                <button class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i> <span class="hidden-480">Search</span></button>
                                            </div>
                                            <button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i> <span class="hidden-480">Reset</span></button>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody id="reservation_list">
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


