<?php  $userId = AuthComponent::user('id');?>
<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'send_to_coordinator', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'requests'))) {?>
	<a href="#" class="btn btn-lg btn-success btn-send-to-coordinate" request-id = "<?php echo $request['Request']['id'];?>">
		<span class = "ti-check"></span>
			Envoyer au coordinateur
	</a>
<?php } ?>
<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'completely_required', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'requests'))) {?>
	<a href="#" class="btn btn-lg btn-warning btn-completely-required"  data-toggle="modal" data-target="#dialog_counselor_completion">
		<i class="ti-close"></i>
			Dossier papier incomplet
	</a>
<div class="modal fade" id="dialog_counselor_completion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="loader"  data-initialize="loader">
	  			<?php echo $this->Html->image("loading-spinner-grey.gif"); ?><span>&nbsp;&nbsp;Operation en cours...</span>
	  		</div>
				<?php  
					echo $this->Form->create('Message', array(
								'url' => array(
									'admin' => true,
									'controller' => 'requests',
									'action' => 'completely_required',
									'ext' => 'json'
								),
								'id' => 'request_completely_form'
							)
						);
					$this->Form->inputDefaults(array('div' => true, 'id' => false));
					echo $this->Form->hidden('Request.id', array(
						'value' => $request['Request']['id'] 
					));
				?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Demande de complément du dossier papier</h4>
				</div>
				<div class="modal-body">
					<div class="form-group required">
						<?php
							echo $this->Form->input('title', array(
								'label' => 'Sujet',
								'type' => 'text',
								'class' => 'form-control',
								'required' => true,
								'data-msg' => 'Ce champ est obligatoir'
							));
						?>
					</div>

					<div class="form-group required">
						<?php
							echo $this->Form->input('body', array(
								'label' => 'Message',
								'type' => 'textarea',
								'class' => 'form-control',
								'required' => true,
								'data-msg' => 'Ce champ est obligatoir'
							));
						?>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary btn-o" data-dismiss="modal">
						Annuler
					</button>
					<button type="submit" class="btn btn-primary">
						Envoyer
					</button>
				</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
<?php } ?>
<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'rollback_request_status_to_creation', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'requests'))) {?>
	<a href="#" class="btn btn-lg btn-warning btn-rollback-to-creation"  data-toggle="modal" data-target="#dialog_rollback_to_creation">
		<i class="ti-close"></i>
			Dossier éléctronique incomplet
	</a>
<div class="modal fade" id="dialog_rollback_to_creation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="loader"  data-initialize="loader">
	  			<?php echo $this->Html->image("loading-spinner-grey.gif"); ?><span>&nbsp;&nbsp;Operation en cours...</span>
	  		</div>
				<?php  
					echo $this->Form->create('Message', array(
								'url' => array(
									'admin' => true,
									'controller' => 'requests',
									'action' => 'rollback_request_status_to_creation',
									'ext' => 'json'
								),
								'id' => 'rollback_to_creation_form'
							)
						);
					$this->Form->inputDefaults(array('div' => false, 'id' => false));
					echo $this->Form->hidden('Request.id', array(
						'value' => $request['Request']['id']
					));
				?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Demande de rectification du dossier éléctronique</h4>
				</div>
				<div class="modal-body">
					<div class="form-group required">
						<?php
							echo $this->Form->input('title', array(
								'label' => 'Sujet',
								'type' => 'text',
								'class' => 'form-control',
								'required' => true,
								'data-msg' => 'Ce champ est obligatoir',
								'value' => 'Demande de rectification'
							));
						?>
					</div>

					<div class="form-group required">
						<?php
							echo $this->Form->input('body', array(
								'label' => 'Message',
								'type' => 'textarea',
								'class' => 'form-control',
								'required' => true,
								'data-msg' => 'Ce champ est obligatoir'
							));
						?>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary btn-o" data-dismiss="modal">
						Annuler
					</button>
					<button type="submit" class="btn btn-primary">
						Envoyer
					</button>
				</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
<?php } ?>