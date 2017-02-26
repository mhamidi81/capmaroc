<!-- start: WIZARD DEMO -->
<div class="container-fluid container-fullw bg-white">
	<div class="row">
		<div class="col-md-12">
			<h5 class="over-title margin-bottom-15">
				Dépot du dossier pour : <span class="text-bold">demo</span>
			</h5>
			<p>
				Les champs marqués (<span class="symbol required"></span>) sont obligatoires.
			</p>
			<!-- start: WIZARD FORM -->
			<div>
				<div id="wizard" class="swMain">
					<!-- start: WIZARD SEPS -->
					<ul>
						<li>
							<a href="#step-1">
								<div class="stepNumber">
									1
								</div>
								<span class="stepDesc">
									<small>Type de bénéficiaire</small>
								</span>
							</a>
						</li>
						<li>
							<a href="#step-2">
								<div class="stepNumber">
									2
								</div>
								<span class="stepDesc">
									<small> Informations du demandeur </small>
								</span>
							</a>
						</li>
						<li>
							<a href="#step-3">
								<div class="stepNumber">
									3
								</div>
								<span class="stepDesc"> 
									<small> Documents  </small> 
								</span>
							</a>
						</li>
						<li>
							<a href="#step-4">
								<div class="stepNumber">
									4
								</div>
								<span class="stepDesc"> 
									<small> Récapitulatif et validation </small> 
								</span>
							</a>
						</li>
					</ul>
					<!-- end: WIZARD SEPS -->
					<!-- start: WIZARD STEP 1 -->
					<div id="step-1">
						<?php echo $this->element('ProfileManagment.step1'); ?>
					</div>
					<!-- end: WIZARD STEP 1 -->
					
					<!-- start: WIZARD STEP 2 -->
					<div id="step-2">
						<div class="row">
							<div class="col-md-12">
								<fieldset>
									<legend>
										Information de contact
									</legend>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>
													Téléphone de bureau <span class="symbol required"></span>
												</label>
												<input type="text" placeholder="Veuillez saisir votre numéro de téléphone" class="form-control" name="tel"/>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">
													Fax <span class="symbol required"></span>
												</label>
												<input type="text" placeholder="Veuillez saisir votre numéro de Fax" class="form-control" name="fax"/>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">
													Téléphone mobile <span class="symbol required"></span>
												</label>
												<input type="text" placeholder="Veuillez saisir votre numéro de téléphone mobile" class="form-control" name="mobile"/>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">
													Email <span class="symbol required"></span>
												</label>
												<input type="email" placeholder="Veuillez saisir votre email" class="form-control" name="email"/>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="control-label">
											Adresse <span class="symbol required"></span>
										</label>
										<input type="text" placeholder="Veuillez saisir votre adresse" class="form-control" name="address">
									</div>
								</fieldset>
							</div>

							<div class="col-md-12">
								<fieldset>
									<legend>
										Informations personnel
									</legend>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>
													Nom <span class="symbol required"></span>
												</label>
												<input type="text" placeholder="Veuillez saisir votre nom" class="form-control" name="firstName"/>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">
													Prénom <span class="symbol required"></span>
												</label>
												<input type="text" placeholder="Veuillez saisir votre prénom" class="form-control" name="lastName"/>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="block">
													Situation familiale
												</label>
												<select class="js-example-basic-single js-states form-control" name="situation_familiale">
													<option value="1">Célibataire</option>
													<option value="2">Marié(e)</option>
													<option value="3">Divorcé(e)</option>
													<option value="4">Veuf(ve)</option>
												</select>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="block">
													Ville <span class="symbol required"></span>
												</label>
												<select class="js-example-basic-single js-states form-control" name="cities">
													<option value="1">Ksar EL Kebir</option>
													<option value="2">Rabat</option>
													<option value="3">Tanger</option>
													<option value="4">Tetouan</option>
												</select>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="block">
													Date de naissance <span class="symbol required"></span>
												</label>
												<p class="input-group input-append datepicker date">
													<input type="text" class="form-control" readonly />
													<span class="input-group-btn">
														<button type="button" class="btn btn-default">
															<i class="glyphicon glyphicon-calendar"></i>
														</button>
													</span>
												</p>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">
													CIN <span class="symbol required"></span>
												</label>
												<input type="text" placeholder="Veuillez saisir votre prénom" class="form-control" name="cin"/>
											</div>
										</div>
									</div>
								</fieldset>
							</div>

							<div class="col-md-12">
								<fieldset>
									<legend>
										Expérience professionnelle
									</legend>

									<div id="experiences">
										<div id="experience">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label>
															Nom de l’entreprise <span class="symbol required"></span>
														</label>
														<input type="text" placeholder="Veuillez saisir votre nom" class="form-control" name="firstName"/>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label">
															Fonction <span class="symbol required"></span>
														</label>
														<input type="text" placeholder="Veuillez saisir votre prénom" class="form-control" name="lastName"/>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-sm-4">
													<div class="form-group">
														<label>
															Du <span class="symbol required"></span>
														</label>
														<p class="input-group input-append datepicker date">
															<input type="text" class="form-control" readonly />
															<span class="input-group-btn">
																<button type="button" class="btn btn-default">
																	<i class="glyphicon glyphicon-calendar"></i>
																</button>
															</span>
														</p>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-group">
														<label class="control-label">
															Au <span class="symbol required"></span>
														</label>
														<p class="input-group input-append datepicker date">
															<input type="text" class="form-control" readonly />
															<span class="input-group-btn">
																<button type="button" class="btn btn-default">
																	<i class="glyphicon glyphicon-calendar"></i>
																</button>
															</span>
														</p>
													</div>
												</div>
												<div class="col-sm-4 current_post">
													<div class="form-group">
														<div class="checkbox clip-check check-primary">
															<input type="checkbox" id="current-post" value="1">
															<label for="current-post">
																Ceci est mon travail actuel.
															</label>
														</div>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="block">
															Date de naissance <span class="symbol required"></span>
														</label>
														<p class="input-group input-append datepicker date">
															<input type="text" class="form-control" readonly />
															<span class="input-group-btn">
																<button type="button" class="btn btn-default">
																	<i class="glyphicon glyphicon-calendar"></i>
																</button>
															</span>
														</p>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label">
															CIN <span class="symbol required"></span>
														</label>
														<input type="text" placeholder="Veuillez saisir votre prénom" class="form-control" name="cin"/>
													</div>
												</div>
											</div>
											<hr>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<a class="btn btn-wide btn-primary" onclick="event.preventDefault();" id="copy_exp" href="#"><i class="fa fa-plus"></i> Ajouter expérience</a>
											</div>
										</div>
										<div class="col-md-6 a-r">
											<div class="form-group">
												<a class="btn btn-wide btn-red hide_delete_personne_button" onclick="event.preventDefault();" id="remove_exp" href="#"><i class="fa fa-trash-o"></i> Supprimer expérience</a>
											</div>
										</div>
									</div>

								</fieldset>
							</div>

							<div class="col-md-12">
								<fieldset>
									<legend>
										Publications et recherches
									</legend>

									<div id="publications">
										<div id="publication">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label>
															Nom <span class="symbol required"></span>
														</label>
														<input type="text" placeholder="Veuillez saisir le nom" class="form-control" name="firstName"/>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>
															Date <span class="symbol required"></span>
														</label>
														<p class="input-group input-append datepicker date">
															<input type="text" class="form-control" readonly />
															<span class="input-group-btn">
																<button type="button" class="btn btn-default">
																	<i class="glyphicon glyphicon-calendar"></i>
																</button>
															</span>
														</p>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-sm-12">
													<div class="form-group">
														<label>
															Description <span class="symbol required"></span>
														</label>
														<textarea placeholder="Description" id="form-field-22" class="form-control"></textarea>
													</div>
												</div>
											</div>
											<hr>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<a class="btn btn-wide btn-primary" onclick="event.preventDefault();" id="copy_pub" href="#"><i class="fa fa-plus"></i> Ajouter publication</a>
											</div>
										</div>
										<div class="col-md-6 a-r">
											<div class="form-group">
												<a class="btn btn-wide btn-red hide_delete_personne_button" onclick="event.preventDefault();" id="remove_pub" href="#"><i class="fa fa-trash-o"></i> Supprimer publication</a>
											</div>
										</div>
									</div>

								</fieldset>
							</div>

							<div class="col-md-12">
								<fieldset>
									<legend>
										Langues
									</legend>

									<div id="langues">
										<div id="langue">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label>
															Langue <span class="symbol required"></span>
														</label>
														<input type="text" placeholder="Veuillez saisir votre langue" class="form-control" name="firstName"/>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="block">
															Niveau <span class="symbol required"></span>
														</label>

														<div class="row">
															<div class="col-sm-4">
																<div class="form-group">
																	<div class="clip-radio radio-primary">
																		<input type="radio" id="wz-lire" name="niveau_langue" value="lire">
																		<label for="wz-lire">
																			Lire
																		</label>
																	</div>
																</div>
															</div>

															<div class="col-sm-4">
																<div class="form-group">
																	<div class="clip-radio radio-primary">
																		<input type="radio" id="wz-ecrire" name="niveau_langue" value="ecrire">
																		<label for="wz-ecrire">
																			Écrire
																		</label>
																	</div>
																</div>
															</div>

															<div class="col-sm-4">
																<div class="form-group">
																	<div class="clip-radio radio-primary">
																		<input type="radio" id="wz-parler" name="niveau_langue" value="parler">
																		<label for="wz-parler">
																			Parler
																		</label>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<hr>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<a class="btn btn-wide btn-primary" onclick="event.preventDefault();" id="copy_lng" href="#"><i class="fa fa-plus"></i> Ajouter langue</a>
											</div>
										</div>
										<div class="col-md-6 a-r">
											<div class="form-group">
												<a class="btn btn-wide btn-red hide_delete_personne_button" onclick="event.preventDefault();" id="remove_lng" href="#"><i class="fa fa-trash-o"></i> Supprimer langue</a>
											</div>
										</div>
									</div>
								</fieldset>

								<div class="form-group">
									<button class="btn btn-primary btn-o back-step btn-wide pull-left">
										<i class="fa fa-circle-arrow-left"></i>
										Retour
									</button>
									<button class="btn btn-primary btn-o next-step btn-wide pull-right">
										Suivant
										<i class="fa fa-arrow-circle-right"></i>
									</button>
								</div>
							</div>
						</div>
					</div>
					<!-- end: WIZARD STEP 2 -->
					<!-- start: WIZARD STEP 3 -->
					<div id="step-3">

						<div class="col-md-12">
							<fieldset>
								<legend>
									Uploader votre CV
								</legend>

								<div class="alert alert-success">
									<p>
										Formats autorisés : 
										<br>
										PDF, DOC, DOCX - Taille max : 5 Mo.
									</p>
								</div>

								<span class="btn btn-success fileinput-button">
									<i class="glyphicon glyphicon-upload"></i> 
									<span>Ajouter CV...</span>
									<input type="file" name="files[]" multiple>
								</span>

							</fieldset>
							<div class="form-group">
								<button class="btn btn-primary btn-o back-step btn-wide pull-left">
									<i class="fa fa-circle-arrow-left"></i>
									Retour
								</button>
								<button class="btn btn-primary btn-o next-step btn-wide pull-right">
									Suivant
									<i class="fa fa-arrow-circle-right"></i>
								</button>
							</div>
						</div>

					</div>
					<!-- end: WIZARD STEP 3 -->
					<!-- start: WIZARD STEP 4 -->
					<div id="step-4">
						<div class="row">
							<div class="col-md-12">
								<div class="text-center">
									<h1 class=" ti-check block text-primary"></h1>
									<h2 class="StepTitle">Félicitations!</h2>
									<p class="text-large">
										Votre demande est pret pour envoyè.
									</p>
									<p class="text-small">
										Veuillez verifier votre dossier et cliquer sur le betton VALIDER ci-dessous pour envoyer votre demande.
									</p>
									<a class="btn btn-wide btn-success" href="javascript:void(0)">
										VALIDER
									</a>
								</div>
							</div>
						</div>
					</div>
					<!-- end: WIZARD STEP 4 -->
				</div>
			</div>
			<!-- end: WIZARD FORM -->
		</div>
	</div>
</div>
<!-- start: WIZARD DEMO -->