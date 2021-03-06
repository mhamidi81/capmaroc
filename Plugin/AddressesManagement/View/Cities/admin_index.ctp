<?php
$this->viewVars['title_for_layout'] = __d('addresses_management', 'Cities');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('addresses_management', 'Cities'), array('action' => 'index'));
?>

<script>

<?php  $this->Html->scriptStart(array('inline' => false, 'block' => 'scriptBottom')); ?>
var cityCrud = {
		datagrid : {},
		init : function(){
		     cityCrud.datagrid = $('#city_datagrid').DataTable({
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
					  	var value = $('#CityFilter').find('input[type = search]').val();
					  	var column = $('#CityFilter').find('.hidden').val();
					  	
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
						title: '<?php echo __d('addresses_management', 'Id'); ?>',
						data: 'City.id',
						sortable: true,
					},
					{
						title: '<?php echo __d('addresses_management', 'Nom'); ?>',
						data: 'City.name',
						sortable: true
					},
					{
						title:  '<?php echo __d('addresses_management', 'Region'); ?>',
						data: 'Region.name',
						sortable: true
					},
					{
						title:  '<?php echo __d('addresses_management', 'Pays'); ?>',
						data: 'Country.name',
						sortable: true
					},
					{
					title:  '<?php echo __d('request_managment', 'Actions'); ?>',
					data: null,
					sortable: false
				}],
				"columnDefs": [{
					"targets": [4],
					"width" : "230px",
					render: function (e, type, data, meta)
					{	
						var actions = [{
							'value': 'Détail',
							'attr': {
								'icon': 'folder-open-o',
								'class': "btn btn-xs btn-primary btn-open",
								'action-id': data.City.id
							}
						}];

						actions.push({
							'value': 'Modifier',
							'attr': {
								'icon': 'pencil',
								'class': "btn btn-xs btn-primary btn-edit",
								'action-id': data.City.id
							}
						});	

						actions.push({
							'value': 'Supprimer',
							'attr': {
								'icon': 'remove',
								'class': "btn btn-xs btn-danger btn-delete",
								'action-id': data.City.id
							}
						});	
						return createButtonGroup(actions);
					}
				}],
		    });			
		},
		showDetail : function (elm) {
	        var tr = $(elm).closest('tr');
	        var row = cityCrud.datagrid.row( tr );
	 
	        if ( row.child.isShown() ) {
	            // This row is already open - close it
	            row.child.hide();
	            tr.removeClass('shown');
	        }
	        else {
	            // Open this row
	            row.child( cityCrud.detail(row.data()) ).show();
	            tr.addClass('shown');
	        }
	    },
		detail : function(d){

		    return '<table id = "city_row_detail" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
						
				'<tr>'+
				'<td><?php echo __d('addresses_management', 'Id'); ?></td>'+
					'<td>'+d.City.id+'</td>'+
				'</tr>'+			
				'<tr>'+
				'<td><?php echo __d('addresses_management', 'Name'); ?></td>'+
					'<td>'+d.City.name+'</td>'+
				'</tr>'+			
				'<tr>'+
				'<td><?php echo __d('addresses_management', 'Region'); ?></td>'+
					'<td>'+d.Region.name+'</td>'+
				'</tr>'+			
				'<tr>'+
				'<td><?php echo __d('addresses_management', 'Country'); ?></td>'+
					'<td>'+d.Country.name+'</td>'+
				'</tr>'+
				'</table>';	
		},
		addRow : function(postData){
			var formURL = $('#add_city_form').attr("action");
			$('#city_datagrid').trigger('dialogLoader', 'show');
			$.ajax(
			{
				url : formURL,
				type: "POST",
				data : postData,
				success:function(response, textStatus, jqXHR) 
				{
					if(response.result == 'success')
					{
						cityCrud.datagrid.row.add(response.record).draw();
						toastr.success(response.message);
						$('#add_city_form').find('input, select').val('');
					}
					else
					{
						toastr.error(response.message); 
					}
					$('#city_datagrid').trigger('dialogLoader', 'hide');
					$('#CityAddDialog').modal('hide'); 
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					$('#city_datagrid').trigger('dialogLoader', 'hide');
					toastr.error("<?php echo __d('addresses_management', 'An error occured please try again!'); ?>");
				}
			});
			
		},
		deleteRow : function(id, tr){

			$('#city_datagrid').trigger('loader', 'show');
			$.ajax(
			{
				url : '<?php  echo Router::url(array('action' => 'delete', 'ext' => 'json'));?>',
				type: "POST",
				data : {id : id},
				success:function(response, textStatus, jqXHR) 
				{
					if(response.result == 'success')
					{
						cityCrud.datagrid.row(tr).remove().draw( false );
						toastr.success(response.message);
					}
					else
					{
						toastr.error(response.message); 
					}
					$('#city_datagrid').trigger('loader', 'hide');
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					$('#city_datagrid').trigger('loader', 'hide');
					toastr.error("<?php echo __d('addresses_management', 'An error occured please try again!'); ?>");
				}
			});
		},
		updateRow : function(data){
			var formURL = $('#edit_city_form').attr("action");
			$('#city_datagrid').trigger('dialogLoader', 'show')
			$.ajax(
			{
				url : formURL,
				type: "POST",
				data : data,
				success:function(response, textStatus, jqXHR) 
				{
					var tr = $('[action-id = '+response.record.City.id+']').closest('tr'); 
					if(response.result == 'success')
					{
						cityCrud.datagrid.row(tr).data( response.record ).draw();
						toastr.success(response.message);
					}
					else
					{
						toastr.error(response.message); 
					}
					 $('#city_datagrid').trigger('dialogLoader', 'hide'); 
					$('#CityEditDialog').modal('hide'); 
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
	  				$('#city_datagrid').trigger('dialogLoader', 'hide');
	  				toastr.error("<?php echo __d('addresses_management', 'An error occured please try again!'); ?>");
				}
			});
		}
	}

	jQuery(document).ready(function() {
		cityCrud.init();

	 	$('#city_datagrid tbody').on('click', '.btn-open', function(){
	 		cityCrud.showDetail(this)
	 	});

		//datagrid ajax form 
		$('.cities').on('click', '.btn-delete', function(e)
		{
			var id = $(this).attr("action-id");
			var tr = $(this).closest("tr");
			
			if(confirm("<?php echo __d('addresses_management', 'Are you sure'); ?>")){
				cityCrud.deleteRow(id, tr);
			}
			
			e.preventDefault();

			return false;
		});

		//datagrid ajax add form 
		$('#add_city_form').submit(function(e)
		{
			var postData = $(this).serializeArray();
			cityCrud.addRow(postData);
			e.preventDefault();

			return false;
		});

		//datagrid ajax edit form 
		$('#edit_city_form').submit(function(e)
		{
			var postData = $(this).serializeArray();
			cityCrud.updateRow(postData);
			e.preventDefault();

			return false;
		});

		$(document).on('click', '.btn-edit', function(event){
			$('#edit_city_form').find('input, select').val('');
			var data = cityCrud.datagrid.row($(this).closest('tr')).data();
			console.log(data);
			$('#edit_city_form input, #edit_city_form select').each(function(){
				
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

			$('#CityEditDialog').modal('show');
			
			event.preventDefault();
			return false;
		});

		$('#CityFilter').on('click', 'a', function (e) {
		  	var field_name =  $(this).parent().attr('data-value')
		  	var field_label = $(this).text();
		  	$(this).closest('.datagrid-search').find('.hidden').val(field_name);
		  	$(this).closest('.datagrid-search').find('.selected-label').text(' ' +field_label);
		  	$(this).closest('.datagrid-search').find('input[type = search]').val("");
		  	$(this).closest('.datagrid-search').find('input[type = search]').attr('placeholder', 'Chercher par '+field_label);
		});

		$('#CityFilter .search').on('click', '.btn', function (e) {
		  	cityCrud.datagrid.ajax.reload();
		});

		$('#CityEditDialog').on('hidden.bs.modal', function (e) {
		  	$('#edit_city_form').clearForm();
		});

		$('#CityAddDialog').on('hidden.bs.modal', function (e) {
		  	$('#add_city_form').clearForm();
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

<div class="cities index">
	<div class="datagrid" id="city_datagrid_container">
		<div class="datagrid-toolbar">
			<div class="col-xs-12 col-sm-6 col-md-8 no-padding">
				<!-- Button trigger modal -->
				<?php  echo $this->Croogo->adminAction(

						__d('addresses_management', 'Nouvelle Ville'), '#',

						array('button' => 'primary', 'data-toggle' => 'modal', 'data-target' =>'#CityAddDialog')

					);?>			</div>
			<div class="col-xs-6 col-md-4 no-padding">
			  	<div class="datagrid-search" id = "CityFilter">
					<div class="input-group">
						<div class="input-group-btn selectlist" data-resize="auto" data-initialize="selectlist">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
								<span class="selected-label">Id</span>
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
					 		</button>
							<ul class="dropdown-menu" role="menu">
												
								<li data-value="City.id">	
									<a href="#">Id</a>
								</li>											
								<li data-value="City.name">	
									<a href="#">Nom</a>
								</li>											
								<li data-value="Region.name">	
									<a href="#">Region</a>
								</li>											
								<li data-value="Country.name">	
									<a href="#">Pays</a>
								</li>												
							</ul>
							<input class="hidden hidden-field" name="column" readonly="readonly" aria-hidden="true" type="text" value = "City.id">
						</div>
						<div class="search input-group">
							<input type="search" class="form-control" placeholder="<?php  echo __d('addresses_management', 'Chercher par Id');  ?>"/>
						  	<span class="input-group-btn">
								<button class="btn btn-default" type="button">
							  		<span class="glyphicon glyphicon-search"></span>
							  		<span class="sr-only">
							  		<?php  echo __d('addresses_management', 'Chercher');  ?>							 		</span>
								</button>
						  	</span>
						</div>
					</div>
			  	</div>
			</div>
			<div class = "clear"></div>
	  	</div>
		<table id="city_datagrid" class="display table-bordered"></table>
	</div>
</div>

<div class="modal fade" id="CityAddDialog" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="CityEdition" data-backdrop = "static">
 
	<?php  echo $this->Form->create('City',
			array('url' => array('action' => 'add', 'ext' => 'json'), 

				'id' => 'add_city_form')

			);?>	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">
						<?php  echo __d('addresses_management', 'Close');  ?>
					</span>
				</button>
				<h4 class="modal-title">
					<?php  echo __d('addresses_management', 'Nouvelle Ville');  ?>				</h4>
			</div>

			<div class="modal-body">
			<?php
				$this->Form->inputDefaults(array('label' => false, 'class' => 'span10'));
				echo $this->Form->input('name', array(
					'label' => __d('addresses_management', 'Nom'),
					'id' => 'AddCityName'
				));
				echo $this->Form->input('region_id', array(
					'label' => __d('addresses_management', 'Region'),
					'id' => 'AddCityRegionId'
				));
				echo $this->Form->input('country_id', array(
					'label' => __d('addresses_management', 'Pays'),
					'id' => 'AddCityCountryId'
				));
			?>
			</div>
		  	<div class="loader" data-initialize="loader"></div>
			<div class="modal-footer">
				<?php 

				echo $this->Html->link(__d('addresses_management', 'Annuler'), '#', array('class' => 'btn btn-danger', 'data-dismiss' => 'modal')); 
 
				?>				<?php 

				echo $this->Form->button(__d('addresses_management', 'Enregistrer'), array('class' => 'btn btn-primary'));

				?>			</div>
		</div><!-- /.modal-content -->
  	</div><!-- /.modal-dialog -->
<?php echo $this->Form->end(); ?></div><!-- /.modal -->

<div class="modal fade" id="CityEditDialog" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="CityEdition" backdrop = "static">
	
	<?php  echo $this->Form->create('City',
			array('url' => array('action' => 'edit', 'ext' => 'json'), 

				'id' => 'edit_city_form')

			);?> 

	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">
						<?php  echo __d('addresses_management', 'Close');  ?>					</span>
				</button>
				<h4 class="modal-title">
					<?php  echo __d('addresses_management', 'Edition de la ville');  ?>				</h4>
	  		</div>
			<div class="modal-body">
			<?php
				echo $this->Form->input('id');
				$this->Form->inputDefaults(array('label' => false, 'class' => 'span10'));
				echo $this->Form->input('name', array(
					'label' => __d('addresses_management', 'Name'),
					'id' => 'EditCityName'
				));
				echo $this->Form->input('region_id', array(
					'label' => __d('addresses_management', 'Region'),
					'id' => 'EditCityRegionId'
				));
				echo $this->Form->input('country_id', array(
					'label' => __d('addresses_management', 'Country'),
					'id' => 'EditCityCountryId'
				));
			?>
			</div>
	  		<div class="loader"  data-initialize="loader"></div>
			<div class="modal-footer">
				<?php 

				echo $this->Html->link(__d('addresses_management', 'Annuler'), '#', array('class' => 'btn btn-danger', 'data-dismiss' => 'modal')); 
 
				?>				<?php 

				echo $this->Form->button(__d('addresses_management', 'Enregistrer'), array('class' => 'btn btn-primary'));

				?>			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
<?php echo $this->Form->end(); ?></div><!-- /.modal -->