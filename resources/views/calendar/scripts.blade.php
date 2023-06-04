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
                @foreach($calendar_events as $calendar_event)
                {
                    id  : '{!! $calendar_event->id !!}',
                    title  : '{!! $calendar_event->title !!}',
                    start  : '{!! $calendar_event->start_date !!}',
                    end    : '{!! $calendar_event->end_date !!}'
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