<?php  //echo date('H:i:s a');      ?>
<div class="custom-calarea barber-calendar">
    
    <input type="hidden" id="setDefaultDate" value="<?php echo $currentDate; ?>">
    <input type="hidden" id="setBarberId" value="<?php echo isset($barberID) ? $barberID : ""; ?>" />

    <input type="hidden" id="setScheduleId" value="<?php echo isset($barber_info['Schedule']['id']) ? $barber_info['Schedule']['id'] : 0; ?>" />
    <input type="hidden" id="service-time" value="<?php echo isset($barber_info['User']['service_time']) ? $barber_info['User']['service_time'] : 0; ?>" />
    <input type="hidden" id="query" value="<?php echo isset($this->request->query['q']) ? $this->request->query['q'] : ""; ?>" />
    <div class="calenderPage">
        <!------------------------code---------------start---------------->
        <div class="row">
            <div id="calender_box" class="col-lg-12 nodatamargin">
                <div class="date-picker custom"></div>
            </div>
        </div>
        <!----Code------end----------------------------------->
        <!------------------------reservations List---------------start---------------->
        <div class="reslistBlock down" id="reserv_blk">
            <div class="headingBlock" style="font-size:4vh;color: #000;">Available Times
                <i class="fa fa-arrow-circle-up book_arrow_up_down" aria-hidden="true" id="arrow_up"></i><i class="fa fa-arrow-circle-down book_arrow_up_down" aria-hidden="true"  id="arrow_down" style="display:none;"></i>				
            </div>

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
            <span class="color-green"><?php echo date("F d", strtotime($currentDate)); ?></span> from <span class="color-green" id="slt_tm"></span> - <span class="color-green" id="slt_tm_to"></span> <br/>Total Cost <span class="color-green" id="slt_cost"></span>
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
            <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'my_account')); ?>" class="custom1 btn btn-success custom">got it</a>
        </div>
    </div> 
</div>
<?php echo $this->Common->loadJsClass('Barber'); ?>

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