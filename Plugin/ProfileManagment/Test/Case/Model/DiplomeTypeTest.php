<?php
App::uses('DiplomeType', 'ProfileManagment.Model');

/**
 * DiplomeType Test Case
 *
 */
class DiplomeTypeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.profile_managment.diplome_type',
		'plugin.profile_managment.diplome'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->DiplomeType = ClassRegistry::init('ProfileManagment.DiplomeType');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->DiplomeType);

		parent::tearDown();
	}

}
