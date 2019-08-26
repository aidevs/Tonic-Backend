<?php

//pr($rooms);die;
/*
 * Paging
 */

$iTotalRecords = $this->params['paging']['WalkinAppointments']['count'];
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
    $id = $user['WalkinAppointments']['id'];
    $url = Router::url(array('controller'=>'customers','action'=>'seat_walkin',$id));
    $dismissOrPending = $user['WalkinAppointments']['status'] == '1' ? $this->BootForm->postLink('<i class="fa fa-trash-o"></i> <span class="hidden-480">Dismiss</span>', array('action' => 'pending_walkin_dismiss', $id,'0'), array('confirm' => __('Are you sure you want to dismiss # %s?', $user['WalkinAppointments']['name']), 'class' => 'btn default btn-xs red', 'escape' => false)) : $this->BootForm->postLink('<i class="fa fa-trash-o"></i> <span class="hidden-480">Pending</span>', array('action' => 'pending_walkin_dismiss', $id,'1'), array('confirm' => __('Are you sure you want to change status to pending # %s?', $user['WalkinAppointments']['name']), 'class' => 'btn default btn-xs red', 'escape' => false)) ;
    $records["data"][] = array(
        $user['WalkinAppointments']['name'],
        $user['WalkinAppointments']['status'] == '1' ? "Pending" : "Dismiss",
        date('m/d/Y',strtotime($user['WalkinAppointments']['created'])), 
        '<a  data-toggle="modal" data-target="#seat-ajax" class="btn default btn-xs yellow" href="'.$url.'"><span class="hidden-480">Seat</span> </a>'.
        $this->BootForm->postLink('<i class="fa fa-trash-o"></i> <span class="hidden-480">Delete</span>', array('action' => 'pending_walkin_delete', $id), array('confirm' => __('Are you sure you want to delete # %s?', $user['WalkinAppointments']['name']), 'class' => 'btn default btn-xs red', 'escape' => false)).
        $dismissOrPending
        
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