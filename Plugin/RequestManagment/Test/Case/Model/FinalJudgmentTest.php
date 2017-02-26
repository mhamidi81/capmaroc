<?php
App::uses('FinalJudgment', 'RequestManagment.Model');

/**
 * FinalJudgment Test Case
 *
 */
class FinalJudgmentTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.request_managment.final_judgment',
		'plugin.request_managment.judgment'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->FinalJudgment = ClassRegistry::init('RequestManagment.FinalJudgment');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->FinalJudgment);

		parent::tearDown();
	}

}
