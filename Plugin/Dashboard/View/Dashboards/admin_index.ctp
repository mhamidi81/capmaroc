<?php echo $this->Html->script(array('../plugins/Chart.js/Chart.min.js'), array('block' => 'scriptBottom'));?>
		<?php echo $this->Html->script(array('../plugins/jquery.sparkline/jquery.sparkline.min.js'), array('block' => 'scriptBottom'));?>
<script>

<?php  $this->Html->scriptStart(array('inline' => false, 'block' => 'scriptBottom')); ?>
<?php 
	$url = Router::url(array(
		'admin' => true,
		'plugin' => 'dashboard',
		'controller' => 'dashboards',
		'action' => 'index',
		'placeholder'
	));

	echo 'var dashboard_url = "'.$url.'";';
?>
	$('#year_selector').on('change', function(){
		dashboard_url = dashboard_url.replace('placeholder', $(this).val());
		location.href = dashboard_url;
	});

	var Index = function() {
		var chart1Handler = function() {
			var data = {
				labels: <?php echo json_encode($char1data['labels']); ?>,
				datasets: [{
					label: 'Agrément accordé',
					fillColor: 'rgba(0,255,0,0.2)',
					strokeColor: 'rgba(0,255,0,0.2)',
					pointColor: 'rgba(0,255,0,0.2)',
					pointStrokeColor: '#fff',
					pointHighlightFill: '#fff',
					pointHighlightStroke: 'rgba(220,220,220,1)',
					data: <?php echo json_encode($char1data['granted']); ?>
				}, {
					label: 'Agrément refusé',
					fillColor: 'rgba(255,0,0,0.2)',
					strokeColor: 'rgba(255,0,0,1)',
					pointColor: 'rgba(255,0,0,1)',
					pointStrokeColor: '#fff',
					pointHighlightFill: '#fff',
					pointHighlightStroke: 'rgba(255,0,0,1)',
					data: <?php echo json_encode($char1data['refused']); ?>
				}]
			};

			var options = {

				maintainAspectRatio: false,

				// Sets the chart to be responsive
				responsive: true,

				///Boolean - Whether grid lines are shown across the chart
				scaleShowGridLines: true,

				//String - Colour of the grid lines
				scaleGridLineColor: 'rgba(0,0,0,.05)',

				//Number - Width of the grid lines
				scaleGridLineWidth: 1,

				//Boolean - Whether the line is curved between points
				bezierCurve: false,

				//Number - Tension of the bezier curve between points
				bezierCurveTension: 0.4,

				//Boolean - Whether to show a dot for each point
				pointDot: true,

				//Number - Radius of each point dot in pixels
				pointDotRadius: 4,

				//Number - Pixel width of point dot stroke
				pointDotStrokeWidth: 1,

				//Number - amount extra to add to the radius to cater for hit detection outside the drawn point
				pointHitDetectionRadius: 20,

				//Boolean - Whether to show a stroke for datasets
				datasetStroke: true,

				//Number - Pixel width of dataset stroke
				datasetStrokeWidth: 2,

				//Boolean - Whether to fill the dataset with a colour
				datasetFill: true,

				// Function - on animation progress
				onAnimationProgress: function() {
				},

				// Function - on animation complete
				onAnimationComplete: function() {
				},

				//String - A legend template
				legendTemplate: '<ul class="tc-chart-js-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].strokeColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>'
			};
			// Get context with jQuery - using jQuery's .get() method.
			var ctx = $("#chart1").get(0).getContext("2d");
			// This will get the first returned node in the jQuery collection.
			var chart1 = new Chart(ctx).Line(data, options);
			//generate the legend
			var legend = chart1.generateLegend();
			//and append it to your page somewhere
			$('#chart1Legend').append(legend);
		};
		var chart2Handler = function() {
			// Chart.js Data
			var data = {
				labels: <?php echo json_encode($char2data['labels']); ?>,
				datasets: [{
					label: 'Physique',
					fillColor: 'rgba(0,0,255,0.5)',
					strokeColor: 'rgba(0,0,255,0.5)',
					highlightFill: 'rgba(0,0,255,0.5)',
					highlightStroke: 'rgba(0,0,255,0.5)',
					data: <?php echo json_encode($char2data['natural']); ?>
				}, {
					label: 'Morale',
					fillColor: 'rgba(151,187,205,0.5)',
					strokeColor: 'rgba(151,187,205,0.8)',
					highlightFill: 'rgba(151,187,205,0.75)',
					highlightStroke: 'rgba(151,187,205,1)',
					data: <?php echo json_encode($char2data['legal']); ?>
				}]
			};

			// Chart.js Options
			var options = {
				maintainAspectRatio: false,

				// Sets the chart to be responsive
				responsive: true,

				//Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
				scaleBeginAtZero: true,

				//Boolean - Whether grid lines are shown across the chart
				scaleShowGridLines: true,

				//String - Colour of the grid lines
				scaleGridLineColor: "rgba(0,0,0,.05)",

				//Number - Width of the grid lines
				scaleGridLineWidth: 1,

				//Boolean - If there is a stroke on each bar
				barShowStroke: true,

				//Number - Pixel width of the bar stroke
				barStrokeWidth: 2,

				//Number - Spacing between each of the X value sets
				barValueSpacing: 5,

				//Number - Spacing between data sets within X values
				barDatasetSpacing: 1,

				//String - A legend template
				legendTemplate: '<ul class="tc-chart-js-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>'
			};
			// Get context with jQuery - using jQuery's .get() method.
			var ctx = $("#chart2").get(0).getContext("2d");
			// This will get the first returned node in the jQuery collection.
			var chart2 = new Chart(ctx).Bar(data, options);
			//generate the legend
			var legend = chart2.generateLegend();
			//and append it to your page somewhere
			$('#chart2Legend').append(legend);
		};
		var chart3Handler = function() {
			// Chart.js Data
			var data = [{
				value: <?php echo $chart3data['refused']; ?>,
				color: '#F7464A',
				highlight: '#FF5A5E',
				label: 'Refusées'
			}, {
				value: <?php echo $chart3data['granted']; ?>,
				color: '#46BFBD',
				highlight: '#5AD3D1',
				label: 'Accordées'
			}];

			// Chart.js Options
			var options = {

				// Sets the chart to be responsive
				responsive: false,

				//Boolean - Whether we should show a stroke on each segment
				segmentShowStroke: true,

				//String - The colour of each segment stroke
				segmentStrokeColor: '#fff',

				//Number - The width of each segment stroke
				segmentStrokeWidth: 2,

				//Number - The percentage of the chart that we cut out of the middle
				percentageInnerCutout: 50, // This is 0 for Pie charts

				//Number - Amount of animation steps
				animationSteps: 100,

				//String - Animation easing effect
				animationEasing: 'easeOutBounce',

				//Boolean - Whether we animate the rotation of the Doughnut
				animateRotate: true,

				//Boolean - Whether we animate scaling the Doughnut from the centre
				animateScale: false,

				//String - A legend template
				legendTemplate: '<ul class="tc-chart-js-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'

			};
			// Get context with jQuery - using jQuery's .get() method.
			var ctx = $("#chart3").get(0).getContext("2d");
			// This will get the first returned node in the jQuery collection.
			var chart3 = new Chart(ctx).Doughnut(data, options);
			//generate the legend
			var legend = chart3.generateLegend();
			//and append it to your page somewhere
			$('#chart3Legend').append(legend);
		};
		// function to initiate Sparkline
		var sparkResize;
		$(window).resize(function(e) {
			clearTimeout(sparkResize);
			sparkResize = setTimeout(sparklineHandler, 500);
		});
		var sparklineHandler = function() {

			$(".sparkline-1 span").sparkline(<?php echo json_encode($char1data['granted']); ?>, {
				type: "bar",
				barColor: "#D43F3A",
				barWidth: "5",
				height: "24",
				tooltipFormat: '<span style="color: {{color}}">&#9679;</span> {{offset:names}}: {{value}}',
				tooltipValueLookups: {
					names: <?php echo json_encode($char1data['labels']); ?>
				}
			});
			$(".sparkline-2 span").sparkline(<?php echo json_encode($char1data['refused']); ?>, {
				type: "bar",
				barColor: "#5CB85C",
				barWidth: "5",
				height: "24",
				tooltipFormat: '<span style="color: {{color}}">&#9679;</span> {{offset:names}}: {{value}}',
				tooltipValueLookups: {
					names: <?php echo json_encode($char1data['labels']); ?>
				}
			});
			<?php 
				foreach ($char1data['granted'] as $key => $value) {
					$char1data['total'][$key] = $char1data['granted'][$key] + $char1data['refused'][$key];
				}
			?>
			$(".sparkline-3 span").sparkline(<?php echo json_encode($char1data['total']); ?>, {
				type: "bar",
				barColor: "#46B8DA",
				barWidth: "5",
				height: "24",
				tooltipFormat: '<span style="color: {{color}}">&#9679;</span> {{offset:names}}: {{value}}',
				tooltipValueLookups: {
					names: <?php echo json_encode($char1data['labels']); ?>
				}
			});
		};
		return {
			init: function() {
				chart1Handler();
				chart2Handler();
				chart3Handler();

				sparklineHandler();
			}
		};
	}();

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
		<div class="col-sm-7">
			<h1 class="mainTitle" style = "display: inline-block;"><?php echo __('Tableau de bord année '); ?> </h1> 
			<?php
				echo $this->Form->input('year_selector', array(
					'style' => 'display: inline-block;width: 92px;font-size: 24px;vertical-align: bottom;margin-left: 5px;',
					'label' => false,
					'div' => false,
					'options' => array_combine(range(2015, date('Y')), range(2015, date('Y'))),
					'class' => 'form-control',
					'selected' => $year
				));
			  ?>
			<span class="mainDescription"><?php echo __('Rapports & Statistiques'); ?> </span>
		</div>
		<div class="col-sm-5">
			<!-- start: MINI STATS WITH SPARKLINE -->
			<ul class="mini-stats pull-right">
				<li>
					<div class="sparkline-1">
						<span ></span>
					</div>
					<div class="values">
						<strong class="text-dark"><?php echo array_sum($char1data['refused']); ?></strong>
						<p class="text-small no-margin">
							Agréments refusés
						</p>
					</div>
				</li>
				<li>
					<div class="sparkline-2">
						<span ></span>
					</div>
					<div class="values">
						<strong class="text-dark"><?php echo array_sum($char1data['granted']); ?></strong>
						<p class="text-small no-margin">
							Agréments accordés
						</p>
					</div>
				</li>
				<li>
					<div class="sparkline-3">
						<span ></span>
					</div>
					<div class="values">
						<strong class="text-dark"><?php echo array_sum($char1data['total']); ?></strong>
						<p class="text-small no-margin">
							Total
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
							<h4 class="panel-title"> Comparative des agréments accordés et refusés par mois</h4>
							<ul class="panel-heading-tabs border-light">
								<li class="panel-tools">
									<a data-original-title="Refresh" data-toggle="tooltip" data-placement="top" class="btn btn-transparent btn-sm panel-refresh" href="#"><i class="ti-reload"></i></a>
								</li>
							</ul>
						</div>
						<div collapse="visits" class="panel-wrapper">
							<div class="panel-body">
								<div class="height-250">
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
						<div class="panel-heading border-bottom">
							<h4 class="panel-title" style = "text-align:center;">Comparative de demandes d'agrément agrées et refusées année <?php echo $year ?></h4>
						</div>
						<div class="panel-body" >
							<div class="text-center height-160">
								<span class="mini-pie"> <canvas id="chart3" class="full-width"></canvas> <span><?php echo array_sum($char1data['total']); ?></span> </span>
								<span class="inline text-large no-wrap">Total de demandes</span>
							</div>
							<div class="margin-top-20 text-center legend-xs inline">
								<div id="chart3Legend" class="chart-legend"></div>
							</div>
						</div>
						<div class="panel-footer">
							<div class="clearfix padding-5 space5">
								<div class="col-xs-6 text-center no-padding">
									<div class="border-right border-dark">
										<span class="text-bold block text-extra-large"><?php echo round(array_sum($char1data['granted']) / array_sum($char1data['total']) * 100); ?>%</span>
										<span class="text-light">Agrées</span>
									</div>
								</div>
								<div class="col-xs-6 text-center no-padding">
									<div class="border-right border-dark">
										<span class="text-bold block text-extra-large"><?php echo round(array_sum($char1data['refused']) / array_sum($char1data['total']) * 100); ?>%</span>
										<span class="text-light">Refusées</span>
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
						<h4 class="no-margin">Nombre de demandes agrées par région</h4>
					</div>
					<div id="accordion" class="panel-group accordion accordion-white no-margin">
						<div class="panel no-radius">
							<div class="panel-heading">
								<h4 class="panel-title"></h4>
							</div>
							<div class="panel-collapse collapse in" id="collapseOne">
								<div class="panel-body no-padding partition-light-grey">
									<table class="table">
										<tbody>
											<?php foreach ($chart4data['labels'] as $key => $label) {?>
												<tr>
													<td><?php echo $label; ?></td>
													<td class="center"><?php echo $chart4data['values'][$key]; ?></td>
													<!--<td><i class="fa fa-caret-down text-red"></i></td>-->
												</tr>											
											<?php } ?>
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
				<div class="panel-heading border-light">
					<h4 class="panel-title"> Agréments accordés par type</h4>
				</div>
				<div class="panel-body">
					<h3 class="inline-block no-margin"><?php echo $char2data['natural_count']+$char2data['legal_count']; ?></h3> Agréments  
					<div class="progress progress-xs no-radius">
						<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
							<span class="sr-only"> 100%</span>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<h4 class="no-margin"><?php echo $char2data['natural_count']; ?></h4>
							<div class="progress progress-xs no-radius no-margin">
								<div  class="progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="color : #0000ff;width: <?php echo round($char2data['natural_count']/($char2data['natural_count']+$char2data['legal_count'])*100); ?>%;">
									<span class="sr-only"> <?php echo $char2data['natural_count']/($char2data['natural_count']+$char2data['legal_count'])*100; ?>%</span>
								</div>
							</div>
							Physique
						</div>
						<div class="col-sm-6">
							<h4 class="no-margin"><?php echo $char2data['legal_count']; ?></h4>
							<div class="progress progress-xs no-radius no-margin">
								<div  class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="background-color : #cbdde6;width: <?php echo $char2data['legal_count']/($char2data['natural_count']+$char2data['legal_count'])*100; ?>%;">
									<span class="sr-only"> <?php echo $char2data['legal_count']/($char2data['natural_count']+$char2data['legal_count'])*100; ?>%</span>
								</div>
							</div>
							Morale
						</div>
					</div>
					<div class="row margin-top-30">
						<div class="col-xs-6 text-center">
							<div class="rate">
								<i class="fa fa-caret-up text-green"></i><span class="value"><?php echo round ($char2data['natural_count']/($char2data['natural_count']+$char2data['legal_count'])*100); ?></span><span class="percentage">%</span>
							</div>
							Physique
						</div>
						<div class="col-xs-6 text-center">
							<div class="rate">
								<i class="fa fa-caret-down text-red"></i><span class="value"><?php echo round ($char2data['legal_count']/($char2data['natural_count']+$char2data['legal_count'])*100); ?></span><span class="percentage">%</span>
							</div>
							Morale
						</div>
					</div>
					<div class="margin-top-10">
						<div class="height-200">
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

<?php
$this->Html
	->addCrumb('', '/admin', array('icon' => $this->Theme->getIcon('home')))
	->addCrumb(__d('croogo', 'Dashboard'), '/' . $this->request->url);