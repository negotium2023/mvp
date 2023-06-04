@extends('adminlte.default')
@section('title') Edit Custom Report @endsection
@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
        @foreach($reports as $report)
        <a href="{{route('custom_report.index')}}" class="btn btn-dark btn-sm float-right"><i class="fa fa-caret-left"></i> Back</a>
        @endforeach
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <hr/>
        {{Form::open(['url' => route('custom_report.update',$report->id), 'method' => 'post','files'=>true])}}
        @foreach($reports as $report)
            <input type="hidden" id="custom_report_id" value="{{$report->id}}" />
        <div class="form-group mt-3">
            {{Form::label('name', 'Report Name:')}}
            {{Form::text('name',$report->name,['class'=>'form-control form-control-sm'. ($errors->has('name') ? ' is-invalid' : ''),'placeholder'=>'Name'])}}
            @foreach($errors->get('name') as $error)
                <div class="invalid-feedback">
                    {{ $error }}
                </div>
            @endforeach
        </div>
        <div class="form-group mt-3">
            {{Form::label('process', 'Process to use for report:')}}

            {{Form::select('process',$process ,$report->process_id,['class'=>'form-control form-control-sm','id' => 'process'])}}
            @foreach($errors->get('process') as $error)
                <div class="invalid-feedback">
                    {{ $error }}
                </div>
            @endforeach
        </div>
            <div class="form-group mt-3">
                {{Form::label('group', 'Group Report')}}
                <div class="col-lg-4 text-left">
                    <div>
                        <input name="group_report" id="group_report" ref="grouped" type="checkbox" {{($report->group_report == 1 ? 'checked' : '')}} />
                    </div>
                </div>
                @foreach($errors->get('group_report') as $error)
                    <div class="invalid-feedback">
                        {{ $error }}
                    </div>
                @endforeach
            </div>
        <div class="form-group mt-3">
            {{Form::label('activity', 'Activity columns to display on report:')}}

            {{--{{Form::select('activity',$activities ,old('activity'),['class'=>'form-control form-control-sm'])}}
            @foreach($errors->get('activity') as $error)
                <div class="invalid-feedback">
                    {{ $error }}
                </div>
            @endforeach--}}
        </div>
        <div class="form-group pb-3 mb-3" id="activities">

        </div>
            <div class="blackboard-fab mr-3 mb-3">
                <button type="submit" class="btn btn-info btn-lg"><i class="fa fa-save"></i> Save</button>
            </div>
        @endforeach
        {{Form::close()}}
    </div>
@endsection
@section('extra-js')
    <script>
        $(document).ready(function() {

            getSelectedActivities();

            function getActivities() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    dataType: 'json',
                    url: '/get_report_activities/' + $('#process').val(),
                    type: 'GET',
                    data: {process_id: $('#process').val()}
                }).done(function (data) {
                    let rows = '<div class="col-sm-12 pull-left pb-2" style="min-height:50px;display: inline-block"><span style="display: table-cell"><input type="checkbox" name="activity__all" id ="activity_all" value="all" onclick="selectAll()" /></span><span style="display: table-cell;word-break: break-word;padding-left:5px;overflow-wrap: break-word;">Select All</span></div>';
                    $.each(data, function (key, value) {
                        rows = rows + '<div class="col-sm-12 pull-left pb-2" style="min-height:50px;display: inline-block"><span style="display: table-cell"><span style="display: table-cell;word-break: break-word;overflow-wrap: break-word;font-weight:bold;">' + value.name + '</span></div>';
                        $.each(value.activity, function (key, value) {
                            if(value.grouping == 0) {
                                rows = rows + '<div class="col-sm-4 pull-left pb-2" style="min-height:50px;display: inline-block"><span style="display: table-cell"><input type="checkbox" class="cactivity" name="activity[]" value="' + value.id + '" /></span><span style="display: table-cell;word-break: break-word;padding-left:5px;overflow-wrap: break-word;">' + value.name + '</span></div>';
                            } else {
                                rows = rows + '<div class="col-sm-4 pull-left pb-2" style="min-height:50px;display: inline-block"><span style="display: table-cell"><input type="checkbox" class="cactivity" name="activity[]" value="' + value.id + '" /></span><span style="display: table-cell;word-break: break-word;padding-left:5px;overflow-wrap: break-word;color:#F00;">' + value.name + '</span></div>';
                            }
                        });

                    });
                    //alert(rows);
                    $("#activities").html(rows);
                });
            }

            function getSelectedActivities() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    dataType: 'json',
                    url: '/get_report_selected_activities/' + $('#custom_report_id').val(),
                    type: 'GET',
                    data: {custom_report_id: $('#custom_report_id').val()}
                }).done(function (data) {
                    let rows = '<div class="col-sm-12 pull-left pb-2" style="min-height:50px;display: inline-block"><span style="display: table-cell"><input type="checkbox" name="activity__all" id ="activity_all" value="all" onclick="selectAll()" /></span><span style="display: table-cell;word-break: break-word;padding-left:5px;overflow-wrap: break-word;">Select All</span></div>';
                    $.each(data, function (key, value) {
                        rows = rows + '<div class="col-sm-12 pull-left pb-2" style="min-height:50px;display: inline-block"><span style="display: table-cell"><span style="display: table-cell;word-break: break-word;overflow-wrap: break-word;font-weight:bold;">' + value.name + '</span></div>';
                        $.each(value.activity, function (key, value) {
                            if (value.selected == 0) {
                                if(value.grouping == 0) {
                                    rows = rows + '<div class="col-sm-4 pull-left pb-2" style="min-height:50px;display: inline-block"><span style="display: table-cell"><input type="checkbox" class="cactivity" name="activity[]" checked value="' + value.id + '" /></span><span style="display: table-cell;word-break: break-word;padding-left:5px;overflow-wrap: break-word;">' + value.name + '</span></div>';
                                } else {
                                    rows = rows + '<div class="col-sm-4 pull-left pb-2" style="min-height:50px;display: inline-block"><span style="display: table-cell"><input type="checkbox" class="cactivity" name="activity[]" checked value="' + value.id + '" /></span><span style="display: table-cell;word-break: break-word;padding-left:5px;overflow-wrap: break-word;">' + value.name + '&nbsp;&nbsp;&nbsp;<i class="fa fa-object-group" aria-hidden="true" style="font-size:0.75em"></i></span></div>';
                                }
                            } else {
                                if(value.grouping == 0) {
                                    rows = rows + '<div class="col-sm-4 pull-left pb-2" style="min-height:50px;display: inline-block"><span style="display: table-cell"><input type="checkbox" class="cactivity" name="activity[]" value="' + value.id + '" /></span><span style="display: table-cell;word-break: break-word;padding-left:5px;overflow-wrap: break-word;">' + value.name + '</span></div>';
                                } else {
                                    rows = rows + '<div class="col-sm-4 pull-left pb-2" style="min-height:50px;display: inline-block"><span style="display: table-cell"><input type="checkbox" class="cactivity" name="activity[]" value="' + value.id + '" /></span><span style="display: table-cell;word-break: break-word;padding-left:5px;overflow-wrap: break-word;">' + value.name + '&nbsp;&nbsp;&nbsp;<i class="fa fa-object-group" aria-hidden="true" style="font-size:0.75em"></i></span></div>';
                                }
                            }
                        });

                    });
                    //alert(rows);
                    $("#activities").html(rows);
                });
            }

            $('#process').on("change",function(){
                if($('#custom_report_id').val() !== $('#process').val()) {
                    getActivities();
                } else {
                    getSelectedActivities();
                }
            })
        });

        function selectAll(){
            if($("#activity_all").is(':checked')){
                $('.cactivity').each(function () {
                    if($(this).is(':disabled')) {
                        $(this).prop('checked', false);
                    } else {
                        $(this).prop('checked', true);
                    }
                })
            } else {
                $('.cactivity').each(function () {
                    if($(this).is(':disabled')) {
                        $(this).prop('checked', false);
                    } else {
                        $(this).prop('checked', false);
                    }
                })
            }
        }
    </script>
@endsection