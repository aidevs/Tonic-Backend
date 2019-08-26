<div class="page-content-wrapper">
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <h3 class="page-title">
            Site Settings
        </h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">                            
                <li>
                    <i class="fa fa-cogs"></i>
                    <a href="javascript:;">Settings</a>
                    
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
                            <i class="fa fa-cogs "></i> Change Settings
                        </div>                
                    </div>
                    <div class="portlet-body form">
                        <?php echo $this->Form->create('Setting', array('url' => array('controller' => 'settings', 'action' => 'prefix', $prefix),'class' => 'form-horizontal setting-form')); ?>
                         <div class="form-wizard">
                         <div class="form-body">
                                           
                                                <?php
                                                $i = 0;
                                                foreach ($settings AS $setting) {
                                                    $key = $setting['Setting']['key'];
                                                    $keyE = explode('.', $key);
                                                    $keyTitle = Inflector::humanize($keyE['1']);

                                                    $label = $keyTitle;
                                                    if ($setting['Setting']['title'] != null) {
                                                        $label = $setting['Setting']['title'];
                                                    }

                                                    $inputType = 'text';
                                                    if ($setting['Setting']['input_type'] != null) {
                                                        $inputType = $setting['Setting']['input_type'];
                                                    }

                                                    echo '<div class="form-group">';
                                                    echo '<label class="col-md-3 control-label">'.$label.'</label>';
                                                    echo $this->Form->input("Setting.$i.id", array('value' => $setting['Setting']['id']));
                                                    echo $this->Form->input("Setting.$i.key", array('type' => 'hidden', 'value' => $key));
                                                    if ($setting['Setting']['input_type'] == 'checkbox') {
                                                        echo $this->Form->input("Setting.$i.value",array('div' => array('class' => 'col-md-4'),'label' => false,'type'=>$inputType,'checked'=>($setting['Setting']['value']==1)?true:false,'data-on-color'=>'success','data-off-color'=>'danger','class'=>'make-switch','data-on-text'=>'&nbsp;Yes&nbsp;&nbsp;','data-off-text'=>'&nbsp;No&nbsp;'));
                                                       
                                                    } else {
                                                      
                                                       
                                                        echo $this->Form->input("Setting.$i.value", array(
                                                            'label' => false,
                                                            'type' => $inputType,
                                                            'value' => $setting['Setting']['value'],
                                                            'rel' => $setting['Setting']['description'],
                                                            'class' => 'form-control',
                                                            'div' => array('class' => 'col-md-4')
                                                        ));
                                                      
                                                    }
                                                   
                                                    $i++;
                                                      echo '</div>';
                                                }
                                                ?>
                                            										
                            <div class="form-group">
                                <div class="col-md-offset-3 col-md-8"> 
                                    <?php echo $this->Form->button('<i class="fa fa-check"></i> Submit', array('type' => 'submit', 'class' => 'btn blue', 'div' => false, 'escape' => false)); ?>
                                    <a href="<?php echo Router::url(array('admin' => true, 'controller' => 'users', 'action' => 'dashboard')); ?>" class="btn default button-previous"><i class="fa fa-close"></i> Cancel</a>
                                </div>
                            </div>
                            </div>
                            </div>
                                            <?php
                                            echo $this->Form->end();
                                            ?>
                            
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php echo $this->Common->loadJsClass('Setting'); ?>

