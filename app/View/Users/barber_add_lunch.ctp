<?php if(!empty($slots)){
	echo $this->Form->create('LunchBreak', array('url'=>array('controller'=>'users','action'=>'users_barber_add_lunch'),'class' => 'form-horizontal book-slot-form custom', 'inputDefaults' => array('label' => false, 'div' => false, 'hiddenField' => false),'novalidate'=>'novalidate')); ?>
 <?php echo $this->Form->hidden('schedule_id', array('value'=>$this->params['pass'][0])); ?>
<div class="modal-body">
    <div class="row">
         <div class="col-md-12 text-center">
             <div data-toggle="buttons" class="btn-group">
                 <?php $i=1; foreach ($slots as $slot) { ?>
                 <label class="btn btn-success dark slt_lbl <?php echo (in_array($slot['Slot']['id'], $lunch_breaks))?'active':''; ?>" >
                  <input <?php echo (in_array($slot['Slot']['id'], $lunch_breaks))?'checked="checked"':''; ?> type="checkbox" name="data[LunchBreak][slot_id][]" value="<?php echo $slot['Slot']['id']; ?>" class="toggle"> <?php echo $slot['Slot']['time']; ?>
                 </label>                      
                 <?php 
                 $i++;} ?>
                                   
             </div>  
        </div>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-success lunch-submit" type="submit"><i class="fa fa-check"></i> Submit</button>
</div>
<?php echo $this->Form->end();
}else{ ?>
<div class="no-datahere" id="slot-box-content">

        <div class="confirm-img"><img src="<?php echo SITE_URL; ?>images/conformation.png" /></div>
        <div class="font18 no-dataheretxt">
            <div class="font24 ffamilybold nodatahead">THAT'S ALL FOLKS!</div>
            No spots <br>
            available for this day.
        </div>

    </div>
<?php } ?>