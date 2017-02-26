<?php

App::uses('AppModel', 'Model');

class UserManagmentAppModel extends AppModel {
	
	public $tablePrefix = 'all_';
	public $actsAs = array('Containable');
}
