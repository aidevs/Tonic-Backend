<?php
if (!empty($appointmentsVew)) {

    $i = 1;
    foreach ($appointmentsVew as $appointment) {
        ?>
        <li> 

            <span class="count"><?php echo $i; ?></span>
            <span class="textBlock">
              <?php echo ($this->Session->read('Auth.User.role_id')==3)?$appointment['User']['name']:$appointment['Barber']['name'];  ?>
                <h6> @ <?php echo $appointment['Slot']['time']; ?> </h6>
                <span>
                    <a  class="cancelBtm" onclick="return confirm('Do you really want to cancel the Appointment?');" href="<?php echo Router::url(array('controller' => 'barbers', 'action' => 'cancel_appointment', $appointment['Appointment']['id'])); ?>">cancel </a></span>	
                <a href="tel:<?php echo $appointment['Barber']['phone']; ?>" ><h6 class="phonenumber"><?php echo $appointment['Barber']['phone']; ?> </h6></a>
            </span>
            <span class="selectHand"> <img src="<?php echo SITE_URL; ?>images/back-icon-black.png"> </span>

        </li>
        <?php
        $i++;
    }
} else {
    ?>
    <li><h4 class='text-center no-data'>No reservations.</h4></li>    
    <?php
}
if (!empty($available_slots)) {
    ?>
    <div class="mainLinks" id="slot-box-content" style="display: none;">
        <form class="book-slot-form">   
            <?php echo $this->Form->input('slot_id', array('label' => false, 'options' => $available_slots, 'name' => 'slot_id', 'class' => 'form-control', 'empty' => 'Select Time')); ?>    
            <a href="javascript:;" class="greenBtn submit-slot-btn">MAKE RESERVATION</a>
        </form>
        <br>
    </div>
<?php } else { ?>
    <div class="mainLinks" id="slot-box-content" style="display: none;">
        <p class="no-slot">No time slot available for this barber. Please choose another barber.</p>
    </div>
<?php } ?>