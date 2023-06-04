@extends('flow.default')
@section('title') {{ ($thread->subject == '' ? 'Messages' : $thread->subject) }} @endsection
@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
        <div class="nav-btn-group">
            <a href="javascript:void(0)" onclick="saveMessage()" class="btn btn-success btn-lg mt-3 ml-2 float-right">Save</a>
            <a href="{{route('messages')}}" class="btn btn-outline-primary btn-sm mt-3 float-right">Back</a>
        </div>
    </div>
@endsection
@section('content')
    <div class="content-container page-content">
        <div class="row col-md-12 h-100 pr-0">
            @yield('header')
            <div class="container-fluid index-container-content">
                <div class="table-responsive h-100">
                    <div class="col-md-12">
                        @each('messenger.partials.messages', $thread->messages, 'message')
                    </div>
                            <div class="col-md-12">
                        @include('messenger.partials.form-message')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection