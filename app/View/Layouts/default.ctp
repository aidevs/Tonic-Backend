<!DOCTYPE html>
<html lang="en">
    <head>
	<?php echo $this->Html->charset(); ?>	
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0" />
	<title><?php echo Configure::read('Site.title'); ?>| <?php echo $title_for_layout; ?></title>
        <script>var SITE_URL='<?php echo Router::url('/'); ?>';</script>
	<?php
	echo $this->Html->meta('icon');
	echo $this->Html->css(array('bootstrap','font-awesome','bootstrap-theme','reset','style','toastr.min','bootstrap-datepicker/datepicker3','dev'));	
	echo $this->Html->script(array('jquery.min','bootstrap.min','jquery.validate.min','bootstrap-toastr/toastr.min','ajaxupload.3.5','bootstrap-datepicker/bootstrap-datepicker','itemslide.min','jquery.mousewheel.js','jquery.blockui.min','jquery.touchSwipe.min'));
	echo $this->fetch('meta');
	echo $this->fetch('css');
	?>
        <link href="<?php echo ASSETS_URL; ?>assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
        <script src="<?php echo ASSETS_URL; ?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <script src="<?php echo ASSETS_URL; ?>assets/global/plugins/moment.min.js" type="text/javascript"></script>
</head>
     <body class="<?php echo $this->request->params['action'] == "calendar" ? "custom-calendar" : "" ?>">
		  <?php echo $this->element('header'); ?>
          <section class="middle-container">
             <?php echo $this->fetch('content'); ?>
          </section>
          <?php 
          echo $this->Html->script(array('site'));
          echo $this->fetch('script'); 
          ?>
         <script>
              //--------------Error-Messages-Toastr--------------------//
                       toastr.options = {
                           "closeButton": true,
                           "debug": false,
                           "positionClass": "toast-top-full-width",
                           "onclick": null,
                           "showDuration": "10000",
                           "hideDuration": "10000",
                           "timeOut": "5000",
                           "extendedTimeOut": "1000",
                           "showEasing": "swing",
                           "hideEasing": "linear",
                           "showMethod": "slideDown",
                           "hideMethod": "slideUp"
                       }

             <?php echo $session_msg; ?>                 
         
            jQuery(document).ready(function() {              
                <?php if(isset($this->request->data['User']['remmber_me']) && $this->request->data['User']['remmber_me']==1){?>
                  if(!$('#UserRemmberMe').is(':checked')){
                     $('#UserRemmberMe').click();
                  }
               <?php }  ?>
            });
        </script>
         <?php echo $this->element('modal'); ?>
    </body>
</html>

