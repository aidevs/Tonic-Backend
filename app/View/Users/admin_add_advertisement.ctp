<div class="page-content-wrapper">
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <h3 class="page-title">
            <?php echo $title_for_layout; ?>
        </h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">                            
                <li>
                    <i class="fa fa-dashboard"></i>
                    <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'dashboard', 'admin' => true)) ?>">Dashboard</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="javascript:void(0);">Advertisement</a>
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
                            <i class="fa fa-user"></i> Advertisement
                        </div>                
                    </div>
                    <div class="portlet-body form">
                        <?php echo $this->Form->create('Advertisement', array('type' => 'file', 'class' => 'form-horizontal user-form', 'inputDefaults' => array('label' => false, 'div' => false, 'hiddenField' => false), 'novalidate' => 'novalidate')); ?>               
                        <div class="form-wizard">
                            <div class="form-body">
                                <?php
                                if (isset($Advertisement)) {
                                    if ($Advertisement['Advertisement']['image'] != "") {
                                        if (file_exists(WWW_ROOT . 'uploads' . DS . 'advertisement' . DS . $Advertisement['Advertisement']['image'])) {
                                            ?>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"></label>
                                                <div class="col-md-4">
                                                    <img src="<?php echo SITE_URL . 'uploads/advertisement/' . $Advertisement['Advertisement']['image']; ?>" width="400" />
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Advertisement Image<span class="required" aria-required="true">
                                            * </span></label>
                                    <div class="col-md-4">
                                        <div class="input-text">
                                            <?php echo $this->Form->input('id'); ?>
                                            <?php echo $this->Form->input('image', array('type' => 'hidden')); ?>

                                            <?php echo $this->Form->input('newimage', array('type' => 'file')); ?>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="col-md-offset-3 col-md-8"> 
                                        <?php echo $this->Form->button('<i class="fa fa-check"></i> Submit', array('type' => 'submit', 'class' => 'btn blue', 'div' => false, 'escape' => false)); ?>
                                        <a href="<?php echo Router::url(array('admin' => true, 'controller' => 'users', 'action' => 'advertisement')); ?>" class="btn default button-previous"><i class="fa fa-close"></i> Cancel </a>
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