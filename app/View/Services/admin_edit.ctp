<div class="page-content-wrapper">
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <h3 class="page-title">
            <?php echo $title_for_layout; ?>
        </h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">                            
                <li>
                    <i class="fa fa-list"></i>
                    <a href="<?php echo Router::url(array('admin' => true, 'controller' => 'services', 'action' => 'index')); ?>">Services</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="javascript:void(0);">Edit Service</a>
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
                            <i class="fa fa-list"></i> Edit
                        </div>                
                    </div>
                    <div class="portlet-body form">
                        <?php echo $this->Form->create('Service', array('type'=>'file','class' => 'form-horizontal service-form', 'inputDefaults' => array('label' => false, 'div' => false, 'hiddenField' => false),'novalidate'=>'novalidate')); ?>  
                         <?php echo $this->Form->input('id'); ?>
                        <div class="form-wizard">
                            <div class="form-body">
                                
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Name<span class="required" aria-required="true">
                                            * </span></label>
                                    <div class="col-md-4">
                                        <div class="input-text">
                                            <?php echo $this->Form->input('name', array('class' => 'form-control')); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Description</label>
                                    <div class="col-md-4">
                                        <div class="input-text">
                                            <?php echo $this->Form->input('description', array('required'=>false,'type'=>'text','class' => 'form-control')); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Cost<span class="required" aria-required="true">
                                            * </span></label>
                                    <div class="col-md-4">
                                        <div class="input-text">
                                            <?php echo $this->Form->input('cost', array('type'=>'text','class' => 'form-control')); ?>
                                        </div>
                                    </div>
                                </div>                         
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Time(Minutes)<span class="required" aria-required="true">
                                            * </span></label>
                                    <div class="col-md-4">
                                        <div class="input-text">
                                            <?php
                                            $dis=(!empty($this->request->data['BarberService']))?true:false;
                                            $opt=array(15,30,45,60,75);
                                            $opt= array_combine($opt, $opt);
                                            echo $this->Form->input('time', array('options'=>$opt,'disabled'=>$dis,'class' => 'form-control')); ?>
                                        </div>
                                    </div>
                                </div>                         
                                                      
				<div class="form-group">
                                    <label class="control-label col-md-3">Status</label>
                                    <div class="col-md-9">
                                        
                                       <?php echo $this->Form->input('status',array('data-on-color'=>'success','data-off-color'=>'danger','class'=>'make-switch','data-on-text'=>'&nbsp;Enabled&nbsp;&nbsp;','data-off-text'=>'&nbsp;Disabled&nbsp;')); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-offset-3 col-md-8"> 
                                        <?php echo $this->Form->button('<i class="fa fa-check"></i> Submit', array('type' => 'submit', 'class' => 'btn blue', 'div' => false, 'escape' => false)); ?>
                                        <a href="<?php echo Router::url(array('admin' => true, 'controller' => 'services', 'action' => 'index')); ?>" class="btn default button-previous"> <i class="fa fa-close"></i> Cancel </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php echo $this->Common->loadJsClass('Service'); ?>
