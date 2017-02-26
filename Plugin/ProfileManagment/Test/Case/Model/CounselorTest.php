<?php
App::uses('Counselor', 'ProfileManagment.Model');

/**
 * Counselor Test Case
 *
 */
class CounselorTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.profile_managment.counselor',
		'plugin.profile_managment.community_activity',
		'plugin.profile_managment.professional_experience',
		'plugin.profile_managment.publication_research',
		'plugin.profile_managment.qualification',
		'plugin.profile_managment.request',
		'plugin.profile_managment.language',
		'plugin.profile_managment.counselors_language',
		'plugin.profile_managment.speciality',
		'plugin.profile_managment.counselors_speciality'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Counselor = ClassRegistry::init('ProfileManagment.Counselor');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Counselor);

		parent::tearDown();
	}

}
