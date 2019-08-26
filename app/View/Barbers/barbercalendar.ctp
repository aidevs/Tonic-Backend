<?php //echo date('H:i:s a');     ?>
<div class="mainLinks calenderMain">
    <input type="hidden" id="setDefaultDate" value="<?php echo $currentDate; ?>">
    <input type="hidden" id="barberId" value="<?php echo $this->Session->read('Auth.User.id'); ?>">        
    <input type="hidden" id="scheduleId" value="<?php echo isset($barberUsers['Schedule']['id']) ? $barberUsers['Schedule']['id'] : ''; ?>">


    <div class="calenderPage">
        <!------------------------code---------------start---------------->
        <div class="row">	
            <div class="col-lg-12">
                <div class="date-picker"></div>
            </div>
        </div>
        <!----Code------end----------------------------------->
        <!------------------------reservations List---------------start---------------->
        <?php if (!empty($barberUsers)) { ?> 
            <div class="reslistBlock down">
                <div class="headingBlock">My booked slot<a href="javascript:;" id="book-slot-btn">+</a> <i class="fa fa-arrow-circle-up arrow_up_down" aria-hidden="true" id="arrow_up"></i><i class="fa fa-arrow-circle-down arrow_up_down" aria-hidden="true"  id="arrow_down" style="display:none;"></i></div>
                <div class="content" id="content-1">    
                    <div id="js-appointment">

                    </div> 
                </div>
            </div>
        <?php } ?>
        <!------------------------reservations List---------------End---------------->
    </div>
</div>
<?php echo $this->Common->loadJsClass('Barber'); ?>
 
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/jquery.mCustomScrollbar.css"> 
<script src="<?php echo SITE_URL; ?>js/jquery.mCustomScrollbar.concat.min.js"></script>

 <script>
(function($){
	$(document).bind('ready ajaxComplete', function(){
            
		$("#content-1").mCustomScrollbar({
//			theme:"minimal"
		});			
	});
})(jQuery);
</script>