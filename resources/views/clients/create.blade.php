@extends('adminlte.default')

@section('title')
    @if(auth()->user()->is('consultant-capture') || auth()->user()->is('manager'))
        Capture Client
        @else
        Capture Client
    @endif
@endsection

@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
        <a href="{{route('clients.index')}}" class="btn btn-dark btn-sm float-right"><i class="fa fa-caret-left"></i> Back</a>
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
        {{Form::open(['url' => route('clients.store'), 'method' => 'post','autocomplete'=>'off'])}}
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active p-3" id="default" role="tabpanel" aria-labelledby="default-tab">

                <div class="row mt-3">
                    <div class="col-lg-12">
                        <div class="form-group">
                            {{Form::label('process', 'Application')}}
                            <select name="process" class="chosen-select form-control form-control-sm {{($errors->has('process') ? ' is-invalid' : '')}}" id="process">
                                <option>Please Select</option>
                                @forelse($processes as $k=>$v)
                                    <optgroup label="{{$k}}">
                                        @foreach($v as $key=>$value)
                                            <option value="{{$value['id']}}" {{($config->default_onboarding_process == $value['id'] ? 'selected' : '')}}>{{$value["name"]}}</option>
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
                            {{Form::text('first_name',old('first_name'),['class'=>'form-control form-control-sm'. ($errors->has('first_name') ? ' is-invalid' : ''),'placeholder'=>'First Name'])}}
                            @foreach($errors->get('first_name') as $error)
                                <div class="invalid-feedback">
                                    {{$error}}
                                </div>
                            @endforeach
                        </div>

                        <div class="form-group">
                            {{Form::label('last_name', 'Surname')}}
                            {{Form::text('last_name',old('last_name'),['class'=>'form-control form-control-sm'. ($errors->has('last_name') ? ' is-invalid' : ''),'placeholder'=>'Last Name'])}}
                            @foreach($errors->get('last_name') as $error)
                                <div class="invalid-feedback">
                                    {{$error}}
                                </div>
                            @endforeach
                        </div>

                        <div class="form-group">
                            {{Form::label('initials', 'Initials')}}
                            {{Form::text('initials',old('initials'),['class'=>'form-control form-control-sm'. ($errors->has('initials') ? ' is-invalid' : ''),'placeholder'=>'Initials'])}}
                            @foreach($errors->get('initials') as $error)
                                <div class="invalid-feedback">
                                    {{$error}}
                                </div>
                            @endforeach
                        </div>

                        <div class="form-group">
                            {{Form::label('id_number', 'ID/Passport Number')}}
                            {{Form::text('id_number',old('id_number'),['class'=>'form-control form-control-sm'. ($errors->has('id_number') ? ' is-invalid' : ''),'placeholder'=>'ID Number', 'id'=>'id_number'])}}
                            @foreach($errors->get('id_number') as $error)
                                <div class="invalid-feedback">
                                    {{$error}}
                                </div>
                            @endforeach
                            {{--<div class="row pt-3" id="hide-smart-id" style="display: none">
                            </div>--}}
                        </div>

                        <div class="form-group">
                            {{Form::label('email', 'Email')}}
                            {{Form::email('email',old('email'),['class'=>'form-control form-control-sm'. ($errors->has('email') ? ' is-invalid' : ''),'placeholder'=>'Email'])}}
                            @foreach($errors->get('email') as $error)
                                <div class="invalid-feedback">
                                    {{$error}}
                                </div>
                            @endforeach
                        </div>

                        <div class="form-group">
                            {{Form::label('contact', 'Cellphone Number')}}
                            {{Form::text('contact',old('contact'),['class'=>'form-control form-control-sm'. ($errors->has('contact') ? ' is-invalid' : ''),'placeholder'=>'Contact Number'])}}
                            @foreach($errors->get('contact') as $error)
                                <div class="invalid-feedback">
                                    {{$error}}
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>

            </div>
            @foreach($forms as $key =>$value)
                @foreach($value as $section =>$v1)
                    <div class="tab-pane fade p-3" id="{{strtolower(str_replace(' ','_',$section))}}" role="tabpanel" aria-labelledby="{{strtolower(str_replace(' ','_',$section))}}-tab" style="padding-bottom: 70px !important;">
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
                                                <input type="checkbox" class="form-check-input" name="add_to_basket[]" id="{{$input['id']}}" value="{{$input['id']}}">
                                                <label  for="{{$input['id']}}" class="form-check-label" style="font-weight:normal !important;"> </label>
                                            </div>
                                            <div class="clearfix"></div>
                                        @if($input['type']=='text')
                                            {{Form::text($input['id'],old($input['id']),['class'=>'form-control form-control-sm','placeholder'=>'Insert text...','spellcheck'=>'true'])}}
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
                                            <input name="{{$input['id']}}" type="date" min="1900-01-01" max="2030-12-30" value="{{old($input['id'])}}" class="form-control form-control-sm" placeholder="Insert date..." />
                                        @endif

                                        @if($input['type']=='textarea')
                                            <textarea spellcheck="true" rows="5" name="{{$input['id']}}" class="form-control form-control-sm text-area"></textarea>
                                        @endif

                                        @if($input['type']=='boolean')
                                            <div class="form-group">
                                                <label class="radio-inline"><input type="radio" name="{{$input["id"]}}" value="1" {{(isset($input["value"]) && $input["value"] == 1 ? 'checked' : '')}}><span class="ml-2">Yes</span></label>
                                                <label class="radio-inline ml-3"><input type="radio" name="{{$input["id"]}}" value="0" {{(isset($input["value"]) && $input["value"] == 0 ? 'checked' : '')}}><span class="ml-2">No</span></label>
                                                {{--{{Form::select($input['id'],[1=>'Yes',0=>'No'],old($input['id']),['class'=>'form-control form-control-sm','placeholder'=>'Please select...'])}}--}}
                                            </div>
                                        @endif
                                        @if($input['type']=='dropdown')

                                            <select multiple="multiple" id="{{$input['id']}}" name="{{$input["id"]}}[]" class="form-control form-control-sm chosen-select">
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
                    </div>
                @endforeach
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
    <script>
        /*$("#id_number, input[name='90']").on('blur', function () {
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

            if(id_number != ''){
                $("#hide-smart-id").show().html('' +
                    '<div class="col-md-3"><strong>Date of Birth: </strong>' + date_of_birth + '</div>' +
                    '<div class="col-md-3"><strong>Gender: </strong>' + gender + '</div>' +
                    '<div class="col-md-3"><strong>Citizenship: </strong>' + citizenship + '</div>'
                );
            }
        });*/
        $(function(){
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
                        $('body').find('.add-client').prop('disabled',true);
                    } else {
                        $('body').find('.add-client').prop('disabled',false);
                    }
                });
            })


            let projects = [@foreach($project as $autocomplete_project) {!! '"'.$autocomplete_project->name.'",' !!} @endforeach];

            autocomplete(document.getElementById("project"), projects);
        })

        $('#project').on('change', function () {
            if ($(this).val() =='{{$projects_down_down->keys()->last()}}'){
                $(this).remove();
                $('#project-wraper').append(
                    '<input type="text" name="project_new" class="form-control form-control-sm">'
                );
            }
        })
    </script>
@endsection