<?php  $userId = AuthComponent::user('id');?>
<?php $user_role = $this->CapTheme->getConnectedUserRole(); ?>

<?php
$this->viewVars['title_for_layout'] = __d('profile_managment', 'Conseillers');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('profile_managment', "Rapports & statistiques"), '#')
	->addCrumb(__d('profile_managment', "Conseillers"), array('action' => 'filter'));
?>
<script>

<?php  $this->Html->scriptStart(array('inline' => false, 'block' => 'scriptBottom')); ?>
	var counselorCrud = {
		datagrid : {},
		init : function(){
		     counselorCrud.datagrid = $('#counselor_datagrid').DataTable({
		        "processing": true,
		        "serverSide": true,
		        "language": {
					"lengthMenu": "_MENU_",
					"processing": '<div  class = "loading-message loading-message-boxed"><?php echo $this->Html->image("loading-spinner-grey.gif"); ?><span>&nbsp;&nbsp;Operation en cours...</span></div>',
					"sInfo":'',
					"sInfoEmpty": "",
					"zeroRecords" : 'aucun enregistrement trouvé' 
				},
		        "ajax": {
		        	url : '<?php echo $this->Html->url(array('action' => 'get_filtred_datagrid_data', 'ext' => 'json')); ?>',
		        	type: "POST",
		 			data : function ( d ) {

					  	d['filter'] = {};

					  	if($('#FilterFirstName').val())
					  	{
					  		d['filter']['Counselor.first_name'] = $('#FilterFirstName').val();
					  	}

					  	if($('#FilterLastName').val())
					  	{
					  		d['filter']['Counselor.last_name'] = $('#FilterLastName').val();
					  	}

					  	if($('#FilterId').val())
					  	{
					  		d['filter']['Counselor.id'] = $('#FilterId').val();
					  	}

					  	if($('#FilterEmail').val())
					  	{
					  		d['filter']['Counselor.email'] = $('#FilterEmail').val();
					  	}

					  	if($('#FilterRegionId').val())
					  	{
					  		d['filter']['Counselor.region_id'] = $('#FilterRegionId').val();
					  	}  						  		
		            }
		        },
				"sort": true,
				"filter": false,
				"columns": [					
					{
						title: '<?php echo __d('profile_managment', 'N°'); ?>',
						data: 'Counselor.id',
						sortable: true
					},
					{
						title: '<?php echo __d('profile_managment', 'Prénom'); ?>',
						data: 'Counselor.first_name',
						sortable: true,
					},
					{
						title:  '<?php echo __d('profile_managment', 'Nom'); ?>',
						data: 'Counselor.last_name',
						sortable: false
					},

					{
						title: '<?php echo __d('profile_managment', 'Email'); ?>',
						data: 'Counselor.email',
						sortable: true
					},
					{
						title:  '<?php echo __d('profile_managment', 'Ville'); ?>',
						data: 'City.name',
						sortable: true
					},
					{
						title:  '<?php echo __d('profile_managment', 'Actions'); ?>',
						data: null,
						sortable: false
					}
				],
				"columnDefs": [
					{
					"targets": [5],
					"width" : "60px",
					render: function (e, type, data, meta)
					{	

						var actions = [{
							'value': 'Ouvrir',
							'attr': {
								'icon': 'folder-open-o',
								'class': "btn btn-xs btn-primary btn-open",
								'action-id': data.Counselor.id
							}
						}];

						return createButtonGroup(actions);	
					}
				}],
		    });			
		},
		detail : function(d){

		    var detail =  '<table id = "counselor_row_detail" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
						
				'<tr>'+
				'<td><?php echo __d('profile_managment', 'Identifiant'); ?> : </td>'+
					'<td>'+d.Counselor.id+'</td>'+
				'</tr>'+			
				'<tr>'+
				'<td><?php echo __d('profile_managment', 'Prénom'); ?> : </td>'+
					'<td>'+d.Counselor.first_name+'</td>'+
				'</tr>'+
				'<tr>'+
				'<td><?php echo __d('profile_managment', 'Nom'); ?> : </td>'+
					'<td>'+d.Counselor.last_name+'</td>'+
				'</tr>';

				if(d.Counselor.image)
				{
					detail += '<tr>'+
					'<td><?php echo __d('profile_managment', 'Logo'); ?> : </td>'+
						'<td><img src = "'+Croogo.basePath+'uploads/counselor/'+d.Counselor.image+'" style= "max-width:200px"></td>'+
					'</tr>';
				}

				detail +='</table>';
				return detail;
		},
		showDetail : function (elm) {
	        var tr = $(elm).closest('tr');
	        var row = counselorCrud.datagrid.row( tr );
	 
	        if ( row.child.isShown() ) {
	            // This row is already open - close it
	            row.child.hide();
	            tr.removeClass('shown');
	        }
	        else {
	            // Open this row
	            row.child( counselorCrud.open(row.data())).show();
	            //row.child.addClass('counselor_profile_row');
	            tr.addClass('shown');
	        }
	    },
		open : function(d){
			$('#counselor_datagrid').trigger('dialogLoader', 'show');
			App.startPageLoading();
	 		$.get( "<?php echo $this->Html->url(array('plugin' => 'request_managment', 'controller' => 'requests','action' => 'get_requester_data')); ?>/"+d.Counselor.id+'/false/counselor', function( data ) {
	 				App.stopPageLoading();
			 	  $('#profile'+d.Counselor.id).html(data);
			});

			return '<div id = "profile'+d.Counselor.id+'" class = "panel panel-white profile"></div>';
		}
	}

	jQuery(document).ready(function() {
		counselorCrud.init();

		$(document).on('click', '.btn-show-document', function(e)
		{
			var id = $(this).attr("target-id");
			$(this).parent().parent().find('.btn').removeClass('current');
			$(this).addClass('current');
			$(this).closest('.profile').find('.panel-document-wrapper').hide();
			$(this).closest('.profile').find('div[document-id = '+id+']').show();
			e.preventDefault();
		});

	 	$('#counselor_datagrid tbody').on('click', '.btn-open', function(e){
	 		$('#counselor_datagrid').find('div.profile').closest('tr').remove();
	 		counselorCrud.showDetail(this);
	 		e.preventDefault();
	 	});	
	 	
	 	$('#counselor_datagrid_container').on('click', '#btn_filter', function(e){
	 		counselorCrud.datagrid.ajax.reload();
	 		e.preventDefault();
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
		
		$(document).on('dialogLoader', '.modal', function(e, action){

			if(action == 'hide')
			{
				$(this).find('.loader').hide();
			}
			else
			{
				$(this).find('.loader').show();
			}
		});	

		$(document).on('hidden.bs.modal','.modal', function (e) {
		  $('body').removeClass('modal-open');
		});	
	});

<?php $this->Html->scriptEnd(); ?></script>
<script>
<?php  $this->Html->scriptStart(array('inline' => false, 'block' => 'scriptBottom')); ?>

$(document).on('shown.bs.tab','a.charts_panel', function (e) {
// newly activated tab
  //call chart to render here
  chart3Handler();
});

<?php $this->Html->scriptEnd(); ?>
</script>
<style>
.datagrid input,.datagrid select {width: 90%;}
</style>
<div class="counselors index">
	<div class="datagrid" id="counselor_datagrid_container">
		<div class="datagrid-toolbar" >
			<table style = "width:100%">
				<tr>
					<th>Identifiant</th>
					<th>Prénom</th>
					<th>Nom</th>
					<th>Email</th>
					<th>Region</th>
					<th></th>
				</tr>
				<tr>
					<th>
						<?php
							echo $this->Form->input('Filter.id', array(
								'label' => false,
								'div' => false,
								'class' => 'form-control'
							));
						?>
					</th>
					<th>
						<?php
							echo $this->Form->input('Filter.first_name', array(
								'label' => false,
								'div' => false,
								'type' => 'text',
								'class' => 'form-control'
							));
						?>
					</th>
					<th>
						<?php
							echo $this->Form->input('Filter.last_name', array(
								'label' => false,
								'div' => false,
								'type' => 'text',
								'class' => 'form-control'
							));
						?>
					</th>
					<th>
						<?php
							echo $this->Form->input('Filter.email', array(
								'label' => false,
								'div' => false,
								'class' => 'form-control'
							));
						?>
					</th>
					<th>
						<?php
							echo $this->Form->input('Filter.region_id', array(
								'empty' => true,
								'options' => $regions,
								'type' => 'select',
								'label' => false,
								'div' => false,
								'class' => 'form-control'
							));
						?>
					</th>
					<th>
						<button class = "btn btn-success" id = "btn_filter" ><i class = "ti-search"></i> Filtrer</button>
					</th>
				</tr>
			</table>
			<div class = "clear"></div>
	 	</div>
		<table id="counselor_datagrid" class="display table-bordered"></table>
	</div>
</div>