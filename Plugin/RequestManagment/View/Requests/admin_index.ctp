<?php  $userId = AuthComponent::user('id');?>
<?php $user_role = $this->CapTheme->getConnectedUserRole(); ?>

<?php
$this->viewVars['title_for_layout'] = __d('request_managment', 'Requests');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('request_managment', "Demandes d'agrément"), array('action' => 'index'));
?>
<?php echo $this->Html->script(array('../plugins/Chart.js/Chart.min.js'), array('block' => 'scriptBottom'));?>
<script>
var connected_user_role = '<?php $user_role; ?>';
var user_statuses  = <?php echo json_encode($user_statuses); ?>;
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
		        	url : '<?php echo $this->Html->url(array('action' => 'get_datagrid_data', 'ext' => 'json')); ?>',
		        	type: "POST",
		 			data : function ( d ) {
					  	var value = $('#RequestFilter').find('input[type = search]').val();
					  	var column = $('#RequestFilter').find('.hidden').val();
					  	d['filter'] = {};
					  	d['filter']['Request.archived'] = 0;
					  	
					  	var statuses = user_statuses;
					  	
					  	if(column && value)
					  	{
					  		d['filter'][column] = value;
					  	}

					  	if($('#archived').length > 0 && $('#archived').is(':checked'))
					  	{
					  		d['filter']['Request.archived'] = 1;
					  		statuses = 'granted';
					  	}
					  	else
					  	if($('#granted').length > 0 && $('#granted').is(':checked'))
					  	{
					  		statuses = 'granted';
					  	}

					  	d['filter']['Status.alias'] = statuses;
					  							  		
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

						if(connected_user_role == 'director' || data.Status.alias != 'pending_postale_papers')
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
						else 
						{
							var actions = [{
								'value': 'Ouvrir',
								'attr': {
									'icon': 'folder-open-o',
									'class': "btn btn-xs btn-primary btn-open",
									'action-id': data.Request.id,
									'disabled' : true
								}
							}];

							return createButtonGroup(actions);							
						}
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
			 	  //$('#request_specialities').select2();
			 	 // $('.panel-scroll').perfectScrollbar();
			});

			return '<div id = "profile'+d.Request.id+'" class = "panel panel-white profile"></div>';
		}
	}

	jQuery(document).ready(function() {
		requestCrud.init();

	 	$('#request_datagrid tbody').on('click', '.btn-open', function(e){
	 		$('#request_datagrid').find('div.profile').closest('tr').remove();
	 		requestCrud.showDetail(this);
	 		e.preventDefault();
	 	});

 		$('#request_datagrid tbody').on('click', '.btn-print-granted-request-decision', function(e){
	        var id = $(this).attr('request-id');
	 		location.href = Croogo.basePath+'/admin/request_managment/requests/print_granted_request_decision/'+id;
 			e.preventDefault();
	 	});
 		$('#request_datagrid tbody').on('click', '.print-granted-request-badge', function(e){
	        var id = $(this).attr('request-id');
	 		location.href = Croogo.basePath+'/admin/request_managment/requests/print_granted_request_badge	/'+id;
 			e.preventDefault();
	 	});
	
		$('input[type=radio][name=show_limit]').change(function() {
	        requestCrud.datagrid.ajax.reload();
    	});
		/*********************PROFILE_VALIDATION*******************************************************/
		$(document).on('click', '.btn-send-to-coordinate', function(e)
		{	
			var self = this;
			var valid = true;
			var documents = $(this).closest('.profile').find('.btn-show-document');
			
			if(documents.length == 0) {
				valid = false;
			}
			
			$(documents).each(function(){
				
				if($(this).find('.badge-success').length == 0)
				{
					var invalid_document = $(this).text();
					App.alert('Veuillez valider svp le document « ' + invalid_document + ' »');
					valid = false;
					return false;
				}
			});
			
			if (valid && confirm('Vous êtes sûr de vouloir envoyer ce dossier au coordinateur')) {				
				var request_id = $(self).attr('request-id');
				App.startPageLoading();
				
				$.ajax(
				{
					url : "<?php echo $this->Html->url(array('action' => 'send_to_coordinator', 'ext' => 'json')); ?>",
					type: "POST",
					data : {id : request_id},
					success:function(response, textStatus, jqXHR) 
					{ 
						App.stopPageLoading();
						
						if(response.result == 'success')
						{
							requestCrud.datagrid.ajax.reload();
							toastr.success(response.message);
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
			}
			e.preventDefault();
		});

		$(document).on('submit', '#request_completely_form', function(e)
		{
			var self = this;
			$('#dialog_counselor_completion').trigger('dialogLoader', 'show');
			var postData = $(this).serializeArray();
			$.ajax(
			{
				url : $(this).attr('action'),
				type: "POST",
				data : postData,
				success:function(response, textStatus, jqXHR) 
				{ 
					
					if(response.result == 'success')
					{
						$('#dialog_counselor_completion').modal('hide');
						requestCrud.datagrid.ajax.reload();
						toastr.success(response.message);
						$(self).clearForm();
					}
					else
					{
						toastr.error(response.message); 
					}
					$('#dialog_counselor_completion').trigger('dialogLoader', 'hide');
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
	  				$('#dialog_counselor_completion').trigger('dialogLoader', 'hide');
	  				toastr.error("<?php echo __d('request_managment', 'An error occured please try again!'); ?>");
				}
			});
			e.preventDefault();

			return false;
		});	

		$(document).on('submit', '#rollback_to_creation_form', function(e)
		{
			var self = this;
			$('#dialog_rollback_to_creation').trigger('dialogLoader', 'show');
			var postData = $(this).serializeArray();

			$.ajax(
			{
				url : $(self).attr('action'),
				type: "POST",
				data : postData,
				success:function(response, textStatus, jqXHR) 
				{ 
					App.stopPageLoading();
					
					if(response.result == 'success')
					{
						$('#dialog_rollback_to_creation').modal('hide');
						requestCrud.datagrid.ajax.reload();
						toastr.success(response.message);
						$(self).clearForm();
					}
					else
					{
						toastr.error(response.message); 
					}
					$('#dialog_rollback_to_creation').trigger('dialogLoader', 'hide');
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
	  				$('#dialog_rollback_to_creation').trigger('dialogLoader', 'hide');
	  				toastr.error("<?php echo __d('request_managment', 'An error occured please try again!'); ?>");
				}
			});
			e.preventDefault();

			return false;
		});
		/*************************************************************************************************/

		/*************************************************************************************************/
		
		/*********************pending_completely*******************************************************/
		$(document).on('click', '.btn-completely-received', function(e)
		{
			var self = this;
			App.startPageLoading();
			$.ajax(
			{
				url : "<?php echo $this->Html->url(array('action' => 'receive_request_completely', 'ext' => 'json')); ?>",
				type: "POST",
				data : {
					id : $(this).attr('request-id')
				},
				success:function(response, textStatus, jqXHR) 
				{ 
					App.stopPageLoading();
					
					if(response.result == 'success')
					{
						$('div#profile_validation_toolbar').hide();
						$('div#pending_completely_toolbar').show();
						requestCrud.datagrid.ajax.reload();
						toastr.success(response.message);
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
		/*************************************************************************************************/
		/*********************profile_validated*******************************************************/
		$(document).on('submit', '#rollback_to_profile_validation_form', function(e)
		{
			var self = this;
			$('#dialog_rollback_to_profile_validation').trigger('dialogLoader', 'show');
			var postData = $(this).serializeArray();

			$.ajax(
			{
				url : $(self).attr('action'),
				type: "POST",
				data : postData,
				success:function(response, textStatus, jqXHR) 
				{ 
					
					if(response.result == 'success')
					{
						$('#dialog_rollback_to_profile_validation').modal('hide');
						requestCrud.datagrid.ajax.reload();
						toastr.success(response.message);
						$(self).clearForm();
					}
					else
					{
						toastr.error(response.message); 
					}

					$('#dialog_rollback_to_profile_validation').trigger('dialogLoader', 'hide');
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
	  				$('#dialog_rollback_to_profile_validation').trigger('dialogLoader', 'hide');
	  				toastr.error("<?php echo __d('request_managment', 'An error occured please try again!'); ?>");
				}
			});
			e.preventDefault();

			return false;
		});	

		$(document).on('click', '.btn-send-to-commission', function(e)
		{	
			var self = this;
			
			if (confirm('Vous êtes sûr de vouloir envoyer ce dossier à la commission')) {				
				var request_id = $(self).attr('request-id');
				App.startPageLoading();
				
				$.ajax(
				{
					url : "<?php echo $this->Html->url(array('action' => 'send_to_commission', 'ext' => 'json')); ?>",
					type: "POST",
					data : {id : request_id},
					success:function(response, textStatus, jqXHR) 
					{ 
						App.stopPageLoading();
						
						if(response.result == 'success')
						{
							requestCrud.datagrid.ajax.reload();
							toastr.success(response.message);
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
			}
			e.preventDefault();
		});

		$(document).on('click', '.btn-archive-request', function(e)
		{	
			var self = this;
			
			if (confirm('Vous êtes sûr de vouloir archiver cet agrément')) {				
				var request_id = $(self).attr('request-id');
				App.startPageLoading();
				
				$.ajax(
				{
					url : "<?php echo $this->Html->url(array('action' => 'archive_request', 'ext' => 'json')); ?>",
					type: "POST",
					data : {id : request_id},
					success:function(response, textStatus, jqXHR) 
					{ 
						App.stopPageLoading();
						
						if(response.result == 'success')
						{
							requestCrud.datagrid.ajax.reload();
							toastr.success(response.message);
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
			}
			e.preventDefault();
		});

		/*************************************************************************************************/
		/*********************commission*******************************************************/
		$(document).on('submit', '#save_member_request_judgment_form', function(e)
		{
			var self = this;
			var postData = $(this).serializeArray();
			$('#dialog_send_commissionnary_judgment').trigger('dialogLoader', 'show');
			
			$.ajax(
			{
				url : $(this).attr('action'),
				data : postData,
				type: "POST",
				success:function(response, textStatus, jqXHR) 
				{ 
					if(response.result == 'success')
					{
						toastr.success(response.message);
						var request_id = $('#MembersRequestRequestId').val();
						//var opened_tab = $(".nav-tabs li.active:first");
						requestCrud.refreshDetail($('button[action-id = '+request_id+']'));
						$('body').removeClass('modal-open');
						$('#dialog_send_commissionnary_judgment').modal('hide');
						//requestCrud.datagrid.ajax.reload();
					}
					else
					{
						toastr.error(response.message); 
					}
					$('#dialog_send_commissionnary_judgment').trigger('dialogLoader', 'hide');
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
	  				$('#dialog_send_commissionnary_judgment').trigger('dialogLoader', 'hide');
	  				toastr.error("<?php echo __d('request_managment', 'An error occured please try again!'); ?>");
				}
			});
			e.preventDefault();
			return false;
		});
		/*************************************************************************************************/

		$(document).on('click', '.btn-validate-papers-reception', function(e)
		{
			bootbox.prompt({
				title: "Veuillez scanner le code à barre via la douchette",
				className: "bootbox-custom-prompt",
				value: "",
				buttons: {
					confirm: {
						label: "Valider",
						className: "btn-left btn-primary",
					},
					cancel: {
						label: "Annuler",
						className: "btn-right btn-default"
					},
				},
				callback: function(result) {
					
					if (result) {
						App.startPageLoading();
						
						$.ajax(
						{
							url : "<?php echo $this->Html->url(array('action' => 'validate_document_reception', 'ext' => 'json')); ?>",
							type: "POST",
							data : {qr_code : result},
							success:function(response, textStatus, jqXHR) 
							{ 
								App.stopPageLoading();
								
								if(response.result == 'success')
								{
									requestCrud.datagrid.ajax.reload();
									toastr.success(response.message);
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
					}
				}
			});
		});

		$(document).on('click', '.btn-validate-company-document', function(e)
		{
			var company_document_id = $(this).closest('.panel-document-wrapper').attr('request-document-id');
			var document_id = $(this).closest('.panel-document-wrapper').attr('document-id');
			var self = this;
			App.startPageLoading();
			
			$.ajax(
			{
				url : "<?php echo $this->Html->url(array('plugin' => 'company_managment', 'admin' => true, 'controller' => 'companies_documents','action' => 'validate_document', 'ext' => 'json')); ?>",
				type: "POST",
				data : {id : company_document_id},
				success:function(response, textStatus, jqXHR) 
				{ 
					App.stopPageLoading();
					
					if(response.result == 'success')
					{
						$(self).closest('.profile').find('button[target-id = '+document_id+']').append('<span class="badge badge-success"><i class = "ti-check"></i></span>');
						$(self).hide();
						$(self).prev().show();
						toastr.success(response.message);
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
		});

		$(document).on('click', '.btn-reset-company-document-validation', function(e)
		{
			var counselor_document_id = $(this).closest('.panel-document-wrapper').attr('request-document-id');
			var document_id = $(this).closest('.panel-document-wrapper').attr('document-id');
			var self = this;
			App.startPageLoading();
			$.ajax(
			{
				url : "<?php echo $this->Html->url(array('plugin' => 'company_managment', 'admin' => true, 'controller' => 'companies_documents','action' => 'invalidate_document', 'ext' => 'json')); ?>",
				type: "POST",
				data : {id : counselor_document_id},
				success:function(response, textStatus, jqXHR) 
				{ 
					App.stopPageLoading();
					
					if(response.result == 'success')
					{
						$(self).closest('.profile').find('button[target-id = '+document_id+']').find('span.badge').remove();
						$(self).hide();
						$(self).next().show();
						toastr.success(response.message);
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
		});

		$(document).on('click', '.btn-validate-counselor-document', function(e)
		{
			var counselor_document_id = $(this).closest('.panel-document-wrapper').attr('request-document-id');
			var document_id = $(this).closest('.panel-document-wrapper').attr('document-id');
			var self = this;
			App.startPageLoading();
			
			$.ajax(
			{
				url : "<?php echo $this->Html->url(array('plugin' => 'profile_managment', 'admin' => true, 'controller' => 'counselors_documents','action' => 'validate_document', 'ext' => 'json')); ?>",
				type: "POST",
				data : {id : counselor_document_id},
				success:function(response, textStatus, jqXHR) 
				{ 
					App.stopPageLoading();
					
					if(response.result == 'success')
					{
						$(self).closest('.profile').find('button[target-id = '+document_id+']').append('<span class="badge badge-success"><i class = "ti-check"></i></span>');
						$(self).hide();
						$(self).prev().show();
						toastr.success(response.message);
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
		});

		$(document).on('click', '.btn-reset-counselor-document-validation', function(e)
		{
			var counselor_document_id = $(this).closest('.panel-document-wrapper').attr('request-document-id');
			var document_id = $(this).closest('.panel-document-wrapper').attr('document-id');
			var self = this;
			App.startPageLoading();
			$.ajax(
			{
				url : "<?php echo $this->Html->url(array('plugin' => 'profile_managment', 'admin' => true, 'controller' => 'counselors_documents','action' => 'invalidate_document', 'ext' => 'json')); ?>",
				type: "POST",
				data : {id : counselor_document_id},
				success:function(response, textStatus, jqXHR) 
				{ 
					App.stopPageLoading();
					
					if(response.result == 'success')
					{
						$(self).closest('.profile').find('button[target-id = '+document_id+']').find('span.badge').remove();
						$(self).hide();
						$(self).next().show();
						toastr.success(response.message);
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
  console.log(e.target); // newly activated tab
  //call chart to render here
  chart3Handler();
});
<?php $this->Html->scriptEnd(); ?>
</script>
<div class="requests index">
	<div class="datagrid" id="request_datagrid_container">
		<div class="datagrid-toolbar">
			<div class="col-xs-12 col-sm-3 col-md-3 no-padding">
				<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'validate_document_reception', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'requests'))) {?>
				<a href="#" class="btn btn-default btn-validate-papers-reception">
					<i class = "ti-layout-column4-alt"></i> 
					Scanner du code à barre
				</a>
				<?php } ?>
			</div>
			<div class="col-xs-12 col-md-5 col-sm-5 no-padding">

				<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'can_see_all_requests', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'requests'))) {?>
				<div class="form-group" style = "margin-bottom:0px;" >
					<div class="clip-radio radio-primary" style = "margin-bottom:0px;">
						<input type="radio" value="archived" name="show_limit" id="archived" >
						<label for="archived">
							Agrées et archivées
						</label>
						<input type="radio" value="granted" name="show_limit" id="granted" >
						<label for="granted">
							Agrées
						</label>
						<input type="radio" value="new" name="show_limit" id="new_request" checked = "true" >
						<label for="new_request">
							Nouvelles
						</label>
					</div>
				</div>
				<?php } ?>

			</div>
			<div class="col-xs-12 col-md-4 col-sm-4 no-padding">
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
								<li data-value="Request.event_date">	
									<a href="#">Date de demande</a>
								</li>											
							</ul>
							<input class="hidden hidden-field" name="column" readonly="readonly" aria-hidden="true" type="text" value = "Request.id">
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