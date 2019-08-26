<?php $this->request->data = $user; ?>

<div class="mainLinks loginFrom register profilePage">

    <?php echo $this->Form->create('User', array('class' => 'user_edit_profile_form')); ?>
    <div class="inputDiv">
        <div class="profilePic">
            <a href="javascript:;"> <img src="<?php echo $this->Common->getUserImage($user['User']['image'], 120, 120, 1, 'front'); ?>">                
            </a>

        </div> 
    </div>
    <div class="inputDiv">
        <?php echo $this->Form->input('name', array('disabled' => true, 'label' => false)); ?>
    </div>
    <div class="inputDiv">
        <?php echo $this->Form->input('email', array('type' => 'text', 'disabled' => true, 'label' => false)); ?>
    </div>
	
	<div class="inputDiv">
        <?php echo $this->Form->input('phone', array('disabled' => true, 'type' => 'text', 'label' => false, 'placeholder' => 'PHONE')); ?>
    </div>
    <div class="inputDiv">
        <div class="container-fluid">
            <div class="row">
                <?php
                //echo $this->Form->input('dob', array('disabled' => true, 'type' => 'text', 'label' => false, 'div' => array('class' => 'col-xs-3'), 'placeholder' => 'DOB'));
                //echo $this->Form->input('phone', array('disabled' => true, 'type' => 'text', 'label' => false, 'div' => array('class' => 'col-xs-9'), 'placeholder' => 'PHONE'));
                ?>
            </div>
        </div>
    </div>

    <?php echo $this->Form->end(); ?>


</div>
<!------------------------reservations List---------------start---------------->
<div class="reslistBlock down" id="user_reserv_blk">

    <div class="headingBlock" style="font-size:5vh;">Upcoming reservations </div>  
<div class="content" id="content-1">   
    <div id="js-appointment">
        <?php
        if (!empty($Appointment)) {

            $i = 1;
            foreach ($Appointment as $appointment) {
                ?>
                 <div class="item"> 

                    <span class="count"><?php echo $i; ?></span>
                    <span class="textBlock">
                       
                        <?php echo  $appointment['Barber']['name'];  ?>
                        
                        <h6> @ <?php echo $appointment['Slot']['time']; ?></h6> 
                            <h6> @ <?php echo date("m/d/Y (D)", strtotime($appointment['Appointment']['date'])); ?></h6>
                        <span>
						
							<a id="<?php echo $appointment['Appointment']['id']; ?>" rel="<?php echo date("m/d/Y",strtotime($appointment['Appointment']['date'])).' '.date("H:i",strtotime($appointment['Slot']['time'])); ?>" class="cancelBtm cancel_app_user" href="">cancel </a></span>
                        <a href="tel:<?php echo $appointment['Barber']['phone']; ?>"  >   <h6 class="phonenumber"><?php echo $appointment['Barber']['phone']; ?></h6>	</a>						 
                    </span>

                </div>
                <?php
                $i++;
            }
        } else {
            ?>
            <div class="item"> <h4 class='text-center no-data'>No reservations.</h4></div>    
            <?php } ?>
    </div>     
</div>
</div>

<!------------------------reservations List---------------End---------------->

<?php echo $this->Common->loadJsClass('Profile'); ?>

<!-- custom scrollbar stylesheet -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/jquery.mCustomScrollbar.css">
<!-- Google CDN jQuery with fallback to local -->
<script src="<?php echo SITE_URL; ?>js/jquery.mCustomScrollbar.concat.min.js"></script>
<script>
(function($){
	$(window).on("load",function(){
		$("#content-1").mCustomScrollbar({
			//theme:"minimal"
		});			
	});
})(jQuery);
</script>