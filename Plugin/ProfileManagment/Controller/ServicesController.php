<?php
App::uses('ProfileManagmentAppController', 'ProfileManagment.Controller');
/**
 * Services Controller
 *
 * @property Service $Service
 * @property PaginatorComponent $Paginator
 */
class ServicesController extends ProfileManagmentAppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Upload');

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

		if($order == "") $order = "Service.id DESC";
		
		$conditions = array();
		
		if ( isset($this->params['data']['filter']))
		{
			$conditions = array($this->params['data']['filter']);
		}

		$this->Paginator->settings = array(
			'conditions' => $conditions,
			'limit' => $limit,
			'page' => $page,
			'order' => $order
		);

		$datum = $this->Paginator->paginate('Service');

		$data = array(
			"draw" => (isset($this->params['data']['draw']))? $this->params['data']['draw'] : 1, 
			"recordsTotal" => $this->params['paging']['Service']['count'], 
			"recordsFiltered" => $this->params['paging']['Service']['count'],
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
	}

/**
 * admin_add method
 *
 * @return void
 */
 	public function admin_add() {

		$inserted_record = array();
		$errors = array();
		
		if(!empty($this->request['data']['Service']['logo']) && $this->request['data']['Service']['logo']['size'] > 0)
		{
			ini_set('memory_limit', '64M');
			ini_set('max_execution_time', 3600);
			$logo = $this->request['data']['Service']['logo'];

			//renommer le fichier
			$this->Upload->custom_name($logo['name']);
			$upload_path = $this->_getUploadPath();
			$this->Upload->destination($upload_path);
			unset($this->request->data['Service']['logo']);
			//envoyer le fichier au serveur
			if($path = $this->Upload->upload($logo))
			{
				$this->request->data['Service']['logo'] = $this->Upload->filename;
			}			
		}
		else
		if(isset($this->request['data']['Service']['logo']))
		{
			unset($this->request->data['Service']['logo']);
		}

		$this->Service->create();
		
		if ($this->Service->save($this->request->data)) {
			$message = __d('profile_managment','The service has been saved');
			$result = 'success';
			$inserted_record = $this->Service->find('first', array(
				'conditions' => array(
					'Service.id' => $this->Service->id
				)
			));
		} else {
			$errors = $this->Service->validationErrors;
			$message =__d('profile_managment','The service could not be saved. Please, try again.');
			$result = 'error';
		}

		$formated_record = $this->__format_datagrid_data(array($inserted_record));
		$data = array('message' =>  $message, 'result' => $result, 'record' => $formated_record[0], 'errors' => $errors);
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}

/**
 * admin_edit method
 *
 * @return void
 */
	public function admin_edit() {
		
		$updated_record = array();
		$errors = array();

		if(!empty($this->request['data']['Service']['logo']) && $this->request['data']['Service']['logo']['size'] > 0)
		{
			$old_image_name = $this->Service->field('logo', array(
				'id' => $this->request['data']['Service']['id']
			));

			ini_set('memory_limit', '64M');
			ini_set('max_execution_time', 3600);
			$image = $this->request['data']['Service']['logo'];

			//renommer le fichier
			$this->Upload->custom_name($image['name']);
			$upload_path = $this->_getUploadPath();
			$this->Upload->destination($upload_path);
			unset($this->request->data['Service']['logo']);
			//envoyer le fichier au serveur
			if($path = $this->Upload->upload($image))
			{
				$this->request->data['Service']['logo'] = $this->Upload->filename;
				
				if(!empty($old_image_name))
				{
					unlink($upload_path.DS.$old_image_name);
				}
				
			}			
		}
		else
		if(isset($this->request['data']['Service']['logo']))
		{
			unset($this->request->data['Service']['logo']);
		}

		if ($this->Service->save($this->request->data)) {

			$message = __d('profile_managment','The service has been saved');
			$result = 'success';
			$updated_record = $this->Service->find('first', array(
				'conditions' => array(
					'Service.id' => $this->Service->id
				)
			));

		} else {
			$errors = $this->Service->validationErrors;
			$message =__d('profile_managment','The service could not be saved. Please, try again.');
			$result = 'error';
		}

		$formated_record = $this->__format_datagrid_data(array($updated_record));
		$data = array('message' =>  $message, 'result' => $result, 'record' => $formated_record[0] , 'errors' => $errors);
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}
/**
 * methode pour recupperer le chemain du dossier upload
 * @return string
 */
	protected function _getUploadPath(){
		
		return WWW_ROOT .'uploads'. DS . 'services';
	}

/**
 * admin_delete method
 *
 * @access public
 * @return void
 */
	public function admin_delete() {

		$id = (isset($this->request->data['id']))? $this->request->data['id'] : -1;

		if ($this->Service->delete($id)) {
			$message = __d('profile_managment','Request deleted');
			$result = 'success';
		} else {
			$message = __d('profile_managment','An error occured');
			$result = 'error';
		}

		$data =  array('message' =>  $message, 'result' => $result, 'id' => $id);
		
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}
}
