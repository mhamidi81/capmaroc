<?php
App::uses('CalendarManagmentAppModel', 'CalendarManagment.Model');
/**
 * Message Model
 *
 * @property Sender $Sender
 * @property Recipient $Recipient
 */
class Event extends CalendarManagmentAppModel {

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
	
		'from' => array(
			'date' => array(
				'rule' => array('datetime','ymd'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'to' => array(
			'date' => array(
				'rule' => array('datetime','ymd'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'Users.user',
			'foreignKey' => false,
			'conditions' => array('User.id = Event.user_id'),
			// 'bindingKey' => 'email',
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
/* 	public function afterSave($created, $options = array()) {
		
		if($created)
		{
			$message = $this->read();
			unset($message['Message']['id']);
			$message['Message']['mailbox'] = 'sent';
			$message['Message']['status'] = 1;
			$this->create();
			$this->save($message, array('callbacks' => false));	
			$this->_sendEmail(
				array(Configure::read('Site.title'), Configure::read('Site.email')),
				$message['Message']['email_to'],
				Configure::read('Site.title'),
				'MessageManagment.notification',
				array()
			);
		}
	} */	

}
