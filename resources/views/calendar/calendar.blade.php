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