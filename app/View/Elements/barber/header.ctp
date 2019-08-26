<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="<?php echo ASSETS_URL; ?>">
                <img src="<?php echo ASSETS_URL; ?>images/logo.png" width="100" height="30" alt="logo" class="logo-default"/>
            </a>
            <div class="menu-toggler sidebar-toggler hide">
                <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
<!--                 BEGIN NOTIFICATION DROPDOWN 
                 DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                 <?php //echo $this->element('admin/notification'); ?>
<!--                 END NOTIFICATION DROPDOWN -->


<!--                 BEGIN USER LOGIN DROPDOWN 
                 DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <i class="icon-user" style="font-size: 21px;"></i>
                        <span class="username username-hide-on-mobile">
                            <?php echo $this->Session->read('Auth.User.name'); ?> </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
						
                        <li>
                            <?php echo $this->Html->link('<i class="icon-key"></i> Log Out', array('controller' => 'barbers', 'action' => 'logout','barber'=>true),array('escape'=>false)); ?>
                           
                        </li>
                    </ul>
                </li>
<!--                 END USER LOGIN DROPDOWN -->

            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>