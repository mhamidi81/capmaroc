<?php
App::uses('User', 'UserManagment.Model');

/**
 * User Test Case
 *
 */
class UserTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.user_managment.user',
		'plugin.user_managment.role',
		'plugin.user_managment.roles_user',
		'plugin.user_managment.comment',
		'plugin.user_managment.node',
		'plugin.user_managment.meta',
		'plugin.user_managment.taxonomy',
		'plugin.user_managment.term',
		'plugin.user_managment.vocabulary',
		'plugin.user_managment.type',
		'plugin.user_managment.types_vocabulary',
		'plugin.user_managment.model_taxonomy',
		'plugin.user_managment.dashboard'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->User = ClassRegistry::init('UserManagment.User');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->User);

		parent::tearDown();
	}

}
