<?php
App::uses('ProfileManagmentAppModel', 'ProfileManagment.Model');
/**
 * Speciality Model
 *
 * @property Counselor $Counselor
 */
class Speciality extends ProfileManagmentAppModel {

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


	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
		public $belongsTo = array(
			'OfficialSpeciality' => array(
				'className' => 'ProfileManagment.OfficialSpeciality',
				'foreignKey' => 'official_speciality_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);	
}
