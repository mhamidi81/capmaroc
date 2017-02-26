<div class="languages view">
<h2><?php echo __('Language'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($language['Language']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($language['Language']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($language['Language']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($language['Language']['created_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Updated'); ?></dt>
		<dd>
			<?php echo h($language['Language']['updated']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Updated By'); ?></dt>
		<dd>
			<?php echo h($language['Language']['updated_by']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Language'), array('action' => 'edit', $language['Language']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Language'), array('action' => 'delete', $language['Language']['id']), array(), __('Are you sure you want to delete # %s?', $language['Language']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Languages'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Language'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Counselors'), array('controller' => 'counselors', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Counselor'), array('controller' => 'counselors', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Counselors'); ?></h3>
	<?php if (!empty($language['Counselor'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('First Name'); ?></th>
		<th><?php echo __('Last Name'); ?></th>
		<th><?php echo __('Address'); ?></th>
		<th><?php echo __('City'); ?></th>
		<th><?php echo __('Cin'); ?></th>
		<th><?php echo __('Birthday'); ?></th>
		<th><?php echo __('Family Status'); ?></th>
		<th><?php echo __('Office Phone'); ?></th>
		<th><?php echo __('Mobile Phone'); ?></th>
		<th><?php echo __('Email'); ?></th>
		<th><?php echo __('Fax'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Created By'); ?></th>
		<th><?php echo __('Updated'); ?></th>
		<th><?php echo __('Updated By'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($language['Counselor'] as $counselor): ?>
		<tr>
			<td><?php echo $counselor['id']; ?></td>
			<td><?php echo $counselor['first_name']; ?></td>
			<td><?php echo $counselor['last_name']; ?></td>
			<td><?php echo $counselor['address']; ?></td>
			<td><?php echo $counselor['city']; ?></td>
			<td><?php echo $counselor['cin']; ?></td>
			<td><?php echo $counselor['birthday']; ?></td>
			<td><?php echo $counselor['family_status']; ?></td>
			<td><?php echo $counselor['office_phone']; ?></td>
			<td><?php echo $counselor['mobile_phone']; ?></td>
			<td><?php echo $counselor['email']; ?></td>
			<td><?php echo $counselor['fax']; ?></td>
			<td><?php echo $counselor['created']; ?></td>
			<td><?php echo $counselor['created_by']; ?></td>
			<td><?php echo $counselor['updated']; ?></td>
			<td><?php echo $counselor['updated_by']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'counselors', 'action' => 'view', $counselor['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'counselors', 'action' => 'edit', $counselor['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'counselors', 'action' => 'delete', $counselor['id']), array(), __('Are you sure you want to delete # %s?', $counselor['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Counselor'), array('controller' => 'counselors', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
