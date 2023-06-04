@extends('layouts.proud')

@section('proud-form')
@if(Session::has('message'))
<p class="success-message">
    {!! Session::get('message') !!}
</p>
@endif

@if(Session::has('error'))
<p class="error-message">
    {!! Session::get('error') !!}
</p>
@endif

<div class="client-detail">
     <div id="myTabContent" class="tab-content">
        <div id="client_portal" role="tabpanel" aria-labelledby="client_portal-tab" class="tab-pane fade p-3 active show">
            <form method="POST" id="login" action="{{route('portal.client.login')}}" autocomplete="off">
                {{ csrf_field() }}
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fa fa-user"></i>
                            </div>
                        </div>
                        {{Form::email('email',old('email'),['class'=>'form-control form-control-sm'. ($errors->has('email') ? ' is-invalid' : ''),'placeholder'=>'Email','required','autofocus'])}}
                    </div>
                    @foreach($errors->get('email') as $error)
                        <div class="invalid-feedback">
                            {{ $error }}
                        </div>
                    @endforeach
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fa fa-user"></i>
                            </div>
                        </div>
                        {{Form::text('id_number',old('id_number'),['class'=>'form-control form-control-sm'. ($errors->has('id_number') ? ' is-invalid' : ''),'placeholder'=>'ID Number','required'])}}
                    </div>
                    @foreach($errors->get('id_number') as $error)
                        <div class="invalid-feedback">
                            {{ $error }}
                        </div>
                    @endforeach
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fa fa-lock"></i>
                            </div>
                        </div>
                        {{Form::password('password',['class'=>'form-control form-control-sm'. ($errors->has('password') ? ' is-invalid' : ''),'placeholder'=>'Password','required'])}}
                    </div>
                    @foreach($errors->get('password') as $error)
                        <div class="invalid-feedback">
                            {{ $error }}
                        </div>
                    @endforeach
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-sm btn-primary btn-block">
                        Log In
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection

@section('proud-footer')
    {{--<div class="card-footer text-center p-3">
        First time here? <a href="{{route('register')}}">Register</a>
    </div>--}}
@endsection

@section('proud-extra')
    <a class="password-request" href="{{route('portal.client.password.request')}}">
        Forgot Your Password?
    </a>

    <script>
        var isMobile = {
            Android: function() {
                return navigator.userAgent.match(/Android/i);
            },
            BlackBerry: function() {
                return navigator.userAgent.match(/BlackBerry/i);
            },
            iOS: function() {
                return navigator.userAgent.match(/iPhone|iPad|iPod/i);
            },
            Opera: function() {
                return navigator.userAgent.match(/Opera Mini/i);
            },
            Windows: function() {
                return navigator.userAgent.match(/IEMobile/i);
            },
            any: function() {
                return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
            }
        };

    </script>

    <style>
        .success-message {
            color: green;
        }

        .error-message {
            color: red;
        }

        .nav-tabs {
            border-bottom: 0px !important;
        }

        .detail-nav {
            border-bottom: 1px solid #eefafd;
            margin-left: -15px !important;
            margin-right: -15px !important;
            width: unset;
        }

        .nav {
            display: flex;
            flex-wrap: wrap;
            padding-left: 0;
            margin-bottom: 0;
            list-style: none;
        }

        .client-detail ul.nav-tabs .nav-item.show .nav-link, .client-detail .nav-tabs .nav-link.active {
            background-color: transparent !important;
            border-color: transparent !important;
            color: #06496f !important;
            border-bottom: 4px solid #4fc6e5 !important;
        }

        .tabbable .nav-tabs .nav-link {
            border: 0px solid #ced4da;
            border-top-left-radius: .25rem;
            border-top-right-radius: .25rem;
            margin-left: 7px;
            margin-bottom: -1px;
        }
    </style>
@endsection
