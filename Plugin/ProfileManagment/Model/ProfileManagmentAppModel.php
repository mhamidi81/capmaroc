<?php

App::uses('AppModel', 'Model');

class ProfileManagmentAppModel extends AppModel
{
	public $tablePrefix = 'pfm_';
	public $actsAs = array('Containable');
}
