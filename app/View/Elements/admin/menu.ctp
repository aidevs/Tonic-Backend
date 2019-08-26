<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->                   
        <ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">

            <li class="sidebar-toggler-wrapper">
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <div class="sidebar-toggler" style="margin-bottom: 15px;">
                </div>
                <!-- END SIDEBAR TOGGLER BUTTON -->
            </li>


            <li class="<?php echo ($this->params['action'] == 'admin_dashboard') ? 'active' : ''; ?>">
                <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'dashboard', 'admin' => true)) ?>">
                    <i class="fa fa-dashboard"></i>
                    <span class="title">Dashboard</span>
                    <span class="<?php echo ($this->params['action'] == 'admin_dashboard') ? 'selected' : ''; ?>"></span>

                </a>                            
            </li>
            <?php if ($this->Session->read('Auth.User.role_id') != 1) { ?>
             <li class="<?php echo ($this->params['controller'] == 'services') ? 'active' : ''; ?>">
                    <a href="javascript:;">
                        <i class="fa fa-list "></i>
                        <span class="title">Services</span>
                        <span class="<?php echo ($this->params['controller'] == 'services') ? 'selected' : 'arrow'; ?>"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="<?php echo ($this->params['controller'] == 'services' && $this->params['action'] == 'admin_index') ? 'active' : ''; ?>">
                            <?php echo $this->Html->link(' <i class="fa fa-list"></i> All Services', array('controller' => 'services', 'action' => 'index', 'admin' => true), array('escape' => false)); ?>                                    
                        </li>
                        <li class="<?php echo ($this->params['controller'] == 'services' && $this->params['action'] == 'admin_add') ? 'active' : ''; ?>">
                            <?php echo $this->Html->link('<i class="fa fa-plus "></i> Add Service', array('admin' => true, 'controller' => 'services', 'action' => 'add'), array('escape' => false)); ?>

                        </li>

                    </ul>
                </li>
            <?php } if ($this->Session->read('Auth.User.role_id') == 1) { ?>
                <li class="<?php echo ($this->params['controller'] == 'users' && !in_array($this->params['action'], array('admin_dashboard', 'admin_profile', 'admin_advertisement'))) ? 'active' : ''; ?>">
                    <a href="javascript:;">
                        <i class="fa fa-users "></i>
                        <span class="title">Admins</span>
                        <span class="<?php echo ($this->params['controller'] == 'users' && !in_array($this->params['action'], array('admin_dashboard', 'admin_profile'))) ? 'selected' : 'arrow'; ?>"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="<?php echo ($this->params['controller'] == 'users' && $this->params['action'] == 'admin_index' && ($this->params['action'] != 'admin_advertisement')) ? 'active' : ''; ?>">
                            <?php echo $this->Html->link(' <i class="fa fa-users"></i> All Admins', array('controller' => 'users', 'action' => 'index', 'admin' => true), array('escape' => false)); ?>                                    
                        </li>
                        <li class="<?php echo ($this->params['controller'] == 'users' && $this->params['action'] == 'admin_add' && ($this->params['action'] != 'admin_advertisement')) ? 'active' : ''; ?>">
                            <?php echo $this->Html->link('<i class="fa fa-plus "></i> Add Admin', array('admin' => true, 'controller' => 'users', 'action' => 'add'), array('escape' => false)); ?>

                        </li>

                    </ul>
                </li>

                <li class="<?php echo ( ($this->params['controller'] == 'settings') ) ? 'active' : ''; ?>">
                    <a href="<?php echo Router::url(array('controller' => 'settings', 'action' => 'prefix', 'site', 'admin' => true)) ?>">
                        <i class="fa fa-cogs "></i>
                        <span class="title">Settings</span>

                    </a>                            
                </li>




            <?php } else { ?>
                <li class="<?php echo ($this->params['controller'] == 'barbers') ? 'active' : ''; ?>">
                    <a href="javascript:;">
                        <i class="fa fa-users "></i>
                        <span class="title">Barbers</span>
                        <span class="<?php echo ($this->params['controller'] == 'barbers') ? 'selected' : 'arrow'; ?>"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="<?php echo ($this->params['controller'] == 'users' && $this->params['action'] == 'admin_index') ? 'active' : ''; ?>">
                            <?php echo $this->Html->link(' <i class="fa fa-users"></i> All Barbers', array('controller' => 'barbers', 'action' => 'index', 'admin' => true), array('escape' => false)); ?>                                    
                        </li>
                        <li class="<?php echo ($this->params['controller'] == 'users' && $this->params['action'] == 'admin_vacations') ? 'active' : ''; ?>">
                            <?php echo $this->Html->link(' <i class="fa fa-calendar"></i> Barber\'s Vacations', array('controller' => 'barbers', 'action' => 'vacations', 'admin' => true), array('escape' => false)); ?>                                    
                        </li>
                        <li class="<?php echo ($this->params['controller'] == 'users' && $this->params['action'] == 'admin_add') ? 'active' : ''; ?>">
                            <?php echo $this->Html->link('<i class="fa fa-plus "></i> Add Barber', array('admin' => true, 'controller' => 'barbers', 'action' => 'add'), array('escape' => false)); ?>

                        </li>

                    </ul>
                </li>            
                <li class="<?php echo ($this->params['controller'] == 'customers') ? 'active' : ''; ?>">
                    <a href="javascript:;">
                        <i class="fa fa-users "></i>
                        <span class="title">Customers</span>
                        <span class="<?php echo ($this->params['controller'] == 'customers') ? 'selected' : 'arrow'; ?>"></span>
                    </a>
                    <ul class="sub-menu">
                        <?php if ($this->Session->read('Auth.User.role_id')==1 || ($this->Session->read('Auth.User.role_id')==2 && $this->Session->read('Auth.User.unlimited_barber')== 1)) { ?> 
                        <li class="<?php echo ($this->params['controller'] == 'customers' && $this->params['action'] == 'admin_walkin') ? 'active' : ''; ?>">
                            <?php echo $this->Html->link(' <i class="fa fa-male"></i> Walk-In', array('controller' => 'customers', 'action' => 'admin_walkin', 'admin' => true), array('escape' => false)); ?>                                    
                        </li>
                        <li class="<?php echo ($this->params['controller'] == 'customers' && $this->params['action'] == 'admin_pending_walkin') ? 'active' : ''; ?>">
                            <?php echo $this->Html->link(' <i class="fa fa-male"></i> Pending Walkins', array('controller' => 'customers', 'action' => 'admin_pending_walkin', 'admin' => true), array('escape' => false)); ?>                                    
                        </li>
                        <?php }?>
                        <li class="<?php echo ($this->params['controller'] == 'customers' && $this->params['action'] == 'admin_reservations') ? 'active' : ''; ?>">
                            <?php echo $this->Html->link('<i class="fa fa-mobile"></i> Reservations', array('admin' => true, 'controller' => 'customers', 'action' => 'admin_reservations'), array('escape' => false)); ?>

                        </li>

                    </ul>
                </li>

            <?php } ?>
            <?php if ($this->Session->read('Auth.User.role_id')==1 || ($this->Session->read('Auth.User.role_id')==2 && $this->Session->read('Auth.User.unlimited_barber')== 1)) { ?> 
            <li class="<?php echo ($this->params['controller'] == 'users' && $this->params['action'] == 'admin_advertisement' ) ? 'active' : ''; ?>">
                <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'advertisement', 'admin' => true)) ?>">
                    <i class="fa fa-film"></i>
                    <span class="title">Advertisements</span>

                </a>                            
            </li>
           <?php } ?>
        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
</div>