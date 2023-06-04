@extends('client.show')

@section('tab-content')
    <div class="client-detail">
        <div class="container-fluid detail-nav">
            <nav class="tabbable">
                <div class="nav nav-tabs">
                    @foreach($client_details as $data => $tab)
                        @foreach($tab as $name => $input)
                            <a class="nav-link" data-section-id="{{$data}}" id="{{strtolower(str_replace(' ','_',str_replace('&','',$name)))}}-tab" data-toggle="tab" href="#{{strtolower(str_replace(' ','_',str_replace('&','',$name)))}}" role="tab" aria-controls="default" aria-selected="false">{{$name}}</a>
                        @endforeach
                    @endforeach
                </div>
            </nav>
            <div class="nav-btn-group">
                <button onclick="saveClientDetails()" class="btn btn-success float-right ml-2">Save Edits</button>
                <a href="{{route('clients.details',[$client,$process_id,$step["id"]])}}" class="btn btn-outline-primary float-right">Cancel</a>
            </div>
        </div>

        {{ csrf_field() }}
        <input type="hidden" id="form_id" value="2"/>
        <div class="tab-content" id="myTabContent">
            @foreach($client_details as $data => $tab)
                @foreach($tab as $name => $sections)
                    <div class="tab-pane fade p-3" id="{{strtolower(str_replace(' ','_',str_replace('&','',$name)))}}" role="tabpanel" aria-labelledby="{{strtolower(str_replace(' ','_',str_replace('&','',$name)))}}-tab">



                        <div class="row grid-items">
                            @foreach($sections["data"] as $key => $value)

                                @if($data == '1000')

                                    <div class="col-md-6 float-left">
                                        <div class="card p-0" style="height: 140px;min-height: 140px;">
                                            <div class="d-table" style="width: 100%;">
                                                <div class="grid-icon">
                                                    <i class="far fa-file-alt"></i>
                                                </div>
                                                <div class="grid-text">
                                                    <span class="grid-heading">{{$value["name"]}}</span>
                                                    Last Updated: {{(isset($value["last_updated"][0]) ? $value["last_updated"][0] : '----/--/--')}}
                                                </div>
                                                <div class="grid-btn">
                                                    <a href="javascript:void(0)" class="btn btn-outline-primary btn-block" data-toggle="modal" data-target="#my{{strtolower(str_replace(' ','_',str_replace('&','',$value["name"])))}}Modal">Edit Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal" id="my{{strtolower(str_replace(' ','_',str_replace('&','',$value["name"])))}}Modal">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">{{$value["name"]}}</h4>
                                                    <button type="button" class="close" data-dismiss="modal">×</button>
                                                </div>

                                                <!-- Modal body -->
                                                <div class="modal-body">
                                                    {{Form::open(['url' => route('clients.savedetail', $client), 'method' => 'post','autocomplete'=>'off','class'=>'clientdetailsmodalform'])}}
                                                    @foreach($value["inputs"] as $input)
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
                                                            <div style="display:block;width:{{$input['level']}}%;margin-left: calc(100% - {{$input['level']}}%);background-color:{{$input['color'] != 'hsl(0,0%,0%)' && $input['color'] != null ? $input['color'] : ''}};">
                                                                <div style="display: inline-block;width: calc(100% - 25px)">
                                                                    <span class="form-label" style="width:88%;float: left;display:block;">
                                                                    {{$input["name"]}}
                                                                    </span>
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
                                                                        <div role="radiogroup">
                                                                            <input type="radio" value="1" name="{{$input["id"]}}" id="{{$input["id"]}}-enabled" {{(isset($input["value"]) && $input["value"] == 1 ? 'checked' : '')}}>
                                                                            <label for="{{$input["id"]}}-enabled">Yes</label><!-- remove whitespace
                                                                    --><input type="radio" value="0" name="{{$input["id"]}}" id="{{$input["id"]}}-disabled" {{(isset($input["value"]) && $input["value"] == 1 ? '' : 'checked')}}><!-- remove whitespace
                                                                    --><label for="{{$input["id"]}}-disabled">No</label>

                                                                            <span class="selection-indicator"></span>
                                                                        </div>
                                                                        {{--{{Form::select($input['id'],[1=>'Yes',0=>'No'],(isset($input['value'])?$input['value']:old($input['id'])),['class'=>'form-control form-control-sm','placeholder'=>'Please select...'])}}--}}
                                                                    @endif

                                                                    @if($input['type']=='dropdown')

                                                                        <select multiple="multiple" name="{{$input["id"]}}[]" class="form-control form-control-sm chosen-select">
                                                                            @foreach($arr as $key=>$value)

                                                                                <option value="{{$key}}">{{$value}}</option>

                                                                            @endforeach
                                                                        </select>

                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                    {{Form::close()}}
                                                </div>

                                                <!-- Modal footer -->
                                                <div class="modal-footer">
                                                    <a href="javascript:void(0)" onclick="saveClientDetailsModal()" class="btn btn-sm btn-success submitModal">Save Edits</a>
                                                    <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                @else
                                @endif
                            @endforeach
                            {{Form::open(['url' => route('clients.savedetail', $client), 'method' => 'post','autocomplete'=>'off','class'=>'clientdetailsform2 w-100'])}}
                            @if($sections["primary_tab"] == 1)
                                <div class="col-md-4 float-left">
                                    <h5>Client Details</h5>
                                    <span class="form-label" style="width:88%;float: left;display:block;">First Names</span>
                                        {{Form::text('first_name',$client->first_name,['class'=>'form-control form-control-sm'. ($errors->has('first_name') ? ' is-invalid' : ''),'placeholder'=>'First Name'])}}

                                    <span class="form-label" style="width:88%;float: left;display:block;">Surname</span>
                                        {{Form::text('last_name',$client->last_name,['class'=>'form-control form-control-sm'. ($errors->has('last_name') ? ' is-invalid' : ''),'placeholder'=>'Last Name'])}}

                                    <span class="form-label" style="width:88%;float: left;display:block;">Initials</span>
                                        {{Form::text('initials',$client->initials,['class'=>'form-control form-control-sm'. ($errors->has('initials') ? ' is-invalid' : ''),'placeholder'=>'Initials'])}}
                                    <span class="form-label" style="width:88%;float: left;display:block;">Known As</span>
                                        {{Form::text('known_as',$client->known_as,['class'=>'form-control form-control-sm'. ($errors->has('known_as') ? ' is-invalid' : ''),'placeholder'=>'Known As'])}}

                                    <span class="form-label" style="width:88%;float: left;display:block;">ID/Passport Number</span>
                                        {{Form::text('id_number',$client->id_number,['class'=>'form-control form-control-sm'. ($errors->has('id_number') ? ' is-invalid' : ''),'placeholder'=>'ID Number', 'id'=>'id_number'])}}

                                        <span class="form-label" style="width:88%;float: left;display:block;">Email</span>
                                        {{Form::email('email',$client->email,['class'=>'form-control form-control-sm'. ($errors->has('email') ? ' is-invalid' : ''),'placeholder'=>'Email'])}}

                                    <span class="form-label" style="width:88%;float: left;display:block;">Cellphone Number</span>
                                    {{Form::text('contact',$client->contact,['class'=>'form-control form-control-sm'. ($errors->has('contact') ? ' is-invalid' : ''),'placeholder'=>'Contact Number'])}}

                                    <span class="form-label" style="width:88%;float: left;display:block;">Reference</span>
                                    {{Form::text('reference',$client->reference,['class'=>'form-control form-control-sm'. ($errors->has('reference') ? ' is-invalid' : ''),'placeholder'=>'Reference'])}}
                                </div>
                            @endif
                            @foreach($sections["data"] as $key => $value)

                                @if($data == '1000')
                                @else

                                        @if(isset($value["grouping"]))
                                            <input type="hidden" class="max_group" value="{{$value['max_group']}}">
                                            <input type="hidden" class="total_groups" value="{{$value['total_groups']}}">
                                            @for($i=1;$i <= (int)$value['total_groups'];$i++)
                                                <div class="col-md-4 float-left mb-1 group-{{$i}}" style="{{($i % 3 == 0 ? '' : 'border-right:1px solid #eefafd;')}}{{($value['max_group'] != '' && $i <= $value['max_group']  ? '' : 'display:none;')}}">
                                                    <h5>Dependant {{$number_to_word[$i]}}</h5>
                                                    @foreach($value["grouping"][$i]["inputs"] as $input)
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
                                                            <div style="display:block;width:{{$input['level']}}%;margin-left: calc(100% - {{$input['level']}}%);background-color:{{$input['color'] != 'hsl(0,0%,0%)' && $input['color'] != null ? $input['color'] : ''}};">
                                                                <div style="display: inline-block;width: calc(100% - 25px)">
                                                                    <span class="form-label" style="width:88%;float: left;display:block;">
                                                                    {{$input["name"]}}
                                                                    </span>
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
                                                                        <div role="radiogroup">
                                                                            <input type="radio" value="1" name="{{$input["id"]}}" id="{{$input["id"]}}-enabled" {{(isset($input["value"]) && $input["value"] == 1 ? 'checked' : '')}}>
                                                                            <label for="{{$input["id"]}}-enabled">Yes</label><!-- remove whitespace
                                                                    --><input type="radio" value="0" name="{{$input["id"]}}" id="{{$input["id"]}}-disabled" {{(isset($input["value"]) && $input["value"] == 1 ? '' : 'checked')}}><!-- remove whitespace
                                                                    --><label for="{{$input["id"]}}-disabled">No</label>

                                                                            <span class="selection-indicator"></span>
                                                                        </div>
                                                                        {{--{{Form::select($input['id'],[1=>'Yes',0=>'No'],(isset($input['value'])?$input['value']:old($input['id'])),['class'=>'form-control form-control-sm','placeholder'=>'Please select...'])}}--}}
                                                                    @endif

                                                                    @if($input['type']=='dropdown')

                                                                        <select multiple="multiple" name="{{$input["id"]}}[]" class="form-control form-control-sm chosen-select">
                                                                            @php
                                                                                foreach((array) $arr as $key2 => $value2){
                                                                                    echo '<option value="'.$key2.'" '.(in_array($key2,$arr2) ? 'selected' : '').'>'.$value2.'</option>';
                                                                                }
                                                                            @endphp
                                                                        </select>

                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endfor
                                            <div class="col-md-4 float-left" style="display:inherit;min-height:250px;text-align:center;vertical-align:middle;padding:7px;margin-bottom:7px;width:{{$input['level']}}%;margin-left: calc(100% - {{$input['level']}}%);background-color:{{$input['color'] != 'hsl(0,0%,0%)' && $input['color'] != null ? $input['color'] : ''}};">
                                                    <a href="javascript:void(0)" class="btn btn-outline-primary m-auto addGroup" style="{{($value['max_group'] == $value['total_groups'] ? 'display:none;' : '')}}">Add Dependant</a>
                                            </div>
                                        @else
                                            <div class="col-md-4 float-left">
                                                @if($value["show_name_in_tabs"] == 1)
                                                    <h5>{{$value["name"]}}</h5>
                                                @endif
                                                @foreach($value["inputs"] as $input)
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
                                                            <div style="display:block;width:{{$input['level']}}%;margin-left: calc(100% - {{$input['level']}}%);background-color:{{$input['color'] != 'hsl(0,0%,0%)' && $input['color'] != null ? $input['color'] : ''}};">
                                                                <div style="display: inline-block;width: calc(100% - 25px)">
                                                                    <span class="form-label" style="width:88%;float: left;display:block;">
                                                                    {{$input["name"]}}
                                                                    </span>
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

                                                                        <div role="radiogroup">
                                                                            <input type="radio" value="1" name="{{$input["id"]}}" id="{{$input["id"]}}-enabled" {{(isset($input["value"]) && $input["value"] == 1 ? 'checked' : '')}}>
                                                                            <label for="{{$input["id"]}}-enabled">Yes</label><!-- remove whitespace
                                                                    --><input type="radio" value="0" name="{{$input["id"]}}" id="{{$input["id"]}}-disabled" {{(isset($input["value"]) && $input["value"] == 1 ? '' : 'checked')}}><!-- remove whitespace
                                                                    --><label for="{{$input["id"]}}-disabled">No</label>

                                                                            <span class="selection-indicator"></span>
                                                                        </div>
                                                                        {{--{{Form::select($input['id'],[1=>'Yes',0=>'No'],(isset($input['value'])?$input['value']:old($input['id'])),['class'=>'form-control form-control-sm','placeholder'=>'Please select...'])}}--}}
                                                                    @endif

                                                                    @if($input['type']=='dropdown')

                                                                        <select multiple="multiple" name="{{$input["id"]}}[]" class="form-control form-control-sm chosen-select">
                                                                            @php
                                                                                foreach((array) $arr as $key2 => $value2){
                                                                                    echo '<option value="'.$key2.'" '.(in_array($key2,$arr2) ? 'selected' : '').'>'.$value2.'</option>';
                                                                                }
                                                                            @endphp
                                                                        </select>

                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                @endforeach
                                            </div>
                                        @endif
                                @endif
                            @endforeach
                                {{Form::close()}}
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>

        <div class="modal fade" id="extraDetail">
            <div class="modal-dialog" style="width:543px !important;max-width:543px;">
                <div class="modal-content">
                    <div class="modal-header text-center" style="border-bottom: 0px;padding:.5rem;">
                        <h5 class="modal-title"></h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="extraDetailID">
                        <div class="box-body extra-detail-body">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm pull-left" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-sm btn-success" id="saveExtraEdits">Save Edits</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extra-js')
    <script>

        //Show modal with information that is not displayed in the client details tabs
        function getExtraDetails(section_id){

            $.ajax({
                type: "GET",
                url: '/client/get-extra-detail/' + section_id,
                success: function (data) {
                    if (data.message === 'Success') {
                        $("#extraDetail").modal('show');
                    }
                }
            });
        }

        $(function(){

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).data("section-id") // activated tab


                if(target === 1000){
                    $('.nav-btn-group').fadeOut();
                } else {
                    $('.nav-btn-group').fadeIn();
                }

                if($('.nav-tabs').children('a').first().hasClass('active')){
                    $('.details').removeClass('hidden');
                } else {
                    $('.details').addClass('hidden');;
                }
            });

            $('.nav-tabs').children('a').first().addClass('active').addClass('show');

            $('.tab-content').children('div').first().addClass('active').addClass('show');

            if($('.nav-tabs').children('a').first().hasClass('active')){
                $('.details').removeClass('hidden');
            } else {
                $('.details').addClass('hidden');
            }

            $('.addGroup').on('click', function() {
                //var cur = $(this).attr('class').match(/\d+$/)[0];
                let cur = parseInt($(".max_group").val());
                let total = parseInt($(".total_groups").val());
                let next = cur+1;
                if(next === total){
                    $('.addGroup').css('display','none');
                }
                $('.group-'+next).css('display','block');
                $(".max_group").val(next)

            });
        })
    </script>
@endsection
