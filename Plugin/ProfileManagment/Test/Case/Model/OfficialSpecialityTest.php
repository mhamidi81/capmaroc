<?php
App::uses('OfficialSpeciality', 'ProfileManagment.Model');

/**
 * OfficialSpeciality Test Case
 *
 */
class OfficialSpecialityTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.profile_managment.official_speciality'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->OfficialSpeciality = ClassRegistry::init('ProfileManagment.OfficialSpeciality');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OfficialSpeciality);

		parent::tearDown();
	}

}
