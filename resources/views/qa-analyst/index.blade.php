@extends('adminlte.default')

@section('title') Analyst QA Report @endsection

@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
        @if(!empty(request()->all()))
            <a target="_blank" href="{{route('qaanalystreport.index', ['analyst' => $_GET['analyst']??null, 'date_from' => $_GET['date_from']??null, 'date_to'=>$_GET['date_to']??null, 'export'=>'pdf'])}}" class="btn btn-dark btn-sm float-right"><i class="fas fa-file-pdf"></i> Export PDF</a>
        @endif
        <hr>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        @if(empty(\request()->all()))
            <form>
                <div class="form-row">
                    <div class="col">
                        {!! Form::label('analyst', 'Analyst') !!}
                        {!! Form::select('analyst', $analysts, null, ['class' => 'form-control', 'placeholder'=>'Select An Analyst']) !!}
                    </div>
                    <div class="col">
                        {!! Form::label('date_from', 'Date From') !!}
                        {!! Form::date('date_from', old('date_from'), ['class'=>'form-control', 'placeholder'=>'Date From']) !!}
                    </div>
                    <div class="col">
                        {!! Form::label('date_to', 'Date To') !!}
                        {!! Form::date('date_to', old('date_to'), ['class'=>'form-control', 'placeholder'=>'Date To']) !!}
                    </div>
                    <div class="col">
                        <div style="margin-bottom: .5rem;">&nbsp;</div>
                        {{Form::submit('Generate Report', ['class'=>'btn btn-dark'])}}
                    </div>
                </div>
            </form>
        @endif

        @if(!empty(\request()->all()))
                <div class="row bg-info">
                    <div class="col-sm-3"><strong>Analyst Name: {{$user->first_name.' '.$user->last_name}}</strong></div>
                    <div class="col-sm-3"><strong>Period Selected: {{ $_GET['date_from']??null }} - {{ $_GET['date_to']??null }}</strong></div>
                    <div class="col-sm-2"><strong>Total Passed For Period: {{$total_passed}}</strong></div>
                    <div class="col-sm-2"><strong>Total Failed For Period: {{$total_failed}}</strong></div>
                    <div class="col-sm-2"><strong>Total Not Reviewed For Period: {{$total_not_reviewed}}</strong></div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Special title treatment</h5>
                    </div>
                    <div class="card-body">

                        <div class="form-row">
                            <div class="col-md-6 row">
                                <ul class="list-group list-group-flush" style="width: 100%">
                                    <li class="list-group-item" style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">Strapline</div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$strapline["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$strapline["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$strapline["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">Correct ME listed</div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$correct_me_listed["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$correct_me_listed["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$correct_me_listed["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">Family Tree</div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$family_tree["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$family_tree["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$family_tree["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">Clients With Exposure And No Exposure Identified Correctly</div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$client_exposure_not_identified_correctly["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$client_exposure_not_identified_correctly["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$client_exposure_not_identified_correctly["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-md-6 row ml-1">
                                <ul class="list-group list-group-flush" style="width: 100%">
                                    <li class="list-group-item" style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                Footer updated
                                            </div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$footer_updated["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$footer_updated["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$footer_updated["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item" style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                Page Numbers updated
                                            </div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$page_numbers_updated["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$page_numbers_updated["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$page_numbers_updated["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">All RP included </div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$all_rp_included["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$all_rp_included["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$all_rp_included["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Overview information</h5>
                    </div>
                    <div class="card-body">

                        <div class="form-row">
                            <div class="col-md-6 row">
                                <ul class="list-group list-group-flush" style="width: 100%">
                                    <li class="list-group-item" style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">Client information</div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$client_information["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$client_information["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$client_information["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">KYC date</div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$kyc_date["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$kyc_date["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$kyc_date["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">PEP</div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$pep["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$pep["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$pep["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">STR</div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$str["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$str["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$str["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">Adverse media</div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$adverse_media["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$adverse_media["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$adverse_media["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-md-6 row ml-1">
                                <ul class="list-group list-group-flush" style="width: 100%">
                                    <li class="list-group-item" style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                Relationship
                                            </div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$relationship["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$relationship["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$relationship["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item" style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                Casa
                                            </div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$casa["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$casa["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$casa["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">Sanctions </div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$sanctions["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$sanctions["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$sanctions["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">Litigation </div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$litigation["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$litigation["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$litigation["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">In line with V5 of Standard </div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$v5_standard["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$v5_standard["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$v5_standard["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Product Exposure</h5>
                    </div>
                    <div class="card-body">

                        <div class="form-row">
                            <div class="col-md-6 row">
                                <ul class="list-group list-group-flush" style="width: 100%">
                                    <li class="list-group-item" style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">All products inclued</div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$all_products_included["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$all_products_included["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$all_products_included["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">Wimi & WFS account listed</div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$wimi_wfs_account_listed["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$wimi_wfs_account_listed["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$wimi_wfs_account_listed["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-md-6 row ml-1">
                                <ul class="list-group list-group-flush" style="width: 100%">
                                    <li class="list-group-item" style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                Linked accounts included
                                            </div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$linked_accounts_included["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$linked_accounts_included["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$linked_accounts_included["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item" style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                Email sent to Carissa for CIB, WIMI & WFS Clients
                                            </div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$email_sent_cib_wimi_wfs_clients["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$email_sent_cib_wimi_wfs_clients["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$email_sent_cib_wimi_wfs_clients["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">STR And TA</h5>
                    </div>
                    <div class="card-body">

                        <div class="form-row">
                            <div class="col-md-6 row">
                                <ul class="list-group list-group-flush" style="width: 100%">
                                    <li class="list-group-item" style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">All info included</div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$all_info_included["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$all_info_included["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$all_info_included["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">Expected account activity</div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$expected_account_activity["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$expected_account_activity["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$expected_account_activity["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-md-6 row ml-1">
                                <ul class="list-group list-group-flush" style="width: 100%">
                                    <li class="list-group-item" style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                Review date correct
                                            </div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$review_date_correct["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$review_date_correct["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$review_date_correct["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item" style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                TA has a conclusion
                                            </div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$ta_has_conclusion["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$ta_has_conclusion["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$ta_has_conclusion["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Adverse Media</h5>
                    </div>
                    <div class="card-body">

                        <div class="form-row">
                            <div class="col-md-6 row">
                                <ul class="list-group list-group-flush" style="width: 100%">
                                    <li class="list-group-item" style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">Listed in chronological order</div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$listed_in_chronological_order["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$listed_in_chronological_order["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$listed_in_chronological_order["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">RB-Summary of article</div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$rb_summary_article["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$rb_summary_article["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$rb_summary_article["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-md-6 row ml-1">
                                <ul class="list-group list-group-flush" style="width: 100%">
                                    <li class="list-group-item" style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                EB-exact extract from article
                                            </div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$eb_exact_extract_from_article["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$eb_exact_extract_from_article["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$eb_exact_extract_from_article["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item" style="margin: 0 0!important;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                Does it align with the background and TA
                                            </div>
                                            <div class="col-md-8 row">
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$does_it_align_with_background_ta["passed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                    <div class="col-md-4"><strong>{{$does_it_align_with_background_ta["failed"]}}</strong></div>
                                                </div>
                                                <div class="col row">
                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                    <div class="col-md-2"><strong>{{$does_it_align_with_background_ta["not_reviewed"]}}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="container-fluid">
                                            @if(empty(\request()->all()))
                                                <form>
                                                    <div class="form-row">
                                                        <div class="col">
                                                            {!! Form::label('analyst', 'Analyst') !!}
                                                            {!! Form::select('analyst', $analysts, null, ['class' => 'form-control', 'placeholder'=>'Select An Analyst']) !!}
                                                        </div>
                                                        <div class="col">
                                                            {!! Form::label('date_from', 'Date From') !!}
                                                            {!! Form::date('date_from', old('date_from'), ['class'=>'form-control', 'placeholder'=>'Date From']) !!}
                                                        </div>
                                                        <div class="col">
                                                            {!! Form::label('date_to', 'Date To') !!}
                                                            {!! Form::date('date_to', old('date_to'), ['class'=>'form-control', 'placeholder'=>'Date To']) !!}
                                                        </div>
                                                        <div class="col">
                                                            <div style="margin-bottom: .5rem;">&nbsp;</div>
                                                            {{Form::submit('Generate Report', ['class'=>'btn btn-dark'])}}
                                                        </div>
                                                    </div>
                                                </form>
                                            @endif

                                            @if(!empty(\request()->all()))
                                                <div class="row bg-info">
                                                    <div class="col-sm-3"><strong>Analyst Name: {{$user->first_name.' '.$user->last_name}}</strong></div>
                                                    <div class="col-sm-3"><strong>Period Selected: {{ $_GET['date_from']??null }} - {{ $_GET['date_to']??null }}</strong></div>
                                                    <div class="col-sm-2"><strong>Total Passed For Period: {{$total_passed}}</strong></div>
                                                    <div class="col-sm-2"><strong>Total Failed For Period: {{$total_failed}}</strong></div>
                                                    <div class="col-sm-2"><strong>Total Not Reviewed For Period: {{$total_not_reviewed}}</strong></div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="card-title">Special title treatment</h5>
                                                    </div>
                                                    <div class="card-body">

                                                        <div class="form-row">
                                                            <div class="col-md-6 row">
                                                                <ul class="list-group list-group-flush" style="width: 100%">
                                                                    <li class="list-group-item" style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">Strapline</div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$strapline["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$strapline["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$strapline["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">Correct ME listed</div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$correct_me_listed["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$correct_me_listed["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$correct_me_listed["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">Family Tree</div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$family_tree["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$family_tree["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$family_tree["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">Clients With Exposure And No Exposure Identified Correctly</div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$client_exposure_not_identified_correctly["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$client_exposure_not_identified_correctly["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$client_exposure_not_identified_correctly["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            <div class="col-md-6 row ml-1">
                                                                <ul class="list-group list-group-flush" style="width: 100%">
                                                                    <li class="list-group-item" style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                Footer updated
                                                                            </div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$footer_updated["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$footer_updated["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$footer_updated["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="list-group-item" style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                Page Numbers updated
                                                                            </div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$page_numbers_updated["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$page_numbers_updated["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$page_numbers_updated["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">All RP included </div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$all_rp_included["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$all_rp_included["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$all_rp_included["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="card-title">Overview information</h5>
                                                    </div>
                                                    <div class="card-body">

                                                        <div class="form-row">
                                                            <div class="col-md-6 row">
                                                                <ul class="list-group list-group-flush" style="width: 100%">
                                                                    <li class="list-group-item" style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">Client information</div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$client_information["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$client_information["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$client_information["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">KYC date</div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$kyc_date["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$kyc_date["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$kyc_date["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">PEP</div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$pep["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$pep["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$pep["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">STR</div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$str["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$str["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$str["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">Adverse media</div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$adverse_media["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$adverse_media["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$adverse_media["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            <div class="col-md-6 row ml-1">
                                                                <ul class="list-group list-group-flush" style="width: 100%">
                                                                    <li class="list-group-item" style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                Relationship
                                                                            </div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$relationship["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$relationship["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$relationship["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="list-group-item" style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                Casa
                                                                            </div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$casa["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$casa["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$casa["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">Sanctions </div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$sanctions["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$sanctions["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$sanctions["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">Litigation </div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$litigation["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$litigation["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$litigation["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">In line with V5 of Standard </div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$v5_standard["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$v5_standard["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$v5_standard["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="card-title">Product Exposure</h5>
                                                    </div>
                                                    <div class="card-body">

                                                        <div class="form-row">
                                                            <div class="col-md-6 row">
                                                                <ul class="list-group list-group-flush" style="width: 100%">
                                                                    <li class="list-group-item" style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">All products inclued</div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$all_products_included["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$all_products_included["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$all_products_included["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">Wimi & WFS account listed</div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$wimi_wfs_account_listed["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$wimi_wfs_account_listed["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$wimi_wfs_account_listed["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            <div class="col-md-6 row ml-1">
                                                                <ul class="list-group list-group-flush" style="width: 100%">
                                                                    <li class="list-group-item" style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                Linked accounts included
                                                                            </div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$linked_accounts_included["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$linked_accounts_included["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$linked_accounts_included["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="list-group-item" style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                Email sent to Carissa for CIB, WIMI & WFS Clients
                                                                            </div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$email_sent_cib_wimi_wfs_clients["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$email_sent_cib_wimi_wfs_clients["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$email_sent_cib_wimi_wfs_clients["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="card-title">STR And TA</h5>
                                                    </div>
                                                    <div class="card-body">

                                                        <div class="form-row">
                                                            <div class="col-md-6 row">
                                                                <ul class="list-group list-group-flush" style="width: 100%">
                                                                    <li class="list-group-item" style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">All info included</div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$all_info_included["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$all_info_included["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$all_info_included["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">Expected account activity</div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$expected_account_activity["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$expected_account_activity["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$expected_account_activity["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            <div class="col-md-6 row ml-1">
                                                                <ul class="list-group list-group-flush" style="width: 100%">
                                                                    <li class="list-group-item" style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                Review date correct
                                                                            </div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$review_date_correct["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$review_date_correct["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$review_date_correct["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="list-group-item" style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                TA has a conclusion
                                                                            </div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$ta_has_conclusion["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$ta_has_conclusion["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$ta_has_conclusion["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="card-title">Adverse Media</h5>
                                                    </div>
                                                    <div class="card-body">

                                                        <div class="form-row">
                                                            <div class="col-md-6 row">
                                                                <ul class="list-group list-group-flush" style="width: 100%">
                                                                    <li class="list-group-item" style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">Listed in chronological order</div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$listed_in_chronological_order["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$listed_in_chronological_order["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$listed_in_chronological_order["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="list-group-item"  style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">RB-Summary of article</div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$rb_summary_article["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$rb_summary_article["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$rb_summary_article["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            <div class="col-md-6 row ml-1">
                                                                <ul class="list-group list-group-flush" style="width: 100%">
                                                                    <li class="list-group-item" style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                EB-exact extract from article
                                                                            </div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$eb_exact_extract_from_article["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$eb_exact_extract_from_article["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$eb_exact_extract_from_article["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="list-group-item" style="margin: 0 0!important;">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                Does it align with the background and TA
                                                                            </div>
                                                                            <div class="col-md-8 row">
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Passed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$does_it_align_with_background_ta["passed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-8"><strong>Failed:</strong></div>
                                                                                    <div class="col-md-4"><strong>{{$does_it_align_with_background_ta["failed"]}}</strong></div>
                                                                                </div>
                                                                                <div class="col row">
                                                                                    <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                                                    <div class="col-md-2"><strong>{{$does_it_align_with_background_ta["not_reviewed"]}}</strong></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
        @endif
    </div>
@endsection