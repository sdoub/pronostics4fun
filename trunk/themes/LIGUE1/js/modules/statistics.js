var chart;

var themes = {
p4f: {
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
	colors: ["#DDDF0D", "#7798BF", "#55BF3B", "#DF5353", "#aaeeee", "#ff0066", "#eeaaee", 
		"#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
	chart: {
		backgroundColor: {
			linearGradient: [0, 0, 0, 400],
			stops: [
				[0, 'rgb(54, 95, 137)'],
				[1, 'rgb(109, 138, 168)']
			]
		},
		borderWidth: 0,
		borderRadius: 15,
		plotBackgroundColor: null,
		plotShadow: false,
		plotBorderWidth: 0
	},
	title: {
		style: { 
			color: '#FFF',
			font: '16px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
		}
	},
	subtitle: {
		style: { 
			color: '#DDD',
			font: '12px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
		}
	},
	xAxis: {
		type: 'datetime',
        maxZoom: 2 * 3600000, // fourteen days
        dateTimeLabelFormats: { // don't display the dummy year
   	  minute: '%H:%M',
   		hour: '%H:%M',
         month: '%e. %b',
           year: '%b'
        },
		gridLineWidth: 0,
		lineColor: '#FFF',
		tickColor: '#FFF',
		labels: {
			style: {
				color: '#FFF',
				fontWeight: 'bold'
			}
		},
		title: {
			style: {
				color: '#FFF',
				font: 'bold 12px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
			}				
		}
	},
	yAxis: {
		alternateGridColor: null,
		minorTickInterval: null,
		gridLineColor: 'rgba(255, 255, 255, .1)',
		lineWidth: 0,
		tickWidth: 0,
		labels: {
			style: {
				color: '#FFF',
				fontWeight: 'bold'
			}
		},
		title: {
			style: {
				color: '#FFF',
				font: 'bold 12px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
			}				
		}
	},
	legend: {
		itemStyle: {
			color: '#CCC'
		},
		itemHoverStyle: {
			color: '#FFF'
		},
		itemHiddenStyle: {
			color: '#333'
		}
	},
	labels: {
		style: {
			color: '#CCC'
		}
	},
	tooltip: {
		backgroundColor: {
			linearGradient: [0, 0, 0, 50],
			stops: [
				[0, 'rgba(96, 96, 96, .8)'],
				[1, 'rgba(16, 16, 16, .8)']
			]
		},
		borderWidth: 0,
		style: {
			color: '#FFF'
		}
	},
	
	
	plotOptions: {
		line: {
			dataLabels: {
				color: '#CCC'
			},
			marker: {
				lineColor: '#333'
			}
		},
		spline: {
			marker: {
				lineColor: '#333'
			}
		},
		scatter: {
			marker: {
				lineColor: '#333'
			}
		}
	},
	
	toolbar: {
		itemStyle: {
			color: '#CCC'
		}
	},
	
	navigation: {
		buttonOptions: {
			backgroundColor: {
				linearGradient: [0, 0, 0, 20],
				stops: [
					[0.4, '#606060'],
					[0.6, '#333333']
				]
			},
			borderColor: '#000000',
			symbolStroke: '#C0C0C0',
			hoverSymbolStroke: '#FFFFFF'
		}
	},
	
	exporting: {
		buttons: {
			exportButton: {
				symbolFill: '#55BE3B'
			},
			printButton: {
				symbolFill: '#7797BE'
			}
		}
	},	
	
	// special colors for some of the
	legendBackgroundColor: 'rgba(48, 48, 48, 0.8)',
	legendBackgroundColorSolid: 'rgb(70, 70, 70)',
	dataLabelsColor: '#444',
	maskColor: 'rgba(255,255,255,0.3)'
}

}; // end themes

// Set the options
var highchartsOptions = Highcharts.setOptions(themes['p4f']);

var chartImagePath;

function CreateScoreChart(ptitle, pcategories) {

	chart = new Highcharts.Chart({
		chart: {
			renderTo: 'containerCharts',
			defaultSeriesType: 'line',
			marginRight: 130,
			marginBottom: 50
			},
		title: {
			text: ptitle,
			x: -20 // center
			},
		xAxis: {
			categories: pcategories,
			labels: {
	            rotation: -45,
	            align: 'right',
	            style: {
	                font: 'normal 13px Verdana, sans-serif'
	            }
	         }
		},
		yAxis: {
			min:0,
			title: {
				text: 'Points',
				x:0
			},
			plotLines: [{
				value: 0,
				width: 1,
				color: '#808080'
			}]
		},
		tooltip: {
			formatter: function() {
	                return '<b>'+ this.series.name +'</b><br/>'+
					this.x +': '+ Math.abs(this.y) + 'pts';
			}
		},
		legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'top',
			x: -10,
			y: 100,
			borderWidth: 0
		}
	});
	
}

function CreateRankingChartWithTimeScale (ptitle, plotBands, fullDay)
{
	 chart = new Highcharts.Chart({
	      chart: {
	         renderTo: 'containerCharts',
	         zoomType: 'x',
	         type: 'line',
	         marginRight: 130,
			 marginBottom: 120
	      },
	      legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'top',
				x: -10,
				y: 100,
				borderWidth: 0
			},
	      title: {
	         text: ptitle
	      },
	      subtitle: {
	         text: ''
	      },
	      xAxis: {
	         type: 'datetime',
	         maxZoom: 7 * 24 * 3600000,
	         dateTimeLabelFormats: { // don't display the dummy year
	    	  minute: '%H:%M',
	    		hour: '%H:%M',
	          month: '%e. %b',
	            year: '%b'
	         },
	         plotBands: plotBands
	      },
	      yAxis: [{
			min:-60,
			max:-1,
	         title: {
	            text: 'Classement',
	         style: {
					color:'#FFFFFF'
				}
	         },
	         labels: {
	        	 formatter: function() {
	             return Math.abs(this.value) + (Math.abs(this.value)==1? ' er' : ' ème') ;
	          },
	             style: {
	                color: '#FFFFFF'
	             }
	          }

	      }],
		      plotOptions: {
	    	  series: {
				cursor: 'pointer',
				point: {
					events: {
						click: function() {
	                      var groupDescription = "";
	                      var currentDate = this.x;
	                      $.each (plotBands, function(i,group) {
	                      	if (currentDate>=group.from && currentDate<=group.to) {
	                      		groupDescription = group.label.text;
	                      	}
	                      });

						  hs.htmlExpand(null, {
	    					pageOrigin: {
	    						x: this.pageX,
	    						y: this.pageY
	    					},
	    					headingText: groupDescription+" : "+Highcharts.dateFormat('%A, %e %b %Y', this.x),

	    					objectType: 'ajax',
	    					src: "get.group.day.php?RankDate="+this.x+"&NickName="+this.series.name+"&FullDay="+fullDay,
	    					captionText: this.series.name,
	    					width: 400
						  });
						}
					}
				},
				events: {
					hide: function(event) {
					var currentSerieName = this.name;
					$.each (chart.series,function(i,serie) {
	    			    if (currentSerieName+"LR" == serie.name && serie.visible) {
	    			    	serie.hide();
	    			    }
					});
	            },show: function(event) {
					var currentSerieName = this.name;
					$.each (chart.series,function(i,serie) {
	    			    if (currentSerieName+"LR" == serie.name && !serie.visible) {
	    			    	serie.show();
	    			    }
					});
	            }}
		     }
	      }
	      ,
	      tooltip: {
	          crosshairs: true,
	          shared: true,
	          useHTML : true,

	      	formatter: function() {

              var groupDescription = "";
              var currentDate = this.points[0].x;
              $.each (plotBands, function(i,group) {
              	if (currentDate>=group.from && currentDate<=group.to) {
              		groupDescription = group.label.text;
              	}
              });

	    	 var htmlTooltip = groupDescription+" : "+Highcharts.dateFormat('%e. %b %Y', this.points[0].x);
	         $.each
	         (
	          this.points,
	          function(i,point)
	                 {
	        	  htmlTooltip += '<table style="font-size:8pt;color:#FFFFFF;width:150px;"><tr><td style="color:'+point.series.color+';width:150px;">'+ point.series.name +': </td>';
	        	  htmlTooltip += '<td style="text-align: right;width:80px;"><b>' + Math.abs(point.y) + (Math.abs(point.y)==1? 'er' : ' ème')+'</b></td></tr></table>';
	                 }
	          );
	         return htmlTooltip;
		}
	  	       }
	   }, function(chart) { // on complete
		   	    chart.renderer.image(chartImagePath+'/images/bullet.worst.png', 150, 10, 16, 16).add();
	 			chart.renderer.image(chartImagePath+'/images/bullet.best.png', 30, 10, 16, 16).add();
		   	    chart.renderer.text('Plus mauvaise position', 170, 22).css({
		            color: '#FFFFFF',
		            fontSize: '7pt'
		        }).add();
	 			chart.renderer.text('Meilleur place', 50, 22).css({
	 	            color: '#FFFFFF',
	 	            fontSize: '7pt'
	 	        }).add();
	   });
}

function CreateScoreChartWithTimeScale (ptitle, plotBands, fullDay)
{
	 chart = new Highcharts.Chart({
	      chart: {
	         renderTo: 'containerCharts',
	         zoomType: 'x',
	         type: 'line',
	         marginRight: 130,
			 marginBottom: 120
	      },
	      legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'top',
				x: -10,
				y: 100,
				borderWidth: 0
			},
	      title: {
	         text: ptitle
	      },
	      subtitle: {
	         text: ''
	      },
	      xAxis: {
	         type: 'datetime',
	         maxZoom: 7 * 24 * 3600000,
	         dateTimeLabelFormats: { // don't display the dummy year
	    	  minute: '%H:%M',
	    		hour: '%H:%M',
	          month: '%e. %b',
	            year: '%b'
	         },
	         plotBands: plotBands
	      },
	      yAxis: [{
	    	  
	    	  min:0,
	    	  title: {
	            text: 'Point',
	         style: {
					color:'#FFFFFF'
				}
	         },
	         labels: {
	        	 formatter: function() {
	             return this.value + (this.value==1? ' pt' : ' pts') ;
	          },
	             style: {
	                color: '#FFFFFF'
	             }
	          }

	      }],
		      plotOptions: {
	    	  series: {
				cursor: 'pointer',
				point: {
					events: {
						click: function() {
	                      var groupDescription = "";
	                      var currentDate = this.x;
	                      $.each (plotBands, function(i,group) {
	                      	if (currentDate>=group.from && currentDate<=group.to) {
	                      		groupDescription = group.label.text;
	                      	}
	                      });

						  hs.htmlExpand(null, {
	    					pageOrigin: {
	    						x: this.pageX,
	    						y: this.pageY
	    					},
	    					headingText: groupDescription+" : "+Highcharts.dateFormat('%A, %e %b %Y', this.x),

	    					objectType: 'ajax',
	    					src: "get.group.day.php?RankDate="+this.x+"&NickName="+this.series.name+"&FullDay="+fullDay,
	    					captionText: this.series.name,
	    					width: 400
						  });
						}
					}
				},
				events: {
					hide: function(event) {
					var currentSerieName = this.name;
					$.each (chart.series,function(i,serie) {
	    			    if (currentSerieName+"LR" == serie.name && serie.visible) {
	    			    	serie.hide();
	    			    }
					});
	            },show: function(event) {
					var currentSerieName = this.name;
					$.each (chart.series,function(i,serie) {
	    			    if (currentSerieName+"LR" == serie.name && !serie.visible) {
	    			    	serie.show();
	    			    }
					});
	            }}
		     }
	      }
	      ,
	      tooltip: {
	          crosshairs: true,
	          shared: true,
	          useHTML : true,

	      	formatter: function() {

              var groupDescription = "";
              var currentDate = this.points[0].x;
              $.each (plotBands, function(i,group) {
              	if (currentDate>=group.from && currentDate<=group.to) {
              		groupDescription = group.label.text;
              	}
              });

	    	 var htmlTooltip = groupDescription+" : "+Highcharts.dateFormat('%e. %b %Y', this.points[0].x);
	         $.each
	         (
	          this.points,
	          function(i,point)
	                 {
	        	  htmlTooltip += '<table style="font-size:8pt;color:#FFFFFF;width:150px;"><tr><td style="color:'+point.series.color+';width:150px;">'+ point.series.name +': </td>';
	        	  htmlTooltip += '<td style="text-align: right;width:80px;"><b>' + Math.abs(point.y) + (Math.abs(point.y)==1? 'pt' : ' '+'pts') + '</b></td></tr></table>';
	                 }
	          );
	         return htmlTooltip;
		}
	  	       }
	   }, function(chart) { // on complete
			chart.renderer.image(chartImagePath+'/images/bullet.bonus.png', 30, 10, 16, 16).add();
 			chart.renderer.text('Bonus (20pts, 40pts, 60pts, 100pts)', 50, 22).css({
 	            color: '#FFFFFF',
 	            fontSize: '7pt'
 	        }).add();
	   });
}

function CreateRankingChart(ptitle, pcategories) {
	chart = new Highcharts.Chart({
		chart: {
			renderTo: 'containerCharts',
			defaultSeriesType: 'line',
			marginRight: 130,
			marginBottom: 50
		},
		title: {
			text: ptitle,
			x: -20 //center
		},
		xAxis: {
			categories: pcategories,
			labels: {
	            rotation: -45,
	            align: 'right',
	            style: {
	                font: 'normal 13px Verdana, sans-serif'
	            }
	         }
		},
		yAxis: {
			min:-60,
			max:-1,
			title: {
				text: 'Classement',
				x:0
			},
			labels: {
	            formatter: function() {
	               return Math.abs(this.value) + (Math.abs(this.value)==1? ' er' : ' eme') ;
	            }
			},
			plotLines: [{
				value: 0,
				width: 1,
				color: '#808080'
			}]
		},
		tooltip: {
			formatter: function() {
	                return '<b>'+ this.series.name +'</b><br/>'+
					this.x +': '+ Math.abs(this.y) + (Math.abs(this.y)==1? 'er' : 'eme');
			}
		},
		legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'top',
			x: -10,
			y: 100,
			borderWidth: 0
		}
	});
}

function CreateMinMaxAvgScoreChart(ptitle, pcategories) {
	chart = new Highcharts.Chart({
		chart: {
			renderTo: 'containerCharts',
			defaultSeriesType: 'line',
			marginRight: 130,
			marginBottom: 100
		},
		title: {
			text: ptitle,
			x: -20 //center
		},
		xAxis: {
			categories: pcategories,
			labels: {
            rotation: -45,
            align: 'right',
            style: {
                font: 'normal 13px Verdana, sans-serif'
            }
         }
			
		},
		yAxis: {
			title: {
			text: 'Points',
			x:0
			}
		},
		plotOptions: {
			scatter: {
				lineWidth:20
			},
			series: {
				dataLabels: {
					enabled:true,
					style: {
						fontWeight:'bold',
						fontSize:'10',
						color:'#000000'
					},
					x:23,
					y:5
				},
				marker: {
					fillColor:'#FFFFFF',
					lineWidth:2, 
					lineColor:'#000000'
				}
			}
		},
		tooltip: {
			formatter: function() {
				var pointType = '';
				for (var currentIndex=0;currentIndex<this.series.data.length; currentIndex++) {
					if (this.y==this.series.data[currentIndex].y) 
						switch (currentIndex) 
						{
						case 0:
							pointType='Minimum';
							break;
						case 1:
							pointType='Moyenne';
							break;
						case 2:
							pointType='Maximum';
							break;
						}
				}
                return '<b>'+ this.series.name +'</b><br/>'+ pointType + ' : ' + Math.abs(this.y) + 'pts';
			}
		},
		legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'top',
			x: -10,
			y: 100,
			borderWidth: 0
		}
	});
}

function CreateMinMaxAvgRankingChart(ptitle, pcategories) {
	chart = new Highcharts.Chart({
		chart: {
			renderTo: 'containerCharts',
			defaultSeriesType: 'line',
			marginRight: 130,
			marginBottom: 100
		},
		title: {
			text: ptitle,
			x: -20 //center
		},
		xAxis: {
			categories: pcategories,
			labels: {
            	rotation: -45,
            	align: 'right',
            	style: {
                	font: 'normal 13px Verdana, sans-serif'
            	}
         	}
		},
		yAxis: {
			title: {
			text: 'Classement',
			x:0
			},
			labels: {
				formatter: function() {
					return Math.abs(this.value) + (Math.abs(this.value)==1? ' er' : ' eme') ;
            	}
			}
		},	      
		plotOptions: {
			scatter: {
				lineWidth:20
			},
			series: {
				dataLabels: {
					enabled:true,
	                formatter: function() {
						return Math.abs(this.y) + (Math.abs(this.y)==1? ' er' : ' ème') ;            
				    },
					style: {
						fontWeight:'bold',
						fontSize:'10',
						color:'#000000'
					},
					x:33,
					y:5
				},
				marker: {
					fillColor:'#FFFFFF',
					lineWidth:2, 
					lineColor:'#000000'
				}
			}
		},
		tooltip: {
			formatter: function() {
				var pointType = '';
				for (var currentIndex=0;currentIndex<this.series.data.length; currentIndex++) {
					if (this.y==this.series.data[currentIndex].y) 
						switch (currentIndex) 
						{
						case 0:
							pointType='Minimum';
							break;
						case 1:
							pointType='Moyenne';
							break;
						case 2:
							pointType='Maximum';
							break;
						}
				}
                return '<b>'+ this.series.name +'</b><br/>'+ pointType + ' : ' + Math.abs(this.y) + (Math.abs(this.y)==1? ' er' : ' eme');
			}
		},
		legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'top',
			x: -10,
			y: 100,
			borderWidth: 0
		}
	});
}
