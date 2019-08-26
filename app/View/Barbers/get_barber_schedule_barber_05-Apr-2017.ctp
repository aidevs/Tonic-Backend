<?php
if (!empty($appointments)) {
    $i = 1;
    foreach ($appointments as $Appointments) {
        ?>

        <div class="item"> 
            <span class="count"><?php echo $i; ?></span>
            <span class="textBlock">
                <?php echo $Appointments['User']['name']; ?>
                <h6> @ <?php echo $Appointments['Slot']['time']; ?> </h6>
                <h6> @ <?php echo $Appointments['Appointment']['date']; ?> </h6>
                <span>
                    <a  class="cancelBtm" onclick="return confirm('Do you really want to cancel the Appointment?');" href="<?php echo Router::url(array('controller' => 'barbers', 'action' => 'cancel_appointment_barber', $Appointments['Appointment']['id'])); ?>">cancel </a></span>	
                <a href="tel:<?php echo $Appointments['User']['phone']; ?>" ><h6 class="phonenumber"><?php echo $Appointments['User']['phone']; ?> </h6></a>
            </span>
            <span class="selectHand"> <img src="<?php echo SITE_URL; ?>images/back-icon-black.png"> </span>

        </div>

        <?php
        $i++;
    }
} else {
    ?>
    <div class="item"> <h4 class='text-center no-data'>No booked slot.</h4></div>    
    <?php
}
if (!empty($available_slots)) {
    ?>
    <div class="mainLinks" id="slot-box-content" style="display: none;">
        <form class="book-slot-form">   
            <?php echo $this->Form->input('slot_id', array('label' => false, 'options' => $available_slots, 'name' => 'slot_id', 'class' => 'form-control', 'empty' => 'Select Time')); ?>    

            <input type="text" name="name"  class ="form-control" style="margin-top: 10px;"  placeholder="Name"/>
            
            <input type="text" name="phone"  class ="form-control" style="margin-top: 10px;"   placeholder="Phone Number"/>
            
             <input type="text" name="email"  class ="form-control" style="margin-top: 10px;"   placeholder="Email Id"/>

            <a href="javascript:;" class="greenBtn submit-slot-btn">book slot</a>
        </form>
        <br>
    </div>
<?php } else { ?>
    <div class="mainLinks" id="slot-box-content" style="display: none;">
        <p class="no-slot">You haven't got a free time slot.</p>
    </div>
<?php } ?>
    
