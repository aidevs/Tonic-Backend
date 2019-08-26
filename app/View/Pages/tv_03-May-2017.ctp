 
<?php if (!$this->request->is('ajax')) { ?>
    <div class="tv-page-inner clearfix">
     <div class="ajax-tv col-md-12">   
    <?php } ?>   
    
        <div class="col-md-6" style="padding: 30px 16px 0 0;" >
            <div class="reslistBlock tv-screen new">
                <div class="headingBlock text-center" >Walk-ins </div>
                 <ul class="container1 customHeight" id="scroller1" style="max-height:350px !important">  
                        <div class="content customHeight" id="walkin_list" style="height:350px !important">
                            <?php
                            if (!empty($walkins)) {
                                $w = 1;
                                foreach ($walkins as $walkin) {
                                    ?>
                                    <li class="<?php echo ($w == 1) ? 'active' : ''; ?>"> 
                                        <a href="javascript:;" class="<?php echo ($w == 1) ? 'active' : ''; ?>"> <span class="count"><?php echo $w; ?></span> 
                                            <span class="textBlock"> <?php echo $walkin['WalkinAppointment']['name']; ?>
                                                <h6> @ <?php echo date('h:i A', strtotime($walkin['WalkinAppointment']['created'])); ?></h6>
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
                        </div>
                    </ul>
            </div>

        </div>
        <div class="col-md-6" style="padding: 30px 0 0 0;">
            <div class="reslistBlock tv-screen new">
                <div class="headingBlock text-center">Reservations </div>
                <ul class="container2 customHeight" id="scroller2" style="max-height:350px !important; ">
                    <div class="content2 customHeight" id="res_list" style="height:350px !important">
                        <?php
                        if ($reservations) {
                            $r = 1;
                            foreach ($reservations as $reservation) {
                                ?>
                                <li class="<?php echo ($r == 1) ? 'active' : ''; ?>"> 
                                    <a href="javascript:;" class="<?php echo ($r == 1) ? 'active' : ''; ?>"> <span class="count"><?php echo $r; ?></span> 
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
                        <?php } ?></div>
                </ul>
            </div>

        </div>
    
    <?php if (!$this->request->is('ajax')) { ?>
        </div>
        <div class="col-md-12 adv-body" style="position: fixed;bottom: 0;">

            <?php if (!empty($shopAddList)) { ?>
                <div class="col-xs-6 ">
                    <div class="row tv-add-col-left">
                        <div class="tv-add" id="shopAddList">
                            <img  width="675" height="159" src="<?php echo $shopAddList[0]; ?>">
                        </div>
                    </div>
                </div> 

                <?php if (count($shopAddList) > 1) { ?>
                    <script>
                        $(document).ready(function () {
                            var shopAddListImages = [];
            <?php foreach ($shopAddList as $key => $value) { ?>
                                shopAddListImages[<?php echo $key + 1; ?>] = "<?php echo $value; ?>";

            <?php } ?>

                            var k = 0;
                            function fadeDivs() {

                                $('#shopAddList img').fadeOut(300, function () {

                                    if (k >= shopAddListImages.length) {
                                        k = 1;
                                    }

                                    $(this).attr('src', shopAddListImages[k]).fadeIn(300);
                                })
                                k = k + 1;
                                setTimeout(fadeDivs, 20000);
                            }
                            ;
                            fadeDivs();

                        });
                    </script>

                    <?php
                }
            } else {
                ?>
                <div class="col-xs-6">
                    <div class="row tv-add-col-left">
                        <div class="tv-add">
                            <img width="675" height="159" src="<?php echo SITE_URL; ?>img/no-img.jpg">
                        </div>
                    </div>
                </div>
            <?php
            }

            if (!empty($adminAddList)) {
                ?>
                <div class="col-xs-6">
                    <div class="row tv-add-col-right">
                        <div class="tv-add" id="adminAddList">
                            <img width="675" height="159" src="<?php echo $adminAddList[0]; ?>">
                        </div>
                    </div>
                </div>

        <?php if (count($adminAddList) > 1) { ?>
                    <script>
                        $(document).ready(function () {
                            var adminAddListImages = [];
            <?php foreach ($adminAddList as $key => $value) { ?>
                                adminAddListImages[<?php echo $key + 1; ?>] = "<?php echo $value; ?>";

            <?php } ?>

                            var j = 0;


                            function fadeDivs1() {


                                $('#adminAddList img').fadeOut(300, function () {
                                    if (j >= adminAddListImages.length) {
                                        j = 1;

                                    }

                                    $(this).attr('src', adminAddListImages[j]).fadeIn(300);
                                })

                                j = j + 1;

                                setTimeout(fadeDivs1, 20000);
                            }
                            fadeDivs1();

                        });
                    </script>

                    <?php
                }
            } else {
                ?>
                <div class="col-xs-6">
                    <div class="row tv-add-col-right">
                        <div class="tv-add">
                            <img width="675" height="159" src="<?php echo SITE_URL; ?>img/no-img.jpg">
                        </div>
                    </div>
                </div>
            <?php }
            ?>

        </div>
    </div>
<?php } ?>


<script type='text/javascript'>   
    //$(".mCustomScrollbar").mCustomScrollbar();
    $(document).ready(function () {
        var headingHeight=$('.headingBlock').height()+70;
        var advHeight=$('.adv-body').height();
        var winHeight=$(window).height();
        var listHeight=winHeight-advHeight-headingHeight;
        $('.customHeight').css('min-height',listHeight+'px')
        $(document).keydown(function (event) {
            if (event.ctrlKey == true && (event.which == '61' || event.which == '107' || event.which == '173' || event.which == '109' || event.which == '187' || event.which == '189')) {
                //alert('disabling zooming'); 
                event.preventDefault();
            }
        });

        $(window).bind('mousewheel DOMMouseScroll', function (event) {
            if (event.ctrlKey == true) {
                //alert('disabling zooming'); 
                event.preventDefault();
            }
        });
    });


    

</script>

<script>
    var autoScroll,autoScroll1,autoScrollTimer,autoScrollTimer1,content,content1,autoScrollTimerAdjust,autoScrollTimerAdjust1;
              $(document).ready(function () {
                                var liSize=parseInt($('.container1 li').length)
                                var liSize1=parseInt($('.container2 li').length)
                                
                                content=$(".container1"),autoScrollTimer=liSize*3000;
                                content1=$(".container2"),autoScrollTimer1=liSize1*3000;
                                var scrollFirstStart=0;
                                content.mCustomScrollbar({                                       
                                        callbacks:{
                                                whileScrolling:function(){                                                   
//                                                    if(scrollFirstStart!=0 && parseInt(this.mcs.topPct)==0){  
//                                                      AutoScrollOff();
//                                                       setTimeout(function(){
//                                                          AutoScrollOn("bottom");
//                                                       },10000)
//                                                    }
//                                                    else if(parseInt(this.mcs.topPct)==100){//console.log('hundered');
//                                                       scrollFirstStart++; 
//                                                       AutoScrollOff();
//                                                       setTimeout(function(){
//                                                          AutoScrollOn("top");
//                                                       },10000)
//                                                    }
                                                  
                                                  
                                                },
                                                onScroll:function(){ 
//                                                    console.log('ssss');  
                                                }
                                        }
                                });
                                content1.mCustomScrollbar({                                       
                                        callbacks:{
                                                whileScrolling:function(){
                                                    /* var liSize1=parseInt($('.container2 li').length)*150;
													$("#res_list").css("height",liSize1+"px"); */
													//$("#res_list").css("height","800px");
                                                },
                                                onScroll:function(){ 
                                                     
                                                }
                                        }
                                });
                                content.addClass("auto-scrolling-on auto-scrolling-to-bottom");
                                content1.addClass("auto-scrolling-on auto-scrolling-to-bottom");
                                AutoScrollOn("bottom");
                                AutoScrollOn1("bottom");
                                
				
			
                });
                function AutoScrollOn(to,timer){
                                        if(!timer){timer=autoScrollTimer;}
                                        content.addClass("auto-scrolling-on").mCustomScrollbar("scrollTo",to,{scrollInertia:timer,scrollEasing:"linear"});
                                        autoScroll=setTimeout(function(){
										        if(content.hasClass("auto-scrolling-to-top")){
                                                        AutoScrollOff();
                                                        setTimeout(function(){
                                                         AutoScrollOn("bottom",timer);
                                                        },10000)
                                                        
                                                        content.removeClass("auto-scrolling-to-top").addClass("auto-scrolling-to-bottom");
                                                }else{
                                                       AutoScrollOff();
                                                       setTimeout(function(){
                                                            AutoScrollOn("top",timer);
                                                       },10000)                                                     
                                                        content.removeClass("auto-scrolling-to-bottom").addClass("auto-scrolling-to-top");
                                                }
                                        },timer);
                                }
                                function AutoScrollOn1(to1,timer1){
                                        if(!timer1){timer1=autoScrollTimer1;}
                                        content1.addClass("auto-scrolling-on").mCustomScrollbar("scrollTo",to1,{scrollInertia:timer1,scrollEasing:"linear"});
                                        autoScroll1=setTimeout(function(){
                                            var liSize2=parseInt($('.container2 li').length);
                                            var timer2=liSize2*3000;
                                            
                                                
                                                    if(content1.hasClass("auto-scrolling-to-top")){                                                       
                                                        AutoScrollOff1();
                                                        setTimeout(function(){
                                                         AutoScrollOn1("bottom",timer1);
                                                        },10000)

                                                        content1.removeClass("auto-scrolling-to-top").addClass("auto-scrolling-to-bottom");

                                                    }else{                                                       
                                                        AutoScrollOff1();
                                                        setTimeout(function(){
                                                                AutoScrollOn1("top",timer1);
                                                        },10000)

                                                        content1.removeClass("auto-scrolling-to-bottom").addClass("auto-scrolling-to-top");

                                                    }
													//liSize1 = liSize2;
												
                                        },timer1);
                                }
                                function AutoScrollOff(){
                                        clearTimeout(autoScroll);
                                        content.removeClass("auto-scrolling-on").mCustomScrollbar("stop");
                                }
                                function AutoScrollOff1(){
                                        clearTimeout(autoScroll1);
                                        content1.removeClass("auto-scrolling-on").mCustomScrollbar("stop");
                                }
</script>