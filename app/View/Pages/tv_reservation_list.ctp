<?php
if ($reservations) {
	$r = 1;
	foreach ($reservations as $reservation) {
		?>
		<li class="<?php echo ($reservation['Appointment']['claim_status'] == 1) ? 'active' : ''; ?>"> 
			<a href="javascript:;" class="<?php echo ($r == 1) ? 'active1' : ''; ?>"> <span class="count"><?php echo $r; ?></span> 
				<span class="textBlock"> <?php echo $reservation['User']['name']; ?>
					<h6> @ <?php echo $reservation['Slot']['time']; ?></h6>
				</span> 
				<span class="selectHand"> <img src="<?php echo SITE_URL; ?>images/back-icon-black.png"> 
				</span>
				<span class="tv-user"><img src="<?php echo $this->Common->getUserImage($reservation['Barber']['image'], 100, 100, 1, 'front'); ?>"></span>
			</a> 
		</li>
		<?php
		$r++;
	}
} else {
	?>
	<li><h4 class='text-center no-data' style="color: #fff;padding: 50px;">No reservations.</h4></li>
<?php } ?>
