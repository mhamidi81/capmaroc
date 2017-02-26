<?php
App::uses('CommunityActivity', 'ProfileManagment.Model');

/**
 * CommunityActivity Test Case
 *
 */
class CommunityActivityTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.profile_managment.community_activity',
		'plugin.profile_managment.counselor'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CommunityActivity = ClassRegistry::init('ProfileManagment.CommunityActivity');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CommunityActivity);

		parent::tearDown();
	}

}
