<?php
App::uses('ProfileManagmentAppModel', 'ProfileManagment.Model');
/**
 * Qualification Model
 *
 * @property Counselor $Counselor
 * @property Diplome $Diplome
 */
class Qualification extends ProfileManagmentAppModel {

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
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'speciality_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'diplome_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'year' => array(
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

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Counselor' => array(
			'className' => 'ProfileManagment.Counselor',
			'foreignKey' => 'counselor_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Diplome' => array(
			'className' => 'ProfileManagment.Diplome',
			'foreignKey' => 'diplome_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Establishment' => array(
			'className' => 'ProfileManagment.Establishment',
			'foreignKey' => 'establishment_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Speciality' => array(
			'className' => 'ProfileManagment.Speciality',
			'foreignKey' => 'speciality_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
