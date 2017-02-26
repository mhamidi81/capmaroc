<?php
App::uses('Qualification', 'ProfileManagment.Model');

/**
 * Qualification Test Case
 *
 */
class QualificationTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.profile_managment.qualification',
		'plugin.profile_managment.counselor',
		'plugin.profile_managment.diplome'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Qualification = ClassRegistry::init('ProfileManagment.Qualification');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Qualification);

		parent::tearDown();
	}

}
