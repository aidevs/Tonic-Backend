<div class="mainLinks loginFrom">
    <?php echo $this->Form->create('User',array('class'=>'user_reset_password')); 
     echo $this->Form->input('password', array('type'=>'password','label' => false, 'div' => array('class' => 'inputDiv'), 'placeholder' => 'PASSWORD'));
     echo $this->Form->input('confirm_password', array('type'=>'password','label' => false, 'div' => array('class' => 'inputDiv'), 'placeholder' => 'CONFIRM PASSWORD'));
    ?>
    <div class="inputDiv">
        <input type="submit" value="Submit">
    </div>
    <?php echo $this->Form->end(); ?> 
    
</div>
<?php echo $this->Common->loadJsClass('Login'); ?>
