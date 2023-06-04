@extends('adminlte.default')

@section('title')
    QA Checklist
@endsection

@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
        <a href="{{redirect()->back()}}" class="btn btn-dark btn-sm float-right"><i class="fa fa-caret-left"></i> Back</a>
    </div>
@endsection

@section('content')
    <div class="container-fluid">


    {{Form::open(['url' => (!isset($qa_checklist) ? route('qaChecklist.store', $client_id) :route('qaChecklist.update', $qa_checklist->id)), 'method' => (!isset($qa_checklist) ? 'POST':'PATCH'),'autocomplete'=>'off'])}}

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
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="strapline" value="1" {{isset($qa_checklist) && $qa_checklist->strapline ? "checked" : ''}} class="custom-control-input" id="strapline">
                                            <label class="custom-control-label" for="strapline">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="strapline" value="0" {{isset($qa_checklist) && !$qa_checklist->strapline ? "checked" : ''}} class="custom-control-input" id="strapline_false">
                                            <label class="custom-control-label" for="strapline_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="strapline" {{isset($qa_checklist) && !isset($qa_checklist->strapline) ? "checked" :''}} class="custom-control-input" id="strapline_nr">
                                            <label class="custom-control-label" for="strapline_nr">Not Reviewed</label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item"  style="margin: 0 0!important;">
                                <div class="row">
                                    <div class="col-md-4">Correct ME listed</div>
                                    <div class="col-md-8 row">
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="correct_me_listed" {{isset($qa_checklist) && $qa_checklist->correct_me_listed ? "checked" : ''}} value="1" class="custom-control-input" id="correct_me_listed">
                                            <label class="custom-control-label" for="correct_me_listed">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="correct_me_listed" {{isset($qa_checklist) && !$qa_checklist->correct_me_listed ? "checked" : '' }} value="0" class="custom-control-input" id="correct_me_listed_false">
                                            <label class="custom-control-label" for="correct_me_listed_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="correct_me_listed" {{isset($qa_checklist) && !isset($qa_checklist->correct_me_listed) ? "checked" : ''}} class="custom-control-input" id="correct_me_listed_nr">
                                            <label class="custom-control-label" for="correct_me_listed_nr">Not Reviewed</label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item"  style="margin: 0 0!important;">
                                <div class="row">
                                    <div class="col-md-4">Family Tree</div>
                                    <div class="col-md-8 row">
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="family_tree" value="1" {{isset($qa_checklist) && $qa_checklist->family_tree ? "checked" : ''}} class="custom-control-input" id="family_tree">
                                            <label class="custom-control-label" for="family_tree">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="family_tree" value="0" {{isset($qa_checklist) && !$qa_checklist->family_tree ? "checked" : ''}} class="custom-control-input" id="family_tree_false">
                                            <label class="custom-control-label" for="family_tree_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="family_tree" {{isset($qa_checklist) && !isset($qa_checklist->family_tree) ? "checked" : ''}} class="custom-control-input" id="family_tree_nr">
                                            <label class="custom-control-label" for="family_tree_nr">Not Reviewed</label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item"  style="margin: 0 0!important;">
                                <div class="row">
                                    <div class="col-md-4">Clients With Exposure And No Exposure Identified Correctly</div>
                                    <div class="col-md-8 row">
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="client_exposure_not_identified_correctly" value="1" {{isset($qa_checklist) && $qa_checklist->client_exposure_not_identified_correctly ?"checked" : ''}} class="custom-control-input" id="client_exposure_not_identified_correctly">
                                            <label class="custom-control-label" for="client_exposure_not_identified_correctly">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="client_exposure_not_identified_correctly" value="0" {{isset($qa_checklist) && !$qa_checklist->client_exposure_not_identified_correctly ?"checked" : ''}} class="custom-control-input" id="client_exposure_not_identified_correctly_false">
                                            <label class="custom-control-label" for="client_exposure_not_identified_correctly_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="client_exposure_not_identified_correctly" {{isset($qa_checklist) && !isset($qa_checklist->client_exposure_not_identified_correctly) ? "checked" : ''}} class="custom-control-input" id="client_exposure_not_identified_correctly_nr">
                                            <label class="custom-control-label" for="client_exposure_not_identified_correctly_nr">Not Reviewed</label>
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
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="footer_updated" value="1" {{isset($qa_checklist) && $qa_checklist->footer_updated ? "checked" : ''}} class="custom-control-input" id="footer_updated">
                                            <label class="custom-control-label" for="footer_updated">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="footer_updated" value="0" {{isset($qa_checklist) && !$qa_checklist->footer_updated ? "checked" : ''}} class="custom-control-input" id="footer_updated_false">
                                            <label class="custom-control-label" for="footer_updated_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="footer_updated" {{isset($qa_checklist) && !isset($qa_checklist->footer_updated) ? "checked" : ''}} class="custom-control-input" id="footer_updated_nr">
                                            <label class="custom-control-label" for="footer_updated_nr">Not Reviewed</label>
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
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="page_numbers_updated" value="1" {{isset($qa_checklist) && $qa_checklist->page_numbers_updated ? "checked" : ''}} class="custom-control-input" id="page_numbers_updated">
                                            <label class="custom-control-label" for="page_numbers_updated">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="page_numbers_updated" value="0" {{isset($qa_checklist) && !$qa_checklist->page_numbers_updated ? "checked" : ''}} class="custom-control-input" id="page_numbers_updated_false">
                                            <label class="custom-control-label" for="page_numbers_updated_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="page_numbers_updated" {{isset($qa_checklist) && !isset($qa_checklist->page_numbers_updated) ? "checked" : ''}} class="custom-control-input" id="page_numbers_updated_nr">
                                            <label class="custom-control-label" for="page_numbers_updated_nr">Not Reviewed</label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item"  style="margin: 0 0!important;">
                                <div class="row">
                                    <div class="col-md-4">All RP included </div>
                                    <div class="col-md-8 row">
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="all_rp_included" value="1" {{isset($qa_checklist) && $qa_checklist->all_rp_included ? "checked" : ''}} class="custom-control-input" id="all_rp_included">
                                            <label class="custom-control-label" for="all_rp_included">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="all_rp_included" value="0" {{isset($qa_checklist) && !$qa_checklist->all_rp_included ? "checked" : ''}} class="custom-control-input" id="all_rp_included_false">
                                            <label class="custom-control-label" for="all_rp_included_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="all_rp_included"  {{isset($qa_checklist) && !isset($qa_checklist->all_rp_included) ? "checked" : ''}} class="custom-control-input" id="all_rp_included_rn">
                                            <label class="custom-control-label" for="all_rp_included_rn">Not Reviewed</label>
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
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="client_information" value="1" {{isset($qa_checklist) && $qa_checklist->client_information ? "checked" : ''}} class="custom-control-input" id="client_information">
                                            <label class="custom-control-label" for="client_information">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="client_information" value="0" {{isset($qa_checklist) && !$qa_checklist->client_information ? "checked" : ''}} class="custom-control-input" id="client_information_false">
                                            <label class="custom-control-label" for="client_information_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="client_information" {{isset($qa_checklist) && !isset($qa_checklist->client_information) ? "checked" : ''}} class="custom-control-input" id="client_information_nr">
                                            <label class="custom-control-label" for="client_information_nr">Not Reviewed</label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item"  style="margin: 0 0!important;">
                                <div class="row">
                                    <div class="col-md-4">KYC date</div>
                                    <div class="col-md-8 row">
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="kyc_date" value="1" {{isset($qa_checklist) && $qa_checklist->kyc_date ? "checked" : ''}} class="custom-control-input" id="kyc_date">
                                            <label class="custom-control-label" for="kyc_date">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="kyc_date" value="0" {{isset($qa_checklist) && !$qa_checklist->kyc_date ? "checked" : ''}} class="custom-control-input" id="kyc_date_false">
                                            <label class="custom-control-label" for="kyc_date_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="kyc_date" {{isset($qa_checklist) && !isset($qa_checklist->kyc_date) ? "checked" : ''}} class="custom-control-input" id="kyc_date_nr">
                                            <label class="custom-control-label" for="kyc_date_nr">Not Reviewed</label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item"  style="margin: 0 0!important;">
                                <div class="row">
                                    <div class="col-md-4">PEP</div>
                                    <div class="col-md-8 row">
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="pep" value="1" {{isset($qa_checklist) && $qa_checklist->pep ? "checked" : ''}} class="custom-control-input" id="pep">
                                            <label class="custom-control-label" for="pep">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="pep" value="0" {{isset($qa_checklist) && !$qa_checklist->pep ? "checked" : ''}} class="custom-control-input" id="pep_false">
                                            <label class="custom-control-label" for="pep_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="pep" {{isset($qa_checklist) && !isset($qa_checklist->pep) ? "checked" : ''}} class="custom-control-input" id="pep_nr">
                                            <label class="custom-control-label" for="pep_nr">Not Reviewed</label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item"  style="margin: 0 0!important;">
                                <div class="row">
                                    <div class="col-md-4">STR</div>
                                    <div class="col-md-8 row">
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="str" value="1" {{isset($qa_checklist) && $qa_checklist->str ? "checked" : ''}} class="custom-control-input" id="str">
                                            <label class="custom-control-label" for="str">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="str" value="0" {{isset($qa_checklist) && !$qa_checklist->str ? "checked" : ''}} class="custom-control-input" id="str_false">
                                            <label class="custom-control-label" for="str_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="str" {{isset($qa_checklist) && !isset($qa_checklist->str) ? "checked" : ''}} class="custom-control-input" id="str_nr">
                                            <label class="custom-control-label" for="str_nr">Not Reviewed</label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item"  style="margin: 0 0!important;">
                                <div class="row">
                                    <div class="col-md-4">Adverse media</div>
                                    <div class="col-md-8 row">
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="adverse_media" value="1" {{isset($qa_checklist) && $qa_checklist->adverse_media ? "checked" : ''}} class="custom-control-input" id="adverse_media">
                                            <label class="custom-control-label" for="adverse_media">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="adverse_media" value="0" {{isset($qa_checklist) && !$qa_checklist->adverse_media ? "checked" : ''}} class="custom-control-input" id="adverse_media_false">
                                            <label class="custom-control-label" for="adverse_media_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="adverse_media" {{isset($qa_checklist) && !isset($qa_checklist->adverse_media) ? "checked" : ''}} class="custom-control-input" id="adverse_media_nr">
                                            <label class="custom-control-label" for="adverse_media_nr">Not Reviewed</label>
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
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="relationship" {{isset($qa_checklist) && $qa_checklist->relationship ? "checked" : ''}} value="1" class="custom-control-input" id="relationship">
                                            <label class="custom-control-label" for="relationship">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="relationship" value="0" {{isset($qa_checklist) && !$qa_checklist->relationship ? "checked" : ''}} class="custom-control-input" id="relationship_false">
                                            <label class="custom-control-label" for="relationship_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="relationship" {{isset($qa_checklist) && !isset($qa_checklist->relationship) ? "checked" : ''}} class="custom-control-input" id="relationship_nr">
                                            <label class="custom-control-label" for="relationship_nr">Not Reviewed</label>
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
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="casa" value="1" {{isset($qa_checklist) && $qa_checklist->casa ? "checked" : ''}} class="custom-control-input" id="casa">
                                            <label class="custom-control-label" for="casa">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="casa" value="0" {{isset($qa_checklist) && !$qa_checklist->casa ? "checked" : ''}} class="custom-control-input" id="casa_false">
                                            <label class="custom-control-label" for="casa_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="casa" {{isset($qa_checklist) && !isset($qa_checklist->casa) ? "checked" : ''}} class="custom-control-input" id="casa_nr">
                                            <label class="custom-control-label" for="casa_nr">Not Reviewed</label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item"  style="margin: 0 0!important;">
                                <div class="row">
                                    <div class="col-md-4">Sanctions </div>
                                    <div class="col-md-8 row">
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="sanctions" value="1" {{isset($qa_checklist) && $qa_checklist->sanctions ? "checked" : ''}} class="custom-control-input" id="sanctions">
                                            <label class="custom-control-label" for="sanctions">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="sanctions" value="0" {{isset($qa_checklist) && !$qa_checklist->sanctions ? "checked" : ''}} class="custom-control-input" id="sanctions_false">
                                            <label class="custom-control-label" for="sanctions_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="sanctions" {{isset($qa_checklist) && !isset($qa_checklist->sanctions) ? "checked" : ''}} class="custom-control-input" id="sanctions_nr">
                                            <label class="custom-control-label" for="sanctions_nr">Not Reviewed</label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item"  style="margin: 0 0!important;">
                                <div class="row">
                                    <div class="col-md-4">Litigation </div>
                                    <div class="col-md-8 row">
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="litigation" value="1" {{isset($qa_checklist) && $qa_checklist->litigation ? "checked" : ''}} class="custom-control-input" id="litigation">
                                            <label class="custom-control-label" for="litigation">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="litigation" value="0" {{isset($qa_checklist) && !$qa_checklist->litigation ? "checked" : ''}} class="custom-control-input" id="litigation_false">
                                            <label class="custom-control-label" for="litigation_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="litigation" {{isset($qa_checklist) && !isset($qa_checklist->litigation) ? "checked" : ''}} class="custom-control-input" id="litigation_nr">
                                            <label class="custom-control-label" for="litigation_nr">Not Reviewed</label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item"  style="margin: 0 0!important;">
                                <div class="row">
                                    <div class="col-md-4">In line with V5 of Standard </div>
                                    <div class="col-md-8 row">
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="v5_standard" value="1" {{isset($qa_checklist) && $qa_checklist->v5_standard ? "checked" : ''}} class="custom-control-input" id="v5_standard">
                                            <label class="custom-control-label" for="v5_standard">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="v5_standard" value="0" {{isset($qa_checklist) && !$qa_checklist->v5_standard ? "checked" : ''}} class="custom-control-input" id="v5_standard_false">
                                            <label class="custom-control-label" for="v5_standard_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="v5_standard" {{isset($qa_checklist) && !isset($qa_checklist->v5_standard) ? "checked" : ''}} class="custom-control-input" id="v5_standard_nr">
                                            <label class="custom-control-label" for="v5_standard_nr">Not Reviewed</label>
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
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="all_products_included" value="1" {{isset($qa_checklist) && $qa_checklist->all_products_included ? "checked" : ''}} class="custom-control-input" id="all_products_included">
                                            <label class="custom-control-label" for="all_products_included">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="all_products_included" value="0" {{isset($qa_checklist) && !$qa_checklist->all_products_included ? "checked" : ''}} class="custom-control-input" id="all_products_included_false">
                                            <label class="custom-control-label" for="all_products_included_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="all_products_included" {{isset($qa_checklist) && !isset($qa_checklist->all_products_included) ? "checked" : ''}} class="custom-control-input" id="all_products_included_nr">
                                            <label class="custom-control-label" for="all_products_included_nr">Not Reviewed</label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item"  style="margin: 0 0!important;">
                                <div class="row">
                                    <div class="col-md-4">Wimi & WFS account listed</div>
                                    <div class="col-md-8 row">
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="wimi_wfs_account_listed" value="1" {{isset($qa_checklist) && $qa_checklist->wimi_wfs_account_listed ? "checked" : ''}} class="custom-control-input" id="wimi_wfs_account_listed">
                                            <label class="custom-control-label" for="wimi_wfs_account_listed">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="wimi_wfs_account_listed" value="0" {{isset($qa_checklist) && !$qa_checklist->wimi_wfs_account_listed ? "checked" : ''}} class="custom-control-input" id="wimi_wfs_account_listed_false">
                                            <label class="custom-control-label" for="wimi_wfs_account_listed_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="wimi_wfs_account_listed" {{isset($qa_checklist) && !isset($qa_checklist->wimi_wfs_account_listed) ? "checked" : ''}} class="custom-control-input" id="wimi_wfs_account_listed_nr">
                                            <label class="custom-control-label" for="wimi_wfs_account_listed_nr">Not Reviewed</label>
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
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="linked_accounts_included" value="1" {{isset($qa_checklist) && $qa_checklist->linked_accounts_included ? "checked" : ''}} class="custom-control-input" id="linked_accounts_included">
                                            <label class="custom-control-label" for="linked_accounts_included">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="linked_accounts_included" value="0" {{isset($qa_checklist) && !$qa_checklist->linked_accounts_included ? "checked" : ''}} class="custom-control-input" id="linked_accounts_included_false">
                                            <label class="custom-control-label" for="linked_accounts_included_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="linked_accounts_included" {{isset($qa_checklist) && !isset($qa_checklist->linked_accounts_included) ? "checked" : ''}} class="custom-control-input" id="linked_accounts_included_nr">
                                            <label class="custom-control-label" for="linked_accounts_included_nr">Not Reviewed</label>
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
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="email_sent_cib_wimi_wfs_clients" value="1" {{isset($qa_checklist) && $qa_checklist->email_sent_cib_wimi_wfs_clients ? "checked" : ''}} class="custom-control-input" id="email_sent_cib_wimi_wfs_clients">
                                            <label class="custom-control-label" for="email_sent_cib_wimi_wfs_clients">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="email_sent_cib_wimi_wfs_clients" value="0" {{isset($qa_checklist) && !$qa_checklist->email_sent_cib_wimi_wfs_clients ? "checked" : ''}} class="custom-control-input" id="email_sent_cib_wimi_wfs_clients_false">
                                            <label class="custom-control-label" for="email_sent_cib_wimi_wfs_clients_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="email_sent_cib_wimi_wfs_clients" {{isset($qa_checklist) && !isset($qa_checklist->email_sent_cib_wimi_wfs_clients) ? "checked" : ''}} class="custom-control-input" id="email_sent_cib_wimi_wfs_clients_nr">
                                            <label class="custom-control-label" for="email_sent_cib_wimi_wfs_clients_nr">Not Reviewed</label>
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
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="all_info_included" value="1" {{isset($qa_checklist) && $qa_checklist->all_info_included ? "checked" : ''}} class="custom-control-input" id="all_info_included">
                                            <label class="custom-control-label" for="all_info_included">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="all_info_included" value="0" {{isset($qa_checklist) && !$qa_checklist->all_info_included ? "checked" : ''}} class="custom-control-input" id="all_info_included_false">
                                            <label class="custom-control-label" for="all_info_included_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="all_info_included" {{isset($qa_checklist) && !isset($qa_checklist->all_info_included) ? "checked" : ''}} class="custom-control-input" id="all_info_included_nr">
                                            <label class="custom-control-label" for="all_info_included_nr">Not Reviewed</label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item"  style="margin: 0 0!important;">
                                <div class="row">
                                    <div class="col-md-4">Expected account activity</div>
                                    <div class="col-md-8 row">
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="expected_account_activity" value="1" {{isset($qa_checklist) && $qa_checklist->expected_account_activity ? "checked" : ''}} class="custom-control-input" id="expected_account_activity">
                                            <label class="custom-control-label" for="expected_account_activity">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="expected_account_activity" value="0" {{isset($qa_checklist) && !$qa_checklist->expected_account_activity ? "checked" : ''}} class="custom-control-input" id="expected_account_activity_false">
                                            <label class="custom-control-label" for="expected_account_activity_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="expected_account_activity" {{isset($qa_checklist) && !$qa_checklist->expected_account_activity ? "checked" : ''}} class="custom-control-input" id="expected_account_activity_nr">
                                            <label class="custom-control-label" for="expected_account_activity_nr">Not Reviewed</label>
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
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="review_date_correct" value="1" {{isset($qa_checklist) && $qa_checklist->review_date_correct ? "checked" : ''}} class="custom-control-input" id="review_date_correct">
                                            <label class="custom-control-label" for="review_date_correct">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="review_date_correct" value="0" {{isset($qa_checklist) && !$qa_checklist->review_date_correct ? "checked" : ''}} class="custom-control-input" id="review_date_correct_false">
                                            <label class="custom-control-label" for="review_date_correct_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="review_date_correct" {{isset($qa_checklist) && !isset($qa_checklist->review_date_correct) ? "checked" : ''}} class="custom-control-input" id="review_date_correct_nr">
                                            <label class="custom-control-label" for="review_date_correct_nr">Not Reviewed</label>
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
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="ta_has_conclusion" value="1" {{isset($qa_checklist) && $qa_checklist->ta_has_conclusion ? "checked" : ''}} class="custom-control-input" id="ta_has_conclusion">
                                            <label class="custom-control-label" for="ta_has_conclusion">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="ta_has_conclusion" value="0" {{isset($qa_checklist) && !$qa_checklist->ta_has_conclusion ? "checked" : ''}} class="custom-control-input" id="ta_has_conclusion_false">
                                            <label class="custom-control-label" for="ta_has_conclusion_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="ta_has_conclusion" {{isset($qa_checklist) && !isset($qa_checklist->ta_has_conclusion) ? "checked" : ''}} class="custom-control-input" id="ta_has_conclusion_nr">
                                            <label class="custom-control-label" for="ta_has_conclusion_nr">Not Reviewed</label>
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
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="listed_in_chronological_order" value="1" {{isset($qa_checklist) && $qa_checklist->listed_in_chronological_order ? "checked" : ''}} class="custom-control-input" id="listed_in_chronological_order">
                                            <label class="custom-control-label" for="listed_in_chronological_order">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="listed_in_chronological_order" value="0" {{isset($qa_checklist) && !$qa_checklist->listed_in_chronological_order ? "checked" : ''}} class="custom-control-input" id="listed_in_chronological_order_false">
                                            <label class="custom-control-label" for="listed_in_chronological_order_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="listed_in_chronological_order" {{isset($qa_checklist) && !isset($qa_checklist->listed_in_chronological_order) ? "checked" : ''}} class="custom-control-input" id="listed_in_chronological_order_nr">
                                            <label class="custom-control-label" for="listed_in_chronological_order_nr">Not Reviewed</label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item"  style="margin: 0 0!important;">
                                <div class="row">
                                    <div class="col-md-4">RB-Summary of article</div>
                                    <div class="col-md-8 row">
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="rb_summary_article" value="1" {{isset($qa_checklist) && $qa_checklist->rb_summary_article ? "checked" : ''}} class="custom-control-input" id="rb_summary_article">
                                            <label class="custom-control-label" for="rb_summary_article">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="rb_summary_article" value="0" {{isset($qa_checklist) && !$qa_checklist->rb_summary_article ? "checked" : ''}} class="custom-control-input" id="rb_summary_article_false">
                                            <label class="custom-control-label" for="rb_summary_article_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="rb_summary_article" {{isset($qa_checklist) && !isset($qa_checklist->rb_summary_article) ? "checked" : ''}} class="custom-control-input" id="rb_summary_article_nr">
                                            <label class="custom-control-label" for="rb_summary_article_nr">Not Reviewed</label>
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
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="eb_exact_extract_from_article" value="1" {{isset($qa_checklist) && $qa_checklist->eb_exact_extract_from_article ? "checked" : ''}} class="custom-control-input" id="eb_exact_extract_from_article">
                                            <label class="custom-control-label" for="eb_exact_extract_from_article">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="eb_exact_extract_from_article" value="0" {{isset($qa_checklist) && !$qa_checklist->eb_exact_extract_from_article ? "checked" : ''}} class="custom-control-input" id="eb_exact_extract_from_article_false">
                                            <label class="custom-control-label" for="eb_exact_extract_from_article_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="eb_exact_extract_from_article" {{isset($qa_checklist) && !isset($qa_checklist->eb_exact_extract_from_article) ? "checked" : ''}} class="custom-control-input" id="eb_exact_extract_from_article_nr">
                                            <label class="custom-control-label" for="eb_exact_extract_from_article_nr">Not Reviewed</label>
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
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="does_it_align_with_background_ta" value="1" {{isset($qa_checklist) && $qa_checklist->does_it_align_with_background_ta ? "checked" : ''}} class="custom-control-input" id="does_it_align_with_background_ta">
                                            <label class="custom-control-label" for="does_it_align_with_background_ta">Pass</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="does_it_align_with_background_ta" value="0" {{isset($qa_checklist) && !$qa_checklist->does_it_align_with_background_ta ? "checked" : ''}} class="custom-control-input" id="does_it_align_with_background_ta_false">
                                            <label class="custom-control-label" for="does_it_align_with_background_ta_false">Fail</label>
                                        </div>
                                        <div class="custom-control col custom-radio">
                                            <input type="radio" name="does_it_align_with_background_ta" {{isset($qa_checklist) && !isset($qa_checklist->does_it_align_with_background_ta) ? "checked" : ''}} class="custom-control-input" id="does_it_align_with_background_ta_nr">
                                            <label class="custom-control-label" for="does_it_align_with_background_ta_nr">Not Reviewed</label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 text-center my-3">
            <button type="submit" class="btn btn-lg btn-primary"><i class="fas fa-save"></i> Save</button>
        </div>

    {{Form::close()}}
    </div>

@endsection
