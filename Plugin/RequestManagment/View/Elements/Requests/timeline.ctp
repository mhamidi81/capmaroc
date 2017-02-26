<div class="panel panel-white no-radius timeline_container">
	<div class="panel-heading border-bottom">
		<h4 class="panel-title">Historique</h4>
	</div>
	<div class="panel-body" style = "max-height:326px;overflow:auto;">
		<div class="timeline-body" >

			<ul class="timeline-xs margin-top-15 margin-bottom-15">
				<?php foreach ($request_statuses as $key => $request_status) { ?>	
				<li class="timeline-item success">
					<div class="margin-left-15">
						<div class="text-muted text-small">
							<?php echo date('d-m-Y h:i:s', strtotime($request_status['RequestStatus']['event_date']));?>
						</div>
						<p>
							<?php echo $request_status['Status']['name']; ?>
							<span class="text-info" href="">
								par <?php echo $request_status['AllUser']['first_name'].' '.$request_status['AllUser']['last_name'] ; ?>
							</span>
						</p>
					</div>
				</li>
				<?php } ?>
				<li class="timeline-item success">
					<div class="margin-left-15">
						<div class="text-muted text-small">
							<?php echo date('d-m-Y h:i:s', strtotime($request['Request']['event_date']));?>
						</div>
						<p>
							Attente dossier papiers
						</p>
					</div>
				</li>
			</ul>
		</div>
	</div>
</div>