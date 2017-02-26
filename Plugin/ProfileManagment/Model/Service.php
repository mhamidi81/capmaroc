<?php
App::uses('ProfileManagmentAppModel', 'ProfileManagment.Model');
/**
 * Service Model
 *
 */
class Service extends ProfileManagmentAppModel {

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

	public function beforeDelete($cascade = true) {
	    $file = $this->field('logo');
	    
	    if(!empty($file))
	    {
	    	unlink(WWW_ROOT .'uploads'. DS . 'services'.DS.$file);
	    }
	   
	    return true;
	}	
}
