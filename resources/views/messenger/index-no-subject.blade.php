@extends('flow.default')
@section('title') Messages @endsection
@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
        <div class="nav-btn-group">

            <div class="form-row">
                <div class="ml-2 mt-3">
                    <div class="btn-group">
                        <a href="{{route('messages.create')}}" class="btn btn-primary btn-sm float-right">New Message</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="content-container page-content">
        <div class="row col-md-12 h-100 pr-0">
            @yield('header')
            <div class="container-fluid index-container-content">
                <div class="table-responsive h-100">
                    @include('messenger.partials.flash')

                    @each('messenger.partials.thread-no-subject', $threads, 'thread', 'messenger.partials.no-threads')
                </div>
            </div>
        </div>
    </div>
@endsection