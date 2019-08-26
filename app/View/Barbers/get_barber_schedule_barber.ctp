<?php
if (!empty($available_slots)) {
    ?>

    <form class="book-slot-form custom"> 
        <input type="hidden" name="time" value="<?php echo $data['t'] ?>">
        <input type="hidden" name="price" value="<?php echo $data['p'] ?>">
        <input type="hidden" name="service" value="<?php echo $data['id'] ?>">
        <input type="hidden" name="slot_ids" value="">
        <input type="hidden" name="to_slot" value="">
        <div data-toggle="buttons" class="btn-group custom slot-time">
            <?php 
            $isSlot=0;
            foreach ($available_slots as $key => $slot): ?>
                <?php 
                 
                if (!in_array($key, $booked_appointment)): ?>
                    <?php /* <label class="btn btn-success dark disable slt_lbl" disabled>
                      <input name="slot_id" id="slot_id" value="<?php echo $key; ?>" class="toggle" rel="<?php echo $slot; ?>" type="radio"> <?php echo $slot; ?>
                      </label>
                      <?php else: ?> */ $isSlot++;  ?>
                    <label class="btn btn-success dark slt_lbl"  >
                        <input name="slot_id" id="slot_id" value="<?php echo $key; ?>" class="toggle" rel="<?php echo $slot; ?>" type="radio"> <?php echo $slot; ?>
                    </label>
                <?php endif; ?>
            <?php endforeach; ?>						
        </div>
        <?php /* echo $this->Form->input('slot_id', array('label' => false, 'options' => $available_slots, 'name' => 'slot_id', 'class' => 'form-control', 'empty' => 'Select Time')); */ 
        $barberClass=(($this->Session->read('Auth.User.role_id')==3)?'barber_book_it':'');
        ?>    
        <a href="javascript:;" class="greenBtn submit-slot-btn book_it_btn custom" style="display: none;">Book It</a>
    </form>
    <br>
    <div class="mainLinks" id="slot-box-content" style="display: none;">
        <form class="book-slot-form-user">
             <input type="text" name="name"  class ="form-control" style="margin-top: 10px;"  placeholder="Name"/>
            
             <input type="text" name="phone"  class ="form-control" style="margin-top: 10px;"   placeholder="Phone Number"/>
            
             <input type="text" name="email"  class ="form-control" style="margin-top: 10px;"   placeholder="Email Id"/>

            <a href="javascript:;" class="greenBtn book-slot-btn">book slot</a>
        </form>
        <br>
    </div>
 <?php if($isSlot==0){?>
   <div class="no-datahere" id="slot-box-content">

        <div class="confirm-img"><img src="<?php echo SITE_URL; ?>images/conformation.png" /></div>
        <div class="font18 no-dataheretxt">
            <div class="font24 ffamilybold nodatahead">THAT'S ALL FOLKS!</div>
            This barber has no spots <br>
            available today
        </div>

    </div>  
 <?php } ?>
<?php } else { ?>
    <div class="no-datahere" id="slot-box-content">

        <div class="confirm-img"><img src="<?php echo SITE_URL; ?>images/conformation.png" /></div>
        <div class="font18 no-dataheretxt">
            <div class="font24 ffamilybold nodatahead">THAT'S ALL FOLKS!</div>
            This barber has no spots <br>
            available today
        </div>

    </div>
<?php } ?>

    
    
