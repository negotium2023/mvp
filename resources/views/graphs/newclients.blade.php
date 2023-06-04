@extends('layouts.app')

@section('title') Graphs @endsection

@section('header')
    <h1><i class="fa fa-line-chart"></i> @yield('title')</h1>
@endsection

@section('content')
   <form class="form-inline mt-3">
        From &nbsp;
        {{Form::date('t',old('t'),['class'=>'form-control form-control-sm','id'=>'converted_end_date'])}}
        &nbsp; To &nbsp;
        {{Form::date('t',old('t'),['class'=>'form-control form-control-sm','id'=>'converted_end_date'])}}
    </form>

    <hr>
 
        <div class="col-lg-12 mt-lg-3">
            <div class="card">
                <div class="card-header">
                    New Clients Added to CCH
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
        
        Highcharts.theme = {
            title: {
                text: ''
            },
            chart: {
                type: 'column'
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
            xAxis: {
                crosshair: true
            },
            credits: {
                enabled: false
            },
            legend: {
                enabled: false
            },
        };

        Highcharts.setOptions(Highcharts.theme);

        var blackboardDashboard3 = Highcharts.chart('blackboard-chart', {
            yAxis: {
                labels: {
                    formatter: function (x) {
                        return (this.value);
                    }
                },
            },
            xAxis: {
                categories: [
                    2014,
                    2015,
                    2016,
                    2017
                ]
            },
            tooltip: {
                formatter: function () {
                    return '<small class="text-muted">' + this.x + '</small><br><b>' + this.y + '</b>';
                }
            },
            series: [{
                data: [
                    112,
                    121,
                    108,
                    155
                ]
            }]
        });

    </script>
@endsection