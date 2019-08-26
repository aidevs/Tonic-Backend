<?php

//pr($rooms);die;
/*
 * Paging
 */

$iTotalRecords = $this->params['paging']['Vacation']['count'];
//  $iDisplayLength = intval($_REQUEST['length']);
//  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
//  $iDisplayStart = intval($_REQUEST['start']);
$sEcho = intval($_REQUEST['draw']);

$records = array();
$records["data"] = array();

//  $end = $iDisplayStart + $iDisplayLength;
//  $end = $end > $iTotalRecords ? $iTotalRecords : $end; '<a href="'.Router::url(array('admin'=>true,'controller' => 'rooms', 'action' => 'view')).'" class="btn btn-xs blue"><i class="fa fa-search"></i> View</a>'.
//pr($users);
$i = 1;
foreach ($users as $user) {
    $id = $user['User']['id'];
    $status = $user['User']['status'];
    $label = ($status == 1) ? 'success' : 'danger';
    $label_text = ($status == 1) ? 'Enabled' : 'Disabled';
    if(!empty($user['Appointment'])){
     $delete = $this->Html->link('<i class="fa fa-trash-o"></i> <span class="hidden-480">Delete</span>', 'javascript:;', array('class' => 'btn default btn-xs disabled', 'escape' => false));    
    }else{
    $delete = $this->BootForm->postLink('<i class="fa fa-trash-o"></i> <span class="hidden-480">Delete</span>', array('action' => 'delete', $id), array('confirm' => __('Are you sure you want to delete %s?', $user['User']['name']), 'class' => 'btn default btn-xs red', 'escape' => false));
    }
	
	$vacation_status = "";
	if(strtotime($user['Vacation']['from_date']) > strtotime(date("Y-m-d h:i:s"))){
		$vacation_status = "<span class='label label-warning'>Upcoming</span>";
	}
	else {
		$vacation_status = "<span class='label label-danger'>Completed</span>";
	}
	
	if(strtotime($user['Vacation']['from_date']) <= strtotime(date("Y-m-d h:i:s")) && strtotime($user['Vacation']['to_date']) >= strtotime(date("Y-m-d h:i:s"))) {
		$vacation_status = "<span class='label label-success'>In Process</span>";
	}
	
    $records["data"][] = array(
		$i,
        $user['User']['name'],
      //  $user['User']['specialty'],
        date("m/d/Y h:i A",strtotime($user['Vacation']['from_date'])),
        date("m/d/Y h:i A",strtotime($user['Vacation']['to_date'])),
		$vacation_status
    );
	$i++;
}

if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
    $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
    $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
}

$records["draw"] = $sEcho;
$records["recordsTotal"] = $iTotalRecords;
$records["recordsFiltered"] = $iTotalRecords;

echo json_encode($records);
?>