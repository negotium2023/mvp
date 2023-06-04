@extends('flow.default')

@section('title') Edit @foreach($process_groups as $process_group) {{$process_group->name}} @endforeach @endsection

@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
        <div class="nav-btn-group">
            <a href="javascript:void(0)" onclick="saveProcessGroup()" class="btn btn-success btn-lg mt-3 ml-2 float-right">Save</a>
            <a href="{{route('processesgroup.index')}}" class="btn btn-outline-primary btn-sm mt-3">Back</a>
        </div>
    </div>
@endsection

@section('content')
    <div class="content-container page-content">
        <div class="row col-md-12 h-100 pr-0">
            @yield('header')
            <div class="container-fluid index-container-content">
                <div class="table-responsive h-100">
                    @foreach($process_groups as $process_group)
                    {{Form::open(['url' => route('processesgroup.update',$process_group->id), 'method' => 'post','class'=>'mt-3 mb-3','autocomplete' => 'off','id'=>'save_process_group_form'])}}
                    {{Form::label('name', 'Name')}}
                    {{Form::text('name',$process_group->name,['class'=>'form-control form-control-sm'. ($errors->has('name') ? ' is-invalid' : ''),'placeholder'=>'Name'])}}
                    @foreach($errors->get('name') as $error)
                        <div class="invalid-feedback">
                            {{ $error }}
                        </div>
                    @endforeach

                    {{Form::close()}}
                    {{-- todo notifications --}}
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extra-js')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
@endsection