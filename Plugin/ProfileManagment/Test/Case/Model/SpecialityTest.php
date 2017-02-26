<?php
App::uses('Speciality', 'ProfileManagment.Model');

/**
 * Speciality Test Case
 *
 */
class SpecialityTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.profile_managment.speciality',
		'plugin.profile_managment.counselor',
		'plugin.profile_managment.counselors_speciality'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Speciality = ClassRegistry::init('ProfileManagment.Speciality');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Speciality);

		parent::tearDown();
	}

}
