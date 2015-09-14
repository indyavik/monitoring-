jQuery(document).ready(function() {
        chart = new Highcharts.Chart({
                chart: {
                        renderTo: 'container'
                       // defaultSeriesType: 'area'
                },
                title: {
                        text: 'UPTIME AND DOWNTIME CHECKS'
                },
                subtitle: {
                        text: 'Source: <a href="'+url+'" target="blank">'+url+'</a>'
                },
                xAxis: {
                        categories: categ,
                        tickmarkPlacement: 'on',
                        title: {
                                enabled: false
                        }
                },
                yAxis: {
                        title: {
                                text: 'Percent'
                        }
                         ,labels: {
                            formatter: function() {
                               return this.value +'%';
                            }
                         }

                },
                tooltip: {
                        formatter: function() {
                        return ''+
                                         this.x +': '+ Highcharts.numberFormat(this.percentage, 1) +'% ('+
                                         Highcharts.numberFormat(this.y, 0, ',') +' percent { Number of downtimes: '+Math.round(100 - this.y)+'} )';
                        }
                },
                plotOptions: {
                        area: {
                                stacking: 'percent',
                                lineColor: '#ffffff',
                                lineWidth: 1,
                                marker: {
                                        lineWidth: 1,
                                        lineColor: '#ffffff'
                                }
                        }
                },
                series: [{
                        name: 'Statistic',
                        data: uptime
                }
                /*, {
                        name: 'Downtime',
                        data: downtime
                }*/
                ]
        });

});