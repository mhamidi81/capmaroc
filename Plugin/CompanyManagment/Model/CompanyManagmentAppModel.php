<?php

App::uses('AppModel', 'Model');

class CompanyManagmentAppModel extends AppModel {
	
	public $tablePrefix = 'cpm_';
	public $actsAs = array('Containable');
}
