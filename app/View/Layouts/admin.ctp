<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8"/>
        <title><?php echo $title_for_layout; ?> | <?php echo Configure::read('Site.title'); ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <?php
        echo $this->Html->meta('icon');
		echo $this->fetch('meta');
        ?>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
        <link href="<?php echo ASSETS_URL; ?>assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo ASSETS_URL; ?>assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo ASSETS_URL; ?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        
        <!-- END GLOBAL MANDATORY STYLES -->       
        <link rel="stylesheet" type="text/css" href="<?php echo ASSETS_URL; ?>assets/global/plugins/select2/select2.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo ASSETS_URL; ?>assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo ASSETS_URL; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker3.css"/>
       <link rel="stylesheet" type="text/css" href="<?php echo ASSETS_URL; ?>assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
	   
	   <link rel="stylesheet" type="text/css" href="<?php echo ASSETS_URL; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
        <link href="<?php echo ASSETS_URL; ?>assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
	   
        <link href="<?php echo ASSETS_URL; ?>assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
        
        <!-- BEGIN THEME STYLES -->
        <!-- DOC: To use 'rounded corners' style just load 'components-rounded.css' stylesheet instead of 'components.css' in the below style tag -->
        <link href="<?php echo ASSETS_URL; ?>assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
        <link href="<?php echo ASSETS_URL; ?>assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo ASSETS_URL; ?>assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo ASSETS_URL; ?>assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
        <link href="<?php echo ASSETS_URL; ?>assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo ASSETS_URL; ?>assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo ASSETS_URL; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css"/>
	
        
        <?php 
         echo $this->Html->css('admin/common');
          echo $this->fetch('meta'); ?>
        <!-- END THEME STYLES -->
        <link rel="shortcut icon" href="favicon.ico"/>
        <script> 
            var path='<?php echo SITE_URL; ?>';
            var SITE_URL='<?php echo SITE_URL; ?>';
            var prefix='<?php echo $this->request->params['prefix']; ?>';
            var total_data=0;
            var aTargets=[];
            var tOrder=[2, "asc"];
            var ajaxUrl=path+prefix+"/<?php echo $this->request->params['controller']; ?>/list";
        
        </script>
    </head>
    <body class="page-header-fixed page-quick-sidebar-over-content page-style-square"> 
        <!-- BEGIN HEADER -->
        <?php 
            if($this->Session->read('Auth.User.role_id')==1)
               echo $this->element('admin/header');
            else
                echo $this->element('admin/header');
            ?>
         
        <!-- END HEADER -->
        <div class="clearfix">
        </div>
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN SIDEBAR -->
            <?php 
            if($this->Session->read('Auth.User.role_id')==1)
                 echo $this->element('admin/menu');
            else
                 echo $this->element('admin/menu');
            ?>
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <?php echo $this->fetch('content'); ?>
            <!-- END CONTENT -->            
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <?php echo $this->element('admin/footer'); ?>
        <!-- END FOOTER -->
        <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
        <!-- BEGIN CORE PLUGINS -->
        <!--[if lt IE 9]>
        <script src="<?php echo ASSETS_URL; ?>assets/global/plugins/respond.min.js"></script>
        <script src="<?php echo ASSETS_URL; ?>assets/global/plugins/excanvas.min.js"></script> 
        <![endif]-->
        <script src="<?php echo ASSETS_URL; ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>        
        <script src="<?php echo ASSETS_URL; ?>assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
        <!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
        <script src="<?php echo ASSETS_URL; ?>assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
        <script src="<?php echo ASSETS_URL; ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo ASSETS_URL; ?>assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="<?php echo ASSETS_URL; ?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="<?php echo ASSETS_URL; ?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>        
<!--        <script src="<?php //echo ASSETS_URL; ?>assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>-->
        <script src="<?php echo ASSETS_URL; ?>assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="<?php echo ASSETS_URL; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js"></script>
        <!-- END CORE PLUGINS -->
        <script src="<?php echo ASSETS_URL; ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="<?php echo ASSETS_URL; ?>assets/global/plugins/jquery-validation/js/additional-methods.js" type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo ASSETS_URL; ?>assets/global/plugins/select2/select2.min.js"></script>
        <script type="text/javascript" src="<?php echo ASSETS_URL; ?>assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="<?php echo ASSETS_URL; ?>assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
        <script type="text/javascript" src="<?php echo ASSETS_URL; ?>assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
		<script type="text/javascript" src="<?php echo ASSETS_URL; ?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
                <script src="<?php echo ASSETS_URL; ?>js/bootstrap-datepicker/bootstrap-datepicker.js"></script>
        <script src="<?php echo ASSETS_URL; ?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <script src="<?php echo ASSETS_URL; ?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <script src="<?php echo ASSETS_URL; ?>assets/global/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo ASSETS_URL; ?>assets/global/plugins/ckeditor/ckeditor.js"></script>
       
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="<?php echo ASSETS_URL; ?>assets/global/scripts/metronic.js" type="text/javascript"></script>
        <script src="<?php echo ASSETS_URL; ?>assets/admin/layout/scripts/layout.js" type="text/javascript"></script>        
        <script src="<?php echo ASSETS_URL; ?>assets/global/scripts/datatable.js"></script>
        <script src="<?php echo ASSETS_URL; ?>assets/admin/pages/scripts/table-ajax.js"></script>       
        <script src="<?php echo ASSETS_URL; ?>assets/admin/pages/scripts/table-managed.js"></script>
       
        
        <script src="<?php echo ASSETS_URL; ?>js/admin.js"></script>
        <script>
            jQuery(document).ready(function() {
                Metronic.init(); // init metronic core componets
                Layout.init(); // init layout
                TableAjax.init();   
            });
        </script>        
        <?php echo $this->fetch('script'); ?>
        
        
        <!-- END PAGE LEVEL SCRIPTS -->
        
        <!-- END JAVASCRIPTS -->
        <?php  //echo $this->Js->writeBuffer(); ?>        
    </body>
    
    <!-- END BODY -->
</html>