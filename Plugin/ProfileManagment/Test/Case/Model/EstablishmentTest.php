<?php
App::uses('Establishment', 'ProfileManagment.Model');

/**
 * Establishment Test Case
 *
 */
class EstablishmentTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.profile_managment.establishment',
		'plugin.profile_managment.qualification',
		'plugin.profile_managment.counselor',
		'plugin.profile_managment.user',
		'plugin.profile_managment.role',
		'plugin.profile_managment.counselors_document',
		'plugin.profile_managment.document',
		'plugin.profile_managment.counselors_language',
		'plugin.profile_managment.language',
		'plugin.profile_managment.counselors_speciality',
		'plugin.profile_managment.speciality',
		'plugin.profile_managment.community_activity',
		'plugin.profile_managment.professional_experience',
		'plugin.profile_managment.publication_research',
		'plugin.profile_managment.request',
		'plugin.profile_managment.company',
		'plugin.profile_managment.city',
		'plugin.profile_managment.country',
		'plugin.profile_managment.companies_document',
		'plugin.profile_managment.edocument',
		'plugin.profile_managment.employee',
		'plugin.profile_managment.status',
		'plugin.profile_managment.meetings_request',
		'plugin.profile_managment.meeting',
		'plugin.profile_managment.judgment',
		'plugin.profile_managment.members_request',
		'plugin.profile_managment.request_status',
		'plugin.profile_managment.diplome',
		'plugin.profile_managment.diplome_type'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Establishment = ClassRegistry::init('ProfileManagment.Establishment');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Establishment);

		parent::tearDown();
	}

}
