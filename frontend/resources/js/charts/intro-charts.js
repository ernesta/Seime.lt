var attChart;
var voteChart;
var indChart;
var sitChar;

$(document).ready(function() {

	if (document.getElementById('attLink')) {
		attChart = new Highcharts.Chart({
			chart: {
				renderTo: 'attLink',
				defaultSeriesType: 'column',
				margin: [0, 30, 30, 30]
			},

			colors: [
				'#5E2F46'
			],

			title: {
				text: null
			},

			yAxis: {
				gridLineWidth: 0,
				labels: {
					enabled: false
				},
				title: {
					text: null
				}
			},

			xAxis: {
				categories: ParticipationByFraction_Fractions,
				labels: {
					enabled: false
				},
				title: {
					text: null
				}
			},

			legend: {
				enabled: false
			},

			tooltip: {
				formatter: function() {
					return '<b>'+ this.x +'</b><br/>'+
						'Dirbtų valandų dalis: '+ Highcharts.numberFormat(this.y, 1) +
						'%';
				}
			},

			series: [{
				name: 'Dirbtų valandų dalis',
				data: ParticipationByFraction_Data,
				dataLabels: {
					enabled: false
				}
			}]
		});
	}


	if (document.getElementById('voteLink')) {
		voteChart = new Highcharts.Chart({
			chart: {
				renderTo: 'voteLink',
				margin: 0
			},

			colors: [
				'#C78933',
				'#D6CEAA',
				'#5E2F46',
				'#C75233'
			],

			title: {
				text: null
			},

			tooltip: {
				formatter: function() {
					return '<strong>'+ this.point.name +':</strong> '+ Highcharts.numberFormat(this.y, 1) +'%';
				}
			},

			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: false
					}
				}
			},

			series: [{
				type: 'pie',
				name: 'Balsavimo statistika',
				data: TotalVotePie_Data
			}]
		});
	}


	if (document.getElementById('indLink')) {
		indChart = new Highcharts.Chart({
			chart: {
				renderTo: 'indLink',
				defaultSeriesType: 'column',
				margin: [0, 30, 30, 30]
		},

		title: {
			text: null
		},

		colors: [
			'#C75233',
			'#C78933'
		],

		xAxis: {
			type: 'datetime',
			title: {
				text: null
			},
			labels: {
				enabled: false
			}
		},

		yAxis: {
			min: 0,
			title: {
				text: null
			},
			labels: {
				enabled: false
			},
			gridLineWidth: 0
		},

		legend: {
			enabled: false
		},

		tooltip: {
			formatter: function() {
				return '<b>' + SittingParticipationExtract_Name + '</b><br/>' +
				'<b>' + Highcharts.dateFormat('%B %e, %Y (%A)', this.x) + '</b><br/>' +
				this.series.name + ': ' + Highcharts.numberFormat(this.y, 1) + ' val.';
			}
		},

		plotOptions: {
			column: {
				stacking: 'normal'
			}
		},

		series: [{
			name: 'Nedalyvauta posėdyje',
			data: SittingParticipationExtract_NotPresentData
		}, {
			name: 'Dalyvauta posėdyje',
			data: SittingParticipationExtract_PresentData
		}]
	});
	}
});
