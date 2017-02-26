<?php
$this->viewVars['title_for_layout'] = __d('profile_managment', 'Specialities');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('profile_managment', 'Specialités'), array('action' => 'index'));
?>

<script>

<?php  $this->Html->scriptStart(array('inline' => false, 'block' => 'scriptBottom')); ?>
var specialityCrud = {
		datagrid : {},
		init : function(){
		     specialityCrud.datagrid = $('#speciality_datagrid').DataTable({
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
					  	var value = $('#SpecialityFilter').find('input[type = search]').val();
					  	var column = $('#SpecialityFilter').find('.hidden').val();
					  	
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
						title: '<?php echo __d('profile_managment', 'Identifiant'); ?>',
						data: 'Speciality.id',
						sortable: true,
					},
					{
						title: '<?php echo __d('profile_managment', 'Titre'); ?>',
						data: 'Speciality.name',
						sortable: true
					},
					{
						title: '<?php echo __d('profile_managment', 'Specialité agrée'); ?>',
						data: 'OfficialSpeciality.name',
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
								'action-id': data.Speciality.id
							}
						}];

						actions.push({
							'value': 'Modifier',
							'attr': {
								'icon': 'pencil',
								'class': "btn btn-xs btn-primary btn-edit",
								'action-id': data.Speciality.id
							}
						});	

						actions.push({
							'value': 'Supprimer',
							'attr': {
								'icon': 'remove',
								'class': "btn btn-xs btn-danger btn-delete",
								'action-id': data.Speciality.id
							}
						});	
						return createButtonGroup(actions);
					}
				}],
		    });			
		},
		showDetail : function (elm) {
	        var tr = $(elm).closest('tr');
	        var row = specialityCrud.datagrid.row( tr );
	 
	        if ( row.child.isShown() ) {
	            // This row is already open - close it
	            row.child.hide();
	            tr.removeClass('shown');
	        }
	        else {
	            // Open this row
	            row.child( specialityCrud.detail(row.data()) ).show();
	            tr.addClass('shown');
	        }
	    },
		detail : function(d){

		    return '<table id = "speciality_row_detail" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
						
				'<tr>'+
				'<td><?php echo __d('profile_managment', 'Identifiant'); ?></td>'+
					'<td>'+d.Speciality.id+'</td>'+
				'</tr>'+			
				'<tr>'+
				'<td><?php echo __d('profile_managment', 'Titre'); ?></td>'+
					'<td>'+d.Speciality.name+'</td>'+
				'</tr>'+
				'</table>';	
		},
		addRow : function(postData){
			var formURL = $('#add_speciality_form').attr("action");
			$('#speciality_datagrid').trigger('dialogLoader', 'show');
			$.ajax(
			{
				url : formURL,
				type: "POST",
				data : postData,
				success:function(response, textStatus, jqXHR) 
				{
					if(response.result == 'success')
					{
						specialityCrud.datagrid.row.add(response.record).draw();
						toastr.success(response.message);
						$('#add_speciality_form').find('input, select').val('');
					}
					else
					{
						toastr.error(response.message); 
					}
					$('#speciality_datagrid').trigger('dialogLoader', 'hide');
					$('#SpecialityAddDialog').modal('hide'); 
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					$('#speciality_datagrid').trigger('dialogLoader', 'hide');
					toastr.error("<?php echo __d('profile_managment', 'An error occured please try again!'); ?>");
				}
			});
			
		},
		deleteRow : function(id, tr){

			$('#speciality_datagrid').trigger('loader', 'show');
			$.ajax(
			{
				url : '<?php  echo Router::url(array('action' => 'delete', 'ext' => 'json'));?>',
				type: "POST",
				data : {id : id},
				success:function(response, textStatus, jqXHR) 
				{
					if(response.result == 'success')
					{
						specialityCrud.datagrid.row(tr).remove().draw( false );
						toastr.success(response.message);
					}
					else
					{
						toastr.error(response.message); 
					}
					$('#speciality_datagrid').trigger('loader', 'hide');
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					$('#speciality_datagrid').trigger('loader', 'hide');
					toastr.error("<?php echo __d('profile_managment', 'An error occured please try again!'); ?>");
				}
			});
		},
		updateRow : function(data){
			var formURL = $('#edit_speciality_form').attr("action");
			$('#speciality_datagrid').trigger('dialogLoader', 'show')
			$.ajax(
			{
				url : formURL,
				type: "POST",
				data : data,
				success:function(response, textStatus, jqXHR) 
				{
					var tr = $('[action-id = '+response.record.Speciality.id+']').closest('tr'); 
					if(response.result == 'success')
					{
						specialityCrud.datagrid.row(tr).data( response.record ).draw();
						toastr.success(response.message);
					}
					else
					{
						toastr.error(response.message); 
					}
					 $('#speciality_datagrid').trigger('dialogLoader', 'hide'); 
					$('#SpecialityEditDialog').modal('hide'); 
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
	  				$('#speciality_datagrid').trigger('dialogLoader', 'hide');
	  				toastr.error("<?php echo __d('profile_managment', 'An error occured please try again!'); ?>");
				}
			});
		}
	}

	jQuery(document).ready(function() {
		specialityCrud.init();

	 	$('#speciality_datagrid tbody').on('click', '.btn-open', function(){
	 		specialityCrud.showDetail(this)
	 	});

		//datagrid ajax form 
		$('.specialities').on('click', '.btn-delete', function(e)
		{
			var id = $(this).attr("action-id");
			var tr = $(this).closest("tr");
			
			if(confirm("<?php echo __d('profile_managment', 'Are you sure'); ?>")){
				specialityCrud.deleteRow(id, tr);
			}
			
			e.preventDefault();

			return false;
		});

		//datagrid ajax add form 
		$('#add_speciality_form').submit(function(e)
		{
			var postData = $(this).serializeArray();
			specialityCrud.addRow(postData);
			e.preventDefault();

			return false;
		});

		//datagrid ajax edit form 
		$('#edit_speciality_form').submit(function(e)
		{
			var postData = $(this).serializeArray();
			specialityCrud.updateRow(postData);
			e.preventDefault();

			return false;
		});

		$(document).on('click', '.btn-edit', function(event){
			$('#edit_speciality_form').find('input, select').val('');
			var data = specialityCrud.datagrid.row($(this).closest('tr')).data();
			console.log(data);
			$('#edit_speciality_form input, #edit_speciality_form select').each(function(){
				
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

			$('#SpecialityEditDialog').modal('show');
			
			event.preventDefault();
			return false;
		});

		$('#SpecialityFilter').on('click', 'a', function (e) {
		  	var field_name =  $(this).parent().attr('data-value')
		  	var field_label = $(this).text();
		  	$(this).closest('.datagrid-search').find('.hidden').val(field_name);
		  	$(this).closest('.datagrid-search').find('.selected-label').text(' ' +field_label);
		  	$(this).closest('.datagrid-search').find('input[type = search]').val("");
		  	$(this).closest('.datagrid-search').find('input[type = search]').attr('placeholder', 'Chercher par '+field_label);
		});

		$('#SpecialityFilter .search').on('click', '.btn', function (e) {
		  	specialityCrud.datagrid.ajax.reload();
		});

		$('#SpecialityEditDialog').on('hidden.bs.modal', function (e) {
		  	$('#edit_speciality_form').clearForm();
		});

		$('#SpecialityAddDialog').on('hidden.bs.modal', function (e) {
		  	$('#add_speciality_form').clearForm();
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

<div class="specialities index">
	<div class="datagrid" id="speciality_datagrid_container">
		<div class="datagrid-toolbar">
			<div class="col-xs-12 col-sm-6 col-md-8 no-padding">
				<!-- Button trigger modal -->
				<?php  echo $this->Croogo->adminAction(

						__d('profile_managment', 'Nouvelle Specialité'), '#',

						array('button' => 'primary', 'data-toggle' => 'modal', 'data-target' =>'#SpecialityAddDialog')

					);?>			</div>
			<div class="col-xs-6 col-md-4 no-padding">
			  	<div class="datagrid-search" id = "SpecialityFilter">
					<div class="input-group">
						<div class="input-group-btn selectlist" data-resize="auto" data-initialize="selectlist">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
								<span class="selected-label">Identifiant</span>
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
					 		</button>
							<ul class="dropdown-menu" role="menu">
												
								<li data-value="Speciality.id">	
									<a href="#">Identifiant</a>
								</li>											
								<li data-value="Speciality.name">	
									<a href="#">Titre</a>
								</li>
								<li data-value="OfficialSpeciality.name">	
									<a href="#">Spécialité agrée</a>
								</li>											
							</ul>
							<input class="hidden hidden-field" name="column" readonly="readonly" aria-hidden="true" type="text" value = "Speciality.id">
						</div>
						<div class="search input-group">
							<input type="search" class="form-control" placeholder="<?php  echo __d('profile_managment', 'Chercher par Identifiant');  ?>"/>
						  	<span class="input-group-btn">
								<button class="btn btn-default" type="button">
							  		<span class="glyphicon glyphicon-search"></span>
							  		<span class="sr-only">
							  		<?php  echo __d('profile_managment', 'Chercher');  ?>
							  	</span>
								</button>
						  	</span>
						</div>
					</div>
			  	</div>
			</div>
			<div class = "clear"></div>
	  	</div>
		<table id="speciality_datagrid" class="display table-bordered"></table>
	</div>
</div>

<div class="modal fade" id="SpecialityAddDialog" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="SpecialityEdition" data-backdrop = "static">
 
	<?php  echo $this->Form->create('Speciality',
			array('url' => array('action' => 'add', 'ext' => 'json'), 

				'id' => 'add_speciality_form')

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
					<?php  echo __d('profile_managment', 'Ajouter une specialité');  ?>				</h4>
			</div>
			<div class="modal-body">
			<?php
				$this->Form->inputDefaults(array('label' => false, 'class' => 'span10'));
				echo $this->Form->input('name', array(
					'label' => __d('profile_managment', 'Titre'),
					'id' => 'AddSpecialityName'
				));
				echo $this->Form->input('official_speciality_id', array(
					'label' => __d('profile_managment', 'Spécialité agrée'),
					'id' => 'AddSpecialityOfficial',
					'options' => $official_specialities
				));
			?>
			</div>
		  	<div class="loader" data-initialize="loader"></div>
			<div class="modal-footer">
				<?php 

				echo $this->Html->link(__d('profile_managment', 'Annuler'), '#', array('class' => 'btn btn-danger', 'data-dismiss' => 'modal')); 
 
				?>				<?php 

				echo $this->Form->button(__d('profile_managment', 'Sauvegrader'), array('class' => 'btn btn-primary'));

				?>			</div>
		</div><!-- /.modal-content -->
  	</div><!-- /.modal-dialog -->
<?php echo $this->Form->end(); ?></div><!-- /.modal -->

<div class="modal fade" id="SpecialityEditDialog" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="SpecialityEdition" backdrop = "static">
	
	<?php  echo $this->Form->create('Speciality',
			array('url' => array('action' => 'edit', 'ext' => 'json'), 

				'id' => 'edit_speciality_form')

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
					<?php  echo __d('profile_managment', 'Modifier la specialité');  ?>				</h4>
	  		</div>
			<div class="modal-body">
			<?php
				echo $this->Form->input('id');
				$this->Form->inputDefaults(array('label' => false, 'class' => 'span10'));
				echo $this->Form->input('name', array(
					'label' => __d('profile_managment', 'Titre'),
					'id' => 'EditSpecialityName'
				));
				echo $this->Form->input('official_speciality_id', array(
					'label' => __d('profile_managment', 'Spécialité agrée'),
					'id' => 'AddSpecialityOfficial',
					'options' => $official_specialities
				));
			?>
			</div>
	  		<div class="loader"  data-initialize="loader"></div>
			<div class="modal-footer">
				<?php 

				echo $this->Html->link(__d('profile_managment', 'Annuler'), '#', array('class' => 'btn btn-danger', 'data-dismiss' => 'modal')); 
 
				?>				<?php 

				echo $this->Form->button(__d('profile_managment', 'Sauvegrader'), array('class' => 'btn btn-primary'));

				?>			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
<?php echo $this->Form->end(); ?></div><!-- /.modal -->