jQuery(document).ready( function() {    
	if (document.getElementById('full-attendance') !== null) {
	fullAtt = new Highcharts.Chart({
		chart: {
			renderTo: 'full-attendance',
			defaultSeriesType: 'bar',
			shadow: false,
			marginRight: 20
		},

		title: {
			text: 'Individualių Seimo narių dalyvavimas posėdyje',
			style: {
				color: '#02385C',
				fontSize: '16px',
				marginBottom: '5px',
				fontFamily: '"Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, Helvetica, sans-serif'
			}
		},
		
		subtitle: {
			text: 'Tamsesnė spalva - dalyvauta, šviesesnė spalva - nedalyvauta',
			style: {
				color: '#6D869F',
				fontSize: '12px',
				fontWeight: 'normal'
			}
		},

		xAxis: {
			categories: SittingDynamics_Members
		},

		yAxis: {
			min: 0,
			title: {
				text: null
			}, 
			labels: {
				formatter: function() {
					return SittingDynamics_Labels[this.value];
				}
			},
			opposite: true
		},
		
		tooltip: {
		    enabled: false
		},

		legend: {
			enabled: false
		},
		
		credits: {
			enabled: false
		},

		plotOptions: {
			series: {
				stacking: 'percent',
				borderWidth: 0,
				shadow: false,
				enableMouseTracking: false, 
				animation: false
			}
		},
		
		series: SittingDynamics_Series
	});
	}
});

function saveChart() {
jQuery.ajax({
	url: 'some.php',
	data: jQuery('#full-attendance ,highcharts-container').html(),
	type: 'POST',
	processData: false,
	success: 
		function(data) {
			alert('aha!');
		}
	});
}

/*		tooltip: {
			formatter: function() {
				return '<b>' + this.x + '</b><br/>' + this.series.name;
			}
		},
*/
