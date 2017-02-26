<?php
$this->viewVars['title_for_layout'] = __d('profile_managment', 'Languages');
$this->extend('/Common/admin_index');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('profile_managment', 'Languages'), array('action' => 'index'));

$this->set('tableClass', 'table table-striped');

$this->append('table-heading');
	$tableHeaders = $this->Html->tableHeaders(array(
		$this->Paginator->sort('id'),
		$this->Paginator->sort('name'),
		$this->Paginator->sort('created'),
		$this->Paginator->sort('created_by'),
		$this->Paginator->sort('updated'),
		$this->Paginator->sort('updated_by'),
		array(__d('croogo', 'Actions') => array('class' => 'actions')),
	));
	echo $this->Html->tag('thead', $tableHeaders);
$this->end();

$this->append('table-body');
	$rows = array();
	foreach ($languages as $language):
		$row = array();
		$row[] = h($language['Language']['id']);
		$row[] = h($language['Language']['name']);
		$row[] = $this->Time->format($language['Language']['created'], '%Y-%m-%d %H:%M', __d('croogo', 'Invalid datetime'));
		$row[] = h($language['Language']['created_by']);
		$row[] = $this->Time->format($language['Language']['updated'], '%Y-%m-%d %H:%M', __d('croogo', 'Invalid datetime'));
		$row[] = h($language['Language']['updated_by']);
		$actions = array($this->Croogo->adminRowActions($language['Language']['id']));
		$actions[] = $this->Croogo->adminRowAction('', array(
			'action' => 'view', $language['Language']['id']
	), array(
			'icon' => 'eye-open',
		));
		$actions[] = $this->Croogo->adminRowAction('', array(
			'action' => 'edit',
			$language['Language']['id'],
		), array(
			'icon' => 'pencil',
		));
		$actions[] = $this->Croogo->adminRowAction('', array(
			'action' => 'delete',
			$language['Language']['id'],
		), array(
			'icon' => 'trash',
			'escape' => true,
		),
		__d('croogo', 'Are you sure you want to delete # %s?', $language['Language']['id'])
		);
		$row[] = $this->Html->div('item-actions', implode(' ', $actions));
		$rows[] = $this->Html->tableCells($row);
	endforeach;
	echo $this->Html->tag('tbody', implode('', $rows));
$this->end();
