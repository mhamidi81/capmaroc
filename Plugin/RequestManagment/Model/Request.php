<?php
App::uses('RequestManagmentAppModel', 'RequestManagment.Model');
/**
 * Request Model
 *
 * @property Counselor $Counselor
 * @property MeetingJudgment $MeetingJudgment
 * @property MemberJudgment $MemberJudgment
 * @property RequestStatus $RequestStatus
 */
class Request extends RequestManagmentAppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'counselor_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'number' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'date_request' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed


/**
 * hasOne associations
 *
 * @var array
 */
	/*public $hasOne = array(
		'Company' => array(
			'className' => 'CompanyManagment.Counselor',
			'foreignKey' => 'counselor_id',
			'conditions' => array('Request.type' => 'legal'),
			'fields' => '',
			'order' => ''
		),
		'Counselor' => array(
			'className' => 'ProfileManagment.Counselor',
			'foreignKey' => 'status_id',
			'conditions' => array('Request.type' => 'natural'),
			'fields' => '',
			'order' => ''
		)
	);*/

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Company' => array(
			'className' => 'CompanyManagment.Company',
			'foreignKey' => 'requester_id',
			'conditions' => array('Request.requester_type' => 'legal'),
			'fields' => '',
			'order' => ''
		),
		'Counselor' => array(
			'className' => 'ProfileManagment.Counselor',
			'foreignKey' => 'requester_id',
			'conditions' => array('Request.requester_type' => 'natural'),
			'fields' => '',
			'order' => ''
		),
		'Status' => array(
			'className' => 'RequestManagment.Status',
			'foreignKey' => 'status_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'AllUser' => array(
			'className' => 'UserManagment.AllUser',
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
	public $hasOne = array(
		'MeetingsRequest' => array(
			'className' => 'RequestManagment.MeetingsRequest',
			'foreignKey' => 'request_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	);

	public $hasMany = array(
		'MembersRequest' => array(
			'className' => 'RequestManagment.MembersRequest',
			'foreignKey' => 'request_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'RequestStatus' => array(
			'className' => 'RequestManagment.RequestStatus',
			'foreignKey' => 'request_id',
			'dependent' => false,
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

	public $statuses_by_role = array(
		'secretary' => array('pending_completely', 'profile_validation'), 
		'commission_member' => array('commission', 'commission_meeting'), 
		'director' => array('commission', 'commission_meeting', 'profile_validated'), 
		'minister' => array('ministry')
	);

	public $requests_types = array(
		'legal' => 'Morale', 
		'natural' => 'Physique'
	);

/**
* getRequestType
* @param $id request identifier
* @return mixed $user_id request sender user_id
*/
	public function identifyRequester($id){
		$data = false;

		$request = $this->find('first', array(
			'fields' => array('requester_type', 'requester_id'),
			'conditions' => array('Request.id' => $id)
		));
		
		if(!empty($request))
		{
			$data['requester_type'] = $request['Request']['requester_type'];
			$data['requester_id'] = $request['Request']['requester_id'];
		}

		return $data;
	}
/**
* getRequestUserId
* @param $id request identifier
* @return mixed $user_id request sender user_id
*/
	public function getRequesterEmail($id){
		$email = false;

		$request = $this->find('first', array(
			'conditions' => array('Request.id' => $id),
			'contain' => array('AllUser'),
		));
		
		if(!empty($request))
		{
			$email = $request['AllUser']['email'];
		}

		return $email;
	}
/**
 * setStatus method
 * @param $status String
 * @return bool
 */
	public function setStatus($status, $request_id, $description = '', $id = '') {
		$result = true;
		/*$request = $this->Request->find('first', array(
			'conditions' => array('Request.id' => $id)
		));*/

		$status_id = $this->Status->field('id', array(
			'Status.alias' => $status
		));

		$this->id = $request_id;
		$result = $this->saveField('status_id', $status_id);
		$this->RequestStatus->create();
		$this->RequestStatus->save(array(
			'id' => $id,
			'request_id' => $this->id, 
			'status_id' => $status_id,
			'user_id' => AuthComponent::user('id'),
			'event_date' => date('Y-m-d h:i:s'),
			'description' => $description
		));

		return $result;
	}
}
