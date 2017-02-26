<?php
App::uses('RequestManagmentAppModel', 'RequestManagment.Model');
/**
 * FinalJudgment Model
 *
 * @property RequestManagment.Judgment $Judgment
 */
class FinalJudgment extends RequestManagmentAppModel {

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
		'Judgment' => array(
			'className' => 'RequestManagment.Judgment',
			'foreignKey' => 'judgment_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
