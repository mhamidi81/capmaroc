<?php echo $this->Html->script(array(
	'../plugins/fullcalendar/fullcalendar.min',
	'../plugins/fullcalendar/lang-all',
	'../plugins/moment/moment.min',
	'../plugins/moment/fr'
	), array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->css(array('../plugins/fullcalendar/fullcalendar.min'), array('block' => 'scriptBottom')); ?>
<style type="text/css">
.lblmodel {
	width: 10% !important;
	
}
.loading-message{
	z-index :10000 !important;
}

</style>
<div class="app-content">
	<div class="wrap-content container" id="container">
		<!-- start: CALENDAR -->
		<div class="container-fluid container-fullw bg-white">
			<div class="row">
				<div class="col-sm-12 space20">
					<a href="#" class="btn btn-primary btn-o add-event"><i class="fa fa-plus"></i> Ajouter évenement</a>
				</div>
				<div class="col-sm-12">
					<div id='full-calendar'></div>
				</div>
			</div>
		</div>
		<!-- end: CALENDAR -->
		<!-- start: EVENTS ASIDE -->
		<div class="modal fade modal-aside horizontal right events-modal" id="editEventDialog" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog modal-sm">
				<div class="modal-content">
					<?php  echo $this->Form->create('Event',
					array('url' => array('action' => 'edit','admin'=>true, 'ext'=>'json' ), 
						'id' => 'edit_event_form')
					);?>

						<div class="modal-body">
							<div class="form-group hidden">
								<?php
								echo $this->Form->input('id', array(
								 'type' => 'text',
								 'id' => 'event-id',
								 'readonly' =>'readonly',
								));?>
							</div>
							<div class="form-group">
								<label>
									Titre
								</label>
								<?php
								echo $this->Form->input('titre', array(
								 'div' => false,
								 'label' => false,
								 'type' => 'text',
								 'id' => 'event-title',
								 'class' => 'form-control text-large',
								 'required' => true,
								));?>
							</div>
							<div class="form-group">
								<label>
									De
								</label>
								<span class="input-icon">
									<?php
									echo $this->Form->input('from', array(
									 'div' => false,
									 'label' => false,
									 'type' => 'text',
									 'id' => 'event-from',
									 'class' => 'form-control dateTimePicker',
									 'required' => true,
									));?>
									<i class="ti-calendar"></i> 
								</span>
							</div>
							<div class="form-group">
								<label>
									À
								</label>
								<span class="input-icon">
									<?php
									echo $this->Form->input('to', array(
									 'div' => false,
									 'label' => false,
									 'type' => 'text',
									 'id' => 'event-to',
									 'class' => 'form-control dateTimePicker',
									 'required' => true,
									));?>
									<i class="ti-calendar"></i> 
								</span>
							</div>
							<div class="form-group">
								<label>
									Description
								</label>
								<?php
								echo $this->Form->input('description', array(
								 'div' => false,
								 'label' => false,
								 'type' => 'textarea',
								 'id' => 'event-description',
								 'class' => 'form-control ',
								 'required' => true,
								));?>
							</div>
						</div>
						<div class="loader" data-initialize="loader">
							<?php echo $this->Html->image("loading-spinner-grey.gif"); ?>
							<span>&nbsp;&nbsp;Enregistrement en cours...</span>
						</div>
						<div class="modal-footer">
							<?php 
							echo $this->Html->link(__d('message_managment', 'Supprimer'), '#', array(
								'class' => 'btn btn-danger btn-o ',
								'data-dismiss' => 'modal',
								'id' => 'deleteEvent'
							)); 
							echo $this->Form->button(__d('message_managment', 'Enregistrer'), array(
								'class' => 'btn btn-primary btn-o'
							));
							?>
						</div>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
		
		<div class="modal fade events-modal" tabindex = "false" id="addEventDialog"  role="dialog" aria-hidden="true" aria-labelledby="EventEdition" data-backdrop = "static">
		 
			<?php  echo $this->Form->create('Event',
					array('url' => array('action' => 'add','admin'=>true, 'ext'=>'json'), 
						'id' => 'add_event_form')
					);?>
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true">&times;</span>
							<span class="sr-only">
								<?php  echo __d('calendar_managment', 'Close');  ?>
							</span>
						</button>
						<h4 class="modal-title">
							<?php  echo __d('calendar_managment', 'Nouvel évenement');  ?>
						</h4>
					</div>
					<div class="modal-body row">
					
						<div class="form-group col-sm-12">
							<label>
								Titre
							</label>
							<?php
							echo $this->Form->input('titre', array(
							 'div' => false,
							 'label' => false,
							 'type' => 'text',
							 'class' => 'form-control',
							 'required' => true,
							));?>
						</div>
						<div class="form-group col-sm-6">
							<label>
								De
							</label>
							<span class="input-icon">
								<?php
								echo $this->Form->input('from', array(
								 'div' => false,
								 'label' => false,
								 'type' => 'text',
								 'class' => 'form-control dateTimePicker',
								 'required' => true,
								));?>
								<i class="ti-calendar"></i> 
							</span>
						</div>
						<div class="form-group col-sm-6">
							<label>
								À
							</label>
							<span class="input-icon">
								<?php
								echo $this->Form->input('to', array(
								 'div' => false,
								 'label' => false,
								 'type' => 'text',
								 'class' => 'form-control dateTimePicker',
								 'required' => true,
								));?>
								<i class="ti-calendar"></i> 
							</span>
						</div>
						<div class="form-group col-sm-12">
							<label>
								Description
							</label>
							<?php
							echo $this->Form->input('description', array(
							 'div' => false,
							 'label' => false,
							 'type' => 'textarea',
							 // 'id' => 'MessageTitle',
							 'required' => true,
							 'rows'=>"6"
							));?>

						</div>
					</div>
					<div class="loader" data-initialize="loader">
						<?php echo $this->Html->image("loading-spinner-grey.gif"); ?>
						<span>&nbsp;&nbsp;Enregistrement en cours...</span>
					</div>
					<div class="modal-footer">
						<?php 
						echo $this->Html->link(__d('message_managment', 'Annuler'), '#', array(
							'class' => 'btn btn-danger', 'data-dismiss' => 'modal'
						)); 
						echo $this->Form->button(__d('message_managment', 'Ajouter'), array(
							'class' => 'btn btn-primary'
						));
						?>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
			<?php echo $this->Form->end(); ?>
		</div><!-- /.modal -->		
	
	</div>
	<!-- end: EVENTS ASIDE -->
</div>

<script>
<?php  $this->Html->scriptStart(array('inline' => false, 'block' => 'scriptBottom')); ?>
$(function() {
	$('.dateTimePicker').datetimepicker({ locale:'fr', format: 'YYYY-MM-DD HH:mm', widgetPositioning: {
            horizontal: 'auto',
            vertical: 'bottom'
         } });
	
	// Ajouter Evenement
	$('#add_event_form').submit(function(e){
		
			e.preventDefault();
			var $form = $(this);
			var formURL = $form.attr("action");
			var newEvent=[{
				"title" : $("#EventTitre").val(),
				"start" : $("#EventFrom").val(),
				"end" : $("#EventTo").val(),
				"description" : $("#EventDescription").val(),
			}];
			
			// App.startPageLoading('Enregistrement en cours');
			$('#addEventDialog').trigger('dialogLoader', 'show');
			
			$.ajax({
				url : formURL,
				type: "POST",
				data : $form.serialize(),
				success:function(response) 
				{
					// App.stopPageLoading();
					$('#addEventDialog').trigger('dialogLoader', 'hide');
					
					if(response.result == 'success'){
						toastr.success(response.message);
						//recuperer l'id de l' evenement cree
						newEvent[0].id = response.id;
						
						$('#full-calendar').fullCalendar( 'addEventSource', newEvent );
					} else {
						toastr.error(response.message);
					}
					$('#addEventDialog').modal('hide');
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					// App.stopPageLoading();
					$('#addEventDialog').trigger('dialogLoader', 'show');
					$('#addEventDialog').modal('hide');
					toastr.error("<?php echo 'Une erreur s\'est produite, veuillez réessayer plus tard. !'; ?>");
				}
			});
			return false;
	});
	
	// Editer Evenement
	$('#edit_event_form').submit(function(e){
		
		e.preventDefault();
		var $form = $(this);
		var formURL = $form.attr("action");
		var current_event_id = $("#event-id").val();
		
		//Check if value found
		if(current_event_id){
 
			var event = $('#full-calendar').fullCalendar('clientEvents', current_event_id);
			event = event[0];
 
			//Set values
			event.title = $("#event-title").val();
			event.start = $("#event-from").val();
			event.end = $("#event-to").val();
			event.description = $("#event-description").val(),
		
			App.startPageLoading('Enregistrement en cours');
			// $('#editEventDialog').trigger('dialogLoader', 'show');
			
			$.ajax({
				url : formURL,
				type: "POST",
				data : $form.serialize(),
				success:function(response) 
				{
					App.stopPageLoading();
					// $('#editEventDialog').trigger('dialogLoader', 'hide');
					
					if(response.result == 'success'){
						
						toastr.success(response.message);
						$('#full-calendar').fullCalendar('updateEvent', event);
					
					} else {
						toastr.error(response.message);
					}
					$('#editEventDialog').modal('hide');
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					App.stopPageLoading();
					// $('#editEventDialog').trigger('dialogLoader', 'hide');
					$('#editEventDialog').modal('hide');
					toastr.error("<?php echo 'Une erreur s\'est produite, veuillez réessayer plus tard. !'; ?>");
				}
			}); 
		}
			return false;
	});
	
	//Supprimer evenement
	$('#deleteEvent').click(function(e){
		
		if (confirm("Etes vous sures de vouloir supprimer cet évenement ?")==true){
			
			var eventId = $("#event-id").val();
			App.startPageLoading('Suppression en cours');
			
			$.ajax({
				url : "<?php echo $this->Html->url(array(
						'plugin' => 'calendar_managment',
						'controller' => 'events',
						'action' => 'delete',
						'admin' => true, 
						'ext' => 'json')); 
					?>",
				type: "POST",
				data : { 'id' : eventId},
				success:function(response) 
				{
					App.stopPageLoading();
					
					if(response.result == 'success'){
						
						toastr.success(response.message);
						$('#full-calendar').fullCalendar( 'removeEvents' ,eventId);
					
					} else {
						toastr.error(response.message);
					}
					$('#editEventDialog').modal('hide');
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					App.stopPageLoading();
					$('#editEventDialog').modal('hide');
					toastr.error("<?php echo 'Une erreur s\'est produite, veuillez réessayer plus tard. !'; ?>");
				}
			}); 
			
		}
	});
	
	
	function getEvents(){

		App.startPageLoading('Chargement en cours');
		
		$.ajax({
			url : '<?php echo $this->Html->url(array(
					'plugin' => 'calendar_managment',
					'controller' => 'events',
					'action' => 'index',
					'admin' => true, 
					'ext' => 'json')); 
				?>',
			type: "POST",
			
			success:function(data) 
			{
				
				var events = [];

				$.each( data, function( key, value ) {
					events.push(value.Event);
				});
				App.stopPageLoading();
				$('#full-calendar').fullCalendar( 'addEventSource', events );
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
				App.stopPageLoading();
				toastr.error("<?php echo 'Une erreur s\'est produite, veuillez réessayer plus tard. !'; ?>");
			}
		});

	};
	
	//reglage des datepicker end-date-time superieur a start-date-time
	var eventInputDateHandler = function() {
		// $('#EventFrom').val('');
		// $('#EventTo').val('');
		var startInput = $('#EventFrom');
		var endInput = $('#EventTo');
			
		
		
		startInput.on("dp.change", function(e) {
			endInput.data("DateTimePicker").minDate(e.date);
		});
		endInput.on("dp.change", function(e) {
			startInput.data("DateTimePicker").maxDate(e.date);
		});
		
		$('#event-from').on("dp.change", function(e) {
			$('#event-to').data("DateTimePicker").minDate(e.date);
		});
		$('#event-to').on("dp.change", function(e) {
			$('#event-from').data("DateTimePicker").maxDate(e.date);
		});
	};
	
	//afficher Modal
	$(".add-event").off().on("click", function() {
		 eventInputDateHandler();
		$('#addEventDialog').modal();
	});
	
	//initialiser les input de modal lors de sa fermeture

	$('.modal').on('hide.bs.modal', function(event) {
		// alert();
		$(this).find('input, textarea').val('');
		// $(".dateTimePicker").data("DateTimePicker").destroy();
		
	});

	$('#full-calendar').fullCalendar({
		lang: 'fr',
		timeFormat: 'HH:mm',
		views: {
				slotLabelFormat: 'HH:mm'
		},
		buttonIcons: {
			prev: 'fa fa-chevron-left',
			next: 'fa fa-chevron-right'
		},
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		eventClick: function(calEvent, jsEvent, view) {
			
			eventInputDateHandler();
			// alert(calEvent.end);
			$("#event-id").val(calEvent.id);
			$("#event-title").val(calEvent.title);
			$("#event-from").datetimepicker({ format: 'YYYY-MM-DD HH:mm' }).data("DateTimePicker").date(calEvent.start);
			$("#event-to").datetimepicker({ format: 'YYYY-MM-DD HH:mm' }).data("DateTimePicker").date(calEvent.end);
			$("#event-description").val(calEvent.description);
			$('#editEventDialog').modal();
		}

	});
	getEvents();
	$('#addEventDialog,#editEventDialog').on('dialogLoader', function(e, action)
	{
		if(action == 'hide')
		{
			$(this).find('.loader').hide();
		}
		else
		{
			$(this).find('.loader').show();
		}
	});
});
<?php $this->Html->scriptEnd(); ?>
</script>
<!-- end: JavaScript Event Handlers for this page -->
<!-- end: CLIP-TWO JAVASCRIPTS -->
