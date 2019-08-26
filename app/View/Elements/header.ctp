<?php if ($this->params['controller'] == 'barbers' && $this->params['action'] == 'calendar') { ?>
    <header>
        <?php if (isset($barber_info) && !empty($barber_info)): ?>
            <div class="custom-calendar1 text-center paddtop20">
                <a href="<?php echo Router::url(array('controller' => 'barbers', 'action' => 'service',$barber_info['User']['id'])); ?>"  class="backBtn" style="position: absolute;"></a>
                <img class="img-rounded55" src="<?php echo $this->Common->getUserImage($barber_info['User']['image'], 91, 90, 1, 'front'); ?>" alt=""><br/>
                <div class="font20 margintop10 paddbottom30"><?php echo $barber_info['User']['name']; ?></div>
            </div>
        <?php else : ?>
        <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'my_account')); ?>"  class="backBtn" style="position: absolute;"></a>
            <div class="font20 text-center margintop40 marginbottom30">Select a Barber to book with</div>
        <?php endif; ?>
        <div class="hide">
            
            <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'login')); ?>" class="homeBtn-left"></a>
            <div id="scrolling" class="newscrollwork">
                <!--Slider-->
                <ul>
                    <?php
                    $j = 0;
                    if (!empty($barbers)) {
                        foreach ($barbers as $barber) {
                            ?>
                            <li data-number="<?php echo $j; ?>" class="slide-<?php echo $j; ?>" data-id="<?php echo $barber['User']['id']; ?>" data-schedule="<?php echo $barber['Schedule']['id']; ?>">
                                <img src="<?php echo $this->Common->getUserImage($barber['User']['image'], 91, 90, 1, 'front'); ?>" alt="">
                            </li>     
                            <?php
                            $j++;
                        }
                    } else {
                        ?> 
                        <li data-number="1" class="slide-1 nobarber-class" data-id="" data-schedule="" style="content:">

                        </li>  
                        <?php
                    }
                    ?>            
                </ul>

            </div> 
        </div>
    </header>
<?php }else if ($this->params['controller'] == 'barbers' && $this->params['action'] == 'my_calendar') { ?>
    <header>
        
        <div class="custom-calendar1 text-center paddtop20">
            <a href="<?php echo Router::url(array('controller' => 'barbers', 'action' => 'my_service')); ?>"  class="backBtn" style="position: absolute;"></a>
            <img class="img-rounded55" src="<?php echo $this->Common->getUserImage($this->Session->read('Auth.User.image'), 91, 90, 1, 'front'); ?>" alt=""><br/>
            <div class="font20 margintop10 paddbottom30"><?php echo $this->Session->read('Auth.User.name'); ?></div>
        </div>
    </header>
<div class="hide">
    <div id="scrolling" class="newscrollwork"><ul><li data-number="1" class="slide-1 nobarber-class" data-id="" data-schedule="" style="content:"></li></ul></div>
</div>
<?php } else if ($this->params['controller'] == 'users' && $this->params['action'] == 'register') { ?>
    <div class="bgImage"> <img src="<?php echo SITE_URL; ?>images/bg.png"> </div>
    <header class="marginTp70">
        <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'login')); ?>" class="backBtn"></a>
    </header>
<?php } elseif ($this->params['controller'] == 'users' && in_array($this->params['action'], array('notes', 'my_profile', 'edit_profile', 'change_password'))) { ?>
    <div class="bgImage"> <img src="<?php echo SITE_URL; ?>images/bg.png"> </div> 
    <header class="marginTp20">
        <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'my_account')); ?>" class="homeBtn"></a>
        <?php if ($this->params['action'] == 'my_profile') { ?>

            <div class="dropdown dropdown-user pull-right">
                <a data-close-others="true" data-hover="dropdown" data-toggle="dropdown" class="dropdown-toggle editBtn pull-right" href="#" aria-expanded="true">    
                </a>
                <ul class="dropdown-menu dropdown-menu-default">
                    <?php if ($this->Session->read('Auth.User.role_id') == 4) { ?>
                        <li>
                            <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'edit_profile')); ?>">
                                <i class="fa fa-pencil-square-o"></i> Edit Profile </a>
                        </li>
                    <?php } ?>
                    <li>
                        <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'change_password')); ?>">
                            <i class="fa fa-pencil-square-o"></i> Change Password </a>
                    </li>
                </ul>
            </div>
        <?php } ?>
    </header>
    <?php
} else if ($this->params['controller'] == 'users' && $this->params['action'] == 'waiting_list') {
    
}else if ($this->params['controller'] == 'barbers' && $this->params['action'] == 'service' || $this->params['action'] == 'my_service') {?>
    <div class="bgImage"> <img src="<?php echo SITE_URL; ?>images/bg.png"> </div>
    <header>
        <?php 
        if($this->params['action'] == 'my_service'){ ?>
          <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'my_account')); ?>"  class="backBtn " style="position: absolute;"></a>  
        <?php }else{ ?>
          <a href="<?php echo Router::url(array('controller' => 'barbers', 'action' => 'calendar')); ?>"  class="backBtn " style="position: absolute;"></a>  
        <?php }  ?>
        <div class="font20 text-center margintop40 marginbottom30">Select Services</div></header>
<?php }else if ($this->params['controller'] == 'barbers' && $this->params['action'] == 'barbercalendar') {
    ?>
    <header>
        <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'login')); ?>" class="homeBtn-left homeBtn-left_new"></a>

    </header>
    <?php
} else {
    ?>
    <div class="bgImage"> <img src="<?php echo SITE_URL; ?>images/bg.png"> </div>
<?php } ?>
<div class="bgImage splash" style="z-index: 9999;display: none;"> <img src="<?php echo SITE_URL; ?>images/splash-screen.jpg" alt=""> </div>
