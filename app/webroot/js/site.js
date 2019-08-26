$.blockUI.defaults.message = '<img src="' + SITE_URL + 'img/ajax.gif"/>';
$.blockUI.defaults.css.border = 'none';
$.blockUI.defaults.css.padding = '15px';
$.blockUI.defaults.css.backgroundColor = 'transparent';
$.blockUI.defaults.css.opacity = .5;
jQuery.validator.addMethod("pattern", function (value, element, param) {
    if (this.optional(element)) {
        return true;
    }
    if (typeof param === 'string') {
        param = new RegExp('^(?:' + param + ')$');
    }
    return param.test(value);
}, "Invalid format.");
jQuery.validator.addMethod("lettersonly", function (value, element) {
    return this.optional(element) || /^[a-z ]+$/i.test(value);
}, "Please use letters only.");
$.validator.addMethod("phoneUS", function (phone_number, element) {
    phone_number = phone_number.replace(/\s+/g, "");
    return this.optional(element) || phone_number.length > 9 &&
            phone_number.match(/^(\+?1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
}, "Please specify a valid phone number");

/*
 * 
 * Common Class
 */
var Common = function () {
    var loadAlert = function () {
        $(".alert").delay(8000).slideUp(200, function () {
            $(this).remove();
        });
        $(".alert button.close").click(function () {
            $('.alert').remove();
        })
    }
    var showSplash = function () {
        if (parseInt(getCookie('close')) != 1) {
            $('.splash').show();
            setTimeout(function () {
                $('.splash').fadeOut("slow");
                setCookie('close', 1);
            }, 5000);
        }
    }
    var setCookie = function (name, value, days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            var expires = "; expires=" + date.toGMTString();
        } else
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
    var goBack = function (){
       $('body').on('click','a.go-back',function(){
            window.history.back();
            return false;
       })
    }
    return {
        init: function () {
            loadAlert();
            showSplash();
            goBack();
        }
    };
}();

/*
 * Login Class
 */
var Login = function () {
    var loginFormValidate = function () {
        $('.user_login_form').validate({
            rules: {
                'data[User][email]': {
                    required: true,
                    email: true
                },
                'data[User][password]': {
                    required: true,
                }
            }, messages: {
                'data[User][email]': {
                    required: 'Please enter an email.'
                },
                'data[User][password]': {
                    required: 'Please enter password.',
                }
            }
        });
    }
    var forgotFormValidate = function () {
        $('.user_forgot_password').validate({
            rules: {
                'data[User][email]': {
                    required: true,
                    email: "Please enter a valid email."
                }
            }, messages: {
                'data[User][email]': {
                    required: 'Please enter an email.'
                }
            }
        });
    }
    var resetFormValidate = function () {
        $('.user_reset_password').validate({
            rules: {
                'data[User][password]': {
                    required: true,
                    minlength: 6

                },
                'data[User][confirm_password]': {
                    required: true,
                    equalTo: "#UserPassword"

                }
            }, messages: {
                'data[User][password]': {
                    required: 'Please enter new password.',
                    minlength: 'New password must be at least 6 characters long'
                },
                'data[User][confirm_password]': {
                    required: 'Please enter confirm password.',
                    equalTo: "New password and confirm password not match."
                }
            }
        });
    }
    var changeFormValidate = function () {
        $('.user_change_password_form').validate({
            rules: {
                'data[User][password]': {
                    required: true,
                    minlength: 6
                },
                'data[User][confirm_password]': {
                    required: true,
                    equalTo: "#UserPassword"

                },
                'data[User][old_password]': {
                    required: true,
                    remote: {url: SITE_URL + 'users/check_password', type: 'POST', data: {field: 'old_password'}}

                }
            }, messages: {
                'data[User][password]': {
                    required: 'Please enter new password.',
                    minlength: 'Password must be at least 6 characters long.'
                },
                'data[User][confirm_password]': {
                    required: 'Please enter confirm password.',
                    equalTo: "Password and confirm password not match."
                },
                'data[User][old_password]': {
                    required: 'Please enter old password.',
                    remote: 'Old password is wrong.'
                }


            }
        });
    }
    return {
        init: function () {
            loginFormValidate();
            forgotFormValidate();
            resetFormValidate();
            changeFormValidate();
        }
    };
}();
/*
 * Profile Class
 */
var Profile = function () {
    var registerFormValidate = function () {
        $('.user_register_form').validate({
            rules: {
                'data[User][name]': {
                    required: true,
                    lettersonly: true
                },
                'data[User][email]': {
                    required: true,
                    email: true,
                    remote: {url: SITE_URL + 'ajax/check_field', type: 'POST', data: {model: 'User', field: 'email'}}
                },
                'data[User][password]': {
                    required: true,
                    minlength: 6
                },
                'data[User][dob]': {
                    required: true
                },
                'data[User][phone]': {
                    required: true,
                    phoneUS: true,
                    pattern: /^[\d\s]+$/,
                    maxlength: 15,
                }
            }, messages: {
                'data[User][name]': {
                    required: 'Please enter name.'
                },
                'data[User][email]': {
                    required: 'Please enter email.',
                    email: 'Please enter valid email.',
                    remote: 'Email already exists.'
                },
                'data[User][password]': {
                    required: 'Please enter new password.',
                    minlength: 'Password must be at least 6 characters long.'
                },
                'data[User][dob]': {
                    required: 'Required.',
                },
                'data[User][phone]': {
                    required: 'Please enter phone.',
                    pattern: 'Please enter valid phone.',
                    maxlength: 'Phone is no more than 15 digits.'
                }
            }
        });
    }
    var editFormValidate = function () {
        $('.user_edit_profile_form').validate({
            rules: {
                'data[User][name]': {
                    required: true,
                    lettersonly: true
                },
                'data[User][first_name]': {
                    required: true
                },
                'data[User][last_name]': {
                    required: true
                },
                'data[User][dob]': {
                    required: true
                },
                'data[User][phone]': {
                    required: true,
                    phoneUS: true,
                    pattern: /^[\d\s]+$/,
                    maxlength: 15,
                },
                'data[User][business_type]': {
                    required: true
                },
                'data[User][company_name]': {
                    required: true
                },
                'data[User][company_info]': {
                    required: true
                },
                'data[User][address]': {
                    required: true
                }
            }, messages: {
                'data[User][name]': {
                    required: 'Please enter name.'
                },
                'data[User][first_name]': {
                    required: 'Please enter first name.'
                },
                'data[User][last_name]': {
                    required: 'Please enter last name.'
                },
                'data[User][dob]': {
                    required: 'Required.'
                },
                'data[User][phone]': {
                    required: 'Please enter phone.',
                    pattern: 'Please enter valid phone.',
                    maxlength: 'Phone is no more than 15 digits.'
                },
                'data[User][business_type]': {
                    required: 'Please enter business type.'
                },
                'data[User][company_name]': {
                    required: 'Please enter company name.'
                },
                'data[User][company_info]': {
                    required: 'Please enter company info.'
                },
                'data[User][address]': {
                    required: 'Please enter address.'
                }
            }
        });
    }
    var editImageUpload = function () {
        var btnUpload = $('#edit_image');
        if (btnUpload.is('input')) {
            new AjaxUpload(btnUpload, {
                action: SITE_URL + 'ajax/change_profile_image',
                name: 'uploadfile',
                onChange: function () {
                    $.blockUI();
                },
                onSubmit: function (file, ext) {
                    if (!(ext && /^(jpg|png|jpeg)$/.test(ext))) {
                        toastr['error']('Only JPG, PNG files are allowed.');
                        return false;
                    }
                },
                onComplete: function (file, response) {
                    $.unblockUI();
                    var obj = $.parseJSON(response)
                    if (obj.error == 0) {
                        var imagePath = '../thumbnail/thumbnail.php?file=../uploads/users/' + obj.image + '&w=120&h=120&el=0&gd=2&color=FFFFFF&crop=1&tp=1';
                        $('.profilePic img').attr('src', imagePath);
                    } else {
                        toastr['error'](obj.msg);
                    }
                    $('input[type="file"]').attr('accept', 'image/*');
                }
            });
            $('input[type="file"]').attr('accept', 'image/*');
        }
    }
    var setSwipe = function () {
        
    }
    var showNote = function () {
        $('body').on('click', '.note-spam i.fa', function () {
            var html = '<div class="modal-content"><div class="modal-body">' + $(this).next().html() + '</div></div>';
            $('#slot-box .modal-body').html(html);
            $('#slot-box').modal('show');
            //alert(123)
        })
    }
    var setDatePicker = function () {
        $('#UserDob').datepicker({
            todayHighlight: true,
            endDate: new Date(),
            startView: "decade",
            format: 'mm/dd/yyyy',
            autoclose: true,
        });
    }
    return {
        init: function () {
            registerFormValidate();
            editFormValidate();
            editImageUpload();
            setSwipe();
            showNote();
            setDatePicker();
        }
    };
}();
/*
 * Calendar Class
 */
var Calendar = function () {
    var carousel;
    var setbarberCarousel = function () {
        carousel = $("#scrolling ul");
        carousel.itemslide({
            swipe_out: false, //NOTE: REMOVE THIS OPTION IF YOU WANT TO DISABLE THE SWIPING SLIDES OUT FEATURE.
            left_sided: true,
            disable_scroll: true
        }); //initialize itemslide


        $(window).resize(function () {
            carousel.reload();
            $("#scrolling ul").css('transform', 'translate3d(0px, 0px, 0px)')
        }); //Recalculate width and center positions and sizes when window is resized

    }
    if(typeof datesForDisable == "undefined"){
           var datesFDisable = [];
    }else{
       var datesFDisable = datesForDisable; 
    }
    var setDatePicker = function () {



        var queryDate = $('#setDefaultDate').val();
        dateParts = queryDate.match(/(\d+)/g)
        var realDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);
        $('.date-picker').datepicker({
            "startDate": new Date(),
            //endDate: '+30d',
             format: 'yyyy-m-d',
             datesDisabled: datesFDisable,
        })
                .datepicker('setDate', realDate)
                .on('changeDate', function (ev) {

                    if (typeof ev.date != 'undefined') {
                        var nwd = new Date(ev.date);
                        var newDate = nwd.getFullYear() + '-' + ("0" + (nwd.getMonth() + 1)).slice(-2) + '-' + nwd.getDate();

                        $(".date-picker").off("click");

                        var barberId = $("#setBarberId").val();
                        var scheduleId = $("#setScheduleId").val();
                        var query = $("#query").val();
                        if($(".barber-calendar").is('div')){
                           window.location.href = SITE_URL + 'barbers/my_calendar/' + barberId + '/' + scheduleId + '/' + newDate + '?q=' + query; 
                        }else{
                           window.location.href = SITE_URL + 'barbers/calendar/' + barberId + '/' + scheduleId + '/' + newDate + '?q=' + query;
                         }
                    }

                });
        //}).find('.datepicker-switch').removeClass("datepicker-switch");
        $('.datepicker td.active').addClass('disabled');
    }

    var getBarberSchedule = function () {
        carousel.on('clickSlide ', function (e) {
            getBarberScheduleAjax(e.slide);
        });
    }
    var getBarberScheduleOnLoad = function () {
        if($('#setBarberId').val()!=''){
          getBarberScheduleAjax(0);
        }
    }
    var bookSlot = function () {
        var bookForm;
        $('body').on('click', '#book-slot-btn', function () {
            var html = $('#slot-box-content').html();
            $('#slot-box-content').remove();
            $('#slot-box .modal-body').html(html);
            $('#slot-box').modal('show');
            bookForm = $('.book-slot-form').validate({
                rules: {
                    'slot_id': {
                        required: true
                    }
                }, messages: {
                    'slot_id': {
                        required: 'Please select time.'
                    }
                }, errorPlacement: function (error, element) {
                    return true;
                }
            });
        })
        $('body').on('click', '.submit-slot-btn', function () {
            $("#slt_tm").html($('input[name="slot_id"]:checked').attr('rel'));
            $("#slt_tm_to").html($('input[name="to_slot"]').val());
            var price =parseFloat($('input[name="price"]').val());
            $("#slt_cost").html('$'+price.toFixed(2));
            $("#slot_confirm").modal("show");
        });
           $('body').on('change','.slt_lbl input',function(){
             var slotId=$(this).val();
             var total_time=$('input[name="time"]').val();   
             var service_time=$('input#service-time').val();   
             var requiredSlot=total_time/service_time;
             var nextElement=$(this).parent('label').next('label');
             var nextLabel=$(this).parent('label').next('label');
             var nSlot=parseInt(slotId);
             var slotArr=[];
             if (requiredSlot > 1) {
                for (var i = 1; i <= requiredSlot; i++) {
                    var nextSlotId = $('input', nextElement).val();
                    slotArr.push(nSlot);
                    nSlot = nSlot + 1;
                    nextElement = nextElement.next('label');
                    if (nSlot != nextSlotId && slotArr.length !=requiredSlot) {
                        $('.submit-slot-btn').hide();
                        toastr['error']('Total service time is ' + total_time + ' minutes, so to accommodate it please pick another slot.');
                        return false;
                    }
                    if (i < requiredSlot) {
                        nextLabel.addClass('active');
                        nextLabel = nextLabel.next('label');
                    }
                }
            }
             var appStartTime=$('input[name="slot_id"]:checked').attr('rel');
             var appEndTime=moment.utc(appStartTime,'hh:mm A').add(parseInt(total_time),'minute').format('hh:mm A');
             $('input[name="slot_ids"]').val(slotArr.join(','));
             $('input[name="to_slot"]').val(appEndTime);
             $('.submit-slot-btn').show();
            })
        $('body').on('click', '#btn_slot_confirm', function () {
            if ($('.book-slot-form').valid()) {
                var activeSlide = $('.itemslide-active').data('number');
                var barberId = $('#setBarberId').val();
                var scheduleId = $('#setScheduleId').val();
                var slotId = $('input[name="slot_id"]:checked').val();
                var slotIds = $('input[name="slot_ids"]').val();
                var service = $('input[name="service"]').val();
                var to_slot = $('input[name="to_slot"]').val();
                var time = $('input[name="time"]').val();
                var price = $('input[name="price"]').val();
                var date = $('#setDefaultDate').val();
                var data = {'barber_id': barberId, 'schedule_id': scheduleId, 'slot_id': slotId, 'date': date,'slot_ids':slotIds,'service':service,'to_slot':to_slot,time,'time':time,'price':price};
                $.ajax({
                    url: SITE_URL + 'ajax/book_schedule',
                    type: 'POST',
                    data: data,
                    beforeSend: function () {
                        $.blockUI();
                    },
                    success: function (data) {
                        $('#slot-box').unblock();

                        var obj = $.parseJSON(data)
                        $.unblockUI();
                        if (obj.error) {
                            toastr['error'](obj.msg);
                        } else {                           
                            $("#slot_confirm").modal("hide");
                            $("#slot_booked_modal").modal("show");
                            //window.location.href = SITE_URL + "barbers/confirmation";
                        }

                    }
                })
            }
        })
    }
    var getBarberScheduleAjax = function (slide) {
        var barberId = $('#setBarberId').val();
        var scheduleId = $('#setScheduleId').val();
        var q = $('#query').val();
        /* var scheduleId = $('.slide-' + slide).data('schedule');
         $('#setScheduleId').val(scheduleId); */

        var myDate = $('#setDefaultDate').val();

        $.ajax({
            url: SITE_URL + 'ajax/get_barber_schedule/' + barberId + '/' + scheduleId + '/' + myDate+'?q='+q,
            type: 'GET',
            beforeSend: function () {
                $.blockUI();
            },
            success: function (data) {
                $('#js-appointment').html(data);
                $.unblockUI();
            }
        })
    }
    var setSwipe = function () {
        $(".reslistBlock").swipe({
            swipe: function (event, direction, distance, duration, fingerCount) {
                if (direction == 'up' && $('.reslistBlock').hasClass('down')) {
                    $('.reslistBlock').animate({
                        'bottom': '-210px;'
                    }, 100, function () {
                        $('.reslistBlock').show().animate({
                            'bottom': '0'
                        });
                    });
                    $('.reslistBlock').toggleClass('down up');
                } else if (direction == 'down' && $('.reslistBlock').hasClass('up')) {
                    $('.reslistBlock').animate({
                        'bottom': '-210px'
                    }, 500/* , function () {
                     $('.reslistBlock').show().animate({
                     'bottom': '-320px;'
                     });
                     } */);

                    $('.reslistBlock').toggleClass('up down');
                }
            }
        });
    }

    $("#reserv_blk").find(".arrow_up_down").click(function () {
        if ($('.reslistBlock').hasClass('down')) {
            $('.reslistBlock').animate({
                'bottom': '-210px;'
            }, 100, function () {
                $('.reslistBlock').show().animate({
                    'bottom': '0'
                });
                $('#arrow_down').show();
                $('#arrow_up').hide();
            });
            $('.reslistBlock').toggleClass('down up');
        } else if ($('.reslistBlock').hasClass('up')) {
            $('.reslistBlock').animate({
                'bottom': '-210px'
            }, 500/* , function () {
             $('.reslistBlock').show().animate({
             'bottom': '-320px;'
             });
             } */);
            $('#arrow_down').hide();
            $('#arrow_up').show();

            $('.reslistBlock').toggleClass('up down');
        }
    });
    $("#reserv_blk").find(".book_arrow_up_down").click(function () {
        if ($('.reslistBlock').hasClass('down')) {
            $('.reslistBlock').animate({
                'bottom': '-355px;'
            }, 100, function () {
                $('.reslistBlock').show().animate({
                    'bottom': '0'
                });
                $('#arrow_down').show();
                $('#arrow_up').hide();
            });
            $('.reslistBlock').toggleClass('down up');
        } else if ($('.reslistBlock').hasClass('up')) {
            $('.reslistBlock').animate({
                'bottom': '-355px'
            }, 500/* , function () {
             $('.reslistBlock').show().animate({
             'bottom': '-320px;'
             });
             } */);
            $('#arrow_down').hide();
            $('#arrow_up').show();

            $('.reslistBlock').toggleClass('up down');
        }
    });



    $("#barber_reserv_blk").find(".arrow_up_down").click(function () {
        if ($('.reslistBlock').hasClass('down')) {
            $('.reslistBlock').animate({
                'bottom': '-320px;'
            }, 100, function () {
                $('.reslistBlock').show().animate({
                    'bottom': '0'
                });
            });
            $('#arrow_down').show();
            $('#arrow_up').hide();
            $('.reslistBlock').toggleClass('down up');
        } else if ($('.reslistBlock').hasClass('up')) {
            $('.reslistBlock').animate({
                'bottom': '-320px'
            }, 500/* , function () {
             $('.reslistBlock').show().animate({
             'bottom': '-320px;'
             });
             } */);
            $('#arrow_down').hide();
            $('#arrow_up').show();

            $('.reslistBlock').toggleClass('up down');
        }
    });


    $("#user_reserv_blk").find(".arrow_up_down").click(function () {
        if ($('.reslistBlock').hasClass('down')) {
            $('.reslistBlock').animate({
                'bottom': '-320px;'
            }, 100, function () {
                $('.reslistBlock').show().animate({
                    'bottom': '0'
                });
            });
            $('.reslistBlock').toggleClass('down up');
            $('#arrow_down').show();
            $('#arrow_up').hide();
        } else if ($('.reslistBlock').hasClass('up')) {
            $('.reslistBlock').animate({
                'bottom': '-320px'
            }, 500/* , function () {
             $('.reslistBlock').show().animate({
             'bottom': '-320px;'
             });
             } */);

            $('.reslistBlock').toggleClass('up down');
            $('#arrow_down').hide();
            $('#arrow_up').show();
        }
    });

    $(function () {
        if (window.history && window.history.pushState) {
            var targetUrl = $(this).attr('href');
            var targetTitle = $(this).attr('title');

            window.history.pushState({url: "" + targetUrl + ""}, targetTitle, targetUrl);
            $(window).on('popstate', function () {
                // alert('Back button was pressed.');
                document.location.href = SITE_URL + 'users/my_account';

            });
        }
    });

    var showNote = function () {
        $('body').on('click', '.note-spam i.fa', function () {
            var html = '<div class="modal-content"><div class="modal-body">' + $(this).next().html() + '</div></div>';
            $('#slot-box .modal-body').html(html);
            $('#slot-box').modal('show');
            //alert(123)
        })
    }
    var CheckNextAv = function () {
        $('body').on('click', 'a.check-next-av', function () {
            var $this=$(this);
            $.ajax({
                url: $(this).attr('href'),
                type: 'GET',
                beforeSend: function () {
                    $.blockUI();
                },
                success: function (data) {
                    var obj = $.parseJSON(data)
                    if (obj.next) {
                       var txt=obj.date+' @ '+obj.slot; 
                       var dateSplit = obj.date.split('/');
                       var newDate= dateSplit[2]+"-"+dateSplit[0]+"-"+dateSplit[1];
                       var href = $('a.book_apt',$this.parents('li')).attr('data-href');
                       $('a.book_apt',$this.parents('li')).removeClass('no-redirect');
                       $('a.book_apt',$this.parents('li')).attr('href',href+"/"+newDate);
                    } else {
                       var txt='Not Available';
                    }
                    $('.next-txt',$this.parents('.next-av-body')).html(txt);
                    $.unblockUI();
                }
            })
        })
    }
    return {
        init: function () {
            setbarberCarousel();
            setDatePicker();
            getBarberSchedule();
            getBarberScheduleOnLoad();
            bookSlot();
            //setSwipe();
            showNote();
            CheckNextAv();
        }
    }

}();
/*
 * barber Class
 */
var Barber = function () {
    var setDatePicker = function () {
        var queryDate = $('#setDefaultDate').val();
        dateParts = queryDate.match(/(\d+)/g)
        var realDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);
        $('.date-picker').datepicker({
            "startDate": new Date(),
            //endDate: '+30d'
        })
                .datepicker('setDate', realDate)
                .on('changeDate', function (ev) {

                    if (typeof ev.date != 'undefined') {
                        var nwd = new Date(ev.date);
                        var newDate = nwd.getFullYear() + '-' + ("0" + (nwd.getMonth() + 1)).slice(-2) + '-' + nwd.getDate();

                        $(".date-picker").off("click");

                        var barberId = $("#setBarberId").val();
                        var scheduleId = $("#setScheduleId").val();
                        var query = $("#query").val();
                        if ($(".barber-calendar").is('div')) {
                            window.location.href = SITE_URL + 'barbers/my_calendar/' + barberId + '/' + scheduleId + '/' + newDate + '?q=' + query;
                        } else {
                            window.location.href = SITE_URL + 'barbers/calendar/' + barberId + '/' + scheduleId + '/' + newDate + '?q=' + query;
                        }
                    }

                });
        //}).find('.datepicker-switch').removeClass("datepicker-switch");
        $('.datepicker td.active').addClass('disabled');
    }
    var getBarberScheduleAjax = function (slide) {
        var barberId = $('#setBarberId').val();
        var scheduleId = $('#setScheduleId').val();
        var q = $('#query').val();
        /* var scheduleId = $('.slide-' + slide).data('schedule');
         $('#setScheduleId').val(scheduleId); */

        var myDate = $('#setDefaultDate').val();

        $.ajax({
            url: SITE_URL + 'ajax/get_barber_schedule_barber/' + barberId + '/' + scheduleId + '/' + myDate+'?q='+q,
            type: 'GET',
            beforeSend: function () {
                $.blockUI();
            },
            success: function (data) {
                $('#js-appointment').html(data);
                $.unblockUI();
            }
        })
    }
    
    var bookSlot = function () {
        var bookForm;
        $('body').on('click', 'a.book-slot-btn', function () {
            if ($('.book-slot-form-user').valid()) {
                var activeSlide = $('.itemslide-active').data('number');
                var barberId = $('#setBarberId').val();
                var scheduleId = $('#setScheduleId').val();
                var slotId = $('input[name="slot_id"]:checked').val();
                var slotIds = $('input[name="slot_ids"]').val();
                var service = $('input[name="service"]').val();
                var to_slot = $('input[name="to_slot"]').val();
                var time = $('input[name="time"]').val();
                var price = $('input[name="price"]').val();
                var date = $('#setDefaultDate').val();
                var name = $('.modal input[name="name"]').val();
                var phone = $('.modal input[name="phone"]').val();               
                var email = $('.modal input[name="email"]').val();
                var data = {'barber_id': barberId, 'schedule_id': scheduleId, 'slot_id': slotId, 'date': date, 'slot_ids': slotIds, 'service': service, 'to_slot': to_slot, time, 'time': time, 'price': price,'name': name, 'phone': phone, 'email': email};
                $.ajax({
                    url: SITE_URL + 'ajax/book_barber_schedule',
                    type: 'POST',
                    data: data,
                    beforeSend: function () {
                        $.blockUI();
                    },
                    success: function (data) {
                        $('#slot-box').unblock();

                        var obj = $.parseJSON(data)
                        $.unblockUI();
                       
                        if (obj.error) {
                            toastr['error'](obj.msg);
                        } else {
                            window.location.href = SITE_URL + "barbers/confirmation";
                        }

                    }
                })
            }
        })
        $('#slot-box').on('shown.bs.modal', function (e) {
            bookForm = $('.book-slot-form-user').validate({
                rules: {
                    'service_id[]': {
                        required: true
                    },
                    'slot_id[]': {
                        required: true
                    },
                    'slot_id': {
                        required: true
                    },
                    'name': {
                        required: true
                    },
                    'phone': {
//                        required: true,
                        phoneUS: true
                    }, 'email': {
                        required: false,
                        email: true
                    }
                }, messages: {
                    'service_id[]': {
                        required: 'Please select service.'
                    },
                    'slot_id[]': {
                        required: 'Please select time.'
                    },
                    'slot_id': {
                        required: 'Please select time.'
                    },
                    'name': {
                        required: 'Please enter name.'
                    },
                    'phone': {
//                        required: 'Please enter phone number.',
                        phoneUS: 'Please specify a valid mobile number'
                    }, 'email': {
                        required: 'Please enter email id.',
                        email: 'Please Enter Valid Email Id'
                    }
                }
            });
        })
        $('body').on('click', '.submit-slot-btn', function () {
            $("#slt_tm").html($('input[name="slot_id"]:checked').attr('rel'));
            $("#slt_tm_to").html($('input[name="to_slot"]').val());
            var price =parseFloat($('input[name="price"]').val());
            $("#slt_cost").html('$'+price.toFixed(2));
            
            var html = $('#slot-box-content').html();
            $('#slot-box-content').remove();
            $('#slot-box .modal-body').html(html);
            $('#slot-box').modal('show');
            
            //$("#slot_confirm").modal("show");
        });
          $('body').on('change','.slt_lbl input',function(){
             var slotId=$(this).val();
             var total_time=$('input[name="time"]').val();   
             var service_time=$('input#service-time').val();   
             var requiredSlot=total_time/service_time;
             var nextElement=$(this).parent('label').next('label');
             var nextLabel=$(this).parent('label').next('label');
             var nSlot=parseInt(slotId);
             var slotArr=[];
             if (requiredSlot > 1) {
                for (var i = 1; i <= requiredSlot; i++) {
                    var nextSlotId = $('input', nextElement).val();
                    slotArr.push(nSlot);
                    nSlot = nSlot + 1;
                    nextElement = nextElement.next('label');
                    if (nSlot != nextSlotId && slotArr.length !=requiredSlot) {
                        $('.submit-slot-btn').hide();
                        toastr['error']('Total service time is ' + total_time + ' minutes, so to accommodate it please pick another slot.');
                        return false;
                    }
                    if (i < requiredSlot) {
                        nextLabel.addClass('active');
                        nextLabel = nextLabel.next('label');
                    }
                }
            }
             var appStartTime=$('input[name="slot_id"]:checked').attr('rel');
             var appEndTime=moment.utc(appStartTime,'hh:mm A').add(parseInt(total_time),'minute').format('hh:mm A');
             $('input[name="slot_ids"]').val(slotArr.join(','));
             $('input[name="to_slot"]').val(appEndTime);
             $('.submit-slot-btn').show();
            })
        $('body').on('click', '.submit-slot-btn11', function () {
            if ($('.book-slot-form').valid()) {
                var activeSlide = $('.itemslide-active').data('number');
                var barberId = $('#barberId').val();
                var scheduleId = $('#scheduleId').val();
                var slotId = $('select[name="slot_id"]').val();
                var name = $('input[name="name"]').val();
                var phone = $('input[name="phone"]').val();
                var date = $('#setDefaultDate').val();
                var email = $('input[name="email"]').val();
                var data = {'user_id': barberId, 'schedule_id': scheduleId, 'slot_id': slotId, 'date': date, 'name': name, 'phone': phone, 'email': email};
                $.ajax({
                    url: SITE_URL + 'ajax/book_barber_schedule',
                    type: 'POST',
                    data: data,
                    beforeSend: function () {
                        $('#slot-box').block();
                    },
                    success: function (data) {
                        $('#slot-box').unblock();

                        var obj = $.parseJSON(data)
                        if (obj.error) {
                            toastr['error'](obj.msg);
                        } else {
                            window.location.href = SITE_URL + "barbers/confirmation";
                        }

                    }
                })
            }
        });
        
        
    }
    
    var setSwipe = function () {
        $(".reslistBlock").find(".arrow_up_down").click(function () {
            if ($('.reslistBlock').hasClass('down')) {
                $('.reslistBlock').animate({
                    'bottom': '-320px;'
                }, 200, function () {
                    $('.reslistBlock').show().animate({
                        'bottom': '-70px'
                    });
                });
                $('#arrow_down').show();
                $('#arrow_up').hide();

                $('.reslistBlock').toggleClass('down up');
            } else {
                $('.reslistBlock').animate({
                    'bottom': '-320px'
                });
                $('#arrow_down').hide();
                $('#arrow_up').show();
                $('.reslistBlock').toggleClass('up down');
            }
        });
    }
    var showNote = function () {
        $('body').on('click', '.note-spam i.fa', function () {
            var html = '<div class="modal-content"><div class="modal-body">' + $(this).next().html() + '</div></div>';
            $('#slot-box .modal-body').html(html);
            $('#slot-box').modal('show');
            //alert(123)
        })
    }


    return {
        init: function () {
            setDatePicker();
            getBarberScheduleAjax();
            bookSlot();
            setSwipe();
            showNote();
        }
    }

}();
/*
 * Notes Class
 */
var Notes = function () {
    var setSwitch = function () {
        $('.make-switch').bootstrapSwitch();
    }
    var setNotesShow = function () {
        $('body').on('change', 'input[name="data[User][show_notes]"]', function () {
            if ($('.btn-group .btn').hasClass('btn-danger')) {
                $('.btn-group .btn').toggleClass('btn-danger btn-success');
                $('.btn-group .btn input').removeAttr('checked');
                $('.btn-group .btn span').text('Show To Barber');
            } else {
                $('.btn-group .btn').toggleClass('btn-success btn-danger');
                $('.btn-group .btn input').attr('checked', 'checked');
                $('.btn-group .btn span').text('Hide To Barber');
            }
        })
    }
    return {
        init: function () {
            setSwitch();
            setNotesShow();
        }
    }
}();
var Service = function () {
    var serviceFormValidate = function () {
        $('.service-form').validate({
            rules: {
                'service_id[]': {
                    required: true
                }
            }, messages: {
                'service_id[]': {
                    required: 'Please select service.'
                }
            }, errorPlacement: function (error, element) {
                return true;
            }
        });
        $('body').on('click', '.submit-service-btn', function () {
            if ($('.service-form').valid()) {
                $('.service-form').submit();
            } else {
                toastr['error']('Please select service.');
            }
        })
        $('body').on('change', '.slt-service input[type="checkbox"]', function () {
            var parent = $(this).parent('label');
            if ($(this).is(':checked')) {
                $('span', parent).text('SELECTED');
            } else {
                $('span', parent).text('SELECT');
            }
            var sum = 0;
            $('.slt-service input[type="checkbox"]:checked').each(function () {
                sum = parseFloat($(this).data('price')) + sum;
            });
            var time = 0;
            $('.slt-service input[type="checkbox"]:checked').each(function () {
                time = parseInt($(this).data('time')) + time;
            });
            $('#total-time').val(time);
            $('#total-price').val(sum);
            sum = '$' + sum;
            $('.service-total-amt').text(sum);
            parent.toggleClass('btn-black btn-success');
        })
    }
    var showInfo = function(){
        $('body').on('click', '.service-info-btn', function () {
            var info=$(this).attr('data-info');           
            if(info!=''){ 
                var newInfo='<p class="service-info">'+info+'</p>';
                $('#slot-box .modal-body').html(newInfo);
                $('#slot-box').modal('show'); 
                
            }           
        })        
    }
    return {
        init: function () {
            serviceFormValidate();
            showInfo();
        }
    };
}();
$(document).ready(function () {
    Common.init();
    $("#js-appointment").delegate(".cancel_app_user", "click", function (e) {
        e.preventDefault();
        var app_id = $(this).attr("id");
        var app_time = new Date($(this).attr("rel"));
        app_time.setHours(app_time.getHours() - 2);
        var curr_time = new Date();
        var app_str_time = app_time.getTime() / 1000;
        var curr_str_time = curr_time.getTime() / 1000;
        if (curr_str_time >= app_str_time) {
            toastr['error']("In order to cancel your appointment, you will need to call the Barber Shop.");
            return false;
        } else {
            var conf = confirm("Do you really want to cancel the Appointment?");
            if (conf) {
                window.location.href = SITE_URL + 'barbers/cancel_appointment/' + app_id;
            }
        }
    });

    if ($("#setBarberId").val() != "") {
        $("#calender_box").show();
        $("#reserv_blk").not('.lunch-body').show();
        $("#barber_list").hide();
        $('body').removeClass("custom-calendar");
    } else {
        $("#calender_box").hide();
        $("#reserv_blk").hide();
    }



    $(".book_apt").click(function () {        
        var barberId = $(this).attr('id');
        var scheduleId = $(this).attr('rel');
        $("#setBarberId").val(barberId);
        $("#setScheduleId").val(scheduleId);
        var myDate = $('#setDefaultDate').val();
        window.location.href = SITE_URL + 'barbers/calendar/' + barberId + '/' + scheduleId + '/' + myDate;
    });

    /* var getBarberScheduleAjax = function (slide) {
     var barberId = $('.slide-' + slide).data('id');
     var scheduleId = $('.slide-' + slide).data('schedule');
     var myDate = $('#setDefaultDate').val();
     
     $.ajax({
     url: SITE_URL + 'ajax/get_barber_schedule/' + barberId + '/' + scheduleId + '/' + myDate,
     type: 'GET',
     beforeSend: function () {
     $.blockUI();
     },
     success: function (data) {
     $('#js-appointment').html(data);
     $.unblockUI();
     }
     })
     } */
}); 