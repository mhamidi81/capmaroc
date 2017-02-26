<?php  $userId = AuthComponent::user('id');?>
<?php 
$connected_memeber_statuses = array();
foreach ($request_statuses as $key => $request_status) {
	if($request_status['RequestStatus']['user_id'] == $userId)
	{
		$connected_memeber_statuses = $request_status;
		break;
	}
}
?>
<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'admin_save_request_decision', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'requests'))) {?>
<div class="col-sm-6 col-md-6">
		<?php  echo $this->Form->create('Request',
		array('url' => array('controller' => 'requests', 'action' => 'admin_save_request_decision', 'ext' => 'json'), 

			'id' => 'admin_save_request_decision')

		);?>
		<?php
			echo $this->Form->input('request_id', array( 
				'type' => 'hidden', 
				'value' => $request['Request']['id'],
			));

			echo $this->Form->input('RequestStatus.id', array( 
				'type' => 'hidden', 
				'value' => (!empty($connected_memeber_statuses))? $connected_memeber_statuses['RequestStatus']['id']: '',
			));			
		?>
		<div class="form-group">
			<?php
			echo $this->Form->input('judgment', array(
				'label' => __('Decision de Mr le ministre'),
				'class' => 'form-control', 
				'type' => 'select',
				'selected' => (!empty($connected_memeber_statuses))? $connected_memeber_statuses['Status']['alias']: '',
				'options' => array('granted' => 'Favorable', 'refused' => 'Défavorable'),
				'empty' => true,
				'required' => true
			));
			?>
		</div>
		<div class="form-group">
			<?php
			echo $this->Form->input('description', array(
 				'class' => 'form-control', 
				'type' => 'textarea',
				'label' => __('Commentaire'),
				'value' => (!empty($connected_memeber_statuses))? $connected_memeber_statuses['RequestStatus']['description']: '',
			))
			?>					
		</div>
			<div class="form-group margin-bottom-0">
				<div class="margin-left-30" id = "send-meeting-judgment-toolbar">
				<?php 
				echo $this->Form->button(__d('request_managment', "Valider votre décision"), array('class' => 'btn btn-success btn-lg'));
				?>
				</div>
			</div>
	<?php echo $this->Form->end(); ?>
<?php }?>
</div>
