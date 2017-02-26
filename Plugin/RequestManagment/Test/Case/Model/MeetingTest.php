<?php
App::uses('Meeting', 'RequestManagment.Model');

/**
 * Meeting Test Case
 *
 */
class MeetingTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.request_managment.meeting',
		'plugin.request_managment.user',
		'plugin.request_managment.meeting_judgment'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Meeting = ClassRegistry::init('RequestManagment.Meeting');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Meeting);

		parent::tearDown();
	}

}
