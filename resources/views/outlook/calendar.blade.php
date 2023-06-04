@extends('layouts.app')
@section('title') Calendar @endsection
@section('header')
    <h1><i class="fa fa-calendar-o"></i> @yield('title')</h1>
    <button class="btn btn-outline-light float-right" id="blackboard-calendar-event-btn" name="testbutton" data-toggle="modal" data-target="#blackboard-calendar-event-modal"><i class="fa fa-plus"></i> Calendar Event</button>
@endsection
@section('content')
    <hr>
    <div id="calendar">
    </div>
    <div id="blackboard-calendar-event-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
        <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title text-left primecolor">Add Calendar Event</h3>
                    <button id="blackboard-modal-close" type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body" style="overflow: hidden;">
                    <div id="success-msg" class="blackboard-hide">
                        <div class="alert alert-info alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                          <strong>Success!</strong> Calendar event added successfully.
                        </div>
                    </div>
                    <div class="col-md-offset-1 col-md-10">
                        <form method="POST" id="add_calendar_event">
                            {{ csrf_field() }}
                            <div class="form-group has-feedback">
                                <input type="text" id="title" name="title" value="{{ old('title') }}" class="form-control" placeholder="Title">
                                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                <span class="text-danger">
                                    <strong id="title-error"></strong>
                                </span>
                            </div>
                            <div class="form-group has-feedback">
                                <input type="text" id="start_date" name="start_date" value="{{ date("Y-m-d H:i:s") }}" class="form-control" placeholder="Start Date">
                                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                <span class="text-danger">
                                    <strong id="start-date-error"></strong>
                                </span>
                            </div>
                            <div class="form-group has-feedback">
                                <input type="text" id="end_date" name="end_date" value="{{ date("Y-m-d 23:59:59") }}" class="form-control" placeholder="End Date">
                                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                <span class="text-danger">
                                    <strong id="end-date-error"></strong>
                                </span>
                            </div>
                            <div class="form-group has-feedback">
                                <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email">
                                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                <span class="text-danger">
                                    <strong id="email-error"></strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <button type="button" id="submitbtn" name="submitbtn" class="btn btn-primary btn-prime white btn-flat" onclick="storeEvent();">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extra-css')
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css'/>
    <style>
        .blackboard-hide{
            display: none;
        }
    </style>
@endsection
@section('extra-js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js'></script>
    <script>
        $(document).ready(function() {
            
            calendar = $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'listDay,month,agendaWeek,agendaDay'
                },
                editable: true,
                events: [
                @foreach($events as $event)
                {
                    id : '{!! $event->getId() !!}',
                    title  : '{!! $event->getSubject() !!}',
                    start  : '{!! (new DateTime($event->getStart()->getDateTime()))->format(DATE_RFC2822) !!}',
                    end    : '{!! (new DateTime($event->getEnd()->getDateTime()))->format(DATE_RFC822) !!}'
                },
                @endforeach
                ],
                selectable: true,
                selectHelper: true,
                select: function (start, end, allDay) {
                    $("#start_date").val($.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss"));
                    $("#end_date").val($.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss"));
                    $("#blackboard-calendar-event-btn").click();
                    calendar.fullCalendar('unselect');
                },
                editable: true,
                eventDrop: function (event, delta) {
                            var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                            var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                            $.ajax({
                                url: 'edit-event.php',
                                data: 'title=' + event.title + '&start=' + start + '&end=' + end + '&id=' + event.id,
                                type: "POST",
                                success: function (response) {
                                    displayMessage("Updated Successfully");
                                }
                            });
                        },
                eventClick: function (event) {
                    var deleteMsg = confirm("Do you really want to delete?");
                    if (deleteMsg) {
                        axios.delete('{!!url('calendarevents')!!}/'+event.id )
                        .then(function (response) {
                            alert(response.data.success);
                            $('#calendar').fullCalendar('removeEvents', event._id);
                        })
                        .catch(function () {
                            alert("Error: There was a problem with this request.");
                        });
                    }
                }
            })

        });
        
        function storeEvent(){
            //Clear form errors
            $('#title-error').html("");
            $('#email-error').html("");
            $('#start-date-error').html("");
            $('#end-date-error').html("");
            $('#submitbtn').html("Processing ...");
            $("#submitbtn").attr("disabled", "disabled");
            
            var post_data = {
                title: $("#title").val(),
                email: $("#email").val(),
                start_date: $("#start_date").val(),
                end_date: $("#end_date").val()
            };
            
            axios.post('{{route('calendarevents.store')}}', post_data)
            .then(function (response) {
                
                if(response.data.errors) {
                    if(response.data.errors.title){
                        $( '#title-error' ).html( response.data.errors.title[0] );
                    }
                    if(response.data.errors.email){
                        $( '#email-error' ).html( response.data.errors.email[0] );
                    }
                    if(response.data.errors.password){
                        $( '#start-date-error' ).html( data.errors.start_date[0] );
                    }
                    if(response.data.errors.password){
                        $( '#end-date-error' ).html( data.errors.end_date[0] );
                    }
                }
                if(response.data.success) {
                    $('#success-msg').removeClass('blackboard-hide');
                    $( '#title' ).val("");
                    $( '#email' ).val("");
                    $( '#start_date' ).val("");
                    $( '#end_date' ).val("");
            
                    setTimeout(function() {
                        $("#blackboard-modal-close").click();
                        $('#success-msg').addClass('blackboard-hide');
                    }, 3000);
                    
                    calendar.fullCalendar('renderEvent',
                        {
                            id: response.data.id,
                            title: response.data.title,
                            start: response.data.start_date,
                            end: response.data.end_date,
                            allDay: false
                        },
                        true
                    );
                }
                enableSubmitBtn();
            })
            .catch(function () {
                alert("Error: There was a problem with this request.");
                enableSubmitBtn();
            });
            
        }
        
        function enableSubmitBtn(){
            $('#submitbtn').html("Submit");
            $("#submitbtn").removeAttr("disabled", "disabled");
        }
    </script>
@endsection