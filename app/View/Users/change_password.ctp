<div class="mainLinks loginFrom register profilePage">
    <?php echo $this->Form->create('User', array('class' => 'user_change_password_form')); ?>
    <div class="inputDiv">
        	<div class="profilePic">
            	
            
            </div>
        </div>
    <div class="inputDiv">
        <?php echo $this->Form->input('old_password', array('type'=>'password','autocomplete'=>'off','placeholder' => 'Old Password','label'=>false)); ?>
    </div>
    <div class="inputDiv">
        <?php echo $this->Form->input('password', array('placeholder' => 'New Password','label'=>false)); ?>
    </div>
    <div class="inputDiv">
        <?php echo $this->Form->input('confirm_password', array('type'=>'password','placeholder' => 'Confirm Password','label'=>false)); ?>
    </div>
    <div class="inputDiv">
            <input type="submit" value="Update">
    </div>
    <div class="inputDiv">
                    <a class="registerBtn" href="<?php echo Router::url(array('controller' => 'users', 'action' => 'my_profile')); ?>">Cancel</a>
    </div>
    <?php echo $this->Form->end(); ?>
</div>


<?php echo $this->Common->loadJsClass('Login'); ?>
