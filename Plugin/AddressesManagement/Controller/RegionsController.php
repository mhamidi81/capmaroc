<?php
App::uses('AddressesManagementAppController', 'AddressesManagement.Controller');
/**
 * Regions Controller
 *
 * @property Region $Region
 * @property PaginatorComponent $Paginator
 */
class RegionsController extends AddressesManagementAppController {

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

		if($order == "") $order = "Region.id DESC";
		
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

		$datum = $this->Paginator->paginate('Region');

		$data = array(
			"draw" => (isset($this->params['data']['draw']))? $this->params['data']['draw'] : 1, 
			"recordsTotal" => $this->params['paging']['Region']['count'], 
			"recordsFiltered" => $this->params['paging']['Region']['count'],
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
		$this->Region->create();
		
		if ($this->Region->save($this->request->data)) {
			$message = __d('addresses_management','The region has been saved');
			$result = 'success';
			$inserted_record = $this->Region->find('first', array(
				'conditions' => array(
					'Region.id' => $this->Region->id
				)
			));
		} else {
			$errors = $this->Region->validationErrors;
			$message =__d('addresses_management','The region could not be saved. Please, try again.');
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

		if ($this->Region->save($this->request->data)) {

			$message = __d('addresses_management','The region has been saved');
			$result = 'success';
			$updated_record = $this->Region->find('first', array(
				'conditions' => array(
					'Region.id' => $this->Region->id
				)
			));

		} else {
			$errors = $this->Region->validationErrors;
			$message =__d('addresses_management','The region could not be saved. Please, try again.');
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

		if ($this->Region->delete($id)) {
			$message = __d('addresses_management','Request deleted');
			$result = 'success';
		} else {
			$message = __d('addresses_management','An error occured');
			$result = 'error';
		}

		$data =  array('message' =>  $message, 'result' => $result, 'id' => $id);
		
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}
}
