<div class="page-content-wrapper">
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <h3 class="page-title">
            <?php echo $title_for_layout; ?>
        </h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">                            
                <li>
                    <i class="fa fa-users"></i>
                    <a href="<?php echo Router::url(array('admin' => true, 'controller' => 'users', 'action' => 'index')); ?>">Admins</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="javascript:void(0);">Add New Admin</a>

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
                            <i class="fa fa-user"></i> Add New
                        </div>                
                    </div>
                    <div class="portlet-body form">
                        <?php echo $this->Form->create('User', array('class' => 'form-horizontal user-form', 'inputDefaults' => array('label' => false, 'div' => false, 'hiddenField' => false),'novalidate'=>'novalidate')); ?>               
                        <div class="form-wizard">
                            <div class="form-body">
                                
                                <div class="form-group">
                                    <label class="col-md-3 control-label">First Name<span class="required" aria-required="true">
                                            * </span></label>
                                    <div class="col-md-4">
                                        <div class="input-text">
                                            <?php echo $this->Form->input('User.first_name', array('class' => 'form-control')); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Last Name<span class="required" aria-required="true">
                                            * </span></label>
                                    <div class="col-md-4">
                                        <div class="input-text">
                                            <?php echo $this->Form->input('User.last_name', array('class' => 'form-control')); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Email Address<span class="required" aria-required="true">
                                            * </span></label>
                                    <div class="col-md-4">
                                        <div class="input-text">
                                            <?php echo $this->Form->input('User.email', array('class' => 'form-control')); ?>
                                        </div>
                                    </div>
                                </div>                         
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Shop Name<span class="required" aria-required="true">
                                            * </span></label>
                                    <div class="col-md-4">
                                        <div class="input-text">
                                            <?php echo $this->Form->input('User.shop_name', array('class' => 'form-control')); ?>
                                        </div>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Shop Description<span class="required" aria-required="true">
                                            * </span></label>
                                    <div class="col-md-4">
                                        <div class="input-text">
                                            <?php echo $this->Form->input('User.shop_description', array('rows'=>3,'class' => 'form-control')); ?>
                                        </div>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Country<span class="required" aria-required="true">
                                            * </span></label>
                                    <div class="col-md-4">
                                        <div class="input-text">
                                            <?php echo $this->Form->input('User.country_id', array('empty'=>'--Select--','class' => 'form-control')); ?>
                                        </div>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Phone<span class="required" aria-required="true">
                                            * </span></label>
                                    <div class="col-md-4">
                                        <div class="input-text">
                                            <?php echo $this->Form->input('User.phone', array('class' => 'form-control')); ?>
                                        </div>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Address<span class="required" aria-required="true">
                                            * </span></label>
                                    <div class="col-md-4">
                                        <div class="input-text">
                                            <?php echo $this->Form->input('User.address', array('rows'=>3,'class' => 'form-control')); ?>
                                        </div>
                                    </div>
                                </div> 
                                 <div class="form-group">
                                    <label class="col-md-3 control-label">Timezone<span class="required" aria-required="true">
                                            * </span></label>
                                    <div class="col-md-4">
                                        <div class="input-text">
                                            <?php echo $this->Form->input('User.timezone', array('options'=>Configure::read('TimeZones'),"label" => FALSE,'class' => 'form-control', 'div' =>FALSE));  ?>
                                        </div>
                                    </div>
                                </div> 

                                
				<div class="form-group">
                                    <label class="control-label col-md-3">Status</label>
                                    <div class="col-md-9">
                                        
                                       <?php echo $this->Form->input('User.status',array('checked'=>true,'data-on-color'=>'success','data-off-color'=>'danger','class'=>'make-switch','data-on-text'=>'&nbsp;Enabled&nbsp;&nbsp;','data-off-text'=>'&nbsp;Disabled&nbsp;')); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-offset-3 col-md-8"> 
                                        <?php echo $this->Form->button('<i class="fa fa-check"></i> Submit', array('type' => 'submit', 'class' => 'btn blue', 'div' => false, 'escape' => false)); ?>
                                        <a href="<?php echo Router::url(array('admin' => true, 'controller' => 'users', 'action' => 'index')); ?>" class="btn default button-previous"><i class="fa fa-close"></i> Cancel </a>
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
<?php echo $this->Common->loadJsClass('Users'); ?>

