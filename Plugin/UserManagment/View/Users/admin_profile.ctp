<?php
$this->viewVars['title_for_layout'] = __d('user_managment', 'Users');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('user_managment', 'Espace Membre'), array('action' => 'profile'));
?>
<br>
<br>
<script>

<?php  $this->Html->scriptStart(array('inline' => false, 'block' => 'scriptBottom')); ?>

	jQuery(document).ready(function() {		
		
		$('#change_password').submit(function(e)
		{

	 		var postData = $(this).serializeArray();
			var formURL = $(this).attr("action");
			$('#change_password').trigger('dialogLoader', 'show');
			
			$.ajax(
			{
				url : formURL,
				type: "POST",
				data : postData,
				success:function(response, textStatus, jqXHR) 
				{
					if(response.result == 'success')
					{
						toastr.success(response.message);
						$('#change_password').find('input[type = password]').val("");
					}
					else
					{
						toastr.error(response.message); 
						$.each(response.errors, function(field, errors){
							var id = $('body').find('[name = "data[User]['+field+']"]').attr('id');
							control = document.getElementById(id);
							control.setCustomValidity(errors[0]);
							$('#'+id).on('change', function(){
								control.setCustomValidity('');
							})
						});
					}
					$('#change_password').trigger('dialogLoader', 'hide');
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					$('#change_password').trigger('dialogLoader', 'hide');
					toastr.error("<?php echo __d('message_managment', 'An error occured please try again!'); ?>");
				}
			});	
			e.preventDefault();
			
			return false;		
		});

		$('#edit_user').submit(function(e)
		{
	 		var postData = $(this).serializeArray();
			var formURL = $(this).attr("action");
			$('#edit_user').trigger('dialogLoader', 'show');
			
			$.ajax(
			{
				url : formURL,
				type: "POST",
				data : postData,
				success:function(response, textStatus, jqXHR) 
				{
					if(response.result == 'success')
					{
						toastr.success(response.message);
					}
					else
					{
						toastr.error(response.message); 
						$.each(response.errors, function(field, errors){
							var id = $('body').find('[name = "data[User]['+field+']"]').attr('id');
							control = document.getElementById(id);
							control.setCustomValidity(errors[0]);
							$('#'+id).on('change', function(){
								control.setCustomValidity('');
							})
						});
					}
					$('#edit_user').trigger('dialogLoader', 'hide');
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					$('#edit_user').trigger('dialogLoader', 'hide');
					toastr.error("<?php echo __d('message_managment', 'An error occured please try again!'); ?>");
				}
			});	
			e.preventDefault();
			
			return false;		
		});
});

<?php $this->Html->scriptEnd(); ?></script>

<div class = "clearfix"></div>
<div class="row">
	<div class="col-md-12">
		<div class="tabbable">
			<ul class="nav nav-tabs tab-padding tab-space-3 tab-blue" id="myTab4" style = "margin-left: 0px;">
				<li class="active">
					<a data-toggle="tab" href="#panel_edit_account">
						Mon profile
					</a>
				</li>
				<li>
					<a data-toggle="tab" href="#panel_edit_password">
						Changer le mot de passe
					</a>
				</li>
			</ul>
			<div class="tab-content">
				<div id="panel_edit_account" class="tab-pane fade in active">
						<?php  
							echo $this->Form->create('User', array(
										'url' => array(
											'admin' => true,
											'plugin' => 'user_managment',
											'controller' => 'users',
											'action' => 'edit_user',
											'ext' => 'json'
										),
										'id' => 'edit_user',
										'role' => 'form'
									)
								);
							$this->Form->inputDefaults(array('label' => false, 'div' => false));
						?>
						<fieldset>
							<legend>
								Information du compte
							</legend>
							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										<label>
										Prénom
										</label>
										<?php
											echo $this->Form->input('first_name', array(
												'type' => 'text',
												'placeholder' => "Veuillez saisir votre prénom",
												'class' => 'form-control',
												'required' => true,
												'data-msg' => 'Ce champ est obligatoir',
												'value' => $user['User']['first_name']
											));
										  ?>
									</div>
									<div class="form-group">
										<label>
										Nom	
										</label>
										<?php
											echo $this->Form->input('last_name', array(
												'type' => 'text',
												'placeholder' => "Veuillez saisir votre nom",
												'class' => 'form-control',
												'required' => true,
												'data-msg' => 'Ce champ est obligatoir',
												'value' => $user['User']['last_name']
											));
										  ?>
									</div>
									<div class="form-group">
										<label>
											Email
										</label>
										<?php
											echo $this->Form->input('email', array(
												'type' => 'email',
												'placeholder' => "Veuillez saisir votre email",
												'class' => 'form-control',
												'required' => false,
												'value' => $user['User']['email']
											));
										  ?>
									</div>
									<div class="form-group">
										<label>
											N° de téléphone
										</label>
										<?php
											echo $this->Form->input('phone', array(
												'type' => 'text',
												'placeholder' => "Veuillez saisir votre n° de téléphone",
												'class' => 'form-control',
												'required' => false,
												'value' => $user['User']['phone']
											));
										  ?>
									</div>
									<div class="form-actions">
										<?php 
										echo $this->Form->submit(__d('croogo', 'Sauvegarder'), array(
												'div' => false,
												'class' => 'btn btn-primary pull-right',
												'escape' => false
											)
										);
										?>
									</div>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
				<div id="panel_edit_password" class="tab-pane fade">
						<?php  
							echo $this->Form->create('User', array(
										'url' => array(
											'admin' => true,
											'plugin' => 'user_managment',
											'controller' => 'users',
											'action' => 'change_password',
											'ext' => 'json'
										),
										'id' => 'change_password',
										'role' => 'form'
									)
								);
						?>
						<div class="row">
							<div class="col-md-8 box-forgot">
							<fieldset>
								<legend>
									Changer le mot de passe
								</legend>
								<div class="form-group">
									<span class="input-icon">
										<?php echo $this->Form->input('old_password', array( 
											'type' => 'password',
											'label' => __d('croogo', 'Mot de passe actuel'),
											'required' => true,
										));
										?>
									</span>
								</div>
								<div class="form-group">
									<span class="input-icon">
										<?php echo $this->Form->input('password', array(
											'label' => __d('croogo', 'New password'),
											'required' => true
										));
										?>
									</span>
								</div>
								<div class="form-group">
									<span class="input-icon">
										<?php echo $this->Form->input('verify_password', array('type' => 'password', 'label' => __d('croogo', 'Verify Password'),
											'required' => true
										));
										?>
									</span>
								</div>
								<div class="form-actions">
									<?php 
									echo $this->Form->submit(__d('croogo', 'Sauvegarder'), array(
											'div' => false,
											'class' => 'btn btn-primary pull-right',
											'escape' => false
										)
									);
									?>
								</div>

							</fieldset>
							</div>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<br>