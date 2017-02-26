<?php echo $this->Html->script(array('../plugins/Chart.js/Chart.min.js'), array('block' => 'scriptBottom'));?>
		<?php echo $this->Html->script(array('../plugins/jquery.sparkline/jquery.sparkline.min.js'), array('block' => 'scriptBottom'));?>
<?php echo $this->Html->script(array('index'), array('block' => 'scriptBottom'));?>
<script>
<?php  $this->Html->scriptStart(array('inline' => false, 'block' => 'scriptBottom')); ?>
			jQuery(document).ready(function() {
				Index.init();
			});
<?php $this->Html->scriptEnd(); ?>
</script>
<?php
$rowClass = $this->Theme->getCssClass('row');
$columnFull = $this->Theme->getCssClass('columnFull');
?>
<div class="<?php echo $rowClass; ?> hidden-lg hidden-md">
	<div class="<?php echo $columnFull; ?>">
		<h2>
			<?php echo $title_for_layout ?>
		</h2>
	</div>
</div>
<!-- start: DASHBOARD TITLE -->
<section id="page-title" class="padding-top-15 padding-bottom-15">
	<div class="row">
		<div class="col-sm-6">
			<h1 class="mainTitle"><?php echo __('Tableau de bord'); ?></h1>
			<span class="mainDescription"><?php echo __('Rapports & Statistiques'); ?> </span>
		</div>
		<div class="col-sm-6">
			<!-- start: MINI STATS WITH SPARKLINE -->
			<ul class="mini-stats pull-right">
				<li>
					<div class="sparkline-1">
						<span ></span>
					</div>
					<div class="values">
						<strong class="text-dark">18304</strong>
						<p class="text-small no-margin">
							Agréements refusés
						</p>
					</div>
				</li>
				<li>
					<div class="sparkline-2">
						<span ></span>
					</div>
					<div class="values">
						<strong class="text-dark">3 833</strong>
						<p class="text-small no-margin">
							Agréements accordés
						</p>
					</div>
				</li>
				<li>
					<div class="sparkline-3">
						<span ></span>
					</div>
					<div class="values">
						<strong class="text-dark">848</strong>
						<p class="text-small no-margin">
							Agréements en traitement
						</p>
					</div>
				</li>
			</ul>
			<!-- end: MINI STATS WITH SPARKLINE -->
		</div>
	</div>
</section>
<!-- end: DASHBOARD TITLE -->
<!-- start: FEATURED BOX LINKS -->

<!-- end: FEATURED BOX LINKS -->
<!-- start: FIRST SECTION -->
<div class="container-fluid container-fullw padding-bottom-10">
	<div class="row">
		<div class="col-sm-12">
			<div class="row">
				<div class="col-md-7 col-lg-8">
					<div class="panel panel-white no-radius" id="visits">
						<div class="panel-heading border-light">
							<h4 class="panel-title"> Comparative des agréements accordés et refusés </h4>
							<ul class="panel-heading-tabs border-light">
								<li>
									<div class="rate">
										<i class="fa fa-caret-up text-primary"></i><span class="value">15</span><span class="percentage">%</span>
									</div>
								</li>
								<li class="panel-tools">
									<a data-original-title="Refresh" data-toggle="tooltip" data-placement="top" class="btn btn-transparent btn-sm panel-refresh" href="#"><i class="ti-reload"></i></a>
								</li>
							</ul>
						</div>
						<div collapse="visits" class="panel-wrapper">
							<div class="panel-body">
								<div class="height-350">
									<canvas id="chart1" class="full-width"></canvas>
									<div class="margin-top-20">
										<div class="inline pull-left">
											<div id="chart1Legend" class="chart-legend"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-5 col-lg-4">
					<div class="panel panel-white no-radius">
						<div class="panel-heading border-light">
							<h4 class="panel-title"> Demandes d'agréement</h4>
						</div>
						<div class="panel-body">
							<h3 class="inline-block no-margin">260</h3> Demandes 
							<div class="progress progress-xs no-radius">
								<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%;">
									<span class="sr-only"> 40% </span>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<h4 class="no-margin">15</h4>
									<div class="progress progress-xs no-radius no-margin">
										<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%;">
											<span class="sr-only"> 80% en traitement</span>
										</div>
									</div>
									Complèt
								</div>
								<div class="col-sm-6">
									<h4 class="no-margin">7</h4>
									<div class="progress progress-xs no-radius no-margin">
										<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
											<span class="sr-only"> 60%</span>
										</div>
									</div>
									Incomplèt
								</div>
							</div>
							<div class="row margin-top-30">
								<div class="col-xs-4 text-center">
									<div class="rate">
										<i class="fa fa-caret-up text-green"></i><span class="value">26</span><span class="percentage">%</span>
									</div>
									Total
								</div>
								<div class="col-xs-4 text-center">
									<div class="rate">
										<i class="fa fa-caret-up text-green"></i><span class="value">62</span><span class="percentage">%</span>
									</div>
									Complèt
								</div>
								<div class="col-xs-4 text-center">
									<div class="rate">
										<i class="fa fa-caret-down text-red"></i><span class="value">12</span><span class="percentage">%</span>
									</div>
									Incomplèt
								</div>
							</div>
							<div class="margin-top-10">
								<div class="height-180">
									<canvas id="chart2" class="full-width"></canvas>
									<div class="inline pull-left legend-xs">
										<div id="chart2Legend" class="chart-legend"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end: FIRST SECTION -->
<!-- start: SECOND SECTION -->
<div class="container-fluid container-fullw bg-white">
	<div class="row">
		<div class="col-sm-8">
			<div class="panel panel-white no-radius">
				<div class="panel-body">
					<div class="partition-light-grey padding-15 text-center margin-bottom-20">
						<h4 class="no-margin">Statistique mensuelle</h4>
						<span class="text-light">Partition de demandes par service</span>
					</div>
					<div id="accordion" class="panel-group accordion accordion-white no-margin">
						<div class="panel no-radius">
							<div class="panel-heading">
								<h4 class="panel-title">
								<a href="#collapseOne" data-parent="#accordion" data-toggle="collapse" class="accordion-toggle padding-15">
									<i class="icon-arrow"></i>
									Ce mois <span class="label label-danger pull-right">3</span>
								</a></h4>
							</div>
							<div class="panel-collapse collapse in" id="collapseOne">
								<div class="panel-body no-padding partition-light-grey">
									<table class="table">
										<tbody>
											<tr>
												<td class="center">1</td>
												<td>Sécretariat</td>
												<td class="center">4909</td>
												<td><i class="fa fa-caret-down text-red"></i></td>
											</tr>
											<tr>
												<td class="center">2</td>
												<td>Direction</td>
												<td class="center">3857</td>
												<td><i class="fa fa-caret-up text-green"></i></td>
											</tr>
											<tr>
												<td class="center">3</td>
												<td>Commission</td>
												<td class="center">1789</td>
												<td><i class="fa fa-caret-up text-green"></i></td>
											</tr>
											<tr>
												<td class="center">4</td>
												<td>Ministère</td>
												<td class="center">612</td>
												<td><i class="fa fa-caret-down text-red"></i></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="panel no-radius">
							<div class="panel-heading">
								<h4 class="panel-title">
								<a href="#collapseTwo" data-parent="#accordion" data-toggle="collapse" class="accordion-toggle padding-15 collapsed">
									<i class="icon-arrow"></i>
									Dérnier mois
								</a></h4>
							</div>
							<div class="panel-collapse collapse" id="collapseTwo">
								<div class="panel-body no-padding partition-light-grey">
									<table class="table">
										<tbody>
											<tr>
												<td class="center">1</td>
												<td>Sécretariat</td>
												<td class="center">5228</td>
												<td><i class="fa fa-caret-up text-green"></i></td>
											</tr>
											<tr>
												<td class="center">2</td>
												<td>Direction</td>
												<td class="center">2853</td>
												<td><i class="fa fa-caret-up text-green"></i></td>
											</tr>
											<tr>
												<td class="center">3</td>
												<td>Commission</td>
												<td class="center">1948</td>
												<td><i class="fa fa-caret-up text-green"></i></td>
											</tr>
											<tr>
												<td class="center">4</td>
												<td>Ministère</td>
												<td class="center">456</td>
												<td><i class="fa fa-caret-down text-red"></i></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="panel panel-white no-radius">
				<div class="panel-heading border-bottom">
					<h4 class="panel-title">Demande d'agréement</h4>
				</div>
				<div class="panel-body">
					<div class="text-center">
						<span class="mini-pie"> <canvas id="chart3" class="full-width"></canvas> <span>450</span> </span>
						<span class="inline text-large no-wrap">Demandes</span>
					</div>
					<div class="margin-top-20 text-center legend-xs inline">
						<div id="chart3Legend" class="chart-legend"></div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="clearfix padding-5 space5">
						<div class="col-xs-4 text-center no-padding">
							<div class="border-right border-dark">
								<span class="text-bold block text-extra-large">90%</span>
								<span class="text-light">Accordés</span>
							</div>
						</div>
						<div class="col-xs-4 text-center no-padding">
							<div class="border-right border-dark">
								<span class="text-bold block text-extra-large">2%</span>
								<span class="text-light">Refusés</span>
							</div>
						</div>
						<div class="col-xs-4 text-center no-padding">
							<span class="text-bold block text-extra-large">8%</span>
							<span class="text-light">En traitement</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end: SECOND SECTION -->
<!-- start: THIRD SECTION -->

<!-- end: THIRD SECTION -->
<!-- start: FOURTH SECTION 
<div class="container-fluid container-fullw bg-white">
	<div class="row">
		<div class="col-xs-12 col-sm-4">
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-white no-radius">
						<div class="panel-body padding-20 text-center">
							<div class="space10">
								<h5 class="text-dark no-margin">Today</h5>
								<h2 class="no-margin"><small>$</small>1,450</h2>
								<span class="badge badge-success margin-top-10">253 Sales</span>
							</div>
							<div class="sparkline-4 space10">
								<span ></span>
							</div>
							<span class="text-white-transparent"><i class="fa fa-clock-o"></i> 1 hour ago</span>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="panel panel-white no-radius">
						<div class="panel-body padding-20 text-center">
							<div class="space10">
								<h5 class="text-dark no-margin">Today</h5>
								<h2 class="no-margin"><small>$</small>1,450</h2>
								<span class="badge badge-danger margin-top-10">253 Sales</span>
							</div>
							<div class="sparkline-5 space10">
								<span ></span>
							</div>
							<span class="text-white-transparent"><i class="fa fa-clock-o"></i> 1 hour ago</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-4">
			<div class="panel panel-white no-radius">
				<div class="panel-heading border-bottom">
					<h4 class="panel-title">Activities</h4>
				</div>
				<div class="panel-body">
					<ul class="timeline-xs margin-top-15 margin-bottom-15">
						<li class="timeline-item success">
							<div class="margin-left-15">
								<div class="text-muted text-small">
									2 minutes ago
								</div>
								<p>
									<a class="text-info" href>
										Steven
									</a>
									has completed his account.
								</p>
							</div>
						</li>
						<li class="timeline-item">
							<div class="margin-left-15">
								<div class="text-muted text-small">
									12:30
								</div>
								<p>
									Staff Meeting
								</p>
							</div>
						</li>
						<li class="timeline-item danger">
							<div class="margin-left-15">
								<div class="text-muted text-small">
									11:11
								</div>
								<p>
									Completed new layout.
								</p>
							</div>
						</li>
						<li class="timeline-item info">
							<div class="margin-left-15">
								<div class="text-muted text-small">
									Thu, 12 Jun
								</div>
								<p>
									Contacted
									<a class="text-info" href>
										Microsoft
									</a>
									for license upgrades.
								</p>
							</div>
						</li>
						<li class="timeline-item">
							<div class="margin-left-15">
								<div class="text-muted text-small">
									Tue, 10 Jun
								</div>
								<p>
									Started development new site
								</p>
							</div>
						</li>
						<li class="timeline-item">
							<div class="margin-left-15">
								<div class="text-muted text-small">
									Sun, 11 Apr
								</div>
								<p>
									Lunch with
									<a class="text-info" href>
										Nicole
									</a>
									.
								</p>
							</div>
						</li>
						<li class="timeline-item warning">
							<div class="margin-left-15">
								<div class="text-muted text-small">
									Wed, 25 Mar
								</div>
								<p>
									server Maintenance.
								</p>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-4">
			<div class="panel panel-white no-radius">
				<div class="panel-heading border-bottom">
					<h4 class="panel-title">Specialization</h4>
				</div>
				<div class="panel-body">
					<canvas id="chart4" class="full-width"></canvas>
					<div class="margin-top-20 padding-bottom-5 inline">
						<div id="chart4Legend" class="chart-legend"></div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="clearfix padding-5 space5">
						<div class="col-xs-4 text-center no-padding">
							<div class="border-right border-dark">
								<span class="text-bold block text-extra-large">90%</span>
								<span class="text-light">Satisfied</span>
							</div>
						</div>
						<div class="col-xs-4 text-center no-padding">
							<div class="border-right border-dark">
								<span class="text-bold block text-extra-large">2%</span>
								<span class="text-light">Unsatisfied</span>
							</div>
						</div>
						<div class="col-xs-4 text-center no-padding">
							<span class="text-bold block text-extra-large">8%</span>
							<span class="text-light">NA</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>-->
<!-- end: FOURTH SECTION -->
<?php
$this->Html
	->addCrumb('', '/admin', array('icon' => $this->Theme->getIcon('home')))
	->addCrumb(__d('croogo', 'Dashboard'), '/' . $this->request->url);