<?php
App::uses('CompanyManagmentAppModel', 'CompanyManagment.Model');
/**
 * Document Model
 *
 * @property CpmCompany $CpmCompany
 * @property CpmManager $CpmManager
 * @property PfmCounselor $PfmCounselor
 */
class Edocument extends CompanyManagmentAppModel {

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
