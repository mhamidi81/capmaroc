<?php
$this->viewVars['title_for_layout'] = __d('profile_managment', "Specialitiés d'agrément");

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('profile_managment', "Specialitiés d'agrément"), array('action' => 'index'));
?>

<script>

<?php  $this->Html->scriptStart(array('inline' => false, 'block' => 'scriptBottom')); ?>
var officialSpecialityCrud = {
		datagrid : {},
		init : function(){
		     officialSpecialityCrud.datagrid = $('#officialSpeciality_datagrid').DataTable({
		        "processing": true,
		        "serverSide": true,
		        "language": {
					"lengthMenu": "_MENU_ Enregistrements par page",
					"processing": '<div class = "loading-message"><span>&nbsp;&nbsp;Loading...</span></div>',
					"sInfo": "",
					"sInfoEmpty": "",
					"zeroRecords" : 'aucun enregistrement trouvé' 
				},
		        "ajax": {
		        	url : '<?php echo $this->Html->url(array('action' => 'get_datagrid_data', 'ext' => 'json')); ?>',
		        	type: "POST",
		 			data : function ( d ) {
					  	var value = $('#OfficialSpecialityFilter').find('input[type = search]').val();
					  	var column = $('#OfficialSpecialityFilter').find('.hidden').val();
					  	
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
						title: '<?php echo __('Id'); ?>',
						data: 'OfficialSpeciality.id',
						sortable: true,
					},
					{
						title: '<?php echo __('Nom'); ?>',
						data: 'OfficialSpeciality.name',
						sortable: true
					},
				{
				title:  '<?php echo __('Actions'); ?>',
				data: null,
				sortable: false
			}],
				"columnDefs": [{
					"targets": [2],
					"width" : "230px",
					render: function (e, type, data, meta)
					{	
						var actions = [{
							'value': 'Détail',
							'attr': {
								'icon': 'folder-open-o',
								'class': "btn btn-xs btn-primary btn-open",
								'action-id': data.OfficialSpeciality.id
							}
						}];

						actions.push({
							'value': 'Modifier',
							'attr': {
								'icon': 'pencil',
								'class': "btn btn-xs btn-primary btn-edit",
								'action-id': data.OfficialSpeciality.id
							}
						});	

						actions.push({
							'value': 'Supprimer',
							'attr': {
								'icon': 'remove',
								'class': "btn btn-xs btn-danger btn-delete",
								'action-id': data.OfficialSpeciality.id
							}
						});	
						return createButtonGroup(actions);
					}
				}],
		    });			
		},
		showDetail : function (elm) {
	        var tr = $(elm).closest('tr');
	        var row = officialSpecialityCrud.datagrid.row( tr );
	 
	        if ( row.child.isShown() ) {
	            // This row is already open - close it
	            row.child.hide();
	            tr.removeClass('shown');
	        }
	        else {
	            // Open this row
	            row.child( officialSpecialityCrud.detail(row.data()) ).show();
	            tr.addClass('shown');
	        }
	    },
		detail : function(d){

		    return '<table id = "officialSpeciality_row_detail" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
						
				'<tr>'+
				'<td><?php echo __('Id'); ?></td>'+
					'<td>'+d.OfficialSpeciality.id+'</td>'+
				'</tr>'+			
				'<tr>'+
				'<td><?php echo __('Nom'); ?></td>'+
					'<td>'+d.OfficialSpeciality.name+'</td>'+
				'</tr>'+
				'</table>';	
		},
		addRow : function(postData){
			var formURL = $('#add_officialSpeciality_form').attr("action");
			$('#OfficialSpecialityAddDialog').trigger('dialogLoader', 'show');
			$.ajax(
			{
				url : formURL,
				type: "POST",
				data : postData,
				success:function(response, textStatus, jqXHR) 
				{
					if(response.result == 'success')
					{
						officialSpecialityCrud.datagrid.row.add(response.record).draw();
						toastr.success(response.message);
						$('#add_officialSpeciality_form').find('input, select').val('');
					}
					else
					{
						toastr.error(response.message); 
					}
					$('#OfficialSpecialityAddDialog').trigger('dialogLoader', 'hide');
					$('#OfficialSpecialityAddDialog').modal('hide'); 
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					$('#OfficialSpecialityAddDialog').trigger('dialogLoader', 'hide');
					toastr.error("<?php echo __('An error occured please try again!'); ?>");
				}
			});
			
		},
		deleteRow : function(id, tr){

			$('#officialSpeciality_datagrid').trigger('loader', 'show');
			$.ajax(
			{
				url : '<?php  echo Router::url(array('action' => 'delete', 'ext' => 'json'));?>',
				type: "POST",
				data : {id : id},
				success:function(response, textStatus, jqXHR) 
				{
					if(response.result == 'success')
					{
						officialSpecialityCrud.datagrid.row(tr).remove().draw( false );
						toastr.success(response.message);
					}
					else
					{
						toastr.error(response.message);
					}
					$('#officialSpeciality_datagrid').trigger('loader', 'hide');
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					$('#officialSpeciality_datagrid').trigger('loader', 'hide');
					toastr.error("<?php echo __('An error occured please try again!'); ?>");
				}
			});
		},
		updateRow : function(data){
			var formURL = $('#edit_officialSpeciality_form').attr("action");
			$('#OfficialSpecialityEditDialog').trigger('dialogLoader', 'show')
			$.ajax(
			{
				url : formURL,
				type: "POST",
				data : data,
				success:function(response, textStatus, jqXHR) 
				{
					var tr = $('[action-id = '+response.record.OfficialSpeciality.id+']').closest('tr'); 
					if(response.result == 'success')
					{
						officialSpecialityCrud.datagrid.row(tr).data( response.record ).draw();
						toastr.success(response.message);
					}
					else
					{
						toastr.error(response.message); 
					}
					 $('#OfficialSpecialityEditDialog').trigger('dialogLoader', 'hide'); 
					$('#OfficialSpecialityEditDialog').modal('hide'); 
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
	  				$('#OfficialSpecialityEditDialog').trigger('dialogLoader', 'hide');
	  				toastr.error("<?php echo __('An error occured please try again!'); ?>");
				}
			});
		}
	}

	jQuery(document).ready(function() {
		officialSpecialityCrud.init();

	 	$('#officialSpeciality_datagrid tbody').on('click', '.btn-open', function(){
	 		officialSpecialityCrud.showDetail(this)
	 	});

		//datagrid ajax form 
		$('.officialSpecialities').on('click', '.btn-delete', function(e)
		{
			var id = $(this).attr("action-id");
			var tr = $(this).closest("tr");
			
			if(confirm("<?php echo __d('profile_managment', 'Are you sure'); ?>")){
				officialSpecialityCrud.deleteRow(id, tr);
			}
			
			e.preventDefault();

			return false;
		});

		//datagrid ajax add form 
		$('#add_officialSpeciality_form').submit(function(e)
		{
			var postData = $(this).serializeArray();
			officialSpecialityCrud.addRow(postData);
			e.preventDefault();

			return false;
		});

		//datagrid ajax edit form 
		$('#edit_officialSpeciality_form').submit(function(e)
		{
			var postData = $(this).serializeArray();
			officialSpecialityCrud.updateRow(postData);
			e.preventDefault();

			return false;
		});

		$(document).on('click', '.btn-edit', function(event){
			$('#edit_officialSpeciality_form').find('input, select').val('');
			var data = officialSpecialityCrud.datagrid.row($(this).closest('tr')).data();
			console.log(data);
			$('#edit_officialSpeciality_form input, #edit_officialSpeciality_form select').each(function(){
				
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

			$('#OfficialSpecialityEditDialog').modal('show');
			
			event.preventDefault();
			return false;
		});

		$('#OfficialSpecialityFilter').on('click', 'a', function (e) {
		  	var field_name =  $(this).parent().attr('data-value')
		  	var field_label = $(this).text();
		  	$(this).closest('.datagrid-search').find('.hidden').val(field_name);
		  	$(this).closest('.datagrid-search').find('.selected-label').text(' ' +field_label);
		  	$(this).closest('.datagrid-search').find('input[type = search]').val("");
		  	$(this).closest('.datagrid-search').find('input[type = search]').attr('placeholder', 'Search by '+field_label);
		});

		$('#OfficialSpecialityFilter .search').on('click', '.btn', function (e) {
		  	officialSpecialityCrud.datagrid.ajax.reload();
		});

		$('#OfficialSpecialityEditDialog').on('hidden.bs.modal', function (e) {
		  	$('#edit_officialSpeciality_form').clearForm();
		});

		$('#OfficialSpecialityAddDialog').on('hidden.bs.modal', function (e) {
		  	$('#add_officialSpeciality_form').clearForm();
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

<div class="officialSpecialities index">
	<div class="datagrid" id="officialSpeciality_datagrid_container">
		<div class="datagrid-toolbar">
			<div class="col-xs-12 col-sm-6 col-md-8 no-padding">
				<!-- Button trigger modal -->
				<?php  echo $this->Croogo->adminAction(

						__d('profile_managment', 'Nouvelle spécialité'), '#',

						array('button' => 'primary', 'data-toggle' => 'modal', 'data-target' =>'#OfficialSpecialityAddDialog')

					);?>			</div>
			<div class="col-xs-6 col-md-4 no-padding">
			  	<div class="datagrid-search" id = "OfficialSpecialityFilter">
					<div class="input-group">
						<div class="input-group-btn selectlist" data-resize="auto" data-initialize="selectlist">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
								<span class="selected-label">Id</span>
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
					 		</button>
							<ul class="dropdown-menu" role="menu">
												
								<li data-value="OfficialSpeciality.id">	
									<a href="#">Id</a>
								</li>											
								<li data-value="OfficialSpeciality.name">	
									<a href="#">Nom</a>
								</li>												
							</ul>
							<input class="hidden hidden-field" name="column" readonly="readonly" aria-hidden="true" type="text" value = "OfficialSpeciality.id">
						</div>
						<div class="search input-group">
							<input type="search" class="form-control" placeholder="<?php  echo __d('profile_managment', 'Chercher par Id');  ?>"/>
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
		<table id="officialSpeciality_datagrid" class="display table-bordered"></table>
	</div>
</div>

<div class="modal fade" id="OfficialSpecialityAddDialog" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="OfficialSpecialityEdition" data-backdrop = "static">
 
	<?php  echo $this->Form->create('OfficialSpeciality',
			array('url' => array('action' => 'add', 'ext' => 'json'), 

				'id' => 'add_officialSpeciality_form')

			);?>	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">
						<?php  echo __d('profile_managment', 'Fermer');  ?>
					</span>
				</button>
				<h4 class="modal-title">
					<?php  echo __d('profile_managment', 'Ajouter une spécialité');  ?>				</h4>
			</div>

			<div class="modal-body">
			<?php
				$this->Form->inputDefaults(array('label' => false, 'class' => 'span10'));
				echo $this->Form->input('name', array(
					'label' => __d('profile_managment', 'Nom'),
					'id' => 'AddOfficialSpecialityName'
				));
			?>
			</div>
		  	<div class="loader" data-initialize="loader"></div>
			<div class="modal-footer">
				<?php 

				echo $this->Html->link(__d('profile_managment', 'Annuler'), '#', array('class' => 'btn btn-danger', 'data-dismiss' => 'modal')); 
 
				?>				<?php 

				echo $this->Form->button(__d('profile_managment', 'Enregistrer'), array('class' => 'btn btn-primary'));

				?>			</div>
		</div><!-- /.modal-content -->
  	</div><!-- /.modal-dialog -->
<?php echo $this->Form->end(); ?></div><!-- /.modal -->

<div class="modal fade" id="OfficialSpecialityEditDialog" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="OfficialSpecialityEdition" backdrop = "static">
	
	<?php  echo $this->Form->create('OfficialSpeciality',
			array('url' => array('action' => 'edit', 'ext' => 'json'), 

				'id' => 'edit_officialSpeciality_form')

			);?> 

	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">
						<?php  echo __d('profile_managment', 'Fermer');  ?>					</span>
				</button>
				<h4 class="modal-title">
					<?php  echo __d('profile_managment', 'Modifier la spécialité');  ?>				</h4>
	  		</div>
			<div class="modal-body">
			<?php
				echo $this->Form->input('id');
				$this->Form->inputDefaults(array('label' => false, 'class' => 'span10'));
				echo $this->Form->input('name', array(
					'label' => __d('profile_managment', 'Nom'),
					'id' => 'EditOfficialSpecialityName'
				));
			?>
			</div>
	  		<div class="loader"  data-initialize="loader"></div>
			<div class="modal-footer">
				<?php 

				echo $this->Html->link(__d('profile_managment', 'Annuler'), '#', array('class' => 'btn btn-danger', 'data-dismiss' => 'modal')); 
 
				?>				<?php 

				echo $this->Form->button(__d('profile_managment', 'Enregistrer'), array('class' => 'btn btn-primary'));

				?>			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
<?php echo $this->Form->end(); ?></div><!-- /.modal -->