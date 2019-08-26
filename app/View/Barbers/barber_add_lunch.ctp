<div class="modal-header">
    <button aria-hidden="true" data-dismiss="modal" class="close" type="button"></button>
    <h4 class="modal-title">Lunch Break</h4>
</div>
<?php echo $this->Form->create('LunchBreak', array('url'=>array('controller'=>'barbers','action'=>'add_lunch'),'class' => 'form-horizontal', 'inputDefaults' => array('label' => false, 'div' => false, 'hiddenField' => false),'novalidate'=>'novalidate')); ?>
 <?php echo $this->Form->hidden('schedule_id', array('value'=>$this->params['pass'][0])); ?>
<div class="modal-body">
    <div class="row">
         <div class="col-md-12 text-center">
             <div data-toggle="buttons" class="btn-group">
                 <?php $i=1; foreach ($slots as $slot) { ?>
                 <label class="btn btn-default margin-right-10 margin-bottom-10 <?php echo (in_array($slot['Slot']['id'], $lunch_breaks))?'active':''; ?>" style="<?php echo (count($slots) > 5)?'width: 18%;':''; ?>">
                  <input <?php echo (in_array($slot['Slot']['id'], $lunch_breaks))?'checked="checked"':''; ?> type="checkbox" name="data[LunchBreak][slot_id][]" value="<?php echo $slot['Slot']['id']; ?>" class="toggle"> <?php echo $slot['Slot']['time']; ?>
                 </label>                      
                 <?php 
                 if($i%5==0){
                     echo '<div class="clearfix"></div>';
                 }                 
                 $i++;} ?>
                                   
             </div>  
        </div>
    </div>
</div>
<div class="modal-footer">
    <button data-dismiss="modal" class="btn default" type="button"><i class="fa fa-close"></i> Close</button>
    <button class="btn blue <?php echo (count($lunch_breaks)==0)?'disabled':''; ?> lunch-submit" type="submit"><i class="fa fa-check"></i> Submit</button>
</div>
<?php echo $this->Form->end(); ?>