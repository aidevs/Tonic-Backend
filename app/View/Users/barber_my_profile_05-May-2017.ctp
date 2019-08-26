<?php $this->request->data = $user; ?>
<div class="mainLinks loginFrom register profilePage">

    <?php echo $this->Form->create('User', array('class' => 'user_edit_profile_form')); ?>
    <div class="inputDiv">
        <div class="profilePic">
            <a href="javascript:;"> <img src="<?php echo $this->Common->getUserImage($user['User']['image'], 120, 120, 1, 'front'); ?>">
                <i class="fa fa-edit"></i>
                <input id="edit_image" type="file">

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
        <div class="workSheudle">
            <div class="colHalf">
                <?php
                $s = 1;
                foreach ($schedules as $schedule) {
                    if (isset($schedule['Schedule']['id']) == '' || $schedule['Schedule']['working'] == 0) {
                        echo substr($schedule['Week']['name'], 0, 3) . ' [OFF] <br>';
                    } else {
                        echo substr($schedule['Week']['name'], 0, 3) . ' [ ' . $schedule['Schedule']['start_time'] . ' - ' . $schedule['Schedule']['end_time'] . ' ] <br>';
                    }
                    if ($s == 4) {
                        echo '</div>';
                        echo '<div class="colHalf">';
                    }
                    $s++;
                }
                ?>
            </div>
            <div class="clearfix"></div>

        </div>
    </div>




    <?php echo $this->Form->end(); ?>


</div>
<!------------------------reservations List---------------start---------------->
<div class="reslistBlock down" id="barber_reserv_blk">
    	<div class="headingBlock">Upcoming reservations </div>
 		<div class="content" id="content-1">   
        <div id="js-appointment">  
        <?php
        if (!empty($Appointment)) {
//pr($Appointment);
            $i = 1;
            foreach ($Appointment as $appointment) {
            
                ?>
                <div class="item"> 

                    <span class="count"><?php echo $i; ?></span>
                    <span class="textBlock">
                        <?php  $this->Session->read('Auth.User.role_id') ;?>
                        <?php echo isset($appointment['User']['name'])?$appointment['User']['name']:'';  ?>
                        
                        
                        <h6> @ <?php echo $appointment['Slot']['time']; ?></h6> 
                        <h6> @ <?php echo date("m/d/Y(D)", strtotime($appointment['Appointment']['date'])); ?></h6>
                          
                        <span>
							<a class="barberSeatBtn" onclick="return confirm('Do you really want to seat the Appointment?');" href="<?php echo Router::url(array('controller' => 'users', 'action' => 'seat_appointment', $appointment['Appointment']['id'])); ?>">Seat </a>
                            <a  class="cancelBtm" onclick="return confirm('Do you really want to cancel the Appointment?');" href="<?php echo Router::url(array('controller' => 'barbers', 'action' => 'cancel_appointment', $appointment['Appointment']['id'])); ?>">cancel </a></span>
                        <a href="tel:<?php echo $appointment['User']['phone']; ?>"  >   <h6 class="phonenumber"><?php echo $appointment['User']['phone']; ?></h6>	</a>						 
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