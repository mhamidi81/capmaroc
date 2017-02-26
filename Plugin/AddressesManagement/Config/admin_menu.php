<?php

CroogoNav::add('sidebar', 'addresses_management', array(
	'admin' => false,
	'icon' => 'flag',
	'title' => __d('addresses_management', 'Pays'),
	'url' => array(
		'admin' => true,
		'plugin' => 'addresses_management',
		'controller' => 'countries',
		'action' => 'index',
	),
	'weight' => 100,
	'access' => array('admin'),
	'children' => array(
		'countries' => array(
			'admin' => false,
			'title' => __d('addresses_management', 'Pays'),
			'url' => array(
				'admin' => true,
				'plugin' => 'addresses_management',
				'controller' => 'countries',
				'action' => 'index',
			),
		),
		'cities' => array(
			'admin' => false,
			'title' => __d('addresses_management', 'Villes'),
			'url' => array(
				'admin' => true,
				'plugin' => 'addresses_management',
				'controller' => 'cities',
				'action' => 'index',
			),
		),
	),
));
