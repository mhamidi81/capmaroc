<?php
$this->viewVars['title_for_layout'] = __d('request_managment', 'Réunion');

$this->Html
	->addCrumb('', '/admin', array('icon' => $this->Theme->getIcon('home')))
	->addCrumb('Réunions', array('action' => 'index'))
	->addCrumb(__d('request_managment', 'Réunion du %s', date('d-m-Y' ,strtotime($meeting['Meeting']['event_date']))), '#');
?>
<?php echo $this->Html->script(array('../plugins/Chart.js/Chart.min.js'), array('block' => 'scriptBottom'));?>
<script>
var request_ids = <?php echo json_encode($request_ids); ?>;
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
		        	url : '<?php echo $this->Html->url(array('controller' => 'requests' ,'action' => 'get_datagrid_data_for_meeting', 'ext' => 'json')); ?>',
		        	type: "POST",
		 			data : function ( d ) {
					  	var value = $('#RequestFilter').find('input[type = search]').val();
					  	var column = $('#RequestFilter').find('.hidden').val();
					  	d['filter'] = {};
					  	if(column && value)
					  	{
					  		
					  		d['filter'][column] = value;
					  	}	
					  	
					  	if(request_ids.length == 0)
					  	{
					  		request_ids = [-1];
					  	}

					  	d['filter']['Request.id'] = request_ids;
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
						title:  '<?php echo __d('request_managment', 'Avis'); ?>',
						data: null,
						sortable: false
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
						"targets": [4],
						render: function (e, type, data, meta)
						{	

							return (data.MeetingsRequest.judgment_id)? data.MeetingsRequest.Judgment.name : '';
						}
					},{
						"targets": [2],
						render: function (e, type, data, meta)
						{	
							return (data.Request.requester_type == 'natural')? data.Counselor.full_name : data.Company.name;
						}
					},{
						"targets": [5],
						"width" : "80px",
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
					}
				],
		    });			
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
	            row.child( requestCrud.open(row.data()) ).show();
	            //row.child.addClass('counselor_profile_row');
	            tr.addClass('shown');
	        }
	    },
		open : function(d){
			$('#request_datagrid').trigger('dialogLoader', 'show');
			App.startPageLoading();
	 		$.get( "<?php echo $this->Html->url(array('controller' => 'requests' ,'action' => 'get_requester_data')); ?>/"+d.Request.id+'/'+<?php echo $meeting_id; ?>, function( data ) {
	 				App.stopPageLoading();
			 	  $('#profile'+d.Request.id).html(data);
			 	  //$('#request_specialities').select2();
			});

			return '<div id = "profile'+d.Request.id+'" class = "panel panel-white profile"></div>';
		},
		updateRow : function(data){
			var formURL = $('#edit_request_form').attr("action");
			App.startPageLoading();
			$.ajax(
			{
				url : formURL,
				type: "POST",
				data : data,
				success:function(response, textStatus, jqXHR) 
				{
					var tr = $('[action-id = '+response.record.Request.id+']').closest('tr'); 
					if(response.result == 'success')
					{
						requestCrud.datagrid.row(tr).data( response.record ).draw();
						toastr.success(response.message);
					}
					else
					{
						toastr.error(response.message); 
					}
					 App.stopPageLoading(); 
					$('#RequestEditDialog').modal('hide'); 
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
	  				$('#request_datagrid').trigger('dialogLoader', 'hide');
	  				toastr.error("<?php echo __d('request_managment', 'An error occured please try again!'); ?>");
				}
			});
		}
	}	

	jQuery(document).ready(function() {
		requestCrud.init();

	 	$('#request_datagrid tbody').on('click', '.btn-open', function(){
	 		requestCrud.showDetail(this)
	 	});

		$(document).on('submit', '#save_meeting_request_judgment_form', function(e)
		{
			var self = this;
			var postData = $(this).serializeArray();
			$('#dialog_send_meeting_request_judgment').trigger('dialogLoader', 'show');
			
			$.ajax(
			{
				url : $(this).attr('action'),
				data : postData,
				type: "POST",
				success:function(response, textStatus, jqXHR) 
				{ 
					$('#dialog_send_meeting_request_judgment').trigger('dialogLoader', 'hide');
					
					if(response.result == 'success')
					{
						toastr.success(response.message);
						requestCrud.datagrid.ajax.reload();
						$('#dialog_send_meeting_request_judgment').modal('hide');
					}
					else
					{
						toastr.error(response.message); 
					}
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
	  				$('#dialog_send_meeting_request_judgment').trigger('dialogLoader', 'hide');
	  				toastr.error("<?php echo __d('request_managment', 'An error occured please try again!'); ?>");
				}
			});
			e.preventDefault();

			return false;
		});

		$(document).on('click', '.btn-show-document', function(e)
		{
			var id = $(this).attr("target-id");
			$(this).parent().parent().find('.btn').removeClass('current');
			$(this).addClass('current');
			$(this).closest('.profile').find('.panel-document-wrapper').hide();
			$(this).closest('.profile').find('div[document-id = '+id+']').show();
			e.preventDefault();
		});

		$('#RequestFilter').on('click', 'a', function (e) {
		  	var field_name =  $(this).parent().attr('data-value')
		  	var field_label = $(this).text();
		  	$(this).closest('.datagrid-search').find('.hidden').val(field_name);
		  	$(this).closest('.datagrid-search').find('.selected-label').text(' ' +field_label);
		  	$(this).closest('.datagrid-search').find('input[type = search]').val("");
		  	$(this).closest('.datagrid-search').find('input[type = search]').attr('placeholder', 'Chercher par '+field_label);
		});

		$('#RequestFilter .search').on('click', '.btn', function (e) {
		  	requestCrud.datagrid.ajax.reload();
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
		/*********************MINISTARY*******************************************************/
		$(document).on('submit', '#admin_save_request_decision', function(e)
		{
			var self = this;
			var postData = $(this).serializeArray();
			App.startPageLoading();
			$.ajax(
			{
				url : $(this).attr('action'),
				data : postData,
				type: "POST",
				success:function(response, textStatus, jqXHR) 
				{ 
					App.stopPageLoading();
					
					if(response.result == 'success')
					{
						toastr.success(response.message);
						//var request_id = $('#RequestRequestId').val();
						//var opened_tab = $(".nav-tabs li.active:first");
						requestCrud.datagrid.ajax.reload();
						//requestCrud.refreshDetail($('button[action-id = '+request_id+']'));
					}
					else
					{
						toastr.error(response.message); 
					}
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
	  				App.stopPageLoading();
	  				toastr.error("<?php echo __d('request_managment', 'An error occured please try again!'); ?>");
				}
			});
			e.preventDefault();

			return false;
		});

		$('.modal').on('dialogLoader', function(e, action){

			if(action == 'hide')
			{
				$(this).find('.loader').hide();
			}
			else
			{
				$(this).find('.loader').show();
			}
		});	

		$(document).on('shown.bs.tab','a.charts_panel', function (e) {
		  console.log(e.target); // newly activated tab
		  //call chart to render here
		  chart3Handler();
		});
	});

<?php $this->Html->scriptEnd(); ?></script>

<div class="requests index">
	<div class="datagrid" id="request_datagrid_container">
		<div class="datagrid-toolbar">
			<div class="col-xs-12 col-sm-6 col-md-8 no-padding">
				<!-- Button trigger modal -->
			</div>
			<div class="col-xs-6 col-md-4 no-padding">
			  	<div class="datagrid-search" id = "RequestFilter">
					<div class="input-group">
						<div class="input-group-btn selectlist" data-resize="auto" data-initialize="selectlist">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
								<span class="selected-label">N°dossier</span>
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
					 		</button>
							<ul class="dropdown-menu" role="menu">							
								<li data-value="Request.number">	
									<a href="#">N°dossier</a>
								</li>											
							</ul>
							<input class="hidden hidden-field" name="column" readonly="readonly" aria-hidden="true" type="text" value = "Request.number">
						</div>
						<div class="search input-group">
							<input type="search" class="form-control" placeholder="<?php  echo __d('request_managment', 'Chercher par N°dossier');  ?>"/>
						  	<span class="input-group-btn">
								<button class="btn btn-default" type="button">
							  		<span class="glyphicon glyphicon-search"></span>
							  		<span class="sr-only">
							  		<?php  echo __d('request_managment', 'Chercher');  ?>							 		
							  		</span>
								</button>
						  	</span>
						</div>
					</div>
			  	</div>
			</div>
			<div class = "clear"></div>
	 	</div>
		<table id="request_datagrid" class="display table-bordered"></table>
	</div>
</div>