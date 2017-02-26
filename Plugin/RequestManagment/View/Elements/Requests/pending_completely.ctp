<?php  $userId = AuthComponent::user('id');?>
<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'receive_request_completely', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'requests'))) {?>
		<a href="#" class="btn btn-lg btn-success btn-completely-received" request-id = "<?php echo $request['Request']['id'];?>" >
			<i class="ti-check"></i>
				Confirmer la récéption du complément du dossier
		</a>
<?php } ?>