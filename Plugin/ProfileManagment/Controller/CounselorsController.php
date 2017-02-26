<?php
App::uses('ProfileManagmentAppController', 'ProfileManagment.Controller');
/**
 * Counselors Controller
 *
 * @property Counselor $Counselor
 * @property PaginatorComponent $Paginator
 */
class CounselorsController extends ProfileManagmentAppController {

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
 * Wizard method
 *
 * @return void
 */
	public function wizard()
	{
		$this->layout = "users_layout";
		
		if ($this->Session->read('Auth.User.id'))
		{
			//$this->Counselor->
		}

	}

/**
 * admin_filter method
 *
 * @return void
 */
	public function admin_filter() {

		$regions = $this->Counselor->City->Region->find('list');
		
		$this->set(compact('regions'));
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

		if($order == "") $order = "Counselor.id DESC";
		
		$conditions = array();
		
		if ( isset($this->params['data']['filter']) )
		{
			if (isset($this->params['data']['filter']['Counselor.region_id']))
			{
				$city_ids = $this->Counselor->City->find('list', array(
					'conditions' => array('City.region_id' => $this->params['data']['filter']['Counselor.region_id']),
					'fields' => array('City.id')
				));
				if(empty($city_ids)) $city_ids = array(-1);
				$conditions = array('Counselor.city_id' => $city_ids);
				unset($this->params->data['filter']['Counselor.region_id']);
				
			}

			$conditions = array_merge($this->params['data']['filter'], $conditions);
		}

		$this->Paginator->settings = array(
			'conditions' => $conditions,
			'contain' => array('City', 'User'),
			'limit' => $limit,
			'page' => $page,
			'order' => $order
		);

		$datum = $this->Paginator->paginate('Counselor');

		$data = array(
			"draw" => (isset($this->params['data']['draw']))? $this->params['data']['draw'] : 1, 
			"recordsTotal" => $this->params['paging']['Counselor']['count'], 
			"recordsFiltered" => $this->params['paging']['Counselor']['count'],
    		"data" => $datum
		);
		$this->set('data', $data);
		$this->set('_serialize', 'data');
	}
}
