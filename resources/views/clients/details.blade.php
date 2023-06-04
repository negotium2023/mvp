@extends('clients.show')

@section('tab-content')
    <div class="col-lg-9">
        <input type="hidden" id="form_id" value="2"/>
        <div class="card">
            <div class="card-header">
                Comments
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    @forelse($client->comments as $comment)
                        <tr>
                            <td>
                                <a href="{{route('profile',$comment->user_id)}}">
                                    <img src="{{route('avatar',['q'=>$comment->user->avatar])}}" class="blackboard-avatar blackboard-avatar-inline" alt="{{$comment->user->name()}}"/>{{$comment->user->name()}}
                                </a>
                                : {{$comment->comment}}
                                <small class="float-right text-muted"><i class="fa fa-calendar"></i> {{substr($comment->created_at,0,10)}}&nbsp;&nbsp;<i class="fa fa-clock-o"></i> {{substr($comment->created_at,11,19)}}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center">
                                <small><i>No comments added yet.</i></small>
                            </td>
                        </tr>
                    @endforelse
                    <tr>
                        <td>
                            {{Form::open(['url' => route('clients.storecomment', $client), 'method' => 'post'])}}
                            <div class="input-group">
                                {{Form::text('comment',old('comment'),['class'=>'form-control form-control-sm','placeholder'=>'Type a comment'])}}
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-paper-plane">&nbsp;Submit your comment</i></button>
                                </div>
                            </div>
                            {{Form::close()}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="container-fluid">
                    @if(count($forms) > 0)
                    <nav class="tabbable">
                <div class="nav nav-pills">
                        <a class="nav-link active show" id="default-tab" data-toggle="tab" href="#default" role="tab" aria-controls="default" aria-selected="false">Default</a>
                        @foreach($forms as $key =>$value)
                            @foreach($value as $section =>$v1)
                                    <a class="nav-link" id="{{strtolower(str_replace($section,' ','_'))}}-tab" data-toggle="tab" href="#{{strtolower(str_replace(' ','_',$section))}}" role="tab" aria-controls="{{strtolower(str_replace(' ','_',$section))}}" aria-selected="true">{{$section}}</a>
                            @endforeach
                        @endforeach
                </div>
                    </nav>
                    <hr />
                    @endif
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active p-3" id="default" role="tabpanel" aria-labelledby="default-tab">
                        <ul style="padding-left: 0;">
                            <dt>
                                First Names
                            </dt>
                            <dd>
                                @if($client->first_name)
                                    {{$client->first_name}}
                                @else
                                    <small><i>No first names captured</i></small>
                                @endif
                            </dd>
                            <dt>
                                Surname
                            </dt>
                            <dd>
                                @if($client->last_name)
                                    {{$client->last_name}}
                                @else
                                    <small><i>No surname captured</i></small>
                                @endif
                            </dd>
                            <dt>
                                Initials
                            </dt>
                            <dd>
                                @if($client->initials)
                                    {{$client->initials}}
                                @else
                                    <small><i>No initials captured</i></small>
                                @endif
                            </dd>

                            <dt>
                                ID/Passport number
                            </dt>
                            <dd>
                                @if($client->id_number)
                                    {{$client->id_number}}
                                @else
                                    <dt>
                                        ID/Passport number
                                    </dt>
                                    <dd>
                                        <small><i>No ID/Passport number captured</i></small>
                                    </dd>
                                    @endif
                                </dd>
                                {{--<div class="row mb-2">
                                    <div class="col-md-3">
                                        <strong>ID/Passport number</strong><br>{{$client->id_number}}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Date Of Birth</strong><br>{{$date_of_birth}}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Gender</strong><br>{{$gender}}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Citizenship</strong><br>{{$citizenship}}
                                    </div>
                                </div>--}}

                            <dt>
                                Email
                            </dt>
                            <dd>
                                @if($client->email)
                                    <a href="mailto:{{$client->email}}">{{$client->email}}</a>
                                @else
                                    <small><i>No email captured</i></small>
                                @endif
                            </dd>
                            <dt>
                                Cellphone number
                            </dt>
                            <dd>
                                @if($client->contact)
                                    {{$client->contact}}
                                @else
                                    <small><i>No contact number captured</i></small>
                                @endif
                            </dd>
                            <dt>
                                Office
                            </dt>
                            <dd>
                                {{$client->office->area->region->division->name}} / {{$client->office->area->region->name}} / {{$client->office->area->name}} / {{$client->office->name}}
                            </dd>
                            <dt>
                                Created
                            </dt>
                            <dd>
                                <p>
                                    {{$client->created_at->diffForHumans()}} <span class="text-muted"><i> <i class="fa fa-clock-o"></i> {{$client->created_at}}</i></span>
                                </p>
                            </dd>
                            @if(isset($client->not_progressing_date) && $client->not_progressing_date != null)
                            <dt>
                                Moved to Not Progressing
                            </dt>
                            <dd>
                                <p>
                                    {{\Illuminate\Support\Carbon::parse($client->not_progressing_date)->diffForHumans()}} <span class="text-muted"><i> <i class="fa fa-clock-o"></i> {{$client->not_progressing_date}}</i></span>
                                </p>
                            </dd>
                            @endif
                        </ul>

                <div id="actual-times" class="mt-3" style="width: 100%"></div>
                    </div>
                    @foreach($forms as $key =>$value)
                        @foreach($value as $section =>$v1)
                            <div class="tab-pane fade p-3" id="{{strtolower(str_replace(' ','_',$section))}}" role="tabpanel" aria-labelledby="{{strtolower(str_replace(' ','_',$section))}}-tab">

                                <div style="padding-left: 0;">
                                    <div style="width: 100%;">
                                        <div class="form-group form-check float-right">
                                            <label class="form-check-label" for="select-all" style="margin-left: -14rem;">Select All for Client Basket</label>
                                            <input style="margin-left: .5rem" type="checkbox" class="form-check-input" id="select-all">
                                        </div>
                                    </div>
                                    @foreach($v1 as $k1 =>$inputs)

                                    @foreach($inputs["inputs"] as $input)
                                            @if($input['type']=='heading')
                                                <h4 style="display:inline-block;width:{{$input['level']}}%;margin-left: calc(100% - {{$input['level']}}%);background-color:{{$input['color'] != 'hsl(0,0%,0%)' ? $input['color'] : ''}};padding:5px;">{{$input['name']}}</h4>
                                            @elseif($input['type']=='subheading')
                                                <h5 style="display:inline-block;width:{{$input['level']}}%;margin-left: calc(100% - {{$input['level']}}%);background-color:{{$input['color'] != 'hsl(0,0%,0%)' ? $input['color'] : ''}};padding:5px;">{{$input['name']}}</h5>
                                            @else
                                            <div style="display:inline-block;border-radius:5px;padding:7px;margin-bottom:7px;width:{{$input['level']}}%;margin-left: calc(100% - {{$input['level']}}%);background-color:{{$input['color'] != 'hsl(0,0%,0%)' && $input['color'] != null ? $input['color'] : '#f5f5f5'}};border:1px solid rgba(0,0,0,.125);">
                                            <div>
                                                <div class="d-inline-block col-md-9" style="color:#000000;padding-left: 0;">
                                                    <strong>{{$input['name']}}</strong>
                                                </div>
                                                <div style="float: right;margin-top: -3px;padding-bottom: 3px;padding-left:10px;text-align: right;" class="col-md-3 clientbasket">

                                                    <input type="checkbox" data-client="{{$client->id}}" class="form-check-input form-inline select-this" name="add_to_basket[]" value="{{$input['id']}}" id="{{$input['id']}}" {{(in_array($input['id'],$in_details_basket) ? 'checked' : '')}}>
                                                    <label for="{{$input['id']}}" class="form-check-label" data-toggle="tooltip" data-html="true" title="Add To Client Basket" style="font-weight:normal !important;"> </label>

                                                </div>
                                            </div>
                                            <div style="color:#000000;">
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

                                @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
                </div>
            </div>
        </div>


    </div>

    <div class="col-lg-3">
        <div class="card">
            <div class="card-header">
                Case Actions
            </div>
            <div class="card-body">
                @if(auth()->user()->can('maintain_client'))

                @else

                @endif
                    @if(auth()->user()->can('maintain_client'))

                        @if(auth()->user()->is('qa') && $client->is_qa)

                        @else

                        @endif
                        {{Form::open(['url' => route('clients.progressing',$client), 'method' => 'post'])}}
                        @if($client->user_id == auth()->id() || auth()->user()->can('maintain_client'))
                            <a href="{{route('clients.edit',[$client,$process_id,$step['id']])}}" class="btn btn-sm btn-block btn-outline-primary {{($user_has_permission ? '' : 'disabled')}}" {{($user_has_permission ? '' : 'disabled')}}><i class="fas fa-edit"></i> Edit</a> <br />

                            {{--<a href="javascript:void(0)" onclick="getApplicationDoc({{$client->id}},{{$process_id}})" class="btn btn-sm btn-block btn-outline-danger {{($user_has_permission ? '' : 'disabled')}}" {{($user_has_permission ? '' : 'disabled')}}><i class="fas fa-at"></i> Submit for Signatures</a><br />--}}
                            <a href="javascript:void(0)" onclick="getApplicationDoc({{$client->id}},{{Auth::id()}},{{$process_id}})" class="btn btn-sm btn-block btn-outline-danger {{($user_has_permission ? '' : 'disabled')}}" {{($user_has_permission ? '' : 'disabled')}}><i class="fas fa-at"></i> Submit for Signatures</a><br />
                            {{--<a href="{{route('docFusionAPI.index',[$client->id,$process_id])}}" class="btn btn-sm btn-block btn-outline-danger {{($user_has_permission ? '' : 'disabled')}}" {{($user_has_permission ? '' : 'disabled')}}><i class="fas fa-at"></i> Submit for Signatures</a><br />--}}
                            <a href="javascript:void(0)" onclick="addressKYC({{$client->id}})" class="btn btn-sm btn-block btn-outline-danger {{($user_has_permission ? '' : 'disabled')}}" {{($user_has_permission ? '' : 'disabled')}}><i class="fa fa-address-card"></i> Submit for KYC</a><br />
                            <a href="javascript:void(0)" onclick="getProofOfAddress({{$client->id}})" class="btn btn-sm btn-block btn-outline-danger {{($user_has_permission ? '' : 'disabled')}}" {{($user_has_permission ? '' : 'disabled')}}><i class="fa fa-chalkboard-teacher"></i> Get Proof of Address</a><br />
                            <a href="javascript:void(0)" onclick="getAVS({{$client->id}})" class="btn btn-sm btn-block btn-outline-danger {{($user_has_permission ? '' : 'disabled')}}" {{($user_has_permission ? '' : 'disabled')}}><i class="fa fa-credit-card"></i> Get AVS Report</a><br />

                            {{--@if(($client->is_qa) && $client->is_qa)
                                @if($client->qa_end_date == null)
                                    <a href="{{route('qaChecklist.create', $client->id)}}" class="btn btn-block btn-sm form-inline btn-primary {{($user_has_permission ? '' : 'disabled')}}" {{($user_has_permission ? '' : 'disabled')}}><span class="mr-2"><i class="fas fa-tasks"></i></span><span style="display:inline-block;text-align:left;"> QA Checklist</span></a>
                                @else
                                    <div class="mr-0 pr-0"><button type="submit" class="btn btn-block btn-sm form-inline btn-primary {{($user_has_permission ? '' : 'disabled')}}" disabled="disabled"><span><i class="fas fa-tasks"></i></span> &nbsp; <span style="display:inline-block;text-align:left;"> QA Checklist</span></button></div>
                                @endif
                            @endif--}}
                        @else
                            <button type="button" class="btn btn-block btn-sm btn-outline-primary disabled" disabled title="You do not have permission to do that"><i class="fa fa-pencil"></i> Edit</button>

                        @endif
                        {{Form::close()}}


                    {{--@if($client->user_id == auth()->id() || auth()->user()->can('maintain_client'))
                        <br />
                        @if($client["consultant_id"] != null)
                        @else
                            <a href="javascript:void(0)" class="btn btn-info btn-sm btn-block mb-4 {{($user_has_permission ? '' : 'disabled')}}" onclick="assignConsultant({{$client["id"]}})" {{($user_has_permission ? '' : 'disabled')}}><i class="fas fa-user-times"></i> Assign Consultant</a>
                        @endif
                    @endif--}}
                    {{Form::open(['url' => route('messages.client',['client_id'=>$client,'process_id'=>$process_id,'step_id'=>$step['id']]), 'method' => 'get'])}}
                    {{Form::hidden('status',true)}}
                    <button type="submit" class="btn btn-block btn-sm btn-success {{($user_has_permission ? '' : 'disabled')}}" {{($user_has_permission ? '' : 'disabled')}}><i class="fa fa-comment"></i> Send Message</button>
                    {{Form::close()}}

                @else
                    <button type="button" class="btn btn-block btn-sm btn-outline-primary disabled" disabled title="You do not have permission to do that"><i class="fa fa-star-o"></i> Follow</button>

                    <br>

                    <button type="button" class="btn btn-block btn-sm btn-outline-primary disabled" disabled title="You do not have permission to do that"><i class="fa fa-pencil"></i> Edit</button>
                @endif

            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                Assigned users
            </div>
            <div class="card-body">
                <dt>
                    Captured by
                </dt>
                <dd>
                    <a href="{{route('profile',$client->introducer_id)}}"><img src="{{route('avatar',['q'=>$client->introducer->avatar])}}" class="blackboard-avatar blackboard-avatar-inline"/> {{$client->introducer->name()}}</a>
                </dd>
            </div>
        </div>
    </div>
@endsection
@section('extra-css')
    <link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endsection

@section('extra-js')
    {{--<script src="https://code.highcharts.com/highcharts.js"></script>--}}
    <script src="https://code.highcharts.com/modules/no-data-to-display.js"></script>
    <script src="https://rawgit.com/highcharts/rounded-corners/master/rounded-corners.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script>
        Highcharts.theme = {
            colors: ['#86bffd', '#17a2b8'],
            title: {
                text: ''
            },
            chart: {
                type: 'column'
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
            xAxis: {
                crosshair: true
            },
            credits: {
                enabled: false
            },
            legend: {
                enabled: false
            },
        };

        Highcharts.setOptions(Highcharts.theme);

        Highcharts.chart('actual-times', {
            colors: ['#ff9999', '#DD0033'],
            title: {
                text: ''
            },
            chart: {
                type: 'column'
            },
            credits: {
                enabled: false
            },
            legend: {
                enabled: false
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                },
                labels: {
                    formatter: function (x) {
                        return (this.value) + " seconds";
                    }
                },
            },
            xAxis: {
                type: 'category',
                crosshair: true,
                categories: [
                ]
            },
            tooltip: {
                formatter: function () {
                    return '<small class="text-muted">' + this.x + '</small><br><b>' + this.y + ' seconds</b>';
                }
            },
            series: [{
                data: [
                ]
            }],
            plotOptions: {
                series: {
                    borderRadiusTopLeft: '3px',
                    borderRadiusTopRight: '3px'
                }
            }
        });

        $(function(){
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                let total = $('.active .select-this').length;
                let total_selected = $('.active .select-this:checked').length;

                if(total === total_selected){
                    $("#select-all").prop('checked',true);
                } else {
                    $("#select-all").prop('checked',false);
                }
            });

            $("#select-all").on('change',function(){  //"select all" change
                var status = this.checked; // "select all" checked status
                var cnt = 1;

                $('.active .select-this').each(function(){ //iterate all listed checkbox items
                    this.checked = status; //change ".checkbox" checked status
                    var status_id = $("#select-all").prop('checked') == true ? 1 : 0;;
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
                    $("#select-all").prop('checked',true);
                } else {
                    $("#select-all").prop('checked',false);
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

            $(".deleteclient").click(function (e) {
            e.preventDefault();
            var conf = confirm("Are you sure you want to delete this client?");
            if(conf)
                window.location = $(this).attr("href");
        });

            $('#movetoqasave').click(function(){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                let client_id = $("#modalMoveToQA").find('#movetoqaclientid').val();
                let user_id = $("#modalMoveToQA").find('#movetoqaconsultantddd').val();

                $.ajax({
                    url: '/clients/' + client_id + '/qa',
                    type: "POST",
                    dataType: "json",
                    data: {clientid:client_id,userid:user_id},
                    success: function (data) {

                        $("#modalMoveToQA").find('#movetoqaclientname').html('');
                        $("#modalMoveToQA").find('.movetoqaconsultant').html('');
                        $("#modalMoveToQA").find('#movetoqaclientid').val('')
                        $("#modalMoveToQA").modal('hide');

                        if(data.message === 'Success') {
                            $('.flash_msg').html('<div class="alert alert-success">Client successfully moved to QA</div>');
                            setTimeout(function(){ window.location.reload(); }, 2000);
                        } else {
                            if(data.message === 'Errorrp') {
                                let YOUR_MESSAGE_STRING_CONST = "Client can only be moved to QA once the investigation on all related parties have been completed.";

                                notifyDialog(YOUR_MESSAGE_STRING_CONST);
                            } else {
                                $('.flash_msg').html('<div class="alert alert-danger">An error occured while trying to move client to QA.</div>');
                                setTimeout(function(){ window.location.reload(); }, 2000);
                            }

                        }

                    }
                })
            })

            $('#qa_complete').click(function(e){
                e.preventDefault();
                var conf = confirm("Complete QA on case and move to Exit Close out?");
                if(conf){
                    $(this).closest('form').submit();
                }
            })

            $('#movetoqacancel').click(function(){
                $("#modalMoveToQA").modal('hide');
            })

            $("#w_i_q_a").click(function () {
                axios.post('/client/work-item-qa/{{$client->id}}', {
                    client: '{{$client->id}}',
                })
                    .then(function (response) {
                        if(response.status == 200){
                            $('#w_i_q_a').prop('disabled', true);
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            })

        });

        function addressKYC(clientID)
        {
            $('#overlay').fadeIn();
            axios.post('/api/address/kyc/individual', {
                client_id: '{{$client->id}}',
            })
            .then(function (response) {
                $('#overlay').fadeOut();
                console.log('response', response);
                // alert(response.data.message);
                notifyDialog(response.data.message);
            })
            .catch(function (error) {
                $('#overlay').fadeOut();
                console.log(error);
            });
        }

        function getProofOfAddress(clientID)
        {
            $('#overlay').fadeIn();
            axios.post('/api/cpb/getproofofaddress', {
                client_id: '{{$client->id}}',
            })
            .then(function (response) {
                $('#overlay').fadeOut();
                console.log('response', response);
                // alert(response.data.message);
                notifyDialog(response.data.message);
            })
            .catch(function (error) {
                $('#overlay').fadeOut();
                console.log(error);
            });
        }

        function getAVS(clientID)
        {
            $('#overlay').fadeIn();
            axios.post('/api/cpb/getavs', {
                client_id: '{{$client->id}}',
            })
            .then(function (response) {
                $('#overlay').fadeOut();
                console.log('response', response);
                // alert(response.data.message);
                notifyDialog(response.data.message);
            })
            .catch(function (error) {
                $('#overlay').fadeOut();
                console.log(error);
            });
        }

    </script>
@endsection
