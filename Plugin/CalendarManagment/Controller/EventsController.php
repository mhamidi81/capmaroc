<?php
App::uses('CalendarManagmentAppController', 'CalendarManagment.Controller');
/**
 * Messages Controller
 *
 * @property Message $Message
 * @property PaginatorComponent $Paginator
 */
class EventsController extends CalendarManagmentAppController {

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
	public function admin_index() {
		$events = $this->Event->find('all',array(
			'fields' => array('id','titre as title','from as start','to as end','description'),
			'conditions' => array('user_id' => 53),
			'recursive' => -1
		));
		// debug($events);die;
		$this->set('data', $events);
        $this->set('_serialize', 'data');
	}
	
/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		
		
		$userId = AuthComponent::user('id');
		
		$message = 'Echec d\'ajout de l\'évenement.';
		$result = 'error';
		$insertedId = '';
		$errors=array();
		
		$data = $this->request['data'];
		$data['Event']['user_id'] = $userId;
		
		
		$this->Event->create();
		if ($this->Event->save($data)) {
			
			$message = 'Evenement ajouté';
			$result = 'success';
			
			$currentEvent = $this->Event->read();
			$insertedId = $currentEvent['Event']['id'];
			
		} else {
			
			$errors = $this->Event->validationErrors;
		}
		// debug(['Event']['id']);die;
		$result = array('message' =>  $message, 'result' => $result, 'errors' => $errors, 'id' =>  $insertedId);
		
		$this->set('data', $result);
        $this->set('_serialize', 'data');
	}
	
/**
 * admin_edit method
 *
 * @return void
 */
	public function admin_edit() {
		
		// debug($this->request['data']);die;
		$userId = AuthComponent::user('id');
		
		$message = 'Echec de l\'édition de l\'évenement.';
		$result = 'error';
		$errors=array();
		
		if ( !empty($this->request['data']['Event']['id'] )){
			
			$data = $this->request['data'];
			$data['Event']['user_id'] = $userId;

			$this->Event->create();
			if ($this->Event->save($data)) {
				$message = 'Evenement édité';
				$result = 'success';
				
			} else {
				
				$errors = $this->Event->validationErrors;
			}
		}
		
		$result = array('message' =>  $message, 'result' => $result, 'errors' => $errors);
		
		$this->set('data', $result);
        $this->set('_serialize', 'data');
	}
/**
 * admin_delete method
 *
 * @return void
 */
	public function admin_delete() {
		
		$userId = AuthComponent::user('id');
		$result = 'error';
		$message = 'Echec de la suppression de l\évenement';
		
		// debug($userId);die;
		if(isset($this->request['data']['id'])){

			$valid = $this->Event->deleteAll(array(
				'Event.id' => $this->request['data']['id'],
				'Event.user_id' => $userId
			));

			if($valid) {
				$result = 'success';
				$message = 'Message supprimé';
			}
		}

		$data = array('result' => $result, 'message' => $message);

		$this->set('data', $data);
		$this->set('_serialize', 'data');
	}

}
