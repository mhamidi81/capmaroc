<?php
App::uses('MeetingsRequest', 'RequestManagment.Model');

/**
 * MeetingsRequest Test Case
 *
 */
class MeetingsRequestTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.request_managment.meetings_request',
		'plugin.request_managment.request',
		'plugin.request_managment.company',
		'plugin.request_managment.city',
		'plugin.request_managment.country',
		'plugin.request_managment.counselor',
		'plugin.request_managment.user',
		'plugin.request_managment.role',
		'plugin.request_managment.counselors_document',
		'plugin.request_managment.document',
		'plugin.request_managment.counselors_language',
		'plugin.request_managment.language',
		'plugin.request_managment.counselors_speciality',
		'plugin.request_managment.speciality',
		'plugin.request_managment.community_activity',
		'plugin.request_managment.professional_experience',
		'plugin.request_managment.publication_research',
		'plugin.request_managment.qualification',
		'plugin.request_managment.diplome',
		'plugin.request_managment.diplome_type',
		'plugin.request_managment.establishment',
		'plugin.request_managment.companies_document',
		'plugin.request_managment.edocument',
		'plugin.request_managment.employee',
		'plugin.request_managment.status',
		'plugin.request_managment.meeting_judgment',
		'plugin.request_managment.member_judgment',
		'plugin.request_managment.request_status',
		'plugin.request_managment.meeting',
		'plugin.request_managment.judgment'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MeetingsRequest = ClassRegistry::init('RequestManagment.MeetingsRequest');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MeetingsRequest);

		parent::tearDown();
	}

}
