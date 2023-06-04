@extends('flow.default')

@section('title') Email History @endsection

@section('header')
    <div class="container-fluid container-title float-left clear" style="margin-bottom: 7px;">
        <h3 style="padding-bottom: 5px;">@yield('title')</h3>
        <div class="nav-btn-group" style="margin-top:15px;padding-bottom: 5px;">
            <form autocomplete="off">
                <div class="form-row">
                    <div class="form-group">
                        <div class="input-group">
                            {{Form::search('q',old('query'),['class'=>'form-control search','placeholder'=>'Search...'])}}
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default" style="height: 2.35rem;"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('content')
    <div class="content-container page-content">
        <div class="row col-md-12 h-100 pr-0">
            <div class="container-fluid index-container-content h-100">
                @yield('header')
                <div class="table-responsive w-100 float-left" style="height: 66%;">
                    <table class="table table-bordered table-hover table-sm table-fixed">
                        <thead>
                        <tr>
                            <th>Date Sent</th>
                            <th>To</th>
                            <th>Subject</th>
                            <th>Sent By</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($emails as $result)
                            <tr>
                                <td><a href="{{route('maillog.show',$result)}}">{{$result->date}}</a></td>
                                <td><a href="{{route('maillog.show',$result)}}">{{$result->to}}</a></td>
                                <td><a href="{{route('maillog.show',$result)}}">{{$result->subject}}</a></td>
                                <td><a href="{{route('maillog.show',$result)}}">{{$result->user["first_name"]}} {{$result->user["last_name"]}}</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
