jQuery.validator.addMethod("pattern", function (value, element, param) {
    if (this.optional(element)) {
        return true;
    }
    if (typeof param === 'string') {
        param = new RegExp('^(?:' + param + ')$');
    }
    return param.test(value);
}, "Invalid format.");

var Common = function () {
    var handleSelect2 = function () {
        if ($().select2) {
            $('.select2me').select2({
                placeholder: "Select",
                allowClear: true
            });
        }
    };
    return {
        handleSelect2:handleSelect2
    }
}();
/*
 * Account Setting Class
 */
var AccountSetting = function () {
    var resetFormValidate = function () {
        $('.user_reset_password').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
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
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element.closest('.input-icon'));
            }
        });
    }
    var changeFormValidate = function () {
        $('.user_change_password_form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input 
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
                    remote: {url: SITE_URL + 'ajax/check_password', type: 'POST', data: {field: 'old_password'}}

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
                    required: 'Please enter current password.',
                    remote: 'Current password is wrong.'
                }
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
        });
    }
    var editFormValidate = function () {
        $('.user_edit_profile_form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input 
            rules: {
                'data[User][first_name]': {
                    required: true
                },
                'data[User][last_name]': {
                    required: true
                },
                'data[User][window_hours]': {
                    required: true,
                    number: true,
                    min: 1,
                    pattern: '^[0-9]+$'

                }
            }, messages: {
                'data[User][first_name]': {
                    required: 'Please enter first name.'
                },
                'data[User][last_name]': {
                    required: 'Please enter last name.'
                },
                'data[User][window_hours]': {
                    required: 'Please enter window hours.',
                    pattern: 'Please enter valid window hours'
                }
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
        });
    }
    return {
        init: function () {
            editFormValidate();
            resetFormValidate();
            changeFormValidate();
        }
    };
}();
/*
 * User Class
 */
var Users = function () {
    var userFormValidate = function () {
        $('.user-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input 
            rules: {
                'data[User][first_name]': {
                    required: true
                },
                'data[User][last_name]': {
                    required: false
                },
                'data[User][email]': {
                    required: true,
                    remote: {url: SITE_URL + 'ajax/check_field', type: 'POST', data: {field: 'email'}}
                },
                'data[User][shop_name]': {
                    required: true,
                    remote: {url: SITE_URL + 'ajax/check_field', type: 'POST', data: {field: 'shop_name'}}
                },
                'data[User][shop_description]': {
                    required: true
                },
                'data[User][specialty]': {
                    required: true
                },
                'data[User][country_id]': {
                    required: true
                },
                'data[User][phone]': {
                    required: true,
                    pattern: /^[\d\s]+$/,
                    maxlength: 15,
                },
                'data[User][address]': {
                    required: true
                },
                'data[User][image]': {
                    extension: "jpg|jpeg|png"
                }, 'data[User][pin]': {
                    required: true
                }
            }, messages: {
                'data[User][first_name]': {
                    required: 'Please enter first name.'
                },
                'data[User][last_name]': {
                    required: 'Please enter last name.'
                },
                'data[User][email]': {
                    required: 'Please enter email address.',
                    remote: 'Email address already exist.'
                },
                'data[User][shop_name]': {
                    required: 'Please enter shop name.',
                    remote: 'Shop name already exist.'
                },
                'data[User][shop_description]': {
                    required: 'Please enter shop description.'
                },
                'data[User][specialty]': {
                    required: 'Please enter specialty.'
                },
                'data[User][country_id]': {
                    required: 'Please select country.'
                },
                'data[User][phone]': {
                    required: 'Please enter phone.',
                    pattern: 'Please enter valid phone.',
                    maxlength: 'Phone is no more than 15 digits.'
                },
                'data[User][address]': {
                    required: 'Please enter address.'
                },
                'data[User][image]': {
                    extension: 'Please select jpg, jpeg, png image.'
                }, 'data[User][pin]': {
                    required: 'Please enter Pin.'
                }
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            errorPlacement: function (error, element) {
                if (element.is('input[type="file"]')) {
                    error.insertAfter(element.parents('.body-img'));
                } else {
                    error.insertAfter(element);
                }
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
        });
    }
    return {
        init: function () {
            userFormValidate();
        }
    };
}();
/*
 * Schedule  Class
 */
var Schedule = function () {
    var oTable;
    var table;
    var nEditing = null;
    var nNew = false;
    var setTimePicker = function () {
        $('.timepicker-no-seconds').timepicker({
            autoclose: true,
            minuteStep: 15
        });
    }
    var scheduleFormValidate = function () {
        $('.schedule-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input 
            rules: {
                'data[Schedule][start_time]': {
                    required: true
                },
                'data[Schedule][end_time]': {
                    required: true
                },
                'data[Schedule][days][]': {
                    required: true,
                    minlength: 1
                }
            }, messages: {
                'data[Schedule][start_time]': {
                    required: 'Please enter start time.'
                },
                'data[Schedule][end_time]': {
                    required: 'Please enter end time.'
                },
                'data[Schedule][days][]': {
                    required: 'Please select days.'
                }
            },
            errorPlacement: function (error, element) {
                if (element.parents('.days-body').is('div')) {
                    error.attr('id', 'days-error')
                    error.insertAfter(element.parents('.days-body'));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
        });

    }
    var setSlotActive = function () {
        if ($('.slot-time-body input:checked').is('input')) {
            $('.slot-time-body input:checked').parent('label').addClass('active');
        } else {
            $('.slot-time-body input:first').click();
        }
    }
    var setStatus = function () {
        $('body').on('switchChange.bootstrapSwitch', '#working', function (event, state) {
            var userId = $(this).data('user');
            var scheduleId = $(this).data('schedule');
            var weekId = $(this).data('week');
            var data = {'user_id': userId, 'schedule_id': scheduleId, 'week_id': weekId, 'status': state};
            $.ajax({
                url: SITE_URL + 'ajax/set_working',
                type: 'POST',
                data: data,
                beforeSend: function () {
                    Metronic.blockUI({
                        message: 'Please wait..',
                        target: $('.table-container'),
                        overlayColor: 'grey',
                        cenrerY: true,
                        boxed: true
                    });
                },
                success: function (data) {
                    Metronic.unblockUI($('.table-container'));

                }
            })
        })
    }
    var reSchedule = function () {
        table = $('.table-schedule');
        oTable = table.dataTable({
            bSort: false,
            bFilter: false,
            bInfo: false,
            bPaginate: false,
            bLengthChange: false
        });
        $('body').on('click', '.reschedule-btn', function (e) {
            e.preventDefault();
            /* Get the row as a parent of the link that was clicked on */
            var nRow = $(this).parents('tr')[0];

            if (nEditing !== null && nEditing != nRow) {
                /* Currently editing - but not this row - restore the old before continuing to edit mode */
                restoreRow(nEditing);
                editRow(nRow);
                nEditing = nRow;
            } else {
                /* No edit in progress - let's start one */
                editRow(nRow);
                nEditing = nRow;
            }
        })
    }
    var cancelReSchedule = function () {
        $('body').on('click', '.cancel-schedule', function () {
            restoreRow(nEditing);
            nEditing = null;
        })
    }
    var saveSchedule = function () {
        $('body').on('click', '.save-schedule', function () {
            if ($('input[name="data[Schedule][start_time]"]').val() == '' || $('input[name="data[Schedule][end_time]"]').val() == '') {
                Metronic.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: 'Please select valid time.',
                    container: $('#form_wizard_1'),
                    place: 'prepend'
                });
                return false;
            } else {
                $.ajax({
                    url: SITE_URL + 'ajax/change_schedule',
                    type: 'POST',
                    data: $('input,select', nEditing).serializeArray(),
                    beforeSend: function () {
                        Metronic.blockUI({
                            message: 'Please wait..',
                            target: $('.table-container'),
                            overlayColor: 'grey',
                            cenrerY: true,
                            boxed: true
                        });
                    },
                    success: function (reslt) {
                        var res = JSON.parse(reslt);
                        if (res.error == 1) {
                            Metronic.unblockUI($('.table-container'));
                            Metronic.alert({
                                type: 'danger',
                                icon: 'warning',
                                message: res.msg,
                                container: $('#form_wizard_1'),
                                place: 'prepend'
                            });
                        } else {
                            window.location.reload();
                        }
                    }
                })
            }
        })
    }
    var editRow = function (nRow) {
        var aData = oTable.fnGetData(nRow);
        var jqTds = $('>td', nRow);
        if ($(nRow).find('input[name="data[working]"]').is('input')) {
            jqTds[1].innerHTML = '<input type="text" name="data[Schedule][start_time]" class="form-control timepicker-no-seconds" value="' + aData[1] + '">';
            jqTds[2].innerHTML = '<input type="text" name="data[Schedule][end_time]" class="form-control timepicker-no-seconds" value="' + aData[2] + '">';
            //jqTds[3].innerHTML = '<select  name="data[Schedule][slot_time]" class="form-control"><option  value="15">15 Minute</option><option  value="30">30 Minute</option><option value="45">45 Minute</option><option value="60">1 Hour</option></select>';
            $('select[name="data[Schedule][slot_time]"] option[value="' + parseInt(aData[3]) + '"]').attr('selected', 'selected')
        } else {
            jqTds[1].innerHTML = '<input type="text" name="data[Schedule][start_time]" class="form-control timepicker-no-seconds" value="">';
            jqTds[2].innerHTML = '<input type="text" name="data[Schedule][end_time]" class="form-control timepicker-no-seconds" value="">';
            //jqTds[3].innerHTML = '<select name="data[Schedule][slot_time]" class="form-control"><option  value="15">15 Minute</option><option value="30">30 Minute</option><option value="45">45 Minute</option><option value="60">1 Hour</option></select>';
            
            jqTds[3].innerHTML = '<select name="data[Schedule][working]" class="form-control"><option value="1">Yes</option><option value="0">No</option></select>';
        }
        jqTds[4].innerHTML = '<button class="btn  btn-sm blue save-schedule"><i class="fa fa-check"></i> Submit</button><button class="btn  btn-sm red cancel-schedule"> <i class="fa fa-close"></i> Cancel </button>';
        setTimePicker();
    }
    var restoreRow = function (nRow) {
        var aData = oTable.fnGetData(nRow);
        var jqTds = $('>td', nRow);

        for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
            oTable.fnUpdate(aData[i], nRow, i, false);
        }

        oTable.fnDraw();
    }
    var checkBreak = function () {
        $('body').on('change', 'input[name="data[LunchBreak][slot_id][]"]', function () {
            /* if ($('input[name="data[LunchBreak][slot_id][]"]:checked').length) {
             $('.lunch-submit').removeClass('disabled');
             } else {
             $('.lunch-submit').addClass('disabled');
             } */
            $('.lunch-submit').removeClass('disabled');
        })
    }
    return {
        init: function () {
            setTimePicker();
            scheduleFormValidate();
            setSlotActive();
            setStatus();
            reSchedule();
            cancelReSchedule();
            saveSchedule();
            checkBreak();
        }
    };
}();
/*
 * Setting  Class
 */
var Setting = function () {
    var settingFormValidate = function () {
        $('.setting-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input 
            rules: {
                'data[Setting][0][value]': {
                    required: true
                },
                'data[Setting][1][value]': {
                    required: true,
                    number: true,
                    min: 1,
                    pattern: '^[0-9]+$'
                }
            }, messages: {
                'data[Setting][0][value]': {
                    required: 'Please enter site title.'
                },
                'data[Setting][1][value]': {
                    required: 'Please enter schedule Hour.',
                    pattern: 'Please enter valid schedule Hour.'
                }
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element);
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
        });
    }
    return {
        init: function () {
            settingFormValidate();
        }
    };
}();
/*
 * Barber  Class
 */
var Barber = function () {
    var addVacationFormValidate = function () {
        $('.vacation-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input 
            rules: {
                'data[Vacation][from_date]': {
                    required: true
                },
                'data[Vacation][to_date]': {
                    required: true
                }
            }, messages: {
                'data[Vacation][from_date]': {
                    required: 'Please enter from date.'
                },
                'data[Vacation][to_date]': {
                    required: 'Please enter to date.'
                }
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element);
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
        });
    }
    var addMoreDate = function () {
        $('body').on('click', '.add-more-date', function () {
            var parent = $(this).parents('.form-group');
            var clone = parent.clone();
            $('input', clone).val('');
            $('.help-block', clone).remove();
            $(clone).removeClass('has-error');
            $('.btn-body a.add-more-date', parent).remove();
            $('.btn-body', parent).html('<a href="javascript:;" class="btn btn-danger remove-more-date">Remove</a>');
            clone.insertAfter(parent);
            setDatePicker();
        })
    }
    var removeMoreDate = function () {
        $('body').on('click', '.remove-more-date', function () {
            $(this).parents('.form-group').remove();
        });
    }
    var setDatePicker = function () {

        var FromEndDate = new Date();
        var ToEndDate = new Date();

        ToEndDate.setDate(ToEndDate.getDate() + 365);

        //$(".vacation-date-from").datetimepicker();

        $(".vacation-date-from").datetimepicker({
            format: 'mm/dd/yyyy h:i',
            todayHighlight: true,
            "startDate": new Date(),
            autoclose: true,
            minuteStep:15,
        }).on('changeDate', function (selected) {
            var startDate = new Date(selected.delegateTarget.value);
            $('.vacation-date-to').datetimepicker('setStartDate', startDate);
        }).on('clearDate', function (selected) {
            $('.vacation-date-to').datetimepicker('setStartDate', null);
        });

        $(".vacation-date-to").datetimepicker({
            format: 'mm/dd/yyyy h:i',
            todayHighlight: true,
            autoclose: true,
            minuteStep:15,
        }).on('changeDate', function (selected) {
            var endDate = new Date(selected.delegateTarget.value);
            $('.vacation-date-from').datetimepicker('setEndDate', endDate);
        }).on('clearDate', function (selected) {
            $('.vacation-date-from').datetimepicker('setEndDate', null);
        });

    }
    var setServices = function () {
        $('body').on('click',"a.barber-service", function () {
            $.ajax({
                url: $(this).attr("href"),
                type: 'GET',
                beforeSend: function () {
                    Metronic.blockUI({
                        message: 'Please wait..',
                        target: $('.table-container'),
                        overlayColor: 'grey',
                        cenrerY: true,
                        boxed: true
                    });
                },
                success: function (data) {                   
                    Metronic.unblockUI($('.table-container'));
                    $('#ajax-version-view .modal-content').html(data);
                    $('#ajax-version-view').modal('show');
                    Common.handleSelect2();
                    validateService();
                    return false;
                }
            })
            return false;
        });
        $('body').on('click', "button.update-service-btn", function () {
            if ($('.barber-service-form').valid()) {
                bootbox.confirm("If barber has any future reservations of the service you are trying to drop/update , then this could lead to lapse those booked reservations. Still do you want to implement this change ?", function (result) {
                    if (result) {
                        postService();
                    }
                });

            }


        });
    }
    var postService = function () {
        $.ajax({
            url: $('.barber-service-form').attr("action"),
            data:$('.barber-service-form').serializeArray(),
            type: 'POST',
            beforeSend: function () {
                Metronic.blockUI({
                    message: 'Please wait..',
                    target: $('.modal-dialog'),
                    overlayColor: 'grey',
                    cenrerY: true,
                    boxed: true
                });
            },
            success: function (data) {
                var res = JSON.parse(data);                
                if (res.error == 1) {
                  Metronic.unblockUI($('.modal-dialog'));  
                  $('.js-error').html(res.msg);
                }else{
                    window.location.reload();
                }
            }
        })
    }
    var validateService = function () {
        $('.barber-service-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input 
            ignore:[],
            rules: {
                'data[BarberService][service_id][]': {
                    required: true
                }
            }, messages: {
                'data[BarberService][service_id][]': {
                    required: 'Please select service.'
                }
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element);
            },
            highlight: function (element) { // hightlight error inputs
                $(element).parents('.form-group').addClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
        });
    }
    return {
        init: function () {
            addVacationFormValidate();
            setDatePicker();
            addMoreDate();
            removeMoreDate();
            setServices();
           
        }
    };
}();

/*
 * Barber Vacation Class
 */
var BarberVacation = function () {
    var setDatePicker = function () {
        $(".vacation-date-from").datetimepicker({
            format: 'mm/dd/yyyy H:ii P',
            todayHighlight: true,
            //"startDate": new Date(),
            autoclose: true,
        }).on('changeDate', function (selected) {
            var startDate = new Date(selected.delegateTarget.value);
            $('.vacation-date-to').datetimepicker('setStartDate', startDate);
        }).on('clearDate', function (selected) {
            $('.vacation-date-to').datetimepicker('setStartDate', null);
        });

        $(".vacation-date-to").datetimepicker({
            format: 'mm/dd/yyyy H:ii P',
            todayHighlight: true,
            //"startDate": new Date(),
            autoclose: true,
        }).on('changeDate', function (selected) {
            var endDate = new Date(selected.delegateTarget.value);
            $('.vacation-date-from').datetimepicker('setEndDate', endDate);
        }).on('clearDate', function (selected) {
            $('.vacation-date-from').datetimepicker('setEndDate', null);
        });

        $(".vacation-date-to").on("blur", function () {
            if ($(this).val() == "") {
                $('.vacation-date-from').datetimepicker('setEndDate', null);
            }
        });

        $(".vacation-date-from").on("blur", function () {
            if ($(this).val() == "") {
                $('.vacation-date-to').datetimepicker('setStartDate', null);
            }
        });
    }
    return {
        init: function () {
            setDatePicker();
        }
    };
}();
/*
 * Customer Class
 */
var Customer = function () {
    $('#slot_time').timepicker({
        autoclose: true,
        minuteStep: 15
    });
    $("#created").datepicker({
        format: 'mm/dd/yyyy',
        todayHighlight: true,
        autoclose: true,
    });
    $('#slot_time').val('');
    var customerFunction = function () {
        //change status popup show
        $("#reservation_list").delegate(".change_status", "click", function () {
            var appointment_id = $(this).attr("rel");
            $.ajax({
                url: SITE_URL + 'ajax/appointmentStatusForm',
                type: 'POST',
                data: {appointment_id: appointment_id},
                beforeSend: function () {
                    Metronic.blockUI({
                        message: 'Please wait..',
                        target: $('.table-container'),
                        overlayColor: 'grey',
                        cenrerY: true,
                        boxed: true
                    });
                },
                success: function (data) {
                    var res = JSON.parse(data);
                    Metronic.unblockUI($('.table-container'));
                    $("#reservationStatusId").val(appointment_id);
                    if (res.status) {
                        $("#reservationStatusStatus").val(res.status);
                    }
                    $('#changeStatus').modal({backdrop: 'static', keyboard: false})
                    return false;
                }
            })
        });

        //Delete reservation record
        $("#reservation_list").delegate(".del_reservation", "click", function () {
            var appointment_id = $(this).attr("rel");
            var el = $(this);
            var conf = confirm("Do you really want to delete this record ?");
            if (conf) {
                $.ajax({
                    url: SITE_URL + 'ajax/deleteReservationRecord',
                    type: 'POST',
                    data: {appointment_id: appointment_id},
                    beforeSend: function () {
                        Metronic.blockUI({
                            message: 'Please wait..',
                            target: $('.table-container'),
                            overlayColor: 'grey',
                            cenrerY: true,
                            boxed: true
                        });
                    },
                    success: function (data) {
                        var res = JSON.parse(data);
                        Metronic.unblockUI($('.table-container'));

                        if (res.error == 0) {
                            Metronic.alert({
                                type: 'success',
                                icon: 'check',
                                message: res.msg,
                                container: $('#form_wizard_1'),
                                place: 'prepend'
                            });
                            el.parent().parent().remove();
                        } else if (res.error == 1) {
                            Metronic.alert({
                                type: 'danger',
                                icon: 'warning',
                                message: res.msg,
                                container: $('#form_wizard_1'),
                                place: 'prepend'
                            });
                        }

                    }
                })
            } else {
                return false;
            }
        });

        //Change reservation Status
        $("#updateStatus").click(function (e) {
            e.preventDefault();
            $.ajax({
                url: SITE_URL + 'ajax/changeAppointmentStatus',
                type: 'POST',
                data: $("#reservationStatusAdminReservationsForm").serializeArray(),
                beforeSend: function () {
                    Metronic.blockUI({
                        message: 'Please wait..',
                        target: $('.table-container'),
                        overlayColor: 'grey',
                        cenrerY: true,
                        boxed: true
                    });
                },
                success: function (data) {
                    var res = JSON.parse(data);
                    $(".close").click();
                    Metronic.unblockUI($('.table-container'));

                    if (res.status) {
                        Metronic.alert({
                            type: 'success',
                            icon: 'check',
                            message: 'Status updated successfully.',
                            container: $('#form_wizard_1'),
                            place: 'prepend'
                        });
                        $("#status" + res.id).attr("class", "");
                        if (res.status == 0) {
                            $("#status" + res.id).attr("class", "label label-sm label-warning");
                            $("#status" + res.id).text("Pending");
                        } else if (res.status == 1) {
                            $("#status" + res.id).attr("class", "label label-sm label-success");
                            $("#status" + res.id).text("Seat");
                        } else if (res.status == 2) {
                            $("#status" + res.id).attr("class", "label label-sm label-danger");
                            $("#status" + res.id).text("Dismiss");
                        }
                    }

                }
            })
        });

    }
    return {
        init: function () {
            customerFunction();
        }
    };
}();
var Service = function(){
    var serviceFormValidate = function () {
        $('.service-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input 
            rules: {
                'data[Service][cost]': {
                     pattern: /^[1-9]\d*(\.\d+)?$/,
                }
            }, messages: {
                'data[Service][cost]': {
                    pattern: 'Please enter valid cost.'
                }
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            errorPlacement: function (error, element) {
                if (element.is('input[type="file"]')) {
                    error.insertAfter(element.parents('.body-img'));
                } else {
                    error.insertAfter(element);
                }
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
        });
    }
    return {
        init: function () {
            serviceFormValidate();
        }
    };
}();

$(".createdate").datepicker({
    format: 'mm/dd/yyyy',
    todayHighlight: true,
    autoclose: true,
});