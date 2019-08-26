<div class="modal-header">
    <button aria-hidden="true" data-dismiss="modal" class="close" type="button"></button>
    <h4 class="modal-title"><?php echo isset($walkins) && !empty($walkins) ? ucfirst($walkins[0]['WalkinAppointments']['name']) : "Pending Walk-In"; ?></h4>
</div>
<?php echo $this->Form->create('SeatWalkin', array('url'=>array('controller'=>'customers','action'=>'add_walkin'),'class' => 'form-horizontal', 'inputDefaults' => array('label' => false, 'div' => false, 'hiddenField' => false),'novalidate'=>'novalidate')); ?>

<input type="hidden" value="<?php echo $this->params['pass'][0]; ?>" name="walkin_id"/>
<div class="modal-body">
    <div class="row">
         <div class="col-md-12 text-center">
             <div class="form-group">
                <label class="col-md-3 control-label">Barber Name</label>
                <div class="col-md-5">
                    <input type="hidden" value="<?php echo isset($walkins) && !empty($walkins) ? $walkins[0]['WalkinAppointments']['name'] : "Pending Walk-In"; ?>" name="name"/>
                    <select name="barber_id" class="form-control">
                       <?php
                       foreach ($barbers as $key=>$barber) { ?>
                       <option value="<?php echo $key ?>"><?php echo $barber; ?></option>
                       <?php 
                       } ?>
                   </select>
                </div>
             </div>  
        </div>
    </div>
</div>
<div class="modal-footer">
    <button data-dismiss="modal" class="btn default" type="button"><i class="fa fa-close"></i> Close</button>
    <button class="btn blue <?php echo (count($walkins)==0)?'disabled':''; ?> lunch-submit" type="submit"><i class="fa fa-check"></i> Submit</button>
</div>
<?php echo $this->Form->end(); ?>