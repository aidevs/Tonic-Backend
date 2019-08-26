<div class="mainLinks loginFrom">

    <?php echo $this->Form->create('User', array('class' => 'user_login_form')); ?>

    <?php
    echo $this->Form->input('email', array('type' => 'text', 'label' => false, 'div' => array('class' => 'inputDiv'), 'placeholder' => 'EMAIL'));
    echo $this->Form->input('password', array('type' => 'password', 'label' => false, 'div' => array('class' => 'inputDiv'), 'placeholder' => 'PASSWORD'));
    ?>
    <div class="inputDiv" style="position: relative;">
        <?php echo $this->Form->input('remmber_me', array('hiddenField' => false, 'type' => 'checkbox', 'label' => false, 'div' => false)); ?>
        <label class="remember_me">Remember me</label>
    </div> 
    <div class="inputDiv">
        <input type="submit" value="LOGIN">
    </div>
    <?php echo $this->Form->end(); ?> 






    <div class="inputDiv">
        <?php if (isset($this->request->params['slug'])) {  
            echo $this->Html->link('REGISTER', array('controller' => 'users', 'action' => 'register','slug'=>$this->request->params['slug'] ), array('class' => 'registerBtn')) ; 
       }else{ 
              echo $this->Html->link('REGISTER', array('controller' => 'users', 'action' => 'register' ), array('class' => 'registerBtn')) ; 
        } ?>
    </div>

    <div class="whoAm">  
      <?php if (isset($this->request->params['slug'])) {  
           echo $this->Html->link('Forgot Password ?', array('controller' => 'users', 'action' => 'forgot_password', 'slug' => $this->request->params['slug']),array('class' => 'registerBtn'))  ; 
       }else{ 
             echo $this->Html->link('Forgot Password ?', array('controller' => 'users', 'action' => 'forgot_password'),array('class' => 'registerBtn')) ; 
        } ?>
    </div>



</div>

<?php echo $this->Common->loadJsClass('Login'); ?>


