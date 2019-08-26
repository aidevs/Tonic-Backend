 <div class="global_wrap"> 
 <div class="signin-bg">
<div class="modal fade" id="forgotModal" tabindex="-1" role="dialog" aria-labelledby="forgotModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div class="popuplogo"><img src="<?php echo SITE_URL;?>images/signin-logo.png"></div>
		<?php echo $this->Common->sessionNoty();?>
      </div>
      <div class="modal-body">
        <?php echo $this->Form->create('User',array('url'=>array('controller'=>'users','action'=>'forgot'),'novalidate'=>'novalidate','id'=>'UserForgot')); ?>
          <div class="form-group">
            <?php echo $this->Form->input('email',array('class'=>'form-control','placeholder'=>'Enter Email Address','label'=>false));?>
          </div> 
          <div class="form-group">
          <?php echo $this->Form->button('Forgot Password',array('type'=>'submit','class'=>'btn btn-primary submit','div'=>false,'escape'=>false)); ?>
          <!--span class="forgotpas"><a href="javascript:void(0)" data-toggle="modal" data-target="#exampleModal">Login?</a></span-->
          </div>
          
        <?php echo $this->Form->end(); ?>
      </div>
      <div class="modal-footer">
        <span class="dontacount">If you have an account?</span>
        <span class="creat" onclick="hideModals('exampleModal');">Login!</span>
		 <!--button type="button" class="btn btn-primary" data-toggle="modal" data-target="#registerModal">signup</button-->
      </div>
    </div>
  </div>
</div>
</div>
</div>  

