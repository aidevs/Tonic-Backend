<?php

//pr($rooms);die;
/*
 * Paging
 */

$iTotalRecords = $this->params['paging']['Appointment']['count'];
//  $iDisplayLength = intval($_REQUEST['length']);
//  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
//  $iDisplayStart = intval($_REQUEST['start']);
$sEcho = intval($_REQUEST['draw']);

$records = array();
$records["data"] = array();

//  $end = $iDisplayStart + $iDisplayLength;
//  $end = $end > $iTotalRecords ? $iTotalRecords : $end; '<a href="'.Router::url(array('admin'=>true,'controller' => 'rooms', 'action' => 'view')).'" class="btn btn-xs blue"><i class="fa fa-search"></i> View</a>'.
//pr($users);
foreach ($users as $user) {
  
    $id = $user['Appointment']['id'];
    $status = $user['Appointment']['status'];
    
        if($user['User']['dob'] != " " && $user['User']['dob'] != NULL){
         $dob= $user['User']['dob'];
        }else{
           $dob='--'; 
        }
    $label = ($status == 1) ? 'success': (($status==2)?'danger':'warning');
    $label_text = ($status == 1) ? 'Seat': (($status==2)?'Dismiss':'Pending');
    $records["data"][] = array(
        $user['User']['name'],        
        $user['Barber']['name'],
          $user['User']['email'],
          $user['User']['phone'],
        $user['Slot']['time'],
        "$dob",
        date('m/d/Y',strtotime($user['Appointment']['date'])), 
        '<span id="status'.$user['Appointment']['id'].'" class="label label-sm label-' . ($label) . '">' . $label_text . '</span>',
        '<button title="Change Status" class="change_status btn btn-primary btn-sm" rel="'.$user['Appointment']['id'].'"><i class="fa fa-check-square-o"></i></button><button title="Delete" class="del_reservation btn btn-danger btn-sm" rel="'.$user['Appointment']['id'].'"><i class="fa fa-trash"></i></button><a class="btn default btn-xs hide disabled" href="javascript:;">No More Action</a>'
    );
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