<?php
App::uses('RequestManagmentAppModel', 'RequestManagment.Model');
/**
 * MembersRequest Model
 *
 * @property Users.User $User
 * @property RequestManagment.Request $Request
 * @property RequestManagment.Judgment $Judgment
 */
class MembersRequest extends RequestManagmentAppModel {

/**
 * Behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'Containable',
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'request_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'judgment_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'Users.User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Request' => array(
			'className' => 'RequestManagment.Request',
			'foreignKey' => 'request_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Judgment' => array(
			'className' => 'RequestManagment.Judgment',
			'foreignKey' => 'judgment_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
