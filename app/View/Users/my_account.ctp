<div class="mainLinks">
    <ul class="btns">
        <li><?php echo $this->Html->link('My Profile', array('controller' => 'users', 'action' => 'my_profile')); ?></li>
        <?php if ($this->Session->read('Auth.User.role_id') == 3) { ?>
        
            <li><?php echo $this->Html->link('Book My slot', array('controller' => 'barbers', 'action' => 'my_service')); ?></li>
			<li><?php echo $this->Html->link('Schedule', array('controller' => 'users', 'action' => 'barberschedule')); ?></li>
			<?php /* <li><?php echo $this->Html->link('Vacations', array('controller' => 'users', 'action' => 'barbervacation')); ?></li> */ ?>
			<li><?php echo $this->Html->link('Waiting List', array('controller' => 'users', 'action' => 'waitingListBarber')); ?></li>
			
        <?php } ?>
        <?php if ($this->Session->read('Auth.User.role_id') == 4) { ?>
            <li><?php echo $this->Html->link('Book Appointment', array('controller' => 'barbers', 'action' => 'calendar')); ?></li>
    <!--        <li><?php //echo $this->Html->link('Notes',array('controller'=>'users','action'=>'notes'));  ?></li>        -->
            <li><?php echo $this->Html->link('Current Wait', array('controller' => 'users', 'action' => 'waiting_list')); ?></li>        
        <?php } ?>
        <li><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout')); ?></li>
    </ul>
</div>


