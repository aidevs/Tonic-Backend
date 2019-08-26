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
                    <a href="<?php echo Router::url(array('admin' => true, 'controller' => 'barbers', 'action' => 'index')); ?>">Barbers</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="javascript:void(0);">Add New Barber</a>
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
                        <?php echo $this->Form->create('User', array('type'=>'file','class' => 'form-horizontal user-form', 'inputDefaults' => array('label' => false, 'div' => false, 'hiddenField' => false),'novalidate'=>'novalidate')); ?>               
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
                                    <label class="col-md-3 control-label">Last Name</label>
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
                                    <label class="col-md-3 control-label">Phone<span class="required" aria-required="true">
                                            * </span></label>
                                    <div class="col-md-4">
                                        <div class="input-text">
                                            <?php echo $this->Form->input('User.phone', array('class' => 'form-control')); ?>
                                        </div>
                                    </div>
                                </div>
                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Pin<span class="required" aria-required="true">
                                            * </span></label>
                                    <div class="col-md-4">
                                        <div class="input-text">
                                            <?php echo $this->Form->input('User.pin', array('options'=>$pin ,'class' => 'form-control')); ?>
                                        </div>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-md-3 control-label">Services</label>
                                    <div class="col-md-4">
                                        <div class="input-text">
                                            <?php echo $this->Form->input('service_id', array('label'=>false,'div'=>false,'multiple'=>true,'data-placeholder'=>'Select Service','class' => 'form-control select2me')); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group last">
                                    <label class="control-label col-md-3">Image</label>
                                    <div class="col-md-9">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                <img src="<?php echo SITE_URL; ?>img/no-image.png" alt=""/>
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                                            </div>
                                            <div class="body-img">
                                                <span class="btn default btn-file">
                                                    <span class="fileinput-new">
                                                        Select image </span>
                                                    <span class="fileinput-exists">
                                                        Change </span>
                                                    <?php echo $this->Form->file('User.image'); ?>
                                                </span>
                                                <a href="#" class="btn red fileinput-exists" data-dismiss="fileinput">
                                                    Remove </a>
                                            </div>
                                        </div>
                                        <div class="clearfix margin-top-10">
                                            <span class="label label-danger">
                                                NOTE! </span>
                                            &nbsp;&nbsp;Image preview only works in IE10+, FF3.6+, Safari6.0+, Chrome6.0+ and Opera11.1+. In older browsers the filename is shown instead.
                                        </div>
                                    </div>
                                </div>
                                 <?php if($this->Session->read('Auth.User.is_social_on')==1){ ?>
				<div class="form-group">
                                    <label class="col-md-3 control-label">Instagram URL</label>
                                    <div class="col-md-4">
                                        <div class="input-text">
                                            <?php echo $this->Form->input('User.insta_url', array('value'=>'http://www.instagram.com/','type'=>'text','class' => 'form-control')); ?>
                                        </div>
                                    </div>
                                </div> 
                                 <?php } ?>
				<div class="form-group">
                                    <label class="control-label col-md-3">Status</label>
                                    <div class="col-md-9">
                                        
                                       <?php echo $this->Form->input('User.status',array('checked'=>true,'data-on-color'=>'success','data-off-color'=>'danger','class'=>'make-switch','data-on-text'=>'&nbsp;Enabled&nbsp;&nbsp;','data-off-text'=>'&nbsp;Disabled&nbsp;')); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-offset-3 col-md-8"> 
                                        <?php echo $this->Form->button('<i class="fa fa-check"></i> Submit', array('type' => 'submit', 'class' => 'btn blue', 'div' => false, 'escape' => false)); ?>
                                        <a href="<?php echo Router::url(array('admin' => true, 'controller' => 'barbers', 'action' => 'index')); ?>" class="btn default button-previous"> <i class="fa fa-close"></i> Cancel </a>
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

