<?php  $roleId = AuthComponent::user('role_id');?>
<?php  $userId = AuthComponent::user('id');?>
<div class="panel-heading border-light">
	<h5 class="over-title margin-bottom-5">Dossier N° <span class="text-bold"><?php echo $request['Request']['number'];?></span>
	</h5>
</div>
<div class="panel-body company_profile">
	<div class="tabbable">
		<ul id="myTab2" class="nav nav-tabs nav-justified">
			<li class="active">
				<a href="#company_cv_<?php echo $company['Company']['id']; ?>_tab" data-toggle="tab" aria-expanded="true">
					Informations générales
				</a>
			</li>
			<li>
				<a href="#company_documents_<?php echo $company['Company']['id']; ?>_tab" data-toggle="tab" aria-expanded="false">
					Documents
				</a>
			</li>
			<li class="">
				<a href="#manager_tabs" data-toggle="tab" aria-expanded="false">
					Gestionnaire
				</a>
			</li>

		<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'can_see_request_judgment_tab', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'requests'))) {?>
			<li>
				<a href="#users_judgment_<?php echo $company['Company']['id']; ?>_tab" data-toggle="tab" aria-expanded="false" class = "charts_panel">
					<?php echo __("Avis des membres de la CNCA"); ?>
				</a>
			</li>
		<?php } ?>
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade active in" id="company_cv_<?php echo $company['Company']['id']; ?>_tab">

				<div class="user-left">
					<div class="col-sm-5 col-md-4">
						<div class="center">
							<div class="user-image">
								<div class="fileinput-new thumbnail">
									<?php echo $this->html->image('../../capwebsite/uploads/company/documents/'.$company['Company']['logo']); ?>
								</div>
							</div>
							<h4><?php echo $company['Company']['name'];?></h4>
							<hr>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<table class="table table-condensed">
							<tbody>
								<tr>
									<td>Raison Sociale : </td>
									<td><?php echo $company['Company']['name'];?></td>
								</tr>
								<tr>
									<td>Statut juridique : </td>
									<td><?php echo strtoupper($company['Company']['type']);?></td>
								</tr>
								<tr>
									<td>Inscrite au registre du commerce : </td>
									<td><?php echo $company['City']['name'].' sous le n° : '.$company['Company']['number'];?></td>
								</tr>
								<tr>
									<td>capital : </td>
									<td><?php echo $company['Company']['capital'];?></td>
								</tr>
								<tr>
									<td>CNSS : </td>
									<td><?php echo $company['Company']['cnss'];?></td>
								</tr>
								<tr>
									<td>N° de patente : </td>
									<td><?php echo strtoupper($company['Company']['patente']);?>
									</td>
								</tr>
								<tr>
									<td>Email : </td>
									<td><?php echo $company['Company']['email'];?></td>
								</tr>
								<tr>
									<td>Adresse : </td>
									<td><?php echo $company['Company']['address'];?></td>
								</tr>								
								<tr>
									<td>Téléphone : </td>
									<td><?php echo $company['Company']['phone'];?></td>
								</tr>
								<tr>
									<td>fax : </td>
									<td><?php echo $company['Company']['fax'];?></td>
								</tr>
								<tr>
									<td>Site internet : </td>
									<td><?php echo $company['Company']['website'];?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class = "clear"></div>
				</div>
			</div>
			<div class="tab-pane fade in" id="company_documents_<?php echo $company['Company']['id']; ?>_tab">
				<div class="row space10">
					<?php foreach ($companies_documents as $key => $document) :?>
					<div class="col-sm-<?php echo 12/count($documents);?>">
						<button class="btn btn-icon margin-bottom-5 btn-show-document btn-block light_gray <?php if ($key == 0) echo 'current'; ?>" target-id = "<?php echo $document['Edocument']['id'];?>">
							<i class="<?php echo $document['Edocument']['icon'];?> block text-primary text-extra-large margin-bottom-10"></i>
							<?php echo $document['Edocument']['name'];?>
							<?php foreach ($company['CompaniesDocument'] as $key => $company_document){
								if($company_document['edocument_id'] == $document['Edocument']['id']){
									if($company_document['is_valid']){ ?>
									<span class="badge badge-success"><i class = "ti-check"></i></span>
							<?php }}} ?>
						</button>
					</div>
					<?php endforeach;?>
				</div>
				<div class="panel panel-white light_gray profile-documents">
					<?php foreach ($companies_documents as $key => $document) :?>
						<?php foreach ($company['CompaniesDocument'] as $key => $company_document){
							if($company_document['edocument_id'] == $document['Edocument']['id']){
						?>
						<div class = "panel-document-wrapper" style = "<?php if($key > 0){echo 'display:none';} ?>" document-id = "<?php echo $document['Edocument']['id']; ?>" request-document-id = "<?php echo $company_document['id']; ?>" >
								<div class="panel-heading border-light">
									<h4 class="panel-title"><?php echo $document['Edocument']['name']; ?></h4>
									<?php if($request['Status']['id'] < 4): ?>
									<ul class="panel-heading-tabs border-light">
										<li class="panel-tools">
											<a href="#" class="btn btn-lg btn-warning btn-reset-company-document-validation" style = "<?php if(!$company_document['is_valid']) echo 'display:none;' ?>">
												<i class="ti-close"></i>
													Annuler la validation
											</a>
											<a href="#" class="btn btn-lg btn-success btn-validate-company-document" style = "<?php if($company_document['is_valid']) echo 'display:none;' ?>">
												<span class = "ti-check"></span>
													Valider le documment
											</a>
										</li>
									</ul>
									<?php endif; ?>
								</div>
							<div class = "panel-body document-thumbnail">
							<?php
								echo $this->Html->image('../../capwebsite/uploads/company/documents/'.$company_document['filename']);
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
			<div class="tab-pane fade" id="manager_tabs">
			<?php	
				echo $this->element('Counselors/get_profile', array(
					'counselor' => $counselor, 
					'request' => $request,
					'documents' => $documents,
					'isMeeting' => false,
					'members_judgments' => array(),
					'meeting_request' => array(),
					'showMembersAvis' => false,
					'official_specialities' => $official_specialities
					), array('plugin' => 'ProfileManagment')
				);
			?>
			</div>
			<?php if ($this->CapTheme->isUserAutorized($userId, array('action' => 'can_see_request_judgment_tab', 'admin' => true, 'plugin' => 'request_managment', 'controller' => 'requests'))) {?>
			<div class="tab-pane fade in" id="users_judgment_<?php echo $company['Company']['id']; ?>_tab">
				<?php
					echo $this->element('Requests/members_judgments', array(
							'meeting_request' => $meeting_request,
							'members_judgments' => $members_judgments,
							'isMeeting' => $isMeeting,
						), array('plugin' => 'RequestManagment')
					);
				?>
			</div>
			<?php } ?>
		</div>
	</div>	
</div>