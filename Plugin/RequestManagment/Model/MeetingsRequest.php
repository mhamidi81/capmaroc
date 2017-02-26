<?php
App::uses('RequestManagmentAppModel', 'RequestManagment.Model');
/**
 * MeetingsRequest Model
 *
 * @property RequestManagment.Request $Request
 * @property RequestManagment.Meeting $Meeting
 * @property RequestManagment.Judgment $Judgment
 */
class MeetingsRequest extends RequestManagmentAppModel {

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
		'meeting_id' => array(
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
		'Request' => array(
			'className' => 'RequestManagment.Request',
			'foreignKey' => 'request_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Meeting' => array(
			'className' => 'RequestManagment.Meeting',
			'foreignKey' => 'meeting_id',
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

/**
 * afterSave
 *
 * @param array $options
 * @return boolean
 */
	public function afterSave($created, $options = array()) {
		
		if($created)
		{
			$this->Request->setStatus('commission_meeting', $this->data['MeetingsRequest']['request_id']);
		}
	}

}
