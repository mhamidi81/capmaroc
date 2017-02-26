<?php
$this->viewVars['title_for_layout'] = __d('user_managment', 'Users');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('user_managment', 'Utilisateurs'), array('action' => 'index'));
?>

<script>

<?php  $this->Html->scriptStart(array('inline' => false, 'block' => 'scriptBottom')); ?>

var userCrud = {
		datagrid : {},
		init : function(){
		     userCrud.datagrid = $('#user_datagrid').DataTable({
		        "processing": true,
		        "serverSide": true,
		        "language": {
					"lengthMenu": "_MENU_ Enregistrements par page",
					"processing": '<?php echo $this->Html->image("loading-spinner-grey.gif"); ?><span>&nbsp;&nbsp;Operation en cours...</span>',
					"sInfo": "",
					"sInfoEmpty": "",
					"zeroRecords" : 'aucun enregistrement trouvé' 
				},
		        "ajax": {
		        	url : '<?php echo $this->Html->url(array('action' => 'get_datagrid_data', 'ext' => 'json')); ?>',
		        	type: "POST",
		 			data : function ( d ) {
					  	var value = $('#UserFilter').find('input[type = search]').val();
					  	var column = $('#UserFilter').find('.hidden').val();
					  	
					  	if(column && value)
					  	{
					  		d['filter'] = {};
					  		d['filter'][column] = value;
					  	}	
		            }
		        },
				"sort": true,
				"filter": false,
				"columns": [
					{
						title:  '<?php echo __d('user_managment', 'Rôle'); ?>',
						data: 'Role.title',
						sortable: true
					},
					{
						title: '<?php echo __d('user_managment', 'Prénom'); ?>',
						data: 'User.first_name',
						sortable: true
					},
					{
						title: '<?php echo __d('user_managment', 'Nom'); ?>',
						data: 'User.last_name',
						sortable: true
					},
					{
						title: '<?php echo __d('user_managment', 'Login'); ?>',
						data: 'User.username',
						sortable: true
					},
					{
						title: '<?php echo __d('user_managment', 'Email'); ?>',
						data: 'User.email',
						sortable: true
					},
					{
					title:  '<?php echo __d('request_managment', 'Actions'); ?>',
					data: null,
					sortable: false
				}],
				"columnDefs": [{
					"targets": [5],
					"width" : "180px",
					render: function (e, type, data, meta)
					{	
						var actions = [];

						actions.push({
							'value': 'Modifier',
							'attr': {
								'icon': 'pencil',
								'class': "btn btn-xs btn-primary btn-edit",
								'action-id': data.User.id
							}
						});	

						actions.push({
							'value': 'Supprimer',
							'attr': {
								'icon': 'remove',
								'class': "btn btn-xs btn-danger btn-delete",
								'action-id': data.User.id
							}
						});	
						return createButtonGroup(actions);
					}
				}],
		    });			
		},
		addRow : function(postData){
			var formURL = $('#add_user_form').attr("action");
			$('#UserAddDialog').trigger('dialogLoader', 'show');
			
			$.ajax(
			{
				url : formURL,
				type: "POST",
				data : postData,
				contentType: false,
	    	    cache: false,
				processData:false,
				success:function(response, textStatus, jqXHR) 
				{
					if(response.result == 'success')
					{
						userCrud.datagrid.row.add(response.record).draw();
						toastr.success(response.message);
						$('#add_user_form').find('input, select').val('');
						$('#UserAddDialog').modal('hide');
					}
					else
					{
						toastr.error(response.message);
						
						$.each(response.errors, function(field, errors){
							var id = $('#UserAddDialog').find('[name = "data[User]['+field+']"]').attr('id');
							control = document.getElementById(id);
							control.setCustomValidity(errors[0]);
							$('#'+id).on('change', function(){
								control.setCustomValidity('');
							})
						});
					}
					$('#UserAddDialog').trigger('dialogLoader', 'hide');
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					$('#UserAddDialog').trigger('dialogLoader', 'hide');
					toastr.error("<?php echo __d('user_managment', 'An error occured please try again!'); ?>");
				}
			});
			
		},
		deleteRow : function(id, tr){

			$('#user_datagrid').trigger('loader', 'show');
			$.ajax(
			{
				url : '<?php echo Router::url(array('action' => 'delete', 'ext' => 'json')); ?>',
				type: "POST",
				data : {id : id},
				success:function(response, textStatus, jqXHR) 
				{
					if(response.result == 'success')
					{
						userCrud.datagrid.row(tr).remove().draw( false );
						toastr.success(response.message);
					}
					else
					{
						toastr.error(response.message); 
					}
					$('#user_datagrid').trigger('loader', 'hide');
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					$('#user_datagrid').trigger('loader', 'hide');
					toastr.error("<?php echo __d('user_managment', 'An error occured please try again!'); ?>");
				}
			});
		},
		updateRow : function(postData){
			var formURL = $('#edit_user_form').attr("action");
			$('#UserEditDialog').trigger('dialogLoader', 'show')
			$.ajax(
			{
				url : formURL,
				type: "POST",
				data : postData,
				contentType: false,
	    	    cache: false,
				processData:false,
				success:function(response, textStatus, jqXHR) 
				{
					var tr = $('[action-id = '+response.record.User.id+']').closest('tr'); 
					if(response.result == 'success')
					{
						userCrud.datagrid.row(tr).data( response.record ).draw();
						toastr.success(response.message);
						$('#UserEditDialog').modal('hide'); 
					}
					else
					{
						toastr.error(response.message);
						$.each(response.errors, function(field, errors){
							var id = $('#UserEditDialog').find('[name = "data[User]['+field+']"]').attr('id');
							control = document.getElementById(id);
							control.setCustomValidity(errors[0]);
							$('#'+id).on('change', function(){
								control.setCustomValidity('');
							})
						});
					}
					 $('#UserEditDialog').trigger('dialogLoader', 'hide'); 
					
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
	  				$('#UserEditDialog').trigger('dialogLoader', 'hide');
	  				toastr.error("<?php echo __d('user_managment', 'An error occured please try again!'); ?>");
				}
			});
		}
	}

	jQuery(document).ready(function() {
		userCrud.init();

	 	$('#user_datagrid tbody').on('click', '.btn-open', function(){
	 		userCrud.showDetail(this)
	 	});

		//datagrid ajax form 
		$('.users').on('click', '.btn-delete', function(e)
		{
			var id = $(this).attr("action-id");
			var tr = $(this).closest("tr");
			
			if(confirm("<?php echo __d('user_managment', 'Are you sure'); ?>")){
				userCrud.deleteRow(id, tr);
			}
			
			e.preventDefault();

			return false;
		});

		$('#UserAddDialog, #UserEditDialog').on('dialogLoader', function(e, action){

			if(action == 'hide')
			{
				$(this).find('.loader').hide();
			}
			else
			{
				$(this).find('.loader').show();
			}
		});

		/*$("#AddUserPassword").on('change', verify_password);
		$("#AddUserVerifyPassword").on('keyup', verify_password);
		
		function verify_password(){

			var password = document.getElementById("AddUserPassword");
			var confirm_password = document.getElementById("AddUserVerifyPassword");
			
			if(confirm_password.value !== password.value)
			{console.log(confirm_password.value+ '!=='+ password.value);
				confirm_password.setCustomValidity("Les Mots de passe ne correspondent pas!");
			}
			else
			{
				confirm_password.setCustomValidity(""); 
			}
		}*/

		//datagrid ajax add form 
		$('#add_user_form').submit(function(e)
		{
			var $form = $(this);
			var formdata = (window.FormData) ? new FormData($form[0]) : null;
			var data = (formdata !== null) ? formdata : $form.serialize();
			userCrud.addRow(data);
			e.preventDefault();

			return false;
		});

		//datagrid ajax edit form 
		$('#edit_user_form').submit(function(e)
		{
			var $form = $(this);
			var formdata = (window.FormData) ? new FormData($form[0]) : null;
			var data = (formdata !== null) ? formdata : $form.serialize();
			userCrud.updateRow(data);
			e.preventDefault();

			return false;
		});

		$(document).on('click', '.btn-edit', function(event){
			$('#edit_user_form').find('input, select').val('');
			$('#editUserPreview').attr('src', "/capmaroc/theme/CapAdmin/img/default-user.png");
			var data = userCrud.datagrid.row($(this).closest('tr')).data();

			$('#edit_user_form input, #edit_user_form select').each(function(){

				if($(this).attr('id'))
				{	
					regex = /\[([^\]]*)]/g;
					keys = [];
					
					while (m = regex.exec($(this).attr('name'))) {
					  keys.push(m[1]);
					}

					if(data.hasOwnProperty(keys[0]) && data[keys[0]][keys[1]]){
						
						if($(this).attr('type') == "file")
						{
							 $('#editUserPreview').attr('src', Croogo.basePath+'uploads/users/'+data[keys[0]][keys[1]]);
						}
						else
						if($(this).parent().hasClass('checkbox'))
						{
							if($(this).attr('type') == 'checkbox')
							{
								$(this).prop('checked', data[keys[0]][keys[1]]);
								$(this).val(1);
							}
							else
							{
								$(this).val(0);
							}
						}
						else

						{
							$(this).val(data[keys[0]][keys[1]]);
						}
						
					}
				}
			});

			$('#UserEditDialog').modal('show');
			
			event.preventDefault();
			return false;
		});

		$('#UserFilter').on('click', 'a', function (e) {
		  	var field_name =  $(this).parent().attr('data-value')
		  	var field_label = $(this).text();
		  	$(this).closest('.datagrid-search').find('.hidden').val(field_name);
		  	$(this).closest('.datagrid-search').find('.selected-label').text(' ' +field_label);
		  	$(this).closest('.datagrid-search').find('input[type = search]').val("");
		  	$(this).closest('.datagrid-search').find('input[type = search]').attr('placeholder', 'Chercher par '+field_label);
		});

		$('#UserFilter .search').on('click', '.btn', function (e) {
		  	userCrud.datagrid.ajax.reload();
		});

		$('#UserEditDialog').on('hidden.bs.modal', function (e) {
		  	$('#edit_user_form').clearForm();
		});

		$('#UserAddDialog').on('hidden.bs.modal', function (e) {
		  	$('#add_user_form').clearForm();
		});

		$("#EditUserImage").change(function(event){
		    if (this.files && this.files[0]) {
		        var reader = new FileReader();

		        reader.onload = function (e) {
		            $('#editUserPreview').attr('src', e.target.result);
		        }

		        reader.readAsDataURL(this.files[0]);
		    }
		});
		$("#EditUserStatus").change(function(event){
		   
		   if($(this).prop('checked') == true)
		   {
		   		$("#EditUserStatus").val(1);
		   		$("#EditUserStatus_").val(1);
		   }
		   else
		   {
		   		$("#EditUserStatus").val(0);
		   		$("#EditUserStatus_").val(0);
		   }
		});

		$.fn.clearForm = function() {
			
			return this.each(function() {
				var type = this.type, tag = this.tagName.toLowerCase();
				
				if (tag == 'form')
					return $(':input',this).clearForm();
				if (type == 'text' || type == 'password' || tag == 'textarea')
					this.value = '';
				else if (type == 'checkbox' || type == 'radio')
					this.checked = false;
				else if (tag == 'select')
					this.selectedIndex = -1;
			});
		};	
	});

<?php $this->Html->scriptEnd(); ?></script>

<div class="users index">
	<div class="datagrid" id="user_datagrid_container">
		<div class="datagrid-toolbar">
			<div class="col-xs-12 col-sm-6 col-md-8 no-padding">
				<!-- Button trigger modal -->
				<?php  echo $this->Croogo->adminAction(

						__d('user_managment', 'Ajouter un utilisateur'), '#',

						array('button' => 'primary', 'data-toggle' => 'modal', 'data-target' =>'#UserAddDialog')

					);?>			</div>
			<div class="col-xs-6 col-md-4 no-padding">
			  	<div class="datagrid-search" id = "UserFilter">
					<div class="input-group">
						<div class="input-group-btn selectlist" data-resize="auto" data-initialize="selectlist">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
								<span class="selected-label">Email</span>
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
					 		</button>
							<ul class="dropdown-menu" role="menu">
								<li data-value="User.email">	
									<a href="#">Email</a>
								</li>								
								<li data-value="Role.title">	
									<a href="#">Rôle</a>
								</li>											
								<li data-value="User.first_name">	
									<a href="#">Prénom</a>
								</li>											
								<li data-value="User.last_name">	
									<a href="#">Nom</a>
								</li>											
								<li data-value="User.username">	
									<a href="#">Login</a>
								</li>										
					
							</ul>
							<input class="hidden hidden-field" name="column" readonly="readonly" aria-hidden="true" type="text" value = "User.id">
						</div>
						<div class="search input-group">
							<input type="search" class="form-control" placeholder="<?php  echo __d('user_managment', 'Chercher par email');  ?>"/>
						  	<span class="input-group-btn">
								<button class="btn btn-default" type="button">
							  		<span class="glyphicon glyphicon-search"></span>
							  		<span class="sr-only">
							  		<?php  echo __d('user_managment', 'Recherche');  ?>							 		</span>
								</button>
						  	</span>
						</div>
					</div>
			  	</div>
			</div>
			<div class = "clear"></div>
	  	</div>
		<table id="user_datagrid" class="display table-bordered"></table>
	</div>
</div>

<div class="modal fade" id="UserAddDialog" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="UserEdition" data-backdrop = "static">
 
	<?php  echo $this->Form->create('User',
			array('url' => array('controller' => 'users', 'action' => 'add', 'ext' => 'json'), 

				'id' => 'add_user_form', 'type' => 'file')

			);?>	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">
						<?php  echo __d('user_managment', 'Close');  ?>
					</span>
				</button>
				<h4 class="modal-title">
					<?php  echo __d('user_managment', "Ajout d'un utilisateur");  ?>				</h4>
			</div>

			<div class="modal-body">
			<?php
				$this->Form->inputDefaults(array('label' => false, 'class' => 'span10'));
				
				echo $this->Form->input('role_id', array(
					'label' => __d('user_managment', 'Rôle:'),
					'id' => 'AddUserRoleId',
					'required' => true
				));
				echo $this->Form->input('service_id', array(
					'label' => __d('user_managment', 'Service:'),
					'id' => 'AddUserServiceId',
					'required' => true
				));
				
				echo $this->Form->input('first_name', array(
					'label' => __d('user_managment', 'Prénom:'),
					'id' => 'AddUserFirstName',
					'required' => true
				));
				echo $this->Form->input('last_name', array(
					'label' => __d('user_managment', 'Nom:'),
					'id' => 'AddUserLastName',
					'required' => true
				));
				echo $this->Form->input('username', array(
					'label' => __d('user_managment', 'Login:'),
					'id' => 'AddUserUsername',
					'required' => true
				));

				echo $this->Form->input('email', array(
					'label' => __d('user_managment', 'Email:'),
					'id' => 'AddUserEmail',
					'required' => true
				));
				echo $this->Form->input('image', array(
					'label' => __d('user_managment', 'Image:'),
					'id' => 'AddUserImage',
					'required' => false,
					'type' => 'file',
					'accept' => 'image/*'
				));
				?>
				<div class="input text">
					<label for="AddUserStatus">
						<?php echo __d('user_managment', 'Statut:'); ?>
					</label>
					<?php 
					echo $this->Form->input('status', array(
						'id' => 'AddUserStatus',
						'div' => false,
						'label' => false,
						'type' => 'checkbox'
					));
					?>
				</div>
			</div>
		  	<div class="loader" data-initialize="loader">
		  		<?php echo $this->Html->image("loading-spinner-grey.gif"); ?><span>&nbsp;&nbsp;Operation en cours...</span>
		  	</div>
			<div class="modal-footer">
				<?php 

				echo $this->Html->link(__d('user_managment', 'Annuler'), '#', array('class' => 'btn btn-danger', 'data-dismiss' => 'modal')); 
 
				?>				<?php 

				echo $this->Form->button(__d('user_managment', 'Sauvegarder'), array('class' => 'btn btn-primary'));

				?>			</div>
		</div><!-- /.modal-content -->
  	</div><!-- /.modal-dialog -->
<?php echo $this->Form->end(); ?></div><!-- /.modal -->

<div class="modal fade" id="UserEditDialog" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="UserEdition" backdrop = "static">
	
	<?php  echo $this->Form->create('User',
			array('url' => array('controller' => 'users', 'action' => 'edit', 'ext' => 'json'), 

				'id' => 'edit_user_form', 'type' => 'file')

			);?> 

	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">
						<?php  echo __d('user_managment', 'Close');  ?>					</span>
				</button>

				<h4 class="modal-title" style = "display:inline-block">
					<?php  echo __d('user_managment', "Edition d'un utilisateur");  ?>				
				</h4>
	  		</div>
			<div class="modal-body">

			<?php
				echo $this->Form->input('id');
				$this->Form->inputDefaults(array('label' => false, 'class' => 'span10'));
				echo $this->Form->input('role_id', array(
					'label' => __d('user_managment', 'Rôle:'),
					'id' => 'EditUserRoleId',
					'required' => true
				));
				echo $this->Form->input('service_id', array(
					'label' => __d('user_managment', 'Service:'),
					'id' => 'EditUserServiceId',
					'required' => true
				));
				echo $this->Form->input('first_name', array(
					'label' => __d('user_managment', 'Prénom:'),
					'id' => 'EditUserFirstName',
					'required' => true
				));
				echo $this->Form->input('last_name', array(
					'label' => __d('user_managment', 'Nom:'),
					'id' => 'EditUserLastName',
					'required' => true
				));
				echo $this->Form->input('username', array(
					'label' => __d('user_managment', 'Login:'),
					'id' => 'EditUserUsername',
					'required' => true
				));

				echo $this->Form->input('email', array(
					'label' => __d('user_managment', 'Email:'),
					'id' => 'EditUserEmail',
					'required' => true
				));
			?>
				<label for="EditUserImage" style = "padding-left: 10px;">
					<?php echo __d('user_managment', 'Image:'); ?>
				</label>
				<img src = "/capmaroc/theme/CapAdmin/img/default-user.png" style= "max-width:50px" class="img-rounded" id = "editUserPreview">
			<?php
				echo $this->Form->input('image', array(
					'label' => false,
					'type' => 'file',
					'id' => 'EditUserImage',
					'required' => false,
					'accept' => 'image/*'
				));
			?>
			<div class="input text checkbox">
				<label for="EditUserStatus" style = "padding-left: 0px;">
					<?php echo __d('user_managment', 'Statut:'); ?>
				</label>
				<?php 
				echo $this->Form->input('status', array(
					'id' => 'EditUserStatus',
					'div' => false,
					'label' => false,
					'type' => 'checkbox'
				));
				?>
			</div>
			</div>
	  		<div class="loader"  data-initialize="loader">
	  			<?php echo $this->Html->image("loading-spinner-grey.gif"); ?><span>&nbsp;&nbsp;Operation en cours...</span>
	  		</div>
			<div class="modal-footer">
				<?php 

				echo $this->Html->link(__d('user_managment', 'Annuler'), '#', array('class' => 'btn btn-danger', 'data-dismiss' => 'modal')); 
 
				?>				<?php 

				echo $this->Form->button(__d('user_managment', 'Sauvegarder'), array('class' => 'btn btn-primary'));

				?>			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
<?php echo $this->Form->end(); ?></div><!-- /.modal -->
<style>
#UserEditDialog div.file{display: inline-block;}
</style>