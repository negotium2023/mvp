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
                        <h3> Create Document </h3>
                        <div class="nav-btn-group">
                            <a href="javascript:void(0)" onclick="saveDocument()" class="btn btn-success btn-lg mt-3 ml-2 float-right">Save</a>
                            <a href="{{route('portal.client.documents')}}" class="btn btn-outline-primary btn-sm mt-3">Back</a>
                        </div>
                    </div>
                    <div class="container-fluid index-container-content">
                        <div class="table-responsive h-100">
                            {{Form::open(['url' => route('portal.client.storedocuments'), 'method' => 'post','files'=>true,'id'=>'save_document_form'])}}

                            <div class="form-group mt-3">
                                {{Form::label('name', 'Name')}}
                                {{Form::text('name',old('name'),['class'=>'form-control'. ($errors->has('name') ? ' is-invalid' : ''),'placeholder'=>'Name'])}}
                                @foreach($errors->get('name') as $error)
                                    <div class="invalid-feedback">
                                        {{ $error }}
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-group">
                                {{Form::label('file', 'File')}}
                                {{Form::file('file',['class'=>'form-control'. ($errors->has('file') ? ' is-invalid' : ''),'placeholder'=>'File'])}}
                                @foreach($errors->get('file') as $error)
                                    <div class="invalid-feedback">
                                        {{ $error }}
                                    </div>
                                @endforeach
                            </div>

                            @if(request()->has('client') && request()->has('process_id') && request()->has('step_id'))
                                {{Form::hidden('client',request()->input('client'))}}
                                {{Form::hidden('process_id',request()->input('process_id'))}}
                                {{Form::hidden('step_id',request()->input('step_id'))}}
                            @endif

                            {{Form::close()}}
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
function saveDocument(){
    $('#save_document_form').submit();
}
    </script>
@endsection