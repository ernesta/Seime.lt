jQuery(document).ready( function() {
if (document.getElementById('sitLink') != null) {
		sitChar = new Highcharts.Chart({
			chart: {
				renderTo: 'sitLink',
				defaultSeriesType: 'area'
			},

			title: {
				text: null
			},

			colors: [
					'#C75233' //'#C78933'
			],

			xAxis: {
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
			
			credits: {
				enabled: false
			},

			tooltip: {
				formatter: function() {				
					return	'<strong>' + Highcharts.dateFormat('%B %e, %Y (%A)', RandomSittingOverTime_Labels[this.x]["unix"]) + '</strong><br/>' +
									'<strong>Laikas: ' + RandomSittingOverTime_Labels[this.x]["time"] + '</strong><br/>' +
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
				data: RandomSittingOverTime_Count
			}]
		});
    }
    });
