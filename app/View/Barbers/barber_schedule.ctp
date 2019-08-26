<?php //echo date('H:i:s a');     ?>
<div class="custom-calarea">
    <input type="hidden" id="setDefaultDate" value="<?php echo $currentDate; ?>">
    <input type="hidden" id="setBarberId" value="<?php echo isset($barberID) ? $barberID: ""; ?>" />
	
    <input type="hidden" id="setScheduleId" value="<?php echo isset($barber_info['Schedule']['id']) ? $barber_info['Schedule']['id']: ""; ?>" />
    <div class="calenderPage">
        <!------------------------code---------------start---------------->
        <div class="row">	
            <div id="schedule_list" class="col-lg-12 custom-schedule-list clearfix">
				<?php
				$j = 0;
				if (!empty($schedules)) {
					foreach ($schedules as $schedule) {
						?>
						<input type="hidden" class="schedule-user-id" name="data[Schedule][user_id]" value="<?php echo $this->Session->read('Auth.User.id'); ?>" />
						<input type="hidden" class="schedule-week-id" name="data[Schedule][week_id]" value="<?php echo $schedule['Week']['id']; ?>" />
						<div>
							<div class="heading"><center><?php echo $schedule['Week']['name']; ?></center></div>
							
							<?php if(isset($schedule['Schedule']['id']) && $schedule['Schedule']['id']!=''){ ?>
								<div>
									<input type="hidden" class="schedule-id" name="data[Schedule][id]" value="<?php echo $schedule['Schedule']['id']; ?>" />
									<div>
										<label>Start Time</label>
										<input type="text" value="<?php echo $schedule['Schedule']['start_time']?>" placeholder="Start Time" />
									</div>
									
									<div>
										<label>End Time</label>
										<input type="text" value="<?php echo $schedule['Schedule']['end_time']?>" placeholder="Start Time" />
									</div>
									
									<div>
										<label>Slot Time</label>
										<input type="text" value="<?php echo $schedule['Schedule']['slot_time']?>" placeholder="Start Time" />
									</div>
								</div>
								
								<div>
									<a  data-toggle="modal" data-target="#lunch-ajax" class="btn default btn-xs yellow lunch-btn" href="<?php echo Router::url(array('controller'=>'ajax','action'=>'add_lunch',$schedule['Schedule']['id'])); ?>"><i class="fa fa-spoon "></i> <span class="hidden-480">Lunch Break</span> </a>
									
									
									<?php echo $this->Form->input('working',array('label'=>false,'div' => false, 'checked'=>($schedule['Schedule']['working']==1)?true:false,'data-on-color'=>'primary','data-off-color'=>'danger','data-size'=>'small','class'=>'make-switch','data-on-text'=>'&nbsp;Yes&nbsp;&nbsp;','data-off-text'=>'&nbsp;No&nbsp;','data-week'=>$schedule['Week']['id'],'data-schedule'=>$schedule['Schedule']['id'],'data-user'=>$this->Session->read('Auth.User.id'))); ?>
								</div>
							<?php }?>	
						</div>
					<?php } ?>
				<?php } ?>
			</div>
        </div>
        <!----Code------end----------------------------------->
        <!------------------------reservations List---------------start---------------->
			<div class="reslistBlock down" id="reserv_blk">
                <div class="headingBlock" style="font-size:5vh;"></div>
                <div class="content" id="content-1">   
                    <div id="js-appointment">
						
                    </div> 
                </div>
            </div>
        <!------------------------reservations List---------------End---------------->
    </div>
</div>


<div class="modal fade" id="slot_confirm" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="slot-boxLabel">
   <div class="modal-dialog custom" style="background:#fff">
         	<div class="modal-body">
					<div class="font26">Confirm</div>
					Reserving <span class="color-green"> <?php echo $barber_info['User']['name']; ?></span> on<br/>
					<span class="color-green"><?php echo date("F d",strtotime($currentDate)); ?></span> @ <span class="color-green" id="slt_tm"></span>
			</div> 
			<div class="modal-footer clearfix">
				<button class="btn btn-success custom" id="btn_slot_confirm">Confirm</button>
				<button data-dismiss="modal" class="btn btn-danger custom pull-right">Decline</button>
			</div>
    </div> 
</div>

<div class="modal fade" id="slot_booked_modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="slot-boxLabel">
   <div class="modal-dialog custom" style="background:#fff">
         	<div class="modal-body">
					<div class="confirm-img"><img src="<?php echo SITE_URL; ?>images/conformation.png" /></div>
					<div class="font18">
                    	<div class="font24">THAT'S ALL FOLKS!</div>
						MAKE SURE TO CHECK YOUR EMAIL FOR CONFIRMATION.
                    </div>
			</div> 
			<div class="modal-footer">
				<a href="<?php echo Router::url(array('controller'=>'users','action'=>'my_account')); ?>" class="custom1 btn btn-success custom">got it</a>
			</div>
    </div> 
</div>
<?php echo $this->Common->loadJsClass('Calendar'); ?>

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
		$("#barber_list").mCustomScrollbar({
			//theme:"minimal"
		});			
	});
	
})(jQuery);
</script>