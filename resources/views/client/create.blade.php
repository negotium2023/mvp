@extends('flow.default')

@section('title')
    Capture Client
@endsection

@section('content')
    <div class="content-container page-content">
        <div class="col-md-12 h-100">
            <div class="container-fluid container-title">
                <h3>@yield('title')</h3>
                {{--<div class="nav-btn-group mt-2">
                    <button onclick="saveClientDetails()" class="btn btn-primary float-right ml-2">Save</button>
                </div>--}}
            </div>
            <div class="container-fluid">

                <div class="col-md-12 pl-0 pr-0">
                    {{Form::open(['url' => route('clients.store'), 'method' => 'post','autocomplete'=>'off','class'=>'clientdetailsform2','style'=>'min-width:100%;'])}}
                    <nav class="tabbable">
                        <div class="nav nav-tabs2">
                            <a class="nav-link active show" id="default-tab" data-toggle="tab" href="#default" role="tab" aria-controls="default" aria-selected="false">Default</a>
                            @foreach($forms as $key =>$value)
                                @foreach($value as $section =>$v1)
                                    <a class="nav-link" id="{{strtolower(str_replace(' ','_',$section))}}-tab" data-toggle="tab" href="#{{strtolower(str_replace(' ','_',$section))}}" role="tab" aria-controls="{{strtolower(str_replace(' ','_',$section))}}" aria-selected="true">{{$section}}</a>
                                @endforeach
                            @endforeach
                        </div>
                    </nav>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="default" role="tabpanel" aria-labelledby="default-tab">

                            <div class="col-lg-12 pl-0 pr-0 mt-3">
                                <input type="hidden" name="process" value="{{($config->default_onboarding_process)}}">

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
                                    {{Form::label('known_as', 'Known As')}}
                                    {{Form::text('known_as',old('known_as'),['class'=>'form-control form-control-sm'. ($errors->has('known_as') ? ' is-invalid' : ''),'placeholder'=>'Known As'])}}
                                    @foreach($errors->get('known_as') as $error)
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

                                <div class="form-group">
                                    {{Form::label('reference', 'Reference')}}
                                    {{Form::text('reference',old('reference'),['class'=>'form-control form-control-sm'. ($errors->has('reference') ? ' is-invalid' : ''),'placeholder'=>'Reference'])}}
                                    @foreach($errors->get('reference') as $error)
                                        <div class="invalid-feedback">
                                            {{$error}}
                                        </div>
                                    @endforeach
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
                                </div>
                            @endforeach
                        @endforeach

                    </div>
                    {{Form::close()}}
                </div>

            </div>
            <div class="col-md-12">
                <button onclick="saveClientDetails()" class="btn btn-primary float-right ml-2 submit-btn">Save</button>
            </div>
        </div>
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
@section('extra-js')
    <script>
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
            });
        });
    </script>
@endsection