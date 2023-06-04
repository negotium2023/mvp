@extends('client.show')

@section('tab-content')
    <div class="col-lg-12">
        <div class="form-group ml-3 activity_div" style="margin-bottom:0px">
            <div class="col-sm-4">
                {{Form::label('action_search', 'Search Actions')}}
                <div class="input-group form-inline mb-2">
                    {{Form::select('action_search', $actions,old('action_search'), ['class'=>'form-control form-control-sm col-sm-2 chosen-select', 'id' => 'action_search1'])}}&nbsp;
                    {{Form::hidden('action_client', $client["id"], ['class'=>'form-control form-control-sm col-sm-2', 'id' => 'action_client'])}}&nbsp;
                </div>
            </div>
        </div>
    </div>
    <div class="loader col-lg-12">

    </div>
@endsection
@section('extra-css')
    <link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
    <style>
        .wrapper, body, html {
            min-height: 100%;
            overflow-x: hidden;
            overflow: inherit;
        }

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

        .modal-open .modal{
            padding-right: 0px !important;
        }
    </style>
@endsection
@section('extra-js')
    <script>

        var spinner = '<svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"\n' +
            '     width="40px" height="40px" preserveAspectRatio="xMaxYMax meet" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;">\n' +
            '  <path fill="#fff" d="M43.935,25.145c0-10.318-8.364-18.683-18.683-18.683c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615c8.072,0,14.615,6.543,14.615,14.615H43.935z">\n' +
            '    <animateTransform attributeType="xml"\n' +
            '      attributeName="transform"\n' +
            '      type="rotate"\n' +
            '      from="0 25 25"\n' +
            '      to="360 25 25"\n' +
            '      dur="1s"\n' +
            '      repeatCount="indefinite"/>\n' +
            '    </path>\n' +
            '  </svg>';

        $('#action_search1').on('change',function(){
            let search = $('#action_search1').val();
            let client_id = $('#action_client').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                dataType: 'json',
                url: '/search_action/' + search,
                type: 'POST',
                data: {search: search},
                beforeSend: function () {
                    $('.loader').html(spinner);
                },
                success: function (data) {
                    let rows = '';
                    $.each(data, function (key, value) {
                        rows = rows +
                            '<div class="form-group ml-3">' +
                            '<div class="col-sm-12">' +
                            '   <label for="action_name">Name</label>' +
                            '   <div class="input-group">' +
                            '       <input type="text" name="action_name" id="action_name" value="' + value.name + '" class="form form-control form-control-sm">' +
                            '       <input type="hidden" name="action_id" id="action_id" value="' + value.id + '" class="form form-control form-control-sm">' +
                            '   </div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="form-group ml-3">' +
                            '<div class="col-sm-12">' +
                            '   <label for="action_description">Description</label>' +
                            '   <div class="input-group">' +
                            '       <input type="text" name="action_description" id="action_description" value="' + value.description + '" class="form form-control form-control-sm">' +
                            '   </div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="form-group ml-3">' +
                            '<div class="col-sm-12">' +
                            '   <label for="action_description">Process</label>' +
                            '   <div class="input-group">' +
                            '       <select name="action_step2" id="action_step2" disabled class="form-control form-control-sm">';

                        $.each(value.process_id, function (key2, value2) {
                            if(value2.selected == 1) {
                                rows = rows + '<option value="' + value2.id + '" selected>' + value2.name + '</option>';
                            } else {
                                rows = rows + '<option value="' + value2.id + '">' + value2.name + '</option>';
                            }
                        });

                        rows = rows +
                            '       </select>';

                        $.each(value.step_id, function (key2, value2) {
                            if(value2.selected == 1) {
                                rows = rows + '<input type="hidden" name="action_process" id="action_process" value="' + value2.id + '">';
                            }
                        });

                        rows = rows +
                            '   </div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="form-group ml-3">' +
                            '<div class="col-sm-12">' +
                            '   <label for="action_description">Step</label>' +
                            '   <div class="input-group">' +
                            '       <select name="action_step2" id="action_step2" disabled class="form-control form-control-sm">';

                        $.each(value.step_id, function (key2, value2) {
                            if(value2.selected == 1) {
                                rows = rows + '<option value="' + value2.id + '" selected>' + value2.name + '</option>';
                            } else {
                                rows = rows + '<option value="' + value2.id + '">' + value2.name + '</option>';
                            }
                        });

                        rows = rows +
                            '       </select>';

                        $.each(value.step_id, function (key2, value2) {
                            if(value2.selected == 1) {
                                rows = rows + '<input type="hidden" name="action_step" id="action_step" value="' + value2.id + '">';
                            }
                        });

                        rows = rows +
                            '   </div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="form-group ml-3 activity_div">' +
                            '<div class="input-group form-inline mb-2 mt-4 col-sm-12">' +
                            '   <input type="text" name="action_search" id="action_search" class="form-control form-control-sm col-sm-2">' +
                            '                        <div class="input-group-prepend">' +
                            '                            <span class="input-group-text"><i class="fa fa-search"></i> </span>' +
                            '                        </div>' +
                            '                        &nbsp;' +
                            '                        <a href="javascript:void(0)" class="btn btn-sm btn-info form-inline"><i class="fa fa-eraser"></i> Clear</a>\n' +
                            '                    </div>' +
                            '                    <div class="input-group col-sm-12">' +
                            '                        <table class="table table-bordered table-responsive table-activities" style="width: 100%;max-height:350px;height:350px;">\n' +
                            '                            <thead style="width: 100%;">' +
                            '                                <tr>\n' +
                            '                                    <th class="last bg-dark">Select</th>' +
                            '                                    <th class="bg-dark" style="width: 100%;">Activity</th>' +
                            '                                </tr>' +
                            '                            </thead>' +
                            '                            <tbody style="width: 100%;" id="actions_activities">' +
                            '                            <tr style="width:100%"><td colspan="2" class="loader2" align="center" style="border-bottom: 0px;">' +
                            '                            </tbody>\n' +
                            '                       </table>' +
                            '                   </div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="form-group ml-3">' +
                            '<div class="col-sm-12">' +
                            '   <label for="action_recipients">Recipients</label>' +
                            '   <div class="input-group">' +
                            '       <select name="action_recipients" id="action_recipients" multiple class="form-control form-control-sm chosen-select">';

                        $.each(value.recipients, function (key2, value2) {
                            if(value2.selected == 1) {
                                rows = rows + '<option value="' + value2.id + '" selected>' + value2.name + '</option>';
                            } else {
                                rows = rows + '<option value="' + value2.id + '">' + value2.name + '</option>';
                            }
                        });

                        rows = rows +
                            '       </select>' +
                            '   </div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="form-group ml-3">' +
                            '<div class="col-sm-12">' +
                            '   <label for="action_description">Due Date</label>' +
                            '   <div class="input-group">' +
                            '       <input type="date" name="action_due_date" id="action_due_date" class="form form-control form-control-sm">' +
                            '   </div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="form-group ml-3">' +
                            '<div class="col-sm-12 text-right">' +
                            '<form method="post" action="/store_client_action/' + client_id + '">'+
                            '<input type="hidden" name="save_action_name" id="save_action_name">' +
                            '                    <input type="hidden" name="_token" id="save_action_token">' +
                            '                    <input type="hidden" name="save_action_description" id="save_action_description">' +
                            '                    <input type="hidden" name="save_action_process" id="save_action_process">' +
                            '                    <input type="hidden" name="save_action_step" id="save_action_step">' +
                            '                    <input type="hidden" name="save_action_recipients" id="save_action_recipients">' +
                            '                    <input type="hidden" name="save_action_due_date" id="save_action_due_date">' +
                            '   <button class="btn btn-info btn-md form-inline" id="save"><i class="fa fa-save"></i> Send Action</button>' +
                            '</form>' +
                            '</div>' +
                            '</div>';
                    });
                    //alert(rows);
                    $(".loader").html(rows);
                    setTimeout(getActivities($('#action_process').val(),$('#action_step').val(),$('#action_id').val()),5000);
                    $('#action_recipients').chosen();


                }
            });
        })

        function getActivities(process_id,step_id,action_id){
            let err = 0;

            if(process_id.length === 0 || process_id === 0){
                err++;
                $('.loader2').html('<div class="alert alert-info fade in show">Please select a process</div>');
            }

            if(step_id.length === 0 || step_id === 0){
                err++;
                $('.loader2').html('<div class="alert alert-info fade in show">Please select a step</div>');
            }

            if(err === 0) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    dataType: 'json',
                    url: '/get_action_activities/' + process_id + '/' + step_id + '/' + action_id,
                    type: 'POST',
                    data: {process_id: process_id, step_id: step_id},
                    beforeSend: function () {
                        $('.loader2').html(spinner);
                    },
                    success: function (data) {
                        let rows = '';
                        $.each(data, function (key, value) {
                            if (value.selected === '1') {
                                rows = rows + '<tr><td align="center"><input type="checkbox" id="action_activity_' + value.id + '" name="action_activities[]" class="action_activity" value="' + value.id + '" checked="checked" /></td><td>' + value.name + '</td></tr>';
                            } else {
                                rows = rows + '<tr><td align="center"><input type="checkbox" id="action_activity_' + value.id + '" name="action_activities[]" class="action_activity" value="' + value.id + '" /></td><td>' + value.name + '</td></tr>';
                            }

                        });
                        //alert(rows);
                        $(".table-activities").addClass('table-striped');
                        $("#actions_activities").html(rows);
                    }
                });
            }
        }

        var xTriggered = 0;

        $('body').on('keyup', '#action_search', function(event){
            /*$(document).on('keyup', "#action_search input[type='text']",function (event) {*/
            /*$('#action_search').on('keyup',function (event){*/

            let search = $('#action_search').val();
            let process_id = $('#action_process').val();
            let step_id = $('#action_step').val();

            if($('#action_search').val().length > 0) {
                if (event.which == 13) {
                    event.preventDefault();
                }
                xTriggered++;

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    dataType: 'json',
                    url: '/search_action_activities/' + process_id + '/' + step_id + '/' + search,
                    type: 'POST',
                    data: {process_id: process_id,step_id:step_id, search: search},
                    beforeSend: function () {
                        $('.loader2').html(spinner);
                    },
                    success: function (data) {
                        let rows = '';
                        $.each(data, function (key, value) {
                            if (value.selected === '1') {
                                rows = rows + '<tr><td align="center"><input type="checkbox" id="action_activity_' + value.id + '" name="action_activities[]" class="action_activity" value="' + value.id + '" checked="checked" /></td><td>' + value.name + '</td></tr>';
                            } else {
                                rows = rows + '<tr><td align="center"><input type="checkbox" id="action_activity_' + value.id + '" name="action_activities[]" class="action_activity" value="' + value.id + '" /></td><td>' + value.name + '</td></tr>';
                            }

                        });
                        //alert(rows);
                        $("#actions_activities").html(rows);
                    }
                });
            } else {
                getActivities($('#action_process').val(),$('#action_step').val(),$('#action_id').val())
            }
        });

        $('body').on('click', '.action_activity', function(event){
            //$("#actions_activities").on("click",".action_activity", function(){
            //if($(this).is(':checked')) {
            let id = $(this).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name=\"csrf-token\"]').attr('content')
                }
            });
            $.ajax({
                dataType: 'json',
                url: '/store_action_activity/' + id,
                type: 'post',
                data: {id: id}
            }).done(function (data) {
                let rows = '';
                $.each(data, function (key, value) {
                    if (value.selected === '1') {
                        rows = rows + '<tr><td align="center"><input type="checkbox" id="action_activity_' + value.id + '" name="action_activities[]" class="action_activity" value="' + value.id + '" checked="checked" /></td><td>' + value.name + '</td></tr>';
                    } else {
                        rows = rows + '<tr><td align="center"><input type="checkbox" id="action_activity_' + value.id + '" name="action_activities[]" class="action_activity" value="' + value.id + '" /></td><td>' + value.name + '</td></tr>';
                    }
                });
                $("#actions_activities").html(rows);
            });
            //}
        });

        $('body').on('click', '#save', function(e){
            e.preventDefault();

            let err = 0;

            let action_name = $('#action_name').val();
            let action_description = $('#action_description').val();
            let action_process = $('#action_process').val();
            let action_step = $('#action_step').val();
            let action_recipients = $("#action_recipients").val();
            let action_due_date = $("#action_due_date").val();

            $('#save_action_token').val($('meta[name=\"csrf-token\"]').attr('content'));
            if(action_name.length === 0){
                err++;
                $('#action_name').addClass('is-invalid');
            } else {
                $('#action_name').removeClass('is-invalid');
                $('#save_action_name').val(action_name);
            }
            if(action_due_date.length === 0){
                err++;
                $('#action_due_date').addClass('is-invalid');
            } else {
                $('#action_due_date').removeClass('is-invalid');
                $('#save_action_due_date').val(action_due_date);
            }
            if(action_description.length === 0){
                err++;
                $('#action_description').addClass('is-invalid');
            } else {
                $('#action_description').removeClass('is-invalid');
                $('#save_action_description').val(action_description);
            }

            if($("#action_process").val() == 0){
                err++;
                $('#action_process').addClass('is-invalid');
            } else {
                $('#action_process').removeClass('is-invalid');
                $('#save_action_process').val(action_process);
            }

            if($("#action_step").val() == 0){
                err++;
                $('#action_step').addClass('is-invalid');
            } else {
                $('#action_step').removeClass('is-invalid');
                $('#save_action_step').val(action_step);
            }

            if(action_recipients.length === 0){
                err++;
                $('#action_recipients_chosen').css('border','1px solid #dc3545');
            } else {
                $('#action_recipients_chosen').css('border','1px solid #ced4da');
                $('#save_action_recipients').val(action_recipients);
            }

            if(err === 0){
                $(this).parents('form:first').submit();
            }
        });

    </script>

@endsection