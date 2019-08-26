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
    $label = ($status == 1) ? 'success' : 'danger';
    $label_text = ($status == 1) ? 'Enabled' : 'Disabled';
    $delete = $this->BootForm->postLink('<i class="fa fa-trash-o"></i> <span class="hidden-480">Delete</span>', array('action' => 'delete', $id), array('confirm' => __('Are you sure you want to delete %s?', $user['User']['name']), 'class' => 'btn default btn-xs red', 'escape' => false));
    $records["data"][] = array(
        '<input type="checkbox" name="id[]" value="' . $id . '">',
        $user['User']['name'],
        $user['User']['email'],
        $user['User']['shop_name'],        
        $user['Country']['country_name'],
        $user['User']['phone'],
        '<span class="label label-sm label-' . ($label) . '">' . $label_text . '</span>',
        '<a class="btn default btn-xs purple" href="' . Router::url(array('admin' => true, 'controller' => 'users', 'action' => 'edit', $id)) . '"><i class="fa fa-edit"></i> <span class="hidden-480">Edit</span> </a>' . $delete,
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