<?php

//pr($rooms);die;
/*
 * Paging
 */

$iTotalRecords = $this->params['paging']['User']['count'];
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
    $id = $user['User']['id'];
    $status = $user['User']['status'];
    $deleted = $user['User']['deleted'];
    $label = ($status == 1) ? 'success' : 'danger';
    $label_text = ($status == 1) ? 'Enabled' : 'Disabled';
    if($deleted==1){
     $cls='danger';   
     $delete = $this->BootForm->postLink('<i class="fa fa-trash-o"></i> <span class="hidden-480 un-achive">Un-Archive</span>', array('action' => 'delete', $id), array('confirm' => __('Are you sure you want to un-archive %s?', $user['User']['name']), 'class' => 'btn default btn-xs green', 'escape' => false));    
    }else{
     $cls='danger';   
    $delete = $this->BootForm->postLink('<i class="fa fa-trash-o"></i> <span class="hidden-480">Archive</span>', array('action' => 'delete', $id), array('confirm' => __('Are you sure you want to archive %s?', $user['User']['name']), 'class' => 'btn default btn-xs red', 'escape' => false));
    }
    $force_delete = $this->BootForm->postLink('<i class="fa fa-trash-o"></i> <span class="hidden-480">Delete</span>', array('action' => 'admin_force_delete', $id), array('confirm' => __('Are you sure you want to delete %s?', $user['User']['name']), 'class' => 'btn default btn-xs red', 'escape' => false));
    
    $records["data"][] = array(
        '<input type="checkbox" name="id[]" value="' . $id . '">',
        $user['User']['name'],
      //  $user['User']['specialty'],
        $user['User']['phone'],
       // $user['User']['gender'],
        $user['User']['pin'],
        '<span class="label label-sm label-' . ($label) . '">' . $label_text . '</span>',
        '<a class="btn default btn-xs purple" href="' . Router::url(array('admin' => true, 'controller' => 'barbers', 'action' => 'edit', $id)) . '"><i class="fa fa-edit"></i> <span class="hidden-480">Edit</span> </a>'
        . '<a class="btn default btn-xs blue" href="' . Router::url(array('admin' => true, 'controller' => 'barbers', 'action' => 'schedule', $id)) . '"><i class="fa fa-calendar"></i> <span class="hidden-480">Schedule</span> </a>'
        . '<a class="btn default btn-xs green" href="' . Router::url(array('admin' => true, 'controller' => 'barbers', 'action' => 'appointments', $id)) . '"><i class="fa fa-clock-o"></i> <span class="hidden-480">Appointments</span> </a>' .
        '<a class="barber-service btn default btn-xs yellow" href="' . Router::url(array('admin' => true, 'controller' => 'barbers', 'action' => 'services', $id)) . '"><i class="fa fa-clock-o"></i> <span class="hidden-480">Services</span> </a>' . $delete.$force_delete,
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