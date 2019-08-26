<?php

//pr($rooms);die;
/*
 * Paging
 */

$iTotalRecords = $this->params['paging']['Service']['count'];
$sEcho = intval($_REQUEST['draw']);

$records = array();
$records["data"] = array();

foreach ($services as $service) {

    $id = $service['Service']['id'];
    $status = $service['Service']['status'];
    $label = ($status == 1) ? 'success' : 'danger';
    $label_text = ($status == 1) ? 'Enabled' : 'Disabled';
    $records["data"][] = array(
        $service['Service']['name'],
        $service['Service']['description'],
        $this->Number->currency($service['Service']['cost'], 'USD'),
        $service['Service']['time'].' Minutes',
        '<span class="label label-sm label-' . ($label) . '">' . $label_text . '</span>',
        '<a class="btn default btn-xs purple" href="' . Router::url(array('admin' => true, 'controller' => 'services', 'action' => 'edit', $id)) . '"><i class="fa fa-edit"></i> <span class="hidden-480">Edit</span> </a>'.
        $delete = $this->BootForm->postLink('<i class="fa fa-trash-o"></i> <span class="hidden-480">Delete</span>', array('action' => 'delete', $id), array('confirm' => __('Are you sure you want to delete %s?',$service['Service']['name']), 'class' => 'btn default btn-xs red', 'escape' => false))
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