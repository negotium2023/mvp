@extends('clients.show')

@section('tab-content')
    <div class="col-lg-5">
        <ul>
            <dt>
                First Name
            </dt>
            <dd>
                @if($client->first_name)
                    {{$client->first_name}}
                @else
                    <small><i>No first names captured</i></small>
                @endif
            </dd>
            <dt>
                Surname
            </dt>
            <dd>
                @if($client->last_name)
                    {{$client->last_name}}
                @else
                    <small><i>No surname captured</i></small>
                @endif
            </dd>
            <dt>
                Initials
            </dt>
            <dd>
                @if($client->initials)
                    {{$client->initials}}
                @else
                    <small><i>No initials captured</i></small>
                @endif
            </dd>
            <dt>
                ID/Passport number
            </dt>
            <dd>
                @if($client->id_number)
                    {{$client->id_number}}
                @else
                    <small><i>No ID/Passport number captured</i></small>
                @endif
            </dd>
            <dt>
                Cellphone number
            </dt>
            <dd>
                @if($client->contact)
                    {{$client->contact}}
                @else
                    <small><i>No contact number captured</i></small>
                @endif
            </dd>
            <dt>
                Email
            </dt>
            <dd>
                @if($client->email)
                    <a href="mailto:{{$client->email}}">{{$client->email}}</a><a href="javascript:void(0)" onclick="sendClientEmail('{{$client->id}}','{!! $client->email !!}')" class="btn btn-sm btn-secondary ml-3">Request Client Feedback</a>
                @else
                    <small><i>No email captured</i></small><a href="javascript:void(0)" onclick="sendClientEmail('{{$client->id}}','{!! $client->email !!}')" class="btn btn-sm btn-secondary ml-3">Request Client Feedback</a>
                @endif
            </dd>
        </ul>
    </div>
    <div class="col-sm-7">
        <attooh-client-basket></attooh-client-basket>
    </div>
@endsection

@section('extra-js')
    {{--<script src="https://code.highcharts.com/highcharts.js"></script>--}}
    <script src="https://code.highcharts.com/modules/no-data-to-display.js"></script>
    <script src="https://rawgit.com/highcharts/rounded-corners/master/rounded-corners.js"></script>
@endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <style>
        a:focus{
            outline:none !important;
            border:0px !important;
        }

        .activity a{
            color: rgba(0,0,0,0.5) !important;
        }

        .activity a.dropdown-item {
            color:#212529 !important;
        }

        .btn-comment{
            padding: .25rem .25rem;
            font-size: .575rem;
            line-height: 1;
            border-radius: .2rem;
        }

        .modal-dialog {
            max-width: 700px;
            margin: 1.75rem auto;
            min-width: 500px;
        }

        .modal .chosen-container, .modal .chosen-container-multi{
            width:98% !important;
        }

        .chosen-container, .chosen-container-multi{
            line-height: 30px;
        }

        .modal-open .modal{
            padding-right: 0px !important;
        }

        .progress { position:relative; width:100%; border: 1px solid #7F98B2; padding: 1px; border-radius: 3px; display:none; }
        .bar { background-color: #B4F5B4; width:0%; height:25px; border-radius: 3px; }
        .percent { position:absolute; display:inline-block; top:3px; left:48%; color: #7F98B2;}
    </style>
@endsection

