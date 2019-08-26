<script>    
            var total_users=<?php echo $total_users; ?>;
            var total_data=<?php echo $total_users; ?>;
            var aTargets=[ 0 ];
            var tOrder=[2, "desc"];
            var ajaxUrl=path+prefix+"/barbers/vacations_list";
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
                            <i class="fa fa-calendar"></i>Vacation Listing
                        </div>
                        
                    </div>
                    <div class="portlet-body">
						<div class="table-container">
                            <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                                <thead>
									<tr role="row" class="filter">
                                        <td colspan="5">
                                            <div class="col-sm-12">
												<div class="row filter">
													<div class="col-sm-1">
														Search:
													</div>
													<div class="col-sm-3">
														 <?php echo $this->Form->input('name',array('label'=>false,'div'=>false,'type'=>'text','class'=>'form-control form-filter input-sm')); ?>  
													</div>
												   
													<div class="col-sm-2">
														<?php echo $this->Form->input('vacation_from',array('label'=>false,'div'=>false,'type'=>'text','class'=>'vacation-date-from form-control form-filter input-sm')); ?>  
													</div>
													<div class="col-sm-2">
														<?php echo $this->Form->input('vacation_to',array('label'=>false,'div'=>false,'type'=>'text','class'=>'vacation-date-to form-control form-filter input-sm')); ?>  
													</div>  
													<?php /* <div class="hide col-sm-3">
														 <select name="data[vacation_time]" class="table-group-action-input form-control input-inline input-small input-sm">
															<option value="all">All</option>
														   <option value="availed">Availed</option>
														   <option value="upcoming">Upcoming</option>
														</select>
													</div> */ ?>
													<div class="col-sm-3">
														<div class="pull-left margin-bottom-5">
															<button class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i> <span class="hidden-480">Search</span></button>
														</div>
														&nbsp;
														<button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i> <span class="hidden-480">Reset</span></button>
													</div>
												</div>
											</div>
                                        </td>
									</tr>
                                    <tr role="row" class="heading">
                                        <th width="2%">
                                           S.No.
                                        </th>
                                        <th width="12%">
                                            Name
                                        </th>
                                        <th width="10%">
                                            Vacation From
                                        </th> 
                                         <th width="10%">
                                            Vacation Till
                                        </th> 
										<th width="10%">
                                            Vacation Status
                                        </th>
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
<?php echo $this->Common->loadJsClass('BarberVacation'); ?>