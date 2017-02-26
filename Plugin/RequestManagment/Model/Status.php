<?php
App::uses('RequestManagmentAppModel', 'RequestManagment.Model');
/**
 * Status Model
 *
 * @property RequestManagment.Request $Request
 */
class Status extends RequestManagmentAppModel {

/**
 * Behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'Containable',
	);

	public  $displayField = 'name';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

}
