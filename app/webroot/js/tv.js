$.blockUI.defaults.message = '<img src="' + SITE_URL + 'img/ajax.gif"/>';
$.blockUI.defaults.css.border = 'none';
$.blockUI.defaults.css.padding = '15px';
$.blockUI.defaults.css.backgroundColor = 'transparent';
$.blockUI.defaults.css.opacity = .5;
/*
 * 
 * Common Class
 */
var Common = function () {    
    var showSplash = function () {
       if(parseInt(getCookie('close'))!=1){
          $('.splash').show(); 
          setTimeout(function(){
          $('.splash').fadeOut( "slow");
          setCookie('close',1);
          }, 5000); 
       } 
    }
    var setCookie = function (name, value, days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            var expires = "; expires=" + date.toGMTString();
        }
        else
            var expires = "";
        document.cookie = name + "=" + value + expires + "; path=/";
    }
    var getCookie = function (name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ')
                c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0)
                return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
    var refreshTv = function(){
      clearInterval(autoRefresh)
      var autoRefresh=setInterval(function(){ 
          getTv();
          
      }, 15000);
    }
	
	var url_text = window.location.href;
	var change_url = url_text.replace('tv', 'tvReservationList');
        var walkin_url = url_text.replace('tv', 'tvWalkinList');
    var getTv = function(){
        var licount = parseInt($('.container2 li').length);
        $.ajax({
            url: change_url,
			//url: SITE_URL + 'pages/tvReservationList',
			type: 'GET',
                        async:true,
            success: function (data) {
				//var test_li = '<li class=""><a class="" href="javascript:;"> <span class="count">2</span><span class="textBlock"> alex warn	<h6> @ 09:30 AM</h6></span> <span class="selectHand"> <img src="/tonic/images/back-icon-black.png"> </span>		<span class="tv-user"><img src="/tonic/thumbnail/thumbnail.php?file=../uploads/users/1456222197Tulips.jpg&amp;w=100&amp;h=100&amp;el=0&amp;gd=2&amp;color=FFFFFF&amp;crop=1&amp;tp=1"></span></a> </li>';
				
                               getWalkinList(data);
                        }
        })
    }
    var getWalkinList = function(datazOthr){
        var walkinlicount = parseInt($('.container1 li').length);
        $.ajax({
            url: walkin_url,
			//url: SITE_URL + 'pages/tvReservationList',
			type: 'GET',
                        async:true,
            success: function (data) {
				//var test_li = '<li class=""><a class="" href="javascript:;"> <span class="count">2</span><span class="textBlock"> alex warn	<h6> @ 09:30 AM</h6></span> <span class="selectHand"> <img src="/tonic/images/back-icon-black.png"> </span>		<span class="tv-user"><img src="/tonic/thumbnail/thumbnail.php?file=../uploads/users/1456222197Tulips.jpg&amp;w=100&amp;h=100&amp;el=0&amp;gd=2&amp;color=FFFFFF&amp;crop=1&amp;tp=1"></span></a> </li>';
				$('#scroller1').html(data);
                                $('#scroller2').html(datazOthr);
                                 clearInterval(scroller);
                                 scrollList();
                        }
        })
    }
    var refreshAdd = function(){
      clearInterval(autoRefreshAd)
      var autoRefreshAd=setInterval(function(){ 
          getAd();
      }, 20000);
    }
    var getAd = function(){
        $.ajax({           
            url: SITE_URL + 'pages/ads/'+$('#shop-id').val(),
            type: 'GET',
            success: function (data) {
             $('.adv-body').html(data);
            }
        })
    }
    return {
        init: function () {
            refreshTv();
            refreshAdd();
            showSplash();
        }
    };
}();

$(document).ready(function () {
    Common.init();
}); 