<div class="page-content-wrapper">
    <div class="page-content" style="min-height:884px">
        <!-- BEGIN PAGE CONTENT-->
        <div class="row margin-top-20">
            <div class="col-md-12">
                <!-- BEGIN PROFILE CONTENT -->
                <div class="profile-content">
                    <?php echo $this->Common->sessionFlash(); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet light">
                                <div class="portlet-title tabbable-line">
                                    <div class="caption caption-md">
                                        <i class="icon-globe theme-font hide"></i>
                                        <span class="caption-subject font-blue-madison bold uppercase">Account Settings</span>
                                    </div>
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a data-toggle="tab" href="#profile" aria-expanded="true">Change Profile</a>
                                        </li>                                       
                                        <li class="">
                                            <a data-toggle="tab" href="#password" aria-expanded="false">Change Password</a>
                                        </li>											
                                    </ul>
                                </div>
                                <div class="portlet-body">
                                    <div class="tab-content">
                                        <!-- PERSONAL INFO TAB -->
                                        <div id="profile" class="tab-pane active">
                                            <?php
                                            echo $this->Form->create('User', array('url' => array('action' => 'admin_profile'), 'novalidate' => 'novalidate', 'class' => 'user_edit_profile_form'));
                                            echo $this->Form->hidden('id');

                                            echo $this->Form->input('first_name', array('class' => 'form-control', "label" => array('class' => 'control-label'), 'div' => array('class' => 'form-group')));
                                            echo $this->Form->input('last_name', array('class' => 'form-control', "label" => array('class' => 'control-label'), 'div' => array('class' => 'form-group')));
                                            ?>
                                            <?php
                                            echo $this->Form->input('email', array('class' => 'form-control', "placeholder" => "Email Address", "label" => array('class' => 'control-label'), 'div' => array('class' => 'form-group'), 'disabled'));
                                            if ($this->Session->read('Auth.User.role_id') == 2) {
                                                echo $this->Form->input('url', array("label" => array('class' => 'control-label', 'text' => 'Shop URL'), 'readonly' => true, 'value' => Router::url(array('admin' => false, 'controller' => 'users', 'action' => 'login', 'slug' => $this->request->data['User']['shop_slug']), true), 'class' => 'form-control', 'div' => array('class' => 'form-group')));
                                                echo $this->Form->input('timezone', array('options' => Configure::read('TimeZones'), "label" => array('class' => 'control-label', 'text' => 'Timezone'), 'class' => 'form-control', 'div' => array('class' => 'form-group')));
                                             echo $this->Form->input('window_hours', array('class' => 'form-control', "label" => array('class' => 'control-label'), 'div' => array('class' => 'form-group')));
                                             
                                            echo $this->Form->input('review_url', array('type'=>'text','class' => 'form-control', "label" => array('class' => 'control-label'), 'div' => array('class' => 'form-group'))); 
                                            }
                                            ?>
                                            <div class="margiv-top-10">						
                                                <?php echo $this->Form->button('<i class="fa fa-check"></i> Submit', array('type' => 'submit', 'class' => 'btn blue', 'div' => false, 'escape' => false)); ?>
                                                <?php echo $this->Html->link('<i class="fa fa-close"></i> Cancel', array('action' => 'dashboard'), array('class' => 'btn default', 'escape' => false)); ?>
                                            </div>
                                            <?php echo $this->Form->end(); ?>
                                        </div>
                                        <!-- END PERSONAL INFO TAB -->                                       
                                        <!-- CHANGE PASSWORD TAB -->
                                        <div id="password" class="tab-pane">
                                            <?php
                                            echo $this->Form->create('User', array('url' => array('action' => 'admin_change_password'), 'novalidate' => 'novalidate', 'class' => 'user_change_password_form'));
                                            echo $this->Form->hidden('id');

                                            echo $this->Form->input('old_password', array('type' => 'password', 'class' => 'form-control', 'label' => array('class' => 'control-label', 'text' => 'Current Password'), 'div' => array('class' => 'form-group')));
                                            ?>
                                            <?php echo $this->Form->input('password', array('type' => 'password', 'class' => 'form-control', "label" => array('class' => 'control-label', 'text' => 'New Password'), 'div' => array('class' => 'form-group'))); ?>

                                            <?php echo $this->Form->input('confirm_password', array('type' => 'password', 'class' => 'form-control', "label" => array('class' => 'control-label'), 'div' => array('class' => 'form-group'))); ?>

                                            <div class="margin-top-10">
                                                <?php echo $this->Form->button('<i class="fa fa-check"></i> Submit', array('type' => 'submit', 'class' => 'btn blue', 'div' => false, 'escape' => false)); ?>
                                                <?php echo $this->Html->link('<i class="fa fa-close"></i> Cancel', array('action' => 'dashboard'), array('class' => 'btn default', 'escape' => false)); ?>
                                            </div>
                                            <?php echo $this->Form->end(); ?>
                                        </div>
                                        <!-- END CHANGE PASSWORD TAB -->		
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PROFILE CONTENT -->
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>
</div>
<?php echo $this->Common->loadJsClass('AccountSetting'); ?>