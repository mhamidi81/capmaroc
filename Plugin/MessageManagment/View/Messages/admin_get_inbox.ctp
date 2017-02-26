<?php
	echo $this->element('inbox', array(
		'messages' => $messages
		), array('plugin' => 'MessageManagment')
	);
?>