@extends('layouts.app')

@section('title') Graphs @endsection

@section('header')
    <h1><i class="fa fa-line-chart"></i> @yield('title')</h1>
@endsection

@section('content')
   
 
        <div class="col-lg-12 mt-lg-3">
            <div class="card">
                <div class="card-header">
                    Client Target Data
                    <div class="float-right">
                        <div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-secondary active" id="type-3-bar">
                                <input type="radio" name="blackboard-dashboard-3-type"><i class="fa fa-line-chart"></i>
                            </label>
                            <label class="btn btn-secondary" id="type-3-line">
                                <input type="radio" name="blackboard-dashboard-3-type"><i class="fa fa-bar-chart"></i>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-body p-1 pt-2 pb-2">
                    <div id="blackboard-chart" class="m-0" style="min-height: 300px;"></div>
                </div>
            </div>
        </div>


@endsection

@section('extra-js')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/no-data-to-display.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>

    <script>
        
        Highcharts.setOptions({
            colors: ['#007bff', '#17a2b8'],
        });
        
        Highcharts.chart('blackboard-chart', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Client Target Data 2017'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: [
                    'Q1',
                    'Q2',
                    'Q3',
                    'Q4'
                ],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''/*'Rainfall (mm)'*/
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Target',
                data: [30, 30, 30, 30]

            }, {
                name: 'Actual',
                data: [36, 25, 37, 37]

            }]
        });


    </script>
@endsection