var Project = function() {

    var handleLogin = function() {

        $('.earning-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                'data[Earning][room_id]': {
                    required: true
                },
                'data[Earning][user_id]': {
                    required: true
                },
                'data[Earning][role_id]': {
                    required: true
                },
                'data[Earning][earnings]': {
                    required: true,
                    number: true
                },
                'data[Earning][bill_number]': {
                    required: true, 
                    number: true
                }
            },

            messages: {
                'data[Earning][room_id]': {
                    required: 'Please select Room.'
                },
                'data[Earning][user_id]': {
                    required: 'Please select user.'
                },
                'data[Earning][role_id]': {
                    required: 'Please select type.'
                },
                'data[Earning][earnings]': {
                    required: 'Please enter earnings.',
                    number:'Please enter a valid earnings.'
                },
                'data[Earning][bill_number]': {
                    required: 'Please enter bill number.',  
                    number:'Please enter a valid bill number.'
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

        $('.project-form input').keypress(function(e) {
            if (e.which == 13) {
                if ($('.project-form').validate().form()) {
                    $('.project-form').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }
 var delete_record=function(){
     $('body').on('click','.delete-record',function(){
         $this=$(this);
          bootbox.confirm("Are you want to sure?", function(result) {
                     
                 if (result){
                    window.location.href=$this.attr('href');
                    } 
                });
             return  false;
     })
 }
 var publish_record=function(){
     $('body').on('click','.publish-record',function(){
         $this=$(this);
          bootbox.confirm("Once project is publish then admin and user no allowed to update/upload/tag.", function(result) {
                     
                 if (result){
                    window.location.href=$this.attr('href');
                    } 
                });
             return  false;
     })
 }
 var project_pages_valid = function() {

        $('.project-page-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                
                'data[ProjectPage][name]': {
                    required: true
                }
            },

            messages: {
                
                'data[ProjectPage][name]': {
                    required: "Name is required."
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

        $('.project-page-form input').keypress(function(e) {
            if (e.which == 13) {
                if ($('.project-page-form').validate().form()) {
                    $('.project-page-form').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }
 var get_project_pages=function(){
         $('body').on('click','.versions-link',function(){
             $this=$(this); 
            var tableContainer=$('.table-container');
             var link=$('.versions-link');
             if($this.find('i').hasClass('fa-plus')){
                 link.find('i').removeClass('fa-minus').addClass('fa-plus');
                 link.parents('tr').next('.versions-detail-body').remove();
                 $this.find('i').removeClass('fa-plus').addClass('fa-minus');
                 
                 
                 $.ajax({
                     url:path+prefix+'/project_pages/version_list',
                     data:{parent:$this.attr('data-parent'),id:$this.attr('data-click')},
                     type:'POST',
                     beforeSend:function(){
                      Metronic.blockUI({
                                message: 'Loading',
                                target: tableContainer,
                                overlayColor: 'none',
                                cenrerY: true,
                                boxed: true
                            });
                     },
                     success:function(data){
                         Metronic.unblockUI(tableContainer);
                      $('<tr class="details versions-detail-body"><td class="details" colspan="6">'+data+'</td></tr>').insertAfter($this.parents('tr'));   
                     }
                })
             }else{
                  $this.find('i').removeClass('fa-minus').addClass('fa-plus');
                  $this.parents('tr').next('.versions-detail-body').remove();
             }
             
         })
 }
 var add_page_version=function(){
         $('body').on('click','.add-new-version',function(){
             $this=$(this); 
             var tableContainer=$('.table-responsive');            
             $.ajax({
                     url:path+prefix+'/project_pages/add_version/'+$this.attr('data-parent')+'/'+$this.attr('data-click'),
                     beforeSend:function(){
                      Metronic.blockUI({
                                message: 'Loading',
                                target: tableContainer,
                                overlayColor: 'none',
                                cenrerY: true,
                                boxed: true
                            });
                     },
                     success:function(data){
                         Metronic.unblockUI(tableContainer);
                      tableContainer.html(data);  
                     }
                })
             
         })
 }
  var edit_page_version=function(){
         $('body').on('click','.edit-version-link',function(){
             $this=$(this); 
             var tableContainer=$('.table-responsive');            
             $.ajax({
                     url:$this.attr('href'),
                     beforeSend:function(){
                      Metronic.blockUI({
                                message: 'Loading',
                                target: tableContainer,
                                overlayColor: 'none',
                                cenrerY: true,
                                boxed: true
                            });
                     },
                     success:function(data){
                         Metronic.unblockUI(tableContainer);
                      tableContainer.html(data);  
                     }
                })
            return false;   
         })
 }
 var cancel_version=function(){
         $('body').on('click','.cancel-version',function(){
             $this=$(this); 
            var tableContainer=$('.table-container'); 
                 $.ajax({
                     url:path+prefix+'/project_pages/version_list',
                     data:{parent:$this.attr('data-parent'),id:$this.attr('data-click')},
                     type:'POST',
                     beforeSend:function(){
                      Metronic.blockUI({
                                message: 'Loading',
                                target: tableContainer,
                                overlayColor: 'none',
                                cenrerY: true,
                                boxed: true
                            });
                     },
                     success:function(data){
                         Metronic.unblockUI(tableContainer);
                         var data_body=$this.parents('tr').prev();
                         $this.parents('tr').remove();
                      $('<tr class="details versions-detail-body"><td class="details" colspan="6">'+data+'</td></tr>').insertAfter(data_body);   
                     }
                })
             
             
         })
 }
 var view_version=function(){   
  $('body').on('click','.close-version-view',function(){            
           $('#ajax-version-view .modal-content').html(''); 
         })
 }
    return {
        //main function to initiate the module
        init: function() {
            handleLogin();
            delete_record();
            get_project_pages();
            project_pages_valid();
            add_page_version();
            edit_page_version();
            view_version();
            cancel_version();
            publish_record();
           
        }
    };

}();