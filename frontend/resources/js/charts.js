//Individual
var masterChart;
var detailChart;
var voting;

//General
//Attendance
var gmasterChart;
var gdetailChart;
var gfrac;
var gsess;
var gseas;

//Voting
var vover;
var vdistr;
var forgainst;
var forreject;

//Sittings
var fullAtt;
var statsAtt;



$(document).ready(function() {
	if (document.getElementById('individual-timeline') != null) {
		function createMaster() {
			masterChart = new Highcharts.Chart({
				chart: {
					renderTo: 'master-container',
					reflow: false,
					borderWidth: 0,
					backgroundColor: null,
					marginLeft: 60,
					marginRight: 20,
					zoomType: 'x',
					events: {
						selection: function(event) {
							var extremesObject = event.xAxis[0];
							var min = extremesObject.min;
							var max = extremesObject.max;
							var detailWorked = [];
							var detailNotWorked = [];
							var xAxis = this.xAxis[0];

							jQuery.each(this.series[0].data, function(i, point) {
								if (point.x > min && point.x < max) {
									detailWorked.push({
										x: point.x,
										y: point.y
									});
								}
							});

							jQuery.each(this.series[1].data, function(i, point) {
								if (point.x > min && point.x < max) {
									detailNotWorked.push({
										x: point.x,
										y: point.y
									});
								}
							});

							xAxis.removePlotBand('mask-before');
							xAxis.addPlotBand({
								id: 'mask-before',
								from: min,
								to: max,
								color: 'rgba(20, 95, 140, 0.2)'
							});

							detailChart.series[0].setData(detailWorked);
							detailChart.series[1].setData(detailNotWorked);
							return false;
						}
					}
				},

				title: {
					text: null
				},

				xAxis: {
					type: 'datetime',
					showLastTickLabel: true,
					maxZoom: 14 * 24 * 3600 * 1000,
					title: {
						text: null
					},
					plotBands: [{
						id: 'mask-before',
						from: IndividualSittingParticipation_StartTime,
						to: IndividualSittingParticipation_EndTime,
						color: 'rgba(20, 95, 140, 0.2)'
					}]
				},

				yAxis: {
					gridLineWidth: 0,
					labels: {
						enabled: false
					},
					title: {
						text: null
					},
					min: 0.1,
					showFirstLabel: true
				},

				tooltip: {
					formatter: function() {
						return false;
					}
				},

				colors: [
					'#C75233',
					'#C78933'
				],

				legend: {
					enabled: true
				},

				plotOptions: {
					series: {
						lineWidth: 1,
						marker: {
							enabled: false
						},
						shadow: false,
						states: {
							hover: {
								lineWidth: 1
							}
						},
						enableMouseTracking: false
					},
					area: {
						stacking: 'normal',
						lineColor: '#D6CEAA',
						lineWidth: 1,
						marker: {
							lineWidth: 1,
							lineColor: '#D6CEAA'
						}
					}
				},

				series: [{
					type: 'area',
					name: 'Nedalyvauta posėdyje',
					pointInterval: 24 * 3600 * 1000,
					data: IndividualSittingParticipation_NotPresentData
				}, {
					type: 'area',
					name: 'Dalyvauta posėdyje',
					pointInterval: 24 * 3600 * 1000,
					data: IndividualSittingParticipation_PresentData
				}]
			}, function(masterChart) {
				createDetail(masterChart)
			});
		}


		function createDetail(masterChart) {
			var detailWorked = [];
			var detailNotWorked = [];
			var detailStart = IndividualSittingParticipation_StartTime;

			jQuery.each(masterChart.series[0].data, function(i, point) {
				if (point.x >= detailStart) {
					detailWorked.push({
						x: point.x,
						y: point.y
					});
				}
			});

			jQuery.each(masterChart.series[1].data, function(i, point) {
				if (point.x >= detailStart) {
					detailNotWorked.push({
						x: point.x,
						y: point.y
					});
				}
			});

			detailChart = new Highcharts.Chart({
				chart: {
					marginBottom: 110,
					renderTo: 'detail-container',
					reflow: false,
					marginLeft: 60,
					marginRight: 20,
					style: {
						position: 'absolute'
					}
				},

				colors: [
					'#C75233',
					'#C78933'
				],

				title: {
					text: 'Seimo nario darbo valandų statistika',
					style: {
						color: '#02385C'
					}
				},

				subtitle: {
					text: 'Detalesnė informacija pasiekiama pažymėjus dominantį periodą pele apatiniame grafike'
				},

				xAxis: {
					type: 'datetime'
				},

				yAxis: {
					title: {
						text: 'Darbo valandų skaičius',
						style: {
							color: '#6D869F',
							fontWeight: 'normal'
						}
					},
					maxZoom: 1
				},

				tooltip: {
					formatter: function() {
						var point = this.points[0];
						var pointTwo = this.points[1];

						return '<b>' + Highcharts.dateFormat('%B %e, %Y (%A)', this.x) + '</b><br/>' +
							point.series.name +': ' + Highcharts.numberFormat(point.y, 1) + ' val.<br/>' + pointTwo.series.name +': '+
							Highcharts.numberFormat(pointTwo.y, 1) + ' val.';
					},
					shared: true
				},

				legend: {
					enabled: false
				},

				plotOptions: {
					series: {
						marker: {
							enabled: false,
							states: {
								hover: {
									enabled: true,
									radius: 3
								}
							}
						}
					},
					column: {
						stacking: 'normal'
					}
				},

				series: [{
					name: 'Nedalyvauta posėdyje',
					type: 'column',
					pointStart: detailStart,
					pointInterval: 24 * 3600 * 1000,
					data: detailWorked
				}, {
					name: 'Dalyvauta posėdyje',
					type: 'column',
					pointStart: detailStart,
					pointInterval: 24 * 3600 * 1000,
					data: detailNotWorked
				}]
		});
	}

	var $container = $('#individual-timeline');

	var $detailContainer = $('<div id="detail-container">').appendTo($container);

	var $masterContainer = $('<div id="master-container">').css({ position: 'absolute', top: 300, height: 140, width: '100%' }).appendTo($container);

	createMaster();
	}



	if (document.getElementById('individual-voting')) {
	voting = new Highcharts.Chart({
		chart: {
			renderTo: 'individual-voting',
			margin: [0, 0, 0, 0],
			plotBackgroundColor: 'none',
			plotBorderWidth: 0,
			plotShadow: false
		},

		title: {
			text: 'Seimo nario balsavimo statistika',
			style: {
				color: '#02385C'
			}
		},

		subtitle: {
			text: 'Vidinis grafikas: bendra Seimo narių statistika<br/>Išorinis grafikas: šio Seimo nario statistika'
		},

		tooltip: {
			formatter: function() {
				return '<b>'+ this.series.name +'</b><br/>'+
				this.point.name +': '+ Highcharts.numberFormat(this.y, 1) +'%';
			}
		},

		series: [{
			type: 'pie',
			name: 'Visi Seimo nariai',
			size: '45%',
			innerSize: '20%',
			data: IndividualPieChart_TotalData,
			dataLabels: {
				enabled: false
			}
			}, {
			type: 'pie',
			name: IndividualName,
			innerSize: '45%',
			data: IndividualPieChart_IndividualData,
			dataLabels: {
				enabled: false
			},
			showInLegend: true
		}]
   });
   }



   if (document.getElementById('general-timeline')) {
		function gcreateMaster() {
			gmasterChart = new Highcharts.Chart({
				chart: {
					renderTo: 'master-container',
					reflow: false,
					borderWidth: 0,
					backgroundColor: null,
					marginLeft: 60,
					marginRight: 20,
					zoomType: 'x',
					events: {
						selection: function(event) {
							var extremesObject = event.xAxis[0];
							var min = extremesObject.min;
							var max = extremesObject.max;
							var gdetailWorked = [];
							var gdetailNotWorked = [];
							var xAxis = this.xAxis[0];

							jQuery.each(this.series[0].data, function(i, point) {
								if (point.x > min && point.x < max) {
									gdetailWorked.push({
										x: point.x,
										y: point.y
									});
								}
							});

							jQuery.each(this.series[1].data, function(i, point) {
								if (point.x > min && point.x < max) {
									gdetailNotWorked.push({
										x: point.x,
										y: point.y
									});
								}
							});

							xAxis.removePlotBand('mask-before');
							xAxis.addPlotBand({
								id: 'mask-before',
								from: min,
								to: max,
								color: 'rgba(20, 95, 140, 0.2)'
							});

							gdetailChart.series[0].setData(gdetailWorked);
							gdetailChart.series[1].setData(gdetailNotWorked);
							return false;
						}
					}
				},

				title: {
					text: null
				},

				xAxis: {
					type: 'datetime',
					showLastTickLabel: true,
					maxZoom: 14 * 24 * 3600 * 1000,
					title: {
						text: null
					},
					plotBands: [{
						id: 'mask-before',
						from: new Date(TotalParticipationBySitting_NotPresentData[TotalParticipationBySitting_NotPresentData.length - 1][0] - 1000 * 3600 * 24 * 30), //Date.UTC(2011, 8, 1),
						to: new Date(TotalParticipationBySitting_NotPresentData[TotalParticipationBySitting_NotPresentData.length - 1][0]),
						color: 'rgba(20, 95, 140, 0.2)'
					}]
				},

				yAxis: {
					gridLineWidth: 0,
					labels: {
						enabled: false
					},
					title: {
						text: null
					},
					min: 0.1,
					showFirstLabel: true
				},

				tooltip: {
					formatter: function() {
						return false;
					}
				},

				colors: [
					'#C75233',
					'#C78933'
				],

				legend: {
					enabled: true
				},

				plotOptions: {
					series: {
						lineWidth: 1,
						marker: {
							enabled: false
						},
						shadow: false,
						states: {
							hover: {
								lineWidth: 1
							}
						},
						enableMouseTracking: false
					},
					area: {
						stacking: 'normal',
						lineColor: '#D6CEAA',
						lineWidth: 1,
						marker: {
							lineWidth: 1,
							lineColor: '#D6CEAA'
						}
					}
				},

				series: [{
					type: 'area',
					name: 'Nedalyvauta posėdyje',
					pointInterval: 24 * 3600 * 1000,
					data: TotalParticipationBySitting_NotPresentData
				}, {
					type: 'area',
					name: 'Dalyvauta posėdyje',
					pointInterval: 24 * 3600 * 1000,
					data: TotalParticipationBySitting_PresentData
				}]

			}, function(gmasterChart) {
				gcreateDetail(gmasterChart)
			});
		}


		function gcreateDetail(gmasterChart) {
			var gdetailWorked = [];
			var gdetailNotWorked = [];
			var detailStart = new Date(TotalParticipationBySitting_NotPresentData[TotalParticipationBySitting_NotPresentData.length - 1][0] - 1000 * 3600 * 24 * 30)

			jQuery.each(gmasterChart.series[1].data, function(i, point) {
				if (point.x >= detailStart) {
					gdetailWorked.push({
						x: point.x,
						y: point.y
					});
				}
			});

			jQuery.each(gmasterChart.series[0].data, function(i, point) {
				if (point.x >= detailStart) {
					gdetailNotWorked.push({
						x: point.x,
						y: point.y
					});
				}
			});

			gdetailChart = new Highcharts.Chart({
				chart: {
					marginBottom: 110,
					renderTo: 'detail-container',
					reflow: false,
					marginLeft: 60,
					marginRight: 20,
					style: {
						position: 'absolute'
					}
				},

				colors: [
					'#C75233',
					'#C78933'
				],

				title: {
					text: 'Vidutinio Seimo nario darbo valandų statistika',
					style: {
						color: '#02385C'
					}
				},

				subtitle: {
					text: 'Detalesnė informacija pasiekiama pažymėjus dominantį periodą pele apatiniame grafike'
				},

				xAxis: {
					type: 'datetime'
				},

				yAxis: {
					title: {
						text: 'Darbo valandų skaičius',
						style: {
							color: '#6D869F',
							fontWeight: 'normal'
						}
					},
					maxZoom: 1
				},

				tooltip: {
					formatter: function() {
						var point = this.points[0];
						var pointTwo = this.points[1];

						return '<b>' + Highcharts.dateFormat('%B %e, %Y (%A)', this.x + 24 * 3600000) + '</b><br/>' +
							point.series.name +': ' + Highcharts.numberFormat(point.y, 1) + ' val.<br/>' + pointTwo.series.name +': '+
							Highcharts.numberFormat(pointTwo.y, 1) + ' val.';
					},
					shared: true
				},

				legend: {
					enabled: false
				},

				plotOptions: {
					series: {
						marker: {
							enabled: false,
							states: {
								hover: {
									enabled: true,
									radius: 3
								}
							}
						}
					},
					column: {
						stacking: 'normal'
					}
				},

				series: [{
					name: 'Nedalyvauta posėdyje',
					type: 'column',
					pointStart: detailStart,
					pointInterval: 24 * 3600 * 1000,
					data: gdetailNotWorked
				}, {
					name: 'Dalyvauta posėdyje',
					type: 'column',
					pointStart: detailStart,
					pointInterval: 24 * 3600 * 1000,
					data: gdetailWorked
				}]
		});
	}

	var $container = $('#general-timeline');

	var $detailContainer = $('<div id="detail-container">').appendTo($container);

	var $masterContainer = $('<div id="master-container">').css({ position: 'absolute', top: 300, height: 140, width: '100%' }).appendTo($container);

	gcreateMaster();
	}



	if (document.getElementById('general-frac')) {
	gfrac = new Highcharts.Chart({
    	chart: {
			renderTo: 'general-frac',
			defaultSeriesType: 'column',
			margin: [ 50, 0, 50, 50]
		},

		colors: [
			'#5E2F46'
		],

		title: {
			text: 'Dirbtų valandų dalis pagal frakcijas*',
			style: {
				color: '#02385C'
			}
		},

		xAxis: {
			categories: ParticipationByFraction_Fractions,
	        labels: {
				rotation: -45,
        	    align: 'right'
            }
		},

		yAxis: {
			min: 0,
			title: {
				text: 'Dirbtų valandų dalis, %',
				style: {
					color: '#6D869F',
					fontWeight: 'normal'
				}
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



	if (document.getElementById('general-seas')) {
		gseas = new Highcharts.Chart({
    	chart: {
			renderTo: 'general-seas',
			defaultSeriesType: 'column',
			margin: [ 50, 0, 50, 50]
		},

		colors: [
			'#C78933'
		],

		title: {
			text: 'Dirbtų valandų dalis pagal mėnesius',
			style: {
				color: '#02385C'
			}
		},

		xAxis: {
			categories: ParticipationByMonth_Months,
	        labels: {
				rotation: -45,
        	    align: 'right'
            }
		},

		yAxis: {
			min: 0,
			title: {
				text: 'Dirbtų valandų dalis, %',
				style: {
					color: '#6D869F',
					fontWeight: 'normal'
				}
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
			data: ParticipationByMonth_Data,
			dataLabels: {
				enabled: false
			}
		}]
	});
	}



	if (document.getElementById('general-vover') != null) {
	vover = new Highcharts.Chart({
		chart: {
			renderTo: 'general-vover',
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			margin: [20, 0, 50, 0]
		},

		colors: [
			'#C78933',
			'#D6CEAA',
			'#5E2F46',
			'#C75233'
		],

		title: {
			text: 'Bendra Seimo narių balsavimo statistika',
			style: {
				color: '#02385C'
			}
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
				},
				showInLegend: true
			}
		},

		series: [{
			type: 'pie',
			name: 'Balsavimo statistika',
			data: TotalVotePie_Data
		}]
	});
	}



	if (document.getElementById('general-vdistr') != null) {
	vdistr = new Highcharts.Chart({
    	chart: {
			renderTo: 'general-vdistr',
			defaultSeriesType: 'column',
			margin: [ 50, 0, 50, 60]
		},

		colors: [
			'#C75233'
		],

		title: {
			text: 'Balsavimų pasiskirstymas pagal dalyvių skaičių',
			style: {
				color: '#02385C'
			}
		},

		xAxis: {
			categories: [
            	'0-10',
            	'11-20',
            	'21-30',
            	'31-40',
            	'41-50',
            	'51-60',
            	'61-70',
            	'71-80',
            	'81-90',
            	'91-100',
            	'101-110',
            	'111-120',
            	'121-130',
            	'131-141'
            ],
	        labels: {
				rotation: -45,
        	    align: 'right'
            }
		},

		yAxis: {
			min: 0,
			title: {
				text: 'Balsavimų skaičius',
				style: {
					color: '#6D869F',
					fontWeight: 'normal'
				}
			}
		},

		legend: {
			enabled: false
		},

		tooltip: {
			formatter: function() {
				return '<strong>Dalyvių skaičius:</strong> ' + this.x +'<br/>'+
					'<strong>Balsavimų skaičius:</strong> '+ Highcharts.numberFormat(this.y, 0);
			}
		},

		series: [{
			name: 'Balsavimų skaičius pagal dalyvius',
			data: ParticipationBySitting_Data,
			dataLabels: {
				enabled: false
			}
		}]
	});
	}



	if (document.getElementById('general-forgainst') != null) {
	forgainst = new Highcharts.Chart({
		chart: {
			renderTo: 'general-forgainst',
			defaultSeriesType: 'column',
			margin: [ 50, 0, 80, 50]
		},

		title: {
			text: 'Balsų pasiskirstymas pagal balsavimo rezultatą',
			style: {
				color: '#02385C'
			}
		},

		colors: [
			'#5E2F46',
			'#D6CEAA'
		],

		xAxis: {
			categories: [
				'Balsavo UŽ',
				'Balsavo PRIEŠ',
				'Susilaikė',
				'Užsiregistravo, bet nebalsavo',
				'Nedalyvavo'
			]
		},

		yAxis: {
			min: 0,
			title: {
            	text: 'Balsų dalis, %',
            	style: {
					color: '#6D869F',
					fontWeight: 'normal'
				}
        	}
        },

        legend: {
			enabled: true
		},

		tooltip: {
			formatter: function() {
				return '<strong>' + this.series.name + '</strong><br/>' +
					'Balsų dalis: '+ Highcharts.numberFormat(this.y, 1) + '%';
			}
		},

		series: [{
			name: 'Rezultatas: nepritarta',
			data: VotesByOutcome_RejectedData
		}, {
			name: 'Rezultatas: pritarta',
			data: VotesByOutcome_AcceptedData
		}]
	});
	}



	if (document.getElementById('general-forreject') != null) {
	forreject = new Highcharts.Chart({
    	chart: {
			renderTo: 'general-forreject',
			defaultSeriesType: 'column',
			margin: [ 50, 0, 50, 60]
		},

		colors: [
			'#C78933'
		],

		title: {
			text: 'Balsų UŽ dalis balsavimuose,<br/>kurių rezultatas NEPRITARTA',
			style: {
				color: '#02385C'
			}
		},

		xAxis: {
			categories: [
            	'0-10%',
            	'10-20%',
            	'20-30%',
            	'30-40%',
            	'40-50%',
            	'50-60%',
            	'60-70%',
            	'70-80%',
            	'80-90%',
            	'90-100%'
            ],
	        labels: {
				rotation: -45,
        	    align: 'right'
            }
		},

		yAxis: {
			min: 0,
			title: {
				text: 'Balsavimų skaičius',
				style: {
					color: '#6D869F',
					fontWeight: 'normal'
				}
			}
		},

		legend: {
			enabled: false
		},

		tooltip: {
			formatter: function() {
				return '<strong>Balsų UŽ dalis:</strong> ' + this.x +'<br/>'+
					'<strong>Balsavimų skaičius:</strong> '+ Highcharts.numberFormat(this.y, 0);
			}
		},

		series: [{
			name: 'Nepriimtų sprendimų skaičius pagal UŽ balsų dalį',
			data: AcceptRatesInVotings_Data,
			dataLabels: {
				enabled: false
			}
		}]
	});
}});
