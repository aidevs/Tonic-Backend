<?php
                            if (!empty($walkins)) {
                                $w = 1;
                                foreach ($walkins as $walkin) {
                                    ?>
                                    <li class="<?php //echo ($w == 1) ? 'active' : ''; ?>"> 
                                        <a href="javascript:;" class="<?php //echo ($w == 1) ? 'active' : ''; ?>"> <span class="count"><?php echo $w; ?></span> 
                                            <span class="textBlock"> <?php echo $walkin['WalkinAppointment']['name']; ?>
                                                <!--<h6> @ <?php echo date('h:i A', strtotime($walkin['WalkinAppointment']['created'])); ?></h6> -->
                                            </span> 
                                            <span class="selectHand"> <img src="<?php echo SITE_URL; ?>images/back-icon-black.png"> 
                                            </span> 
                                            <?php
                                            if (!empty($walkin['WalkinAppointmentBarber'])) {
                                                foreach ($walkin['WalkinAppointmentBarber'] as $walkinBarber) {
                                                    ?>
                                                    <span class="tv-user"><img src="<?php echo $this->Common->getUserImage($walkinBarber['User']['image'], 100, 100, 1, 'front'); ?>" alt=""></span> 
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <span class="tv-user"><img src="<?php echo SITE_URL; ?>images/questionmark.png"></span> 

                                            <?php } ?>
                                        </a> 
                                    </li>
                                    <?php
                                    $w++;
                                }
                            } else {
                                ?>
                                <li><h4 class='text-center no-data' style="color: #fff;padding: 50px;">No walkins.</h4></li>
                            <?php } ?>
