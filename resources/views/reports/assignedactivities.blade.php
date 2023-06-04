@extends('adminlte.default')

@section('title') Assigned Actions per User @endsection

@section('header')
    <div class="container-fluid container-title">
        <h3><i class="fa fa-line-chart"></i> @yield('title')</h3>

    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <form class="mt-3">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="activities_search">Activity</label>
                    <select name="activities_search" class="chosen-select form-control form-control-sm col-sm-12">
                        <option value="">All</option>
                        @foreach($activities_dropdown as $activity)
                            <option value="{{$activity->id}}" {{(isset($_GET['activities_search']) && $_GET['activities_search'] == (int)$activity->name ? 'selected="selected"' : '')}}>{{$activity->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="client_search">Client</label>
                    {{Form::select('client_search',$clients_dropdown ,old('client_search'),['class'=>'chosen-select form-control form-control-sm col-sm-12'])}}
                </div>
                <div class="form-group col-md-4">
                    <label for="client_search">User</label>
                    <select name="assigned_user" class="chosen-select form-control form-control-sm col-sm-12">
                        <option value="">All</option>
                        @foreach($assigned_users as $user)
                            <option value="{{$user->id}}" {{(isset($_GET['assigned_user']) && $_GET['assigned_user'] == (int)$user->id ? 'selected="selected"' : '')}}>{{$user->full_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="client_search">From: date</label>
                    {{Form::date('f',old('f'),['class'=>'form-control form-control-sm'])}}
                </div>
                <div class="form-group col-md-2">
                    <label for="client_search">To: date</label>
                    {{Form::date('t',old('t'),['class'=>'form-control form-control-sm'])}}
                </div>
                <div class="form-group col-md-3" style="margin-top:2em">
                    <button type="submit" class="btn btn-sm btn-secondary ml-2 mr-2"><i class="fa fa-search"></i> Search</button>
                    <a href="{{route('reports.assigned_actions')}}" class="btn btn-sm btn-info"><i class="fa fa-eraser"></i> Clear</a>
                </div>
            </div>
        </form>
        <hr>

        <div class="col-sm-12">
            <div class="table-responsive js-pscroll">
                <table class="table table-bordered table-sm table-hover">
                    <thead class="btn-dark">
                    <tr>
                        <th>Customer Name</th>
                        <th>Activities Assigned</th>
                        @role('admin')
                        <th>User Assigned to</th>
                        @endrole
                        <th>Due Date</th>
                        <th class="last">Status</th>
                        @role('admin')
                        <th class="last">Actions</th>
                        @endrole
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($activities as $client_name => $activity_array)
                        @php
                            $clientname = "";
                        @endphp
                        @foreach($activity_array as $activity)
                            @if(isset($activity["client_id"]))
                            <tr>
                                <td><a href="{{route('clients.show',['client'=>$activity['client_id']])}}">
                                        @if($client_name == "" || ($client_name != $clientname))
                                            {{ $client_name }}
                                        @endif
                                    </a></td>
                                <td><a href="{{route('clients.stepprogressaction',['client'=>$activity['client_id'],'step'=>$activity['step_id'],'action_id'=>$activity['action_id']])}}">{{ $activity['activity_name']}}</a></td>
                                @role('admin')
                                <td>
                                    @php
                                        $user_string = '';
                                        foreach ($activity["user"] as $user){
                                        //foreach ($user as $value){
                                        $user_string = $user_string.$user.'<br />';
                                        //}
                                        }

                                        echo $user_string;
                                    @endphp
                                </td>
                                @endrole
                                <td id="due_date">{{ $activity['due_date'] }}</td>
                                <td class="last" align="center"><i class="fas fa-circle" style="color: {{ $activity['class'] }}"></i></td>
                                @role('admin')
                                <td class="last">
                                    <a class="completeaction btn btn-success btn-sm" href="{{route('assignedactions.complete',['clientid' => $activity['client_id'],'activityid' => $activity['activity_id']])}}">Complete</a>
                                    <a class="deleteaction btn btn-danger btn-sm" href="{{route('assignedactions.delete',['clientid' => $activity['client_id'],'activityid' => $activity['activity_id']])}}">Delete</a>
                                </td>
                                @endrole
                            </tr>
                            @endif
                            @php
                                $clientname = $client_name;
                            @endphp
                        @endforeach
                        @empty
                        <tr>
                            <td colspan="6">No records found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('extra-css')
    <link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
    <style>
        /* Colour styles of 'Robot' */
        .robot-circle-red {
            color: red;
        }

        .robot-circle-green {
            color: green;
        }

        .robot-circle-yellow {
            color: yellow;
        }

        thead th {
            position: -webkit-sticky; /* for Safari */
            position: sticky;
            top: 0;
            z-index: 2;
            color: #fff;
            background-color: #343a40;
            box-shadow: 0 1px 1px rgba(0,0,0,.075);
        }

        tbody td:first-child {
            position: -webkit-sticky; /* for Safari */
            position: sticky;
            left: 0;
        }
        thead th:first-child {
            left: -1px;
            z-index: 3;
        }
        tbody td:first-child {
            left: -1px;
            z-index: 1;
            background: #FFFFFF;
            border-left: 1px solid #ffffff
        }

        .column-shadow{
            box-shadow: 8px 0px 10px 0px rgba(0, 0, 0, 0.05);
            -moz-box-shadow: 8px 0px 10px 0px rgba(0, 0, 0, 0.05);
            -webkit-box-shadow: 8px 0px 10px 0px rgba(0, 0, 0, 0.05);
            -o-box-shadow: 8px 0px 10px 0px rgba(0, 0, 0, 0.05);
            -ms-box-shadow: 8px 0px 10px 0px rgba(0, 0, 0, 0.05);
            border-left: 1px solid #dee2e6;
        }
    </style>
@endsection
@section('extra-js')
    <script>
        $(".deleteaction").click(function (e) {
            e.preventDefault();
            var conf = confirm("Are you sure you want to delete this Assigned Action?");
            if(conf)
                window.location = $(this).attr("href");
        });

        $(".completeaction").click(function (e) {
            e.preventDefault();
            var conf = confirm("Are you sure you want to set this Assigned Action as completed?");
            if(conf)
                window.location = $(this).attr("href");
        });

        $(document).ready(function() {
            $('.js-pscroll').each(function () {
                var ps = new PerfectScrollbar(this);

                $(window).on('resize', function () {
                    ps.update();
                })

                $(this).on('ps-x-reach-start', function () {
                    $('.table100-firstcol').removeClass('column-shadow');
                });

                $(this).on('ps-scroll-x', function () {
                    $('.table100-firstcol').addClass('column-shadow');
                });

            })
        })
    </script>
@endsection
                
            
