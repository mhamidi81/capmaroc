<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
class RequestManagmentAppController extends AppController {



/**
 * Cached actions per Role
 *
 * @var array
 * @access public
 */
	public $allowedActions = array();

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

	protected function __isUserAutorized($url){
		$roleId = AuthComponent::user('role_id');
		
		if($roleId)
		{
			if (isset($url['admin']) && $url['admin'] == true) {
				$url['action'] = 'admin_' . $url['action'];
			}

			$plugin = empty($url['plugin']) ? null : Inflector::camelize($url['plugin']) . '/';
			$path = '/:plugin/:controller/:action';
			$path = str_replace(
				array(':controller', ':action', ':plugin/'),
				array(Inflector::camelize($url['controller']), $url['action'], $plugin),
				'controllers/' . $path
			);
			$linkAction = str_replace('//', '/', $path);

			if (in_array($linkAction, $this->__getAllowedActionsByRoleId($roleId))) {
				return true;
			}			
		}

		return false;
	}

/**
 * Returns an array of allowed actions for current logged in User
 *
 * @param integer $userId Role id
 * @return array
 */
	private function __getAllowedActionsByRoleId($roleId) {
		if (!empty($this->allowedActions[$roleId])) {
			return $this->allowedActions[$roleId];
		}

		$plugin = Configure::read('Site.acl_plugin');
		App::uses('AclPermission', $plugin . '.Model');
		$this->AclPermission = ClassRegistry::init($plugin . '.AclPermission');
		$this->allowedActions[$roleId] = $this->AclPermission->getAllowedActionsByRoleId($roleId);

		return $this->allowedActions[$roleId];
	}

}
