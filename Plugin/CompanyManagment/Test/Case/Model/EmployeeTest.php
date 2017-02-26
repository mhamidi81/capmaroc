<?php
App::uses('Employee', 'CompanyManagment.Model');

/**
 * Employee Test Case
 *
 */
class EmployeeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.company_managment.employee',
		'plugin.company_managment.company',
		'plugin.company_managment.city',
		'plugin.company_managment.country',
		'plugin.company_managment.counselor',
		'plugin.company_managment.user',
		'plugin.company_managment.role',
		'plugin.company_managment.counselors_document',
		'plugin.company_managment.document',
		'plugin.company_managment.counselors_language',
		'plugin.company_managment.language',
		'plugin.company_managment.counselors_speciality',
		'plugin.company_managment.speciality',
		'plugin.company_managment.community_activity',
		'plugin.company_managment.professional_experience',
		'plugin.company_managment.publication_research',
		'plugin.company_managment.qualification',
		'plugin.company_managment.diplome',
		'plugin.company_managment.diplome_type',
		'plugin.company_managment.establishment',
		'plugin.company_managment.request',
		'plugin.company_managment.status',
		'plugin.company_managment.meeting_judgment',
		'plugin.company_managment.meeting',
		'plugin.company_managment.judgment',
		'plugin.company_managment.member_judgment',
		'plugin.company_managment.request_status',
		'plugin.company_managment.companies_document',
		'plugin.company_managment.edocument'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Employee = ClassRegistry::init('CompanyManagment.Employee');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Employee);

		parent::tearDown();
	}

}
