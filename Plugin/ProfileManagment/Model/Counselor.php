<?php
App::uses('ProfileManagmentAppModel', 'ProfileManagment.Model');
/**
 * Counselor Model
 *
 * @property CommunityActivity $CommunityActivity
 * @property ProfessionalExperience $ProfessionalExperience
 * @property PublicationResearch $PublicationResearch
 * @property Qualification $Qualification
 * @property Request $Request
 * @property Language $Language
 * @property Speciality $Speciality
 */
class Counselor extends ProfileManagmentAppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'first_name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'last_name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'cin' => array(
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

	public $virtualFields = array(
	    'full_name' => 'CONCAT(Counselor.first_name, " ", Counselor.last_name)'
	);

	public  $displayField = 'full_name';

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
		'City' => array(
			'className' => 'AddressesManagement.City',
			'foreignKey' => 'city_id',
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
		'CounselorsDocument' => array(
			'className' => 'ProfileManagment.CounselorsDocument',
			'foreignKey' => 'counselor_id',
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
		'CounselorsLanguage' => array(
			'className' => 'ProfileManagment.CounselorsLanguage',
			'foreignKey' => 'counselor_id',
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
		'CounselorsSpeciality' => array(
			'className' => 'ProfileManagment.CounselorsSpeciality',
			'foreignKey' => 'counselor_id',
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
		'CommunityActivity' => array(
			'className' => 'ProfileManagment.CommunityActivity',
			'foreignKey' => 'counselor_id',
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
		'ProfessionalExperience' => array(
			'className' => 'ProfileManagment.ProfessionalExperience',
			'foreignKey' => 'counselor_id',
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
		'PublicationResearch' => array(
			'className' => 'ProfileManagment.PublicationResearch',
			'foreignKey' => 'counselor_id',
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
		'Qualification' => array(
			'className' => 'ProfileManagment.Qualification',
			'foreignKey' => 'counselor_id',
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
		'Request' => array(
			'className' => 'RequestManagment.Request',
			'foreignKey' => 'counselor_id',
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

}
