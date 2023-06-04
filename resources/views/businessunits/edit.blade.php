@extends('adminlte.default')

@section('title') Edit Business Unit @endsection

@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
        <a href="{{route('businessunits.index')}}" class="btn btn-dark btn-sm float-right"><i class="fa fa-caret-left"></i> Back</a>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <hr />
        @foreach($businessunits as $result)
            {{Form::open(['url' => route('businessunits.update',$result), 'method' => 'post','files'=>true,'autocomplete'=>'off'])}}

            <div class="form-group mt-3">
                {{Form::label('name', 'Name')}}
                {{Form::text('name',$result->name,['class'=>'form-control form-control-sm'. ($errors->has('name') ? ' is-invalid' : ''),'placeholder'=>'Name'])}}
                @foreach($errors->get('name') as $error)
                    <div class="invalid-feedback">
                        {{ $error }}
                    </div>
                @endforeach
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-sm">Save</button>
            </div>

            {{Form::close()}}
    </div>
    @endforeach
@endsection