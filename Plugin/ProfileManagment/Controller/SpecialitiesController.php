<?php
App::uses('ProfileManagmentAppController', 'ProfileManagment.Controller');
/**
 * Specialities Controller
 *
 * @property Speciality $Speciality
 * @property PaginatorComponent $Paginator
 */
class SpecialitiesController extends ProfileManagmentAppController {

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

		if($order == "") $order = "Speciality.id DESC";
		
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

		$datum = $this->Paginator->paginate('Speciality');

		$data = array(
			"draw" => (isset($this->params['data']['draw']))? $this->params['data']['draw'] : 1, 
			"recordsTotal" => $this->params['paging']['Speciality']['count'], 
			"recordsFiltered" => $this->params['paging']['Speciality']['count'],
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
		$official_specialities = $this->Speciality->OfficialSpeciality->find('list');
		$this->set(compact('official_specialities'));
	}

/**
 * admin_add method
 *
 * @return void
 */
 	public function admin_add() {

		$inserted_record = array();
		$errors = array();
		$this->Speciality->create();
		
		if ($this->Speciality->save($this->request->data)) {
			$message = __d('profile_managment','The speciality has been saved');
			$result = 'success';
			$inserted_record = $this->Speciality->find('first', array(
				'conditions' => array(
					'Speciality.id' => $this->Speciality->id
				)
			));
		} else {
			$errors = $this->Speciality->validationErrors;
			$message =__d('profile_managment','The speciality could not be saved. Please, try again.');
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

		if ($this->Speciality->save($this->request->data)) {

			$message = __d('profile_managment','The speciality has been saved');
			$result = 'success';
			$updated_record = $this->Speciality->find('first', array(
				'conditions' => array(
					'Speciality.id' => $this->Speciality->id
				)
			));

		} else {
			$errors = $this->Speciality->validationErrors;
			$message =__d('profile_managment','The speciality could not be saved. Please, try again.');
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

		if ($this->Speciality->delete($id)) {
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
