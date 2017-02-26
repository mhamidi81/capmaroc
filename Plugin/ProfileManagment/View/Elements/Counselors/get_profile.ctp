<?php  $userId = AuthComponent::user('id');?>
<?php if($request['Request']['requester_type'] == 'natural'){?>
<div class="panel-heading border-light">
	<h5 class="over-title margin-bottom-5">Dossier N° <span class="text-bold"><?php echo $request['Request']['number'];?></span>
	</h5>
</div>
<?php } ?>

<div class="panel-body counselor_profile">
	<div class="tabbable">
		<ul id="counsular_<?php echo $counselor['Counselor']['id']; ?>_tabs" class="nav nav-tabs nav-justified">
			<li class="active">
				<a href="#counselor_cv_<?php echo $counselor['Counselor']['id']; ?>_tab" data-toggle="tab" aria-expanded="true">
					<?php echo __("Curriculum vitae"); ?>
				</a>
			</li>
			<li class="">
				<a href="#counselor_documents_<?php echo $counselor['Counselor']['id']; ?>_tab" data-toggle="tab" aria-expanded="false">
					<?php echo __("Documents"); ?>
				</a>
			</li>		
			<?php if($showMembersAvis ) { ?>
			<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'can_see_request_judgment_tab', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'requests'))) {?>
			<li class="">
				<a href="#users_judgment_<?php echo $counselor['Counselor']['id']; ?>_tab" data-toggle="tab" aria-expanded="false" class = "charts_panel">
					<?php echo __("Avis des membres de la CNCA"); ?>
				</a>
			</li>
			<?php } ?>
			<?php } ?>
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade active in" id="counselor_cv_<?php echo $counselor['Counselor']['id']; ?>_tab">
				<div class="user-left">
					<div class="col-sm-5 col-md-4">
						<div class="center">
							<div class="user-image">
								<div class="fileinput-new thumbnail">
									<?php echo $this->html->image('../../capwebsite/uploads/counselor/images/'.$counselor['Counselor']['image']); ?>
								</div>
							</div>
							<h4><?php echo $counselor['Counselor']['full_name'];?></h4>
							<hr>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<table class="table table-condensed">
							<tbody>
								<tr>
									<td>N°CIN : </td>
									<td><?php echo $counselor['Counselor']['cin'];?></td>
								</tr>
								<tr>
									<td>Date de naissance : </td>
									<td><?php echo $counselor['Counselor']['birthday'];?></td>
								</tr>
								<tr>
									<td>Email : </td>
									<td><?php echo $counselor['Counselor']['email'];?></td>
								</tr>
								<tr>
									<td>Téléphone fixe: </td>
									<td><?php echo $counselor['Counselor']['office_phone'];?></td>
								</tr>
								<tr>
									<td>Téléphone mobile: </td>
									<td><?php echo $counselor['Counselor']['mobile_phone'];?></td>
								</tr>
								<tr>
									<td>Ville : </td>
									<td><?php echo $counselor['Counselor']['City']['name'];?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class = "clear"></div>
					<div class = "counselor_diplomes">
						<h5>Diplômes</h5>
						<ul class="list-group">
							<?php
								foreach ($counselor['Qualification'] as  $qualification) : 
							?>
							<li class = "list-group-item">
										<?php echo $qualification['Diplome']['name']; ?> 
										(<?php echo $qualification['year']; ?>, 
										<?php echo $qualification['Establishment']['name']; ?>)
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<div class = "counselor_specialities">
						<h5>Spécialités</h5>
						<ul class="list-group">
							<?php
								foreach ($counselor['Qualification'] as  $spectialie) : 
							?>
							<li class = "list-group-item">
								<?php echo $spectialie['Speciality']['name']; ?>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<div class = "counselor_experiences">
						<h5>Expériences professionnelles</h5>
						<ul class="list-group">
							<?php
								foreach ($counselor['ProfessionalExperience'] as  $experience) : 
							?>
							<li class = "list-group-item">
								<?php echo $experience['organisation']; ?> de <?php echo $experience['start_date']; ?> à <?php echo $experience['end_date']; ?> (<?php echo $experience['function']; ?>)
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<div class = "counselor_community_activities">
						<h5>Activités sociales</h5>
						<ul class="list-group">
							<?php
								foreach ($counselor['CommunityActivity'] as  $activity) : 
							?>
							<li class = "list-group-item">
								<?php echo $activity['name']; ?>, 
								Association : <?php echo $activity['association']; ?>
								Période : de <?php echo $activity['start_date']; ?> à <?php echo $activity['end_date']; ?>
								description : <?php echo $activity['description']; ?>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php if (!empty($counselor['PublicationResearch'])) : ?>
					<div class = "counselor_publications">
						<h5>Publications et recherches</h5>
						<ul class="list-group">
							<?php
								foreach ($counselor['PublicationResearch'] as  $publication) : 
							?>
							<li class = "list-group-item">
								<?php echo $qualification['Diplome']['name']; ?>,
								Date : <?php echo $publication['event_date']; ?>,
								Sujet : <?php echo $publication['name']; ?>
								Description : <?php echo $publication['description']; ?>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php endif; ?>
					<div class = "counselor_languages">
						<h5>Langues</h5>
						<ul class="list-group">
							<?php
								foreach ($counselor['CounselorsLanguage'] as  $language) : 
							?>
							<li class = "list-group-item">
							<?php echo $language['language']; ?> (
								<?php if($language['read']) echo "<strong>lire : </strong>".$language['read']; ?>,
								<?php if($language['write']) echo "<strong>Ecrire : </strong>".$language['write']; ?>,
								<?php if($language['speak']) echo "<strong>Parler : </strong>".$language['speak']; ?>,
							)
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
			<div class="tab-pane fade in" id="counselor_documents_<?php echo $counselor['Counselor']['id']; ?>_tab">
				<div class="row space10">
					<?php foreach ($documents as $key => $document) :?>
					<div class="col-sm-<?php echo 12/count($documents);?>">
						<button class="btn btn-icon margin-bottom-5 btn-show-document btn-block light_gray <?php if ($key == 0) echo 'current'; ?>" target-id = "<?php echo $document['Document']['id'];?>">
							<i class="<?php echo $document['Document']['icon'];?> block text-primary text-extra-large margin-bottom-10"></i>
							<?php echo $document['Document']['name'];?>
							<?php foreach ($counselor['CounselorsDocument'] as $key => $counselor_document){
								if($counselor_document['document_id'] == $document['Document']['id']){
									if($counselor_document['is_valid']){ ?>
									<span class="badge badge-success"><i class = "ti-check"></i></span>
							<?php }}} ?>
						</button>
					</div>
					<?php endforeach;?>
				</div>
				<div class="panel panel-white light_gray profile-documents">
					<?php foreach ($documents as $key => $document) :?>
						<?php foreach ($counselor['CounselorsDocument'] as $key => $counselor_document){
							if($counselor_document['document_id'] == $document['Document']['id']){
						?>
						<div class = "panel-document-wrapper" style = "<?php if($key > 0){echo 'display:none';} ?>" document-id = "<?php echo $document['Document']['id']; ?>" request-document-id = "<?php echo $counselor_document['id']; ?>" >
								<div class="panel-heading border-light">
									<h4 class="panel-title"><?php echo $document['Document']['name']; ?></h4>
									<?php if($request['Status']['id'] < 4): ?>
									<ul class="panel-heading-tabs border-light">
										<li class="panel-tools">
											<a href="#" class="btn btn-lg btn-warning btn-reset-counselor-document-validation" style = "<?php if(!$counselor_document['is_valid']) echo 'display:none;' ?>">
												<i class="ti-close"></i>
													Annuler la validation
											</a>
											<a href="#" class="btn btn-lg btn-success btn-validate-counselor-document" style = "<?php if($counselor_document['is_valid']) echo 'display:none;' ?>">
												<span class = "ti-check"></span>
													Valider le documment
											</a>
										</li>
									</ul>
									<?php endif; ?>
								</div>
							<div class = "panel-body document-thumbnail">
							<?php
									echo $this->Html->image('../../capwebsite/uploads/counselor/documents/'.$counselor_document['filename']);
							?>
							</div>
					</div>
					<?php
							}
						 }
						 ?>
					<?php endforeach;?>
				</div>
			</div>
			<?php if($showMembersAvis ) { ?>
			<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'can_see_request_judgment_tab', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'requests'))) {?>
			<div class="tab-pane fade in" id="users_judgment_<?php echo $counselor['Counselor']['id']; ?>_tab">
				<?php
					echo $this->element('Requests/members_judgments', array(
							'meeting_request' => $meeting_request, 
							'members_judgments' => $members_judgments,
							'isMeeting' => $isMeeting,
							'official_specialities' => $official_specialities
						), array('plugin' => 'RequestManagment')
					);
				?>
			</div>
			<?php } ?>
			<?php } ?>
		</div>
	</div>
</div>