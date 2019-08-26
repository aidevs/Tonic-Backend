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
            <li class="<?php echo ($this->params['action'] == 'barber_schedules') ? 'active' : ''; ?>">
                <a href="<?php echo Router::url(array('controller' => 'barbers', 'action' => 'schedules', 'barber' => true)) ?>">
                    <i class="fa fa-calendar"></i>
                    <span class="title">Schedules</span>
                    <span class="<?php echo ($this->params['action'] == 'barber_schedules') ? 'selected' : ''; ?>"></span>

                </a>                            
            </li>           
            <li class="<?php echo (in_array($this->params['action'], array('barber_vacations','barber_add_vacation'))) ? 'active' : ''; ?>">
                <a href="<?php echo Router::url(array('controller' => 'barbers', 'action' => 'vacations', 'barber' => true)) ?>">
                    <i class="fa fa-tree"></i>
                    <span class="title">Vacations</span>
                    <span class="<?php echo (in_array($this->params['action'], array('barber_vacations','barber_add_vacation'))) ? 'selected' : ''; ?>"></span>

                </a>                            
            </li>           
        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
</div>