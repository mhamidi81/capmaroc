<?php
App::uses('ProfessionalExperience', 'ProfileManagment.Model');

/**
 * ProfessionalExperience Test Case
 *
 */
class ProfessionalExperienceTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.profile_managment.professional_experience',
		'plugin.profile_managment.counselor'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ProfessionalExperience = ClassRegistry::init('ProfileManagment.ProfessionalExperience');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ProfessionalExperience);

		parent::tearDown();
	}

}
