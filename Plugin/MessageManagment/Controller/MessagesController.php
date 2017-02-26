<?php
App::uses('MessageManagmentAppController', 'MessageManagment.Controller');
/**
 * Messages Controller
 *
 * @property Message $Message
 * @property PaginatorComponent $Paginator
 */
class MessagesController extends MessageManagmentAppController {

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
 * admin_index method
 *
 * @return void
 */
	public function admin_index($message_id = false) {
		
		$userEmail = $this->Message->Recipient->field('email', array('id' => AuthComponent::user('id')));
		//$userEmail = AuthComponent::user('id');

		$conditions = array(
			'email_to' => $userEmail,
			'mailbox' => 'inbox',
			'deleted' => false,
		); 
		
		if ($message_id){
			$conditions['Message.id'] = $message_id; 
		}
		
		$this->Paginator->settings = array(
			'conditions' => $conditions,
			'limit' => 10,
			'order' => 'Message.created DESC',
			'contain' => array(
				'Sender' => array(
					'fields' => array('first_name','last_name','email','image')
				)
			)
		);
		
		$messages = $this->Paginator->paginate('Message');
		
		$recipients = $this->Message->Recipient->find('first',array(
			'fields' => array('first_name','last_name','email','image'),
			'conditions' => array(
				'email' => $userEmail,
			),
			'recursive' => -1
		));
		// debug($recipients);die;
		$this->set(compact('messages','recipients'));

	}

/**
 * admin_add method
 *
 * @return void
 */
 	public function admin_add() {
		
		//$userEmail = AuthComponent::user('id');
		$userEmail = $this->Message->Recipient->field('email', array('id' => AuthComponent::user('id')));
		$message = 'Echec d\'envoi de message.';
		$result = 'error';
		$errors=array();
		
		if( !empty($this->request['data']['Recipient']['id']) && !empty($this->request['data']['Message']['title']) ){
			
			foreach($this->request['data']['Recipient']['id'] as $i => $recipientId ){
				
				//messages enovoyes (boite de reception  du destinataire') 
				
				$inbox[$i]['Message']['email_to'] = $recipientId;
				$inbox[$i]['Message']['email_from'] = $userEmail;
				// $inbox[$i]['Message']['email_from'] = $userEmail;
				$inbox[$i]['Message']['title'] = $this->request['data']['Message']['title'];
				$inbox[$i]['Message']['body'] = $this->request['data']['Message']['body'];
				$inbox[$i]['Message']['mailbox'] = 'inbox';
				$inbox[$i]['Message']['status'] = 0;
				
			}

			if ($this->Message->saveAll($inbox)) {
				
				$message = 'Message envoyé';
				$result = 'success';
				
			} else {
				
				$errors = $this->Message->validationErrors;
			}
			
		}
		
		$data = array('message' =>  $message, 'result' => $result, 'errors' => $errors);
		
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}

/**
 * contact_admin method
 *
 * @return void
 */
 	public function admin_contact() {
		
		$userEmail = AuthComponent::user('email');
		
		$message = 'Echec d\'envoi de message.';
		
		$result = 'error';
		
		$errors = array();
		
		$destinataire = Configure::read('site_contact_email');
		
	
		if( (!empty($this->request['data']['Message']['title'])) && (!empty($this->request['data']['Message']['body']))){
			
			//messages enovoyes (boite de reception  du destinataire') 
			
			$inbox['Message']['email_to'] = $destinataire;
			$inbox['Message']['email_from'] = $userEmail;
			$inbox['Message']['title'] = $this->request['data']['Message']['title'];
			$inbox['Message']['body'] = $this->request['data']['Message']['body'];
			$inbox['Message']['mailbox'] = 'inbox';
			$inbox['Message']['status'] = 0;

			$this->Message->create();

			if ($this->Message->save($inbox)) {
				
				$message = 'Message envoyé';
				$result = 'success';
				
			} else {
				
				$errors = $this->Message->validationErrors;
			}
			
		}
		
		$data = array('message' =>  $message, 'result' => $result, 'errors' => $errors);
		
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}

/**
 * admin_edit method
 *
 * @return void
 */
	public function admin_view() {

		$result = 'error';
		$message = 'error';
		
		if(isset($this->request['data']['id'])){
			
			$this->Message->id = $this->request['data']['id'];
			
			$valid = $this->Message->saveField('status' , 1 );
			
			if($valid){
				$result = 'success';
			}
		}

		$data = array('result' => $result, 'errors' => array());
		
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}

/**
 * admin_get_inbox method
 *
 * @return void
 */
	public function admin_get_inbox() {
		
		$page = 1 ;
		$userEmail = AuthComponent::user('email');
		// $userEmail = $this->Message->Recipient->field('email', array('id' => AuthComponent::user('id')));
		
		if ( !empty($this->request->data['page']) ) 
			$page = $this->request->data['page'];
		
		$this->Paginator->settings = array(
				'conditions' => array(
					'email_to' => $userEmail,
					'mailbox' => 'inbox',
					'deleted' => false,
				),
				'page' => $page,
				'limit' => 10,
				'contain' => array(
					'Sender' => array(
						'fields' => array('first_name','last_name','email','image')
					),
					'Recipient' => array(
						'fields' => array('first_name','last_name','email','image')
					)
				),
				'order' => 'Message.created DESC'
			);
			
		$messages = $this->Paginator->paginate('Message');
		
		$this->set(compact('messages','recipients'));
	}

/**
 * admin_get_sentbox method
 *
 * @return void
 */
	public function admin_get_sentbox() {
		
		$page = 1 ;
		$userEmail = AuthComponent::user('email');
		
		if ( !empty($this->request->data['page']) ) 
			$page = $this->request->data['page'];
		
		$this->Paginator->settings = array(
			'conditions' => array(
				'email_from' => $userEmail,
				'mailbox' => 'sent',
				'deleted' => false,
			),
			'page' => $page,
			'limit' => 10,
			'order' => 'Message.created DESC',
			'contain' => array(
				'Recipient' => array(
					'fields' => array('first_name','last_name','email','image')
				),
				'Sender' => array(
					'fields' => array('first_name','last_name','email','image')
				),
			),
			
		);
			
		$messages = $this->Paginator->paginate('Message');

		$this->set(compact('messages'));
	}

/**
 * admin_get_trash method
 *
 * @return void
 */
 
	public function admin_get_trash() {
		
		$page = 1 ;
		$userEmail = AuthComponent::user('email');
		
		if ( !empty($this->request->data['page']) ) 
			$page = $this->request->data['page'];
		$this->Paginator->settings = array(
				'conditions' => array(
					'deleted' => true,
					'OR' => array(
						array(
							'Message.email_from ' => $userEmail,
							'Message.mailbox ' => 'sent',
						),
						array(
							'Message.email_to ' => $userEmail,
							'Message.mailbox ' => 'inbox',
						),
					),
				),
				'page' => $page,
				'limit' => 10,
				'order' => 'Message.created DESC',
				'contain' => array(
					'Recipient' => array(
						'fields' => array('first_name','last_name','email','image')
					),
					'Sender' => array(
						'fields' => array('first_name','last_name','email','image')
					)
				)
			);
			
		$messages = $this->Paginator->paginate('Message');
		
		$this->set(compact('messages'));
	}

/**
 * admin_search method
 *
 * @return void
 */
 
	public function admin_search() {
		
		$query = $this->request['data']['q'];
		$page = 1 ;
		$userEmail = AuthComponent::user('email');
		// debug($this->request['data']);die;
		
		if ( !empty($this->request->data['page']) ) 
			$page = $this->request->data['page'];
		
		if($this->request['data']['inbox'] == 'get_inbox'){
		
			$this->Paginator->settings = array(
				'conditions' => array(
					'OR' => array(
						'Message.title like ' => '%'.$query.'%',
						'Message.body like ' => '%'.$query.'%',
						'Sender.email like ' => $query.'%',
					),
					'Message.email_to ' => $userEmail,
					'mailbox' => 'inbox',
					'deleted' => false,
				),
				'page' => $page,
				'limit' => 2,
				'contain' => array(
					'Sender' => array(
						'fields' => array('first_name','last_name','email','image')
					),
					'Recipient' => array(
						'fields' => array('first_name','last_name','email','image')
					)
				),
				'order' => 'Message.created DESC'
			);
		}
		
		if($this->request['data']['inbox'] == 'get_sentbox'){
		
			$this->Paginator->settings = array(
				'conditions' => array(
					'OR' => array(
						'Message.title like ' => '%'.$query.'%',
						'Message.body like ' => '%'.$query.'%',
						'Recipient.email like ' => $query.'%',
					),
					'Message.email_from ' => $userEmail,
					'mailbox' => 'sent',
					'deleted' => false,
				),
				'page' => $page,
				'limit' => 2,
				'contain' => array(
					'Sender' => array(
						'fields' => array('first_name','last_name','email','image')
					),
					'Recipient' => array(
						'fields' => array('first_name','last_name','email','image')
					)
				),
				'order' => 'Message.created DESC'
			);
		}
		
		if($this->request['data']['inbox'] == 'get_trash'){
		
			$this->Paginator->settings = array(
				'conditions' => array(
					'OR' => array(
						array(
							'Message.email_from ' => $userEmail,
							'Message.mailbox ' => 'sent',
						),
						array(
							'Message.email_to ' => $userEmail,
							'Message.mailbox ' => 'inbox',
						)
					),
					'AND' => array(
						'OR' => array(
							'Message.title like ' => '%'.$query.'%',
							'Message.body like ' => '%'.$query.'%',
							'Recipient.email like ' => $query.'%',
							'Sender.email like ' => $query.'%',
						),
					),
					'deleted' => true,
				),
				'page' => $page,
				'limit' => 2,
				'contain' => array(
					'Sender' => array(
						'fields' => array('first_name','last_name','email','image')
					),
					'Recipient' => array(
						'fields' => array('first_name','last_name','email','image')
					)
				),
				'order' => 'Message.created DESC'
			);
		}
		
			$messages = $this->Paginator->paginate('Message');
			// debug($messages);die;
			$this->set(compact('messages'));
		
	}

/**
 * admin_get_users mail method
 *
 * @return void
 */
 
	public function admin_get_users() {

		$userEmail = $this->Message->Recipient->field('email', array('id' => AuthComponent::user('id')));
		
		$query = $this->request['data']['q']['term'];
		
		$users = $this->Message->Recipient->find('all',array(
			'fields' => array('Recipient.email as id', 'Recipient.email as text'),
			'conditions' => array(
				'OR' => array(
					'first_name like' => $query.'%',
					'last_name like' => $query.'%',
					'email like' => $query.'%',
				),
				'Recipient.email !=' => $userEmail
			),
			'recursive' => -1
		));
		
		$users = array('q' => $query, 'results' => Set::extract($users,'{n}.Recipient'));
		
		$this->set('data', $users);
		$this->set('_serialize', 'data');
	}

/**
 * admin_delete method
 *
 * @access public
 * @return void
 */
	public function admin_delete() {
		
		$userEmail = AuthComponent::user('email');

		$result = 'error';
		$message = 'Echec de la suppression du message';
		
		if(isset($this->request['data']['id'])){

			$mailbox_message = $this->Message->find('first', array(
				'conditions' => array(
					'Message.id' => $this->request['data']['id']
				),
				'contain' => false
			));

			if(!empty($mailbox_message))
			{
				$this->Message->id = $mailbox_message['Message']['id'];
				
				if ( $mailbox_message['Message']['deleted'] == 0 ) {
					
					$valid = $this->Message->saveField('deleted' , 1 );
				}
				else
				{
					$valid = $this->Message->delete($mailbox_message['Message']['id']);
				}				
			}

			if($valid) {
				$result = 'success';
				$message = 'Message supprimé';
			}
		}

		$data = array('result' => $result, 'message' => $message);

		$this->set('data', $data);
		$this->set('_serialize', 'data');

	}
/**
 * admin_delete method
 *
 * @access public
 * @return void
 */
	public function admin_remettre() {
		
		
		$userEmail = AuthComponent::user('email');

		$result = 'error';
		$message = 'une erreur est servenue lors de remettre du message';
		$valid = false;

		if(isset($this->request['data']['id'])){

			$mailbox_message = $this->Message->find('first', array(
				'conditions' => array(
					'Message.id' => $this->request['data']['id'],
				),
				'contain' => false
			));

			if(!empty($mailbox_message))
			{
				$this->Message->id = $mailbox_message['Message']['id'];
				
				if ( $mailbox_message['Message']['deleted'] == 1 ) {
					
					$valid = $this->Message->saveField('deleted' , 0 );
				}				
			}

			if($valid) {
				$result = 'success';
				$message = 'Le Message à été bien déplacé';
			}
		}

		$data = array('result' => $result, 'message' => $message);

		$this->set('data', $data);
		$this->set('_serialize', 'data');

	}
}
