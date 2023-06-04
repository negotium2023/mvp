@extends('layouts.app')

@section('title') Capture Calendar Event @endsection

@section('header')
    <h1>@yield('title')</h1>
@endsection

@section('content')
    {{Form::open(['url' => route('calendarevents.store'), 'method' => 'post'])}}
    <div class="row mt-3">
        <div class="col-lg-6">
            <div class="form-group">
                {{Form::label('title', 'Title')}}
                {{Form::text('title',old('title'),['class'=>'form-control'. ($errors->has('title') ? ' is-invalid' : ''),'placeholder'=>'Title'])}}
                @foreach($errors->get('first_name') as $error)
                    <div class="invalid-feedback">
                        {{$error}}
                    </div>
                @endforeach
            </div>
            <div class="form-group">
                {{Form::label('start_date', 'Start Date: ')}}
                {{Form::text('start_date',null,['class'=>'form-control'. ($errors->has('start_date') ? ' is-invalid' : ''),'autofocus'])}}
                @foreach($errors->get('date') as $error)
                    <div class="invalid-feedback">
                        {{$error}}
                    </div>
                @endforeach
            </div>
            <div class="form-group">
                {{Form::label('end_date', 'End Date: ')}}
                {{Form::text('end_date',null,['class'=>'form-control'. ($errors->has('end_date') ? ' is-invalid' : ''),'autofocus'])}}
                <span class="input-group date">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
                @foreach($errors->get('date') as $error)
                    <div class="invalid-feedback">
                        {{$error}}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn">Save</button>
    </div>
    {{Form::close()}}
@endsection