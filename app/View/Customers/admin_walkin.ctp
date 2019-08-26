<script>    
            var total_users=<?php echo $total_walkins; ?>;
            var total_data=<?php echo $total_walkins; ?>;
            var aTargets=[5];
            var tOrder=[1, "asc"];
            var ajaxUrl=path+prefix+"/customers/walkin_list";

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
                    <i class="fa fa-male"></i>
                    <a href="javascript:void(0);">Walk-In</a>
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
                            <i class="fa fa-male"></i>Customers Listing
                        </div>
                        <div class="actions">

                            <a href="<?php echo Router::url(array('admin' => true,'action' => 'export_walkins')); ?>" class="btn green yellow-stripe">
                                <i class="fa fa-file-excel-o"></i>
                                <span class="hidden-480">
                                    Export All Walkins </span>
                            </a>

                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">                             
                            <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                                <thead>
                                    <tr role="row" class="heading">                                        
                                        <th width="15%">
                                           Customer Name
                                        </th>
                                        <th width="15%">
                                           Barber Name
                                        </th>
                                         <th width="15%">
                                            Time
                                        </th> 
                                         <th width="15%">
                                            Date
                                        </th>
                                        <th width="15%">
                                            Status
                                        </th>
                                         <th width="15%">
                                            Actions
                                        </th>
                                    </tr>
                                    <tr role="row" class="filter">
                                        
                                        <td>
                                             <?php echo $this->Form->input('name',array('label'=>false,'div'=>false,'type'=>'text','class'=>'form-control form-filter input-sm')); ?>  
                                        </td>                                       
                                        <td>
                                             <?php echo $this->Form->input('barber_name',array('label'=>false,'div'=>false,'type'=>'text','class'=>'form-control form-filter input-sm')); ?>  
                                        </td>                                       
                                        <td> <?php echo $this->Form->input('slot_time',array('label'=>false,'div'=>false,'type'=>'text','class'=>'form-control form-filter input-sm')); ?>   </td>
                                        <td> <?php echo $this->Form->input('created',array('label'=>false,'div'=>false,'type'=>'text','class'=>'form-control form-filter input-sm')); ?>   </td>
                                        <td><?php echo $this->Form->input('status',array('empty'=>'Select Status','options'=>array(1=>'Seat',0=>'Dismiss'),'label'=>false,'div'=>false,'class'=>'form-control form-filter input-sm')); ?> </td>
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