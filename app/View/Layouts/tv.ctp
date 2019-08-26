<!DOCTYPE html>
<html lang="en">
    <head>
	<?php echo $this->Html->charset(); ?>	
        <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui"> 

        <meta name="HandheldFriendly" content="true">
        
	<title><?php echo Configure::read('Site.title'); ?>| <?php echo $title_for_layout; ?></title>
        <script>var SITE_URL='<?php echo Router::url('/'); ?>';</script>
	<?php
	echo $this->Html->meta('icon');
	echo $this->Html->css(array('bootstrap','font-awesome','bootstrap-theme','reset','style','jquery.mCustomScrollbar.min'));	
	echo $this->Html->script(array('jquery.min','jquery.blockui.min','jquery.mCustomScrollbar.concat.min'));
	echo $this->fetch('meta');
	echo $this->fetch('css');	
	?>        
</head>
<body class="tv-newchanage" style="background: #383d3f;">
      
          
           <div class="tv-page">
             <?php echo $this->fetch('content'); ?>
          </div>
          <?php echo $this->fetch('script'); 
          echo $this->Html->script(array('tv'));
          ?>
    </body>
</html>

