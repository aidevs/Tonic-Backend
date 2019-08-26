<div class="custom-calarea">
    <div style="background-color:#fff" class="customtabnew clearfix">
        <div class="custominnernew">
            
            <a href="<?php echo SITE_URL; ?>users/barberschedule" class="btn btn-success">Schedule</a>
            <a href="<?php echo SITE_URL; ?>users/barbervacation" class="btn btn-default">Vacations</a>
        </div>
    </div>
    <div class="calenderPage mt60">
        <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'my_account')); ?>" class="homeBtn"></a><br>
        <!------------------------code---------------start---------------->
        <div class="row" style="margin-top: 25px;">	
            <div id="schedule_list" class="col-lg-12 custom-schedule-list clearfix">
                <?php
                $j = 0;
                if (!empty($schedules)) {
                    foreach ($schedules as $schedule) {
                        ?>
                        <form id="schedule_frm<?php echo $schedule['Schedule']['id'] ?>">
                            <input type="hidden" class="schedule-user-id" name="data[Schedule][user_id]" value="<?php echo $this->Session->read('Auth.User.id'); ?>" />
                            <input type="hidden" class="schedule-week-id" name="data[Schedule][week_id]" value="<?php echo $schedule['Week']['id']; ?>" />
                            <div class="barbarschedulecustombox">
                                <div class="heading newcustomheader text-center <?php if ($schedule['Schedule']['working'] == 0) {
                    echo "customheaderdisable";
                } ?>"> <?php echo $schedule['Week']['name']; ?> </div>

        <?php if (isset($schedule['Schedule']['id']) && $schedule['Schedule']['id'] != '') { ?>
                                    <div class="container selectdatecustom">
                                        <input type="hidden" class="schedule-id" name="data[Schedule][id]" value="<?php echo $schedule['Schedule']['id']; ?>" />
                                        <div class="col-xs-6 text-center">
                                            <div class="newcustomdate">Start Time</div>
                                            <input type="text" name="data[Schedule][start_time]" class="timepicker text-center border3" value="<?php echo $schedule['Schedule']['start_time'] ?>" placeholder="Start Time" rel="<?php echo $schedule['Schedule']['id'] ?>" />
                                        </div>

                                        <div class="col-xs-6 text-center">
                                            <div class="newcustomdate">End Time</div>
                                            <input type="text" data-timepicki-tim="<?php echo date("h", strtotime($schedule['Schedule']['end_time'])); ?>" data-timepicki-mini="<?php echo date("i", strtotime($schedule['Schedule']['end_time'])); ?>" data-timepicki-meri="<?php echo date("A", strtotime($schedule['Schedule']['end_time'])); ?>" class="timepicker text-center border3" name="data[Schedule][end_time]" value="<?php echo $schedule['Schedule']['end_time'] ?>" placeholder="Start Time" rel="<?php echo $schedule['Schedule']['id'] ?>" />
                                        </div>

                                        <div class="text-center" style="display: none;">
                                            <div class="newcustomdate">Slot Time</div>
            <?php $slot_options = array($this->Session->read('Auth.User.service_time') => $this->Session->read('Auth.User.service_time')." Minute"); ?>
            <?php echo $this->Form->input("Schedule.slot_time", array('options' => $slot_options, 'default' => $schedule['Schedule']['slot_time'], 'label' => false, 'div' => false, 'rel' => $schedule['Schedule']['id'], 'class' => 'sch_save border3 text-center')) ?>

                                        </div>
                                    </div>

                                    <div class="clearfix mt10 luncharea">

            <a  data-toggle="modal" class="btn btn-success custom-btn-success lunch-btn lunchbreak pull-left" rel="<?php echo $schedule['Schedule']['id']; ?>" id="<?php echo $schedule['Week']['name']; ?>" href=""><!--<i class="fa fa-spoon "></i> -->LUNCH BREAKS </a>


                                        <div class="pull-right">

                                            <span class="working_label_off">OFF</span>
                                            <label class="switch">
            <?php echo $this->Form->checkbox('Schedule.working', array('label' => false, 'checked' => ($schedule['Schedule']['working'] == 1) ? true : false, 'rel' => $schedule['Schedule']['id'], 'class' => 'sch_save')); ?>
                                                <div class="slider round"></div>
                                            </label>
                                            <span class="working_label_on">ON</span>
                                        </div>
                                    <?php //echo $this->Form->input('working',array('label'=>false,'div' => false, 'checked'=>($schedule['Schedule']['working']==1)?true:false,'data-on-color'=>'success','data-off-color'=>'default','data-size'=>'mini','data-style'=>'ios','class'=>'make-switch','data-on-text'=>'&nbsp;&nbsp;&nbsp;&nbsp;','data-off-text'=>'&nbsp;&nbsp;&nbsp;','data-week'=>$schedule['Week']['id'],'data-schedule'=>$schedule['Schedule']['id'],'data-user'=>$this->Session->read('Auth.User.id')));  ?>

                                    </div>
                        <?php } ?>	
                            </div>
                        </form>
    <?php } ?>
<?php } ?>
            </div>
        </div>
        <div class="reslistBlock down lunch-body" id="reserv_blk" style="display:none">
            <div class="headingBlock" style="font-size:5vh;color:#000">
                <span id="headingBlock_text"></span>
                <span id="close_lunch_blk" style="float:right;cursor:pointer;margin-left: 5px;"><img src="<?php echo SITE_URL; ?>images/cancelBtn.png" style="width:20px;" /></span>
                <i class="fa fa-arrow-circle-up book_arrow_up_down" aria-hidden="true" id="arrow_up"></i><i class="fa fa-arrow-circle-down book_arrow_up_down" aria-hidden="true"  id="arrow_down" style="display:none;"></i>				
            </div>
            <div class="content" id="content-1">   
                <div id="js-appointment">
                </div> 
            </div>
        </div>
    </div>
</div>
<?php //echo $this->Common->loadJsClass('Calendar'); ?>
<!-- custom scrollbar stylesheet -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/jquery.mCustomScrollbar.css">
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/ripples.min.css">
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/bootstrap-material-datetimepicker.css">
<!-- Google CDN jQuery with fallback to local -->
<script src="<?php echo SITE_URL; ?>js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="<?php echo SITE_URL; ?>js/ripples.min.js"></script>
<script src="<?php echo SITE_URL; ?>js/material.min.js"></script>
<script src="<?php echo SITE_URL; ?>js/moment-with-locales.min.js"></script>
<script src="<?php echo SITE_URL; ?>js/bootstrap-material-datetimepicker.js"></script>

<script>
(function ($) {
    $('.timepicker').bootstrapMaterialDatePicker({ date: false,format : 'hh:mm A' }).on('change', function(e, date){
     save_sch_frm($(this).attr('rel'));
    });
       

        $("#close_lunch_blk").click(function () {
            $("#reserv_blk").hide();
        });

        $(".lunch-btn").click(function () {
            var schedule_id = $(this).attr("rel");
            var day_name = $(this).attr("id");
            $(".headingBlock #headingBlock_text").html(day_name);
            $.ajax({
                url: SITE_URL + 'ajax/users_barber_add_lunch/' + schedule_id,
                type: 'POST',
                beforeSend: function () {
                },
                success: function (data) {
                    $("#js-appointment").html(data);
                }
            });
            $("#reserv_blk").show();
        });

        $(".sch_save").change(function () {

            var sch_id = $(this).attr("rel");
            $.ajax({
                url: SITE_URL + 'ajax/user_barber_change_schedule',
                type: 'POST',
                data: $("#schedule_frm" + sch_id).serializeArray(),
                beforeSend: function () {
                },
                success: function (data) {
                    var res = JSON.parse(data);
                    if (res.error == 1) {
                        toastr['error'](res.msg);
                    } else {
                        window.location.reload();
                    }
                }
            })
        });

        $(window).on("load", function () {
            $("#content-1").mCustomScrollbar({
                //theme:"minimal"
            });
            $("#barber_list").mCustomScrollbar({
                //theme:"minimal"
            });
        });
        $("#reserv_blk").hide();
    })(jQuery);
    function save_sch_frm(sch_id) {
        $.ajax({
            url: SITE_URL + 'ajax/user_barber_change_schedule',
            type: 'POST',
            data: $("#schedule_frm" + sch_id).serializeArray(),
            beforeSend: function () {
            },
            success: function (data) {
                var res = JSON.parse(data);
                if (res.error == 1) {
                    toastr['error'](res.msg);
                } else {
                    window.location.reload();
                }
            }
        })
    }
</script>