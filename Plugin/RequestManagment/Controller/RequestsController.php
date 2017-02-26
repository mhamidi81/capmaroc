<?php
App::uses('RequestManagmentAppController', 'RequestManagment.Controller');
/**
 * Requests Controller
 *
 * @property Request $Request
 * @property PaginatorComponent $Paginator
 */
class RequestsController extends RequestManagmentAppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * beforeFilter method
 *
 * @return void
 */
	function beforeFilter() { 
		parent::beforeFilter();

		$this->Security->csrfCheck = false;
		$this->Security->validatePost = false;
	}
/**
 * admin_archive_request method
 *
 * @return void
 */
	public function admin_print_granted_request_decision($request_id) {
		$this->layout = 'pdf';
		$meeting = array();

		if(!empty($request_id)){
			
			if (!$this->Request->exists($request_id)) {
				$message =__d('request_managment', 'Invalid  identifiant');
				$this->redirect($this->referer());
			}
			else
			{
				$request = $this->Request->find('first', array(
					'conditions' => array(
						'Request.id' => $request_id,
						'Status.alias' => 'granted'
					),
					'contain' => array('Status', 'Counselor', 'Company' => array('Counselor.CounselorsDocument.Document', 'City', 'CompaniesDocument.Edocument'))
				));

				$counselor = $this->Request->Counselor->find('first', array(
					'conditions' => array(
						'Counselor.id' => ($request['Request']['requester_type'] == "natural")? $request['Request']['requester_id'] : $request['Company']['counselor_id']
					),
					'contain' => array(
						'Qualification' => array('Establishment' , 'Diplome.DiplomeType', 'Speciality')
					)
				));

			}
		}

		$this->set(compact('request', 'counselor'));
	}
	
	public function admin_print_granted_request_badge($request_id) {
		$this->layout = 'pdf';
		$meeting = array();

		if(!empty($request_id)){
			
			if (!$this->Request->exists($request_id)) {
				$message =__d('request_managment', 'Invalid  identifiant');
				$this->redirect($this->referer());
			}
			else
			{
				$request = $this->Request->find('first', array(
					'conditions' => array(
						'Request.id' => $request_id,
						'Status.alias' => 'granted'
					),
					'contain' => array('MeetingsRequest', 'Status', 'Counselor', 'Company' => array('Counselor.CounselorsDocument.Document', 'City', 'CompaniesDocument.Edocument'))
				));

				$counselor = $this->Request->Counselor->find('first', array(
					'conditions' => array(
						'Counselor.id' => ($request['Request']['requester_type'] == "natural")? $request['Request']['requester_id'] : $request['Company']['counselor_id']
					),
					'contain' => array(
						'Qualification' => array('Establishment' , 'Diplome.DiplomeType', 'Speciality')
					)
				));

				$specialities = json_decode($request['MeetingsRequest']['specialities'], true);
				$this->loadModel('ProfileManagment.OfficialSpeciality');
				
				if(empty($specialities))
				{
					$specialities = array(-1);
				}
				$specialities = $this->OfficialSpeciality->find('list', array('OfficialSpeciality.id' => $specialities));
			}
		}

		$this->set(compact('request', 'counselor', 'specialities'));
	}

/**
 * save_meeting_request_judgment method
 *
 * @return void
 */
	public function admin_save_meeting_request_judgment() {
		
		$result = 'error';
		$message =__d('request_managment','Une erreur est survenue durant la validation de profil réssayer svp! ');
		$meetings_request = isset($this->request['data']['MeetingsRequest'])? $this->request['data']['MeetingsRequest'] : '';
		
		if(isset($meetings_request['request_id']) && !empty($meetings_request['judgment_id']) && !empty($meetings_request['meeting_id']) && !empty($meetings_request['specialities'])){
			
			if (!$this->Request->exists($meetings_request['request_id'])) {
				$message =__d('profile_managment', 'Invalid demande identifiant');
			}
			else
			{
				$id = $this->Request->MeetingsRequest->field('id', array(
					'MeetingsRequest.request_id' => $meetings_request['request_id'],
					'MeetingsRequest.meeting_id' => $meetings_request['meeting_id'],
				));
				
				if($id)
				{
					$this->Request->MeetingsRequest->id = $id;
				}

				$valid = $this->Request->MeetingsRequest->save(array(
					'request_id' => $meetings_request['request_id'],
					'meeting_id' => $meetings_request['meeting_id'],
					'judgment_id' => $meetings_request['judgment_id'],
					'specialities' => json_encode($meetings_request['specialities']),
					'description' => isset($meetings_request['description'])? $meetings_request['description'] : ''
				));

				if($valid){
					$message = __d('request_managment',"L'avis de la commission a été bien enregistré");
					$result = 'success';
				}
			}
		}

		$data = array('message' =>  $message, 'result' => $result, 'errors' => array());
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}

/**
 * save_member_request_judgment method
 *
 * @return void
 */
	public function admin_save_member_request_judgment() {

		$result = 'error';
		$message =__d('request_managment','Une erreur est survenue durant la validation de profil réssayer svp! ');
		$members_request = isset($this->request['data']['MembersRequest'])? $this->request['data']['MembersRequest'] : '';
		
		if(isset($members_request['request_id']) && !empty($members_request['judgment_id']) && !empty($members_request['specialities'])){
			
			if (!$this->Request->exists($members_request['request_id'])) {
				$message =__d('profile_managment', 'Invalid demande identifiant');
			}
			else
			{
				$id = $this->Request->MembersRequest->field('id', array(
					'MembersRequest.request_id' => $members_request['request_id'],
					'MembersRequest.user_id' => $this->Auth->User('id'),
				));
				
				if($id)
				{
					$this->Request->MembersRequest->id = $id;
				}

				$valid = $this->Request->MembersRequest->save(array(
					'request_id' => $members_request['request_id'],
					'user_id' => $this->Auth->user('id'),
					'judgment_id' => $members_request['judgment_id'],
					'specialities' => json_encode($members_request['specialities']),
					'event_date' => Date('Y-m-d'),
					'description' => isset($members_request['description'])? $members_request['description'] : ''
				));
				
				if($valid){
					$message = __d('request_managment','Votre avis a été enregistré avec succès');
					$result = 'success';
				}
			}
		}

		$data = array('message' =>  $message, 'result' => $result, 'errors' => array());
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}
/**
 * validate_document_reception method
 *
 * @return void
 */
	public function admin_validate_document_reception() {

		$result = 'error';
		$message =__d('request_managment', 'Une erreur est survenue durant la confirmation de réception du dossier papier!');
		$request = array();

		if(isset($this->request['data']['qr_code'])){
			$qr_code = $this->request['data']['qr_code'];
			
			$request = $this->Request->find('first', array(
				'fields' => array('qr_code', 'id'),
				'conditions' => array(
					"Request.qr_code = '{$qr_code}'",
					'Status.alias' => 'pending_postale_papers'
				),
				'contain' => array('Status')
			));
			
			if (empty($request)) {
				$message =__d('request_managment',"Le code à barre n'est pas valide!");
			}
			else
			{
				if($this->Request->setStatus('profile_validation', $request['Request']['id'])){
					$message = __d('request_managment','La réception du dossier papier a été confirmé avec succès');
					$result = 'success';
				}				
			}
		}

		$data = array('message' =>  $message, 'result' => $result, 'errors' => array(), 'data' => $request);
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}
/**
 * admin_validate_requester method
 *
 * @return void
 */
	public function admin_rollback_request_status_to_creation() {

		$result = 'error';
		$message =__d('request_managment','Une erreur est survenue durant la sauvegarde');

		if(isset($this->request['data']['Request']['id'])){
			$id = $this->request['data']['Request']['id'];
			
			if (!$this->Request->exists($id)) {
				$message =__d('profile_managment', 'Invalid demande identifiant');
			}
			else
			{

				if($this->Request->setStatus('creation', $id, 'Demande de complément dossier éléctronique'))
				{
					$message = __d('request_managment',"Opération faite avec succès");
					$result = 'success';

					if(isset($this->request['data']['Message']['title']) && isset($this->request['data']['Message']['body']))
					{

						$this->loadModel('MessageManagment.Message');
						$m_status = $this->Message->save(array(
							'email_from' => $this->Auth->user('email'),
							'email_to' => $this->Request->getRequesterEmail($id),
							'title' => $this->request['data']['Message']['title'],
							'body' => $this->request['data']['Message']['body'],
							'mailbox' => 'inbox'
						));

						if($m_status)
						{
							$message.= ' , un courriel de notification a été envoyé';
						}				
					}					
				}

			}
		}


		$data = array('message' =>  $message, 'result' => $result, 'errors' => array());
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}
/**
 * admin_rollback_request_status_to_profile_validation method
 *
 * @return void
 */
	public function admin_rollback_request_status_to_profile_validation() {

		$result = 'error';
		$message =__d('request_managment','Une erreur est survenue durant la sauvegarde');

		if(isset($this->request['data']['Request']['id'])){
			$id = $this->request['data']['Request']['id'];
			
			if (!$this->Request->exists($id)) {
				$message =__d('profile_managment', 'Invalid demande identifiant');
			}
			else
			{
				$old_status = $this->Request->RequestStatus->find('first', array(
					'fields' => array('Status.alias', 'AllUser.email'),
					'conditions' => array(
						'RequestStatus.request_id' => $id,
						'Status.alias' => 'profile_validated'
					),
					'contain' => array('Status', 'AllUser'),
					'order' => 'Status.order desc'
				));


				if(!empty($old_status) && $this->Request->setStatus('profile_validation', $id, 'Demande de vérification'))
				{
					$message = __d('request_managment',"Opération faite avec succès");
					$result = 'success';
					
					if(isset($this->request['data']['Message']['title']) && isset($this->request['data']['Message']['body']))
					{

						$this->loadModel('MessageManagment.Message');
						$m_status = $this->Message->save(array(
							'email_from' => $this->Auth->user('email'),
							'email_to' => $old_status['AllUser']['email'],
							'title' => $this->request['data']['Message']['title'],
							'body' => $this->request['data']['Message']['body'],
							'mailbox' => 'inbox'
						));

						if($m_status)
						{
							$message.= ' , un courriel de notification a été envoyé';
						}				
					}					
				}

			}
		}


		$data = array('message' =>  $message, 'result' => $result, 'errors' => array());
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}
/**
 * admin_completely_required method
 *
 * @return void
 */
	public function admin_completely_required() {

		$result = 'error';
		$message =__d('request_managment','Une erreur est survenue durant la validation de profil réssayer svp! ');

		if(isset($this->request['data']['Request']['id'])){
			$id = $this->request['data']['Request']['id'];
			
			if (!$this->Request->exists($id)) {
				$message =__d('profile_managment', 'Invalid demande identifiant');
			}
			elseif($this->Request->setStatus('pending_completely', $id))
			{
				$message = __d('request_managment','Le dossier a été marqué comme incomplèt');
				$result = 'success';
				
				if(isset($this->request['data']['Message']['title']) && isset($this->request['data']['Message']['body']))
				{

					$recipient_email = $this->Request->getRequesterEmail($id);

					$this->loadModel('MessageManagment.Message');
					$m_status = $this->Message->save(array(
						'email_from' => $this->Auth->user('email'),
						'email_to' => $recipient_email,
						'title' => $this->request['data']['Message']['title'],
						'body' => $this->request['data']['Message']['body'],
						'mailbox' => 'inbox'
					));

					if($m_status)
					{
						$message.= ' , un courriel de notification a été envoyé au bénéficiaire';
					}				
				}
			}
		}


		$data = array('message' =>  $message, 'result' => $result, 'errors' => array());
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}

/**
 * admin_receive_request_completely method
 *
 * @return void
 */
	public function admin_receive_request_completely() {

		$result = 'error';
		$message =__d('request_managment','Une erreur est survenue durant la validation de profil réssayer svp! ');

		if(isset($this->request['data']['id'])){
			$id = $this->request['data']['id'];

			if (!$this->Request->exists($id)) {
				$message =__d('profile_managment', 'Invalid demande identifiant');
			}
			else
			{
				if($this->Request->setStatus('profile_validation', $id)){
					$message = __d('request_managment','Le dossier a été confirmé complet avec succès');
					$result = 'success';
				}
			}
		}

		$data = array('message' =>  $message, 'result' => $result, 'errors' => array());
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}
/**
 * admin_validate_requester method
 *
 * @return void
 */
 
 /*
	public function admin_invalidate_requester() {

		$result = 'error';
		$message =__d('request_managment','Une erreur est survenue durant la validation de profil réssayer svp! ');

		if(isset($this->request['data']['id'])){
			$id = $this->request['data']['id'];

			if (!$this->Request->exists($id)) {
				$message =__d('profile_managment', 'Invalid demande identifiant');
			}
			else
			{
				
				if($this->Request->setStatus('profile_validation', $id)){
					$message = __d('request_managment','Le dossier a été envoyé à la direction avec succès');
					$result = 'success';
				}
			}
		}

		$data = array('message' =>  $message, 'result' => $result, 'errors' => array());
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}*/
/**
 * admin_validate_requester method
 *
 * @return void
 */
 
	public function admin_rejecte_request() {

		$result = 'error';
		$message =__d('request_managment','Une erreur est survenue durant la sauvegarde ');

		if(isset($this->request['data']['id'])){
			$id = $this->request['data']['id'];

			if (!$this->Request->exists($id)) {
				$message =__d('profile_managment', 'Invalid demande identifiant');
			}
			else
			{
				
				if($this->Request->setStatus('profile_validation', $id)){
					$message = __d('request_managment','Le dossier a été bien rejeté');
					$result = 'success';
				}
			}
		}

		$data = array('message' =>  $message, 'result' => $result, 'errors' => array());
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}
/**
 * admin_archive_request method
 *
 * @return void
 */
	public function admin_archive_request() {

		$result = 'error';
		$message =__d('request_managment','Une erreur est survenue durant la sauvegarde ');

		if(isset($this->request['data']['id'])){
			$id = $this->request['data']['id'];

			if (!$this->Request->exists($id)) {
				$message =__d('profile_managment', 'Invalid demande identifiant');
			}
			else
			{
				$this->Request->id = $id;

				if($this->Request->saveField('archived', 1)){
					$message = __d('request_managment','Le dossier a été bien archivé');
					$result = 'success';
				}
			}
		}

		$data = array('message' =>  $message, 'result' => $result, 'errors' => array());
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}

/**
 * admin_send_to_coordinator method
 *
 * @return void
 */
	public function admin_send_to_coordinator() {

		$result = 'error';
		$message =__d('request_managment','Une erreur est survenue durant la validation de profil réssayer svp! ');

		if(isset($this->request['data']['id'])){
			$id = $this->request['data']['id'];

			if (!$this->Request->exists($id)) {
				$message =__d('profile_managment', 'Invalid demande identifiant');
			}
			else
			{
				
				if($this->Request->setStatus('profile_validated', $id)){
					$message = __d('request_managment','Le dossier a été envoyé au coordinateur');
					$result = 'success';
				}
			}
		}

		$data = array('message' =>  $message, 'result' => $result, 'errors' => array());
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}

/**
 * admin_validate_requester method
 *
 * @return void
 */
	public function admin_send_to_commission() {

		$message =__d('request_managment','Une erreur est survenue durant la validation de profil réssayer svp! ');
		$result = 'error';

		if(isset($this->request['data']['id'])){
			$id = $this->request['data']['id'];
			
			if (!$this->Request->exists($id)) {
				$message =__d('profile_managment', 'Invalid demande identifiant');
			}
			else
			{
				if($this->Request->setStatus('commission', $id)){
					$message = __d('request_managment','Le dossier a été envoyé à la direction avec succès');
					$result = 'success';
				}
			}
		}

		$data = array('message' =>  $message, 'result' => $result, 'errors' => array());
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}

/**
 * admin_send_to_ministary method
 *
 * @return void
 */
	public function admin_provided_request() {

		$message =__d('request_managment', "Une erreur est survenue lors de l'envoi du dossier! ");
		$result = 'error';

		if(isset($this->request['data']['id'])){
			$id = $this->request['data']['id'];
			
			if (!$this->Request->exists($id)) {
				$message =__d('profile_managment', 'Invalid demande identifiant');
			}
			else
			{
				if($this->Request->setStatus('provided', $id)){
					$message = __d('request_managment','Le dossier a été mis sous réserve');
					$result = 'success';
				}
			}
		}

		$data = array('message' =>  $message, 'result' => $result, 'errors' => array());
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}

/**
 * admin_grant_request method
 *
 * @return void
 */
	public function admin_save_request_decision() {
		
		$message =__d('request_managment', "Une erreur est survenue lors de l'aprobation de l'agrément! ");
		$result = 'error';
		
		if(isset($this->request['data']['Request']['request_id'])  && !empty($this->request['data']['Request']['judgment'])){
			$id = $this->request['data']['Request']['request_id'];
			$judgment = $this->request['data']['Request']['judgment'];
			
			if (!$this->Request->exists($id)) {
				$message =__d('profile_managment', 'Invalid demande identifiant');
			}
			else
			{
				$description = isset($this->request['data']['Request']['description'])? $this->request['data']['Request']['description'] : '';
				$request_status_id = (isset($this->request['data']['RequestStatus']['id']))? $this->request['data']['RequestStatus']['id'] : ''; 

				if($judgment == 'granted')
				{
					if($this->Request->setStatus('granted', $id, $description, $request_status_id )){
						$message = __d('request_managment', "Décision enregistrée avec succès");
						$result = 'success';
					}
				}
				elseif($judgment == 'refused')
				{
					if($this->Request->setStatus('refused', $id, $description, $request_status_id)){
						$message = __d('request_managment', "Décision enregistrée avec succès");
						$result = 'success';
					}
				}

			}
		}

		$data = array('message' =>  $message, 'result' => $result, 'errors' => array());
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}

/**
 * get_requester method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_get_requester_data($id, $meeting_id = false, $param_type = 'request') {
		
		if($param_type == 'request')
		{
			if (!$this->Request->exists($id)) {
				throw new NotFoundException(__d('profile_managment', 'Invalid demande identifiant'));
			}			
		}
		else{

			if (!$this->Request->Counselor->exists($id)) {
				throw new NotFoundException(__d('profile_managment', 'Invalid conseiller identifiant'));
			}			
		}

		$members_request = array();
		$companies_documents = array();
		$members_judgments = array();
		$meeting_request = array();
		$contexte = false;
		
		if($param_type == 'request')
		{
			$request = $this->Request->find('first', array(
				'conditions' => array('Request.id' => $id),
				'contain' => array('Status')
			));
		}
		else
		{
			$user_id = $this->Request->Counselor->field('user_id', array(
				'Counselor.id' => $id
			));

			$request = $this->Request->find('first', array(
				'conditions' => array('Request.user_id' => $user_id),
				'contain' => array('Status'),
				'order' => array('Request.event_date desc')
			));					
		}
		$specialities_ids = array('-1');

		if($request['Request']['requester_type'] == "natural")
		{
			$data = $this->Request->Counselor->find('first', array(
				'conditions' => array(
					'Counselor.id' => $request['Request']['requester_id']
				),
				'contain' => array(
					'CounselorsDocument',
					'CounselorsLanguage.Language',
					'CommunityActivity',
					'ProfessionalExperience',
					'PublicationResearch',
					'City',
					'Qualification' => array('Establishment' , 'Diplome.DiplomeType', 'Speciality')
				)
			));
			$specialities_ids = Hash::extract($data, 'Qualification.{n}.Speciality.id');

			$documents = $this->Request->Counselor->CounselorsDocument->Document->find('all', array(
				'contain' => false
			));
		}
		else
		{
			$data = $this->Request->Company->find('first', array(
				'conditions' => array(
					'Company.id' => $request['Request']['requester_id']
				),
				'contain' => array(
					'Counselor.CounselorsLanguage.Language',
					'Counselor.CommunityActivity',
					'Counselor.ProfessionalExperience',
					'Counselor.PublicationResearch',
					'Counselor.Qualification' => array('Establishment' , 'Diplome.DiplomeType', 'Speciality'),
					'Counselor.City',
					'Counselor.CounselorsDocument.Document',
					'CompaniesDocument.Edocument',
					'City'
				)
			));
			$specialities_ids = Hash::extract($data, 'Counselor.Qualification.{n}.Speciality.id');
			$companies_documents = $this->Request->Company->CompaniesDocument->Edocument->find('all', array(
				'contain' => false
			));
			$documents = $this->Request->Counselor->CounselorsDocument->Document->find('all', array(
				'contain' => false
			));
		}

		if(is_numeric($meeting_id))
		{
			$meeting_request = $this->Request->MeetingsRequest->find('first', array(
				'conditions' => array(
					'meeting_id' => $meeting_id,
					'request_id' => $request['Request']['id']
					),
				'contain' => array('Judgment', 'Meeting')
			));
			$contexte = 'meeting';
		}
		else
		{
			$meeting_request = $this->Request->MeetingsRequest->find('first', array(
				'conditions' => array(
					'request_id' => $request['Request']['id']
					),
				'contain' => array('Judgment', 'Meeting'),
				'order' => 'MeetingsRequest.id desc'
			));			
		}

		$members_judgments = $this->Request->MembersRequest->find('all', array(
			'conditions' => array(
				'request_id' => $request['Request']['id']
				),
			'contain' => array('Judgment', 'User.Service')
		));

		$request_statuses = $this->Request->RequestStatus->find('all', array(
			'contain' => array('Status', 'AllUser'),
			'conditions' => array('RequestStatus.request_id' => $request['Request']['id']),
			'order' => 'RequestStatus.event_date desc'
		));
		$this->loadModel('ProfileManagment.Speciality');
		$judgment_data = $this->Request->MeetingsRequest->Judgment->find("all");
		$official_specialities = $this->Speciality->find('list', array(
			'fields' => array('OfficialSpeciality.id', 'OfficialSpeciality.name'),
			'conditions' => array(
				'Speciality.id' => $specialities_ids
			),
			'contain' => array('OfficialSpeciality')
		));

		$judgments = Hash::combine($judgment_data, '{n}.Judgment.id', '{n}.Judgment.name');
		$this->set(compact('official_specialities', 'data', 'request', 'documents', 'companies_documents', 'judgments', 'members_judgments', 'judgment_data', 'meeting_request', 'contexte', 'request_statuses'));
	}

/**
 * get_pending_request_datagrid_data method
 *
 * @return array
 */
	public function admin_get_pending_request_datagrid_data() {		
		
		$conditions = array();

		$pending_requests = $this->Request->query("
			SELECT `Request`.`id`, `Request`.`requester_type`, 
			`Request`.`number`, `Request`.`event_date`, `Company`.`name`, `Counselor`.`first_name`, `Counselor`.`last_name`
			FROM rqm_requests AS `Request` 
			LEFT JOIN `cpm_companies` AS `Company` ON (`Request`.`requester_id` = `Company`.`id` AND `Request`.`requester_type` = 'legal') 
			LEFT JOIN `pfm_counselors` AS `Counselor` ON (`Request`.`requester_id` = `Counselor`.`id` AND `Request`.`requester_type` = 'natural') 
			LEFT JOIN `rqm_statuses` AS `Status` ON (`Request`.`status_id` = `Status`.`id`)
			Where Status.alias in ('commission', 'commission_meeting') and Request.id NOT IN 
			(Select MeetingsRequest.request_id 
			FROM rqm_meetings_requests MeetingsRequest 
			JOIN rqm_requests as Request on (Request.id = MeetingsRequest.request_id)
			JOIN rqm_statuses on (Request.status_id = rqm_statuses.id)
			JOIN rqm_meetings as Meeting on (Meeting.id = MeetingsRequest.meeting_id)
			WHERE rqm_statuses.alias = 'commission_meeting')
			order by Request.id
			limit 0,100;
		");

		$data = array(
    		"data" => $pending_requests
		);

		$this->set('data', $data);
		$this->set('_serialize', 'data');
	}

/**
 * get_pending_request_datagrid_data method
 *
 * @return array
 */
	public function admin_get_meeting_and_pending_request_datagrid_data() {		
		
		$pending_requests = array();

		if(isset($this->request['data']['meeting_id'])){

			$pending_requests = $this->Request->query("
				SELECT `Request`.`id`, `Request`.`requester_type`, 
				`Request`.`number`, `Request`.`event_date`, `Company`.`name`, `Counselor`.`first_name`, `Counselor`.`last_name`
				FROM rqm_requests AS `Request` 
				LEFT JOIN `cpm_companies` AS `Company` ON (`Request`.`requester_id` = `Company`.`id` AND `Request`.`requester_type` = 'legal') 
				LEFT JOIN `pfm_counselors` AS `Counselor` ON (`Request`.`requester_id` = `Counselor`.`id` AND `Request`.`requester_type` = 'natural') 
				LEFT JOIN `rqm_statuses` AS `Status` ON (`Request`.`status_id` = `Status`.`id`)
				Where Status.alias in ('commission', 'commission_meeting') and Request.id NOT IN 
				(Select MeetingsRequest.request_id 
				FROM rqm_meetings_requests MeetingsRequest 
				JOIN rqm_requests as Request on (Request.id = MeetingsRequest.request_id)
				JOIN rqm_statuses on (Request.status_id = rqm_statuses.id)
				JOIN rqm_meetings as Meeting on (Meeting.id = MeetingsRequest.meeting_id)
				WHERE rqm_statuses.alias = 'commission_meeting' and MeetingsRequest.meeting_id != ".$this->request['data']['meeting_id'].")
				order by Request.id
				limit 0,100;
			");
		}

		$data = array(
    		"data" => $pending_requests
		);

		$this->set('data', $data);
		$this->set('_serialize', 'data');
	}

/**
 * get_filtred_datagrid_data method : generation de états
 *
 * @return array
 */
	public function admin_get_filtred_datagrid_data() {		

		$limit = "10";
		
		if ( isset( $this->params['data']['start'] ) && $this->params['data']['length'] != '-1' )
		{
			$limit = $this->params['data']['length'];
		}

		$page = "1";
		
		if ( isset( $this->params['data']['start'] ))
		{
			$page = ($this->params['data']['start'] / $limit) + 1;
		}

		$order = "";
		
		if ( isset( $this->params['data']['order'] ) )
		{
			$order = "";

			foreach ($this->params['data']['order'] as $i => $datum)
			{
				if ( $this->params['data']['columns'][$datum['column']]['orderable'] == "true" )
				{
					if(!empty($order)) $order .= ", ";
					$order .= "".$this->params['data']['columns'][$datum['column']]['data']." ".$datum['dir'];
				}
			}
		}

		if($order == "") $order = "Request.id DESC";
		
		$conditions = array();
		
		if ( isset($this->params['data']['filter']) )
		{
			if (isset($this->params['data']['filter']['Requester.region_id']))
			{
				$city_ids = $this->Request->Counselor->City->find('list', array(
					'conditions' => array('City.region_id' => $this->params['data']['filter']['Requester.region_id']),
					'fields' => array('City.id')
				));
				if(empty($city_ids)) $city_ids = array(-1);
				$conditions = array('OR' => array('Counselor.city_id' => $city_ids, 'Company.city_id' => $city_ids));
				unset($this->params->data['filter']['Requester.region_id']);
				
			}

			$conditions = array_merge($this->params['data']['filter'], $conditions);
		}

		$this->Paginator->settings = array(
			'conditions' => $conditions,
			'contain' => array('Counselor', 'Company', 'Status'),
			'limit' => $limit,
			'page' => $page,
			'order' => $order
		);

		$datum = $this->Paginator->paginate('Request');

		$data = array(
			"draw" => (isset($this->params['data']['draw']))? $this->params['data']['draw'] : 1, 
			"recordsTotal" => $this->params['paging']['Request']['count'], 
			"recordsFiltered" => $this->params['paging']['Request']['count'],
    		"data" => $datum
		);
		$this->set('data', $data);
		$this->set('_serialize', 'data');
	}
/**
 * get_datagrid_data method
 *
 * @return array
 */
	public function admin_get_datagrid_data() {		

		$limit = "10";
		
		if ( isset( $this->params['data']['start'] ) && $this->params['data']['length'] != '-1' )
		{
			$limit = $this->params['data']['length'];
		}

		$page = "1";
		
		if ( isset( $this->params['data']['start'] ))
		{
			$page = ($this->params['data']['start'] / $limit) + 1;
		}

		$order = "";
		
		if ( isset( $this->params['data']['order'] ) )
		{
			$order = "";

			foreach ($this->params['data']['order'] as $i => $datum)
			{
				if ( $this->params['data']['columns'][$datum['column']]['orderable'] == "true" )
				{
					if(!empty($order)) $order .= ", ";
					$order .= "".$this->params['data']['columns'][$datum['column']]['data']." ".$datum['dir'];
				}
			}
		}

		if($order == "") $order = "Request.id DESC";
		
		$conditions = array();
		
		if ( isset($this->params['data']['filter']) )
		{
			$conditions = array($this->params['data']['filter']);
		}

		$role_id = AuthComponent::user('role_id');

		if (!$this->__isUserAutorized(array('action' => 'can_see_all_requests', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'requests')))
		{
			$connected_user_role = $this->Request->MembersRequest->User->Role->field('alias', array('Role.id' => $role_id));
			$conditions[] = array('Status.alias' => $this->Request->statuses_by_role[$connected_user_role]);			
		}

		$this->Paginator->settings = array(
			'conditions' => $conditions,
			'contain' => array('Counselor', 'Company', 'Status'),
			'limit' => $limit,
			'page' => $page,
			'order' => $order
		);

		$datum = $this->Paginator->paginate('Request');

		$data = array(
			"draw" => (isset($this->params['data']['draw']))? $this->params['data']['draw'] : 1, 
			"recordsTotal" => $this->params['paging']['Request']['count'], 
			"recordsFiltered" => $this->params['paging']['Request']['count'],
    		"data" => $datum
		);
		$this->set('data', $data);
		$this->set('_serialize', 'data');
	}
/**
 * get_datagrid_data method
 *
 * @return array
 */
	public function admin_get_datagrid_data_for_meeting() {		

		$limit = "10";
		
		if ( isset( $this->params['data']['start'] ) && $this->params['data']['length'] != '-1' )
		{
			$limit = $this->params['data']['length'];
		}

		$page = "1";
		
		if ( isset( $this->params['data']['start'] ))
		{
			$page = ($this->params['data']['start'] / $limit) + 1;
		}

		$order = "";
		
		if ( isset( $this->params['data']['order'] ) )
		{
			$order = "";

			foreach ($this->params['data']['order'] as $i => $datum)
			{
				if ( $this->params['data']['columns'][$datum['column']]['orderable'] == "true" )
				{
					if(!empty($order)) $order .= ", ";
					$order .= "".$this->params['data']['columns'][$datum['column']]['data']." ".$datum['dir'];
				}
			}
		}

		if($order == "") $order = "Request.id DESC";
		
		$conditions = array();
		
		if ( isset($this->params['data']['filter']) )
		{
			$conditions = array($this->params['data']['filter']);
		}

		$this->Paginator->settings = array(
			'conditions' => $conditions,
			'contain' => array('Counselor', 'Company', 'Status', 'MeetingsRequest.Judgment'),
			'limit' => $limit,
			'page' => $page,
			'order' => $order
		);

		$datum = $this->Paginator->paginate('Request');

		$data = array(
			"draw" => (isset($this->params['data']['draw']))? $this->params['data']['draw'] : 1, 
			"recordsTotal" => $this->params['paging']['Request']['count'], 
			"recordsFiltered" => $this->params['paging']['Request']['count'],
    		"data" => $datum
		);
		$this->set('data', $data);
		$this->set('_serialize', 'data');
	}
/**
 * __format_datagrid_data method
 *
 * @param array $unformated_data unformated data
 * @return array $formated_data formated data
 */
	protected function __format_datagrid_data($unformated_data) {
		$formated_data = array();

		foreach ($unformated_data as $datum) {

			$formated_data[] = $datum;
		}

		return $formated_data;
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$counselors = $this->Request->Counselor->find('list');
		$statuses = $this->Request->Status->find('list');
		$role_id = AuthComponent::user('role_id');
		$connected_user_role = $this->Request->MembersRequest->User->Role->field('alias', array('Role.id' => $role_id));
		$user_statuses = $this->Request->statuses_by_role[$connected_user_role];
		

		$this->set(compact('counselors', 'statuses', 'user_statuses', 'requests_types'));
	}

/**
 * admin_filter method
 *
 * @return void
 */
	public function admin_filter() {
		$statuses = $this->Request->Status->find('list');
		$regions = $this->Request->Counselor->City->Region->find('list');
		$requests_types = $this->Request->requests_types;
		
		$this->set(compact('statuses', 'requests_types', 'regions'));
	}

/**
 * admin_add method
 *
 * @return void
 */
 	public function admin_add() {

		$inserted_record = array();
		$errors = array();
		$this->Request->create();
		
		if ($this->Request->save($this->request->data)) {
			$message = __d('request_managment','The request has been saved');
			$result = 'success';
			$inserted_record = $this->Request->find('first', array(
				'conditions' => array(
					'Request.id' => $this->Request->id
				)
			));
		} else {
			$errors = $this->Request->validationErrors;
			$message =__d('request_managment','The request could not be saved. Please, try again.');
			$result = 'error';
		}

		$formated_record = $this->__format_datagrid_data(array($inserted_record));
		$data = array('message' =>  $message, 'result' => $result, 'record' => $formated_record[0], 'errors' => $errors);
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}

/******************************
Abstract methodes
*******************************/

//user can see all requests 
	public function admin_can_see_all_requests() {

	}
	
//user can see can_see_meeting_judgments
	public function admin_can_see_meeting_judgments() {

	}

//user can see request judgments tab
	public function admin_can_see_request_judgment_tab() {

	}
//user can see request judgments 
	public function admin_can_see_all_request_judgments() {

	}
//user can see request judgments 
	public function admin_can_see_only_his_judgment() {

	}
}
