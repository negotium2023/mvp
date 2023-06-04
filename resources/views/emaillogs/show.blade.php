@extends('flow.default')

@section('title') View Email @endsection

@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
        <div class="nav-btn-group">
            <a href="{{route('maillog.index')}}" class="btn btn-outline-primary mt-2">Back</a>
        </div>
    </div>
@endsection

@section('content')
    <div class="content-container page-content">
        <div class="row col-md-12 h-100 pr-0">
            <div class="container-fluid index-container-content h-100">
                @yield('header')
                <div class="table-responsive w-100 float-left" style="height: 66%;">
                    <table class="table table-bordered table-sm">
                        <tbody>

                            <tr>
                                <th>Date Sent:</th>
                                <td>{{$mails["date"]}}</td>
                            </tr>
                            <tr>
                                <th>Sent By:</th>
                                <td>{{$mails->user->first_name}} {{$mails->user->last_name}}</td>
                            </tr>
                            <tr>
                                <th>To:</th>
                                <td>{{$mails["to"]}}</td>
                            </tr>
                            <tr>
                                <th>Subject:</th>
                                <td>{{$mails["subject"]}}</td>
                            </tr>
                            @if(count($attachments) != null)
                                <tr>
                                    <th>Attachments:</th>
                                    <td>
                                        @foreach($attachments as $attachment)
                                            <a href="{{storage_path($attachment->attachment)}}" download>{{$attachment->name}}</a><br />
                                        @endforeach
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <th>Body:</th>
                                <td class="email_body">{!! $mails["body"] !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
