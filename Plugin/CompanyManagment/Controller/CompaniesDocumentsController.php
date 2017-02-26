<?php
App::uses('CompanyManagmentAppController', 'CompanyManagment.Controller');
/**
 * CompaniesDocuments Controller
 *
 * @property CompaniesDocument $CompaniesDocument
 * @property PaginatorComponent $Paginator
 */
class CompaniesDocumentsController extends CompanyManagmentAppController {

/**
 * admin_validate_document method
 *
 * @return void
 */
	public function admin_validate_document() {

		$data = $this->__set_document_validation(1);
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}

/**
 * admin_validate_document method
 *
 * @return void
 */
	public function admin_invalidate_document() {

		$data = $this->__set_document_validation(0);
		$this->set('data', $data);
        $this->set('_serialize', 'data');
	}
/**
 * __set_document_validation method
 * @param $status bool
 * @return array mixed
 */
	protected function __set_document_validation($status) {

		$message =__d('request_managment','Une erreur est survenue durant la validation réssayer svp! ');
		$result = 'error';

		if(isset($this->request['data']['id'])){
			$id = $this->request['data']['id'];

			if (!$this->CompaniesDocument->exists($id)) {
				$message = __d('profile_managment', 'Invalid CompaniesDocument');
			}
			else
			{
				$this->CompaniesDocument->id = $id;
	
				if($this->CompaniesDocument->saveField('is_valid', $status))
				{
					$message = __d('request_managment','Le document a été validé avec succès');
					$result = 'success';
				}
				else
				{
					$message = __d('request_managment','La validation du document a été annulée');
				}			
			}
		}

		return array('message' =>  $message, 'result' => $result, 'errors' => array());
	}
}
