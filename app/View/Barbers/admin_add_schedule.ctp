<div class="page-content-wrapper">
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <h3 class="page-title">
            <?php echo $title_for_layout; ?>
        </h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">                            
                <li>
                    <i class="fa fa-calendar"></i>
                    <a href="<?php echo Router::url(array('admin' => true, 'controller' => 'barbers', 'action' => 'index')); ?>">Barbers</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-calendar"></i>
                    <a href="<?php echo Router::url(array('admin' => true, 'controller' => 'barbers', 'action' => 'schedule',$user['User']['id'],$week['Week']['id'])); ?>"><?php echo $user['User']['name'] ?> Schedule</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="javascript:void(0);">Schedule Detail</a>

                </li>
            </ul>
        </div>
        <!-- END PAGE HEADER-->
        <div class="row">

            <div class="col-md-12">
                <?php echo $this->Common->sessionFlash(); ?>
                <div class="portlet box blue" id="form_wizard_1">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-calendar"></i> Schedule Detail
                        </div>                
                    </div>
                    <div class="portlet-body form">
                        <?php echo $this->Form->create('Schedule', array('class' => 'form-horizontal schedule-form', 'inputDefaults' => array('label' => false, 'div' => false, 'hiddenField' => false),'novalidate'=>'novalidate')); ?>   
                         <?php echo $this->Form->hidden('week_id', array('value'=>$week['Week']['id'])); ?>
                        <div class="form-wizard">
                            <div class="form-body">
                                
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Working Hours<span class="required" aria-required="true">
                                            * </span></label>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <?php echo $this->Form->input('Schedule.start_time', array('data-default-time'=>'09:00 AM','class' => 'timepicker-no-seconds form-control','readonly'=>true)); ?>
                                            <span class="input-group-addon">
                                                to </span>
                                            <?php echo $this->Form->input('Schedule.end_time', array('data-default-time'=>'10:00 AM','class' => 'timepicker-no-seconds form-control','readonly'=>true)); ?>
                                        </div>
                                        
                                    </div>
                                </div>                                
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Appointment Slot</label>
                                    <div class="col-md-9">
                                      <div data-toggle="buttons" class="btn-group slot-time-body">  
                                     <?php                                     
                                     echo $this->Form->input('Schedule.slot_time',array('options'=>array(30=>'30 Minute',45=>'45 Minute',60=>'1 Hour'),'type'=>'radio','class'=>'star','legend'=>false,'before'=>'<label class="btn btn-default btn-sm">','after'=>'</label>', 'separator'=>'</label><label class="btn btn-default btn-sm">')); ?>   
                                      </div> 
                                    </div>
                                </div>
                                <?php if(empty($this->request->data)){ ?>
                                <div class="form-group">
                                    <div class="col-md-offset-3 col-md-8"> 
                                        <?php echo $this->Form->button('<i class="fa fa-check"></i> Submit', array('type' => 'submit', 'class' => 'btn blue', 'div' => false, 'escape' => false)); ?>
                                        <a href="<?php echo Router::url(array('admin' => true, 'controller' => 'barbers', 'action' => 'index')); ?>" class="btn default button-previous"><i class="fa fa-close"></i> Cancel </a>
                                    </div>
                                </div>
                                <?php }else{ ?>
                                <div class="form-group">
                                    <div class="col-md-offset-3 col-md-8">
                                        <a href="<?php echo Router::url(array('admin' => true, 'controller' => 'barbers', 'action' => 're_schedule',$this->request->data['Schedule']['user_id'])); ?>" class="btn blue"><i class="fa fa-calendar"></i> Re-Schedule</a>
                                        <a href="<?php echo Router::url(array('admin' => true, 'controller' => 'barbers', 'action' => 'index')); ?>" class="btn default button-previous"><i class="fa fa-close"></i> Cancel </a>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php echo $this->Common->loadJsClass('Schedule'); ?>

