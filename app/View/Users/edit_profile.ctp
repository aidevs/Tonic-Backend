<div class="mainLinks loginFrom register profilePage">
    	
        <?php echo $this->Form->create('User', array('class' => 'user_register_form')); ?>
        <div class="inputDiv">
        	<div class="profilePic">
            	<a href="javascript:;">
                    <img src="<?php echo $this->Common->getUserImage($this->request->data['User']['image'],120,120,1,'front'); ?>">
                <i class="fa fa-edit"></i>
                <input id="edit_image" type="file">
                
                </a>
            
            </div>
        </div>
        <div class="inputDiv">
            <?php echo $this->Form->input('name', array('label' => false,'placeholder' => 'Name')); ?>
        </div>
        	<div class="inputDiv">
            <?php echo $this->Form->input('email', array('type'=>'text','disabled'=>true,'label' => false)); ?>
        	</div>
            <div class="inputDiv">
                <?php
                       // echo $this->Form->input('dob', array('type' => 'text', 'label' => false, 'div' => array('class' => 'col-xs-3'), 'placeholder' => 'DOB'));
                        echo $this->Form->input('phone', array('type' => 'text', 'label' => false, 'placeholder' => 'PHONE'));
                        ?>
            </div>
         <div class="inputDiv">
            <input type="submit" value="Save">
        </div>
       <div class="inputDiv">
                    <a class="registerBtn" href="<?php echo Router::url(array('controller' => 'users', 'action' => 'my_profile')); ?>">Cancel</a>
       </div>
            
      <?php echo $this->Form->end(); ?>
    
    
</div>
<?php echo $this->Common->loadJsClass('Profile'); ?>