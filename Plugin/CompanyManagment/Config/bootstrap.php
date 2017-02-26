<?php

//Croogo::hookRoutes('Example');
//Croogo::hookBehavior('Node', 'Example.Example', array());
//Croogo::hookComponent('*', 'Example.Example');
//Croogo::hookHelper('Nodes', 'Example.Example');

/**
 * Admin menu (navigation)
 */
/*CroogoNav::add('profiles', array(
	'title' => 'Profiles',
	'icon' => array('file', 'large'),
	'url' => '#',
	'weight' => 10,
	'children' => array(
		'counselors' => array(
			'title' => __d('capmaroc', 'Counselors'),
			'url' => array(
				'admin' => true,
				'plugin' => 'profile_managment',
				'controller' => 'counselors',
				'action' => 'index',
			),
		),
		'professional_experiences' => array(
			'title' => __d('capmaroc', 'Experiences'),
			'url' => array(
				'admin' => true,
				'plugin' => 'profile_managment',
				'controller' => 'professional_experiences',
				'action' => 'index',
			),
		),
		'publication_researches' => array(
			'title' => __d('capmaroc', 'Publications'),
			'url' => array(
				'admin' => true,
				'plugin' => 'profile_managment',
				'controller' => 'publication_researches',
				'action' => 'index',
			),
		),
		'community_activities' => array(
			'title' => __d('capmaroc', 'Activities'),
			'url' => array(
				'admin' => true,
				'plugin' => 'profile_managment',
				'controller' => 'community_activities',
				'action' => 'index',
			),
		),
		'option' => array(
			'title' => __d('capmaroc', 'Options'),
			'url' => '#',
			'children' => array(
				'diplome_types' => array(
					'title' => __d('capmaroc', 'Diplome type'),
					'url' => array(
						'admin' => true,
						'plugin' => 'profile_managment',
						'controller' => 'diplome_types',
						'action' => 'index',
					),
				),
				'diplomes' => array(
					'title' => __d('capmaroc', 'Diplome'),
					'url' => array(
						'admin' => true,
						'plugin' => 'profile_managment',
						'controller' => 'diplomes',
						'action' => 'index',
					),
				),
				'establishments' => array(
					'title' => __d('capmaroc', 'Establishments'),
					'url' => array(
						'admin' => true,
						'plugin' => 'profile_managment',
						'controller' => 'establishments',
						'action' => 'index',
					),
				),
				'languages' => array(
					'title' => __d('capmaroc', 'Languages'),
					'url' => array(
						'admin' => true,
						'plugin' => 'profile_managment',
						'controller' => 'languages',
						'action' => 'index',
					),
				),
				'specialities' => array(
					'title' => __d('capmaroc', 'Speciality'),
					'url' => array(
						'admin' => true,
						'plugin' => 'profile_managment',
						'controller' => 'specialities',
						'action' => 'index',
					),
				),
			),
		),
	),
));
*/
/*$Localization = new L10n();
Croogo::mergeConfig('Wysiwyg.actions', array(
	'Example/admin_rte_example' => array(
		array(
			'elements' => 'ExampleBasic',
			'preset' => 'basic',
		),
		array(
			'elements' => 'ExampleStandard',
			'preset' => 'standard',
			'language' => 'ja',
		),
		array(
			'elements' => 'ExampleFull',
			'preset' => 'full',
			'language' => $Localization->map(Configure::read('Site.locale')),
		),
		array(
			'elements' => 'ExampleCustom',
			'toolbar' => array(
				array('Format', 'Bold', 'Italic'),
				array('Copy', 'Paste'),
			),
			'uiColor' => '#ffe79a',
			'language' => 'fr',
		),
	),
));*/

/**
 * Admin row action
 *
 * When browsing the content list in admin panel (Content > List),
 * an extra link called 'Example' will be placed under 'Actions' column.
 */
//Croogo::hookAdminRowAction('Nodes/admin_index', 'Example', 'plugin:example/controller:example/action:index/:id');

/* Row action with link options */
/*Croogo::hookAdminRowAction('Nodes/admin_index', 'Button with Icon', array(
	'plugin:example/controller:example/action:index/:id' => array(
		'options' => array(
			'icon' => 'key',
			'button' => 'success',
		),
	),
));*/

/* Row action with icon */
/*Croogo::hookAdminRowAction('Nodes/admin_index', 'Icon Only', array(
	'plugin:example/controller:example/action:index/:id' => array(
		'title' => false,
		'options' => array(
			'icon' => 'picture',
			'tooltip' => array(
				'data-title' => 'A nice and simple action with tooltip',
				'data-placement' => 'left',
			),
		),
	),
));*/

/* Row action with confirm message */
/*Croogo::hookAdminRowAction('Nodes/admin_index', 'Reload Page', array(
	'admin:true/plugin:nodes/controller:nodes/action:index' => array(
		'title' => false,
		'options' => array(
			'icon' => 'refresh',
			'tooltip' => 'Reload page',
		),
		'confirmMessage' => 'Reload this page?',
	),
));*/

/**
 * Admin tab
 *
 * When adding/editing Content (Nodes),
 * an extra tab with title 'Example' will be shown with markup generated from the plugin's admin_tab_node element.
 *
 * Useful for adding form extra form fields if necessary.
 */
//Croogo::hookAdminTab('Nodes/admin_add', 'Example', 'example.admin_tab_node');
//Croogo::hookAdminTab('Nodes/admin_edit', 'Example', 'example.admin_tab_node');
