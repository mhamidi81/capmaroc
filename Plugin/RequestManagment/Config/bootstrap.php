<?php


/**
 * Admin menu (navigation)
 */

CroogoNav::add('sidebar', 'stats', array(
	'admin' => false,
	'title' => 'Etats & statistiques',
	'icon' => 'dashboard',
	'url' => '#',
	'children' => array(
		'dashboard' => array(
			'icon' => 'pie-chart',
			'title' => __d('croogo', 'Tableau de bord'),
			'url' => array(
				'admin' => true,
				'plugin' => 'dashboard',
				'controller' => 'dashboards',
				'action' => 'index',
			),
			'weight' => 0,
			'children' => array(),
		),
		'requests_stats' => array(
			'icon' => 'email',
			'title' => __d('croogo', 'Demandes'),
			'url' => array(
				'admin' => true,
				'plugin' => 'request_managment',
				'controller' => 'requests',
				'action' => 'filter',
			),
			'children' => array(),
		),
		'counselor_stats' => array(
			'icon' => 'user',
			'title' => __d('croogo', 'Conseillers'),
			'url' => array(
				'admin' => true,
				'plugin' => 'profile_managment',
				'controller' => 'counselors',
				'action' => 'filter',
			),
			'weight' => 0,
			'children' => array(),
		)
	)
));

CroogoNav::add('sidebar', 'usm_users', array(
	'admin' => false,
	'title' => 'Utilisateurs',
	'icon' => 'user',
	'url' => array(
		'admin' => true,
		'plugin' => 'user_managment',
		'controller' => 'users',
		'action' => 'index',
	),
	'children' => array(),
));

CroogoNav::add('sidebar', 'requests', array(
	'admin' => false,
	'title' => 'Demandes',
	'icon' => 'folder',
	'url' => array(
		'admin' => true,
		'plugin' => 'request_managment',
		'controller' => 'requests',
		'action' => 'index',
	),
	'children' => array(),
));
CroogoNav::add('sidebar', 'meetings', array(
	'admin' => false,
	'title' => 'Réunion',
	'icon' => 'calendar',
	'url' => array(
		'admin' => true,
		'plugin' => 'request_managment',
		'controller' => 'meetings',
		'action' => 'index',
	),
	'children' => array(),
));

CroogoNav::add('sidebar', 'services', array(
	'admin' => false,
	'title' => 'Services',
	'icon' => 'bookmark',
	'url' => array(
		'admin' => true,
		'plugin' => 'profile_managment',
		'controller' => 'services',
		'action' => 'index',
	),
	'children' => array(),
));

CroogoNav::add('sidebar', 'etablissements', array(
	'admin' => false,
	'title' => 'Etablissements',
	'icon' => 'bookmark',
	'url' => array(
		'admin' => true,
		'plugin' => 'profile_managment',
		'controller' => 'establishments',
		'action' => 'index',
	),
	'children' => array(),
));
CroogoNav::add('sidebar', 'diplomats', array(
	'admin' => false,
	'title' => 'Diplômes',
	'icon' => 'medall',
	'url' => array(
		'admin' => true,
		'plugin' => 'profile_managment',
		'controller' => 'diplomes',
		'action' => 'index',
	),
	'children' => array(),
));
CroogoNav::add('sidebar', 'specialities', array(
	'admin' => false,
	'title' => 'Spécialités',
	'icon' => 'view-list',
	'url' => array(
		'admin' => true,
		'plugin' => 'profile_managment',
		'controller' => 'specialities',
		'action' => 'index',
	),
	'children' => array(),
));
CroogoNav::add('sidebar', 'official_specialities', array(
	'admin' => false,
	'title' => "Spécialités d'agrément",
	'icon' => 'view-list',
	'url' => array(
		'admin' => true,
		'plugin' => 'profile_managment',
		'controller' => 'official_specialities',
		'action' => 'index',
	),
	'children' => array(),
));
CroogoNav::add('sidebar', 'cities', array(
	'admin' => false,
	'title' => 'Villes',
	'icon' => 'direction',
	'url' => array(
		'admin' => true,
		'plugin' => 'addresses_management',
		'controller' => 'cities',
		'action' => 'index',
	),
	'children' => array(),
));
CroogoNav::add('sidebar', 'regions', array(
	'admin' => false,
	'title' => 'Regions',
	'icon' => 'direction-alt',
	'url' => array(
		'admin' => true,
		'plugin' => 'addresses_management',
		'controller' => 'regions',
		'action' => 'index',
	),
	'children' => array(),
));