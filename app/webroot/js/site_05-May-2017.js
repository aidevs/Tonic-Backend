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
    return {
        init: function () {
            loadAlert();
            showSplash();
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
        $(".reslistBlock").swipe({
            swipe: function (event, direction, distance, duration, fingerCount) {
                if (direction == 'up' && $('.reslistBlock').hasClass('down')) {
                    $('.reslistBlock').animate({
                        'bottom': '-320px;'
                    }, 100, function () {
                        $('.reslistBlock').show().animate({
                            'bottom': '0'
                        });
                    });
                    $('.reslistBlock').toggleClass('down up');
                } else if (direction == 'down' && $('.reslistBlock').hasClass('up')) {
                    $('.reslistBlock').animate({
                        'bottom': '0'
                    }, 100, function () {
                        $('.reslistBlock').show().animate({
                            'bottom': '-320px;'
                        });
                    });
                    $('.reslistBlock').toggleClass('up down');
                }
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
						window.location.href = SITE_URL + 'barbers/calendar/'+ barberId + '/'+ scheduleId + '/'+ newDate;
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
		getBarberScheduleAjax(0);
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
			$("#slot_confirm").modal("show");
		});
		$('body').on('click', '#btn_slot_confirm', function () {
			if ($('.book-slot-form').valid()) {
				var activeSlide = $('.itemslide-active').data('number');
                var barberId = $('#setBarberId').val();
                var scheduleId = $('#setScheduleId').val();
                var slotId = $('input[name="slot_id"]:checked').val();
                var date = $('#setDefaultDate').val();
                var data = {'barber_id': barberId, 'schedule_id': scheduleId, 'slot_id': slotId, 'date': date};
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
                        if (obj.error) {
                            toastr['error'](obj.msg);
                        } else {
							$.unblockUI();
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
        /* var scheduleId = $('.slide-' + slide).data('schedule');
		$('#setScheduleId').val(scheduleId); */
		
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
	
	$("#reserv_blk").find(".headingBlock").click(function(){
		if ($('.reslistBlock').hasClass('down')) {
			$('.reslistBlock').animate({
				'bottom': '-210px;'
			}, 100, function () {
				$('.reslistBlock').show().animate({
					'bottom': '0'
				});
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
			
			$('.reslistBlock').toggleClass('up down');
		}
	});
	
	$("#barber_reserv_blk").find(".headingBlock").click(function(){
		if ($('.reslistBlock').hasClass('down')) {
			$('.reslistBlock').animate({
				'bottom': '-320px;'
			}, 100, function () {
				$('.reslistBlock').show().animate({
					'bottom': '0'
				});
			});
			$('.reslistBlock').toggleClass('down up');
		} else if ($('.reslistBlock').hasClass('up')) {
			$('.reslistBlock').animate({
				'bottom': '-320px'
			}, 500/* , function () {
				$('.reslistBlock').show().animate({
					'bottom': '-320px;'
				});
			} */);
			
			$('.reslistBlock').toggleClass('up down');
		}
	});
	
	
	$("#user_reserv_blk").find(".headingBlock").click(function(){
		if ($('.reslistBlock').hasClass('down')) {
			$('.reslistBlock').animate({
				'bottom': '-320px;'
			}, 100, function () {
				$('.reslistBlock').show().animate({
					'bottom': '0'
				});
			});
			$('.reslistBlock').toggleClass('down up');
		} else if ($('.reslistBlock').hasClass('up')) {
			$('.reslistBlock').animate({
				'bottom': '-320px'
			}, 500/* , function () {
				$('.reslistBlock').show().animate({
					'bottom': '-320px;'
				});
			} */);
			
			$('.reslistBlock').toggleClass('up down');
		}
	});
	
	$(function() {
		if (window.history && window.history.pushState) {
			var targetUrl = $(this).attr('href');
			var targetTitle = $(this).attr('title');
			
			window.history.pushState({url: "" + targetUrl + ""}, targetTitle, targetUrl);
			$(window).on('popstate', function() {
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
    return {
        init: function () {
            setbarberCarousel();
            setDatePicker();
            getBarberSchedule();
            getBarberScheduleOnLoad();
            bookSlot();
            setSwipe();
            showNote();
        }
    }
	
}();
/*
 * barber Class
 */
var Barber = function () {
    var setDatePicker = function () {


        var queryDate = $('#setDefaultDate').val(),
                dateParts = queryDate.match(/(\d+)/g)
        var realDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);

        $('.date-picker').datepicker({
            "startDate": new Date(),
            //endDate: '+30d'
        })
                .datepicker('setDate', realDate)
                .on('changeDate', function (ev) {
                    var nwd = new Date(ev.date);
                    var newDate = nwd.getFullYear() + '-' + ("0" + (nwd.getMonth() + 1)).slice(-2) + '-' + nwd.getDate();
//                    alert(newDate);
                    $(".date-picker").off("click");
                    window.location.href = SITE_URL + 'barbers/barbercalendar/' + newDate;

                });
				//}).find('.datepicker-switch').removeClass("datepicker-switch");

    }
    var getBarberScheduleAjax = function (slide) {
        var barberId = $('#barberId').val();
        var scheduleId = $('#scheduleId').val();
        var myDate = $('#setDefaultDate').val();

        $.ajax({
            url: SITE_URL + 'ajax/get_barber_schedule_barber/' + barberId + '/' + scheduleId + '/' + myDate,
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
        $('body').on('click', '#book-slot-btn', function () {
            var html = $('#slot-box-content').html();
            $('#slot-box-content').remove();
            $('#slot-box .modal-body').html(html);
            $('#slot-box').modal('show');
            bookForm = $('.book-slot-form').validate({
                rules: {
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
                        required: true,
                        email: true
                    }
                }, messages: {
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
            if ($('.book-slot-form').valid()) {
                var activeSlide = $('.itemslide-active').data('number');
                var barberId = $('#barberId').val();
                var scheduleId = $('#scheduleId').val();
                var slotId = $('select[name="slot_id"]').val();
				var name = $('input[name="name"]').val();
                var phone = $('input[name="phone"]').val();
                var date = $('#setDefaultDate').val();
                  var email = $('input[name="email"]').val();
                var data = {'user_id': barberId, 'schedule_id': scheduleId, 'slot_id': slotId, 'date': date, 'name': name, 'phone': phone,'email':email};
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
        })
    }

    var setSwipe = function () {
        $(".reslistBlock").swipe({
            swipe: function (event, direction, distance, duration, fingerCount) {
                if (direction == 'up' && $('.reslistBlock').hasClass('down')) {
                    $('.reslistBlock').animate({
                        'bottom': '-320px;'
                    }, 100, function () {
                        $('.reslistBlock').show().animate({
                            'bottom': '0'
                        });
                    });
                    $('.reslistBlock').toggleClass('down up');
                } else if (direction == 'down' && $('.reslistBlock').hasClass('up')) {
                    $('.reslistBlock').animate({
                        'bottom': '0'
                    }, 100, function () {
                        $('.reslistBlock').show().animate({
                            'bottom': '-320px;'
                        });
                    });
                    $('.reslistBlock').toggleClass('up down');
                }
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
$(document).ready(function () {
    Common.init();
	$("#js-appointment").delegate(".cancel_app_user","click", function(e){
		e.preventDefault();
		var app_id = $(this).attr("id");
		var app_time = new Date($(this).attr("rel"));
		app_time.setHours(app_time.getHours() - 2);
		var curr_time = new Date();
		var app_str_time = app_time.getTime()/1000;
		var curr_str_time = curr_time.getTime()/1000;
		if(curr_str_time >= app_str_time) {
			toastr['error']("In order to cancel your appointment, you will need to call the Barber Shop.");
			return false;
		}
		else {
			var conf = confirm("Do you really want to cancel the Appointment?");
			if(conf){
				window.location.href = SITE_URL + 'barbers/cancel_appointment/'+app_id;
			}
		}
	});
	
	if($("#setBarberId").val() != ""){
		$("#calender_box").show();
		$("#reserv_blk").show();
		$("#barber_list").hide();
		$('body').removeClass("custom-calendar");
	}
	else {
		$("#calender_box").hide();
		$("#reserv_blk").hide();
	}
	
	
	
	$(".book_apt").click(function(){
		var barberId = $(this).attr('id');
		var scheduleId = $(this).attr('rel');
		$("#setBarberId").val(barberId);
		$("#setScheduleId").val(scheduleId);
		var myDate = $('#setDefaultDate').val();
		window.location.href = SITE_URL + 'barbers/calendar/'+ barberId + '/'+ scheduleId + '/'+ myDate;
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