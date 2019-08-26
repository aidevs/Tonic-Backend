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
    $label = ($status == 1) ? 'success': (($status==2)?'danger':'warning');
    $label_text = ($status == 1) ? 'Seat': (($status==2)?'Dismiss':'Pending');
    $records["data"][] = array(
        $user['User']['name'],
        $user['Slot']['time'],
        date('Y-m-d',  strtotime($user['Appointment']['date'])), 
        '<span class="label label-sm label-' . ($label) . '">' . $label_text . '</span>',
        '<a class="btn default btn-xs disabled" href="javascript:;">No More Action</a>'
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