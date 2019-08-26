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
      var autoRefresh=setInterval(function(){ getTv(); }, 60000);
    }
    var getTv = function(){
        $.ajax({
            url: window.location.href,
            type: 'GET',
            success: function (data) {
              $('.ajax-tv').html(data);
            }
        })
    }
    return {
        init: function () {
            refreshTv();
            showSplash();
        }
    };
}();

$(document).ready(function () {
    Common.init();
}); 