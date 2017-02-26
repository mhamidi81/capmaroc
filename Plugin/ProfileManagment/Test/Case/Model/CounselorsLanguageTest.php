<?php
App::uses('CounselorsLanguage', 'ProfileManagment.Model');

/**
 * CounselorsLanguage Test Case
 *
 */
class CounselorsLanguageTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.profile_managment.counselors_language',
		'plugin.profile_managment.language',
		'plugin.profile_managment.counselor'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CounselorsLanguage = ClassRegistry::init('ProfileManagment.CounselorsLanguage');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CounselorsLanguage);

		parent::tearDown();
	}

}
