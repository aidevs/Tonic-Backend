<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8"/>
        <title><?php echo $this->fetch('title'); ?></title>
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
        <link href="<?php echo ASSETS_URL; ?>assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>

        <!-- END GLOBAL MANDATORY STYLES -->       
        <link rel="stylesheet" type="text/css" href="<?php echo ASSETS_URL; ?>assets/global/plugins/select2/select2.css"/>
        <link href="<?php echo ASSETS_URL; ?>assets/admin/pages/css/login3.css" rel="stylesheet" type="text/css"/>

        <!-- BEGIN THEME STYLES -->
        <!-- DOC: To use 'rounded corners' style just load 'components-rounded.css' stylesheet instead of 'components.css' in the below style tag -->
        <link href="<?php echo ASSETS_URL; ?>assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
        <link href="<?php echo ASSETS_URL; ?>assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo ASSETS_URL; ?>assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo ASSETS_URL; ?>assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
        <link href="<?php echo ASSETS_URL; ?>assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
        <?php echo $this->fetch('meta'); ?>
        <!-- END THEME STYLES -->
        <link rel="shortcut icon" href="favicon.ico"/>
        <script>
            var path = '<?php echo SITE_URL; ?>';
            var SITE_URL = '<?php echo SITE_URL; ?>';
            var prefix = '<?php echo $this->request->params['prefix']; ?>';


        </script>
    </head>
    <!-- END HEAD -->
    <!-- BEGIN BODY -->   
    <body class="login">
        <!-- BEGIN LOGO -->
        <div class="logo">
            <a href="<?php echo ASSETS_URL; ?>">
                <img width="150" src="<?php echo ASSETS_URL; ?>images/logo.png" alt=""/>
            </a>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        <div class="menu-toggler sidebar-toggler">
        </div>
        <!-- END SIDEBAR TOGGLER BUTTON -->
        <!-- BEGIN LOGIN -->
        <div class="content">
            <!-- BEGIN LOGIN FORM -->

            <?php echo $this->Form->create('User', array("class" => "login-form user_reset_password", 'autocomplete' => 'off')); ?>
            <?php echo $this->Form->input('id'); ?>
            <h3 class="form-title">Reset Password</h3>
            <?php
            if (in_array($this->params['action'], array('admin_login', 'admin_reset_password'))) {
                echo $this->Common->sessionFlash();
            }
            ?>
            <div class="alert alert-danger display-hide">
                <button class="close" data-close="alert"></button>
                <span>
                    Enter password. </span>
            </div>
            <div class="form-group">
                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                <label class="control-label visible-ie8 visible-ie9">Password</label>
                <div class="input-icon">
                    <i class="fa fa-lock"></i>
<?php echo $this->Form->input('password', array('class' => 'form-control placeholder-no-fix', 'label' => false, 'div' => false, 'placeholder' => 'Password', 'autocomplete' => 'off')); ?>

                </div>
            </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Confirm Password</label>
                <div class="input-icon">
                    <i class="fa fa-lock"></i>
<?php echo $this->Form->input('confirm_password', array('class' => 'form-control placeholder-no-fix', 'label' => false, 'div' => false, 'placeholder' => 'Confirm Password', 'autocomplete' => 'off', 'type' => 'password')); ?>

                </div>
            </div>
            <div style="height:50px;">	
                <button type="submit" class="btn green-haze pull-right">
                    Submit <i class="m-icon-swapright m-icon-white"></i>
                </button>
            </div>



<?php echo $this->Form->end(); ?>
            <!-- END LOGIN FORM -->
            <!-- BEGIN FORGOT PASSWORD FORM -->



            <!-- END FORGOT PASSWORD FORM -->

        </div>
        <!-- END LOGIN -->
        <!-- BEGIN COPYRIGHT -->
        <div class="copyright">
<?php echo date('Y'); ?> &copy; <?php echo Configure::read('Site.title'); ?>.
        </div>
        <!-- END COPYRIGHT -->
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
        <script src="<?php echo ASSETS_URL; ?>assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="<?php echo ASSETS_URL; ?>assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <script src="<?php echo ASSETS_URL; ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo ASSETS_URL; ?>assets/global/plugins/select2/select2.min.js"></script>
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="<?php echo ASSETS_URL; ?>assets/global/scripts/metronic.js" type="text/javascript"></script>
        <script src="<?php echo ASSETS_URL; ?>assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
        <script src="<?php echo ASSETS_URL; ?>js/admin.js"></script>
        <script>
            jQuery(document).ready(function () {
                Metronic.init(); // init metronic core componets
                Layout.init(); // init layout
                AccountSetting.init();
            });
        </script>        
<?php echo $this->fetch('script'); ?>
        <!-- END JAVASCRIPTS -->
    </body>

    <!-- END BODY -->
</html>