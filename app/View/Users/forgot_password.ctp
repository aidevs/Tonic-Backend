<div class="mainLinks loginFrom">
    <?php echo $this->Form->create('User',array('class'=>'user_forgot_password')); 
     echo $this->Form->input('email', array('type'=>'text','label' => false, 'div' => array('class' => 'inputDiv'), 'placeholder' => 'EMAIL'));
    ?>
    <div class="inputDiv">
        <input type="submit" value="SUBMIT">
    </div>
    <div class="inputDiv">
        
        <?php if (isset($this->request->params['slug'])) {  
        echo $this->Html->link('LOGIN', array('controller' => 'users', 'action' => 'login','slug'=>  isset($this->request->params['slug'])?$this->request->params['slug']:''),array('class'=>'registerBtn')); 
       }else{ 
         echo $this->Html->link('LOGIN', array('controller' => 'users', 'action' => 'login'),array('class'=>'registerBtn')) ; 
        } ?>
       
    </div>
    <?php echo $this->Form->end(); ?> 
</div>
<?php echo $this->Common->loadJsClass('Login'); ?>