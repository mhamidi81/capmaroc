<?php
App::uses('CompanyCounselor', 'ProfileManagment.Model');

/**
 * CompanyCounselor Test Case
 *
 */
class CompanyCounselorTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.profile_managment.company_counselor',
		'plugin.profile_managment.counselor',
		'plugin.profile_managment.company'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CompanyCounselor = ClassRegistry::init('ProfileManagment.CompanyCounselor');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CompanyCounselor);

		parent::tearDown();
	}

}
