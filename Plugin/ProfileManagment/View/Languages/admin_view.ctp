<?php

$this->extend('/Common/admin_view');
$this->viewVars['title_for_layout'] = sprintf('%s: %s', __d('croogo', 'Languages'), h($language['Language']['name']));

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('croogo', 'Languages'), array('action' => 'index'));

if (isset($language['Language']['name'])):
	$this->Html->addCrumb($language['Language']['name'], '/' . $this->request->url);
endif;

$this->set('title', __d('croogo', 'Language'));

$this->append('actions');
	echo $this->Croogo->adminAction(__d('croogo', 'Edit Language'), array(
		'action' => 'edit',
		$language['Language']['id'],
	), array(
		'button' => 'default',
	));
	echo $this->Croogo->adminAction(__d('croogo', 'Delete Language'), array(
		'action' => 'delete', $language['Language']['id'],
	), array(
		'method' => 'post',
		'button' => 'danger',
		'escapeTitle' => true,
		'escape' => false,
	),
	__d('croogo', 'Are you sure you want to delete # %s?', $language['Language']['id'])
	);
	echo $this->Croogo->adminAction(__d('croogo', 'List Languages'), array('action' => 'index'));
	echo $this->Croogo->adminAction(__d('croogo', 'New Language'), array('action' => 'add'), array('button' => 'success'));
	echo $this->Croogo->adminAction(__d('croogo', 'List Counselors'), array('controller' => 'counselors', 'action' => 'index'));
	echo $this->Croogo->adminAction(__d('croogo', 'New Counselor'), array('controller' => 'counselors', 'action' => 'add'));
$this->end();

$this->append('main');
?>
<div class="languages view">
	<dl class="inline">
		<dt><?php echo __d('croogo', 'Id'); ?></dt>
		<dd>
			<?php echo h($language['Language']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __d('croogo', 'Name'); ?></dt>
		<dd>
			<?php echo h($language['Language']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __d('croogo', 'Created'); ?></dt>
		<dd>
			<?php echo $this->Time->format($language['Language']['created'], '%Y-%m-%d %H:%M:%S', __d('croogo', 'Invalid datetime')); ?>
			&nbsp;
		</dd>
		<dt><?php echo __d('croogo', 'Created By'); ?></dt>
		<dd>
			<?php echo h($language['Language']['created_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __d('croogo', 'Updated'); ?></dt>
		<dd>
			<?php echo $this->Time->format($language['Language']['updated'], '%Y-%m-%d %H:%M:%S', __d('croogo', 'Invalid datetime')); ?>
			&nbsp;
		</dd>
		<dt><?php echo __d('croogo', 'Updated By'); ?></dt>
		<dd>
			<?php echo h($language['Language']['updated_by']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<?php $this->end(); ?>