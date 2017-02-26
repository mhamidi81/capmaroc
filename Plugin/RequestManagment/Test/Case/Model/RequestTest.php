<?php
App::uses('Request', 'RequestManagment.Model');

/**
 * Request Test Case
 *
 */
class RequestTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.request_managment.request',
		'plugin.request_managment.counselor',
		'plugin.request_managment.meeting_judgment',
		'plugin.request_managment.member_judgment',
		'plugin.request_managment.request_status'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Request = ClassRegistry::init('RequestManagment.Request');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Request);

		parent::tearDown();
	}

}
