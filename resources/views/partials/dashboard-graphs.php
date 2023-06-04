<div class="row pt-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                Number of days to complete a client
                <div class="float-right">
                    <div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-secondary btn-graph active" id="type-1-column">
                            <input type="radio" name="blackboard-dashboard-1-type"><i class="far fa-chart-bar"></i>
                        </label>
                        <label class="btn btn-secondary btn-graph" id="type-1-bar">
                            <input type="radio" name="blackboard-dashboard-1-type"><i class="fa fa-align-left"></i>
                        </label>
                        <label class="btn btn-secondary btn-graph" id="type-1-line">
                            <input type="radio" name="blackboard-dashboard-1-type"><i class="fa fa-chart-line"></i>
                        </label>
                    </div>
                </div>
            </div>
            <div class="card-body p-1 pt-2 pb-2">
                <div id="blackboard-dashboard-1" class="m-0" style="height: 250px;"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                Completed Clients
                <div class="float-right">
                    <div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-secondary btn-graph" id="type-2-column">
                            <input type="radio" id="month_filter">Month
                        </label>
                        <label class="btn btn-secondary btn-graph active" id="type-2-column">
                            <input type="radio" id="week_filter">Week
                        </label>
                        &nbsp;
                        <label class="btn btn-secondary btn-graph active" id="type-2-column">
                            <input type="radio" name="blackboard-dashboard-2-type"><i class="far fa-chart-bar"></i>
                        </label>
                        <label class="btn btn-secondary btn-graph" id="type-2-bar">
                            <input type="radio" name="blackboard-dashboard-2-type"><i class="fa fa-align-left"></i>
                        </label>
                        <label class="btn btn-secondary btn-graph" id="type-2-line">
                            <input type="radio" name="blackboard-dashboard-2-type"><i class="fa fa-chart-line"></i>
                        </label>
                    </div>
                </div>
            </div>
            <div class="card-body p-1 pt-2 pb-2">
                <div id="blackboard-dashboard-2" class="m-0" style="height: 250px;"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                Average Step Lead Time
                <div class="float-right">
                    <div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-secondary btn-graph active" id="type-3-column">
                            <input type="radio" name="blackboard-dashboard-3-type"><i class="far fa-chart-bar"></i>
                        </label>
                        <label class="btn btn-secondary btn-graph" id="type-3-bar">
                            <input type="radio" name="blackboard-dashboard-3-type"><i class="fa fa-align-left"></i>
                        </label>
                        <label class="btn btn-secondary btn-graph" id="type-3-line">
                            <input type="radio" name="blackboard-dashboard-3-type"><i class="fa fa-chart-line"></i>
                        </label>
                    </div>
                </div>
            </div>
            <div class="card-body p-1 pt-2 pb-2">
                <div id="blackboard-dashboard-3" class="m-0" style="height: 250px;"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                Outstanding Activities
                <div class="float-right">
                    <div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-secondary active" id="type-4-select">
                            <div class="col-sm-8">
                                {{ Form::select('step_id', ['Select Step'] + $outstanding_activity_select, null, ['id'=>'step_id', 'style'=>'width:150px; font-size:0.6em; height:22px', 'class'=>'form-control form-control-sm']) }}
                            </div>
                        </label>
                        <label class="btn btn-secondary active" id="type-4-column">
                            <input type="radio" name="blackboard-dashboard-4-type"><i class="far fa-chart-bar"></i>
                        </label>
                        <label class="btn btn-secondary btn-graph" id="type-4-bar">
                            <input type="radio" name="blackboard-dashboard-4-type"><i class="fa fa-align-left"></i>
                        </label>
                        <label class="btn btn-secondary  btn-graph" id="type-4-line">
                            <input type="radio" name="blackboard-dashboard-4-type"><i class="fa fa-chart-line"></i>
                        </label>
                    </div>
                </div>
            </div>
            <div class="card-body p-1 pt-2 pb-2">
                <div id="blackboard-dashboard-4" class="m-0" style="height: 250px;"></div>
            </div>
        </div>
    </div>
</div>