<?php
App::uses('DashboardAppController', 'Dashboard.Controller');
/**
 * Dashboards Controller
 *
 * @property Dashboard $Dashboard
 * @property PaginatorComponent $Paginator
 */
class DashboardsController extends DashboardAppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

	public $uses = array('RequestManagment.Request');


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
	public function admin_index($year = false) {

		if(!$year){
			$year = date('Y');
		}

		$statuses = $this->Request->Status->find('list');
		$role_id = AuthComponent::user('role_id');
		$connected_user_role = $this->Request->MembersRequest->User->Role->field('alias', array('Role.id' => $role_id));

		$char1data = $this->__chart1data($year);
		$char2data = $this->__chart2data($year);
		$chart3data = $this->__chart3data($year);
		$chart4data = $this->__chart4data($year);
		$this->set(compact('year', 'user_statuses', 'char1data', 'char2data', 'chart3data','chart4data'));
		
		if($role_id  == 1)
		{
			$this->render('blank');
		}
	}
	
	protected function __chart1data ($year){

		$request_per_mounth = array('Jan' => 0, 'Fév' => 0, 'Mar' => 0, 'Avr' => 0, 'Mai' => 0, 'Jui' => 0, 'Juil' => 0, 'Aôu' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Déc' => 0);
		$data = array('granted' => array_values($request_per_mounth), 'refused' => array_values($request_per_mounth), 'labels' => array_keys($request_per_mounth));
		$mounth_abr = array_keys($request_per_mounth);
		
		$requests = $this->Request->find('all', array(
			'fields' => array('count(*) total', 'MONTH(Request.event_date) mounth', 'Status.alias'),
			'conditions' => array('Status.alias' => array('granted', 'refused'), 'YEAR(Request.event_date)' => $year),
			'group' => array('MONTH(Request.event_date)', 'Status.alias'),
			'order' => array('Request.event_date'),
			'contain' => array('Status.alias')
		));

		foreach ($requests as $key => $datum) {

			if(isset($datum[0]) && isset($datum['Status'])) {
				$data[$datum['Status']['alias']][$datum[0]['mounth'] - 1] = $datum[0]['total'];
			}
		}

		return $data;
	}

	protected function __chart2data ($year){

		$request_per_mounth = array('Jan' => 0, 'Fév' => 0, 'Mar' => 0, 'Avr' => 0, 'Mai' => 0, 'Jui' => 0, 'Juil' => 0, 'Aôu' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Déc' => 0);
		$data = array('natural_count' => 0,'legal_count' => 0, 'legal' => array_values($request_per_mounth), 'natural' => array_values($request_per_mounth), 'labels' => array_keys($request_per_mounth));
		$mounth_abr = array_keys($request_per_mounth);
		
		$requests = $this->Request->find('all', array(
			'fields' => array('count(*) total', 'MONTH(Request.event_date) mounth', 'Request.requester_type'),
			'conditions' => array('Status.alias' => 'granted', 'YEAR(Request.event_date)' => $year),
			'group' => array('MONTH(Request.event_date)', 'Request.requester_type'),
			'order' => array('Request.event_date'),
			'contain' => array('Status.alias')
		));


		foreach ($requests as $key => $datum) {

			if(isset($datum[0]) && isset($datum['Request'])) {
				$data[$datum['Request']['requester_type']][$datum[0]['mounth'] - 1] = $datum[0]['total'];
				$data[$datum['Request']['requester_type'].'_count'] += $datum[0]['total'];
			}
		}

		return $data;
	}

	protected function __chart3data ($year){

		$data = array('granted' => 0, 'refused' => 0);
		
		$requests = $this->Request->find('all', array(
			'fields' => array('count(*) total', 'Status.alias'),
			'conditions' => array('Status.alias' => array('granted', 'refused'), 'YEAR(Request.event_date)' => $year),
			'group' => array('Status.alias'),
			'contain' => array('Status.alias')
		));

		foreach ($requests as $key => $datum) {

			if(isset($datum[0]) && isset($datum['Status'])) {
				$data[$datum['Status']['alias']] = $datum[0]['total'];
			}
		}

		return $data;
	}

	protected function __chart4data ($year){

		$regions_by_ids = $this->Request->Counselor->City->Region->find('list');

		$requests_per_region = array_fill_keys(array_keys($regions_by_ids), 0);
		
		$requests = $this->Request->find('all', array(
			'conditions' => array('Status.alias' => 'granted', 'YEAR(Request.event_date)' => $year),
			'contain' => array('Status.alias', 'Counselor.City', 'Company.City')
		));



		foreach ($requests as $key => $datum) {
			
			if($datum['Request']['requester_type'] == 'legal')
			{
				//if(isset($datum['Company']['City']['region_id']))
				$requests_per_region[$datum['Company']['City']['region_id']] += 1; 
			}
			else
			{
				//if(isset($datum['Counselor']['City']['region_id']))
				$requests_per_region[$datum['Counselor']['City']['region_id']] += 1; 
			}
		}

		$data = array('labels' => array_values($regions_by_ids), 'values' => array_values($requests_per_region));

		return $data;
	}


}
