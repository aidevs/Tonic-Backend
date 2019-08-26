<?php //echo date('H:i:s a');     ?>
<div class="mainLinks calenderMain">
    <input type="hidden" id="setDefaultDate" value="<?php echo $currentDate; ?>">
    <div class="calenderPage">
        <!------------------------code---------------start---------------->
        <div class="row">	
            <div id="barber_list" class="col-lg-12">
				<ul>
					<?php
					$j = 0;
					if (!empty($barbers)) {
						foreach ($barbers as $barber) {
							?>
							<li data-number="<?php echo $j; ?>" class="slide-<?php echo $j; ?>" data-id="<?php echo $barber['User']['id']; ?>" data-schedule="<?php echo $barber['Schedule']['id']; ?>">
								<img src="<?php echo $this->Common->getUserImage($barber['User']['image'], 91, 90, 1, 'front'); ?>" alt="">
								<?php echo $barber['User']['name'] ?>
								<button id="<?php echo $barber['User']['id']; ?>" rel="<?php echo $barber['Schedule']['id']; ?>" class="btn btn-success book_apt">BOOK</button>
							</li>     
							<?php
							$j++;
						}
					} else {
						?> 
						<li data-number="1" class="slide-1 nobarber-class" data-id="" data-schedule="" style="content:">

						</li>  
						<?php
					}
					?>            
				</ul>
			</div>
        </div>
    </div>
</div>

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