<?php
App::uses('CounselorsSpeciality', 'ProfileManagment.Model');

/**
 * CounselorsSpeciality Test Case
 *
 */
class CounselorsSpecialityTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.profile_managment.counselors_speciality',
		'plugin.profile_managment.counselor',
		'plugin.profile_managment.speciality'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CounselorsSpeciality = ClassRegistry::init('ProfileManagment.CounselorsSpeciality');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CounselorsSpeciality);

		parent::tearDown();
	}

}
