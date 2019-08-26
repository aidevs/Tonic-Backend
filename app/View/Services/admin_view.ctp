<div class="services view">
<h2><?php echo __('Service'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($service['Service']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($service['User']['name'], array('controller' => 'users', 'action' => 'view', $service['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($service['Service']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($service['Service']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cost'); ?></dt>
		<dd>
			<?php echo h($service['Service']['cost']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Time'); ?></dt>
		<dd>
			<?php echo h($service['Service']['time']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($service['Service']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Updated'); ?></dt>
		<dd>
			<?php echo h($service['Service']['updated']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Service'), array('action' => 'edit', $service['Service']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Service'), array('action' => 'delete', $service['Service']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $service['Service']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Services'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Service'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Barber Services'), array('controller' => 'barber_services', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Barber Service'), array('controller' => 'barber_services', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Barber Services'); ?></h3>
	<?php if (!empty($service['BarberService'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Service Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($service['BarberService'] as $barberService): ?>
		<tr>
			<td><?php echo $barberService['id']; ?></td>
			<td><?php echo $barberService['user_id']; ?></td>
			<td><?php echo $barberService['service_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'barber_services', 'action' => 'view', $barberService['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'barber_services', 'action' => 'edit', $barberService['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'barber_services', 'action' => 'delete', $barberService['id']), array('confirm' => __('Are you sure you want to delete # %s?', $barberService['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Barber Service'), array('controller' => 'barber_services', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Users'); ?></h3>
	<?php if (!empty($service['User'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Role Id'); ?></th>
		<th><?php echo __('Parent Id'); ?></th>
		<th><?php echo __('First Name'); ?></th>
		<th><?php echo __('Last Name'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Email'); ?></th>
		<th><?php echo __('Password'); ?></th>
		<th><?php echo __('Pin'); ?></th>
		<th><?php echo __('Specialty'); ?></th>
		<th><?php echo __('Shop Name'); ?></th>
		<th><?php echo __('Shop Slug'); ?></th>
		<th><?php echo __('Shop Description'); ?></th>
		<th><?php echo __('Country Id'); ?></th>
		<th><?php echo __('Phone'); ?></th>
		<th><?php echo __('Image'); ?></th>
		<th><?php echo __('Address'); ?></th>
		<th><?php echo __('Notes'); ?></th>
		<th><?php echo __('Show Notes'); ?></th>
		<th><?php echo __('Gender'); ?></th>
		<th><?php echo __('Age'); ?></th>
		<th><?php echo __('Dob'); ?></th>
		<th><?php echo __('Activation Key'); ?></th>
		<th><?php echo __('Status'); ?></th>
		<th><?php echo __('Token'); ?></th>
		<th><?php echo __('Advertisement'); ?></th>
		<th><?php echo __('Timezone'); ?></th>
		<th><?php echo __('Window Hours'); ?></th>
		<th><?php echo __('Unlimited Barber'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Updated'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($service['User'] as $user): ?>
		<tr>
			<td><?php echo $user['id']; ?></td>
			<td><?php echo $user['role_id']; ?></td>
			<td><?php echo $user['parent_id']; ?></td>
			<td><?php echo $user['first_name']; ?></td>
			<td><?php echo $user['last_name']; ?></td>
			<td><?php echo $user['name']; ?></td>
			<td><?php echo $user['email']; ?></td>
			<td><?php echo $user['password']; ?></td>
			<td><?php echo $user['pin']; ?></td>
			<td><?php echo $user['specialty']; ?></td>
			<td><?php echo $user['shop_name']; ?></td>
			<td><?php echo $user['shop_slug']; ?></td>
			<td><?php echo $user['shop_description']; ?></td>
			<td><?php echo $user['country_id']; ?></td>
			<td><?php echo $user['phone']; ?></td>
			<td><?php echo $user['image']; ?></td>
			<td><?php echo $user['address']; ?></td>
			<td><?php echo $user['notes']; ?></td>
			<td><?php echo $user['show_notes']; ?></td>
			<td><?php echo $user['gender']; ?></td>
			<td><?php echo $user['age']; ?></td>
			<td><?php echo $user['dob']; ?></td>
			<td><?php echo $user['activation_key']; ?></td>
			<td><?php echo $user['status']; ?></td>
			<td><?php echo $user['token']; ?></td>
			<td><?php echo $user['advertisement']; ?></td>
			<td><?php echo $user['timezone']; ?></td>
			<td><?php echo $user['window_hours']; ?></td>
			<td><?php echo $user['unlimited_barber']; ?></td>
			<td><?php echo $user['created']; ?></td>
			<td><?php echo $user['updated']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'users', 'action' => 'view', $user['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'users', 'action' => 'edit', $user['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'users', 'action' => 'delete', $user['id']), array('confirm' => __('Are you sure you want to delete # %s?', $user['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
