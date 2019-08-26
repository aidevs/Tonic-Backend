<?php //echo date('H:i:s a');      ?>
<div class="custom-calarea">    
    <div class="calenderPage">
        <!------------------------code---------------start---------------->
        <div class="row">	
            <div  class="col-lg-12 custom-calendar-list clearfix">
                <form method="POST" class="service-form custom"> 
                <ul>
                    <input  name="total" id="total-price" type="hidden">
                    <input  name="time" id="total-time" type="hidden">
                    <input  name="date" id="date_av" value="<?php echo (isset($this->params['pass'][1])?$this->params['pass'][1]:"") ?>" type="hidden">
                    <?php
                    $j = 0;
                    if (!empty($services)) {
                        foreach ($services as $service) {
                            ?>
                    <li>
                                <img src="<?php echo $this->Common->getUserImage($service['User']['image'], 91, 90, 1, 'front'); ?>" alt="">
                                <div class="custom-calendar-name service-info-btn" data-info="<?php echo $service['Service']['description']; ?>"><?php echo $service['Service']['name']; ?> - (<?php echo $service['Service']['time']; ?> MIN)</div>
                                <div class="pull-right vcenter" style="height:13vw">
                                    <div data-toggle="buttons" class="btn-group custom">
                                                <label class="btn btn-black slt-service"  >
                                                    <input data-price="<?php echo $service['Service']['cost']; ?>" data-time="<?php echo $service['Service']['time']; ?>" name="service_id[]" id="service_id" value="<?php echo $service['Service']['id']; ?>" class="toggle"  type="checkbox"><span>SELECT</span>
                                                </label>
                                            </div>
                                </div>
                            </li>     
                            <?php
                            $j++;
                        }
                    } else {
                        ?> 
                         <li data-number="1" class="slide-1 nobarber-class" data-id="" data-schedule="" style="content:">
                          No service found for this barber. Please choose another barber.
                         </li>  
                        <?php
                    }
                    ?>            
                </ul>
                    <?php  if (!empty($services)) { ?>
                    <div class="service-total">
                    <ul>
                        <li>
                           
                            <div class="custom-calendar-name">TOTAL COST</div>
                            <div class="custom-calendar-name pull-right service-total-amt">$0</div>
                            
                        </li>   
                    </ul>
                    </div>
                   <a href="javascript:;" class="greenBtn submit-service-btn book_it_btn custom">Next</a>
                    <?php } ?>
                </form>
            </div>
           
        </div>
        <!----Code------end----------------------------------->
       
    </div>
</div>

<?php echo $this->Common->loadJsClass('Service'); ?>

<!-- custom scrollbar stylesheet -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/jquery.mCustomScrollbar.css">
<!-- Google CDN jQuery with fallback to local -->
<script src="<?php echo SITE_URL; ?>js/jquery.mCustomScrollbar.concat.min.js"></script>
<script>
    (function ($) {
        $(window).on("load", function () {
            $("#content-1").mCustomScrollbar({
                //theme:"minimal"
            });
            $("#barber_list").mCustomScrollbar({
                //theme:"minimal"
            });
        });

    })(jQuery);
</script>