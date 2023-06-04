@extends('adminlte.default')

@section('title') Dashboard @endsection

@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
        <hr>
    </div>
@endsection

@section('content')
    <div class="container-fluid chart-wrapper">
        <div class="rotate2 height-chart text-center">
            <p>Pipeline and Performance</p>
        </div>
        <div>
            <!---->
            <canvas id="pipeline-performance" width="429" height="280"></canvas>
        </div>
        <div class="align-middle" style="padding-right: 50px;">
            <div class="table-responsive">
                <table class="table table-sm text-right">
                    <thead>
                    <tr>
                        <th>AUM</th>
                        <th>Revenue</th>
                        <th>Revenue (Ongoing)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>R2,000,000</td>
                        <td>R10,000</td>
                        <td>R10,000</td>
                    </tr>
                    <tr>
                        <td>R1,500,000</td>
                        <td>R5,000</td>
                        <td>R5,000</td>
                    </tr>
                    <tr>
                        <td>R100,000</td>
                        <td>R350,000</td>
                        <td>R350,000</td>
                    </tr>
                    <tr>
                        <td>R2,000,000</td>
                        <td>R120,000</td>
                        <td>R120,000</td>
                    </tr>
                    <tr>
                        <td>R450,000</td>
                        <td>R80,000</td>
                        <td>R80,000</td>
                    </tr>
                    <tr>
                        <td>R5,000,000</td>
                        <td>R123,000</td>
                        <td>R123,000</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="container-fluid mt-5 clearfix">
        <!--<div class="col-md-1">
          <div class="rotate">
              <p>Tasks</p>
          </div>
        </div>-->
        <!--<div class="text-center">-->
        <div class="rotate2 float-left text-center" style="position: relative;top:40px;">
            <p>Tasks</p>
        </div>
        <div class="shadow task text-center">
            <div class="row clearfix mb-2">
                <div class="col-md-6">
                    <img src="{!! asset('img/task-bell.svg') !!}" alt="Tasks Bell">
                </div>
                <div class="col-md-6 count">
                    19
                </div>
            </div>
            <p>My Overdue Tasks</p>
        </div>
        <!--</div>
        <div class="text-center">-->
        <div class="shadow task text-center">
            <div class="row clearfix mb-2">
                <div class="col-md-6">
                    <img src="{!! asset('img/task-checklist.svg') !!}" alt="Tasks Checklist">
                </div>
                <div class="col-md-6 count">
                    19
                </div>
            </div>
            <p>My Tasks for Today</p>
        </div>
        <!--</div>
        <div class="text-center">-->
        <div class="shadow task text-center">
            <div class="row clearfix mb-2">
                <div class="col-md-6">
                    <img src="{!! asset('img/future-tasks.svg') !!}" alt="Future Tasks Checklist">
                </div>
                <div class="col-md-6 count">
                    22
                </div>
            </div>
            <p>My Future Tasks</p>
        </div>
        <!--</div>
        <div class="text-center">-->
        <div class="shadow task text-center">
            <div class="row clearfix mb-2">
                <div class="col-md-6">
                    <img src="{!! asset('img/delegated-tasks.svg') !!}" alt="Delegated Tasks Checklist">
                </div>
                <div class="col-md-6 count">
                    94
                </div>
            </div>
            <p>Delegated Tasks</p>
        </div>
        <!--</div>-->
    </div>
    <div class="container-fluid my-5 ">
        <!--<div class="col-md-1">-->
        <div class="rotate2 float-left  text-left" style="position: relative;left:-20px;top:40px;">
            <p>Interactions</p>
        </div>
        <!--</div>-->
        <!--<div class="col-md-2 text-center">-->
        <div class="shadow task2 text-center" style="margin-left: -40px;">
            <div class="row clearfix mb-2">
                <div class="col-md-6">
                    <img src="{!! asset('img/calendar-1.svg') !!}" alt="Calendar">
                </div>
                <div class="col-md-6 count">
                    3
                </div>
            </div>
            <p>My Overdue Tasks</p>
        </div>
        <!--</div>-->
        <!--<div class="col-md-2 text-center">-->
        <div class="shadow text-center task2">
            <div class="row clearfix mb-2">
                <div class="col-md-6">
                    <img src="{!! asset('img/closing-appoitment.svg') !!}" alt="Closing Appointments">
                </div>
                <div class="col-md-6 count">
                    7
                </div>
            </div>
            <p>Closing Appointments</p>
        </div>
        <!--</div>
        <div class="col-md-2 text-center">-->
        <div class="shadow text-center task2">
            <div class="row clearfix mb-2">
                <div class="col-md-6">
                    <img src="{!! asset('img/review-appointments.svg') !!}" alt="Review Appointments">
                </div>
                <div class="col-md-6 count">
                    7
                </div>
            </div>
            <p>Review Appointments</p>
        </div>
        <!--</div>
        <div class="col-md-2 text-center">-->
        <div class="shadow task2 text-center">
            <div class="row clearfix mb-2">
                <div class="col-md-6">
                    <img src="{!! asset('img/fyc-icon.svg') !!}" alt="Average FYC Size Accepted">
                </div>
                <div class="col-md-6 count">
                    R4,000
                </div>
            </div>
            <p>Average FYC Size Accepted</p>
        </div>
        <!--</div>
        <div class="col-md-2 text-center">-->
        <div class="shadow task2 text-center">
            <div class="row clearfix mb-2">
                <div class="col-md-6">
                    <img src="{!! asset('img/aum-icon.svg') !!}" alt="Average AUM Size Accepted">
                </div>
                <div class="col-md-6 count">
                    R600K
                </div>
            </div>
            <p>Average AUM Size Accepted</p>
        </div>
        <!--</div>-->
    </div>
@endsection

@section('extra-js')

@endsection