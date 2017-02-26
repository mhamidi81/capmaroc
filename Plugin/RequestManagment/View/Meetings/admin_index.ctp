<?php  $userId = AuthComponent::user('id');?>
<?php
echo $this->Html->script(array(
	'../plugins/jquery-smart-wizard/jquery.smartWizard.js'
), array('block' => 'scriptBottom'));
?>
<?php
$this->viewVars['title_for_layout'] = __d('request_managment', 'Meetings');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('request_managment', 'Réunion'), array('action' => 'index'));
?>

<script>

<?php  $this->Html->scriptStart(array('inline' => false, 'block' => 'scriptBottom')); ?>


var FormWizard = {
    wizardContent : $('#wizard'),
    wizardForm : $('#add_meeting_form'),
    numberOfSteps : $('.swMain > ul > li').length,
    init : function () {
        // function to initiate Wizard Form
        FormWizard.wizardContent.smartWizard({
            selected: 0,
            keyNavigation: false,
            onLeaveStep: FormWizard.leaveAStepCallback,
            onShowStep: FormWizard.onShowStep,
        });
        var numberOfSteps = 0;
    },
    onShowStep : function (obj, context) {
    	if(context.toStep == FormWizard.numberOfSteps){
    		$('.anchor').children("li:nth-child(" + context.toStep + ")").children("a").removeClass('wait');
            //displayConfirm();
    	}
        $(".next-step").unbind("click").click(function (e) {
            e.preventDefault();
            FormWizard.wizardContent.smartWizard("goForward");
        });
        $(".back-step").unbind("click").click(function (e) {
            e.preventDefault();
            FormWizard.wizardContent.smartWizard("goBackward");
        });
        $(".go-first").unbind("click").click(function (e) {
            e.preventDefault();
            FormWizard.wizardContent.smartWizard("goToStep", 1);
        });
        $(".finish-step").unbind("click").click(function (e) {
            e.preventDefault();
            FormWizard.onFinish(obj, context);
        });
    },
    leaveAStepCallback : function (obj, context) {
        return FormWizard.validateSteps(context.fromStep, context.toStep);

        // return false to stay on step and true to continue navigation
    },
    onFinish : function (obj, context) {
        if (FormWizard.validateSteps(3, 4)) {
            $('.anchor').children("li").last().children("a").removeClass('wait').removeClass('selected').addClass('done').children('.stepNumber').addClass('animated tada');
            $('#add_meeting_form').submit();
        }
    },
    validateSteps : function (stepnumber, nextstep) {
        var isStepValid = false;
        
       
        if (nextstep > stepnumber) {
        	
           if(stepnumber == 1)
           {
	            if (FormWizard.wizardForm[0].checkValidity()) { // validate the form

	                for (var i=stepnumber; i<=nextstep; i++){
	        		$('.anchor').children("li:nth-child(" + i + ")").not("li:nth-child(" + nextstep + ")").children("a").removeClass('wait').addClass('done').children('.stepNumber').addClass('animated tada');
	        		}
	                //focus the invalid fields
	                isStepValid = true;
	                return true;
	            }
	            else
	            {
	            	$('#add_meeting_submit_btn').trigger('click');
	            }           	
           }
           
           if(stepnumber == 2)
           {
           		if(WizardRequestsCrud.rows_selected.length == 0)
           		{
           			$('#step-2').find('.alert').show();
           			return false;
           		}
           		else
           		{
           			$('#step-2').find('.alert').hide();
           			 isStepValid = true;
	                return true;
           		}
           }

           if(stepnumber == 3)
           {
           		if(WizardMembersCrud.rows_selected.length == 0)
           		{
           			$('#step-3').find('.alert').show();
           			return false;
           		}
           		else
           		{
           			$('#step-3').find('.alert').hide();
           			 isStepValid = true;
	                return true;
           		}
           }
        } else if (nextstep < stepnumber) {
        	for (i=nextstep; i<=stepnumber; i++){
        		$('.anchor').children("li:nth-child(" + i + ")").children("a").addClass('wait').children('.stepNumber').removeClass('animated tada');
        	}
            
            return true;
        } 
    },
   	validateAllSteps : function () {
        var isStepValid = true;
        // all step validation logic
        return isStepValid;
    }
}

var WizardMembersCrud = {
	datagrid : false,
	rows_selected : [],
	getData : function(){
		$.ajax(
		{
			url : '<?php echo $this->Html->url(array('plugin' => 'user_managment' ,'controller' => 'users', 'action' => 'get_datagrid_data', 'ext' => 'json')); ?>',
			type: "POST",
			data : {'filter' : {'Role.alias' : 'commission_member'}, 'length' : 100},
			success:function(response, textStatus, jqXHR) 
			{	

				$.each(response.data, function(key, datum){
					WizardMembersCrud.rows_selected.push(datum.User.id);
				});

				WizardMembersCrud.init(response);
				WizardMembersCrud.updateDataTableSelectAllCtrl();
			}
		});
	},
	init : function(data){
		
		if(WizardMembersCrud.datagrid)
		{
			WizardMembersCrud.datagrid.destroy();
			WizardMembersCrud.rows_selected = [];
		}
			

	    WizardMembersCrud.datagrid = $('#wizard_commission_members_datagrid').DataTable({
	        "processing": true,
	        "serverSide": false,
	        "language": {
				"lengthMenu": "",
				"processing": '<div  class = "loading-message loading-message-boxed"><?php echo $this->Html->image("loading-spinner-grey.gif"); ?><span>&nbsp;&nbsp;Operation en cours...</span></div>',
				"sInfo":'',
				"sInfoEmpty": "",
				"zeroRecords" : "aucune membre de commission n'a été trouvé"
			},
			"data" : data.data,
			"sort": true,
			"filter": false,
			"columns": [
				{
					title:  '<input name="select_all" value="1" type="checkbox">',
					data: null,
					sortable: false
				},				
				{
					title: '<?php echo __d('request_managment', 'Prénom'); ?>',
					data: 'User.first_name',
					sortable: true
				},
				{
					title: '<?php echo __d('request_managment', 'Nom'); ?>',
					data: 'User.last_name',
					sortable: true,
				},
				{
					title: '<?php echo __d('request_managment', 'Service'); ?>',
					data: 'Service.name',
					sortable: true,
				}
			],
			"columnDefs": [{
				'targets': [0],
				'className': 'text-center',
				'render': function (data, type, full, meta){
					return '<input type="checkbox">';
				}
			}],
			'order': [1, 'asc'],
			'rowCallback': function(row, data, dataIndex){
				// Get row ID
				var rowId = data.User.id;
				
				// If row ID is in the list of selected row IDs
				if($.inArray(rowId, WizardMembersCrud.rows_selected) !== -1){
					$(row).find('input[type="checkbox"]').prop('checked', true);
					$(row).addClass('selected');
				}
			}
	    });

		// Handle table draw event
		WizardMembersCrud.datagrid.on('draw', function(){
			// Update state of "Select all" control
			WizardMembersCrud.updateDataTableSelectAllCtrl();
		});	
	},
	updateDataTableSelectAllCtrl: function(){

		var $table             = WizardMembersCrud.datagrid.table().node();
		var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
		var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
		var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);

		// If none of the checkboxes are checked
		if($chkbox_checked.length === 0){
			chkbox_select_all.checked = false;
			
			if('indeterminate' in chkbox_select_all){
				chkbox_select_all.indeterminate = false;
			}

		// If all of the checkboxes are checked
		} else if ($chkbox_checked.length === $chkbox_all.length){
		chkbox_select_all.checked = true;
			
			if('indeterminate' in chkbox_select_all){
				chkbox_select_all.indeterminate = false;
			}
		// If some of the checkboxes are checked
		} else 
		{
			chkbox_select_all.checked = true;
			if('indeterminate' in chkbox_select_all){
				chkbox_select_all.indeterminate = true;
			}
		}
	}
};

var WizardRequestsCrud = {
	datagrid : false,
	rows_selected : [],
	getData : function(){
		$.ajax(
		{
			url : '<?php echo $this->Html->url(array('controller' => 'requests', 'action' => 'admin_get_pending_request_datagrid_data', 'ext' => 'json')); ?>',
			type: "POST",
			data : {'length' : 100},
			success:function(response, textStatus, jqXHR) 
			{	
				WizardRequestsCrud.init(response);
			}
		});
	},
	init : function(data){
		
		if(WizardRequestsCrud.datagrid)
		{
			WizardRequestsCrud.datagrid.destroy();
			WizardRequestsCrud.rows_selected = [];
		}
			
	    WizardRequestsCrud.datagrid = $('#request_datagrid').DataTable({
	        "processing": true,
	        "serverSide": false,
	        "language": {
				"lengthMenu": "",
				"processing": '<div  class = "loading-message loading-message-boxed"><?php echo $this->Html->image("loading-spinner-grey.gif"); ?><span>&nbsp;&nbsp;Operation en cours...</span></div>',
				"sInfo":'',
				"sInfoEmpty": "",
				"zeroRecords" : 'aucune demande trouvée' 
			},
			"data" : data.data,
			"sort": true,
			"filter": false,
			"columns": [
				{
					title:  '<input name="select_all" value="1" type="checkbox">',
					data: null,
					sortable: false
				},				
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
					title: '<?php echo __d('request_managment', 'Date de demande'); ?>',
					data: 'Request.event_date',
					sortable: true
				},
				{
					title:  '<?php echo __d('request_managment', 'Demandeur'); ?>',
					data: null,
					sortable: false
				}
			],
			"columnDefs": [
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
						return (data.Request.requester_type == 'natural')? 'Physique' : 'Morale';
					}
				},{
				'targets': [0],
				'className': 'text-center',
				'render': function (data, type, full, meta){
					return '<input type="checkbox">';
				}
				},{
				"targets": [4],
				render: function (e, type, data, meta)
				{	

					return (data.Request.requester_type == 'natural')? data.Counselor.first_name+' '+data.Counselor.last_name : data.Company.name;
				}
			}],
			'order': [1, 'asc'],
			'rowCallback': function(row, data, dataIndex){
				// Get row ID
				var rowId = data[0];

				// If row ID is in the list of selected row IDs
				if($.inArray(rowId, WizardRequestsCrud.rows_selected) !== -1){
					$(row).find('input[type="checkbox"]').prop('checked', true);
					$(row).addClass('selected');
				}
			}
	    });
		// Handle table draw event
	   WizardRequestsCrud.datagrid.on('draw', function(){
	      // Update state of "Select all" control
	      WizardRequestsCrud.updateDataTableSelectAllCtrl();
	   });
	},
	updateDataTableSelectAllCtrl: function(){

		var $table             = WizardRequestsCrud.datagrid.table().node();
		var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
		var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
		var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);

		// If none of the checkboxes are checked
		if($chkbox_checked.length === 0){
			chkbox_select_all.checked = false;
			
			if('indeterminate' in chkbox_select_all){
				chkbox_select_all.indeterminate = false;
			}

		// If all of the checkboxes are checked
		} else if ($chkbox_checked.length === $chkbox_all.length){
		chkbox_select_all.checked = true;
			
			if('indeterminate' in chkbox_select_all){
				chkbox_select_all.indeterminate = false;
			}
		// If some of the checkboxes are checked
		} else 
		{
			chkbox_select_all.checked = true;
			if('indeterminate' in chkbox_select_all){
				chkbox_select_all.indeterminate = true;
			}
		}
	}
};

var meetingCrud = {
		datagrid : {},
		init : function(){
		     meetingCrud.datagrid = $('#meeting_datagrid').DataTable({
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
					  	var value = $('#MeetingFilter').find('input[type = search]').val();
					  	var column = $('#MeetingFilter').find('.hidden').val();
					  	
					  	if(column && value)
					  	{
					  		d['filter'] = {};
					  		d['filter'][column] = value;
					  	}

				  		if(!d['filter']) 
			  			{
			  				d['filter'] = {};
			  			}
				<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'aprove_all_meeting_requests', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'meetings'))) {?>
					  	if($('#show_not_all').is(':checked'))
					  	{
					  		d['filter']['Meeting.closed'] = 1;
					  		d['filter']['Meeting.archived'] = 0;					  		
					  	}
					  	else
					  	{
					  		d['filter']['Meeting.archived'] = 1;
					  	}
				<?php }else{ ?>
					  	if($('#show_closed').is(':checked'))
					  	{
					  		d['filter']['Meeting.closed'] = 1;					  		
					  	}
					  	else
					  	{
					  		d['filter']['Meeting.closed'] = 0;
					  	}
				<?php } ?>
		            }
		        },
				"sort": true,
				"filter": false,
				"columns": [
					{
						title: '<?php echo __d('request_managment', 'Numéro'); ?>',
						data: 'Meeting.id',
						sortable: true,
					},
					{
						title: '<?php echo __d('request_managment', 'Date de réunion'); ?>',
						data: 'Meeting.event_date',
						sortable: true
					},
					{
					title:  '<?php echo __d('request_managment', 'Actions'); ?>',
					data: null,
					sortable: false
				}],
				"columnDefs": [
				{
					"targets": [1],
					render: function (e, type, data, meta)
					{	
						if(moment(data.Meeting.event_date).isValid())
						{
							return moment(data.Meeting.event_date).format('DD-MM-YYYY hh:mm:ss');
						}
						else
						{
							return '';
						}
						
					}
				},{
					"targets": [2],
					"width" : "230px",
					render: function (e, type, data, meta)
					{
						var all_meeting_requests_aproved = true;
					    var all_meeting_requests_decision_made = true;

					    $(data.MeetingsRequest).each(function(key, meeting_request){
					    	 
					    	 if(!meeting_request.judgment_id)
					    	 {
					    	 	all_meeting_requests_aproved = false;

					    	 	return false;
					    	 }
					    });

					    if(all_meeting_requests_aproved)
					    {
						    $(data.MeetingsRequest).each(function(key, meeting_request){
						    	 if(meeting_request.Request.Status.alias != 'granted' && meeting_request.Request.Status.alias != 'refused')
						    	 {
						    	 	all_meeting_requests_decision_made = false;

						    	 	return false;
						    	 }
						    });
					    }

						var options = '';
						var actions_header =  '<li class="btn-group">'
							+ 	'<button data-close-others="true" data-delay="1000" data-hover="dropdown" data-toggle="dropdown" class="btn btn-xs btn-primary dropdown-toggle  type="button" >'
							+ 	'<span>Actions...</span><i class="fa fa-angle-down"></i></button>'
							+ 	'<ul role="menu" class="dropdown-menu">';

						<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'open', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'meetings'))) {?>
						options += '<li><a href="#" class="btn-state btn-open" action-id = "'+data.Meeting.id+'" ><i class = "fa fa-folder-open-o"></i> Ouvrir</a></li>';
						<?php } ?>
						
						if(!data.Meeting.closed)
							{
						<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'edit', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'meetings'))) {?>
						options +=	'<li><a href="#" class="btn-state btn-edit-meeting-date"><i class = "fa fa-calendar"></i> Editer la date de la réunion</a></li>';
						<?php } ?>
						<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'update_meeting_members', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'meetings'))) {?>
						options +=	'<li><a href="#" class="btn-state btn-edit-meeting-members"><i class = "fa fa-user"></i> Editer les Membres invités</a></li>';
						<?php } ?>
						<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'update_meeting_requests', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'meetings'))) {?>
						options +=	'<li><a href="#" class="btn-state btn-edit-meeting-requests" ><i class = "fa fa-folder"></i> Editer les Dossiers à traiter</a></li>';
						<?php } ?>
						}

						<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'aprove_all_meeting_requests', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'meetings'))) {?>
							
							if(data.Meeting.closed && data.Meeting.archived == 0)
							{
								options +=	'<li><a href="#" class="btn-state btn-aprove-all"  ><i class = "fa fa-check"></i> Approuver tous les dossiers</a></li>';
							}
						<?php } ?>

						<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'archive_meeting', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'meetings'))) {?>

							if(all_meeting_requests_decision_made && data.Meeting.closed && data.Meeting.archived == 0)
							{
								options +=	'<li><a href="#" class="btn-state btn-archive_meeting"  ><i class = "fa fa-save"></i> Valider et archiver la réunion</a></li>';
							}
						<?php } ?>

						<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'print_meeting_pv', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'meetings'))) {?>
							
							if(all_meeting_requests_aproved)
							{
								options +=	'<li><a href="#" class="btn-state btn-print-meeting-pv"  ><i class = "fa fa-print"></i> Imprimer le pv de la réunion</a></li>';
							}
						<?php } ?>



						if(!data.Meeting.closed)
						{
							
						if(all_meeting_requests_aproved)
						{
							<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'close_meeting', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'meetings'))) {?>
							options +=	'<li><a href="#" class="btn-state btn-close-meeting" ><i class = "fa fa-lock"></i> Clôturer la réunion</a></li>';
							<?php } ?>
						}

						<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'admin_print_meeting_pv', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'meetings'))) {?>

						options +=	'<li><a href="#" class="btn-state btn-print-invitations" ><i class = "fa fa-print"></i> Imprimer les invitations</a></li>';
							
						<?php } ?>
						<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'delete', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'meetings'))) {?>
						options +=	'<li><a href="#" class="btn-state btn-delete" action-id = "'+data.Meeting.id+'" ><i class = "fa fa-remove"></i> Supprimer</a></li>';
						<?php } ?>
						}

						var actions_footer =  	'</ul></li>';
						return actions_header+options+actions_footer;
					}
				}],
		    });			
		},
		showDetail : function (elm) {
	        var tr = $(elm).closest('tr');
	        var row = meetingCrud.datagrid.row( tr ).data();
	 		location.href = Croogo.basePath+'/admin/request_managment/meetings/open/'+row.Meeting.id;
	    },
		addRow : function(postData){
			var formURL = $('#add_meeting_form').attr("action");
			$('#MeetingAddDialog').trigger('dialogLoader', 'show');
			$.ajax(
			{
				url : formURL,
				type: "POST",
				data : postData,
				success:function(response, textStatus, jqXHR) 
				{
					if(response.result == 'success')
					{
						meetingCrud.datagrid.row.add(response.record).draw();
						toastr.success(response.message);
						$('#add_meeting_form').find('input, select').val('');
					}
					else
					{
						toastr.error(response.message); 
					}
					$('#MeetingAddDialog').trigger('dialogLoader', 'hide');
					$('#MeetingAddDialog').modal('hide'); 
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					$('#MeetingAddDialog').trigger('dialogLoader', 'hide');
					toastr.error("<?php echo __d('request_managment', 'An error occured please try again!'); ?>");
				}
			});
			
		},
		deleteRow : function(id, tr){

			App.startPageLoading();
			$.ajax(
			{
				url : '<?php echo Router::url(array('action' => 'delete', 'ext' => 'json')); ?>',
				type: "POST",
				data : {id : id},
				success:function(response, textStatus, jqXHR) 
				{
					if(response.result == 'success')
					{
						meetingCrud.datagrid.row(tr).remove().draw( false );
						toastr.success(response.message);
					}
					else
					{
						toastr.error(response.message); 
					}
					App.stopPageLoading();
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					App.stopPageLoading();
					toastr.error("<?php echo __d('request_managment', 'An error occured please try again!'); ?>");
				}
			});
		},
		close : function(id){

			App.startPageLoading();
			$.ajax(
			{
				url : '<?php echo Router::url(array('action' => 'close_meeting', 'ext' => 'json')); ?>',
				type: "POST",
				data : {id : id},
				success:function(response, textStatus, jqXHR) 
				{
					if(response.result == 'success')
					{
						meetingCrud.datagrid.ajax.reload();
						toastr.success(response.message);
					}
					else
					{
						toastr.error(response.message); 
					}
					App.stopPageLoading();
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					App.stopPageLoading();
					toastr.error("<?php echo __d('request_managment', 'An error occured please try again!'); ?>");
				}
			});
		},
		archive : function(id){

			App.startPageLoading();
			$.ajax(
			{
				url : '<?php echo Router::url(array('action' => 'archive_meeting', 'ext' => 'json')); ?>',
				type: "POST",
				data : {id : id},
				success:function(response, textStatus, jqXHR) 
				{
					if(response.result == 'success')
					{
						meetingCrud.datagrid.ajax.reload();
						toastr.success(response.message);
					}
					else
					{
						toastr.error(response.message); 
					}
					App.stopPageLoading();
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					App.stopPageLoading();
					toastr.error("<?php echo __d('request_managment', 'An error occured please try again!'); ?>");
				}
			});
		},
		aproveAll : function(id){

			App.startPageLoading();
			$.ajax(
			{
				url : '<?php echo Router::url(array('action' => 'aprove_all_meeting_requests', 'ext' => 'json')); ?>',
				type: "POST",
				data : {id : id},
				success:function(response, textStatus, jqXHR) 
				{
					if(response.result == 'success')
					{
						meetingCrud.datagrid.ajax.reload();
						toastr.success(response.message);
					}
					else
					{
						toastr.error(response.message); 
					}
					App.stopPageLoading();
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					App.stopPageLoading();
					toastr.error("<?php echo __d('request_managment', 'An error occured please try again!'); ?>");
				}
			});
		}
}

var EditMeetingRequestsCrud = {
	meeting_id : -1,
	schujuled_for_meeting : [],
	datagrid : false,
	rows_selected : [],
	getData : function(){
		$('#MeetingRequestsEditDialog').trigger('dialogLoader', 'show');
		$.ajax(
		{
			url : '<?php echo $this->Html->url(array('admin' => true, 'controller' => 'requests', 'action' => 'admin_get_meeting_and_pending_request_datagrid_data', 'ext' => 'json')); ?>',
			type: "POST",
			data : {'meeting_id' : EditMeetingRequestsCrud.meeting_id},
			success:function(response, textStatus, jqXHR) 
			{	
				EditMeetingRequestsCrud.init(response);
				$('#MeetingRequestsEditDialog').trigger('dialogLoader', 'hide');
			}
		});
	},
	init : function(data){
		
		if(EditMeetingRequestsCrud.datagrid)
		{
			EditMeetingRequestsCrud.datagrid.destroy();
			EditMeetingRequestsCrud.rows_selected = [];
		}

		EditMeetingRequestsCrud.rows_selected = EditMeetingRequestsCrud.schujuled_for_meeting;

	    EditMeetingRequestsCrud.datagrid = $('#pending_request_datagrid').DataTable({
	        "processing": true,
	        "serverSide": false,
	        "language": {
				"lengthMenu": "",
				"processing": '<div  class = "loading-message loading-message-boxed"><?php echo $this->Html->image("loading-spinner-grey.gif"); ?><span>&nbsp;&nbsp;Operation en cours...</span></div>',
				"sInfo":'',
				"sInfoEmpty": "",
				"zeroRecords" : 'aucune demande trouvée' 
			},
			"data" : data.data,
			"sort": true,
			"filter": false,
			"columns": [
				{
					title:  '<input name="select_all" value="1" type="checkbox">',
					data: null,
					sortable: false
				},				
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
					title: '<?php echo __d('request_managment', 'Date de demande'); ?>',
					data: 'Request.event_date',
					sortable: true
				},
				{
					title:  '<?php echo __d('request_managment', 'Demandeur'); ?>',
					data: null,
					sortable: false
				}
			],
			"columnDefs": [
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
						return (data.Request.requester_type == 'natural')? 'Physique' : 'Morale';
					}
				},{
				'targets': [0],
				'className': 'text-center',
				'render': function (data, type, full, meta){
					return '<input type="checkbox">';
				}
				},{
				"targets": [4],
				render: function (e, type, data, meta)
				{	

					return (data.Request.requester_type == 'natural')? data.Counselor.first_name+' '+data.Counselor.last_name : data.Company.name;
				}
			}],
			'order': [1, 'asc'],
			'rowCallback': function(row, data, dataIndex){
				// Get row ID
				var rowId = data.Request.id;

				// If row ID is in the list of selected row IDs
				if($.inArray(rowId, EditMeetingRequestsCrud.rows_selected) !== -1){
					$(row).find('input[type="checkbox"]').prop('checked', true);
					$(row).addClass('selected');
				}
			},
			"drawCallback": function( settings ) {
				EditMeetingRequestsCrud.updateDataTableSelectAllCtrl();
			}
	    });

		EditMeetingRequestsCrud.datagrid.on('drawCallback', function(){

		})
	},
	updateDataTableSelectAllCtrl: function(){

		var $table             = $('#pending_request_datagrid');
		var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
		var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
		var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);

		// If none of the checkboxes are checked
		if($chkbox_checked.length === 0){
			chkbox_select_all.checked = false;
			
			if('indeterminate' in chkbox_select_all){
				chkbox_select_all.indeterminate = false;
			}

		// If all of the checkboxes are checked
		} else if ($chkbox_checked.length === $chkbox_all.length){
		chkbox_select_all.checked = true;
			
			if('indeterminate' in chkbox_select_all){
				chkbox_select_all.indeterminate = false;
			}
		// If some of the checkboxes are checked
		} else 
		{
			chkbox_select_all.checked = true;
			if('indeterminate' in chkbox_select_all){
				chkbox_select_all.indeterminate = true;
			}
		}
	}
};

var EditMeetingMembersCrud = {
	schujuled_for_meeting : [],
	datagrid : false,
	rows_selected : [],
	getData : function(){
		$('#MeetingMembersEditDialog').trigger('dialogLoader', 'show');
		$.ajax(
		{
			url : '<?php echo $this->Html->url(array('plugin' => 'user_managment' ,'controller' => 'users', 'action' => 'get_datagrid_data', 'ext' => 'json')); ?>',
			type: "POST",
			data : {'filter' : {'Role.alias' : 'commission_member'}, 'length' : 100},
			success:function(response, textStatus, jqXHR) 
			{
				EditMeetingMembersCrud.init(response);
				EditMeetingMembersCrud.updateDataTableSelectAllCtrl();
				$('#MeetingMembersEditDialog').trigger('dialogLoader', 'hide');

			}
		});
	},
	init : function(data){
		
		if(EditMeetingMembersCrud.datagrid)
		{
			EditMeetingMembersCrud.datagrid.destroy();
		}

		EditMeetingMembersCrud.rows_selected = EditMeetingMembersCrud.schujuled_for_meeting;	

	    EditMeetingMembersCrud.datagrid = $('#commission_members_datagrid').DataTable({
	        "processing": true,
	        "serverSide": false,
	        "language": {
				"lengthMenu": "",
				"processing": '<div  class = "loading-message loading-message-boxed"><?php echo $this->Html->image("loading-spinner-grey.gif"); ?><span>&nbsp;&nbsp;Operation en cours...</span></div>',
				"sInfo":'',
				"sInfoEmpty": "",
				"zeroRecords" : "aucune membre de commission n'a été trouvé"
			},
			"data" : data.data,
			"sort": true,
			"filter": false,
			"columns": [
				{
					title:  '<input name="select_all" value="1" type="checkbox">',
					data: null,
					sortable: false
				},				
				{
					title: '<?php echo __d('request_managment', 'Prénom'); ?>',
					data: 'User.first_name',
					sortable: true
				},
				{
					title: '<?php echo __d('request_managment', 'Nom'); ?>',
					data: 'User.last_name',
					sortable: true,
				},
				{
					title: '<?php echo __d('request_managment', 'Service'); ?>',
					data: 'Service.name',
					sortable: true,
				}
			],
			"columnDefs": [{
				'targets': [0],
				'className': 'text-center',
				'render': function (data, type, full, meta){
					return '<input type="checkbox">';
				}
			}],
			'order': [1, 'asc'],
			'rowCallback': function(row, data, dataIndex){
				// Get row ID
				var rowId = data.User.id;

				// If row ID is in the list of selected row IDs
				if($.inArray(rowId, EditMeetingMembersCrud.rows_selected) !== -1){
					$(row).find('input[type="checkbox"]').prop('checked', true);
					$(row).addClass('selected');
				}
			},
			"drawCallback": function( settings ) {
				// Update state of "Select all" control
				EditMeetingMembersCrud.updateDataTableSelectAllCtrl();
			}
	    });	
	},
	updateDataTableSelectAllCtrl: function(){

		var $table             = $('#commission_members_datagrid');
		var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
		var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
		var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);

		// If none of the checkboxes are checked
		if($chkbox_checked.length === 0){
			chkbox_select_all.checked = false;
			
			if('indeterminate' in chkbox_select_all){
				chkbox_select_all.indeterminate = false;
			}

		// If all of the checkboxes are checked
		} else if ($chkbox_checked.length === $chkbox_all.length){
		chkbox_select_all.checked = true;
			
			if('indeterminate' in chkbox_select_all){
				chkbox_select_all.indeterminate = false;
			}
		// If some of the checkboxes are checked
		} else 
		{
			chkbox_select_all.checked = true;
			if('indeterminate' in chkbox_select_all){
				chkbox_select_all.indeterminate = true;
			}
		}
	}
};

jQuery(document).ready(function() {
	meetingCrud.init();
	FormWizard.init();

	$('input[type=radio][name=show_limit]').change(function() {
        meetingCrud.datagrid.ajax.reload();
	});

	$(document).on('click', '#request_datagrid tbody input[type="checkbox"]', function(e){

      var $row = $(this).closest('tr');

      // Get row data
      var data = WizardRequestsCrud.datagrid.row($row).data();
      // Get row ID
      var rowId = data.Request.id;

      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, WizardRequestsCrud.rows_selected);

      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         WizardRequestsCrud.rows_selected.push(rowId);

      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         WizardRequestsCrud.rows_selected.splice(index, 1);
      }

      if(this.checked){
         $row.addClass('selected');
      } else {
         $row.removeClass('selected');
      }

      // Update state of "Select all" control
      WizardRequestsCrud.updateDataTableSelectAllCtrl();

      // Prevent click event from propagating to parent
      e.stopPropagation();
	});

	$(document).on('click', '#wizard_commission_members_datagrid tbody input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');

      // Get row data
      var data = WizardMembersCrud.datagrid.row($row).data();

      // Get row ID
      var rowId = data.User.id;

      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, WizardMembersCrud.rows_selected);

      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         WizardMembersCrud.rows_selected.push(rowId);

      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         WizardMembersCrud.rows_selected.splice(index, 1);
      }

      if(this.checked){
         $row.addClass('selected');
      } else {
         $row.removeClass('selected');
      }

      // Update state of "Select all" control
      WizardMembersCrud.updateDataTableSelectAllCtrl();

      // Prevent click event from propagating to parent
      e.stopPropagation();
	});
   // Handle click on "Select all" control
   $(document).on('click', 'input[name="select_all"]', function(e){
	
		if(this.checked){
			$(this).closest('table').find('input[type="checkbox"]:not(:checked)').trigger('click');
		} else {
			$(this).closest('table').find('input[type="checkbox"]:checked').trigger('click');
		}

    	// Prevent click event from propagating to parent
		e.stopPropagation();
   });

 	$('#meeting_datagrid tbody').on('click', '.btn-open', function(e){
 		meetingCrud.showDetail(this);
 		e.preventDefault();
 	});

 	$('#meeting_datagrid tbody').on('click', '.btn-print-invitations', function(e){
	        var tr = $(this).closest('tr');
	        var row = meetingCrud.datagrid.row( tr ).data();
	 		location.href = Croogo.basePath+'/admin/request_managment/meetings/print_meeting_invitations/'+row.Meeting.id;
 		e.preventDefault();
 	});


 	$('#meeting_datagrid tbody').on('click', '.btn-print-meeting-pv', function(e){
	        var tr = $(this).closest('tr');
	        var row = meetingCrud.datagrid.row( tr ).data();
	 		location.href = Croogo.basePath+'/admin/request_managment/meetings/print_meeting_pv/'+row.Meeting.id;
 		e.preventDefault();
 	});

 	$('#meeting_datagrid tbody').on('click', '.btn-edit-meeting-date', function(e){
 		var tr = $(this).closest('tr');
	    var row = meetingCrud.datagrid.row( tr ).data();
	    $('#EditMeetingEventDate').data('event_date', row.Meeting.event_date);
	    $('#EditMeetingName').val(row.Meeting.name);
	    $('#EditMeetingDescription').val(row.Meeting.description);
	    $('#MeetingEditDialog').find('.meeting_id').val(row.Meeting.id);

 		$('#MeetingEditDialog').modal('show');
 		e.preventDefault();
 	});

 	$('#meeting_datagrid tbody').on('click', '.btn-edit-meeting-members', function(e){
 		var tr = $(this).closest('tr');
	    var row = meetingCrud.datagrid.row( tr ).data();
	    EditMeetingMembersCrud.schujuled_for_meeting = [];
	    $(row.MeetingsUser).each(function(key, meeting_user){
	    	 EditMeetingMembersCrud.schujuled_for_meeting.push(meeting_user.user_id);
	    });
	    
	    $('#MeetingMembersEditDialog').find('.meeting_id').val(row.Meeting.id);
 		$('#MeetingMembersEditDialog').modal('show');
 		e.preventDefault();
 	});

 	$('#meeting_datagrid tbody').on('click', '.btn-edit-meeting-requests', function(e){
  		var tr = $(this).closest('tr');
	    var row = meetingCrud.datagrid.row( tr ).data();
	    EditMeetingRequestsCrud.meeting_id = row.Meeting.id;
	    EditMeetingRequestsCrud.schujuled_for_meeting = [];
	    $(row.MeetingsRequest).each(function(key, meeting_request){
	    	 EditMeetingRequestsCrud.schujuled_for_meeting.push(meeting_request.request_id);
	    });
	     $('#MeetingRequestsEditDialog').find('.meeting_id').val(row.Meeting.id);
 		$('#MeetingRequestsEditDialog').modal('show');
 		e.preventDefault();
 	});

	//datagrid ajax form 
	$('.meetings').on('click', '.btn-delete', function(e)
	{
		var id = $(this).attr("action-id");
		var tr = $(this).closest("tr");
		
		if(confirm("<?php echo __d('request_managment', 'Vous êtes sûr de vouloir supprimer cette réunion?'); ?>")){
			meetingCrud.deleteRow(id, tr);
		}
		
		e.preventDefault();

		return false;
	});

	//datagrid ajax form 
	$('.meetings').on('click', '.btn-close-meeting', function(e)
	{
 		var tr = $(this).closest('tr');
	    var row = meetingCrud.datagrid.row( tr ).data();
		var id = row.Meeting.id;
		
		if(confirm("<?php echo __d('request_managment', 'Vous êtes sûr de vouloir clôturer cette réunion?'); ?>")){
			meetingCrud.close(id);
		}
		
		e.preventDefault();

		return false;
	});
	
	$('.meetings').on('click', '.btn-archive_meeting', function(e)
	{
 		var tr = $(this).closest('tr');
	    var row = meetingCrud.datagrid.row( tr ).data();
		var id = row.Meeting.id;
		
		if(confirm("<?php echo __d('request_managment', 'Vous êtes sûr de vouloir valider vos décisions?'); ?>")){
			meetingCrud.archive(id);
		}
		
		e.preventDefault();

		return false;
	});

	//datagrid ajax add form 
	$('.meetings').on('click', '.btn-aprove-all', function(e)
	{		
 		var tr = $(this).closest('tr');
	    var row = meetingCrud.datagrid.row( tr ).data();
		var id = row.Meeting.id;
		
		if(confirm("<?php echo __d('request_managment', 'Vous êtes sûr de vouloir approuver l\'avis de la commission de toutes les demandes d\'agréments de cette réunion?'); ?>")){
			meetingCrud.aproveAll(id);
		}
		
		e.preventDefault();

		return false;
	});

	//datagrid ajax add form 
	$('#add_meeting_form').submit(function(e)
	{
		
		var postData = $(this).serializeArray();

		$(postData).each(function(key, datum){
			
			if(datum.name == "data[Meeting][event_date]")
			{
				postData[key]['value'] = moment(postData[key]['value'], 'DD-MM-YYYY hh:mm:ss').format('YYYY-MM-DD hh:mm:ss');
			}
		});

		$(WizardMembersCrud.rows_selected).each(function(key, id){
			postData.push({name : "data[MeetingsUser][][user_id]", value : id});
		});

		$(WizardRequestsCrud.rows_selected).each(function(key, id){
			postData.push({name : "data[MeetingsRequest][][request_id]", value : id});
		});
		meetingCrud.addRow(postData);
		e.preventDefault();

		return false;
	});


	$(document).on('click', '.btn-edit', function(event){
		$('#edit_meeting_form').find('input, select').val('');
		var data = meetingCrud.datagrid.row($(this).closest('tr')).data();

		$('#edit_meeting_form input, #edit_meeting_form select').each(function(){
			
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

		$('#MeetingEditDialog').modal('show');
		
		event.preventDefault();
		return false;
	});

	$('#MeetingFilter').on('click', 'a', function (e) {
	  	var field_name =  $(this).parent().attr('data-value')
	  	var field_label = $(this).text();
	  	$(this).closest('.datagrid-search').find('.hidden').val(field_name);
	  	$(this).closest('.datagrid-search').find('.selected-label').text(' ' +field_label);
	  	$(this).closest('.datagrid-search').find('input[type = search]').val("");
	  	$(this).closest('.datagrid-search').find('input[type = search]').attr('placeholder', 'Chercher par '+field_label);
	});

	$('#MeetingFilter .search').on('click', '.btn', function (e) {
	  	meetingCrud.datagrid.ajax.reload();
	});

	$('#MeetingEditDialog').on('hidden.bs.modal', function (e) {
	  	
	});

	$('#MeetingAddDialog').on('shown.bs.modal', function (e) {
  		WizardRequestsCrud.getData();
		WizardMembersCrud.getData();
	  	$('#AddMeetingName').val("Réunion de la Commission Nationale de Conseil du {1}");
	  	$('#AddMeetingEventDate').val('');
	});

	$('#MeetingAddDialog').on('hidden.bs.modal', function (e) {
		FormWizard.wizardContent.smartWizard('goToStep', 1);
	});

	$(document).on('dialogLoader', '.modal', function(e, action){

		if(action == 'hide')
		{
			$(this).find('.loading').hide();
		}
		else
		{
			$(this).find('.loading').show();
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

	
	$(document).on('click', '#pending_request_datagrid tbody input[type="checkbox"]', function(e){

      var $row = $(this).closest('tr');

      // Get row data
      var data = EditMeetingRequestsCrud.datagrid.row($row).data();
      // Get row ID
      var rowId = data.Request.id;

      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, EditMeetingRequestsCrud.rows_selected);

      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         EditMeetingRequestsCrud.rows_selected.push(rowId);

      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         EditMeetingRequestsCrud.rows_selected.splice(index, 1);
      }

      if(this.checked){
         $row.addClass('selected');
      } else {
         $row.removeClass('selected');
      }

      // Update state of "Select all" control
      EditMeetingRequestsCrud.updateDataTableSelectAllCtrl();

      // Prevent click event from propagating to parent
      e.stopPropagation();
	});

	$(document).on('click', '#commission_members_datagrid tbody input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');

      // Get row data
      var data = EditMeetingMembersCrud.datagrid.row($row).data();

      // Get row ID
      var rowId = data.User.id;

      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, EditMeetingMembersCrud.rows_selected);

      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         EditMeetingMembersCrud.rows_selected.push(rowId);

      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         EditMeetingMembersCrud.rows_selected.splice(index, 1);
      }

      if(this.checked){
         $row.addClass('selected');
      } else {
         $row.removeClass('selected');
      }

      // Update state of "Select all" control
      EditMeetingMembersCrud.updateDataTableSelectAllCtrl();

      // Prevent click event from propagating to parent
      e.stopPropagation();
	});

	//datagrid ajax add form 
	$('#edit_meeting_form').submit(function(e)
	{
		var mysql_date = false; 
		var postData = $(this).serializeArray();

		$(postData).each(function(key, datum){
			
			if(datum.name == "data[Meeting][event_date]")
			{
				mysql_date = moment(postData[key]['value'], 'DD-MM-YYYY hh:mm:ss').format('YYYY-MM-DD hh:mm:ss');
				postData[key]['value'] = mysql_date;
				
			}
		});

		var formURL = $('#edit_meeting_form').attr("action");
		$('#MeetingEditDialog').trigger('dialogLoader', 'show');
		
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postData,
			success:function(response, textStatus, jqXHR) 
			{
				if(response.result == 'success')
				{
					toastr.success(response.message);
					meetingCrud.datagrid.ajax.reload();
				}
				else
				{
					toastr.error(response.message); 
				}
				$('#MeetingEditDialog').trigger('dialogLoader', 'hide');
				$('#MeetingEditDialog').modal('hide'); 
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
				$('#MeetingEditDialog').trigger('dialogLoader', 'hide');
				toastr.error("<?php echo __d('request_managment', 'An error occured please try again!'); ?>");
			}
		});

		e.preventDefault();
		return false;
	});
	//datagrid ajax add form 
	$('#edit_meeting_members_form').submit(function(e)
	{
	    if(EditMeetingMembersCrud.rows_selected.length == 0)
   		{
   			$('#MeetingMembersEditDialog').find('.alert').show();
   			e.preventDefault();
   			return false;
   		}
   		else
   		{
   			$('#MeetingMembersEditDialog').find('.alert').hide();
   		}

		var postData = $(this).serializeArray();

		$(EditMeetingMembersCrud.rows_selected).each(function(key, id){
			postData.push({name : "data[MeetingsUser][][user_id]", value : id});
		});

		var formURL = $('#edit_meeting_members_form').attr("action");
		$('#MeetingMembersEditDialog').trigger('dialogLoader', 'show');
		
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postData,
			success:function(response, textStatus, jqXHR) 
			{
				if(response.result == 'success')
				{
					toastr.success(response.message);
					meetingCrud.datagrid.ajax.reload();
				}
				else
				{
					toastr.error(response.message); 
				}
				$('#MeetingMembersEditDialog').trigger('dialogLoader', 'hide');
				$('#MeetingMembersEditDialog').modal('hide'); 
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
				$('#MeetingMembersEditDialog').trigger('dialogLoader', 'hide');
				toastr.error("<?php echo __d('request_managment', 'An error occured please try again!'); ?>");
			}
		});

		e.preventDefault();
		return false;
	});	
	//datagrid ajax add form 
	$('#edit_meeting_requests_form').submit(function(e)
	{
		var postData = $(this).serializeArray();

	    if(EditMeetingRequestsCrud.rows_selected.length == 0)
   		{
   			$('#MeetingRequestsEditDialog').find('.alert').show();
   			e.preventDefault();
   			return false;
   		}
   		else
   		{
   			$('#MeetingRequestsEditDialog').find('.alert').hide();
   		}

		$(EditMeetingRequestsCrud.rows_selected).each(function(key, id){
			postData.push({name : "data[MeetingsRequest][][request_id]", value : id});
		});

		var formURL = $('#edit_meeting_requests_form').attr("action");
		$('#MeetingRequestsEditDialog').trigger('dialogLoader', 'show');
		
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postData,
			success:function(response, textStatus, jqXHR) 
			{
				if(response.result == 'success')
				{
					toastr.success(response.message);
					meetingCrud.datagrid.ajax.reload();
				}
				else
				{
					toastr.error(response.message); 
				}
				$('#MeetingRequestsEditDialog').trigger('dialogLoader', 'hide');
				$('#MeetingRequestsEditDialog').modal('hide'); 
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
				$('#MeetingRequestsEditDialog').trigger('dialogLoader', 'hide');
				toastr.error("<?php echo __d('request_managment', 'An error occured please try again!'); ?>");
			}
		});

		e.preventDefault();
		return false;
	});

	$('#MeetingEditDialog').on('shown.bs.modal', function (e) {
	  	var fr_date = moment($('#EditMeetingEventDate').data('event_date'), 'YYYY-MM-DD hh:mm:ss').format('DD-MM-YYYY hh:mm:ss');
	  	 $('#EditMeetingEventDate').data("DateTimePicker").useCurrent(false);
	  	$('#EditMeetingEventDate').val(fr_date);
	});
	$('#MeetingMembersEditDialog').on('shown.bs.modal', function (e) {
		EditMeetingMembersCrud.getData();
	});	

	$('#MeetingRequestsEditDialog').on('shown.bs.modal', function (e) {
		EditMeetingRequestsCrud.getData();
	});
});

<?php $this->Html->scriptEnd(); ?></script>

<div class="meetings index">
	<div class="datagrid" id="meeting_datagrid_container">
		<div class="datagrid-toolbar">
			<div class="col-xs-12 col-sm-6 col-md-4 no-padding">
				<!-- Button trigger modal -->

<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'add', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'meetings'))) {?>
		<?php  echo $this->Croogo->adminAction(

				__d('request_managment', 'Ajouter une réunion'), '#',

				array('button' => 'primary', 'data-toggle' => 'modal', 'data-target' =>'#MeetingAddDialog')

			);?>
<?php  } ?>		
			</div>
			<div class="col-xs-12 col-md-4 col-sm-6 no-padding">

				<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'aprove_all_meeting_requests', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'meetings'))) {?>
				<div class="form-group" style = "margin-bottom:0px;" >
					<div class="clip-radio radio-primary" style = "margin-bottom:0px;">
						<input type="radio" value="all" name="show_limit" id="show_all" >
						<label for="show_all">
							Réunions archivées
						</label>
						<input type="radio" value="not_all" name="show_limit" id="show_not_all" checked = "true" >
						<label for="show_not_all">
							Réunions en instance 
						</label>
					</div>
				</div>
				<?php }else{ ?>
				<div class="form-group" style = "margin-bottom:0px;" >
					<div class="clip-radio radio-primary" style = "margin-bottom:0px;">
						<input type="radio" value="all" name="show_limit" id="show_closed" >
						<label for="show_closed">
							Réunions traitées
						</label>
						<input type="radio" value="not_all" name="show_limit" id="show_not_closes" checked = "true" >
						<label for="show_not_closes">
							Réunions en instance 
						</label>
					</div>
				</div>				
				<?php }?>

			</div>
			<div class="col-xs-12 col-md-4 col-sm-12 no-padding">
			  	<div class="datagrid-search" id = "MeetingFilter">
					<div class="input-group">
						<div class="input-group-btn selectlist" data-resize="auto" data-initialize="selectlist">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
								<span class="selected-label">Numéro</span>
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
					 		</button>
							<ul class="dropdown-menu" role="menu">		
								<li data-value="Meeting.id">	
									<a href="#">Numéro</a>
								</li>																						
								<li data-value="Meeting.event_date">
									<a href="#">Date de réunion</a>
								</li>												
							</ul>
							<input class="hidden hidden-field" name="column" readonly="readonly" aria-hidden="true" type="text" value = "Meeting.id">
						</div>
						<div class="search input-group">
							<input type="search" class="form-control" placeholder="<?php  echo __d('request_managment', 'Chercher par Numéro');  ?>"/>
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
		<table id="meeting_datagrid" class="display table-bordered"></table>
	</div>
</div>
<div class="modal fade" id="MeetingAddDialog" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="MeetingEdition" backdrop = "static">
	<div class="modal-dialog modal-lg">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close" style = "position: absolute;right: 8px;z-index: 1;">
		<span aria-hidden="true">×</span>
	</button>
		<div class="modal-content">
		<?php  echo $this->Form->create('Meeting',
					array('url' => array('action' => 'add', 'ext' => 'json'), 

						'id' => 'add_meeting_form' , 'class' => 'smart-wizard' )

					);?>
				<div id="wizard" class="swMain">
					<!-- start: WIZARD SEPS -->
					<ul class="anchor">
						<li>
							<a href="#step-1" class="selected" isdone="1" rel="1">
								<div class="stepNumber">
									1
								</div>
								<span class="stepDesc"><small> Informations générales </small></span>
							</a>
						</li>
						<li>
							<a href="#step-2" class="disabled" isdone="0" rel="2">
								<div class="stepNumber">
									2
								</div>
								<span class="stepDesc"> <small> Demandes d'agrément </small></span>
							</a>
						</li>
						<li>
							<a href="#step-3" class="disabled" isdone="0" rel="3">
								<div class="stepNumber">
									3
								</div>
								<span class="stepDesc"> <small> Membres de commission </small> </span>
							</a>
						</li>
					</ul>
					<div id="step-1" class="content">
						<div class="modal-body">
						<?php
							$this->Form->inputDefaults(array('label' => false, 'class' => 'form-control'));
							echo $this->Form->input('event_date', array(
								'label' => __d('request_managment', 'Date de réunion'),
								'id' => 'AddMeetingEventDate',
								'type' => 'text',
								'class' => 'datetimepicker',
								'minDate' => 0,
								'required' => true
							));
							echo $this->Form->input('name', array(
								'label' => __d('request_managment', 'Sujet de réunion'),
								'id' => 'AddMeetingName',
								'type' => 'text',
								'required' => true,
								'value' => "Réunion de la Commission Nationale de Conseil du {1}"
							));
							echo $this->Form->input('message', array(
								'label' => __d('request_managment', 'Description'),
								'id' => 'AddMeetingDescription',
								'value' => "J’ai l’honneur de vous demander de bien vouloir participer à la réunion de la Commission Nationale de Conseil Agricole qui aura lieu le {1} Cette réunion portera sur l'examen des dossiers ci-dessous : {2}",
								'required' => true,
								'type' => 'textarea'
							));
						?>
						{1} : date de la réunion; {2} liste des demandes d'agrément à traiter
						</div>
						<div class="modal-footer">
							<button class="btn btn-primary btn-o next-step btn-wide pull-right">
								Suivant <i class="fa fa-arrow-circle-right"></i>
							</button>
							<div class = "clear"></div>
						</div>
					</div>
					<div id="step-2" class="content">
						<div class="modal-body">
							<div class="alert alert-danger">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
								Séléctionner svp les demandes d'agrément à traiter!
								
							</div>
							<table id="request_datagrid" class="display table-bordered"></table>
						</div>
						<div class="modal-footer">
							<button class="btn btn-primary btn-o next-step btn-wide pull-right">
								Suivant <i class="fa fa-arrow-circle-right"></i>
							</button>
							<button class="btn btn-primary btn-o back-step btn-wide pull-right">
								<i class="fa fa-arrow-circle-left"></i> Précédent
							</button>
							<div class = "clear"></div>
						</div>
					</div>
					<div id="step-3" class="content">
						<div class="modal-body">
							<div class="loading loader"  data-initialize="loader">
					  			<?php echo $this->Html->image("loading-spinner-grey.gif"); ?><span>&nbsp;&nbsp;Operation en cours...</span>
					  		</div>
							<div class="alert alert-danger">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
								Selectionner svp les membres de la commission à inviter
							</div>
							<table id="wizard_commission_members_datagrid" class="display table-bordered"></table>
						</div>
						<div class="modal-footer">
							<button class="btn btn-primary btn-o finish-step btn-wide pull-right">
								Terminer <i class="fa fa-arrow-circle-right"></i>
							</button>
							<button class="btn btn-primary btn-o back-step btn-wide pull-right">
								<i class="fa fa-arrow-circle-left"></i> Précédent
							</button>
							<div class = "clear"></div>
						</div>
					</div>
				</div>
				<input type = "submit" class = "no-display" id = "add_meeting_submit_btn">
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>

<div class="modal fade" id="MeetingEditDialog" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="MeetingEdition" backdrop = "static">
	
		<?php  
			echo $this->Form->create('Meeting',
				array('url' => array('controller' => 'meetings', 'action' => 'edit', 'ext' => 'json'), 
				'id' => 'edit_meeting_form'));
			echo $this->Form->hidden('id', array('class' => 'meeting_id'));
		?> 
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">
						<?php  echo __d('user_managment', 'Close');  ?>					</span>
				</button>

				<h4 class="modal-title" style = "display:inline-block">
					<?php  echo __d('user_managment', "Edition d'une réunion");  ?>				
				</h4>
	  		</div>
			<div class="modal-body">
			<?php
				$this->Form->inputDefaults(array('label' => false, 'class' => 'form-control'));
				echo $this->Form->input('event_date', array(
					'label' => __d('request_managment', 'Date de la réunion'),
					'id' => 'EditMeetingEventDate',
					'type' => 'text',
					'class' => 'datetimepicker',
					'minDate' => 0,
					'required' => true
				));
				echo $this->Form->input('name', array(
					'label' => __d('request_managment', 'Sujet de la réunion'),
					'id' => 'EditMeetingName',
					'type' => 'text',
					'required' => true,
					'value' => ''
				));
				echo $this->Form->input('description', array(
					'label' => __d('request_managment', 'Description'),
					'id' => 'EditMeetingDescription',
					'value' => '',
					'required' => true,
					'type' => 'textarea'
				));
			?>
			{1} : date de la réunion; {2} liste des demandes d'agrément à traiter
			</div>
	  		<div class="loader loading"  data-initialize="loader">
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
	<?php echo $this->Form->end(); ?>
</div><!-- /.modal -->

<div class="modal fade" id="MeetingMembersEditDialog" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="MeetingEdition" backdrop = "static">
	
		<?php  echo $this->Form->create('Meeting',
			array('url' => array('controller' => 'meetings', 'action' => 'update_meeting_members', 'ext' => 'json'), 

			'id' => 'edit_meeting_members_form')
		);
		echo $this->Form->hidden('id', array('class' => 'meeting_id'));
		?> 
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">
						<?php  echo __d('user_managment', 'Close');  ?>					</span>
				</button>

				<h4 class="modal-title" style = "display:inline-block">
					<?php  echo __d('user_managment', "Edition des membres de la commission invités à la réunion");  ?>				
				</h4>
	  		</div>
			<div class="modal-body">
				<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
					Selectionner svp les membres de la commission
				</div>
				<table id="commission_members_datagrid" class="display table-bordered"></table>
			</div>
	  		<div class="loader loading"  data-initialize="loader">
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
	<?php echo $this->Form->end(); ?>
</div><!-- /.modal -->

<div class="modal fade" id="MeetingRequestsEditDialog" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="MeetingEdition" backdrop = "static">
		<?php  
		echo $this->Form->create('Meeting',
			array('url' => array('controller' => 'meetings', 'action' => 'update_meeting_requests', 'ext' => 'json'), 

			'id' => 'edit_meeting_requests_form')

		);
		echo $this->Form->hidden('id', array('class' => 'meeting_id'));
		?> 	
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">
						<?php  echo __d('user_managment', 'Close');  ?>					</span>
				</button>

				<h4 class="modal-title" style = "display:inline-block">
					<?php  echo __d('user_managment', "Demandes à traitées durant la réunion");  ?>				
				</h4>
	  		</div>
			<div class="modal-body">
				<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
					Séléctionner svp les demandes d'agrément à traiter!
					
				</div>
				<table id="pending_request_datagrid" class="display table-bordered"></table>
			</div>
	  		<div class="loader loading"  data-initialize="loader">
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
	<?php echo $this->Form->end(); ?>
</div><!-- /.modal -->
