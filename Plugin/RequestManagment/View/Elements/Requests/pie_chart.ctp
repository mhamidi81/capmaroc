<?php  $userId = AuthComponent::user('id');?>
<?php 

	foreach ($judgment_data as $key => $judgment) {
		
		if(empty($judgment['Judgment']['count'])){
			$judgment_data[$key]['Judgment']['count'] = 0;
		}

		foreach ($members_judgments as $index => $member_judgment) {
			
			if($member_judgment['MembersRequest']['judgment_id'] == $judgment['Judgment']['id'])
			{
				$judgment_data[$key]['Judgment']['count']++;
			}
		}
	}
?>
<div class="panel panel-white no-radius">
	<div class="panel-heading border-bottom">
		<h4 class="panel-title">Statistique des avis</h4>
	</div>
	<div class="panel-body" style = "padding:15px 0 15px 2px;">
		<div class="text-center">
			<span class="fluid-pie"> 
				<canvas id="avis_chart"></canvas> 
				<span class = "inside-pie-legend"><?php echo count($members_judgments); ?> Avis</span> 
			</span>
		</div>
		<div class="margin-top-20 text-center legend-md">
			<div id="avis_chart_legend" class="chart-legend" ></div>
		</div>
	</div>
	<div class="panel-footer">
		<div class="clearfix padding-5 space5">
			<?php foreach ($judgment_data as $key => $judgment) { ?>
			<div class="col-xs-6 text-center no-padding">
				<div class="<?php if($key+1 < count($judgment_data)) echo "border-right border-dark"; ?>">
					<span class="text-bold block text-extra-large"><?php echo $judgment['Judgment']['count']; ?></span>
					<span class="text-light"><?php echo $judgment['Judgment']['name']; ?></span>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
<script>
var chart3Handler = function() {
		// Chart.js Data
		var data = [
		<?php foreach ($judgment_data as $key => $judgment) { ?>
			{
				value: <?php echo $judgment['Judgment']['count']; ?>,
				color: '<?php echo $judgment['Judgment']['color']; ?>',
				highlight: '<?php echo $judgment['Judgment']['highlight']; ?>',
				label: '<?php echo $judgment['Judgment']['name']; ?>'
			}
		<?php if($key < (count($judgment_data))){echo ',';} ?>
		<?php } ?>
		];

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
			legendTemplate: '<ul class="tc-chart-js-legend"><% for (var i=0; i<segments.length; i++){%><li style="width:50%;text-align:left;"><span style="background-color:<%=segments[i].fillColor%>"></span><%= (segments[i].value/<?php echo count($members_judgments); ?>*100).toFixed(2) %>% <%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'

		};
		// Get context with jQuery - using jQuery's .get() method.
		var ctx = $("#avis_chart").get(0).getContext("2d");
		// This will get the first returned node in the jQuery collection.
		var chart3 = new Chart(ctx).Doughnut(data, options);
		//generate the legend
		var legend = chart3.generateLegend();
		//and append it to your page somewhere
		$('#avis_chart_legend').html(legend);
};

</script>