<?php

CroogoNav::add('sidebar', 'settings', array(
	'icon' => 'cog',
	'title' => __d('croogo', 'Settings'),
	'url' => array(
		'admin' => true,
		'plugin' => 'settings',
		'controller' => 'settings',
		'action' => 'prefix',
		'Site',
	),
	'weight' => 60,
	'children' => array(
		'site' => array(
			'title' => __d('croogo', 'Application'),
			'url' => array(
				'admin' => true,
				'plugin' => 'settings',
				'controller' => 'settings',
				'action' => 'prefix',
				'Site',
			),
			'weight' => 10,
		),
		'meeting' => array(
			'title' => __d('croogo', 'Réunion'),
			'url' => array(
				'admin' => true,
				'plugin' => 'settings',
				'controller' => 'settings',
				'action' => 'prefix',
				'Meeting',
			),
			'weight' => 60,
		),
		'reading' => array(
			'title' => __d('croogo', 'Reading'),
			'url' => array(
				'admin' => true,
				'plugin' => 'settings',
				'controller' => 'settings',
				'action' => 'prefix',
				'Reading',
			),
			'weight' => 30,
		),
		'service' => array(
			'title' => __d('croogo', 'Service'),
			'url' => array(
				'admin' => true,
				'plugin' => 'settings',
				'controller' => 'settings',
				'action' => 'prefix',
				'Service',
			),
			'weight' => 60,
		),

		'languages' => array(
			'title' => __d('croogo', 'Languages'),
			'url' => array(
				'admin' => true,
				'plugin' => 'settings',
				'controller' => 'languages',
				'action' => 'index',
			),
			'weight' => 70,
		),

	),
));
