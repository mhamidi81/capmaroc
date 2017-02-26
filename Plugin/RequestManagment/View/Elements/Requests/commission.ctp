<?php  $userId = AuthComponent::user('id');?>
<?php 
$connected_memeber_judgment = array();
foreach ($members_judgments as $key => $member_judgment) {
	if($member_judgment['MembersRequest']['user_id'] == $userId)
	{
		$connected_memeber_judgment = $member_judgment['MembersRequest'];
		break;
	}
}
?>
<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'save_member_request_judgment', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'requests'))) {?>
<a href="#" class="btn btn-lg btn-success btn-send-commissionnary-judgment" request-id = "<?php echo $request['Request']['id'];?>" data-toggle="modal" data-target="#dialog_send_commissionnary_judgment">
	<i class="ti-close"></i>
		<?php if(!empty($connected_memeber_judgment)) echo 'Editer votre avis'; else echo 'Envoyer votre avis'; ?>
</a>
<div class="modal fade label_block" id="dialog_send_commissionnary_judgment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="loader"  data-initialize="loader">
	  			<?php echo $this->Html->image("loading-spinner-grey.gif"); ?><span>&nbsp;&nbsp;Operation en cours...</span>
	  		</div>
			<?php  echo $this->Form->create('MembersRequest',
			array('url' => array('controller' => 'requests', 'action' => 'save_member_request_judgment', 'ext' => 'json'), 

				'id' => 'save_member_request_judgment_form')

			);?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Avis du membre de la commission</h4>
				</div>
				<div class="modal-body">
					<?php

						echo $this->Form->hidden('request_id', array( 
							'value' => $request['Request']['id']
						));
					?>
					<div class="form-group">
						<?php

							echo $this->Form->input('judgment_id', array(
								'label' =>  __('Avis du membre de la commission'),
								'class' => 'form-control', 
								'type' => 'select',
								'options' => $judgments,
								'empty' => true,
								'required' => true,
								'selected' => (isset($connected_memeber_judgment['judgment_id']))? $connected_memeber_judgment['judgment_id'] : ''
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
								'selected' => (isset($connected_memeber_judgment['specialities']))? json_decode($connected_memeber_judgment['specialities'], true) : ''
							));
							?>
					</div>
					<div class="form-group">
							<?php
							echo $this->Form->input('description', array(
				 				'class' => 'form-control', 
								'type' => 'textarea',
								'label' => __('Commentaire'),
								'div' => false,
								'value' => (isset($connected_memeber_judgment['description']))? $connected_memeber_judgment['description'] : ''
							))
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
<?php }?>