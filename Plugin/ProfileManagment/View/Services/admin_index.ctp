<?php
$this->viewVars['title_for_layout'] = __d('profile_managment', 'Services');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('profile_managment', 'Services'), array('action' => 'index'));
?>

<script>

<?php  $this->Html->scriptStart(array('inline' => false, 'block' => 'scriptBottom')); ?>
var serviceCrud = {
		datagrid : {},
		init : function(){
		     serviceCrud.datagrid = $('#service_datagrid').DataTable({
		        "processing": true,
		        "serverSide": true,
		        "language": {
					"lengthMenu": "_MENU_ Enregistrements par page",
					"processing": '<?php echo $this->Html->image("loading-spinner-grey.gif"); ?><span>&nbsp;&nbsp;Loading...</span>',
					"sInfo": "",
					"sInfoEmpty": "",
					"zeroRecords" : 'aucun enregistrement trouvé' 
				},
		        "ajax": {
		        	url : '<?php echo $this->Html->url(array('action' => 'get_datagrid_data', 'ext' => 'json')); ?>',
		        	type: "POST",
		 			data : function ( d ) {
					  	var value = $('#ServiceFilter').find('input[type = search]').val();
					  	var column = $('#ServiceFilter').find('.hidden').val();
					  	
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
						title: '<?php echo __d('profile_managment', 'Id'); ?>',
						data: 'Service.id',
						sortable: true,
						visible : false
					},
					{
						title: '<?php echo __d('profile_managment', 'Nom'); ?>',
						data: 'Service.name',
						sortable: true
					},
					{
						title: '<?php echo __d('profile_managment', 'Sigle'); ?>',
						data: 'Service.abreviation',
						sortable: true
					},
					{
					title:  '<?php echo __d('request_managment', 'Actions'); ?>',
					data: null,
					sortable: false
				}],
				"columnDefs": [{
					"targets": [3],
					"width" : "230px",
					render: function (e, type, data, meta)
					{	
						var actions = [{
							'value': 'Détail',
							'attr': {
								'icon': 'folder-open-o',
								'class': "btn btn-xs btn-primary btn-open",
								'action-id': data.Service.id
							}
						}];

						actions.push({
							'value': 'Modifier',
							'attr': {
								'icon': 'pencil',
								'class': "btn btn-xs btn-primary btn-edit",
								'action-id': data.Service.id
							}
						});	

						actions.push({
							'value': 'Supprimer',
							'attr': {
								'icon': 'remove',
								'class': "btn btn-xs btn-danger btn-delete",
								'action-id': data.Service.id
							}
						});	
						return createButtonGroup(actions);
					}
				}],
		    });			
		},
		showDetail : function (elm) {
	        var tr = $(elm).closest('tr');
	        var row = serviceCrud.datagrid.row( tr );
	 
	        if ( row.child.isShown() ) {
	            // This row is already open - close it
	            row.child.hide();
	            tr.removeClass('shown');
	        }
	        else {
	            // Open this row
	            row.child( serviceCrud.detail(row.data()) ).show();
	            tr.addClass('shown');
	        }
	    },
		detail : function(d){

		    var detail =  '<table id = "service_row_detail" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
						
				'<tr>'+
				'<td><?php echo __d('profile_managment', 'Id'); ?> : </td>'+
					'<td>'+d.Service.id+'</td>'+
				'</tr>'+			
				'<tr>'+
				'<td><?php echo __d('profile_managment', 'Name'); ?> : </td>'+
					'<td>'+d.Service.name+'</td>'+
				'</tr>';
				if(d.Service.logo)
				{
					detail += '<tr>'+
					'<td><?php echo __d('profile_managment', 'Logo'); ?> : </td>'+
						'<td><img src = "'+Croogo.basePath+'uploads/services/'+d.Service.logo+'" style= "max-width:200px"></td>'+
					'</tr>';
				}

				detail +='</table>';
				return detail;
		},
		addRow : function(postData){
			var formURL = $('#add_service_form').attr("action");
			$('#service_datagrid').trigger('dialogLoader', 'show');
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
						serviceCrud.datagrid.row.add(response.record).draw();
						toastr.success(response.message);
						$('#add_service_form').find('input, select').val('');
						$('#ServiceAddDialog').modal('hide'); 
					}
					else
					{
						toastr.error(response.message);
						$.each(response.errors, function(field, errors){
							var id = $('#ServiceAddDialog').find('[name = "data[Service]['+field+']"]').attr('id');
							control = document.getElementById(id);
							control.setCustomValidity(errors[0]);
							$('#'+id).on('change', function(){
								control.setCustomValidity('');
							})
						});
					}
					$('#service_datagrid').trigger('dialogLoader', 'hide');
					
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					$('#service_datagrid').trigger('dialogLoader', 'hide');
					toastr.error("<?php echo __d('profile_managment', 'An error occured please try again!'); ?>");
				}
			});
			
		},
		deleteRow : function(id, tr){

			$('#service_datagrid').trigger('loader', 'show');
			$.ajax(
			{
				url : '<?php echo Router::url(array('action' => 'delete', 'ext' => 'json')); ?>',
				type: "POST",
				data : {id : id},
				success:function(response, textStatus, jqXHR) 
				{
					if(response.result == 'success')
					{
						serviceCrud.datagrid.row(tr).remove().draw( false );
						toastr.success(response.message);
					}
					else
					{
						toastr.error(response.message); 
					}
					$('#service_datagrid').trigger('loader', 'hide');
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					$('#service_datagrid').trigger('loader', 'hide');
					toastr.error("<?php echo __d('profile_managment', 'An error occured please try again!'); ?>");
				}
			});
		},
		updateRow : function(postData){
			var formURL = $('#edit_service_form').attr("action");
			$('#service_datagrid').trigger('dialogLoader', 'show');

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
					var tr = $('[action-id = '+response.record.Service.id+']').closest('tr'); 

					if(response.result == 'success')
					{
						serviceCrud.datagrid.row(tr).data( response.record ).draw();
						toastr.success(response.message);
					}
					else
					{
						toastr.error(response.message);
						$.each(response.errors, function(field, errors){
							var id = $('#ServiceEditDialog').find('[name = "data[Service]['+field+']"]').attr('id');
							control = document.getElementById(id);
							control.setCustomValidity(errors[0]);
							$('#'+id).on('change', function(){
								control.setCustomValidity('');
							})
						});
					}
					 $('#service_datagrid').trigger('dialogLoader', 'hide'); 
					$('#ServiceEditDialog').modal('hide'); 
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
	  				$('#service_datagrid').trigger('dialogLoader', 'hide');
	  				toastr.error("<?php echo __d('profile_managment', 'An error occured please try again!'); ?>");
				}
			});
		}
	}

	jQuery(document).ready(function() {
		serviceCrud.init();

	 	$('#service_datagrid tbody').on('click', '.btn-open', function(){
	 		serviceCrud.showDetail(this)
	 	});

		//datagrid ajax form 
		$('.services').on('click', '.btn-delete', function(e)
		{
			var id = $(this).attr("action-id");
			var tr = $(this).closest("tr");
			
			if(confirm("<?php echo __d('profile_managment', 'Are you sure'); ?>")){
				serviceCrud.deleteRow(id, tr);
			}
			
			e.preventDefault();

			return false;
		});

		//datagrid ajax add form 
		$('#add_service_form').submit(function(e)
		{
			var $form = $(this);
			var formdata = (window.FormData) ? new FormData($form[0]) : null;
			var data = (formdata !== null) ? formdata : $form.serialize();
			serviceCrud.addRow(data);
			e.preventDefault();

			return false;
		});

		//datagrid ajax edit form 
		$('#edit_service_form').submit(function(e)
		{
			var $form = $(this);
			
			var formdata = (window.FormData) ? new FormData($form[0]) : null;
			var data = (formdata !== null) ? formdata : $form.serialize();
			serviceCrud.updateRow(data);
			e.preventDefault();
			return false;
		});

		$(document).on('click', '.btn-edit', function(event){
			$('#edit_service_form').find('input').val('');
			var data = serviceCrud.datagrid.row($(this).closest('tr')).data();
			$('#editLogoPreview').attr('src', "/capmaroc/theme/CapAdmin/img/default-user.png");
			$('#edit_service_form input,#edit_service_form select').each(function(){
				
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
							 $('#editLogoPreview').attr('src', Croogo.basePath+'uploads/services/'+data[keys[0]][keys[1]]);
						}
						else
						{
							$(this).val(data[keys[0]][keys[1]]);
						}
						
					}
				}
			});

			$('#ServiceEditDialog').modal('show');
			
			event.preventDefault();
			return false;
		});

		$('#ServiceFilter').on('click', 'a', function (e) {
		  	var field_name =  $(this).parent().attr('data-value')
		  	var field_label = $(this).text();
		  	$(this).closest('.datagrid-search').find('.hidden').val(field_name);
		  	$(this).closest('.datagrid-search').find('.selected-label').text(' ' +field_label);
		  	$(this).closest('.datagrid-search').find('input[type = search]').val("");
		  	$(this).closest('.datagrid-search').find('input[type = search]').attr('placeholder', 'Chercher par '+field_label);
		});

		$('#ServiceFilter .search').on('click', '.btn', function (e) {
		  	serviceCrud.datagrid.ajax.reload();
		});

		$('#ServiceEditDialog').on('hidden.bs.modal', function (e) {
		  	$('#edit_service_form').clearForm();
		});

		$('#ServiceAddDialog').on('hidden.bs.modal', function (e) {
		  	$('#add_service_form').clearForm();
		});

		$("#EditServiceLogo").change(function(event){
		    if (this.files && this.files[0]) {
		        var reader = new FileReader();

		        reader.onload = function (e) {
		            $('#editLogoPreview').attr('src', e.target.result);
		        }

		        reader.readAsDataURL(this.files[0]);
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

<div class="services index">
	<div class="datagrid" id="service_datagrid_container">
		<div class="datagrid-toolbar">
			<div class="col-xs-12 col-sm-6 col-md-8 no-padding">
				<!-- Button trigger modal -->
				<?php  echo $this->Croogo->adminAction(

						__d('profile_managment', 'Nouveau Service'), '#',

						array('button' => 'primary', 'data-toggle' => 'modal', 'data-target' =>'#ServiceAddDialog')

					);?>			</div>
			<div class="col-xs-6 col-md-4 no-padding">
			  	<div class="datagrid-search" id = "ServiceFilter">
					<div class="input-group">
						<div class="input-group-btn selectlist" data-resize="auto" data-initialize="selectlist">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
								<span class="selected-label">Nom</span>
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
					 		</button>
							<ul class="dropdown-menu" role="menu">											
								<li data-value="Service.name">	
									<a href="#">Nom</a>
								</li>												
							</ul>
							<input class="hidden hidden-field" name="column" readonly="readonly" aria-hidden="true" type="text" value = "Service.id">
						</div>
						<div class="search input-group">
							<input type="search" class="form-control" placeholder="<?php  echo __d('profile_managment', 'Chercher par Nom');  ?>"/>
						  	<span class="input-group-btn">
								<button class="btn btn-default" type="button">
							  		<span class="glyphicon glyphicon-search"></span>
							  		<span class="sr-only">
							  		<?php  echo __d('profile_managment', 'Chercher');  ?>							 		</span>
								</button>
						  	</span>
						</div>
					</div>
			  	</div>
			</div>
			<div class = "clear"></div>
	  	</div>
		<table id="service_datagrid" class="display table-bordered"></table>
	</div>
</div>

<div class="modal fade" id="ServiceAddDialog" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="ServiceEdition" data-backdrop = "static">
 
	<?php  echo $this->Form->create('Service',
			array('url' => array('action' => 'add', 'ext' => 'json'), 

				'id' => 'add_service_form', 'type' => 'file')

			);?>	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">
						<?php  echo __d('profile_managment', 'Close');  ?>
					</span>
				</button>
				<h4 class="modal-title">
					<?php  echo __d('profile_managment', 'Ajouter un nouveau Service');  ?>				</h4>
			</div>

			<div class="modal-body">
			<?php
				$this->Form->inputDefaults(array('label' => false, 'class' => 'span10'));
				echo $this->Form->input('name', array(
					'label' => __d('profile_managment', 'Nom:'),
					'id' => 'AddServiceName'
				));
				echo $this->Form->input('abreviation', array(
					'label' => __d('profile_managment', 'Sigle:'),
					'id' => 'AddServiceAbreviation'
				));
				echo $this->Form->input('logo', array(
					'label' => __d('user_managment', 'Logo:'),
					'type' => 'file',
					'id' => 'AddServiceLogo',
					'required' => false,
					'accept' => 'image/*'
				));
			?>
			</div>
		  	<div class="loader" data-initialize="loader"></div>
			<div class="modal-footer">
				<?php 

				echo $this->Html->link(__d('profile_managment', 'Annuler'), '#', array('class' => 'btn btn-danger', 'data-dismiss' => 'modal')); 
 
				?>				<?php 

				echo $this->Form->button(__d('profile_managment', 'Sauvegarder'), array('class' => 'btn btn-primary'));

				?>			</div>
		</div><!-- /.modal-content -->
  	</div><!-- /.modal-dialog -->
<?php echo $this->Form->end(); ?></div><!-- /.modal -->

<div class="modal fade" id="ServiceEditDialog" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="ServiceEdition" backdrop = "static">
	
	<?php  echo $this->Form->create('Service',
			array('url' => array('action' => 'edit', 'ext' => 'json'), 

				'id' => 'edit_service_form', 'type' => 'file')

			);?> 

	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">
						<?php  echo __d('profile_managment', 'Close');  ?>					</span>
				</button>
				<h4 class="modal-title">
					<?php  echo __d('profile_managment', 'Modification de service');  ?>				</h4>
	  		</div>
			<div class="modal-body">
			<?php
				echo $this->Form->input('id');
				$this->Form->inputDefaults(array('label' => false, 'class' => 'span10'));
				echo $this->Form->input('name', array(
					'label' => __d('profile_managment', 'Nom'),
					'id' => 'EditServiceName'
				));
				echo $this->Form->input('abreviation', array(
					'label' => __d('profile_managment', 'Sigle'),
					'id' => 'EditServiceAbreviation'
				));
			?>
			<label for="EditServiceLogo" style = "padding-left: 10px;"><?php echo __d('user_managment', 'Logo'); ?></label>
			<img src = "/capmaroc/theme/CapAdmin/img/default-user.png" style= "max-width:50px" class="img-rounded" id = "editLogoPreview">
			<?php
				echo $this->Form->input('logo', array(
					'label' => false,
					'type' => 'file',
					'id' => 'EditServiceLogo',
					'required' => false,
					'accept' => 'image/*'
				));
			?>
			</div>
	  		<div class="loader"  data-initialize="loader"></div>
			<div class="modal-footer">
				<?php 

				echo $this->Html->link(__d('profile_managment', 'Annuler'), '#', array('class' => 'btn btn-danger', 'data-dismiss' => 'modal')); 
 
				?>				<?php 

				echo $this->Form->button(__d('profile_managment', 'Sauvegarder'), array('class' => 'btn btn-primary'));

				?>			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
<?php echo $this->Form->end(); ?></div><!-- /.modal -->
<style>
#ServiceEditDialog div.file{display: inline-block;}
</style>