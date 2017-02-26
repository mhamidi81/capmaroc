<?php  $userId = AuthComponent::user('id');?>
<?php $user_role = $this->CapTheme->getConnectedUserRole(); ?>

<?php
$this->viewVars['title_for_layout'] = __d('request_managment', 'Requests');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('request_managment', "Rapports & statistiques"), '#')
	->addCrumb(__d('request_managment', "Demandes d'agrément"), array('action' => 'filter'));
?>
<script>

<?php  $this->Html->scriptStart(array('inline' => false, 'block' => 'scriptBottom')); ?>
	var requestCrud = {
		datagrid : {},
		init : function(){
		     requestCrud.datagrid = $('#request_datagrid').DataTable({
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

					  	if($('#FilterStatus').val())
					  	{
					  		d['filter']['Status.id'] = $('#FilterStatus').val();
					  	}

					  	if($('#FilterDate').val())
					  	{
					  		d['filter']['Request.event_date'] = moment($('#FilterDate').datepicker('getDate')).format('YYYY-MM-DD');
					  	}

					  	if($('#FilterNumber').val())
					  	{
					  		d['filter']['Request.number'] = $('#FilterNumber').val();
					  	}

					  	if($('#FilterRequesterType').val())
					  	{
					  		d['filter']['Request.Requester_type'] = $('#FilterRequesterType').val();
					  	}

					  	if($('#FilterRegionId').val())
					  	{
					  		d['filter']['Requester.region_id'] = $('#FilterRegionId').val();
					  	}  						  		
		            }
		        },
				"sort": true,
				"filter": false,
				"columns": [					
					{
						title: '<?php echo __d('request_managment', 'N°Dossier'); ?>',
						data: 'Request.number',
						sortable: true
					},
					{
						title: '<?php echo __d('request_managment', 'Personnalité juridique'); ?>',
						data: 'Request.requester_type',
						sortable: true,
					},
					{
						title:  '<?php echo __d('request_managment', 'Nom'); ?>',
						data: null,
						sortable: false
					},

					{
						title: '<?php echo __d('request_managment', 'Date de demande'); ?>',
						data: 'Request.event_date',
						sortable: true
					},
					{
						title:  '<?php echo __d('request_managment', 'Statut'); ?>',
						data: 'Status.name',
						sortable: true
					},
					{
						title:  '<?php echo __d('request_managment', 'Actions'); ?>',
						data: null,
						sortable: false
					}
				],
				"columnDefs": [
				{
					"targets": [1],
					render: function (e, type, data, meta)
					{	
						return (data.Request.requester_type == 'natural')? 'Physique' : 'Morale';
					}
				},
				{
					"targets": [3],
					render: function (e, type, data, meta)
					{	
						return moment(data.Request.event_date).format('DD-MM-YYYY');
					}
				},{
					"targets": [2],
					render: function (e, type, data, meta)
					{	
						return (data.Request.requester_type == 'natural')? data.Counselor.full_name : data.Company.name;
					}
				},{
					"targets": [5],
					"width" : "60px",
					render: function (e, type, data, meta)
					{	

						var actions = [{
							'value': 'Ouvrir',
							'attr': {
								'icon': 'folder-open-o',
								'class': "btn btn-xs btn-primary btn-open",
								'action-id': data.Request.id
							}
						}];

						return createButtonGroup(actions);	
					}
				}],
		    });			
		},
		refreshDetail : function (elm) {
	        var tr = $(elm).closest('tr');
	        var row = requestCrud.datagrid.row( tr );
            row.child( requestCrud.open(row.data()) );
	    },
		showDetail : function (elm) {
	        var tr = $(elm).closest('tr');
	        var row = requestCrud.datagrid.row( tr );
	 
	        if ( row.child.isShown() ) {
	            // This row is already open - close it
	            row.child.hide();
	            tr.removeClass('shown');
	        }
	        else {
	            // Open this row
	            row.child( requestCrud.open(row.data())).show();
	            //row.child.addClass('counselor_profile_row');
	            tr.addClass('shown');
	        }
	    },
		open : function(d){
			$('#request_datagrid').trigger('dialogLoader', 'show');
			App.startPageLoading();
	 		$.get( "<?php echo $this->Html->url(array('action' => 'get_requester_data')); ?>/"+d.Request.id, function( data ) {
	 				App.stopPageLoading();
			 	  $('#profile'+d.Request.id).html(data);
			 	 // $('.panel-scroll').perfectScrollbar();
			});

			return '<div id = "profile'+d.Request.id+'" class = "panel panel-white profile"></div>';
		}
	}

	jQuery(document).ready(function() {
		requestCrud.init();
		
		$(document).on('click', '.btn-show-document', function(e)
		{
			var id = $(this).attr("target-id");
			$(this).parent().parent().find('.btn').removeClass('current');
			$(this).addClass('current');
			$(this).closest('.profile').find('.panel-document-wrapper').hide();
			$(this).closest('.profile').find('div[document-id = '+id+']').show();
			e.preventDefault();
		});	 	
	 	$('#request_datagrid tbody').on('click', '.btn-open', function(e){
	 		$('#request_datagrid').find('div.profile').closest('tr').remove();
	 		requestCrud.showDetail(this);
	 		e.preventDefault();
	 	});	

	 	$('#request_datagrid_container').on('click', '#btn_filter', function(e){
	 		requestCrud.datagrid.ajax.reload();
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
<div class="requests index">
	<div class="datagrid" id="request_datagrid_container">
		<div class="datagrid-toolbar" >
			<table style = "width:100%">
				<tr>
					<th>Status</th>
					<th>Date</th>
					<th>N°dossier</th>
					<th>Personnalité juridique</th>
					<th>Region</th>
					<th></th>
				</tr>
				<tr>
					<th>
						<?php
							echo $this->Form->input('Filter.status', array(
								'empty' => true,
								'options' => $statuses,
								'type' => 'select',
								'label' => false,
								'div' => false,
								'class' => 'form-control'
							));
						?>
					</th>
					<th>
						<?php
							echo $this->Form->input('Filter.date', array(
								'label' => false,
								'div' => false,
								'type' => 'text',
								'class' => 'datepicker form-control',
								'maxDate' => 0
							));
						?>
					</th>
					<th>
						<?php
							echo $this->Form->input('Filter.number', array(
								'label' => false,
								'div' => false,
								'type' => 'text',
								'class' => 'form-control'
							));
						?>
					</th>
					<th>
						<?php
							echo $this->Form->input('Filter.requester_type', array(
								'empty' => true,
								'options' => $requests_types,
								'type' => 'select',
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
		<table id="request_datagrid" class="display table-bordered"></table>
	</div>
</div>