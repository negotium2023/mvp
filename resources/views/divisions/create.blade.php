@extends('flow.default')

@section('title') Create Division @endsection

@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
        <a href="{{route('locations.index')}}" class="btn btn-dark btn-sm float-right"><i class="fa fa-caret-left"></i> Back</a>
    </div>
@endsection

@section('content')
    <div class="content-container">
        <div class="row col-md-12">
            @yield('header')
            <div class="container-fluid">
                {{Form::open(['url' => route('divisions.store'), 'method' => 'post'])}}

                <div class="form-group mt-3">
                    {{Form::label('name', 'Name')}}
                    {{Form::text('name',old('name'),['class'=>'form-control'. ($errors->has('name') ? ' is-invalid' : ''),'placeholder'=>'Name'])}}
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
        </div>
    </div>
@endsection