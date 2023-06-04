@extends('layouts.app')

@section('title') Graphs @endsection

@section('header')
    <h1><i class="fa fa-line-chart"></i> @yield('title')</h1>
@endsection

@section('content')
    <div class="col-lg-12 mt-lg-3">
        <div class="card">
            <div class="card-header">
                Yearly Comparison
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
                type: 'line'
            },
            title: {
                text: 'Yearly Comparison'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            yAxis: {
                title: {
                    text: 'Number of Clients'
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                }
            },
            series: [{
                name: '2016 Clients',
                data: [11, 25, 36, 45, 61, 74, 89, 97, 98, 113, 122, 135]
            }, {
                name: '2017 Clients',
                data: [13, 16, 36, 8.5, 48, 61, 78, 91, 98, 101, 104, 108]
            }]
        });

    </script>
@endsection