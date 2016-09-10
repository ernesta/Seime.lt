jQuery(document).ready( function() {
	if (document.getElementById('stats-attendance') != null) {
	statsAtt = new Highcharts.Chart({
		chart: {
			renderTo: 'stats-attendance',
			defaultSeriesType: 'area',
			margin: [50, 10, 50, 60],
			spacingTop: 0			
		},

		title: {
			text: 'Bendras dalyvių skaičius posėdžio metu',
			style: {
				color: '#6D869F',
				fontSize: '12px',
				fontWeight: 'normal'
			}
		},

		colors: [
			'#C75233'
		],

		xAxis: {
			labels: {
				formatter: function() {
					return SittingCountOverTime_CountLabels[this.value];
				}
			}		
		},

		yAxis: {
			title: {
				text: 'Seimo narių skaičius',
				style: {
					color: '#6D869F',
					fontWeight: 'normal'
				}
			},
			labels: {
				formatter: function() {
					return this.value;
				}
			},
			max: 150,			
			tickInterval: 50,
			startOnTick: false,
			showFirstLabel: false
		},

		legend: {
			enabled: false
		},
		
		credits: {
			enabled: false
		},

		tooltip: {
			formatter: function() {
				return '<strong>Laikas: ' + SittingCountOverTime_CountLabels[this.x] + '</strong><br/>'+
					'Seimo narių skaičius: '+ Highcharts.numberFormat(this.y, 0);
			}
		},

		plotOptions: {
			area: {
				marker: {
					enabled: false,
					symbol: 'circle',
					radius: 2,
					states: {
						hover: {
							enabled: true
						}
					}
				}
			}
		},

		series: [{
			name: 'Seimo narių skaičius',
			data: SittingCountOverTime_Counts
		}]
	});
}
});
