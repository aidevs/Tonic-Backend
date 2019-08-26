<style>
    .modal-backdrop{opacity: 0;z-index: -1;}
    .tv-user{float: none;}
</style>
<div class="">

    <div class="calenderPage ">        
        <div class="reslistBlock tv-screen watingList">

            <div class="headingBlock">Waiting List <div class="pull-right" style="margin-top: 0;"><a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'my_account')); ?>" class="homeBtn"></a></div></div>  

     <ul>
        <?php
        if (!empty($reservation_walkins)) {
            $i = 1;
            foreach ($reservation_walkins as $appointment) {
				//pr($appointment);
                ?>
                <li> 
                    <a href="javascript:;" data-toggle="modal" data-target="#barber-modal-<?php echo $i; ?>">
                        <span class="count"><?php echo $i; ?></span>
                        <span class="textBlock">
                            <?php echo $appointment['name']; ?>
                            <h6> @ <?php echo $appointment['time']; ?></h6>	
							 
                        </span>
                        <span class="selectHand"> <img src="<?php echo SITE_URL; ?>images/back-icon-black.png"> </span>
                    </a>
                </li>
                <div id="barber-modal-<?php echo $i; ?>" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Barbers</h4>
                                    </div>
                                    <div class="modal-body">
                                        <?php 
                                        if (!empty($appointment['WalkinAppointmentBarber'])) {
                                            foreach ($appointment['WalkinAppointmentBarber'] as $walkinBarber) {
                                                ?>
                                                <div class="row">
                                                    <div class="col-xs-3">
                                                     <img class="border50" src="<?php echo $this->Common->getUserImage($walkinBarber['User']['image'], 100, 100, 1, 'front'); ?>" alt="">   
                                                    </div>
                                                    <div class="col-xs-9">
                                                      <?php echo $walkinBarber['User']['name']; ?>  
                                                    </div>
                                                    
                                                </div> 
                                            <?php }
                                        }else{?>
                                            <div class="row">
                                                    <div class="col-xs-3">
                                                     <img class="border50" src="<?php echo $this->Common->getUserImage($appointment['barber_image'], 100, 100, 1, 'front'); ?>" alt="">   
                                                    </div>
                                                    <div class="col-xs-9">
                                                      <?php echo $appointment['barber_name']; ?>  
                                                    </div>
                                                    
                                                </div> 
                                        <?php }
                                        ?>
                                        
                                    </div>                                    
                                </div>

                            </div>
                        </div>
                <?php
                $i++;
            }
        } else {
            ?>
            <li><h4 class='text-center no-data'>No reservations.</h4></li>    
<?php } ?>
    </ul>  
        </div>        
    </div>

</div>