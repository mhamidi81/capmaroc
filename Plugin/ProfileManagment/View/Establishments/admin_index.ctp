<?php
$this->viewVars['title_for_layout'] = __d('profile_managment', 'Etablissements');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('profile_managment', 'Etablissements'), array('action' => 'index'));
?>

<script>

<?php  $this->Html->scriptStart(array('inline' => false, 'block' => 'scriptBottom')); ?>
var establishmentCrud = {
		datagrid : {},
		init : function(){
		     establishmentCrud.datagrid = $('#establishment_datagrid').DataTable({
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
					  	var value = $('#EstablishmentFilter').find('input[type = search]').val();
					  	var column = $('#EstablishmentFilter').find('.hidden').val();
					  	
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
						data: 'Establishment.id',
						sortable: true,
						visible : false
					},
					{
						title: '<?php echo __d('profile_managment', 'Nom'); ?>',
						data: 'Establishment.name',
						sortable: true
					},
					{
						title: '<?php echo __d('profile_managment', 'Sigle'); ?>',
						data: 'Establishment.abreviation',
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
								'action-id': data.Establishment.id
							}
						}];

						actions.push({
							'value': 'Modifier',
							'attr': {
								'icon': 'pencil',
								'class': "btn btn-xs btn-primary btn-edit",
								'action-id': data.Establishment.id
							}
						});	

						actions.push({
							'value': 'Supprimer',
							'attr': {
								'icon': 'remove',
								'class': "btn btn-xs btn-danger btn-delete",
								'action-id': data.Establishment.id
							}
						});	
						return createButtonGroup(actions);
					}
				}],
		    });			
		},
		showDetail : function (elm) {
	        var tr = $(elm).closest('tr');
	        var row = establishmentCrud.datagrid.row( tr );
	 
	        if ( row.child.isShown() ) {
	            // This row is already open - close it
	            row.child.hide();
	            tr.removeClass('shown');
	        }
	        else {
	            // Open this row
	            row.child( establishmentCrud.detail(row.data()) ).show();
	            tr.addClass('shown');
	        }
	    },
		detail : function(d){

		    var detail =  '<table id = "establishment_row_detail" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
						
				'<tr>'+
				'<td><?php echo __d('profile_managment', 'Id'); ?> : </td>'+
					'<td>'+d.Establishment.id+'</td>'+
				'</tr>'+			
				'<tr>'+
				'<td><?php echo __d('profile_managment', 'Name'); ?> : </td>'+
					'<td>'+d.Establishment.name+'</td>'+
				'</tr>';
				if(d.Establishment.logo)
				{
					detail += '<tr>'+
					'<td><?php echo __d('profile_managment', 'Logo'); ?> : </td>'+
						'<td><img src = "'+Croogo.basePath+'uploads/establishments/'+d.Establishment.logo+'" style= "max-width:200px"></td>'+
					'</tr>';
				}

				detail +='</table>';
				return detail;
		},
		addRow : function(postData){
			var formURL = $('#add_establishment_form').attr("action");
			$('#establishment_datagrid').trigger('dialogLoader', 'show');
			$.ajax(
			{
				url : formURL,
				type: "POST",
				data : postData,
				success:function(response, textStatus, jqXHR) 
				{
					if(response.result == 'success')
					{
						establishmentCrud.datagrid.row.add(response.record).draw();
						toastr.success(response.message);
						$('#add_establishment_form').find('input, select').val('');
						$('#EstablishmentAddDialog').modal('hide'); 
					}
					else
					{
						toastr.error(response.message);
						$.each(response.errors, function(field, errors){
							var id = $('#EstablishmentAddDialog').find('[name = "data[Establishment]['+field+']"]').attr('id');
							control = document.getElementById(id);
							control.setCustomValidity(errors[0]);
							$('#'+id).on('change', function(){
								control.setCustomValidity('');
							})
						});
					}
					$('#establishment_datagrid').trigger('dialogLoader', 'hide');
					
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					$('#establishment_datagrid').trigger('dialogLoader', 'hide');
					toastr.error("<?php echo __d('profile_managment', 'An error occured please try again!'); ?>");
				}
			});
			
		},
		deleteRow : function(id, tr){

			$('#establishment_datagrid').trigger('loader', 'show');
			$.ajax(
			{
				url : '<?php echo Router::url(array('action' => 'delete', 'ext' => 'json')); ?>',
				type: "POST",
				data : {id : id},
				success:function(response, textStatus, jqXHR) 
				{
					if(response.result == 'success')
					{
						establishmentCrud.datagrid.row(tr).remove().draw( false );
						toastr.success(response.message);
					}
					else
					{
						toastr.error(response.message); 
					}
					$('#establishment_datagrid').trigger('loader', 'hide');
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					$('#establishment_datagrid').trigger('loader', 'hide');
					toastr.error("<?php echo __d('profile_managment', 'An error occured please try again!'); ?>");
				}
			});
		},
		updateRow : function(data){
			var formURL = $('#edit_establishment_form').attr("action");
			$('#establishment_datagrid').trigger('dialogLoader', 'show')
			$.ajax(
			{
				url : formURL,
				type: "POST",
				data : data,
				success:function(response, textStatus, jqXHR) 
				{
					var tr = $('[action-id = '+response.record.Establishment.id+']').closest('tr'); 
					if(response.result == 'success')
					{
						establishmentCrud.datagrid.row(tr).data( response.record ).draw();
						toastr.success(response.message);
					}
					else
					{
						toastr.error(response.message);
						$.each(response.errors, function(field, errors){
							var id = $('#EstablishmentEditDialog').find('[name = "data[Establishment]['+field+']"]').attr('id');
							control = document.getElementById(id);
							control.setCustomValidity(errors[0]);
							$('#'+id).on('change', function(){
								control.setCustomValidity('');
							})
						});
					}
					 $('#establishment_datagrid').trigger('dialogLoader', 'hide'); 
					$('#EstablishmentEditDialog').modal('hide'); 
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
	  				$('#establishment_datagrid').trigger('dialogLoader', 'hide');
	  				toastr.error("<?php echo __d('profile_managment', 'An error occured please try again!'); ?>");
				}
			});
		}
	}

	jQuery(document).ready(function() {
		establishmentCrud.init();

	 	$('#establishment_datagrid tbody').on('click', '.btn-open', function(){
	 		establishmentCrud.showDetail(this)
	 	});

		//datagrid ajax form 
		$('.establishments').on('click', '.btn-delete', function(e)
		{
			var id = $(this).attr("action-id");
			var tr = $(this).closest("tr");
			
			if(confirm("<?php echo __d('profile_managment', 'Are you sure'); ?>")){
				establishmentCrud.deleteRow(id, tr);
			}
			
			e.preventDefault();

			return false;
		});

		//datagrid ajax add form 
		$('#add_establishment_form').submit(function(e)
		{
			var $form = $(this);
			var data = $form.serialize();
			establishmentCrud.addRow(data);
			e.preventDefault();

			return false;
		});

		//datagrid ajax edit form 
		$('#edit_establishment_form').submit(function(e)
		{
			var $form = $(this);
			var data = $form.serialize();
			establishmentCrud.updateRow(data);
			e.preventDefault();

			return false;
		});

		$(document).on('click', '.btn-edit', function(event){
			$('#edit_establishment_form').find('input').val('');
			var data = establishmentCrud.datagrid.row($(this).closest('tr')).data();
			
			$('#edit_establishment_form input[type=text],#edit_establishment_form input[type=hidden]').each(function(){
				
				if($(this).attr('id'))
				{	
					regex = /\[([^\]]*)]/g;
					keys = [];
					
					while (m = regex.exec($(this).attr('name'))) {
					  keys.push(m[1]);
					}

					if(data.hasOwnProperty(keys[0]) && data[keys[0]][keys[1]]){
						$(this).val(data[keys[0]][keys[1]]);
					}
				}
			});

			$('#EstablishmentEditDialog').modal('show');
			
			event.preventDefault();
			return false;
		});

		$('#EstablishmentFilter').on('click', 'a', function (e) {
		  	var field_name =  $(this).parent().attr('data-value')
		  	var field_label = $(this).text();
		  	$(this).closest('.datagrid-search').find('.hidden').val(field_name);
		  	$(this).closest('.datagrid-search').find('.selected-label').text(' ' +field_label);
		  	$(this).closest('.datagrid-search').find('input[type = search]').val("");
		  	$(this).closest('.datagrid-search').find('input[type = search]').attr('placeholder', 'Chercher par '+field_label);
		});

		$('#EstablishmentFilter .search').on('click', '.btn', function (e) {
		  	establishmentCrud.datagrid.ajax.reload();
		});

		$('#EstablishmentEditDialog').on('hidden.bs.modal', function (e) {
		  	$('#edit_establishment_form').clearForm();
		});

		$('#EstablishmentAddDialog').on('hidden.bs.modal', function (e) {
		  	$('#add_establishment_form').clearForm();
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

<div class="establishments index">
	<div class="datagrid" id="establishment_datagrid_container">
		<div class="datagrid-toolbar">
			<div class="col-xs-12 col-sm-6 col-md-8 no-padding">
				<!-- Button trigger modal -->
				<?php  echo $this->Croogo->adminAction(

						__d('profile_managment', 'Nouveau établissement'), '#',

						array('button' => 'primary', 'data-toggle' => 'modal', 'data-target' =>'#EstablishmentAddDialog')

					);?>			</div>
			<div class="col-xs-6 col-md-4 no-padding">
			  	<div class="datagrid-search" id = "EstablishmentFilter">
					<div class="input-group">
						<div class="input-group-btn selectlist" data-resize="auto" data-initialize="selectlist">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
								<span class="selected-label">Nom</span>
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
					 		</button>
							<ul class="dropdown-menu" role="menu">											
								<li data-value="Establishment.name">	
									<a href="#">Nom</a>
								</li>												
							</ul>
							<input class="hidden hidden-field" name="column" readonly="readonly" aria-hidden="true" type="text" value = "Establishment.id">
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
		<table id="establishment_datagrid" class="display table-bordered"></table>
	</div>
</div>

<div class="modal fade" id="EstablishmentAddDialog" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="EstablishmentEdition" data-backdrop = "static">
 
	<?php  echo $this->Form->create('Establishment',
			array('url' => array('action' => 'add', 'ext' => 'json'), 

				'id' => 'add_establishment_form', 'file' => true)

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
					<?php  echo __d('profile_managment', 'Ajouter un nouveau établissement');  ?>				</h4>
			</div>

			<div class="modal-body">
			<?php
				$this->Form->inputDefaults(array('label' => false, 'class' => 'span10'));
				echo $this->Form->input('name', array(
					'label' => __d('profile_managment', 'Nom:'),
					'id' => 'AddEstablishmentName'
				));
				echo $this->Form->input('abreviation', array(
					'label' => __d('profile_managment', 'Sigle:'),
					'id' => 'AddEstablishmentAbreviation'
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

<div class="modal fade" id="EstablishmentEditDialog" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="EstablishmentEdition" backdrop = "static">
	
	<?php  echo $this->Form->create('Establishment',
			array('url' => array('action' => 'edit', 'ext' => 'json'), 

				'id' => 'edit_establishment_form', 'file' => true)

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
					<?php  echo __d('profile_managment', 'Modification de établissement');  ?>				</h4>
	  		</div>
			<div class="modal-body">
			<?php
				echo $this->Form->input('id');
				$this->Form->inputDefaults(array('label' => false, 'class' => 'span10'));
				echo $this->Form->input('name', array(
					'label' => __d('profile_managment', 'Nom'),
					'id' => 'EditEstablishmentName'
				));
				echo $this->Form->input('abreviation', array(
					'label' => __d('profile_managment', 'Sigle'),
					'id' => 'EditEstablishmentAbreviation'
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