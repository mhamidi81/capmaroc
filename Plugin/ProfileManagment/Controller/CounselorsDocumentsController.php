<?php
App::uses('ProfileManagmentAppController', 'ProfileManagment.Controller');
/**
 * CounselorsDocuments Controller
 *
 * @property CounselorsDocument $CounselorsDocument
 * @property PaginatorComponent $Paginator
 */
class CounselorsDocumentsController extends ProfileManagmentAppController {


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
		
		$success = false;
		$result = 'error';

		if(isset($this->request['data']['id'])){
			$id = $this->request['data']['id'];
		}
		else
		{
			$id = -1;
		}

		if (!$this->CounselorsDocument->exists($id)) {
			$message = __d('profile_managment', 'Invalid %s', __d('profile_managment', 'counselor'));
		}
		else
		{
			$counselor_document = $this->CounselorsDocument->find('first', array(
				'conditions' => array('CounselorsDocument.id' => $id)
			));
			$this->CounselorsDocument->id = $id;
			
			if($this->CounselorsDocument->saveField('is_valid', $status))
			{
				if($status)
					$message = __d('request_managment','Le document a été validé avec succès');
				else
					$message = __d('request_managment','La validation du document a été annulée');

				$result = 'success';
			}
			else
			{
				$message =__d('request_managment','Une erreur est survenue durant la validation réssayer svp! ');
			}				
		}

		return array('message' =>  $message, 'result' => $result, 'errors' => array());
	}
}
