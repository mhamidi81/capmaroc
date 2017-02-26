<?php
App::uses('ManagersDocument', 'CompanyManagment.Model');

/**
 * ManagersDocument Test Case
 *
 */
class ManagersDocumentTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.company_managment.managers_document',
		'plugin.company_managment.manager',
		'plugin.company_managment.document'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ManagersDocument = ClassRegistry::init('CompanyManagment.ManagersDocument');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ManagersDocument);

		parent::tearDown();
	}

}
