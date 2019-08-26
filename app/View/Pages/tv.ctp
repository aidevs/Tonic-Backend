 
<?php if (!$this->request->is('ajax')) { ?>
    <div class="tv-page-inner clearfix">
        <div class="ajax-tv col-md-12" style="<?php echo ($shop['User']['is_ad_on']==1)?'':'margin-bottom:0;'; ?>"> 
         <input type="hidden" id="shop-id" value="<?php echo $shop['User']['id'] ?>" />   
    <?php } ?>   
    
        <div class="col-md-6" style="padding: 30px 16px 0 0;" >
            <div class="reslistBlock tv-screen new">
                <div class="headingBlock text-center" >Walk-ins </div>
                 <ul class="container1 customHeight" id="scroller1" style="max-height:350px !important">  
                        
                            <?php
                            if (!empty($walkins)) {
                                $w = 1;
                                foreach ($walkins as $walkin) {
                                    ?>
                                    <li class="<?php //echo ($w == 1) ? 'active' : ''; ?>"> 
                                        <a href="javascript:;" class="<?php echo ($w == 1) ? 'active1' : ''; ?>"> <span class="count"><?php echo $w; ?></span> 
                                            <span class="textBlock"> <?php echo $walkin['WalkinAppointment']['name']; ?>
                                                <!--<h6> @ <?php // echo date('h:i A', strtotime($walkin['WalkinAppointment']['created'])); ?></h6>-->
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
                       
                    </ul>
            </div>

        </div>
        <div class="col-md-6" style="padding: 30px 0 0 0;">
            <div class="reslistBlock tv-screen new">
                <div class="headingBlock text-center">Reservations </div>
                <ul class="container2 customHeight" id="scroller2" style="max-height:350px !important; ">
                   
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
                </ul>
            </div>

        </div>
    
    <?php if (!$this->request->is('ajax')) { ?>
        </div>
        <?php if($shop['User']['is_ad_on']==1){ ?>
        <div class="col-md-12 adv-body" style="position: fixed;bottom: 0;">

            <?php if (!empty($shopAddList)) { ?>
                    <div class="row tv-add-col-left">
                        <div class="tv-add" id="shopAddList">
                            <img   height="159" src="<?php echo $shopAddList[0]; ?>">
                        </div>
                    </div>

                <?php if (count($shopAddList) > 1) { ?>
                    

                    <?php
                }
            } else {
                ?>
                    <div class="row tv-add-col-left">
                        <div class="tv-add">
                            <img height="159" src="<?php echo SITE_URL; ?>img/no-img.jpg">
                        </div>
                    </div>
            <?php
            }
  /*
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
            <?php } */
            ?>

        </div>
        <?php } ?>
    </div>



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
    var autoScroll,autoScroll1,autoScrollTimer,autoScrollTimer1,content,content1,autoScrollTimerAdjust,autoScrollTimerAdjust1,scroller;
                var liSize=parseInt($('.container1 li').length)
                var liSize1=parseInt($('.container2 li').length)
                
                 //run instantly and then goes after (setTimeout interval)
                var isSyncingLeftScroll = false;
                var isSyncingRightScroll = false;
                var leftDiv = document.getElementById('scroller1');
                var rightDiv = document.getElementById('scroller2');

                leftDiv.onscroll = function () {
                    if (!isSyncingLeftScroll) {
                        isSyncingRightScroll = true;
                        rightDiv.scrollTop = this.scrollTop;
                    }
                    isSyncingLeftScroll = false;
                }

                rightDiv.onscroll = function () {
                    if (!isSyncingRightScroll) {
                        isSyncingLeftScroll = true;
                        leftDiv.scrollTop = this.scrollTop;
                    }
                    isSyncingRightScroll = false;
                }
              $(document).ready(function () {                               
                    scrollList();
//                $(scrollId).animate({scrollTop: $(scrollId)[0].scrollHeight}, 300000);
//                setTimeout(function () {
//                    $(scrollId).animate({scrollTop: 0}, 300000);
//                }, 300000);
//                var handle = setInterval(function () {
//                    // 4000 - it will take 4 secound in total from the top of the page to the bottom
//                    $(scrollId).animate({scrollTop: $(scrollId)[0].scrollHeight}, 300000);
//                    setTimeout(function () {
//                       
//                        $(scrollId).animate({scrollTop: 0}, 300000);
//                    }, 300000);
//
//                }, 32000);
            });
              function scrollList(){
                  
                   $('#scroller1').animate({scrollTop:0}, 0);
                   $('#scroller2').animate({scrollTop:0}, 0);
                    var liSize=parseInt($('.container1 li').length)
                    var liSize1=parseInt($('.container2 li').length)
                    var scrollId='#scroller2';
                    
                    if(liSize > liSize1){
                     scrollId='#scroller1';  
                    }
                    
                    var div = $(scrollId);
                     scroller=setInterval(function(){
                        var pos = div.scrollTop();
                        if(div.scrollTop() + div.innerHeight() >= div[0].scrollHeight){
                            clearInterval(scroller);
                            var sTop=setInterval(function(){
                                        var pos1 = div.scrollTop();
                                        div.scrollTop(pos1 - 1);
                                        if(pos1==0){
                                           clearInterval(sTop); 
                                           scrollList();
                                        }
                            }, 100);                           
                         }
                        div.scrollTop(pos + 1);
                    }, 100) 
                   
                    }                  
				
			
              
                
</script>
<?php } ?>