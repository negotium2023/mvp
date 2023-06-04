@extends('client.show')

@section('tab-content')
    <div class="client-detail">
        <div class="container-fluid detail-nav">
            <nav class="tabbable">
                <div class="nav nav-tabs">
                    @forelse($client_details as $data => $tab)
                        @foreach($tab as $name => $input)
                            <a class="nav-link" id="{{strtolower(str_replace(' ','_',str_replace('&','',$name)))}}-tab" data-toggle="tab" href="#{{strtolower(str_replace(' ','_',str_replace('&','',$name)))}}" role="tab" aria-controls="default" aria-selected="false">{{$name}}</a>
                        @endforeach
                    @empty
                        <a class="nav-link" id="personal_details-tab" data-toggle="tab" href="#personal_details" role="tab" aria-controls="default" aria-selected="false">Personal Details</a>
                    @endforelse
                </div>
            </nav>
            <div class="nav-btn-group">
                <a href="{{route('clients.edit',[$client,$process_id,$step['id']])}}" class="btn btn-primary float-right">Edit Details</a>
            </div>
        </div>
        <div class="tab-content" id="myTabContent">
            @forelse($client_details as $data => $tab)
                @foreach($tab as $name => $sections)
                    <div class="tab-pane fade p-3" id="{{strtolower(str_replace(' ','_',str_replace('&','',$name)))}}" role="tabpanel" aria-labelledby="{{strtolower(str_replace(' ','_',str_replace('&','',$name)))}}-tab">
                        <div class="row grid-items">
                            @if($sections["primary_tab"] == 1)
                                <div class="col-md-4">
                                    <h5>Client Details</h5>
                                    <div style="display:inline-block;padding:7px;margin-bottom:7px;width: 100%;">
                                        <div>
                                            <span class="form-label">First Names</span>
                                        </div>
                                        <div class="form-text">
                                            {{$client->first_name}}
                                        </div>
                                    </div>
                                    <div style="display:inline-block;padding:7px;margin-bottom:7px;width: 100%;">
                                        <div>
                                            <span class="form-label">Surname</span>
                                        </div>
                                        <div class="form-text">
                                            {{$client->last_name}}
                                        </div>
                                    </div>
                                    <div style="display:inline-block;padding:7px;margin-bottom:7px;width: 100%;">
                                        <div>
                                            <span class="form-label">Initials</span>
                                        </div>
                                        <div class="form-text">
                                            {{$client->initials}}
                                        </div>
                                    </div>
                                    <div style="display:inline-block;padding:7px;margin-bottom:7px;width: 100%;">
                                        <div>
                                            <span class="form-label">Known As</span>
                                        </div>
                                        <div class="form-text">
                                            {{$client->known_as}}
                                        </div>
                                    </div>
                                    <div style="display:inline-block;padding:7px;margin-bottom:7px;width: 100%;">
                                        <div>
                                            <span class="form-label">ID/Passport Number</span>
                                        </div>
                                        <div class="form-text">
                                            {{$client->id_number}}
                                        </div>
                                    </div>
                                    <div style="display:inline-block;padding:7px;margin-bottom:7px;width: 100%;">
                                        <div>
                                            <span class="form-label">Email</span>
                                        </div>
                                        <div class="form-text">
                                            {{$client->email}}
                                        </div>
                                    </div>
                                    <div style="display:inline-block;padding:7px;margin-bottom:7px;width: 100%;">
                                        <div>
                                            <span class="form-label">Cellphone Number</span>
                                        </div>
                                        <div class="form-text">
                                            {{$client->contact}}
                                        </div>
                                    </div>
                                    <div style="display:inline-block;padding:7px;margin-bottom:7px;width: 100%;">
                                        <div>
                                            <span class="form-label">Reference</span>
                                        </div>
                                        <div class="form-text">
                                            {{$client->reference}}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @foreach($sections["data"] as $key => $value)
                                @if($data == '1000')

                                    <div class="col-md-6 float-left">
                                        <div class="card p-0 m-0" style="height: 100px;min-height: 100px;border: 1px solid #ecf1f4;margin-bottom:0.75rem;">
                                            <div class="d-table" style="width: 100%;">
                                                <div class="grid-icon">
                                                    <i class="far fa-file-alt"></i>
                                                </div>
                                                <div class="grid-text">
                                                    <span class="grid-heading">{{$value["name"]}}</span>
                                                    Last Updated: 00/00/0000
                                                </div>
                                                <div class="grid-btn">
                                                    <a href="javascript:void(0)" class="btn btn-outline-primary btn-block" data-toggle="modal" data-target="#my{{strtolower(str_replace(' ','_',str_replace('&','',$value["name"])))}}Modal">View</a>
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
                                                    @foreach($value["inputs"] as $input)
                                                        @if($input['type']=='heading')
                                                            <h4 style="display:inline-block;width:{{$input['level']}}%;margin-left: calc(100% - {{$input['level']}}%);background-color:{{$input['color'] != 'hsl(0,0%,0%)' ? $input['color'] : ''}};padding:5px;">{{$input['name']}}</h4>
                                                        @elseif($input['type']=='subheading')
                                                            <h5 style="display:inline-block;width:{{$input['level']}}%;margin-left: calc(100% - {{$input['level']}}%);background-color:{{$input['color'] != 'hsl(0,0%,0%)' ? $input['color'] : ''}};padding:5px;">{{$input['name']}}</h5>
                                                        @else
                                                            <div style="display:inline-block;padding:7px;margin-bottom:7px;width:{{$input['level']}}%;margin-left: calc(100% - {{$input['level']}}%);background-color:{{$input['color'] != 'hsl(0,0%,0%)' && $input['color'] != null ? $input['color'] : ''}};">
                                                                <div>
                                                                    <span class="form-label">{{$input['name']}}</span>
                                                                </div>
                                                                <div class="form-text">
                                                                    @if(isset($input['value']))
                                                                        @if($input['type'] == 'dropdown')
                                                                            @php

                                                                                $arr = (array)$input['dropdown_items'];
                                                                                $arr2 = (array)$input['dropdown_values'];

                                                                            @endphp
                                                                            @php
                                                                                foreach((array) $arr as $key => $value){
                                                                                    if(in_array($key,$arr2)){
                                                                                        echo $value.'<br />';
                                                                                    }
                                                                                }
                                                                            @endphp
                                                                        @elseif($input['type'] == 'boolean')
                                                                            {{($input['value'] == '1' ? 'Yes' : 'No')}}
                                                                        @else
                                                                            {{$input['value']}}
                                                                        @endif
                                                                    @else
                                                                        <small><i>No value captured.</i></small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>

                                                <!-- Modal footer -->
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Close</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                @else

                                    @if(isset($value["grouping"]))
                                        <input type="hidden" class="max_group" value="{{$value['max_group']}}">
                                        <input type="hidden" class="total_groups" value="{{$value['total_groups']}}">
                                        @for($i=1;$i <= (int)$value['total_groups'];$i++)
                                            <div class="col-md-4 float-left mb-1 group-{{$i}}" style="{{($i % 3 == 0 ? '' : 'border-right:1px solid #eefafd;')}}{{($value['max_group'] != '' && $i <= $value['max_group']  ? '' : 'display:none;')}}">

                                                <h5>Dependant {{$number_to_word[$i]}}</h5>
                                                @if($i <= $value['max_group'])
                                                    @foreach($value["grouping"][$i]["inputs"] as $input)
                                                        @if($input['type']=='heading')
                                                            <h4 style="display:inline-block;width:{{$input['level']}}%;margin-left: calc(100% - {{$input['level']}}%);background-color:{{$input['color'] != 'hsl(0,0%,0%)' ? $input['color'] : ''}};padding:5px;">{{$input['name']}}</h4>
                                                        @elseif($input['type']=='subheading')
                                                            <h5 style="display:inline-block;width:{{$input['level']}}%;margin-left: calc(100% - {{$input['level']}}%);background-color:{{$input['color'] != 'hsl(0,0%,0%)' ? $input['color'] : ''}};padding:5px;">{{$input['name']}}</h5>
                                                        @else
                                                            <div class="col-md-6 float-left" style="display:inline-block;padding:7px;margin-bottom:7px;width:{{$input['level']}}%;margin-left: calc(100% - {{$input['level']}}%);background-color:{{$input['color'] != 'hsl(0,0%,0%)' && $input['color'] != null ? $input['color'] : ''}};">
                                                                <div>
                                                                    <span class="form-label">{{$input['name']}}</span>
                                                                </div>
                                                                <div class="form-text">
                                                                    @if(isset($input['value']))
                                                                        @if($input['type'] == 'dropdown')
                                                                            @php

                                                                                $arr = (array)$input['dropdown_items'];
                                                                                $arr2 = (array)$input['dropdown_values'];

                                                                            @endphp
                                                                            @php
                                                                                foreach((array) $arr as $key2 => $value2){
                                                                                    if(in_array($key2,$arr2)){
                                                                                        echo $value2.'<br />';
                                                                                    }
                                                                                }
                                                                            @endphp
                                                                        @elseif($input['type'] == 'boolean')
                                                                            {{($input['value'] == '1' ? 'Yes' : 'No')}}
                                                                        @else
                                                                            {{$input['value']}}
                                                                        @endif
                                                                    @else
                                                                        <small><i>No value captured.</i></small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    {{Form::open(['url' => route('clients.savedetail', $client), 'method' => 'post','autocomplete'=>'off','class'=>'clientdetailsform'])}}
                                                    @foreach($value["grouping"][$i]["inputs"] as $input)
                                                        @if($input['type'] == 'dropdown')
                                                            @php

                                                                $arr3 = (array)$input['dropdown_items'];
                                                                $arr23 = (array)$input['dropdown_values'];

                                                            @endphp
                                                            <input type="hidden" id="old_{{$input['id']}}" name="old_{{$input['id']}}" value="{{(!empty($arr23) ? implode(',',$arr23) : old($input['id']))}}">
                                                        @else
                                                            <input type="hidden" id="old_{{$input['id']}}" name="old_{{$input['id']}}" value="{{old($input['id'])}}">
                                                        @endif
                                                        @if($input['type']=='heading')
                                                            <h4 style="width:{{$input['level']}}%;margin-left: calc(100% - {{$input['level']}}%);background-color:{{$input['color'] != 'hsl(0,0%,0%)' ? $input['color'] : ''}};padding:5px;">{{$input['name']}}</h4>
                                                        @elseif($input['type']=='subheading')
                                                            <h5 style="width:{{$input['level']}}%;margin-left: calc(100% - {{$input['level']}}%);background-color:{{$input['color'] != 'hsl(0,0%,0%)' ? $input['color'] : ''}};padding:5px;">{{$input['name']}}</h5>
                                                        @else
                                                            <div style="display:block;width:{{$input['level']}}%;margin-left: calc(100% - {{$input['level']}}%);background-color:{{$input['color'] != 'hsl(0,0%,0%)' && $input['color'] != null ? $input['color'] : ''}};">
                                                                <div style="display: inline-block;width: 100%;">
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
                                                                        </div>{{--
                                                                            {{Form::select($input['id'],[1=>'Yes',0=>'No'],(isset($input['value'])?$input['value']:old($input['id'])),['class'=>'form-control form-control-sm','placeholder'=>'Please select...'])}}--}}
                                                                    @endif

                                                                    @if($input['type']=='dropdown')

                                                                        <select multiple="multiple" name="{{$input["id"]}}[]" class="form-control form-control-sm chosen-select">
                                                                            @php
                                                                                foreach((array) $arr3 as $key3 => $value3){
                                                                                    echo '<option value="'.$key3.'" '.(in_array($key3,$arr23) ? 'selected' : '').'>'.$value3.'</option>';
                                                                                }
                                                                            @endphp
                                                                        </select>

                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                    <input type="submit" class="btn btn-primary float-right" value="Add">
                                                    {{Form::close()}}
                                                @endif
                                            </div>
                                        @endfor
                                        <div class="col-md-4 float-left" style="display:inherit;min-height:250px;text-align:center;vertical-align:middle;padding:7px;margin-bottom:7px;width:{{$input['level']}}%;margin-left: calc(100% - {{$input['level']}}%);background-color:{{$input['color'] != 'hsl(0,0%,0%)' && $input['color'] != null ? $input['color'] : ''}};">
                                            <a href="javascript:void(0)" class="btn btn-outline-primary m-auto addGroup" style="{{($value['max_group'] == $value['total_groups'] ? 'display:none;' : '')}}">Add Dependant</a>
                                        </div>
                                    @else
                                        <div class="col-md-4">
                                            @if($value["show_name_in_tabs"] == 1)
                                                <h5>{{$value["name"]}}</h5>
                                            @endif
                                            @foreach($value["inputs"] as $input)
                                                @if($input['type']=='heading')
                                                    <h4 style="display:inline-block;width:{{$input['level']}}%;margin-left: calc(100% - {{$input['level']}}%);background-color:{{$input['color'] != 'hsl(0,0%,0%)' ? $input['color'] : ''}};padding:5px;">{{$input['name']}}</h4>
                                                @elseif($input['type']=='subheading')
                                                    <h5 style="display:inline-block;width:{{$input['level']}}%;margin-left: calc(100% - {{$input['level']}}%);background-color:{{$input['color'] != 'hsl(0,0%,0%)' ? $input['color'] : ''}};padding:5px;">{{$input['name']}}</h5>
                                                @else
                                                    <div style="display:inline-block;padding:7px;margin-bottom:7px;width:{{$input['level']}}%;margin-left: calc(100% - {{$input['level']}}%);background-color:{{$input['color'] != 'hsl(0,0%,0%)' && $input['color'] != null ? $input['color'] : ''}};">
                                                        <div>
                                                            <span class="form-label">{{$input['name']}}</span>
                                                        </div>
                                                        <div class="form-text">
                                                            @if(isset($input['value']))
                                                                @if($input['type'] == 'dropdown')
                                                                    @php

                                                                        $arr = (array)$input['dropdown_items'];
                                                                        $arr2 = (array)$input['dropdown_values'];

                                                                    @endphp
                                                                    @php
                                                                        foreach((array) $arr as $key => $value){
                                                                            if(in_array($key,$arr2)){
                                                                                echo $value.'<br />';
                                                                            }
                                                                        }
                                                                    @endphp
                                                                @elseif($input['type'] == 'boolean')
                                                                    {{($input['value'] == '1' ? 'Yes' : 'No')}}
                                                                @elseif($input['type'] == 'date')
                                                                    {{-- {{$input['value']}} --}}
                                                                    {{date('d-m-Y', strtotime($input['value']))}}
                                                                @else
                                                                    {{$input['value']}}
                                                                @endif
                                                            @else
                                                                <small><i>No value captured.</i></small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @empty
                <div class="tab-pane fade p-3" id="personal_detail" role="tabpanel" aria-labelledby="personal_detail-tab">
                    <div class="row grid-items">
                        <div class="col-md-4">
                            <h5>Client Details</h5>
                            <div style="display:inline-block;padding:7px;margin-bottom:7px;width: 100%;">
                                <div>
                                    <span class="form-label">First Names</span>
                                </div>
                                <div class="form-text">
                                    {{$client->first_name}}
                                </div>
                            </div>
                            <div style="display:inline-block;padding:7px;margin-bottom:7px;width: 100%;">
                                <div>
                                    <span class="form-label">Surname</span>
                                </div>
                                <div class="form-text">
                                    {{$client->last_name}}
                                </div>
                            </div>
                            <div style="display:inline-block;padding:7px;margin-bottom:7px;width: 100%;">
                                <div>
                                    <span class="form-label">Initials</span>
                                </div>
                                <div class="form-text">
                                    {{$client->initials}}
                                </div>
                            </div>
                            <div style="display:inline-block;padding:7px;margin-bottom:7px;width: 100%;">
                                <div>
                                    <span class="form-label">Known As</span>
                                </div>
                                <div class="form-text">
                                    {{$client->known_as}}
                                </div>
                            </div>
                            <div style="display:inline-block;padding:7px;margin-bottom:7px;width: 100%;">
                                <div>
                                    <span class="form-label">ID/Passport Number</span>
                                </div>
                                <div class="form-text">
                                    {{$client->id_number}}
                                </div>
                            </div>
                            <div style="display:inline-block;padding:7px;margin-bottom:7px;width: 100%;">
                                <div>
                                    <span class="form-label">Email</span>
                                </div>
                                <div class="form-text">
                                    {{$client->email}}
                                </div>
                            </div>
                            <div style="display:inline-block;padding:7px;margin-bottom:7px;width: 100%;">
                                <div>
                                    <span class="form-label">Cellphone Number</span>
                                </div>
                                <div class="form-text">
                                    {{$client->contact}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
@section('extra-js')
    <script>
        $(function(){

            $('.nav-tabs').children('a').first().addClass('active').addClass('show');
            $('.tab-content').children('div').first().addClass('active').addClass('show');

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                let total = $('.active .select-this').length;
                let total_selected = $('.active .select-this:checked').length;

                if(total === total_selected){
                    $(".select-all").prop('checked',true);
                } else {
                    $(".select-all").prop('checked',false);
                }
            });

            $('.addGroup').on('click', function() {
                //var cur = $(this).attr('class').match(/\d+$/)[0];
                let cur = parseInt($(".max_group").val());
                let total = parseInt($(".total_groups").val());
                let next = cur+1;
                /*if(next === total){*/
                    $('.addGroup').css('display','none');
                /*}*/
                $('.group-'+next).css('display','block');
                $(".max_group").val(next)

            });
        })
    </script>
@endsection
