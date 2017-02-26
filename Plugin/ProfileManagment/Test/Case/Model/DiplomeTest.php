<?php
App::uses('Diplome', 'ProfileManagment.Model');

/**
 * Diplome Test Case
 *
 */
class DiplomeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.profile_managment.diplome',
		'plugin.profile_managment.diplome_type',
		'plugin.profile_managment.establishment',
		'plugin.profile_managment.qualification'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Diplome = ClassRegistry::init('ProfileManagment.Diplome');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Diplome);

		parent::tearDown();
	}

}
