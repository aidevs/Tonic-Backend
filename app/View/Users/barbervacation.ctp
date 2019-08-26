
<div class="custom-calarea">
    <div style="background-color:#fff" class="customtabnew clearfix">
		<div class="custominnernew">		
            <a href="<?php echo SITE_URL; ?>users/barberschedule" class="btn btn-default">Schedule</a>
            <a href="<?php echo SITE_URL; ?>users/barbervacation" class="btn btn-success">Vacations</a>
        </div>
	</div>
    <div class="calenderPage mt60">
        <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'my_account')); ?>" class="homeBtn"></a><br>
        <!------------------------code---------------start---------------->
        <div class="row" style="margin-top: 25px;">	
            <div id="vacation_list" class="col-lg-12 custom-vacation-list clearfix">
				<?php
				$j = 0;
				if (!empty($vacations)) {
					foreach ($vacations as $vacation) { 
						?>
						<div class="barbarschedulecustombox" style="background-color:#fff; padding:0; margin-bottom:15px;">
						  <div class="container selectdatecustom selectdatecustom2">	
                            <div class="heading newcustomheader text-center" style="padding: 10px 10px 6px 10px;"> Vacation<span class="pull-right"><i class="fa fa-edit vacation_edit" rel="<?php echo $vacation['Vacation']['id']; ?>" style="position: absolute; right: 20px; font-size: 16px; vertical-align: middle; cursor: pointer; color: rgb(255, 255, 255); display: block;"></i></span></div>
							<div id="vac_block<?php echo $vacation['Vacation']['id']; ?>" class="container selectdatecustom selectdatecustom2">
								<input type="hidden" class="vacation-id" name="data[vacation][id]" value="<?php echo $vacation['Vacation']['id']; ?>" />
								<div  class="col-xs-6 text-center">
									<label class="newcustomdate">From</label>
                                                                        <input disabled type="text" autocomplete="off" name="data[vacation][from_date]" class="timepicker datetimepicker text-center border3 inputcustomnew edittimepicker timepicker_from" value="<?php echo date('m/d/y h:i A', strtotime($vacation['Vacation']['from_date']));?>" placeholder="From Date"  data-alt-input=true rel="<?php echo $vacation['Vacation']['id'] ?>" /> 
								</div>
								
								<div  class="col-xs-6 text-center">
									<label class="newcustomdate">Until</label>
									<input disabled type="text" name="data[vacation][to_date]" autocomplete="off" class="timepicker datetimepicker text-center border3 inputcustomnew edittimepicker timepicker_to" value="<?php echo date('m/d/y h:i A', strtotime($vacation['Vacation']['to_date']));?>" placeholder="Until" data-alt-input=true rel="<?php echo $vacation['Vacation']['id'] ?>" />
								</div>
							</div>
							<div class="container selectdatecustom selectdatecustom2">
								<div class="col-xs-12 text-center">
									<a class="vacation_del" rel="<?php echo $vacation['Vacation']['id']; ?>" href=""><i class="fa fa-trash"></i></a>
									<a style="display:none;" class="vacation_update vacation_edit_save btn btn-success custom" rel="<?php echo $vacation['Vacation']['id']; ?>" href="">Save</a>
									<a style="display:none;" class="vacation_edit_save vacation_edit_cancel btn btn-danger custom" rel="<?php echo $vacation['Vacation']['id']; ?>" href="">Cancel</a>
								</div>
							</div>
                          </div>  
						</div>
					<?php }?>	
				<?php } ?>
			</div>
        </div>
		<div class="footerareanew ">
			<a data-toggle="modal" data-target="#new_vacation_modal" class="btn btn-success" href="#"> <span class="hidden-480">Add Vacation</span> </a>
		</div>
    </div>
</div>


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

<div class="modal fade" id="new_vacation_modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="slot-boxLabel">
	<div class="modal-dialog custom" style="background:#fff">
		<div class="modal-content">
		<form id="vacation_frm" type="POST" name="Vacation">
		<div class="modal-header custamnew heading newcustomheader text-center">
			<button aria-hidden="true" class="close" data-dismiss="modal" type="button">&times;</button>
			<h4 class="modal-title">New Vacation</h4>
		</div>
		<div class="modal-body modalbodycustomnew">
        <div class="container selectdatecustom selectdatecustom2">
            <div class="col-xs-6 text-center">
                <label class="newcustomdate">From</label>
                <?php echo $this->Form->input("Vacation.from_date",array("class" => "datetimepicker text-center border3 inputcustomnew","label"=>false, "id" => "from_date","type" => "text")); ?>
            </div>
            <div class="col-xs-6 text-center">
                <label class="newcustomdate">Until</label>
                <?php echo $this->Form->input("Vacation.to_date",array("class" => "datetimepicker text-center border3 inputcustomnew","label"=>false, "id" => "to_date","type" => "text")); ?>
            </div>
		</div>
        </div> 
		<div class="modal-footer modalcustomnew">
			<button id="vacation_save" class="custom1 btn btn-success custom">create</button>
		</div>
		</form>
		</div>
    </div> 
</div>


 

<script>
(function($){
    $('#from_date').bootstrapMaterialDatePicker({format : 'MM/DD/YY hh:mm A' }).on('change', function(e, date){
      $('#to_date').bootstrapMaterialDatePicker('setMinDate', date);
                            var startDate = date;
                            var endDate = new Date($("#to_date").val());
                            if(startDate >= endDate){                                    
                                    $("#to_date").val('');
                                    return false;
                            }
    });
    $('#to_date').bootstrapMaterialDatePicker({format : 'MM/DD/YY hh:mm A' }).on('change', function(e, date){
                            var startDate = new Date($("#from_date").val());
                            var endDate = new Date($("#to_date").val());
                            if(startDate >= endDate){
                                    toastr['error']("From date should be less then until date");
                                    $("#to_date").val('');

                                    return false;
                            }
    });
    
    $('.timepicker_from').bootstrapMaterialDatePicker({format : 'MM/DD/YY hh:mm A' }).on('change', function(e, date){
      $('.timepicker_to').bootstrapMaterialDatePicker('setMinDate', date);
                            var startDate = date;
                            var endDate = new Date($(".timepicker_to").val());
                            if(startDate >= endDate){                                    
                                    $(".timepicker_to").val('');
                                    return false;
                            }
    });
    $('.timepicker_to').bootstrapMaterialDatePicker({format : 'MM/DD/YY hh:mm A' }).on('change', function(e, date){
                            var startDate = new Date($(".timepicker_from").val());
                            var endDate = new Date($(".timepicker_to").val());
                            if(startDate >= endDate){
                                    toastr['error']("From date should be less then until date");
                                    $(".timepicker_to").val('');

                                    return false;
                            }
    });
	var startDate;
	
	/*flatpickr("#from_date", {
		enableTime: true,
		altInput: true,
		minDate: new Date(),
		altFormat: "m/d/Y h:i K",
		onClose: 	function(selectedDates, dateStr, instance){
			$("#to_date").val('');
			
			document.getElementById("to_date").flatpickr({
				minDate: new Date(selectedDates),
				enableTime: true,
				altInput: true,
				altFormat: "m/d/Y h:i K",
			});
			//$("#to_date").flatpickr("option", "minDate", dp);
		}
	});
	
	flatpickr("#to_date", {
		enableTime: true,
		altInput: true,
		minDate: $("#from_date").val(),
		altFormat: "m/d/Y h:i K",
		onClose: 	function(current_time, $input){
			var startDate = new Date($("#from_date").val());
			var endDate = new Date($("#to_date").val());

			if(startDate >= endDate){
				console.log( 'startDate >= endDate' )
				toastr['error']("From date should be less then until date");
				$("#to_date").val('');

				return false;
			}else if( startDate < endDate ){
			}	
		}
	});
	 $('#from_date').datetimepicker({
		timeFormat: 'hh:mm TT',
		dateFormat: "mm/dd/yy",
	 	minDate: 0,
     	onClose: 	function(dp,$input){
     							$("#to_date").val('');
                               $("#to_date").datetimepicker("option", "minDate", dp);
                           }
	});
	$('#to_date').datetimepicker({
		timeFormat: 'hh:mm TT',
		dateFormat: "mm/dd/yy",
		minDate: $("#from_date").val(),
		 onClose: 	function(current_time, $input){
            			var startDate = new Date($("#from_date").val());
            			var endDate = new Date($("#to_date").val());

                		if(startDate >= endDate){
                			console.log( 'startDate >= endDate' )
                			toastr['error']("From date should be less then until date");
                			$("#to_date").val('');

                			return false;
                		}else if( startDate < endDate ){
                		}	
                 	}
	});


	flatpickr(".edittimepicker", {
		enableTime: true,
		altInput: true,
		minDate: new Date(),
		//altFormat: "h",
		//altFormat: "m/d/Y h:i K",
	});*/


	/* $('.edittimepicker').datetimepicker({
		timeFormat: 'hh:mm TT',
		dateFormat: "mm/dd/yy",
	 	minDate: 0,
     	onClose: 	function(dp,$input){
     							
                           }
	});  */
	



	$(window).on("load",function(){
		$("#content-1").mCustomScrollbar({
			//theme:"minimal"
		});			
		$("#barber_list").mCustomScrollbar({
			//theme:"minimal"
		});			
	});
	$("#vacation_frm")[0].reset();
	$("#vacation_save").click(function(e){
		e.preventDefault();
		var error_msg = "";
		if($("#from_date").val() == ""){
			error_msg += "From date is required.<br/>";
		}
		
		if($("#to_date").val() == ""){
			error_msg += "Until date is required.<br/>";
		}
		
		if(error_msg != ""){
			toastr['error'](error_msg);
			return false;
		}
		else {
			$.ajax({
				url: SITE_URL + 'ajax/user_barber_add_vacation',
				type: 'POST',
				data: $("#vacation_frm").serializeArray(),
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
	});
	
	$(".vacation_del").click(function(e){
		e.preventDefault();
		var vacation_id = $(this).attr("rel");
		var confirm_msg = confirm("Are you sure want to delete ?");
		if(confirm_msg){
			$.ajax({
				url: SITE_URL + 'ajax/user_barber_delete_vacation/'+vacation_id,
				type: 'POST',
				//data: {vacation_id : vacation_id},
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
			});
		}
		else {
			return false;
		}
	})
	
	$(".timepicker").each(function(){
		$(this).removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});
	
	$(".vacation_edit").click(function(){
		var vac_id = $(this).attr('rel');
		$(".timepicker").each(function(){
			$(this).removeAttr("disabled");
			//$(this).attr("disabled","disabled");
		});
		
		var vacation_from = $("#vac_block"+vac_id).find("input[name='data[vacation][from_date]']").val();
		var vacation_to = $("#vac_block"+vac_id).find("input[name='data[vacation][to_date]']").val();
		
		$(".vacation_edit_save").each(function(){
			if($(this).attr("rel") != vac_id){
				$(this).hide();
			}
			else {
				$(this).show();
			}
		});
		
		
		
		$(".vacation_edit_cancel").click(function(e){
			e.preventDefault();
			$("#vac_block"+vac_id).find("input[name='data[vacation][from_date]']").val(vacation_from);
			$("#vac_block"+vac_id).find("input[name='data[vacation][to_date]']").val(vacation_to);
			$(".vacation_edit_save").hide();
			$(".vacation_del").show();
			$(".vacation_edit").show();
			$(".timepicker").each(function(){
				$(this).removeAttr("disabled");
				$(this).attr("disabled","disabled");
			});
		});
		
		$(".vacation_edit").each(function(){
			$(this).hide();
		});
		
		$(".vacation_del").each(function(){
			if($(this).attr("rel") != vac_id){
				$(this).show();
			}
			else {
				$(this).hide();
			}
		});
		$("input[rel='"+vac_id+"']").removeAttr("disabled");
	});
	
	$(".vacation_update").click(function(e){
		e.preventDefault();
		
		var vacation_id = $(this).attr('rel');
		var vacation_from = $("#vac_block"+vacation_id).find("input[name='data[vacation][from_date]']").val();
		var vacation_to = $("#vac_block"+vacation_id).find("input[name='data[vacation][to_date]']").val();
		var error_msg = "";
		if($("#vac_block"+vacation_id).find("input[name='data[vacation][from_date]']").val() == ""){
			error_msg += "From date is required.<br/>";
		}
		
		if($("#vac_block"+vacation_id).find("input[name='data[vacation][to_date]']").val() == ""){
			error_msg += "Until date is required.<br/>";
		}
		
		if(error_msg != ""){
			toastr['error'](error_msg);
			return false;
		}
		else {
			$.ajax({
				url: SITE_URL + 'ajax/edit_user_barber_add_vacation',
				type: 'POST',
				data: {vacation_id:vacation_id, vacation_from: vacation_from, vacation_to: vacation_to},
				beforeSend: function () {
				},
				success: function (data) {
				var res = JSON.parse(data);
					if (res.error == 1) {
						toastr['error'](res.msg);
					} else {
						toastr['success'](res.msg);
						$(".vacation_edit_save").hide();
						$(".vacation_del").show();
						$(".vacation_edit").show();
						$(".timepicker").each(function(){
							$(this).removeAttr("disabled");
							$(this).attr("disabled","disabled");
						});
					}
				}
			})
		}
		
	});
	
})(jQuery);
</script>