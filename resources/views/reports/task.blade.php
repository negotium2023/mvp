@extends('flow.default')

@section('title') Task Report @endsection

@section('content')
    <div class="content-container page-content">
        <div class="row col-md-12 h-100">
            <div class="container-fluid container-title">
                <h3>@yield('title')</h3>
            </div>
            <div class="container-fluid container-content">
                <div class="table-responsive ">
                    <table class="table table-sm table-bordered blackboard-recents dataTable" id="task_table">
                        <thead>
                        <!-- Add search filters to the datatable -->
                        {{--<tr>
                            <th colspan="4">
                                Task Report
                                <a href="{{route('clients.index')}}" class="btn btn-primary btn-sm float-right"><i class="fa fa-eye"></i> View all</a>
                            </th>
                        </tr>--}}
                        <tr>
                            <th>
                                Task Description
                            </th>
                            <th>
                                Client
                            </th>
                            <th>
                                Due Date
                            </th>
                            <th>
                                Assigned to
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($tasks as $task)
                            <tr>
                                <td>{{$task->name}}</td>
                                <td>{{ isset($task->card->client_name) ? $task->card->client_name : '' }}</td>
                                <td>{{date('Y-m-d', strtotime($task->due_date))}}</td>
                                <td>{{isset($task->assigned->first_name) ? $task->assigned->first_name : ''}} {{isset($task->assigned->last_name) ? $task->assigned->last_name : ''}}</td>
                            </tr>
                            @isset($task->subTasks)
                                @foreach($task->subTasks as $subTask)
                                    <tr>
                                        <td><div style="margin-left: 20px;">{{$subTask->name}}</div></td>
                                        <td>{{ isset($subTask->card->client_name) ? $subTask->card->client_name : '' }}</td>
                                        <td>{{date('Y-m-d', strtotime($subTask->due_date))}}</td>
                                        <td>{{isset($subTask->assigned->first_name) ? $subTask->assigned->first_name : ''}} {{isset($subTask->assigned->last_name) ? $subTask->assigned->last_name : ''}}</td>
                                    </tr>
                                @endforeach
                            @endisset
                        @empty
                            <tr>
                                <td colspan="4">No tasks found for the search criteria</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extra-js')
    <script src="{{asset('adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script>
        $(function () {
            $('#task_table').DataTable({
                'paging'      : true,
                'lengthChange': true,
                'searching'   : true,
                'ordering'    : false,
                'info'        : true,
                'autoWidth'   : false,
                "pageLength": 15
            });

            // $("#task_table_length").html().replace(' entries','');
        });
    </script>
@endsection
@section('extra-css')
    <link rel="stylesheet" href="{{asset('adminlte/plugins/datatables/jquery.dataTables.min.css')}}">
    <style>
        #task_table_filter {
            margin-right: 8px;
        }
    </style>
@endsection
