@extends('flow.portal_client.default')

@section('title') {{($client->company == '' || $client->company == 'N/A' || $client->company == 'n/a' ? $client->first_name.' '.$client->last_name : $client->company )}} @endsection

@section('content')
    <div class="client-sidemenu elevation-3">
        <img src="{{route('portal.client.avatar',$client)}}" class="client-avatar"/>
        <dd class="seperator">
            &nbsp;
        </dd>
        <dt>
            Full Name
        </dt>
        <dd>
            {{$client->first_name}}
        </dd>
        <dt>
            Surname
        </dt>
        <dd>
            {{$client->last_name}}
        </dd>
        <dt>
            ID Number
        </dt>
        <dd>
            {{$client->id_number}}
        </dd>
        <dd class="seperator">
            &nbsp;
        </dd>
        <dt>
            Email Address
        </dt>
        <dd>
            {{$client->email}}
        </dd>
        <dt>
            Contact Number
        </dt>
        <dd>
            {{(substr($client->contact,0,1) == '0' ? '+27'.substr($client->contact,1) : $client->contact )}}
        </dd>
        <dt>
            Reference
        </dt>
        <dd>
            {{($client->reference == '' ? '&nbsp;' : $client->reference)}}
        </dd>
    </div>
    <div class="nav-client client">
        <ul class="nav nav-tabs nav-fill">
            <li class="nav-item">
                <a class="nav-link" href="{{route('portal.client')}}">Client Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="">Document Vault</a>
            </li>
        </ul>
    </div>
    
    <div class="content-container client-content" style="">
        <div class="col-md-12 p-0 h-100">
            <div class="client-detail">
                <div class="content-container m-0 p-0">
                    <div class="container-fluid container-title">
                        <h3> Document Vault </h3>
                        <div class="nav-btn-group">
                                    <div>
                                        <a href="{{route('portal.client.createdocuments')}}" class="btn btn-primary btn-sm float-right mt-3">Add Document</a>
                                    </div>
                        </div>
                    </div> 
                    <div class="container-fluid index-container-content">
                        <div class="table-responsive h-100">
                            <table class="table table-bordered table-sm table-hover table-fixed">
                                <thead>
                                    <tr>
                                        <th>Name <span id="more-info" data-toggle="tooltip" data-placement="top" title="" data-original-title="Tick a checkbox to select a document">?</span></th> <th>Type</th> <th>Size</th> <th>Uploader</th> <th>Added</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                @forelse($client->documents as $document)
                                    @if($document->display_in_client_portal == 1)
                                        <tr>
                                            <td><a href="{{route('portal.client.getdocument',['q'=>$document->file])}}" target="_blank">{{$document->name}}</a></td>
                                            <td>{{$document->type()}}</td>
                                            <td>{{$document->size()}}</td>
                                            <td>@if($document->user_id != 0) 
                                                <img src="{{route('portal.client.getavatar',['q'=>$document->user->avatar])}}" class="blackboard-avatar blackboard-avatar-inline" alt="{{$document->user->name()}}"/> 
                                                @else 
                                                Uploaded by client 
                                            @endif</td>
                                            <td>{{$document->created_at->diffForHumans()}}</td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">No documents match those criteria.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>

@endsection
@section('extra-css')
    <style>
        
    </style>
@endsection
@section('extra-js')
    <script>
        
    </script>
@endsection