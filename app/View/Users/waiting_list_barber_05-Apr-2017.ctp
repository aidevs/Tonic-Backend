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
        if (!empty($watinglists)) {
            $i = 1;
            foreach ($watinglists as $appointment) {
//				pr($appointment);die;
                ?>
                <li> 
                    <a href="javascript:;" data-toggle="modal" data-target="#barber-modal-<?php echo $i; ?>">
                        <span class="count"><?php echo $i; ?></span>
                        <span class="textBlock">
                            <?php echo $appointment['WalkinAppointments']['name']; ?>
                            <h6> @ <?php echo $appointment['WalkinAppointments']['created']; ?></h6>	
							 
                        </span>
                        <a  class="barberSeatBtn" onclick="return confirm('Are you sure you want to Seat #<?php echo $appointment['WalkinAppointments']['name']; ?>');" href="<?php echo Router::url(array('controller' => 'customers', 'action' => 'seat_walkin', $appointment['WalkinAppointments']['id'])); ?>">Seat </a>
                        
                        <a  class="cancelBtm" title="Dismiss" onclick="return confirm('Are you sure you want to dismiss #<?php echo $appointment['WalkinAppointments']['name']; ?>');" href="<?php echo Router::url(array('controller' => 'customers', 'action' => 'pending_walkin_dismiss', $appointment['WalkinAppointments']['id'])); ?>">Dismiss </a>
                    </a>
                </li>
                
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