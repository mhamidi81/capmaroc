<?php
App::uses('UserManagmentAppController', 'UserManagment.Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends UserManagmentAppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Upload');

	public $uses = array('Users.User');

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
 * get_datagrid_data method
 *
 * @return array
 */
	public function admin_get_datagrid_data() {

		$limit = "10";
		
		if ( isset( $this->params['data']['length'] ) && $this->params['data']['length'] != '-1' )
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

		if($order == "") $order = "User.id DESC";
		
		$conditions = array();
		
		if (isset($this->params['data']['filter']))
		{
			$conditions = array($this->params['data']['filter']);
		}

		$this->Paginator->settings = array(
			'conditions' => array_merge(array('User.role_id > 3'), $conditions),
			'contain' => array('Role', 'Service'),
			'limit' => $limit,
			'page' => $page,
			'order' => $order
		);

		$datum = $this->Paginator->paginate('User');

		$data = array(
			"draw" => (isset($this->params['data']['draw']))? $this->params['data']['draw'] : 1, 
			"recordsTotal" => $this->params['paging']['User']['count'], 
			"recordsFiltered" => $this->params['paging']['User']['count'],
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
		$roles = $this->User->Role->find('list', array('conditions' => array('Role.id > 4')));
		//$this->loadModel('ProfileManagment.Service');
		
		$services = $this->User->Service->find('list');
		$this->set(compact('roles', 'services'));
	}

/**
 * admin_add method
 *
 * @return void
 */
 	public function admin_add() {

		$inserted_record = array();
		$errors = array();
		$this->User->create();
		
		if($this->request['data']['User']['image']['size'] > 0)
		{
			ini_set('memory_limit', '64M');
			ini_set('max_execution_time', 3600);
			$image = $this->request['data']['User']['image'];

			//renommer le fichier
			$this->Upload->custom_name($image['name']);
			$upload_path = $this->_getUploadPath();
			$this->Upload->destination($upload_path);
			unset($this->request->data['User']['image']);
			//envoyer le fichier au serveur
			if($path = $this->Upload->upload($image))
			{
				$this->request->data['User']['image'] = $this->Upload->filename;
			}			
		}
		else
		{
			unset($this->request->data['User']['image']);
		}

		$this->request->data['User']['password'] = $this->__generatePassword();
		
		if ($this->User->save($this->request->data)) {
			
			$this->_sendEmail(
				array(Configure::read('Site.title'), Configure::read('Site.email')),
				$this->request->data['User']['email'],
				__d('UserManagment', '[%s] Création de votre compte ', Configure::read('Site.title')),
				'UserManagment.register',
				array('user' => $this->request->data)
			);

			$message = __d('user_managment','The user has been saved');
			$result = 'success';
			$inserted_record = $this->User->find('first', array(
				'conditions' => array(
					'User.id' => $this->User->id
				)
			));
		} else {
			$errors = $this->User->validationErrors;
			$message =__d('user_managment', "Une erreur est survenue durant l'ajout de l'utilisateur.");
			$result = 'error';
		}

		$formated_record = $this->__format_datagrid_data(array($inserted_record));
		$data = array('message' =>  $message, 'result' => $result, 'record' => $formated_record[0], 'errors' => $errors);
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}
/**
 *
 * Génère une clé secrete aléatoire de l)quthentificqtion composé de 8 caractères alpanumériques (a-z, 0-9)
 *
 * @return string Mot de passe généré
 */
public function __generatePassword() {
	$maxLength = 8;
	$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
	$pass = array();
	$alphaLength = strlen($alphabet) - 1;
   
	for ($i = 0;$i < $maxLength;$i++) {
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}

	return implode($pass);
}

/**
 * methode pour recupperer le chemain du dossier upload
 * @return string
 */
	protected function _getUploadPath(){
		
		return WWW_ROOT .'uploads'. DS . 'users';
	}

/**
 * admin_edit method
 *
 * @return void
 */
	public function admin_edit() {
		
		$updated_record = array();
		$errors = array();

		if(!empty($this->request['data']['User']['image']) && $this->request['data']['User']['image']['size'] > 0)
		{
			$old_image_name = $this->User->field('image', array(
				'id' => $this->request['data']['User']['id']
			));

			ini_set('memory_limit', '64M');
			ini_set('max_execution_time', 3600);
			$image = $this->request['data']['User']['image'];

			//renommer le fichier
			$this->Upload->custom_name($image['name']);
			$upload_path = $this->_getUploadPath();
			$this->Upload->destination($upload_path);
			unset($this->request->data['User']['image']);
			//envoyer le fichier au serveur
			if($path = $this->Upload->upload($image))
			{
				$this->request->data['User']['image'] = $this->Upload->filename;

				if(!empty($old_image_name))
				{
					unlink($upload_path.DS.$old_image_name);
				}
			}			
		}
		else
		if(isset($this->request['data']['User']['image']))
		{
			unset($this->request->data['User']['image']);
		}

		if ($this->User->save($this->request->data)) {

			$message = __d('user_managment','The user has been saved');
			$result = 'success';
			$updated_record = $this->User->find('first', array(
				'conditions' => array(
					'User.id' => $this->User->id
				)
			));

		} else {
			$errors = $this->User->validationErrors;
			$message =__d('user_managment',"Une erreur est survenue durant la mise à jour de l'utilisateur.");
			$result = 'error';
		}

		$formated_record = $this->__format_datagrid_data(array($updated_record));
		$data = array('message' =>  $message, 'result' => $result, 'record' => $formated_record[0] , 'errors' => $errors);
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}

/**
 * admin_delete method
 *
 * @access public
 * @return void
 */
	public function admin_delete() {

		$id = (isset($this->request->data['id']))? $this->request->data['id'] : -1;

		if ($this->User->delete($id)) {
			$message = __d('user_managment',"l'utilisateur a été bien supprimé");
			$result = 'success';
		} else {
			$message = __d('user_managment',"Une erreur est survenue durant la suppression.");
			$result = 'error';
		}

		$data =  array('message' =>  $message, 'result' => $result, 'id' => $id);
		
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}
	
/**
 * Convenience method to send email
 *
 * @param string $from Sender email
 * @param string $to Receiver email
 * @param string $subject Subject
 * @param string $template Template to use
 * @param string $theme Theme to use
 * @param array  $viewVars Vars to use inside template
 * @param string $emailType user activation, reset password, used in log message when failing.
 * @return boolean True if email was sent, False otherwise.
 */
	protected function _sendEmail($from, $to, $subject, $template, $viewVars = null) {

		$success = false;

		try {

			$email = new CakeEmail();
			$email->config('default');
			$email->from($from[1], $from[0]);
			$email->to($to);
			$email->subject($subject);
			$email->template($template);
			$email->viewVars($viewVars);
			$email->emailFormat('html');
			$success = $email->send();
		} catch (SocketException $e) {
			$this->log(sprintf('Error sending %s notification : %s', $subject, $e->getMessage()));
		}

		return $success;
	}

/**
 * Reset
 *
 * @param string $email
 * @param string $key
 * @return void
 * @access public
 */
	public function admin_profile() {

		$this->User->id = $this->Auth->user('id');
		$user = $this->User->find('first', array('conditions' => array('User.id' => $this->User->id)));
		$this->set('user', $user);
	}

/**
 * Reset
 *
 * @return void
 * @access public
 */
	public function admin_change_password() {
		
		$message =__d('request_managment','Une erreur est survenue durant la sauvegarde!');
		$result = 'error';
		$errors = array();
		$old_password = $this->request->data['User']['old_password'];
		$user_id = $this->User->field('id', array(
			'User.password' => AuthComponent::password($old_password),
			'User.id' => $this->Auth->user('id')
		));
		
		if ($user_id) {
			$this->User->id = $user_id;
			$user['User']['activation_key'] = md5(uniqid());
			$user['User']['password'] = $this->request->data['User']['password'];
			$user['User']['verify_password'] = $this->request->data['User']['verify_password'];
			
			if ($this->User->save($user['User'])) {
				$message =__d('croogo','Votre mot de passe a été changé avec succès.');
				$result = 'success';
			}
			else
			{
				$errors = $this->User->validationErrors;
			}
		}

		$data = array('message' =>  $message, 'result' => $result, 'errors' => $errors);

		$this->set('data', $data);
		$this->set('_serialize', 'data');
	}

/**
 * Reset
 *
 * @param string $email
 * @param string $key
 * @return void
 * @access public
 */
	public function admin_edit_user() {
		
		$message =__d('request_managment','Une erreur est survenue durant la sauvegarde!');
		$result = 'error';
		$errors = array();

		$this->User->id = $this->Auth->user('id');
		$user['User']['first_name'] = $this->request->data['User']['first_name'];
		$user['User']['last_name'] = $this->request->data['User']['last_name'];
		$user['User']['phone'] = $this->request->data['User']['phone'];

		if ($this->User->save($user['User'])) {
			$message =__d('request_managment','Votre profile a été mis à jour avec succé.');
			$result = 'success';
			$this->Session->write('Auth', $this->User->read(null, $this->Auth->User('id')));
		}
		else
		{
			$errors = $this->User->validationErrors;
		}

		$data = array('message' =>  $message, 'result' => $result, 'errors' => $errors);

		$this->set('data', $data);
		$this->set('_serialize', 'data');
	}
}
