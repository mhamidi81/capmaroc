<?php
App::uses('RequestManagmentAppModel', 'RequestManagment.Model');
/**
 * Meeting Model
 *
 * @property Croogo.User $User
 * @property RequestManagment.MeetingJudgment $MeetingJudgment
 */
class Meeting extends RequestManagmentAppModel {

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
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'MeetingsRequest' => array(
			'className' => 'RequestManagment.MeetingsRequest',
			'foreignKey' => 'meeting_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'MeetingsUser' => array(
			'className' => 'RequestManagment.MeetingsUser',
			'foreignKey' => 'meeting_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

/**
 * beforeDelete
 *
 * @param boolean $cascade
 * @return boolean
 */
	public function beforeDelete($cascade = true) {
		$is_closed = $this->field('closed', array(
			'Meeting.id' => $this->id,
		));
		
		if($is_closed == 0)
		{
			/*$meeting_id = $this->id;
			$subject_tpl = Configure::read('Meeting.cancellation_email_subject');
			$body_tpl = Configure::read('Meeting.cancellation_email_body');
			$this->sendMeetingEmail($meeting_id, $subject_tpl, $body_tpl, false);*/

			$meeting_requests = $this->MeetingsRequest->find('all', array(
				'conditions' => array(
					'MeetingsRequest.meeting_id' => $this->id
				)
			));

			foreach ($meeting_requests as $key => $meeting_request) {
				
				$this->MeetingsRequest->Request->setStatus(
					'commission', 
					$meeting_request['MeetingsRequest']['request_id'],
					'Annulation de la réunion'
				);
			}
		}

		return ($is_closed == 0);
	}

/**
 * sendMeetingEmail
 *
 * @param boolean $cascade
 * @return boolean
 */
	public function sendMeetingEmail($meeting_id, $subject_tpl, $body_tpl, $attach_requests = true, $members_ids = false) {

		$meeting = $this->find('first', array('conditions' => array('Meeting.id' => $meeting_id)));
		
		$conditions = array('MeetingsUser.meeting_id' => $meeting_id);		

		if($members_ids)
		{
			$conditions = array('MeetingsUser.user_id' => $members_ids);
		}

		$members_emails = $this->MeetingsUser->find('list', array(
			'conditions' => $conditions,
			'contain' => array('User'),
			'fields' => array('User.email')
		));	

		$body = str_replace('{1}', date('d-m-Y H:i:s', strtotime($meeting['Meeting']['event_date'])), $body_tpl);
		$subject_tpl = str_replace('{1}', date('d-m-Y H:i:s', strtotime($meeting['Meeting']['event_date'])), $subject_tpl);

		if($attach_requests)
		{	
			$requests_numbers = $this->MeetingsRequest->find('list', array(
				'conditions' => array('MeetingsRequest.meeting_id' => $meeting_id),
				'contain' => array('Request'),
				'fields' => array('Request.number')
			));

			$li = '';
			
			foreach ($requests_numbers as $key => $number) {
				$li .= '<li>'.$number.'</li>';
			}
			$body_requests = '<ul>'.$li.'</ul>';
			$body = str_replace('{2}', $body_requests, $body);
		}


		$this->loadModel('MessageManagment.Message');
		
		foreach ($members_emails as $key => $email) {
			
			$this->Message->create();
			$this->Message->save(array(
				'email_from' => AuthComponent::user('email'),
				'email_to' => $email,
				'title' => $subject_tpl,
				'body' => $body,
				'mailbox' => 'inbox'
			));
		}
	}

}
