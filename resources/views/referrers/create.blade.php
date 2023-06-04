@extends('adminlte.default')

@section('title') Create Referrer @endsection

@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
        <a href="{{route('referrers.index')}}" class="btn btn-dark btn-sm float-right d-none d-md-block d-lg-block"><i class="fa fa-caret-left"></i> Back</a>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <hr />
    <div class="row mt-3 d-block d-md-none d-lg-none">
        <div class="col-sm-12">
            <div class="w-50 p-1 float-left">
                <a href="{{route('clients.create')}}" class="btn {{active('clients.create','active')}} btn-outline-dark w-100 float-left"><i class="fa fa-plus"></i> Client</a>
            </div>
            <div class="w-50 p-1 float-right">
                <a href="{{route('referrers.create')}}" class="btn {{active('referrers.create','active')}} btn-outline-dark w-100 float-right"><i class="fa fa-plus"></i> Referrer</a>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="row mt-3">
    <div class="col-sm-12">

    <div class="individual">
    {{Form::open(['url' => route('referrers.store'), 'method' => 'post','class'=>'mt-3'])}}
        <div class="form-group">
            {{Form::label('contact', 'Referral Type')}}
            {{Form::select('referrer_type',$referrer_type,($errors->has('referral_type')) ? old('referrer_type') : 0,['class'=>'form-control'])}}
            @foreach($errors->get('referrer_type') as $error)
                <div class="invalid-feedback">
                    {{ $error }}
                </div>
            @endforeach
        </div>
        <div class="form-group">
        {{Form::label('first_name', 'First Name')}}
        {{Form::text('first_name',old('first_name'),['class'=>'form-control'. ($errors->has('first_name') ? ' is-invalid' : ''),'placeholder'=>'First Name'])}}
        @foreach($errors->get('first_name') as $error)
            <div class="invalid-feedback">
                {{$error}}
            </div>
        @endforeach
    </div>

    <div class="form-group">
        {{Form::label('last_name', 'Last Name')}}
        {{Form::text('last_name',old('last_name'),['class'=>'form-control'. ($errors->has('last_name') ? ' is-invalid' : ''),'placeholder'=>'Last Name'])}}
        @foreach($errors->get('last_name') as $error)
            <div class="invalid-feedback">
                {{$error}}
            </div>
        @endforeach
        <small id="last_name" class="form-text text-muted">
            Leave empty if referrer is an entity
        </small>
    </div>

    <div class="form-group">
        {{Form::label('email', 'Email')}}
        {{Form::email('email',old('email'),['class'=>'form-control'. ($errors->has('email') ? ' is-invalid' : ''),'placeholder'=>'Email'])}}
        @foreach($errors->get('email') as $error)
            <div class="invalid-feedback">
                {{$error}}
            </div>
        @endforeach
    </div>

    <div class="form-group">
        {{Form::label('contact', 'Contact Number')}}
        {{Form::text('contact',old('contact'),['class'=>'form-control'. ($errors->has('contact') ? ' is-invalid' : ''),'placeholder'=>'Contact Number'])}}
        @foreach($errors->get('contact') as $error)
            <div class="invalid-feedback">
                {{$error}}
            </div>
        @endforeach
    </div>

    <div class="form-group">
        {{Form::label('contact', 'UHY Referral?')}}
        {{Form::select('uhy_referral',[0 => 'No', 1 => 'Yes'],($errors->has('uhy_referral')) ? old('uhy_referral') : '',['class'=>'form-control','placeholder'=>'Please select...'])}}
        @foreach($errors->get('uhy_referral') as $error)
            <div class="invalid-feedback">
                {{ $error }}
            </div>
        @endforeach
    </div>

    <div class="form-group uhy_fields d-none">
        {{Form::label('contact', 'UHY Firm Name')}}
        {{Form::text('uhy_firm_name',old('uhy_firm_name'),['class'=>'form-control'. ($errors->has('uhy_firm_name') ? ' is-invalid' : ''),'placeholder'=>'UHY Firm Name'])}}
        @foreach($errors->get('uhy_firm_name') as $error)
            <div class="invalid-feedback">
                {{$error}}
            </div>
        @endforeach
    </div>

    <div class="form-group uhy_fields d-none">
        {{Form::label('contact', 'UHY Contact Number')}}
        {{Form::text('uhy_contact',old('uhy_contact'),['class'=>'form-control'. ($errors->has('uhy_contact') ? ' is-invalid' : ''),'placeholder'=>'UHY Contact Number'])}}
        @foreach($errors->get('uhy_contact') as $error)
            <div class="invalid-feedback">
                {{$error}}
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
    </div>
@endsection
@section('extra-js')
    <script>

        $('select[name="uhy_referral"]').change(function () {
            if($('select[name="uhy_referral"]').val() == 1)
                $('.uhy_fields').removeClass('d-none').addClass('d-block');
            else
                $('.uhy_fields').removeClass('d-block').addClass('d-none');
        });
    </script>
@endsection