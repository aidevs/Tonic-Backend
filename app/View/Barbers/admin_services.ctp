<div class="modal-header">
    <button aria-hidden="true" data-dismiss="modal" class="close" type="button"></button>
    <h4 class="modal-title">Services</h4>
</div>
<div class="modal-body">
    <div class="js-error"></div>
    <?php echo $this->Form->create('BarberService',['class'=>'barber-service-form']); ?>
    <div class="form-group">
        <?php echo $this->Form->input('service_id', array('label'=>false,'div'=>false,'multiple'=>true,'data-placeholder'=>'Select Service','class' => 'form-control select2me')); ?>
    </div>
    <div class="form-group">
        <button class="btn blue update-service-btn" type="button"><i class="fa fa-check"></i> Submit</button>
    </div>
    <?php echo $this->Form->end(); ?>
</div>