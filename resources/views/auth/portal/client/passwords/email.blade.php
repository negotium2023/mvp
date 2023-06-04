@extends('layouts.proud')

@section('proud-form')
    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form class="form-horizontal" method="POST" action="{{ route('portal.client.password.email') }}">
        {{ csrf_field() }}

        <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <i class="fa fa-user"></i>
                    </div>
                </div>
                {{Form::email('email',old('email'),['class'=>'form-control'. ($errors->has('email') ? ' is-invalid' : ''),'placeholder'=>'Email address','required','autofocus'])}}
            </div>
            @foreach($errors->get('email') as $error)
                <div class="invalid-feedback">
                    {{ $error }}
                </div>
            @endforeach
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-sm btn-primary btn-block">
                Send Password Reset Link
            </button>
        </div>
    </form>
@endsection

@section('proud-footer')
@endsection

@section('proud-extra')
    <a class="password-request" href="{{route('portal.client.login')}}">
        Log In
    </a>
@endsection
