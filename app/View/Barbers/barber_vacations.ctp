<div class="page-content-wrapper">
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <h3 class="page-title">
            <?php echo $title_for_layout; ?>
        </h3>
        <?php echo $this->Common->sessionFlash(); ?>
        <div class="page-bar">
            <ul class="page-breadcrumb">                            
                <li>
                    <i class="fa fa-tree"></i>
                    <a href="javascript:void(0);">Barber Vacations</a>                    
                </li>                
            </ul>
        </div>
        <!-- END PAGE HEADER-->
        <div class="row">

            <div class="col-md-12">
                <?php echo $this->Common->sessionFlash(); ?>
                <div class="portlet" id="form_wizard_1">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-tree"></i>Vacations
                        </div>       
                        <div class="actions">

                            <a href="<?php echo Router::url(array('barber' => true, 'controller' => 'barbers', 'action' => 'add_vacation')); ?>" class="btn green yellow-stripe">
                                <i class="fa fa-plus"></i>
                                <span class="hidden-480">
                                    Add </span>
                            </a>

                        </div>
                    </div>

                    <div class="portlet-body">
                        <div class="table-container">                             
                            <table class="table table-striped table-bordered table-hover table-schedule">
                                <thead>
                                    <tr role="row" class="heading">                                       
                                        <th width="15%">
                                            From Date
                                        </th>
                                        <th width="15%">
                                            To Date
                                        </th>

                                        <th width="8%">
                                           Created On
                                        </th>
                                        <th width="20%">
                                            Actions
                                        </th>
                                    </tr>                                    
                                </thead>
                                <tbody>
                                    <?php
                                    $w = 0;
                                    foreach ($vacations as $vacation) {
                                        ?>
                                        <tr role="row" class="<?php echo ($w % 2 == 0) ? 'even' : 'odd'; ?>">
                                            <td>
                                                <?php echo date('m/d/Y h:i A', strtotime($vacation['Vacation']['from_date'])); ?>
                                            </td> 
                                            <td>
                                                <?php echo date('m/d/Y h:i A', strtotime($vacation['Vacation']['to_date'])); ?>
                                            </td>
                                            <td>
                                                <?php echo date('m/d/Y', strtotime($vacation['Vacation']['created'])); ?>
                                            </td> 
                                            <td> 
                                                <?php
                                                echo
                                                '<a class="btn default btn-xs purple" href="' . Router::url(array('barber' => true, 'controller' => 'barbers', 'action' => 'edit_vacation', $vacation['Vacation']['id'])) . '"><i class="fa fa-edit"></i> <span class="hidden-480">Edit</span> </a>';
                                                echo $delete = $this->BootForm->postLink('<i class="fa fa-trash-o"></i> <span class="hidden-480">Delete</span>', array('controller' => 'barbers', 'action' => 'delete_date', $vacation['Vacation']['id']), array('confirm' => __('Are you sure you want to delete %s?', $vacation['Vacation']['date']), 'class' => 'btn default btn-xs red', 'escape' => false));
                                                ?>
                                            </td> 


                                        </tr>   
    <?php $w++;
} ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<div aria-hidden="true" role="basic" id="lunch-ajax" class="modal fade" style="display: none;">    
    <div class="modal-dialog">
        <div class="modal-content">

        </div>
    </div>
</div>
<?php echo $this->Common->loadJsClass('Barber'); ?>