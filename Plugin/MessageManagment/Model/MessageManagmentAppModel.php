<?php

App::uses('AppModel', 'Model');
App::uses('CakeEmail', 'Network/Email');

class MessageManagmentAppModel extends AppModel {
	
	public $tablePrefix = 'msm_';
	public $actsAs = array('Containable');

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
}
