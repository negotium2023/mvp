@extends('adminlte.default')

@section('title') Edit Referrer @endsection

@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
        <a href="{{route('referrers.index')}}" class="btn btn-dark btn-sm float-right"><i class="fa fa-caret-left"></i> Back</a>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <hr />
    {{Form::open(['url' => route('referrers.update',$referrer), 'method' => 'put','class'=>'mt-3'])}}
    <div class="form-group">
        {{Form::label('contact', 'Referral Type')}}
        {{Form::select('referrer_type',$referrer_type,($errors->has('referral_type')) ? old('referrer_type') : $referrer->referrer_type,['class'=>'form-control'])}}
        @foreach($errors->get('referrer_type') as $error)
            <div class="invalid-feedback">
                {{ $error }}
            </div>
        @endforeach
    </div>
    <div class="form-group">
        {{Form::label('first_name', 'First Name')}}
        {{Form::text('first_name',$referrer->first_name,['class'=>'form-control'. ($errors->has('first_name') ? ' is-invalid' : ''),'placeholder'=>'First Name'])}}
        @foreach($errors->get('first_name') as $error)
            <div class="invalid-feedback">
                {{$error}}
            </div>
        @endforeach
    </div>

    <div class="form-group">
        {{Form::label('last_name', 'Last Name')}}
        {{Form::text('last_name',$referrer->last_name,['class'=>'form-control'. ($errors->has('last_name') ? ' is-invalid' : ''),'placeholder'=>'Last Name'])}}
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
        {{Form::email('email',$referrer->email,['class'=>'form-control'. ($errors->has('email') ? ' is-invalid' : ''),'placeholder'=>'Email'])}}
        @foreach($errors->get('email') as $error)
            <div class="invalid-feedback">
                {{$error}}
            </div>
        @endforeach
    </div>

    <div class="form-group">
        {{Form::label('contact', 'Contact Number')}}
        {{Form::text('contact',$referrer->contact,['class'=>'form-control'. ($errors->has('contact') ? ' is-invalid' : ''),'placeholder'=>'Contact Number'])}}
        @foreach($errors->get('contact') as $error)
            <div class="invalid-feedback">
                {{$error}}
            </div>
        @endforeach
    </div>

    <div class="form-group">
        {{Form::label('contact', 'UHY Referral?')}}
        {{Form::select('uhy_referral',[0 => 'No', 1 => 'Yes'],(isset($referrer->uhy_referral) ? $referrer->uhy_referral : ''),['class'=>'form-control','placeholder'=>'Please select...'])}}
        @foreach($errors->get($referrer->uhy_referral) as $error)
            <div class="invalid-feedback">
                {{ $error }}
            </div>
        @endforeach
    </div>

    <div class="form-group uhy_fields">
        {{Form::label('contact', 'UHY Firm Name')}}
        {{Form::text('uhy_firm_name',$referrer->uhy_firm_name,['class'=>'form-control'. ($errors->has('uhy_firm_name') ? ' is-invalid' : ''),'placeholder'=>'UHY Firm Name'])}}
        @foreach($errors->get('uhy_firm_name') as $error)
            <div class="invalid-feedback">
                {{$error}}
            </div>
        @endforeach
    </div>

    <div class="form-group uhy_fields">
        {{Form::label('contact', 'UHY Contact Number')}}
        {{Form::text('uhy_contact',$referrer->uhy_contact,['class'=>'form-control'. ($errors->has('uhy_contact') ? ' is-invalid' : ''),'placeholder'=>'UHY Contact Number'])}}
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
@endsection
@section('extra-js')
    <script>
        @if($referrer->uhy_referral == 0)
            $('.uhy_fields').hide();
        @endif

        $('select[name="uhy_referral"]').change(function () {
            if($('select[name="uhy_referral"]').val() == 1)
                $('.uhy_fields').show();
            else
                $('.uhy_fields').hide();
        });
    </script>
@endsection