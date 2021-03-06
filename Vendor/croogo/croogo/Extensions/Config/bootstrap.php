<?php

/**
 * Dashboard URL
 */
Configure::write('Croogo.dashboardUrl', array(
	'admin' => true,
	'plugin' => 'dashboard',
	'controller' => 'dashboards',
	'action' => 'index',
));

if (!CakePlugin::loaded('Migrations')) {
	CakePlugin::load('Migrations');
}
if (!CakePlugin::loaded('Settings')) {
	CakePlugin::load('Settings');
}
if (!CakePlugin::loaded('Search')) {
	CakePlugin::load('Search');
}
