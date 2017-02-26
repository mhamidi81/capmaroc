<?php
App::uses('PublicationResearch', 'ProfileManagment.Model');

/**
 * PublicationResearch Test Case
 *
 */
class PublicationResearchTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.profile_managment.publication_research',
		'plugin.profile_managment.counselor'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->PublicationResearch = ClassRegistry::init('ProfileManagment.PublicationResearch');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->PublicationResearch);

		parent::tearDown();
	}

}
