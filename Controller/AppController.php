<?php

App::uses('CroogoAppController', 'Croogo.Controller');

/**
 * Base Application Controller
 *
 * @package  Croogo
 * @link     http://www.croogo.org
 */
class AppController extends CroogoAppController {

/**
 * Constructor
 *
 * @access public
 */

	public function beforeFilter() {
		
		$prefix = isset($this->request->params['prefix']) ? $this->request->params['prefix'] : '';
		
		if ($prefix === 'admin') {
			$theme = Configure::read('Site.admin_theme');
			
			if($this->Auth->user('role_id') > 3  && $theme !== 'CapAdmin')
			{
				Configure::write('Site.admin_theme', 'CapAdmin');
				$this->_setupTheme();
			}			
		}

		$this->__getUserMessages();
		parent::beforeFilter();
	}


	private function __getUserMessages(){
		$this->loadModel('MessageManagment.Message');
		$user_email = AuthComponent::user('email');
		
		$unread_messages = $this->Message->find('all',array(
			'contain' => array(
				'Sender' => array(
					'fields' => array('first_name','last_name','email','image')
				)
			),
			'conditions' => array(
				'Message.email_to' => $user_email,
				'Message.mailbox' => 'inbox',
			),
			'order' => array('status', 'created DESC'),
			'limit' => 5,
		));

		$unread_messages_count = $this->Message->find('count',array(
			'conditions' => array(
				'Message.email_to' => $user_email,
				'Message.mailbox' => 'inbox',
				'Message.status' => 0
			)
		));

		$this->set(compact('unread_messages', 'unread_messages_count'));
	}
}





