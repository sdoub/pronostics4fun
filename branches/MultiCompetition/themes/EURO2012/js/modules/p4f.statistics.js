var themes = {
	p4f : {
		lang: {
		months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
				'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
			weekdays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
			shortMonths : ['Janv.', 'Fév.', 'Mars', 'Avr.', 'Mai', 'Juin', 'Juil', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
			decimalPoint : ",",
			thousandsSep : " ",
			loading : "Chargement ...",
			resetZoom : "RàZ zoom"
		},	
		colors : [ "#aaeeee", "#DF5353", "#55BF3B", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee", 
		   		"#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
		chart : {
			backgroundColor : {
				linearGradient : [ 0, 0, 0, 400 ],
				stops: [
						[0, 'rgb(70,87,35)'],
						[1, 'rgb(27, 61, 28)']
					]
			},
			borderWidth : 0,
			borderRadius : 15,
			plotBackgroundColor : null,
			plotShadow : false,
			plotBorderWidth : 0
		},
		title : {
			style : {
				color : '#FFF',
				font : '16px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
			}
		},
		subtitle : {
			style : {
				color : '#DDD',
				font : '12px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
			}
		},
		xAxis : {
			gridLineWidth : 0,
			lineColor : '#FFF',
			tickColor : '#FFF',
			labels : {
				style : {
					color : '#FFF',
					fontWeight : 'bold'
				}
			},
			title : {
				style : {
					color : '#FFF',
					font : 'bold 12px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
				}
			}
		},
		yAxis : {
			alternateGridColor : null,
			minorTickInterval : null,
			gridLineColor : 'rgba(255, 255, 255, .1)',
			lineWidth : 0,
			tickWidth : 0,
			labels : {
				style : {
					color : '#FFF',
					fontWeight : 'bold'
				}
			},
			title : {
				style : {
					color : '#FFF',
					font : 'bold 12px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
				}
			}
		},
		legend : {
			itemStyle : {
				color : '#CCC'
			},
			itemHoverStyle : {
				color : '#FFF'
			},
			itemHiddenStyle : {
				color : '#333'
			}
		},
		labels : {
			style : {
				color : '#CCC'
			}
		},
		tooltip : {
			backgroundColor : {
				linearGradient : [ 0, 0, 0, 50 ],
				stops : [ [ 0, 'rgba(96, 96, 96, .8)' ],
						[ 1, 'rgba(16, 16, 16, .8)' ] ]
			},
			borderWidth : 0,
			style : {
				color : '#FFF'
			}
		},

		plotOptions : {
			line : {
				dataLabels : {
					color : '#CCC'
				},
				marker : {
					lineColor : '#333'
				}
			},
			spline : {
				marker : {
					lineColor : '#333'
				}
			},
			scatter : {
				marker : {
					lineColor : '#333'
				}
			}
		},

		toolbar : {
			itemStyle : {
				color : '#CCC'
			}
		},

		navigation : {
			buttonOptions : {
				backgroundColor : {
					linearGradient : [ 0, 0, 0, 20 ],
					stops : [ [ 0.4, '#606060' ], [ 0.6, '#333333' ] ]
				},
				borderColor : '#000000',
				symbolStroke : '#C0C0C0',
				hoverSymbolStroke : '#FFFFFF'
			}
		},

		exporting : {
			buttons : {
				exportButton : {
					symbolFill : '#55BE3B'
				},
				printButton : {
					symbolFill : '#7797BE'
				}
			}
		},

		// special colors for some of the
		legendBackgroundColor : 'rgba(48, 48, 48, 0.8)',
		legendBackgroundColorSolid : 'rgb(70, 70, 70)',
		dataLabelsColor : '#444',
		maskColor : 'rgba(255,255,255,0.3)'
	}

}; // end themes

// Set the options
var highchartsOptions = Highcharts.setOptions(themes['p4f']);

var chart;

function CreateForecastsChart(ptitle,psubTitle, pcategories) {
	chart = new Highcharts.Chart( {
		chart : {
			renderTo : 'containerCharts',
			defaultSeriesType : 'bar',
			marginRight : 40,
			marginBottom : 50
		},
		title : {
			text : ptitle
			
		// center
		},
        subtitle: {
           text: psubTitle
        },
		xAxis : {
			categories : pcategories,
			gridLineColor: '#cccccc',
		    gridLineWidth: 1,
	         plotBands: [{ // visualize the weekend
	            from: 3,
	            to: 7,
	            color: 'rgba(68, 170, 213, .2)'
	         }]
		},
		yAxis: {
	         title: {
	            text: ''
	         }
		},
		tooltip : {
			enabled:true,
			crosshairs : true,
			shared : true
		},
		plotOptions : {
			series : {
				dataLabels : {
					enabled : false,
					style : {
						fontWeight : 'bold',
						fontSize : '10',
						color : '#FFFFFF'
					},
					x : 0,
					y : -20
				}
			}
		}, 
		legend: {
			layout: 'horizontal',
			align: 'right',
			verticalAlign: 'top',
			x: -250,
			y: 460,
			borderWidth: 0
		}
	});
}

function CreateResultsChart(ptitle,psubTitle, pcategories) {
	chart = new Highcharts.Chart( {
		chart : {
			renderTo : 'containerCharts',
			defaultSeriesType : 'column',
			marginRight : 40,
			marginBottom : 50
		},
		title : {
			text : ptitle
			
		// center
		},
        subtitle: {
           text: psubTitle
        },
		xAxis : {
			categories : pcategories,
			gridLineColor: '#cccccc',
		    gridLineWidth: 1,
	         plotBands: [{ // visualize the weekend
	            from: 3,
	            to: 7,
	            color: 'rgba(68, 170, 213, .2)'
	         }]
		},
		yAxis: {
	         title: {
	            text: ''
	         }
		},
		tooltip : {
			enabled:true,
			crosshairs : true,
			shared : true
		},
		plotOptions : {
			series : {
				dataLabels : {
					enabled : false,
					style : {
						fontWeight : 'bold',
						fontSize : '10',
						color : '#FFFFFF'
					},
					x : 0,
					y : -20
				}
			},
	         column: {
	            stacking: 'normal'
	         }
		}, 
		legend: {
			layout: 'horizontal',
			align: 'right',
			verticalAlign: 'top',
			x: -250,
			y: 460,
			borderWidth: 0
		}
	});
}

function CreatePointsChart(ptitle,psubTitle, pcategories) {
	chart = new Highcharts.Chart( {
		chart : {
			renderTo : 'containerCharts',
			defaultSeriesType : 'column',
			marginRight : 40,
			marginBottom : 50
		},
		title : {
			text : ptitle
			
		// center
		},
        subtitle: {
           text: psubTitle
        },
		xAxis : {
			categories : pcategories,
			gridLineColor: '#cccccc',
		    gridLineWidth: 1,
	         plotBands: [{ // visualize the weekend
	            from: 3,
	            to: 7,
	            color: 'rgba(68, 170, 213, .2)'
	         }]
		},
		yAxis: [
		  {
	         title: {
	            text: 'Points'
	         }
		},{
	         title: {
            	text: 'Moyenne',
            	style: {
					color:'#55BF3B'
				}	
         	},
            labels: {
               style: {
                  color: '#55BF3B'
               }
            },
         	min:0,
         	max:10,
         	opposite: true
		}],
		tooltip : {
			enabled:true,
			crosshairs : true,
			shared : true
		},
		plotOptions : {
			series : {
				dataLabels : {
					enabled : false,
					style : {
						fontWeight : 'bold',
						fontSize : '10',
						color : '#FFFFFF'
					},
					x : 0,
					y : -20
				}
			},
	         column: {
	            stacking: 'normal'
	         }
		}, 
		legend: {
			layout: 'horizontal',
			align: 'right',
			verticalAlign: 'top',
			x: -250,
			y: 460,
			borderWidth: 0
		}
	});
}



function CreateScoreChart(ptitle, pcategories) {

	chart = new Highcharts.Chart( {
		chart : {
			renderTo : 'containerCharts',
			defaultSeriesType : 'line',
			marginRight : 130,
			marginBottom : 50
		},
		title : {
			text : ptitle,
			x : -20
		// center
		},
		xAxis : {
			categories : pcategories,
			labels : {
				rotation : -45,
				align : 'right',
				style : {
					font : 'normal 13px Verdana, sans-serif'
				}
			}
		},
		yAxis : {
			min : 0,
			title : {
				text : 'Points',
				x : -20
			},
			plotLines : [ {
				value : 0,
				width : 1,
				color : '#808080'
			} ]
		},
		tooltip : {
			formatter : function() {
				return '<b>' + this.series.name + '</b><br/>' + this.x + ': '
						+ Math.abs(this.y) + 'pts';
			}
		},
		legend : {
			layout : 'vertical',
			align : 'right',
			verticalAlign : 'top',
			x : -10,
			y : 100,
			borderWidth : 0
		}
	});

}

function CreateRankingChart(ptitle, pcategories) {
	chart = new Highcharts.Chart( {
		chart : {
			renderTo : 'containerCharts',
			defaultSeriesType : 'line',
			marginRight : 130,
			marginBottom : 50
		},
		title : {
			text : ptitle,
			x : -20
		// center
		},
		xAxis : {
			categories : pcategories,
			labels : {
				rotation : -45,
				align : 'right',
				style : {
					font : 'normal 13px Verdana, sans-serif'
				}
			}
		},
		yAxis : {
			min : -30,
			max : -1,
			title : {
				text : 'Classement',
				x : -20
			},
			labels : {
				formatter : function() {
					return Math.abs(this.value)
							+ (Math.abs(this.value) == 1 ? ' er' : ' ème');
				}
			},
			plotLines : [ {
				value : 0,
				width : 1,
				color : '#808080'
			} ]
		},
		tooltip : {
			formatter : function() {
				return '<b>' + this.series.name + '</b><br/>' + this.x + ': '
						+ Math.abs(this.y)
						+ (Math.abs(this.y) == 1 ? 'er' : 'ème');
			}
		},
		legend : {
			layout : 'vertical',
			align : 'right',
			verticalAlign : 'top',
			x : -10,
			y : 100,
			borderWidth : 0
		}
	});
}

function CreateMinMaxAvgScoreChart(ptitle, pcategories) {
	chart = new Highcharts.Chart(
			{
				chart : {
					renderTo : 'containerCharts',
					defaultSeriesType : 'line',
					marginRight : 130,
					marginBottom : 100
				},
				title : {
					text : ptitle,
					x : -20
				// center
				},
				xAxis : {
					categories : pcategories,
					labels : {
						rotation : -45,
						align : 'right',
						style : {
							font : 'normal 13px Verdana, sans-serif'
						}
					}

				},
				yAxis : {
					title : {
						text : 'Points',
						x : -20
					}
				},
				plotOptions : {
					scatter : {
						lineWidth : 20
					},
					series : {
						dataLabels : {
							enabled : true,
							style : {
								fontWeight : 'bold',
								fontSize : '10',
								color : '#000000'
							},
							x : 23,
							y : 5
						},
						marker : {
							fillColor : '#FFFFFF',
							lineWidth : 2,
							lineColor : '#000000'
						}
					}
				},
				tooltip : {
					formatter : function() {
						var pointType = '';
						for ( var currentIndex = 0; currentIndex < this.series.data.length; currentIndex++) {
							if (this.y == this.series.data[currentIndex].y)
								switch (currentIndex) {
								case 0:
									pointType = 'Minimum';
									break;
								case 1:
									pointType = 'Moyenne';
									break;
								case 2:
									pointType = 'Maximum';
									break;
								}
						}
						return '<b>' + this.series.name + '</b><br/>'
								+ pointType + ' : ' + Math.abs(this.y) + 'pts';
					}
				},
				legend : {
					layout : 'vertical',
					align : 'right',
					verticalAlign : 'top',
					x : -10,
					y : 100,
					borderWidth : 0
				}
			});
}

function CreateMinMaxAvgRankingChart(ptitle, pcategories) {
	chart = new Highcharts.Chart(
			{
				chart : {
					renderTo : 'containerCharts',
					defaultSeriesType : 'line',
					marginRight : 130,
					marginBottom : 100
				},
				title : {
					text : ptitle,
					x : -20
				// center
				},
				xAxis : {
					categories : pcategories,
					labels : {
						rotation : -45,
						align : 'right',
						style : {
							font : 'normal 13px Verdana, sans-serif'
						}
					}
				},
				yAxis : {
					title : {
						text : 'Classement',
						x : -20
					},
					labels : {
						formatter : function() {
							return Math.abs(this.value)
									+ (Math.abs(this.value) == 1 ? ' er'
											: ' ème');
						}
					}
				},
				plotOptions : {
					scatter : {
						lineWidth : 20
					},
					series : {
						dataLabels : {
							enabled : true,
							formatter : function() {
								return Math.abs(this.y)
										+ (Math.abs(this.y) == 1 ? ' er'
												: ' ème');
							},
							style : {
								fontWeight : 'bold',
								fontSize : '10',
								color : '#000000'
							},
							x : 33,
							y : 5
						},
						marker : {
							fillColor : '#FFFFFF',
							lineWidth : 2,
							lineColor : '#000000'
						}
					}
				},
				tooltip : {
					formatter : function() {
						var pointType = '';
						for ( var currentIndex = 0; currentIndex < this.series.data.length; currentIndex++) {
							if (this.y == this.series.data[currentIndex].y)
								switch (currentIndex) {
								case 0:
									pointType = 'Minimum';
									break;
								case 1:
									pointType = 'Moyenne';
									break;
								case 2:
									pointType = 'Maximum';
									break;
								}
						}
						return '<b>' + this.series.name + '</b><br/>'
								+ pointType + ' : ' + Math.abs(this.y)
								+ (Math.abs(this.y) == 1 ? ' er' : ' ème');
					}
				},
				legend : {
					layout : 'vertical',
					align : 'right',
					verticalAlign : 'top',
					x : -10,
					y : 100,
					borderWidth : 0
				}
			});
}
