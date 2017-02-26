<?php
App::uses('Administration', 'RequestManagment.Model');

/**
 * Administration Test Case
 *
 */
class AdministrationTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.request_managment.administration'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Administration = ClassRegistry::init('RequestManagment.Administration');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Administration);

		parent::tearDown();
	}

}
