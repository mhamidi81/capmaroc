<?php
App::uses('Judgment', 'RequestManagment.Model');

/**
 * Judgment Test Case
 *
 */
class JudgmentTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.request_managment.judgment'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Judgment = ClassRegistry::init('RequestManagment.Judgment');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Judgment);

		parent::tearDown();
	}

}
