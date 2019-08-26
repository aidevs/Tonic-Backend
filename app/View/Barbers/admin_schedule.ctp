<div class="page-content-wrapper">
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <h3 class="page-title">
            <?php echo $title_for_layout; ?>
        </h3>
         <?php //echo $this->Common->sessionFlash(); ?>
        <div class="page-bar">
            <ul class="page-breadcrumb">                            
                <li>
                    <i class="fa fa-calendar"></i>
                    <a href="<?php echo Router::url(array('admin' => true, 'controller' => 'barbers', 'action' => 'index')); ?>">Barbers</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="javascript:void(0);">Barber Schedule</a>

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
                            <i class="fa fa-calendar"></i> Schedule Detail
                        </div>                
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">                             
                            <table class="table table-striped table-bordered table-hover table-schedule">
                                <thead>
                                    <tr role="row" class="heading">                                       
                                        <th width="15%">
                                            Days
                                        </th>
                                         <th width="15%">
                                            Start Time
                                        </th>                                                                       
                                        <th width="15%">
                                            End Time
                                        </th>
<!--                                        <th width="15%">
                                            Slot Time
                                        </th>-->
                                        <th width="8%">
                                           Working
                                        </th>
                                        <th width="15%">
                                            Actions
                                        </th>
                                    </tr>                                    
                                </thead>
                                <tbody>
                                    <?php 
                                    $w=0;
                                    foreach ($schedules as $schedule) {  ?>
                                    <tr role="row" class="<?php echo ($w%2==0)?'even':'odd'; ?>">
                                      <td><?php echo $schedule['Week']['name']; ?></td> 
                                      <input type="hidden" class="schedule-user-id" name="data[Schedule][user_id]" value="<?php echo $this->params['pass'][0]; ?>" />
                                      <input type="hidden" class="schedule-week-id" name="data[Schedule][week_id]" value="<?php echo $schedule['Week']['id']; ?>" />
                                      <?php if(isset($schedule['Schedule']['id']) && $schedule['Schedule']['id']!=''){ ?>
                                      <input type="hidden" class="schedule-id" name="data[Schedule][id]" value="<?php echo $schedule['Schedule']['id']; ?>" />
                                                                           
                                      <td><?php echo $schedule['Schedule']['start_time']; ?></td>  
                                      <td><?php echo $schedule['Schedule']['end_time']; ?></td>  
<!--                                      <td><?php //echo $schedule['Schedule']['slot_time']; ?> Minutes</td>  -->
                                      <td> <?php echo $this->Form->input('working',array('label'=>false,'div' => false, 'checked'=>($schedule['Schedule']['working']==1)?true:false,'data-on-color'=>'primary','data-off-color'=>'danger','data-size'=>'small','class'=>'make-switch','data-on-text'=>'&nbsp;Yes&nbsp;&nbsp;','data-off-text'=>'&nbsp;No&nbsp;','data-week'=>$schedule['Week']['id'],'data-schedule'=>$schedule['Schedule']['id'],'data-user'=>$this->params['pass'][0])); ?></td>  
                                      <td>
                                          <?php 
                                          if($schedule['Week']['name']==date('l')){
                                            echo  $this->BootForm->link('<i class="fa fa-calendar"></i> <span class="hidden-480">Re-Schedule</span>','javascript:;', array('alert' => __('Current day can&#39;t be rescheduled.'), 'class' => 'btn default btn-xs green', 'escape' => false));  
                                          }else{
                                          echo '<a class="btn default btn-xs green reschedule-btn" href="javascript:;"><i class="fa fa-calendar"></i> <span class="hidden-480">Re-Schedule</span> </a>'; 
                                          
                                          }
                                         
                                          ?></td>
                                      <?php }else{ ?>
                                      <td><span class="label label-sm label-warning">N/A</span></td>  
                                      <td><span class="label label-sm label-warning">N/A</span></td>  
                                      <td><span class="label label-sm label-warning">N/A</span></td>  
<!--                                      <td><span class="label label-sm label-warning">N/A</span></td>  -->
                                      <td><?php echo '<a class="btn default btn-xs blue reschedule-btn" href="javascript:;"><i class="fa fa-calendar"></i> <span class="hidden-480">Schedule</span> </a>'; ?></td>
                                      <?php } ?>
                                    </tr>   
                                   <?php $w++; } ?>
                                     
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php echo $this->Common->loadJsClass('Schedule'); ?>

