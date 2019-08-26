var ComponentsPickers = function () {

    var handleDate = function() {

        $('.expire-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                'expire_date': {
                    required: true
                }
            },

            messages: {
                'expire_date': {
                    required: "Please select date range."
                }
            },

            invalidHandler: function(event, validator) { //display error alert on form submit   
                //$('.alert-danger', $('.login-form')).show();
            },

            highlight: function(element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },

            errorPlacement: function(error, element) {
                error.insertAfter(element);
            },

            submitHandler: function(form) {
                form.submit(); // form validation success, call ajax form submit
            }
        });

        $('.expire-form input').keypress(function(e) {
            if (e.which == 13) {
                if ($('.expire-form').validate().form()) {
                    $('.expire-form').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

   

    var handleDateRangePickers = function () {
        if (!jQuery().daterangepicker) {
            return;
        }

        $('#defaultrange').daterangepicker({
                opens: (Metronic.isRTL() ? 'left' : 'right'),
                format: 'DD/MM/YYYY',
                separator: ' to ',
                startDate: moment(),
                endDate: moment().add('days', 29),
                minDate: moment(),
                
            },
            function (start, end) {
                $('#defaultrange input').val(start.format('YYYY-MM-DD') + ' To ' + end.format('YYYY-MM-DD'));
            }
        );        

       
    }

    return {
        //main function to initiate the module
        init: function () {
            handleDate();
            handleDateRangePickers();
        }
    };

}();