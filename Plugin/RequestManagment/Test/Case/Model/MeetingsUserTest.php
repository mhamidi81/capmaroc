<?php
App::uses('MeetingsUser', 'RequestManagment.Model');

/**
 * MeetingsUser Test Case
 *
 */
class MeetingsUserTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.request_managment.meetings_user',
		'plugin.request_managment.user',
		'plugin.request_managment.role',
		'plugin.request_managment.meeting',
		'plugin.request_managment.meeting_judgment'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MeetingsUser = ClassRegistry::init('RequestManagment.MeetingsUser');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MeetingsUser);

		parent::tearDown();
	}

}
