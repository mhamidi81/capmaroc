<?php  $userId = AuthComponent::user('id');?>
<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'archive_request', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'requests')) && $request['Request']['archived'] == 0) {?>
	<a href="#" class="btn btn-lg btn-success btn-archive-request" request-id = "<?php echo $request['Request']['id'];?>">
		<span class = "fa fa-folder-o"></span>
			Archiver l'agrément
	</a>
<?php } ?>
<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'print_granted_request_decision', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'requests'))) {?>
	<a href="#" class="btn btn-lg btn-success btn-print-granted-request-decision" request-id = "<?php echo $request['Request']['id'];?>">
		<span class = "fa fa-print"></span>
			Imprimer la décision
	</a>
<?php } ?>
<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'print_granted_request_badge', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'requests'))) {?>
	<a href="#" class="btn btn-lg btn-success print-granted-request-badge" request-id = "<?php echo $request['Request']['id'];?>">
		<span class = "fa fa-print"></span>
			Imprimer le badge
	</a>
<?php } ?>
