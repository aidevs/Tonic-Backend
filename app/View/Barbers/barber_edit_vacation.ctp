<div class="page-content-wrapper">
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <h3 class="page-title">
            <?php echo $title_for_layout; ?>
        </h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">                            
                <li>
                    <i class="fa fa-tree"></i>
                    <a href="<?php echo Router::url(array('barber' => true, 'controller' => 'barbers', 'action' => 'vacations')); ?>">Vacations</a>
                    <i class="fa fa-angle-right"></i>
                </li>               
                <li>
                    <a href="javascript:void(0);">Edit</a>

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
                            <i class="fa fa-tree"></i> Edit
                        </div>                
                    </div>
                    <div class="portlet-body form">
                        <?php
                        echo $this->Form->create('Vacation', array('class' => 'form-horizontal vacation-form', 'inputDefaults' => array('label' => false, 'div' => false, 'hiddenField' => false), 'novalidate' => 'novalidate'));
                        echo $this->Form->hidden('Vacation.id');
                        ?>
                        <div class="form-wizard">
                            <div class="form-body">  
                                <div class="form-group">
                                    <label class="col-md-2 control-label">From Date<span class="required" aria-required="true">
                                            * </span></label>
                                    <div class="col-md-2">
                                        <div class="input-text">
                                            <?php
                                            echo $this->Form->input('from_date', array(
                                                'class' => 'form-control vacation-date-from',
                                                'type' => 'text',
                                                'value' => isset($this->request->data['Vacation']['from_date']) ? date('m/d/Y H:i', strtotime($this->request->data['Vacation']['from_date'])) : '',
                                            ));
                                            ?>
                                        </div>
                                    </div>

                                    <label class="col-md-2 control-label">To Date<span class="required" aria-required="true">
                                            * </span></label>
                                    <div class="col-md-2">
                                        <div class="input-text">
                                            <?php
                                            echo $this->Form->input('to_date', array(
                                                'class' => 'form-control vacation-date-to',
                                                'type' => 'text',
                                                'value'=>isset($this->request->data['Vacation']['to_date'])?date('m/d/Y H:i', strtotime($this->request->data['Vacation']['to_date'])):'',
                                                    )
                                            );
                                            ?>
                                        </div>
                                    </div>

                                    <!--                                    <div class="col-md-4 btn-body">
                                                                            <a href="javascript:;" class="btn btn-success add-more-date">Add More</a>
                                                                        </div>-->
                                </div>

                                <div class="form-group">
                                    <div class="col-md-offset-3 col-md-8"> 
                        <?php echo $this->Form->button('<i class="fa fa-check"></i> Submit', array('type' => 'submit', 'class' => 'btn blue', 'div' => false, 'escape' => false)); ?>
                                        <a href="<?php echo Router::url(array('barber' => true, 'controller' => 'barbers', 'action' => 'vacations')); ?>" class="btn default button-previous"><i class="fa fa-close"></i> Cancel </a>
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
<?php echo $this->Common->loadJsClass('Barber'); ?>