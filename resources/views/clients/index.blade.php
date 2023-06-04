@extends('flow.default')

@section('title') Clients @endsection

@section('header')
    <div class="container-fluid container-title float-left clear" style="margin-top: 1%;margin-bottom: 7px;">
        <h3 style="padding-bottom: 5px;">@yield('title')</h3>
        <div class="nav-btn-group" style="top:29%;padding-bottom: 5px;">
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
                    <div class="table-responsive float-left" style="margin-right:2%;height: 25%;width:48.5%;">
                        <table class="table table-borderless table-sm table-fixed">
                            <thead>
                            <tr>
                                <th nowrap style="box-shadow: none;"><h4>Birthdays</h4></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($clients_birthdays as $clientb)
                                <tr>
                                    <td>{{$clientb["company"]}} - {{$clientb["when"]}}</td>
                                    <td class="last">
                                        <a href="javascript:void(0)" onclick="composeWhatsapp({{$clientb['id']}})" class="btn btn-sm btn-primary" style="line-height: 1rem !important;">Send Message</a>
                                    </td>
                                </tr >
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center"><small class="alert alert-info w-100 d-block text-muted">There are no upcoming birthdays.</small></td>
                                </tr>
                            @endforelse
                            </tbody>

                        </table>
                    </div>
                    <div class="table-responsive float-right clear pl-1" style="height: 25%;width:48.5%;">
                        <table class="table table-borderless table-sm table-fixed billboard-table">
                            <thead>
                            <tr>
                                <th nowrap style="box-shadow: none;"><h4>@if(in_array(76,$user_offices)) Priority Clients for the Week @else Practice Billboard @endif</h4></th>
                                <th class="last" nowrap style="box-shadow: none;vertical-align: top;">
                                    <a href="javascript:void(0)" onclick="composeBillboardMessage()" class="btn btn-sm btn-primary" style="line-height: 1rem !important;">Add a message</a>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($messages as $message)
                                <tr>
                                    <td class="billboard" colspan="100%"><a href="javascript:void(0)" style="display: block;" onclick="showBillboardMessage({{$message->id}})">{{$message->message}}</a>
                                        {{--<span class="pull-right btn-danger clickable close-icon" onclick="deleteBillboardMessage({{$message->id}})" data-effect="fadeOut"><i class="fa fa-times"></i></span>
                                        <div class="card-block">
                                            <blockquote class="card-blockquote">
                                                <div class="blockquote-body">{{$message->message}}</div>
                                            </blockquote>
                                        </div>--}}
                                    </td>
                                </tr >
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center"><small class="alert alert-info w-100 d-block text-muted">There are no messages to display.</small></td>
                                </tr>
                            @endforelse
                            </tbody>

                        </table>
                    </div>
                    @yield('header')
                    <div class="table-responsive w-100 float-left" style="height: 66%;">
                        <table class="table table-bordered table-hover table-sm table-fixed">
                            <thead>
                            <tr>
                                <th nowrap>@sortablelink('company', 'Name')</th>
                                <th nowrap>@sortablelink('email', 'Email')</th>
                                <th nowrap>@sortablelink('cell', 'Cellphone Number')</th>
                                <th nowrap>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($clients as $client)
                                    <tr>
                                        <td><a href="{{route('clients.overview',[$client["id"],$client["process_id"],$client["step_id"]])}}">{{(isset($client["company"] ) && $client["company"] != ' ' ? $client["company"]  : 'Not Captured')}}</a></td>
                                        <td>{{!is_null($client["email"]) ? $client["email"] : ''}}</td>
                                        <td>{{!is_null($client["contact"]) ? $client["contact"] : ''}}</td>
                                        <td class="last">
                                            <a href="javascript:void(0)" data-toggle="tooltip" data-html="true" class="btn btn-sm btn-primary" onclick="startNewApplication({{$client['id']}},{{$client['process_id']}})" title="Start a new application"><i class="fas fa-folder-plus"></i> </a>
                                            <a href="javascript:void(0)" data-toggle="tooltip" data-html="true" class="btn btn-sm btn-secondary" onclick="showOpenApplications({{$client['id']}})" title="Open applications"><i class="fas fa-folder-open"></i> </a>
                                            <a href="javascript:void(0)" data-toggle="tooltip" data-html="true" class="btn btn-sm btn-secondary" onclick="showClosedApplications({{$client['id']}})" title="Closed applications"><i class="fas fa-folder"></i> </a>
                                            <a href="javascript:void(0)" data-toggle="tooltip" data-html="true" class="btn btn-sm btn-danger" onclick="submitForSignatures({{$client['id']}},{{$client["process_id"]}},{{$client["step_id"]}})" title="Submit an application for signatures"><i class="fas fa-share-square"></i> </a>
                                        </td>
                                    </tr >
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center"><small class="text-muted">No clients match those criteria.</small></td>
                                </tr>
                            @endforelse
                            </tbody>

                        </table>
                    </div>
                 </div>

                  @include('client.modals.index')
            </div>
         </div>

@endsection
