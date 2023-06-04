<div class="modal fade" id="modalChangeProcess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 500px;">
        <div class="modal-content">
            <div class="modal-header text-center" style="border-bottom: 0px;padding:.5rem;">
                <h5 class="modal-title">Start New Application</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mx-3">
                <div class="row">
                    <div class="md-form col-sm-12 text-left">
                        <input type="hidden" class="client_id" />
                        <input type="hidden" class="process_id" />
                        <select name="process" class=" chosen-select form-control form-control-sm {{($errors->has('process') ? ' is-invalid' : '')}}" id="move_to_process_new">

                        </select>
                        <div id="move_to_process_new_msg" class="is-invalid"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="md-form col-sm-12 text-right btn-div p-0">
                    <button class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>&nbsp;
                    <button class="btn btn-success" id="changeprocesssave">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCurrentProcesses" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 500px;">
        <div class="modal-content">
            <div class="modal-header text-center" style="border-bottom: 0px;padding:.5rem;">
                <h5 class="modal-title">Current Applications</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mx-3">
                <div class="row">
                    <div class="md-form col-sm-12 text-left">
                        <ul id="current_processes" style="padding: 0px 1rem;margin:0px">

                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="md-form col-sm-12 text-right btn-div p-0">
                    <button class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalClosedProcesses" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 500px;">
        <div class="modal-content">
            <div class="modal-header text-center" style="border-bottom: 0px;padding:.5rem;">
                <h5 class="modal-title">Closed Applications</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mx-3">
                <div class="row">
                    <div class="md-form col-sm-12 text-left">
                        <ul id="closed_processes" style="padding: 0px 1rem;margin:0px">

                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="md-form col-sm-12 text-right btn-div p-0">
                    <button class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAllProcesses" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 700px;">
        <div class="modal-content">
            <div class="modal-header text-center" style="border-bottom: 0px;padding:.5rem;">
                <h5 class="modal-title">Submit for Signatures</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mx-3">
                <input type="hidden" id="all_processes_process_id" name="all_processes_process_id">
                <input type="hidden" id="all_processes_step_id" name="all_processes_step_id">
                <div class="row">
                    <div class="md-form col-sm-12 text-left">
                        <p class="instruction"></p>
                        <table id="all_processes">

                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="md-form col-sm-12 text-right btn-div p-0">
                    <button class="btn btn-outline-primary" data-dismiss="modal">Close</button>&nbsp;
                    <a href="javascript:void(0)" class="btn btn-success" id="getApplicationDoc">Submit</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSendMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 618px;max-width: 618px;">
        <div class="modal-content">
            <div class="modal-header text-center" style="border-bottom: 0px;padding:32px 32px 0px 32px;">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('messages.store') }}" method="post" class="form-inline" id="send_message_form">
                    {{ csrf_field() }}
                    <div class="col-md-12">
                        <div class="form-group">
                            @if($message_users->count() > 0)
                                <select name="recipients" data-placeholder="Add recipients" class="form-control form-control-sm select2 chosen-select" multiple>
                                    @foreach($message_users as $user)

                                        <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>

                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <!-- Subject Form Input -->
                        <div class="form-group" id="message_subject">
                            <input type="text" class="form-control form-control-sm" name="subject" placeholder="Subject" value="{{ old('subject') }}">
                        </div>

                        <!-- Message Form Input -->
                        <div class="form-group">
                            <textarea name="message" rows="10" id ="message" class="my-editor form-control form-control-sm">@if(Session::has('page_url')) Hi<br /><br />please have a look at <a href="{{Session::get('page_url')}}">{{Session::get('page_url')}}</a>. @endif</textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>&nbsp;
                <button class="btn btn-success" onclick="sendMessage()">Send Message</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSendMail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 618px;max-width: 618px;">
        <div class="modal-content">
            <div class="modal-header text-center" style="border-bottom: 0px;padding:32px 32px 0px 32px;">
                <h5 class="modal-title">Compose Mail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if (isset($client->id))
                <form action="{{ route('clients.sendMail', $client->id )}}" method="post" class="form-inline" id="send_mail_form">
                    {{ csrf_field() }}
                    <div class="col-md-12">
                        <div class="form-group">
                                <select disabled name="recipient" data-placeholder="Recipient" class="form-control form-control-sm select2 chosen-select">
                                        <option selected  value="{{$client->email}}">{{$client->first_name}} {{$client->last_name}}:  {{$client->email}}</option>
                                </select>
                        </div>
                        <!-- Subject Form Input -->
                        <div class="form-group" id="msubject">
                            <input type="text" class="form-control form-control-sm" name="mail_subject" placeholder="Subject" value="">
                        </div>

                        <!-- Message Form Input -->
                        <div class="form-group">
                            <textarea name="mail_message" rows="10" id ="mail_message" class="my-editor form-control form-control-sm"></textarea>
                        </div>
                    </div>
                </form>
                @else
                    
                @endif
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>&nbsp;
                <button class="btn btn-success" onclick="sendMail()">Send Mail</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSendWhatsapp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 618px;max-width: 618px;">
        <div class="modal-content">
            <div class="modal-header text-center" style="border-bottom: 0px;padding:32px 32px 0px 32px;">
                <h5 class="modal-title">Compose Whatsapp Message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pb-0 mb-0">
                @if (isset($client->id))
                    <form action="{{ route('clients.whatsappMessage') }}" method="post" class="form-inline" class="send_whatsapp_form">
                        {{ csrf_field() }}
                        <input type="hidden" class="client_id" value="{{$client->id}}">
                        <div class="col-md-12">
                            <div class="form-group">
                                <select disabled id="recipient" name="recipient" data-placeholder="Recipient" class="form-control form-control-sm select2 chosen-select recipient">
                                    <option selected value="{{$client->contact}}">{{$client->first_name}} {{$client->last_name}}: {{(substr($client->contact,0,1) == '0' ? '27'.substr($client->contact,1) : $client->contact )}}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <select name="template" placeholder="Recipient" class="form-control form-control-sm select2 chosen-select template" onchange="getWhatsappTemplate()">
                                    @foreach($whatsapp_templates as $key => $value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Message Form Input -->
                            <div class="form-group">
                                <textarea name="whatsapp_message" rows="10" class="whatsapp_message" class="form-control form-control-sm" style="width: 100%;"></textarea>
                            </div>
                        </div>
                    </form>
                @else
                    <input type="hidden" class="client_id">
                    <div class="col-md-12 pb-0">
                        <div class="col-md-12 p-0">
                            <select disabled name="recipient" placeholder="Recipient" class="form-control form-control-sm select2 chosen-select recipient">

                            </select>
                        </div>

                        <div class="col-md-12 p-0">
                            <select name="template" placeholder="Recipient" class="form-control form-control-sm select2 chosen-select template" onchange="getWhatsappTemplate()">
                                @foreach($whatsapp_templates as $key => $value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Message Form Input -->
                        <div class="col-md-12 p-0">
                            <textarea name="whatsapp_message" rows="10" class="whatsapp_message"  class="form-control form-control-sm" style="width: 100%;"></textarea>
                        </div>
                    </div>
                @endif
                {{-- <form method="post" action="/message">
                    {{ csrf_field() }}
                    <label for="number">Where to send? <input name="number" id="number" type="text" size="20" /></label>
                    <label for="number">Message <textarea name="message" id="message" type="text"></textarea></label>
                    <input type="submit" />
                </form> --}}
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>&nbsp;
                <button class="btn btn-success" onclick="sendWhatsapp()">Send Whatsapp</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBillboardMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 618px;max-width: 618px;">
        <div class="modal-content">
            <div class="modal-header text-center" style="border-bottom: 0px;padding:32px 32px 0px 32px;">
                <h5 class="modal-title">Add a @if(in_array(76,$user_offices)) Priority Clients for the Week @else Billboard Message @endif</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pb-0 mb-0">

                <div class="col-md-12 pb-0">
                    <!-- Message Form Input -->
                    <div class="col-md-12 p-0">
                        <select name="client" placeholder="Client" class="form-control form-control-sm select2 chosen-select billboard_client">
                            @foreach($client_list as $key => $value)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Message Form Input -->
                    <div class="col-md-12 p-0">
                        <textarea name="billboard_message" rows="10" class="billboard_message"  class="form-control form-control-sm" style="width: 100%;"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>&nbsp;
                <button class="btn btn-success" onclick="saveBillboardMessage()">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditBillboardMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 618px;max-width: 618px;">
        <div class="modal-content">
            <div class="modal-header text-center" style="border-bottom: 0px;padding:32px 32px 0px 32px;">
                <h5 class="modal-title">Edit a @if(in_array(76,$user_offices)) Priority Clients for the Week @else Billboard Message @endif</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pb-0 mb-0">

                <div class="col-md-12 pb-0">
                    <input type="hidden" class="message_id">
                    <!-- Message Form Input -->
                    <div class="col-md-12 p-0">
                        <select name="client" placeholder="Client" class="form-control form-control-sm select2 chosen-select billboard_client">
                            @foreach($client_list as $key => $value)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Message Form Input -->
                    <div class="col-md-12 p-0">
                        <textarea name="billboard_message" rows="10" class="billboard_message"  class="form-control form-control-sm" style="width: 100%;"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>&nbsp;
                <button class="btn btn-success" onclick="updateBillboardMessage()">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalShowBillboardMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 618px;max-width: 618px;">
        <div class="modal-content">
            <div class="modal-header text-center" style="border-bottom: 0px;padding:32px 32px 0px 32px;">
                <h5 class="modal-title">Add a @if(in_array(76,$user_offices)) Priority Client for the Week @else Billboard Message @endif</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pb-0 mb-0">

                <div class="col-md-12 pb-0">
                    <input type="hidden" class="message_id">
                    <!-- Message Form Input -->
                    <div class="col-md-12 p-0 billboard_client">
                    </div>
                    <!-- Message Form Input -->
                    <div class="col-md-12 p-0 pt-1 billboard_message">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>&nbsp;
                <button class="btn btn-success" onclick="editBillboardMessage()">Edit</button>
                <button class="btn btn-danger" onclick="deleteBillboardMessage()">Delete</button>
            </div>
        </div>
    </div>
</div>