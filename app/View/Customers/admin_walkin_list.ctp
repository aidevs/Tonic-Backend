<?php

//pr($rooms);die;
/*
 * Paging
 */

$iTotalRecords = $this->params['paging']['Walkin']['count'];
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
    $id = $user['Walkin']['id'];
     $status = $user['Walkin']['status'];
     $label = ($status == 1) ? 'success': 'danger';
     $label_text = ($status == 1) ? 'Seat':'Dismiss';
    $records["data"][] = array(
        $user['Walkin']['name'],
        $user['User']['name'],
        $user['Walkin']['time'],
        date('m/d/Y',strtotime($user['Walkin']['date'])), 
        '<span class="label label-sm label-' . ($label) . '">' . $label_text . '</span>',
        $this->BootForm->postLink('<i class="fa fa-trash-o"></i> <span class="hidden-480">Delete</span>', array('action' => 'walkin_delete', $id), array('confirm' => __('Are you sure you want to delete # %s?', $id), 'class' => 'btn default btn-xs red', 'escape' => false))
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