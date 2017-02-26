<?php  $userId = AuthComponent::user('id');?>
<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'validate_requester', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'requests'))) {?>
	<a href="#" class="btn btn-lg btn-success btn-validate-profile" request-id = "<?php echo $request['Request']['id'];?>">
		<span class = "ti-check"></span>
			Envoyer à la direction
	</a>
<?php } ?>
<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'completely_required', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'requests'))) {?>
	<a href="#" class="btn btn-lg btn-warning btn-invalidate-profile" request-id = "<?php echo $request['Request']['id'];?>" >
		<i class="ti-close"></i>
			Dossier Incomplet
	</a>
<?php } ?>