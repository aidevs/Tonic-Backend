 
<div class="mainLinks loginFrom register">

    <?php echo $this->Form->create('User', array('class' => 'user_register_form')); ?>

        <?php
        echo $this->Form->input('name', array('type' => 'text', 'label' => false, 'div' => array('class' => 'inputDiv'), 'placeholder' => 'NAME'));
        echo $this->Form->input('email', array('type' => 'text', 'label' => false, 'div' => array('class' => 'inputDiv'), 'placeholder' => 'EMAIL'));
        echo $this->Form->input('password', array('type' => 'password', 'label' => false, 'div' => array('class' => 'inputDiv'), 'placeholder' => 'PASSWORD'));
		echo $this->Form->input('phone', array('type' => 'text', 'label' => false, 'div' => array('class' => 'inputDiv'), 'placeholder' => 'PHONE'));
        ?>

        <div class="inputDiv">
            <div class="container-fluid">
                <div class="row">
                    <?php
                    //echo $this->Form->input('dob', array('type' => 'text', 'label' => false, 'div' => array('class' => 'col-xs-4'), 'placeholder' => 'DOB'));
                    //echo $this->Form->input('phone', array('type' => 'text', 'label' => false, 'div' => array('class' => 'col-xs-8'), 'placeholder' => 'PHONE'));
                    ?>
                </div>
            </div>
        </div>
        <div class="inputDiv">
            <input type="submit" value="REGISTER">
        </div>
       <div class="inputDiv">
             <?php if (isset($this->request->params['slug'])) {  
          echo $this->Html->link('LOGIN', array('controller' => 'users', 'action' => 'login','slug'=>  isset($this->request->params['slug'])?$this->request->params['slug']:'' ),array('class'=>'registerBtn')) ; 
       }else{ 
            echo $this->Html->link('LOGIN', array('controller' => 'users', 'action' => 'login'),array('class'=>'registerBtn'))  ; 
        } ?>
       
       </div>
    <?php echo $this->Form->end(); ?> 


</div>
<?php echo $this->Common->loadJsClass('Profile'); ?>