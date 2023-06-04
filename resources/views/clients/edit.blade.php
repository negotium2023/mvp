@extends('adminlte.default')

@section('title') Update Client @endsection

@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
        <a href="{{route('clients.show',[$client,$process_id,$step['id']])}}" class="btn btn-dark btn-sm float-right"><i class="fa fa-caret-left"></i> Back</a>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <nav class="tabbable">
            <div class="nav nav-pills">
                <a class="nav-link active show" id="default-tab" data-toggle="tab" href="#default" role="tab" aria-controls="default" aria-selected="false">Default</a>
                @foreach($forms as $key =>$value)
                    @foreach($value as $section =>$v1)
                        <a class="nav-link" id="{{strtolower(str_replace(' ','_',$section))}}-tab" data-toggle="tab" href="#{{strtolower(str_replace(' ','_',$section))}}" role="tab" aria-controls="{{strtolower(str_replace(' ','_',$section))}}" aria-selected="true">{{$section}}</a>
                    @endforeach
                @endforeach
            </div>
        </nav>
        {{Form::open(['url' => route('clients.update', $client), 'method' => 'pUt','autocomplete'=>'off'])}}
        <input type="hidden" id="form_id" value="2"/>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active p-3" id="default" role="tabpanel" aria-labelledby="default-tab">

    <div class="form-group mt-3">
        {{Form::label('process', 'Application')}}
        <select name="process" class="chosen-select form-control form-control-sm {{($errors->has('process') ? ' is-invalid' : '')}}" id="process">
            <option>Please Select</option>
            @forelse($processes as $k=>$v)
                <optgroup label="{{$k}}">
                    @foreach($v as $key=>$value)
                        <option value="{{$value['id']}}" {{($client->process_id == $value['id'] ? 'selected' : '')}}>{{$value["name"]}}</option>
                    @endforeach
                </optgroup>
            @empty
                <option value="">There are no applications available for this client.</option>
            @endforelse
        </select>
        {{--{{Form::select('process',$processes,($config->default_onboarding_process != null ? $config->default_onboarding_process : 0),['class'=>'form-control form-control-sm'. ($errors->has('process') ? ' is-invalid' : ''),'autofocus','id'=>'process'])}}--}}
        @foreach($errors->get('process') as $error)
            <div class="invalid-feedback">
                {{$error}}
            </div>
        @endforeach
    </div>

    <div class="form-group">
        {{Form::label('first_name', 'First Names')}}
        {{Form::text('first_name',$client->first_name,['class'=>'form-control form-control-sm'. ($errors->has('first_name') ? ' is-invalid' : ''),'placeholder'=>'First Name'])}}
        @foreach($errors->get('first_name') as $error)
            <div class="invalid-feedback">
                {{$error}}
            </div>
        @endforeach
    </div>

        <div class="form-group">
            {{Form::label('last_name', 'Surname')}}
            {{Form::text('last_name',$client->last_name,['class'=>'form-control form-control-sm'. ($errors->has('last_name') ? ' is-invalid' : ''),'placeholder'=>'Last Name'])}}
            @foreach($errors->get('last_name') as $error)
                <div class="invalid-feedback">
                    {{$error}}
                </div>
            @endforeach
        </div>

        <div class="form-group">
            {{Form::label('initials', 'Initials')}}
            {{Form::text('initials',$client->initials,['class'=>'form-control form-control-sm'. ($errors->has('last_name') ? ' is-invalid' : ''),'placeholder'=>'Initials'])}}
            @foreach($errors->get('initials') as $error)
                <div class="invalid-feedback">
                    {{$error}}
                </div>
            @endforeach
        </div>

    <div class="form-group">
        {{Form::label('id_number', 'ID/Passport Number')}}
        {{Form::text('id_number',$client->id_number,['class'=>'form-control form-control-sm'. ($errors->has('id_number') ? ' is-invalid' : ''),'placeholder'=>'ID Number', 'id'=>'id_number'])}}
        @foreach($errors->get('id_number') as $error)
            <div class="invalid-feedback">
                {{$error}}
            </div>
        @endforeach
        {{--<div class="row pt-3" id="hide-smart-id" style="display: {{isset($client->id_number)?'flex':'none'}}">
            <div class="col-md-3"><strong>Date of Birth: </strong>{{$date_of_birth}}</div>
            <div class="col-md-3"><strong>Gender: </strong>{{$gender}}</div>
            <div class="col-md-3"><strong>Citizenship: </strong>{{$citizenship}}</div>
        </div>--}}
    </div>

        <div class="form-group">
            {{Form::label('email', 'Email')}}
            {{Form::email('email',$client->email,['class'=>'form-control form-control-sm'. ($errors->has('email') ? ' is-invalid' : ''),'placeholder'=>'Email'])}}
            @foreach($errors->get('email') as $error)
                <div class="invalid-feedback">
                    {{$error}}
                </div>
            @endforeach
        </div>

        <div class="form-group">
            {{Form::label('contact', 'Cellphone Number')}}
            {{Form::text('contact',$client->contact,['class'=>'form-control form-control-sm'. ($errors->has('contact') ? ' is-invalid' : ''),'placeholder'=>'Contact Number'])}}
            @foreach($errors->get('contact') as $error)
                <div class="invalid-feedback">
                    {{$error}}
                </div>
            @endforeach
        </div>
    </div>
    @foreach($forms as $key =>$value)

        @foreach($value as $section =>$v1)
                    <div class="tab-pane fade p-3" id="{{strtolower(str_replace(' ','_',$section))}}" role="tabpanel" aria-labelledby="{{strtolower(str_replace(' ','_',$section))}}-tab">
                        <div style="margin-top: -3px;padding-bottom: 3px;padding-left:10px;text-align: right;padding-right:22px;" class="col-md-12 clientbasket-all">
                            <input type="checkbox" id="select-all-{{$key}}" class="form-check-input select-all"> <label for="select-all-{{$key}}" class="form-check-label" data-toggle="tooltip" data-html="true" title="Select All/Add All" style="display:inline-block;margin-bottom:10px;"></label>
                        </div>
                @foreach($v1 as $k1 =>$inputs)

                    @foreach($inputs["inputs"] as $input)
                        @if($input['type'] == 'dropdown')
                            @php

                                $arr = (array)$input['dropdown_items'];
                                $arr2 = (array)$input['dropdown_values'];

                            @endphp
                            <input type="hidden" id="old_{{$input['id']}}" name="old_{{$input['id']}}" value="{{(!empty($arr2) ? implode(',',$arr2) : old($input['id']))}}">
                        @else
                            <input type="hidden" id="old_{{$input['id']}}" name="old_{{$input['id']}}" value="{{old($input['id'])}}">
                        @endif
                            @if($input['type']=='heading')
                                <h4 style="width:{{$input['level']}}%;margin-left: calc(100% - {{$input['level']}}%);background-color:{{$input['color'] != 'hsl(0,0%,0%)' ? $input['color'] : ''}};padding:5px;">{{$input['name']}}</h4>
                            @elseif($input['type']=='subheading')
                                <h5 style="width:{{$input['level']}}%;margin-left: calc(100% - {{$input['level']}}%);background-color:{{$input['color'] != 'hsl(0,0%,0%)' ? $input['color'] : ''}};padding:5px;">{{$input['name']}}</h5>
                            @else
                            <div class="list-group-item" style="display:inline-block;width:{{$input['level']}}%;margin-left: calc(100% - {{$input['level']}}%);background-color:{{$input['color'] != 'hsl(0,0%,0%)' && $input['color'] != null ? $input['color'] : '#f5f5f5'}};border:1px solid rgba(0,0,0,.125);">
                            <div style="display:inline-block;width:20px;vertical-align:top;"><i class="fa fa-circle" style="color: {{isset($input['value']) && $input['value'] != null ? 'rgba(50, 193, 75, 0.7)' : 'rgba(242, 99, 91, 0.7)'}}"></i> </div>
                            <div style="display: inline-block;width: calc(100% - 25px)">
                                    <span style="width:88%;float: left;display:block;">
                                    {{$input["name"]}}
                                        <small class="text-muted"> [{{$input['type_display']}}] @if($input['kpi']==1) <span class="fa fa-asterisk" title="Activity is required for step completion" style="color:#FF0000"></span> @endif</small>
                                    </span>

                                    <div style="float: right;margin-right:5px; display: inline-block;margin-top: -3px;padding-bottom: 3px;text-align: right;" class="form-inline clientbasket">
                                    <input type="checkbox"  data-client="{{$client->id}}" class="form-check-input select-this" name="add_to_basket[]" id="{{$input['id']}}" value="{{$input['id']}}" {{($input['client_bucket'] == 1 || in_array($input['id'],$cd) ? 'checked' : '')}}>
                                    <label  for="{{$input['id']}}" class="form-check-label" style="font-weight:normal !important;"> </label>
                                    </div>
                                <div class="clearfix"></div>
                                @if($input['type']=='text')
                                    {{Form::text($input['id'],(isset($input['value'])?$input['value']:old($input['id'])),['class'=>'form-control form-control-sm','placeholder'=>'Insert text...','spellcheck'=>'true'])}}
                                @endif

                                @if($input['type']=='percentage')
                                    <input type="number" min="0" step="1" max="100" name="{{$input['id']}}" value="{{(isset($input['value']) ? $input['value'] : old($input['id']))}}" class="form-control form-control-sm" spellcheck="true" />
                                @endif

                                @if($input['type']=='integer')
                                    <input type="number" min="0" step="1" name="{{$input['id']}}" value="{{(isset($input['value']) ? $input['value'] : old($input['id']))}}" class="form-control form-control-sm" spellcheck="true" />
                                @endif

                                @if($input['type']=='amount')
                                    <input type="number" min="0" step="1" name="{{$input['id']}}" value="{{(isset($input['value']) ? $input['value'] : old($input['id']))}}" class="form-control form-control-sm" spellcheck="true" />
                                @endif

                                @if($input['type']=='date')
                                    <input name="{{$input['id']}}" type="date" min="1900-01-01" max="2030-12-30" value="{{(isset($input['value'])?$input['value']:old($input['id']))}}" class="form-control form-control-sm" placeholder="Insert date..." />
                                @endif

                                @if($input['type']=='textarea')
                                    <textarea spellcheck="true" rows="5" name="{{$input['id']}}" class="form-control form-control-sm text-area">{{(isset($input['value'])?$input['value']:old($input['id']))}}</textarea>
                                @endif

                                @if($input['type']=='boolean')
                                    <label class="radio-inline"><input type="radio" name="{{$input["id"]}}" value="1" {{(isset($input["value"]) && $input["value"] == 1 ? 'checked' : '')}}><span class="ml-2">Yes</span></label>
                                    <label class="radio-inline ml-3"><input type="radio" name="{{$input["id"]}}" value="0" {{(isset($input["value"]) && $input["value"] == 0 ? 'checked' : '')}}><span class="ml-2">No</span></label>
                                    {{--{{Form::select($input['id'],[1=>'Yes',0=>'No'],(isset($input['value'])?$input['value']:old($input['id'])),['class'=>'form-control form-control-sm','placeholder'=>'Please select...'])}}--}}
                                @endif
                                @if($input['type']=='dropdown')

                                    <select multiple="multiple" name="{{$input["id"]}}[]" class="form-control form-control-sm chosen-select">
                                        @php
                                            foreach((array) $arr as $key => $value){
                                                echo '<option value="'.$key.'" '.(in_array($key,$arr2) ? 'selected' : '').'>'.$value.'</option>';
                                            }
                                        @endphp
                                    </select>
                                    <div>
                                        <small class="form-text text-muted">
                                            Search and select multiple entries
                                        </small>
                                    </div>

                                @endif
                            </div>
                        </div>
                            @endif
                    @endforeach
                @endforeach
            @endforeach
        </div>
            @endforeach

            </div>
            <div class="blackboard-fab mr-3 mb-3">
                <button type="submit" class="btn btn-info btn-lg"><i class="fa fa-save"></i><span style="font-size:1rem;line-height: 1.8;padding-left:10px;float: right;display:block;text-align:left;">Save</span></button>
            </div>
            {{Form::close()}}
    </div>
    <div class="modal fade" id="modalProcess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center" style="border-bottom: 0px;padding:.5rem;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mx-3">
                    <div class="row">
                        <div class="md-form col-sm-12 mb-3 text-left message">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

        .chosen-container, .chosen-container-multi{
            width:100% !important;
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
@section('extra-js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        /*$(function () {
            $("#id_number").on('blur', function () {
                let id_number = $(this).val().replace('-', '').replace(" ", '').replace('\\', '').replace('/', '');
                let partial_d_o_b = id_number.substring(0,2)+'-'+ id_number.substring(2,4) + '-' + id_number.substring(4,6);
                let date_of_birth = ((id_number.substring(0,2) > 45) && (id_number.substring(0,2) <= 99)) ? '19' + partial_d_o_b : '20' + partial_d_o_b;

                let gender = "";
                if (id_number.substring(6, 10) < 5000){
                    gender = "Female";
                }else if(id_number.substring(6, 10) >= 5000){
                    gender = "Male";
                }

                let citizenship = (id_number.substring(10, 11) == 0)?"SA Citizen":"Permanent Resident";

                $("#hide-smart-id").show().html('' +
                    '<div class="col-md-3"><strong>Date of Birth: </strong>' + date_of_birth + '</div>' +
                    '<div class="col-md-3"><strong>Gender: </strong>' + gender + '</div>' +
                    '<div class="col-md-3"><strong>Citizenship: </strong>' + citizenship + '</div>'
                );
            });
        })*/
        $(function(){

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                let total = $('.active .select-this').length;
                let total_selected = $('.active .select-this:checked').length;

                if(total === total_selected){
                    $(".select-all").prop('checked',true);
                } else {
                    $(".select-all").prop('checked',false);
                }
            });

            $(".select-all").on('change',function(){  //"select all" change
                var status = this.checked; // "select all" checked status
                var cnt = 1;

                $('.active .select-this').each(function(){ //iterate all listed checkbox items
                    this.checked = status; //change ".checkbox" checked status
                    var status_id = $(".active .select-all").prop('checked') == true ? 1 : 0;;
                    var input = $(this).val();
                    var form_id = $('#form_id').val();
                    var client_id = $(this).data('client');
                    var section = $('.active .section ').val();


                    $.ajax({
                        type: "GET",
                        dataType: "json",
                        url: '/forms/include-in-basket',
                        data: {'status': status_id, 'input_id':input, 'client_id': client_id,'form_id':form_id,'section':section,'all':'1'},
                        success: function(data) {
                            if (status_id) {
                                cnt = cnt + 1;
                                if (cnt === $(".select-this:checked").length) {
                                    toastr.success(data.success);
                                }
                            }

                            if (!status_id && cnt === 1) {
                                cnt = cnt + 1;
                                toastr.warning(data.success);
                            }
                            toastr.options.timeOut = 500;

                        }
                    });
                });
            });

            $('.select-this').on('change',function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                let total = $('.active .select-this').length;
                let total_selected = $('.active .select-this:checked').length;

                if(total === total_selected){
                    $(".select-all").prop('checked',true);
                } else {
                    $(".select-all").prop('checked',false);
                }

                var status = $(this).prop('checked') == true ? 1 : 0;
                var input = $(this).val();
                var form_id = $('#form_id').val();
                var client_id = $(this).data('client');

                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '/forms/include-in-basket',
                    data: {'status': status, 'input_id':input, 'client_id': client_id,'form_id':form_id},
                    success: function(data){
                        if(status){
                            toastr.success(data.success);
                        }else{
                            toastr.warning(data.success);
                        }
                        toastr.options.timeOut = 500;
                    }
                });
            })

            $('#process').on('change',function(){
                $.ajax({
                    dataType: 'json',
                    url: '/processes/step_count/'+$('#process').val(),
                    type:'GET'
                }).done(function(data) {
                    if(data === 0){
                        let process = $('#process').val();
                        $('#modalProcess').modal('show');
                        $('#modalProcess').find('.message').html('The process you selected currently has no steps.<br /><br />' +
                            'Click <a href="/processes/' + process + '/show">here</a> to add steps to this process now.');
                        $('body').find('.update-client').prop('disabled',true);
                    } else {
                        $('body').find('.update-client').prop('disabled',false);
                    }
                });
            })


        })
    </script>
@endsection