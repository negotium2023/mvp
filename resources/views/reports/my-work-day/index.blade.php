@extends('flow.default')

@section('title') <strong style="color: #06496f;">My Work Day</strong>  @endsection

@section('content')
    <div class="content-container page-content">
        <div class="row col-md-12 h-100">
            <div class="container-fluid" style="padding-top: 10px;">
                <h3>@yield('title')</h3>
                <div class="row m-0 p-0 col-sm-12" style="text-align: left;">
                    <form class="form-inline w-100">
                        <div class="form-row w-100">
                            <div class="form-group col-md-3">
                                <span style="color: #085c8d8e;">Board</span><br />
                                <select name="board" class="form-control w-100 mt-0" style="font-weight: bold">
                                    {{-- <option value="">Board</option> --}}
                                    <option selected value="all" style="font-weight: bold">Select Board</option>
                                    @foreach($boards as $board)
                                        <option style="font-weight: bold" value="{{$board->id}}" {{(isset($_GET['board']) && $_GET['board'] == $board->id ? 'selected="selected"' : '')}}>{{$board->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <span style="color: #085c8d8e;">Section</span><br />
                                <select name="section" class="form-control w-100 mt-0" style="font-weight: bold">
                                    {{-- <option value="">Section</option> --}}
                                    <option selected value="all" style="font-weight: bold">Select Section</option>
                                    @foreach($sections as $section)
                                        <option style="font-weight: bold" value="{{$section->id}}" {{(isset($_GET['section']) && $_GET['section'] == $section->id ? 'selected="selected"' : '')}}>{{$section->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <span style="color: #085c8d8e;">Status</span><br />
                                <select name="status" class="form-control w-100 mt-0" style="font-weight: bold">
                                    {{-- <option value="">Status</option> --}}
                                    <option style="font-weight: bold" selected value="all" {{(isset($_GET['status']) && $_GET['status'] == 'all' ? 'selected="selected"' : '')}}>Select Status</option>
                                    <option style="font-weight: bold" value="complete" {{(isset($_GET['status']) && $_GET['status'] == 'complete' ? 'selected="selected"' : '')}}>Complete</option>
                                    <option style="font-weight: bold" value="uncomplete" {{(isset($_GET['status']) && $_GET['status'] == 'uncomplete' ? 'selected="selected"' : '')}}>Uncomplete</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <span style="color: #085c8d8e;">From Date</span>
                                {{Form::date('f',old('f'),['class'=>'form-control col-sm-12 mt-0','style'=>'height: calc(1.8125rem + 2px);'])}}
                            </div>
                            <div class="form-group col-md-2">
                                <span style="color: #085c8d8e;">To Date</span>
                                {{Form::date('t',old('t'),['class'=>'form-control col-sm-12 mt-0','style'=>'height: calc(1.8125rem + 2px);'])}}
                            </div>
                            <div class="form-group col-md-3 pt-0">
                                <span style="color: #085c8d8e;">Advisor on Record</span><br />
                                <select name="advisor" class="form-control w-100 mt-0" style="font-weight: bold">
                                    {{-- <option value="">Advisor on Record</option> --}}
                                    <option style="font-weight: bold" selected value="all">Select Advisor</option>
                                    @foreach($users as $user)
                                        <option style="font-weight: bold" value="{{$user->first_name}} {{$user->last_name}}" {{(isset($_GET['advisor']) && $_GET['advisor'] == $user->first_name.' '.$user->last_name ? 'selected="selected"' : '')}}>{{$user->first_name}} {{$user->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3 pt-0">
                                <span style="color: #085c8d8e;">Assignee</span><br />
                                <select name="user" class="form-control w-100 mt-0" style="font-weight: bold">
                                    {{-- <option value="">Assignee</option> --}}
                                    <option style="font-weight: bold" selected value="all">Select Assignee</option>
                                    @foreach($users as $user)
                                        <option style="font-weight: bold" value="{{$user->first_name}} {{$user->last_name}}" {{(isset($_GET['user']) && $_GET['user'] == $user->first_name.' '.$user->last_name ? 'selected="selected"' : '')}}>{{$user->first_name}} {{$user->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3 pt-0">
                                <small>&nbsp;</small><br />
                                <input type="text" name="q" value="{{(isset($_GET['q']) ? $_GET['q'] : '')}}" class="form-control w-100 mt-0" placeholder="Search..."/>
                            </div>
                            <div class="form-group col-md-3 pt-0">
                                <button type="submit" class="btn btn-sm btn-primary submit-btn ml-2 mt-2" style="width: 46%;">Search</button>&nbsp;
                                <a href="{{route('reports.myworkday')}}" class="btn btn-sm btn-outline-info submit-btn mt-2" style="width: 46%;">Clear</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="container-fluid container-content" style="height:calc(100% - 14rem);overflow: hidden;">
                <div class="table-responsive" style="height:100%;overflow: auto;">
                    <table class="table table-responsive  table-hover" style="position: relative;">
                        <thead>
                        <tr>
                            <th>

                            </th>
                            <th>
                                Action
                            </th>
                            <th>
                                Status
                            </th>
                            <th>
                                ID
                            </th>
                            <th nowrap >
                                Card Name
                            </th>
                            <th nowrap>
                                Create Date
                            </th>
                            <th>
                                Client
                            </th>
                            <th>
                                Client ID Number
                            </th>
                            <th nowrap >
                                Card Summary Description
                            </th>
                            <th nowrap >
                                Feedback Notes From Client
                            </th>
                            <th nowrap >
                                File Prep
                            </th>
                            <th nowrap>
                                Financial Advisor on Record
                            </th>
                            <th>
                                Assignee
                            </th>
                            <th nowrap>
                                Due Date
                            </th>
                            <th nowrap>
                                Completed Date
                            </th>
                            <th>
                                Comments
                            </th>
                            <th>
                                Board
                            </th>
                            <th>
                                Section
                            </th>
                            {{-- <th>
                                Actions
                            </th> --}}
                        </tr>
                        </thead>
                        <tbody id="myworkday">
                        @forelse($cards as $card)
                            @if(isset($card->section->board))
                                <tr id="{{$card->id}}" data-id="{{$card->id}}" class="my-card {{($card->due_date < \Carbon\Carbon::parse(now())->format('Y-m-d') && $card->complete == 0 ? 'overdue' : '')}} {{($card->complete == '1' ? 'completed' : '')}}">
                                <td nowrap>
                                    @if(count($card["tasks"]) > 0) <a href="javascript:void(0)" onclick="showTasks({{$card->id}})" style="padding:0 7px;"><i class="fas fa-plus text-success"></i></a> @else <a href="javascript:void(0)" style="padding:0 7px;"><i class="fas fa-plus text-default"></i></a> @endif
                                </td>
                                <td nowrap>
                                    @if($card["complete"] == '1') <a href="javascript:void(0)" onclick="toggleCardStatus({{$card->id}})" style="padding:0 7px;" class="text-danger" id="c{{$card->id}}t"><i class="fas fa-check"></i></a> @else <a href="javascript:void(0)" onclick="toggleCardStatus({{$card->id}})" style="padding:0 7px;" class="text-success" id="c{{$card->id}}t"><i class="fas fa-check"></i></a> @endif
                                </td>
                                <td nowrap>
                                    <span id="card_status_badge{{$card->id}}" class="badge {{($card["complete"] == '1' ? 'badge-success' : 'badge-danger')}}" style="padding: 7px; border-radius: 2px !important">{{($card["complete"] == '1' ? 'Complete' : 'Uncomplete')}}</span>
                                </td>
                                <td style="text-align: center;">{{$card->id}}</td>
                                <td nowrap>{{$card->name}}</td>
                                <td nowrap>{{date("d-m-Y",strtotime($card->created_at))}}</td>
                                <td nowrap>{{($card->client_name != null && $card->client_name != '' ? $card->client_name : '')}}</td>
                                <td nowrap>{{isset($card->client["id_number"]) ? $card->client["id_number"] : ''}}</td>
                                <td>{{$card->summary_description}}</td>
                                <td>{{$card->description}}</td>
                                <td>{{$card->description2}}</td>
                                <td nowrap>{{($card->assignee_name != null && $card->assignee_name != '' ? $card->assignee_name : '')}}</td>
                                <td>{{$card->team_names}}</td>
                                <td nowrap>{{date("d-m-Y",strtotime($card->due_date))}}</td>
                                <td nowrap id="c{{$card->id}}-completed_date">{{($card->completed_date != '' ? \Carbon\Carbon::parse($card->completed_date)->format('d-m-Y') : '')}}</td>
                                <td></td>
                                <td nowrap>{{$card->section->board->name}}</td>
                                <td nowrap>{{$card->section->name}}</td>
                                {{-- <td nowrap>
                                    @if($card["complete"] == '1') <a href="javascript:void(0)" onclick="toggleCardStatus({{$card->id}})" style="padding:0 7px;" class="text-danger" id="c{{$card->id}}t"><i class="fas fa-times"></i></a> @else <a href="javascript:void(0)" onclick="toggleCardStatus({{$card->id}})" style="padding:0 7px;" class="text-success" id="c{{$card->id}}t"><i class="fas fa-check"></i></a> @endif
                                </td> --}}
                            </tr>
                            @endif
                            @if(count($card["tasks"]) > 0)
                            <tr class="my-task tasks-{{$card->id}}" style="display: none;">
                                <td colspan="17" style="padding: 10px 0px !important;">
                                <table class="table-responsive table-bordered table-hover" style="width: calc(100% - 2rem);margin-left: 2rem;">
                                    <tr>
                                        <th></th>
                                        <th>Action</th>
                                        <th>Status</th>
                                        <th>Create Date</th>
                                        <th>Task Name</th>
                                        <th>Assignee</th>
                                        <th>Due Date</th>
                                        <th>Completed Date</th>
                                    </tr>
                                    @forelse($card["tasks"] as $card)
                                    <tr id="t{{$card->id}}" class="{{($card->due_date < \Carbon\Carbon::parse(now())->format('Y-m-d') && $card->status_id == 0 ? 'overdue' : ($card->status_id == 1 ? 'completed' : ''))}}">
                                        <td nowrap class="last">@if(count($card["subTasks"]) > 0) <a href="javascript:void(0)" onclick="showSubtasks({{$card->id}})" style="padding: 0 7px;"><i class="fas fa-plus text-success"></i></a> @else <a href="javascript:void(0)" style="padding: 0 7px;"><i class="fas fa-plus text-default"></i></a> @endif</td>
                                        <td nowrap class="last">@if($card["status_id"] == 1) <a href="javascript:void(0)" onclick="toggleTaskStatus({{$card->id}},{{$card->status_id}})" style="padding: 0 7px;" class="text-danger" id="t{{$card->id}}t"><i class="fas fa-check"></i></a> @else <a href="javascript:void(0)" onclick="toggleTaskStatus({{$card->id}},{{$card->status_id}})" style="padding: 0 7px;" class="text-success" id="t{{$card->id}}t"><i class="fas fa-check"></i></a> @endif</td>
                                        <td nowrap>
                                            <span id="task_status_badge{{$card->id}}" class="badge {{($card->status_id == '1' ? 'badge-success' : 'badge-danger')}}" style="padding: 7px; border-radius: 2px !important">{{($card->status_id == '1' ? 'Complete' : 'Uncomplete')}}</span>
                                        </td>
                                        <td nowrap>{{date("Y-m-d",strtotime($card->created_at))}}</td>
                                        <td>{{$card->name}}</td>
                                        <td>{{($card->assignee_name != null && $card->assignee_name != '' ? $card->assignee_name : '')}}</td>
                                        <td nowrap>{{$card->due_date}}</td>
                                        <td nowrap id="t{{$card["id"]}}-completed_date">{{($card->completed_date != '' ? \Carbon\Carbon::parse($card->completed_date)->format('Y-m-d') : '')}}</td>
                                    </tr>
                                        @if(count($card["subTasks"]) > 0)
                                            <tr class="my-subtask subtasks-{{$card->id}}" style="display: none;">
                                                <td colspan="17" style="padding: 10px 0px !important; border-bottom: 0px !important;border-left: 0px !important;border-right: 0px !important;">
                                                    <table style="width: calc(100% - 2rem);margin-left: 2rem;">
                                                        <tr>
                                                            <th></th>
                                                            <th>Action</th>
                                                            <th>Status</th>
                                                            <th>Create Date</th>
                                                            <th>Task Name</th>
                                                            <th>Assignee</th>
                                                            <th>Due Date</th>
                                                            <th>Completed Date</th>
                                                        </tr>
                                                        @forelse($card["subTasks"] as $card)
                                                            <tr id="st{{$card->id}}" class="{{($card->due_date < \Carbon\Carbon::parse(now())->format('Y-m-d') && $card->status_id == 0 ? 'overdue' : ($card->status_id == 1 ? 'completed' : ''))}}">
                                                                <td nowrap class="last">@if(count($card["subTasks"]) > 0) <a href="javascript:void(0)" onclick="showSubtasks({{$card->id}})" style="padding: 0 7px;"><i class="fas fa-plus text-success"></i></a> @else <a href="javascript:void(0)" style="padding: 0 7px;"><i class="fas fa-plus text-default"></i></a> @endif</td>
                                                                <td nowrap class="last">@if($card["status_id"] == 1) <a href="javascript:void(0)" onclick="toggleTaskStatus({{$card->id}},{{$card->status_id}})" style="padding: 0 7px;" class="text-danger" id="t{{$card->id}}t"><i class="fas fa-check"></i></a> @else <a href="javascript:void(0)" onclick="toggleTaskStatus({{$card->id}},{{$card->status_id}})" style="padding: 0 7px;" class="text-success" id="t{{$card->id}}t"><i class="fas fa-check"></i></a> @endif</td>
                                                                <td nowrap>
                                                                    <span id="sub_task_status_badge{{$card->id}}" class="badge {{($card->status_id == '1' ? 'badge-success' : 'badge-danger')}}" style="padding: 7px; border-radius: 2px !important">{{($card->status_id == '1' ? 'Complete' : 'Uncomplete')}}</span>
                                                                </td>
                                                                <td nowrap>{{date("Y-m-d",strtotime($card->created_at))}}</td>
                                                                <td>{{$card->name}}</td>
                                                                <td>{{($card->assignee_name != null && $card->assignee_name != '' ? $card->assignee_name : '')}}</td>
                                                                <td nowrap>{{$card->due_date}}</td>
                                                                <td nowrap>{{($card->completed_date != '' ? \Carbon\Carbon::parse($card->completed_date)->format('Y-m-d') : '')}}</td>
                                                            </tr>
                                                        @empty
                                                        @endforelse
                                                    </table>
                                                </td>

                                            </tr>
                                        @endif
                                    @empty
                                    @endforelse
                                </table>
                                </td>

                            </tr>
                            @endif


                        @empty
                        @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalShowMyWorkDayItem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width: 618px;max-width: 618px;">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom: 2px solid #06496f;padding:27px 27px 0px 27px;display: block !important;">
                    <small style="color: #06496f;opacity: 0.5;" class="section_name"></small>
                    <h5 class="modal-title card_name"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0 mb-0">
                    <input type="hidden" class="card_id">
                    <div class="col-md-12 pb-0 pt-0">
                        <small>Case Summary</small>
                        <span class="case_summary w-100 d-block"></span>
                    </div>
                    <div class="col-md-12 pb-0">
                        <small>Client</small>
                        <span class="client w-100 d-block"></span>
                    </div>
                    <div class="row col-md-12 pb-0 mt-0">
                        <div class="col-md-4 mb-0 mt-0 pt-0">
                            <small>Insurer</small>
                            <span class="insurer w-100 d-block"></span>
                        </div>
                        <div class="col-md-4 mb-0 mt-0 pt-0">
                            <small>Policy</small>
                            <span class="policy w-100 d-block"></span>
                        </div>
                        <div class="col-md-4 mb-0 mt-0 pt-0">
                            <small>Dependency</small>
                            <span class="dependency w-100 d-block"></span>
                        </div>
                    </div>
                    <div class="row col-md-12 pb-0 mt-0">
                        <div class="col-md-4 mb-0 mt-0 pt-0">
                            <small>Advisor on record</small>
                            <span class="advisor w-100 d-block"></span>
                        </div>
                        <div class="col-md-8 mb-0 mt-0 pt-0">
                            <small>Assign Team members</small>
                            <span class="team w-100 d-block"></span>
                        </div>
                    </div>
                    <div class="row col-md-12 pb-0 mt-0">
                        <div class="col-md-6 mb-0 mt-0 pt-0">
                            <small>Upfront Revenue</small>
                            <span class="upfront_revenue w-100 d-block"></span>
                        </div>
                        <div class="col-md-6 mb-0 mt-0 pt-0">
                            <small>Ongoing Revenue</small>
                            <span class="ongoing_revenue w-100 d-block"></span>
                        </div>
                    </div>
                    <div class="row col-md-12 pb-0 mt-0">
                        <div class="col-md-4 mb-0 mt-0 pt-0">
                            <small>Due Date</small>
                            <span class="due_date w-100 d-block"></span>
                        </div>
                        <div class="col-md-4 mb-0 mt-0 pt-0">
                            <small>Status</small>
                            <span class="status w-100 d-block"></span>
                        </div>
                        <div class="col-md-4 mb-0 mt-0 pt-0">
                            <small>Priority</small>
                            <span class="priority w-100 d-block"></span>
                        </div>
                    </div>
                    <div class="col-md-12 pb-0">
                        <small>Feedback Notes From Client</small>
                        <span class="description w-100 d-block"></span>
                    </div>
                    <div class="col-md-12 pb-0">
                        <small>File Prep</small>
                        <span class="description2 w-100 d-block"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>&nbsp;
                    <button class="btn btn-primary" onclick="editMyWorkDayItem()">Edit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditMyWorkDayItem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width: 618px;max-width: 618px;">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom: 2px solid #06496f;padding:27px 27px 0px 27px;display: block !important;">
                    <small style="color: #06496f;opacity: 0.5;" class="section_name"></small>
                    <input type="text" class="form-control form-control-sm card_name mt-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0 mb-0">
                    <input type="hidden" class="card_id">
                    <div class="col-md-12 pb-0 pt-0">
                        <small>Case Summary</small>
                        <input type="text" class="form-control form-control-sm case_summary w-100">
                    </div>
                    <div class="col-md-12 pb-0">
                        <small>Client</small>
                        <select class="form-control form-control-sm client w-100"></select>
                    </div>
                    <div class="row col-md-12 pb-0 mt-0">
                        <div class="col-md-4 mb-0 mt-0 pt-0">
                            <small>Insurer</small>
                            <input type="text" class="form-control form-control-sm insurer w-100 d-block">
                        </div>
                        <div class="col-md-4 mb-0 mt-0 pt-0">
                            <small>Policy</small>
                            <input type="text" class="form-control form-control-sm policy w-100 d-block">
                        </div>
                        <div class="col-md-4 mb-0 mt-0 pt-0">
                            <small>Dependency</small>
                            <select class="form-control form-control-sm dependency w-100"></select>
                        </div>
                    </div>
                    <div class="row col-md-12 pb-0 mt-0">
                        <div class="col-md-4 mb-0 mt-0 pt-0">
                            <small>Advisor on record</small>
                            <select class="form-control form-control-sm advisor w-100"></select>
                        </div>
                        <div class="col-md-8 mb-0 mt-0 pt-0">
                            <small>Assign Team members</small>
                            <select multiple class="chosen-select form-control form-control-sm team w-100"></select>
                        </div>
                    </div>
                    <div class="row col-md-12 pb-0 mt-0">
                        <div class="col-md-6 mb-0 mt-0 pt-0">
                            <small>Upfront Revenue</small>
                            <input type="number" class="form-control form-control-sm upfront_revenue w-100 d-block">
                        </div>
                        <div class="col-md-6 mb-0 mt-0 pt-0">
                            <small>Ongoing Revenue</small>
                            <input type="number" class="form-control form-control-sm ongoing_revenue w-100 d-block">
                        </div>
                    </div>
                    <div class="row col-md-12 pb-0 mt-0">
                        <div class="col-md-4 mb-0 mt-0 pt-0">
                            <small>Due Date</small>
                            <input type="date" class="form-control form-control-sm due_date w-100 d-block">
                        </div>
                        <div class="col-md-4 mb-0 mt-0 pt-0">
                            <small>Status</small>
                            <select class="form-control form-control-sm status w-100"></select>
                        </div>
                        <div class="col-md-4 mb-0 mt-0 pt-0">
                            <small>Priority</small>
                            <select class="form-control form-control-sm priority w-100"></select>
                        </div>
                    </div>
                    <div class="col-md-12 pb-0">
                        <small>Feedback Notes From Client</small>
                        <textarea rows="7" class="description w-100 d-block"></textarea>
                    </div>
                    <div class="col-md-12 pb-0">
                        <small>File Prep</small>
                        <textarea rows="7" class="description2 w-100 d-block"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>&nbsp;
                    <button class="btn btn-success" onclick="saveMyWorkDayItem()">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extra-css')
    <style>
        .text-default{
            color: #777777 !important;
        }
        #myworkday tr{
            cursor: pointer;
        }

        /* #myworkday .my-card.overdue{
            background: #e99b9a;
        } */

        /* .completed{
            background: #a0e99a;
        } */

        /* #myworkday .my-task tr.overdue{
            background: rgba(233,155,154,0.7);
        }

        #myworkday .my-subtask tr.overdue{
            background: rgba(233,155,154,0.4);
        } */

        table  th{
            position: sticky;
            top: -1px;
            background: #FFF;
            /* outline: 1px solid #e9ecef; */
            border-bottom: 1px solid #e9ecef !important;
            border-top: 2px solid #4fc6e5 !important;
            border-right: 1px solid #e9ecef !important;
            outline-offset: -1px;
            vertical-align: middle !important;
            color: #06496f;
        }

        table  td{
            /* border-bottom:1px solid #e9ecef !important; */
            border-top: 0 !important;
            border-right: 1px solid #e9ecef !important;
            color: #06496f;
        }

        #modalShowMyWorkDayItem span{
            color:#06496f;
        }

        #modalEditMyWorkDayItem .modal-body input,#modalEditMyWorkDayItem .modal-body select{
            margin: 0px !important;
        }

        #modalEditMyWorkDayItem .modal-body .chosen-choices{
            padding: 0px 5px !important;
        }
        #modalEditMyWorkDayItem .modal-body input[type=date]{
            padding:.25rem .5rem !important;
        }

        tr.my-task:hover,tr.my-subtask:hover{
            background-color: transparent !important;
        }

        

    </style>
@endsection
@section('extra-js')
    <script>

            $('#myworkday').on('click','.my-card td',function (event) {

                if($(this).is(":nth-child(1)") || $(this).is(":nth-child(2)") || $(this).is(":nth-child(18)")){
                    console.log('td');
                } else {
                    let id = $(this).closest('tr').attr('data-id');
                    showWorkDayItem(id);
                }
            })

        function showWorkDayItem(id){
            var id = id;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/reports/my-work-day/' + id + '/details',
                type:"GET",
                dataType:"json",
                success:function(data){
                    $("#modalShowMyWorkDayItem").modal('show');
                    $('#modalShowMyWorkDayItem').find('.card_id').val(data.card.id);
                    $('#modalShowMyWorkDayItem').find('.section_name').html(data.section_name);
                    $('#modalShowMyWorkDayItem').find('.card_name').html(data.card.name);
                    $('#modalShowMyWorkDayItem').find('.case_summary').html(data.card.summary_description);
                    if(data.card.client_name === null) {
                        $('#modalShowMyWorkDayItem').find('.client').html('None');
                    } else {
                        $('#modalShowMyWorkDayItem').find('.client').html(data.card.client_name);
                    }
                    $('#modalShowMyWorkDayItem').find('.insurer').html(data.card.insurer);
                    $('#modalShowMyWorkDayItem').find('.policy').html(data.card.policy);
                    $('#modalShowMyWorkDayItem').find('.dependency').html(data.dependency);
                    $('#modalShowMyWorkDayItem').find('.advisor').html(data.card.assignee_name);
                    $('#modalShowMyWorkDayItem').find('.team').html(data.card.team_names);
                    $('#modalShowMyWorkDayItem').find('.upfront_revenue').html(data.card.upfront_revenue);
                    $('#modalShowMyWorkDayItem').find('.ongoing_revenue').html(data.card.ongoing_revenue);
                    $('#modalShowMyWorkDayItem').find('.due_date').html(data.card.due_date);
                    $('#modalShowMyWorkDayItem').find('.status').html(data.statuss);
                    $('#modalShowMyWorkDayItem').find('.priority').html(data.priority);
                    $('#modalShowMyWorkDayItem').find('.description').html(data.card.description);
                    $('#modalShowMyWorkDayItem').find('.description2').html(data.card.description2);
                }
            });
        }
        /*function addRowHandlers() {
            var table = document.getElementById("myworkday");
            var rows = $("table > tbody > tr:first > td");

            for (i = 0; i <= rows.length; i++) {
                var currentRow = table.rows[i];
                var createClickHandler =
                    function (row) {
                        return function () {
                            var cell = row.getElementsByTagName("td")[0];
                            var id = row.getAttribute('data-id');

                            if(row.classList.contains("my-card")) {
                                if(cell) {
                                    $.ajaxSetup({
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        }
                                    });

                                    $.ajax({
                                        url: '/reports/my-work-day/' + id + '/details',
                                        type: "GET",
                                        dataType: "json",
                                        success: function (data) {
                                            $("#modalShowMyWorkDayItem").modal('show');
                                            $('#modalShowMyWorkDayItem').find('.card_id').val(data.card.id);
                                            $('#modalShowMyWorkDayItem').find('.section_name').html(data.section_name);
                                            $('#modalShowMyWorkDayItem').find('.card_name').html(data.card.name);
                                            $('#modalShowMyWorkDayItem').find('.case_summary').html(data.card.summary_description);
                                            if (data.card.client_name === null) {
                                                $('#modalShowMyWorkDayItem').find('.client').html('None');
                                            } else {
                                                $('#modalShowMyWorkDayItem').find('.client').html(data.card.client_name);
                                            }
                                            $('#modalShowMyWorkDayItem').find('.insurer').html(data.card.insurer);
                                            $('#modalShowMyWorkDayItem').find('.policy').html(data.card.policy);
                                            $('#modalShowMyWorkDayItem').find('.dependency').html(data.dependency);
                                            $('#modalShowMyWorkDayItem').find('.advisor').html(data.card.assignee_name);
                                            $('#modalShowMyWorkDayItem').find('.team').html(data.card.team_names);
                                            $('#modalShowMyWorkDayItem').find('.upfront_revenue').html(data.card.upfront_revenue);
                                            $('#modalShowMyWorkDayItem').find('.ongoing_revenue').html(data.card.ongoing_revenue);
                                            $('#modalShowMyWorkDayItem').find('.due_date').html(data.card.due_date);
                                            $('#modalShowMyWorkDayItem').find('.status').html(data.statuss);
                                            $('#modalShowMyWorkDayItem').find('.priority').html(data.priority);
                                            $('#modalShowMyWorkDayItem').find('.description').html(data.card.description);
                                        }
                                    });
                                }
                            }
                        };
                    };
                currentRow.onclick = createClickHandler(currentRow);
            }
        }

        window.onload = addRowHandlers();*/

        $(".chosen-select").chosen();

        $('#modalShowMyWorkDayItem').on('hidden.bs.modal', function () {
            $('#modalShowMyWorkDayItem').find('.card_id').val('');
            $('#modalShowMyWorkDayItem').find('.section_name').html('');
            $('#modalShowMyWorkDayItem').find('.card_name').html('');
            $('#modalShowMyWorkDayItem').find('.case_summary').html('');
                $('#modalShowMyWorkDayItem').find('.client').html('');
            $('#modalShowMyWorkDayItem').find('.insurer').html('');
            $('#modalShowMyWorkDayItem').find('.policy').html('');
            $('#modalShowMyWorkDayItem').find('.dependency').html('');
            $('#modalShowMyWorkDayItem').find('.advisor').html('');
            $('#modalShowMyWorkDayItem').find('.team').html('');
            $('#modalShowMyWorkDayItem').find('.upfront_revenue').html('');
            $('#modalShowMyWorkDayItem').find('.ongoing_revenue').html('');
            $('#modalShowMyWorkDayItem').find('.due_date').html('');
            $('#modalShowMyWorkDayItem').find('.status').html('');
            $('#modalShowMyWorkDayItem').find('.priority').html('');
            $('#modalShowMyWorkDayItem').find('.description').html('');
        })

        $('#modalEditMyWorkDayItem').on('hidden.bs.modal', function () {
            $('#modalEditMyWorkDayItem').find('.card_id').val('');
            $('#modalEditMyWorkDayItem').find('.section_name').html('');
            $('#modalEditMyWorkDayItem').find('.card_name').val('');
            $('#modalEditMyWorkDayItem').find('.case_summary').val('');
            $("#modalEditMyWorkDayItem").find('.status').empty();
            $("#modalEditMyWorkDayItem").find('.priority').empty();
            $("#modalEditMyWorkDayItem").find('.dependency').empty();
            $("#modalEditMyWorkDayItem").find('.advisor').empty();
            $('#modalEditMyWorkDayItem').find('.client').empty();
            $('#modalEditMyWorkDayItem').find(".team").empty();
            $('#modalEditMyWorkDayItem').find('.insurer').val('');
            $('#modalEditMyWorkDayItem').find('.policy').val('');
            $('#modalEditMyWorkDayItem').find('.dependency').val('');
            $('#modalEditMyWorkDayItem').find('.advisor').val('');
            $('#modalEditMyWorkDayItem').find('.upfront_revenue').val('');
            $('#modalEditMyWorkDayItem').find('.ongoing_revenue').val('');
            $('#modalEditMyWorkDayItem').find('.due_date').val('');
            $('#modalEditMyWorkDayItem').find('.status').val('');
            $('#modalEditMyWorkDayItem').find('.priority').val('');
            $('#modalEditMyWorkDayItem').find('.description').val('');
        });

        function saveMyWorkDayItem() {
            let id = $('#modalEditMyWorkDayItem').find('.card_id').val();
            let card_name = $('#modalEditMyWorkDayItem').find('.card_name').val();
            let summary_description = $('#modalEditMyWorkDayItem').find('.case_summary').val();
            let status = $('#modalEditMyWorkDayItem').find('.status').val();
            let priority = $('#modalEditMyWorkDayItem').find('.priority').val();
            let policy = $('#modalEditMyWorkDayItem').find('.policy').val();
            let insurer = $('#modalEditMyWorkDayItem').find('.insurer').val();
            let dependency = $('#modalEditMyWorkDayItem').find('.dependency').val();
            let upfront = $('#modalEditMyWorkDayItem').find('.upfront_revenue').val();
            let ongoing = $('#modalEditMyWorkDayItem').find('.ongoing_revenue').val();
            let due_date = $('#modalEditMyWorkDayItem').find('.due_date').val();
            let description = $('#modalEditMyWorkDayItem').find('.description').val();
            let description2 = $('#modalEditMyWorkDayItem').find('.description2').val();
            let client = $('#modalEditMyWorkDayItem').find('.client').val();
            let advisor = $('#modalEditMyWorkDayItem').find('.advisor').val();
            let team = $('#modalEditMyWorkDayItem').find('.team').val();


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: '/reports/my-work-day/' + id + '/save',
                data: {id:id,client:client,advisor:advisor,team:team,description:description,description2:description2,due_date:due_date,ongoing:ongoing,upfront:upfront,dependency:dependency,insurer:insurer,policy:policy,priority:priority,status:status,card_name:card_name,summary_description:summary_description},
                success: function( data ) {
                    
                    let row = '';
                    $("#modalEditMyWorkDayItem").modal('hide');

                    let task_length = '';
                    console.log(data.tasks.length);
                    if(data.tasks.length > 0){
                        task_length = '<a href="javascript:void(0)" onclick="showTasks(' + data.id + ')" style="padding:0 7px;"><i class="fas fa-plus text-success"></i></a>';
                    } else {
                        task_length = '<a href="javascript:void(0)" style="padding:0 7px;"><i class="fas fa-plus text-default"></i></a>';
                    }

                    let complete = '';
                    if(data.complete === "1"){
                        complete = '<a href="javascript:void(0)" onclick="toggleCardStatus(data.id)" style="padding:0 7px;" class="text-success" id="c' + data.id + 't"><i class="fas fa-check"></i></a>';
                    } else {
                        complete = '<a href="javascript:void(0)" onclick="toggleCardStatus(' + data.id + ')" style="padding:0 7px;" class="text-default" id="c' + data.id + 't"><i class="fas fa-check"></i></a>';
                    }

                    let overdue = '';
                    var date1= new Date(data.due_date);
                    var date2= new Date();

                    if(date1 < date2 && data.complete === "0"){
                        overdue = 'overdue';
                    }

                    row = '<tr id="' + data.id + '" data-id="' + data.id + '" class="my-card ' + overdue + ' ' + (data.complete === '1' ? 'completed' : '') + '">' +
                        '<td nowrap>' + task_length + '</td>' + '' +
                        '<td nowrap>' + complete + '</td>' +
                    '<td>' + data.id + '</td>' +
                    '<td>' + data.name + '</td>' +
                    '<td>' + data.created + '</td>' +
                    '<td>' + data.client + '</td>' +
                    '<td>' + data.client_id +  '</td>' +
                    '<td>' + data.summary + '</td>' +
                    '<td>' + data.description + '</td>' +
                    '<td>' + data.description2 + '</td>' +
                    '<td>' + data.advisor + '</td>' +
                    '<td>' + data.team_names + '</td>' +
                    '<td>' + data.due_date + '</td>' +
                    '<td>' + data.completed_date + '</td>' +
                    '<td></td>' +
                    '<td>' + data.board + '</td>' +
                    '<td>' + data.section + '</td></tr>';

                    $(document).find('#' + id).replaceWith(row);

                        toastr.success('<strong>Success!</strong> Card was updated successfully.');
                        

                        toastr.options.timeOut = 1000;
                        
                        location.reload();
                }
            });


        }

        function editMyWorkDayItem() {
            let id = $('#modalShowMyWorkDayItem').find('.card_id').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/reports/my-work-day/' + id + '/details',
                type:"GET",
                dataType:"json",
                success:function(data){
                    $("#modalShowMyWorkDayItem").modal('hide');
                    $("#modalEditMyWorkDayItem").modal('show');
                    $('#modalEditMyWorkDayItem').find('.card_id').val(data.card.id);
                    $('#modalEditMyWorkDayItem').find('.section_name').html(data.section_name);
                    $('#modalEditMyWorkDayItem').find('.card_name').val(data.card.name);
                    $('#modalEditMyWorkDayItem').find('.case_summary').val(data.card.summary_description);
                    $.each(data.progress_status, function(key, value) {
                        $("#modalEditMyWorkDayItem").find('.status').append($("<option></option>").attr("value",key).text(value));
                    });
                    $.each(data.priority_status, function(key, value) {
                        $("#modalEditMyWorkDayItem").find('.priority').append($("<option></option>").attr("value",key).text(value));
                    });
                    $.each(data.office_clients, function(key, value) {
                        $("#modalEditMyWorkDayItem").find('.client').append($("<option></option>").attr("value",value).text(value));
                    });
                    $.each(data.cards, function(key, value) {
                        $("#modalEditMyWorkDayItem").find('.dependency').append($("<option></option>").attr("value",key).text(value));
                    });
                    $.each(data.office_users, function(key, value) {
                        $("#modalEditMyWorkDayItem").find('.team').append($("<option></option>").attr("value",value.full_name).text(value.full_name));
                        $("#modalEditMyWorkDayItem").find('.advisor').append($("<option></option>").attr("value",value.full_name).text(value.full_name));
                    });
                    if(data.card.client_name === null) {
                        $('#modalEditMyWorkDayItem').find('.client').val('None');
                    } else {
                        $('#modalEditMyWorkDayItem').find('.client').val(data.card.client_name);
                    }
                    if(data.card.team_names === null) {

                    } else {
                        $.each(data.card.team_names.replace(', ', ',').split(","), function (i, e) {
                            $('#modalEditMyWorkDayItem').find(".team option[value='" + e + "']").prop("selected", true);
                            $('#modalEditMyWorkDayItem').find(".chosen-select").trigger("chosen:updated");
                        });
                    }
                    $('#modalEditMyWorkDayItem').find('.insurer').val(data.card.insurer);
                    $('#modalEditMyWorkDayItem').find('.policy').val(data.card.policy);
                    $('#modalEditMyWorkDayItem').find('.dependency').val(data.card.dependency_id);
                    $('#modalEditMyWorkDayItem').find('.advisor').val(data.card.assignee_name);
                    /*$('#modalEditMyWorkDayItem').find('.team').val(data.card.team_names);*/
                    $('#modalEditMyWorkDayItem').find('.upfront_revenue').val(data.card.upfront_revenue);
                    $('#modalEditMyWorkDayItem').find('.ongoing_revenue').val(data.card.ongoing_revenue);
                    $('#modalEditMyWorkDayItem').find('.due_date').val(data.card.due_date);
                    $('#modalEditMyWorkDayItem').find('.status').val(data.card.status_id);
                    $('#modalEditMyWorkDayItem').find('.priority').val(data.card.priority_id);
                    $('#modalEditMyWorkDayItem').find('.description').val(data.card.description);
                    $('#modalEditMyWorkDayItem').find('.description2').val(data.card.description2);
                }
            });
        }

        function showTasks(id){
            if($('.tasks-' + id).is(':visible')) {
                $('.tasks-' + id).hide();
            } else {
                $('.tasks-' + id).show();
            }
        }

        function showSubtasks(id){
            if($('.subtasks-' + id).is(':visible')) {
                $('.subtasks-' + id).hide();
            } else {
                $('.subtasks-' + id).show();
            }
        }

        function toggleCardStatus(id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: '/reports/my-work-day/card/update_status/' + id,
                data: {currentstatus:status},
                success: function( data ) {
                    //$('#'+data.card.id).remove();

                    var today = new Date();
                    var dueDate = new Date(data.card.due_date);
                    console.log(today);
                    console.log(dueDate);

                    if(today > dueDate && data.card.complete === 0){
                        $('#'+data.card.id).addClass('overdue');
                        $('#'+data.card.id).removeClass('completed');
                        $('#card_status_badge'+data.card.id).removeClass('badge-success');
                        $('#card_status_badge'+data.card.id).addClass('badge-danger');
                        $('#card_status_badge'+data.card.id).html('Uncomplete');
                    }

                    if(data.card.complete === 1){
                        $('#'+data.card.id).addClass('completed');
                        $('#'+data.card.id).removeClass('overdue');
                        $('#card_status_badge'+data.card.id).removeClass('badge-danger');
                        $('#card_status_badge'+data.card.id).addClass('badge-success');
                        $('#card_status_badge'+data.card.id).html('Complete');
                    }

                    if($('#c' + data.card.id + 't').is(':visible')){
                        $('#c' + data.card.id + 't').toggleClass('text-danger');
                        // $('#c' + data.card.id + 't').find('i').toggleClass('fa-times');
                        $('#c' + data.card.id + 't').toggleClass('text-success');
                        // $('#c' + data.card.id + 't').find('i').toggleClass('fa-check');
                        $('#c' + data.card.id + '-completed_date').html(data.completed_date);
                    }
                    toastr.success('<strong>Success!</strong> Task was updated successfully.');

                    toastr.options.timeOut = 1000;

                }
            });
        }

        function toggleTaskStatus(id,status) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: '/reports/my-work-day/task/update_status/' + id,
                data: {currentstatus:status},
                success: function( data ) {
                    let cd = data.completed_date;

                    var today = new Date();
                    var dueDate = new Date(data.task.due_date);
                    console.log(today);
                    console.log(dueDate);

                    // if(today > dueDate && data.task.status_id === 0){
                    //     if(data.task.parent_id > 0) {
                    //         $('#st' + data.task.id).addClass('overdue');
                    //         $('#st' + data.task.id).removeClass('completed');
                    //     } else {
                    //         $('#t' + data.task.id).addClass('overdue');
                    //         $('#t' + data.task.id).removeClass('completed');
                    //     }
                    // }

                    // if(data.task.status_id === 1){
                    //     if(data.task.parent_id > 0) {
                    //         $('#st' + data.task.id).addClass('completed');
                    //         $('#st' + data.task.id).removeClass('overdue');
                    //     } else {
                    //         $('#t' + data.task.id).addClass('completed');
                    //         $('#t' + data.task.id).removeClass('overdue');
                    //     }
                    // }

                    if(today > dueDate && data.task.status_id === 0){
                            if(data.task.parent_id > 0) {
                                $('#st'+data.task.id).addClass('overdue');
                                $('#st'+data.task.id).removeClass('completed');
                                $('#sub_task_status_badge'+data.task.id).removeClass('badge-success');
                                $('#sub_task_status_badge'+data.task.id).addClass('badge-danger');
                                $('#sub_task_status_badge'+data.task.id).html('Uncomplete');
                            } else {
                                $('#t'+data.task.id).addClass('overdue');
                                $('#t'+data.task.id).removeClass('completed');
                                $('#task_status_badge'+data.task.id).removeClass('badge-success');
                                $('#task_status_badge'+data.task.id).addClass('badge-danger');
                                $('#task_status_badge'+data.task.id).html('Uncomplete');
                            }
                    }

                    if(data.task.status_id === 1){
                        if(data.task.parent_id > 0) {
                                $('#st'+data.task.id).addClass('overdue');
                                $('#st'+data.task.id).removeClass('completed');
                                $('#sub_task_status_badge'+data.task.id).removeClass('badge-danger');
                                $('#sub_task_status_badge'+data.task.id).addClass('badge-success');
                                $('#sub_task_status_badge'+data.task.id).html('Complete');
                            } else {
                                $('#t'+data.task.id).addClass('overdue');
                                $('#t'+data.task.id).removeClass('completed');
                                $('#task_status_badge'+data.task.id).removeClass('badge-danger');
                                $('#task_status_badge'+data.task.id).addClass('badge-success');
                                $('#task_status_badge'+data.task.id).html('Complete');
                            }
                    }

                    // if($('#c' + data.task.id + 't').is(':visible')){
                    //     $('#c' + data.task.id + 't').toggleClass('text-danger');
                    //     // $('#c' + data.card.id + 't').find('i').toggleClass('fa-times');
                    //     $('#c' + data.task.id + 't').toggleClass('text-success');
                    //     // $('#c' + data.card.id + 't').find('i').toggleClass('fa-check');
                    //     $('#c' + data.task.id + '-completed_date').html(data.completed_date);
                    // }

                    if($('#t' + data.task.id + 't').is(':visible')){
                        $('#t' + data.task.id + 't').toggleClass('text-danger');
                        $('#t' + data.task.id + 't').toggleClass('text-success');
                        $('#t' + data.task.id + '-completed_date').html(cd);
                    }

                    if($('#st' + data.task.id + 't').is(':visible')){
                        $('#st' + data.task.id + 't').toggleClass('text-danger');
                        $('#st' + data.task.id + 't').toggleClass('text-success');
                        $('#st' + data.task.id + '-completed_date').html(data.completed_date);
                    }
                    toastr.success('<strong>Success!</strong> Task was updated successfully.');

                    toastr.options.timeOut = 1000;

                }
            });
        }
    </script>
@endsection