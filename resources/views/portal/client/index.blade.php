@extends('flow.portal_client.default')

@section('title') {{($client->company == '' || $client->company == 'N/A' || $client->company == 'n/a' ? $client->first_name.' '.$client->last_name : $client->company )}} @endsection

@section('content')
    @include('portal.client.client-sidebar')
    <div class="nav-client client">
        <ul class="nav nav-tabs nav-fill">
            <li class="nav-item">
                <a class="nav-link active" href="{{route('portal.client')}}">Client Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('portal.client.documents')}}">Document Vault</a>
            </li>
        </ul>
    </div>
    <div class="content-container client-content" style="">

            <div class="col-md-12 p-0 h-100">
            <div class="client-detail">
                <div class="container-fluid detail-nav">
                    <nav class="tabbable">
                        <div class="nav nav-tabs">
                            <a id="personal_details-tab" data-toggle="tab" href="#personal_details" role="tab" aria-controls="default" aria-selected="true" class="nav-link active show">Personal Details</a> 
                            <a id="children__dependants-tab" data-toggle="tab" href="#children__dependants" role="tab" aria-controls="default" aria-selected="false" class="nav-link">Children &amp; Dependants</a> 
                            <a id="banking_details-tab" data-toggle="tab" href="#banking_details" role="tab" aria-controls="default" aria-selected="false" class="nav-link">Banking Details</a>
                        </div>
                    </nav> 
                    <div class="nav-btn-group">
                        <!-- <a href="http://localhost:8001/clients/2452/edit/35/188" class="btn btn-primary float-right">Edit Details</a> -->
                    </div>
                </div> 
                <div id="myTabContent" class="tab-content">
                    <div id="personal_details" role="tabpanel" aria-labelledby="personal_details-tab" class="tab-pane fade p-3 active show">
                        <div class="row grid-items">
                            <div class="col-md-4">
                                <h5>Client Details</h5> 
                                <div style="display: inline-block; padding: 7px; margin-bottom: 7px; width: 100%;"><div>
                                    <span class="form-label">First Names</span>
                                </div> 
                                <div class="form-text">
                                    {{$client->first_name}}
                                </div>
                            </div> 
                            <div style="display: inline-block; padding: 7px; margin-bottom: 7px; width: 100%;">
                                <div><span class="form-label">Surname</span></div> 
                                <div class="form-text">
                                    {{$client->last_name}}
                                </div>
                            </div> 
                            <div style="display: inline-block; padding: 7px; margin-bottom: 7px; width: 100%;">
                                <div><span class="form-label">Initials</span></div> 
                                <div class="form-text">
                                    {{$client->initials}}
                                </div>
                            </div> 
                            <div style="display: inline-block; padding: 7px; margin-bottom: 7px; width: 100%;">
                                <div><span class="form-label">Known As</span></div> <div class="form-text">
                                    {{$client->first_name}}
                                </div>
                            </div> 
                            <div style="display: inline-block; padding: 7px; margin-bottom: 7px; width: 100%;">
                                <div><span class="form-label">ID/Passport Number</span></div> 
                                <div class="form-text">
                                    {{$client->id_number}}
                                </div>
                            </div> 
                            <div style="display: inline-block; padding: 7px; margin-bottom: 7px; width: 100%;">
                                <div><span class="form-label">Email</span></div> 
                                <div class="form-text">
                                    {{$client->email}}
                                </div>
                            </div> 
                            <div style="display: inline-block; padding: 7px; margin-bottom: 7px; width: 100%;">
                                <div><span class="form-label">Cellphone Number</span></div>
                                <div class="form-text">
                                    {{(substr($client->contact,0,1) == '0' ? '+27'.substr($client->contact,1) : $client->contact )}}
                                </div>
                            </div> 
                            <div style="display: inline-block; padding: 7px; margin-bottom: 7px; width: 100%;">
                                <div><span class="form-label">Reference</span></div> 
                                <div class="form-text">
                                    {{$client->reference}}
                                </div>
                            </div>
                            </div> 
                                <div class="col-md-4">
                                    <h5>Principal Life Details</h5> 
                                    @foreach ($principalLifeDetails as $principalLifeDetails)
                                    <div style="display: inline-block; padding: 7px; margin-bottom: 7px; width: 100%; margin-left: calc(0%);">
                                        <div><span class="form-label">{{$principalLifeDetails->name}}</span></div> 
                                        <div class="form-text">
                                            @if(isset($principalLifeDetails->value) && $principalLifeDetails->value != '')
                                                {{$principalLifeDetails->value}}
                                            @else
                                                <small><i>No value captured.</i></small>
                                            @endif
                                        </div>
                                    </div> 
                                    @endforeach
                                    
                                        </div> 
                                        <div class="col-md-4">
                                            <h5>Spouse Details</h5> 
                                            @foreach ($spouseDetails as $spouseDetail)
                                            <div style="display: inline-block; padding: 7px; margin-bottom: 7px; width: 100%; margin-left: calc(0%);">
                                                <div><span class="form-label">{{$spouseDetail->name}}</span></div> 
                                                <div class="form-text">
                                                    @if(isset($spouseDetail->value) && $spouseDetail->value != '')
                                                        {{$spouseDetail->value}}
                                                    @else
                                                        <small><i>No value captured.</i></small>
                                                    @endif
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div> 
                                <div id="children__dependants" role="tabpanel" aria-labelledby="children__dependants-tab" class="tab-pane fade p-3">
                                    <div class="row grid-items">
                                        <div class="col-md-4">
                                            @foreach ($childrenAndDependencies as $childrenAndDependency)
                                            <div style="display: inline-block; padding: 7px; margin-bottom: 7px; width: 100%; margin-left: calc(0%);">
                                                <div><span class="form-label">{{$childrenAndDependency->name}}</span></div> 
                                                <div class="form-text">
                                                    @if(isset($childrenAndDependency->value) && $childrenAndDependency->value != '')
                                                        {{$childrenAndDependency->value}}
                                                    @else
                                                        <small><i>No value captured.</i></small>
                                                    @endif
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div> 
                                <div id="banking_details" role="tabpanel" aria-labelledby="banking_details-tab" class="tab-pane fade p-3">
                                    <div class="row grid-items">
                                        <div class="col-md-4"><h5>Banking Details</h5> 
                                            @foreach($bankingDetails as $bankingDetail)
                                            <div style="display: inline-block; padding: 7px; margin-bottom: 7px; width: 100%; margin-left: calc(0%);">
                                                <div><span class="form-label">{{$bankingDetail->name}}</span></div> 
                                                <div class="form-text">
                                                    @if(isset($bankingDetail->value) && $bankingDetail->value != '')
                                                        {{$bankingDetail->value}}
                                                    @else
                                                        <small><i>No value captured.</i></small>
                                                    @endif
                                                </div>
                                            </div> 
                                            @endforeach
                                        </div>
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