<?php echo $this->Html->script(array('../plugins/Chart.js/Chart.min.js'), array('block' => 'scriptBottom'));?>
<?php 
	$userId = AuthComponent::user('id');
	$user_role = $this->CapTheme->getConnectedUserRole(); 
?>

<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'can_see_meeting_judgments', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'requests')) && !empty($meeting_request['MeetingsRequest']['judgment_id'])) {?>
		<fieldset class="meeting-judgment-container">

			<legend>Avis de la commission nationale de conseil agricole</legend>
			<p><strong>Avis : </strong><?php echo $meeting_request['Judgment']['name']; ?></p>
			<?php 
			$m_specialities = json_decode($meeting_request['MeetingsRequest']['specialities'], true); 
			if(empty($m_specialities))
			{
				$m_specialities = array();
			}
			?>
			<p><strong>Spécialité<?php if(count($m_specialities) > 0) echo "s"; ?>: </strong>
				<?php 
				
				foreach ($m_specialities as $key => $id) {
					
					if(!empty($official_specialities[$id]))
					{
						echo $official_specialities[$id];
						
						if($key < count($m_specialities) - 1)
						{
							echo ', ';
						}						
					}
				}

				?>
			</p>
			<p>
				<?php echo $meeting_request['MeetingsRequest']['description']; ?>
			</p>
			<div class="panel-group accordion" id="accordion">
				<div class="panel panel-white">
					<div class="panel-heading">
						<h5 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded = "false">
							<i class="icon-arrow"></i> Afficher le détails des avis des membres de la commission
						</a></h5>
					</div>
					<div id="collapseOne" class="panel-collapse collapse" aria-expanded = "false">
						<div class="panel-body">
							<table style = "border-spacing: 10px;border-collapse: separate;" width = "100%">
								<?php foreach ($members_judgments as $key => $member_judgment) :?>
								<?php if($key % 2 == 0){ echo '<tr>'; } ?>
									<td style = "width:50%;position:relative;">
										<table >
											<tr>
												<td style = "text-align:center; width:150px;">
													<?php 
													if(!empty($member_judgment['User']['Service']['logo']))
													echo $this->Html->image('../uploads/establishments/'.$member_judgment['User']['Service']['logo'], array('style' => 'max-width:100px;')).'<br>'; ?>
													<?php echo $member_judgment['User']['Service']['name']; ?> (<?php echo $member_judgment['User']['Service']['abreviation']; ?>)
												</td>
												<td style = "vertical-align: top;">
													<div>
														<strong><?php echo $member_judgment['Judgment']['name']; ?></strong>
														<?php 
														$specialities = json_decode($member_judgment['MembersRequest']['specialities'], true); 
														if(empty($specialities))
														{
															$specialities = array();
														}
														?>
														<p><strong>Spécialité<?php if(count($specialities) > 0) echo "s"; ?>: </strong>
															<?php 
															
															foreach ($specialities as $id) {
																if(!empty($official_specialities[$id]))
																echo $official_specialities[$id].'<br>';
															}

															?>
														</p>
														<p><?php echo $member_judgment['MembersRequest']['description']; ?></p>			
														<div style = "  position: absolute;bottom: 5px;right: 10px;">
															<small><?php echo 'par '.$member_judgment['User']['first_name'].' '.$member_judgment['User']['last_name'].' le '.$member_judgment['MembersRequest']['event_date']; ?></small>
														</div>
													</div>
												</td>
											</tr>
										</table>
									</td>
								<?php if($key % 2 != 0){ echo '</tr>'; } ?>
								<?php if($key % 2 == 0 && count($members_judgments) == $key + 1){ echo '<td></td></tr>'; } ?>
								<?php endforeach; ?>
							</table>
						</div>
					</div>
				</div>
			</div>
		</fieldset>
		<br>
		<div class="row">
			<div class="col-sm-12 col-md-12">
				<div class="col-sm-6 col-md-6 no-padding" >
					<?php
						echo $this->element('Requests/pie_chart', array(
								'request' => $request, 
								'judgment_data' => $judgment_data,
								'members_judgments' => $members_judgments
							), array('plugin' => 'RequestManagment')
						);
					?>
				</div>
				<div class="col-sm-6 col-md-6">
					<?php
						echo $this->element('Requests/timeline', array(
								'request' => $request, 
								'judgment_data' => $judgment_data,
								'members_judgments' => $members_judgments
							), array('plugin' => 'RequestManagment')
						);				
					?>
				</div>
			</div>
		</div>
		<div class = "clearfix"></div>
<?php }elseif ($this->CapTheme->isUserAutorized($userId, array('action' => 'can_see_all_request_judgments', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'requests')) && !empty($members_judgments))  { ?>
	<div class="row">
	<div class="col-sm-12 col-md-12">
	<table style = "border-spacing: 10px;border-collapse: separate;" width = "100%">
		<?php foreach ($members_judgments as $key => $member_judgment) :
			
			 if($key % 2 == 0){ echo '<tr>'; } ?>
			<td style = "width:50%;position:relative;">
				<table >
					<tr>
						<td style = "text-align:center; width:150px;">
							<?php 
							if(!empty($member_judgment['User']['Service']['logo']))
								echo $this->Html->image('../uploads/establishments/'.$member_judgment['User']['Service']['logo'], array('style' => 'max-width:100px;')).'<br>'; ?>
							<?php echo $member_judgment['User']['Service']['name']; ?> (<?php echo $member_judgment['User']['Service']['abreviation']; ?>)
						</td>
						<td style = "vertical-align: top;">
							<div>
								<strong><?php echo $member_judgment['Judgment']['name']; ?></strong>
								<?php 
								$specialities = json_decode($member_judgment['MembersRequest']['specialities'], true); 
								if(empty($specialities))
								{
									$specialities = array();
								}
								?>
								<p><strong>Spécialité<?php if(count($specialities) > 0) echo "s"; ?>: </strong>
									<?php 
									
									foreach ($specialities as $id) {
										if(!empty($official_specialities[$id]))
										echo $official_specialities[$id].'<br>';
									}

									?>
								</p>
								<p><?php echo $member_judgment['MembersRequest']['description']; ?></p>			
								<div style = "  position: absolute;bottom: 5px;right: 10px;">
									<small><?php echo 'par '.$member_judgment['User']['first_name'].' '.$member_judgment['User']['last_name'].' le '.$member_judgment['MembersRequest']['event_date']; ?></small>
								</div>
							</div>
						</td>
					</tr>
				</table>
			</td>
		<?php if($key % 2 != 0){ echo '</tr>'; } ?>
		<?php if($key % 2 == 0 && count($members_judgments) == $key + 1){ echo '<td style = "background-color: #fff;"></td></tr>'; } ?>
		<?php endforeach; ?>
	</table>
	</div>
	</div>
	<div class="row">
		<div class="col-sm-12 col-md-12">
			<div class="col-sm-6 col-md-6 no-padding">
				<?php
					echo $this->element('Requests/pie_chart', array(
							'request' => $request, 
							'judgment_data' => $judgment_data,
							'members_judgments' => $members_judgments
						), array('plugin' => 'RequestManagment')
					);
				?>
			</div>
			<div class="col-sm-6 col-md-6">
				<?php
					echo $this->element('Requests/timeline', array(
							'request' => $request, 
							'judgment_data' => $judgment_data,
							'members_judgments' => $members_judgments
						), array('plugin' => 'RequestManagment')
					);				
				?>
			</div>
		</div>
	</div>
	<div class = "clearfix"></div>


<?php /*} elseif ($this->CapTheme->isUserAutorized($userId, array('action' => 'can_see_only_his_judgment', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'requests')) && !empty($members_judgments)))  { ?>
	<?php foreach ($members_judgments as $key => $member_judgment) :
		if($member_judgment['User']['id'] == $userId) { ?>
			<table style = "border-spacing: 10px;border-collapse: separate;">
				<tr>
					<td style = "text-align:center; width:150px;">
						<?php echo $this->Html->image('../uploads/establishments/'.$member_judgment['User']['Establishment']['logo'], array('style' => 'max-width:100px;')); ?><br>
						<?php echo $member_judgment['User']['Establishment']['name']; ?> (<?php echo $member_judgment['User']['Establishment']['abreviation']; ?>)
					</td>
					<td style = "vertical-align: top;">
						<div>
							<strong><?php echo $member_judgment['Judgment']['name']; ?></strong>
							<p><?php echo $member_judgment['MembersRequest']['description']; ?></p>			
							<div style = "  position: absolute;bottom: 5px;right: 10px;">
								<small><?php echo 'par '.$member_judgment['User']['first_name'].' '.$member_judgment['User']['last_name'].' le '.$member_judgment['MembersRequest']['event_date']; ?></small>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<?php break; 
		} ?>
	<?php endforeach; */?>
<?php } else { ?>
<div class="col-sm-8">
	<h1 class="mainTitle">Aucun avis</h1>
	<span class="mainDescription">Aucun membre de commission n'a traité encore ce dossier</span>
</div>
<?php } ?>