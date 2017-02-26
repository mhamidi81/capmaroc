<?php  $userId = AuthComponent::user('id');?>
<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'admin_save_meeting_request_judgment', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'requests'))) {?>

<a href="#" class="btn btn-lg btn-success btn-send-commissionnary-judgment" request-id = "<?php echo $request['Request']['id'];?>" data-toggle="modal" data-target="#dialog_send_meeting_request_judgment">
	<i class="ti-close"></i>
		Avis de la commission
</a>
<div class="modal fade label_block" id="dialog_send_meeting_request_judgment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="loader"  data-initialize="loader">
	  			<?php echo $this->Html->image("loading-spinner-grey.gif"); ?><span>&nbsp;&nbsp;Operation en cours...</span>
	  		</div>	
			<?php  echo $this->Form->create('MeetingsRequest',
			array('url' => array('controller' => 'requests', 'action' => 'admin_save_meeting_request_judgment', 'ext' => 'json'), 

				'id' => 'save_meeting_request_judgment_form')

			);?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Avis de la commission</h4>
			</div>
			<div class="modal-body">
				<?php

					echo $this->Form->hidden('request_id', array(
						'value' => $request['Request']['id'],
					));
					echo $this->Form->input('meeting_id', array( 
						'type' => 'hidden', 
						'value' => $meeting_id,
					));
				?>
				<div class="form-group">
					<?php

						echo $this->Form->input('judgment_id', array(
							'label' => __('Avis'),
							'class' => 'form-control', 
							'type' => 'select',
							'options' => $judgments,
							'empty' => true,
							'required' => true,
							'selected' => (isset($meeting_request['MeetingsRequest']['judgment_id']))? $meeting_request['MeetingsRequest']['judgment_id'] : ''
						));
						?>
				</div>
				<div class="form-group">
					<?php

						echo $this->Form->input('specialities', array(
							'label' => __('Specialitiés'),
							'class' => 'form-control', 
							'type' => 'select',
							'options' => $official_specialities,
							'empty' => true,
							'id' => 'request_specialities',
							'required' => true,
							'multiple' => true,
							'selected' => (isset($meeting_request['MeetingsRequest']['specialities']))? json_decode($meeting_request['MeetingsRequest']['specialities'], true) : ''
						));
						?>
				</div>
				<div class="form-group">
						<?php
						echo $this->Form->input('description', array(
			 				'class' => 'form-control', 
							'type' => 'textarea',
							'label' => __('Commentaire'),
							'value' => (isset($meeting_request['MeetingsRequest']['description']))? $meeting_request['MeetingsRequest']['description'] : ''
						))
						?>					
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-o" data-dismiss="modal">
					Annuler
				</button>
				<button type="submit" class="btn btn-primary">
					Sauvegarder l'avis
				</button>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
<?php }?>