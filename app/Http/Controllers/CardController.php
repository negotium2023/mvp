<?php

namespace App\Http\Controllers;

use App\Board;
use App\Card;
use App\Client;
use App\Document;
use App\OfficeUser;
use App\PriorityStatus;
use App\Section;
use App\Status;
use App\Task;
use App\User;
use App\CustomCard;
use App\CardSection;
use App\CardSectionInputs;
use App\CardInputText;
use App\CardInputTextData;
use App\CardInputAmount;
use App\CardInputAmountData;
use App\CardInputBoolean;
use App\CardInputBooleanData;
use App\CardInputDate;
use App\CardInputDateData;
use App\CardInputHeading;
use App\CardInputTextarea;
use App\CardInputTextareaData;
use App\CardInputDocument;
use App\CardInputDocumentData;
use App\CardInputDropdown;
use App\CardInputDropdownData;
use App\CardInputDropdownItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class CardController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //$team_names = implode(', ', $request->card_form["team_names"] ?? []);

        if((isset($request->card_form["saved"]) && $request->card_form["saved"] != 0) && (isset($request->saved) && $request->saved != 0)){

            $assignee_id = User::select('id', DB::raw('CONCAT(first_name," ", last_name) AS full_name'))->where(DB::raw('CONCAT(first_name," ", last_name)'), $request->card_form["assignee_name"])->first()->id;
            $client_id = ($request->client_name != '' ? Client::select('id', DB::raw('CONCAT(first_name," ", last_name) AS full_name'))->where(DB::raw('CONCAT(first_name," ", last_name)'), $request->client_name)->first()->id : null);
            $card = Card::find($request->card_form["saved"]);
            $card->name = $request->card_name;
            $card->insurer = (isset($request->card_form["insurer"]) ? $request->card_form["insurer"] : '');
            $card->policy = (isset($request->card_form["policy"]) ? $request->card_form["policy"] : '');
            $card->upfront_revenue = (isset($request->card_form["upfront_revenue"]) ? $request->card_form["upfront_revenue"] : '');
            $card->ongoing_revenue = (isset($request->card_form["ongoing_revenue"]) ? $request->card_form["ongoing_revenue"] : '');
            $card->dependency_id = (isset($request->card_form["dependency_id"]) ? $request->card_form["dependency_id"] : null);
            $card->due_date = (isset($request->card_form["selected_deadline"]) ? Carbon::parse($request->card_form["selected_deadline"])->toDateString() : Carbon::parse($request->card_form["due_date"])->toDateString());
            $card->assignee_id = $assignee_id;
            $card->assignee_name = $request->card_form["assignee_name"];
            $card->team_names = (is_array($request->card_form["team_names"]) ? implode(', ', $request->card_form["team_names"]) : $request->card_form["team_names"]);
            $card->status_id = $request->card_form["progress_status_id"] ?? 1;
            $card->priority_id = $request->priority_id ?? (isset($request->card_form["priority_status_id"]) ? $request->card_form["priority_status_id"] : 1);
            $card->section_id = $request->section["section_id"];
            $card->summary_description = (isset($request->card_form["summary_description"]) ? $request->card_form["summary_description"] : '');
            $card->description = (isset($request->card_form["description"]) ? $request->card_form["description"] : '');
            $card->description2 = (isset($request->card_form["description2"]) ? $request->card_form["description2"] : '');
            $card->client_id = $client_id;
            $card->client_name = $request->client_name;
            $card->creator_id = Auth::id();
            $card->enabled = 1;
            $card->card_section_id = $request->card_section_id;
            $card->board_id = $request->board_id;
            $card->save();

            if (!empty($request->task)) {
                foreach ($request->task as $task) {
                    $assignee_id2 = User::select('id', DB::raw('CONCAT(first_name," ", last_name) AS full_name'))->where(DB::raw('CONCAT(first_name," ", last_name)'), (isset($task["assignee_name"]) && $task["assignee_name"] != '' ? $task["assignee_name"] : $request->card_form["assignee_name"]))->first()->id;
                    Task::where('card_id',$request->card_form["saved"])->forceDelete();
                    $tasks = (isset($task["id"]) ? Task::find($task["id"]) : new Task());
                    $tasks->name = $task["name"];
                    $tasks->assignee_name = (isset($task["assignee_name"]) && $task["assignee_name"] != '' ? $task["assignee_name"] : $request->card_form["assignee_name"]);
                    $tasks->assignee_id = $assignee_id2;
                    $tasks->due_date = (isset($task["due_date"]) ? Carbon::parse($task["due_date"])->toDateString() : (isset($task["date"]) ? Carbon::parse($task["date"])->toDateString() : (isset($request->card_form["deadline"]) ? Carbon::parse($request->card_form["deadline"])->toDateString() : Carbon::parse($request->card_form["due_date"])->toDateString())));
                    $tasks->parent_id = null;
                    $tasks->creator_id = auth()->id();
                    $tasks->card_id = $card->id;
                    $tasks->status_id = 1;
                    $tasks->save();

                    if (!empty($task["subtasks"])) {
                        foreach ($task["subtasks"] as $sub_task_name) {
                            if ($sub_task_name["name"] != "") {
                                $assignee_id2 = User::select('id', DB::raw('CONCAT(first_name," ", last_name) AS full_name'))->where(DB::raw('CONCAT(first_name," ", last_name)'), (isset($task["assignee_name"]) && $task["assignee_name"] != '' ? $task["assignee_name"] : $request->card_form["assignee_name"]))->first()->id;
                                $sub_task = (isset($sub_task_name["id"]) ? Task::find($sub_task_name["id"]) : new Task());
                                $sub_task->name = $sub_task_name["name"];
                                $sub_task->assignee_id = $assignee_id2;
                                $sub_task->assignee_name = (isset($task["assignee_name"]) && $task["assignee_name"] != '' ? $task["assignee_name"] : $request->card_form["assignee_name"]);
                                $sub_task->due_date = (isset($sub_task_name["due_date"]) ? Carbon::parse($sub_task_name["due_date"])->toDateString() : (isset($sub_task_name["date"]) ? Carbon::parse($sub_task_name["date"])->toDateString() : (isset($request->card_form["deadline"]) ? Carbon::parse($request->card_form["deadline"])->toDateString() : Carbon::parse($request->card_form["due_date"])->toDateString())));
                                $sub_task->parent_id = $tasks->id;
                                $sub_task->creator_id = Auth::id();
                                $sub_task->card_id = $card->id;
                                $sub_task->status_id = 1;
                                $sub_task->save();
                            }
                        }
                    }

                    if (!empty($task["sub_tasks"])) {
                        foreach ($task["sub_tasks"] as $sub_task_name) {
                            $assignee_id2 = User::select('id', DB::raw('CONCAT(first_name," ", last_name) AS full_name'))->where(DB::raw('CONCAT(first_name," ", last_name)'), $tasks["assignee_name"])->first()->id;
                            $sub_task = (isset($sub_task_name["id"]) ? Task::find($sub_task_name["id"]) : new Task());
                            $sub_task->name = $sub_task_name["name"];
                            $sub_task->assignee_id = $assignee_id2;
                            $sub_task->assignee_name = $task["assignee_name"];
                            $sub_task->due_date = (isset($sub_task_name["due_date"]) ? Carbon::parse($sub_task_name["due_date"])->toDateString() : (isset($sub_task_name["date"]) ? Carbon::parse($sub_task_name["date"])->toDateString() : (isset($request->card_form["deadline"]) ? Carbon::parse($request->card_form["deadline"])->toDateString() : Carbon::parse($request->card_form["due_date"])->toDateString())));
                            $sub_task->parent_id = $tasks->id;
                            $sub_task->creator_id = Auth::id();
                            $sub_task->card_id = $card->id;
                            $sub_task->status_id = 1;
                            $sub_task->save();
                        }
                    }
                }
            }

            return response()->json([
                'Card' => $card->load([
                        'discussions',
                        'creator',
                        'tasks.subTasks',
                        'assignedUser',
                        'priorityStatus',
                        'status',
                        'document',
                        'recordings']
                ),
                'card_id' => $card->id
            ]);
        } else {

            $assignee_id = User::select('id', DB::raw('CONCAT(first_name," ", last_name) AS full_name'))->where(DB::raw('CONCAT(first_name," ", last_name)'), $request->card_form["assignee_name"])->first()->id;
            $client_id = ($request->client_name != '' ? Client::select('id', DB::raw('CONCAT(first_name," ", last_name) AS full_name'))->where(DB::raw('CONCAT(first_name," ", last_name)'), $request->client_name)->first()->id : null);
            $card = (isset($request->section["id"]) ? Card::find($request->section["id"]) : (isset($request->card_form["saved"]) && $request->card_form["saved"] > 0 ? Card::find($request->card_form["saved"]) : new Card()));
            $card->name = $request->card_name;
            $card->insurer = (isset($request->card_form["insurer"]) ? $request->card_form["insurer"] : '');
            $card->policy = (isset($request->card_form["policy"]) ? $request->card_form["policy"] : '');
            $card->upfront_revenue = (isset($request->card_form["upfront_revenue"]) ? $request->card_form["upfront_revenue"] : '');
            $card->ongoing_revenue = (isset($request->card_form["ongoing_revenue"]) ? $request->card_form["ongoing_revenue"] : '');
            $card->dependency_id = (isset($request->card_form["dependency_id"]) ? $request->card_form["dependency_id"] : null);
            $card->due_date = (isset($request->card_form["selected_deadline"]) ? Carbon::parse($request->card_form["selected_deadline"])->toDateString() : Carbon::parse($request->card_form["due_date"])->toDateString());
            $card->assignee_id = $assignee_id;
            $card->assignee_name = $request->card_form["assignee_name"];
            $card->team_names = (is_array($request->card_form["team_names"]) ? implode(', ', $request->card_form["team_names"]) : $request->card_form["team_names"]);
            $card->status_id = $request->card_form["progress_status_id"] ?? 1;
            $card->priority_id = $request->priority_id ?? (isset($request->card_form["priority_status_id"]) ? $request->card_form["priority_status_id"] : 1);
            $card->section_id = $request->section["section_id"];
            $card->summary_description = (isset($request->card_form["summary_description"]) ? $request->card_form["summary_description"] : '');
            $card->description = (isset($request->card_form["description"]) ? $request->card_form["description"] : '');
            $card->description2 = (isset($request->card_form["description2"]) ? $request->card_form["description2"] : '');
            $card->client_id = $client_id;
            $card->client_name = $request->client_name;
            $card->creator_id = Auth::id();
            if($request->has('saved') && $request->input('saved') != 0) {
                $card->enabled = 1;
            }
            $card->card_section_id = $request->card_section_id;
            $card->board_id = $request->board_id;
            $card->save();

            $id = $card->id;

            if (!empty($request->task)) {
                foreach ($request->task as $task) {
                    $assignee_id2 = User::select('id', DB::raw('CONCAT(first_name," ", last_name) AS full_name'))->where(DB::raw('CONCAT(first_name," ", last_name)'), (isset($task["assignee_name"]) && $task["assignee_name"] != '' ? $task["assignee_name"] : $request->card_form["assignee_name"]))->first()->id;
                    $tasks = (isset($task["id"]) ? Task::find($task["id"]) : new Task());
                    $tasks->name = $task["name"];
                    $tasks->assignee_name = (isset($task["assignee_name"]) && $task["assignee_name"] != '' ? $task["assignee_name"] : $request->card_form["assignee_name"]);
                    $tasks->assignee_id = $assignee_id2;
                    $tasks->due_date = (isset($task["due_date"]) ? Carbon::parse($task["due_date"])->toDateString() : (isset($task["date"]) ? Carbon::parse($task["date"])->toDateString() : (isset($request->card_form["deadline"]) ? Carbon::parse($request->card_form["deadline"])->toDateString() : Carbon::parse($request->card_form["due_date"])->toDateString())));
                    $tasks->parent_id = null;
                    $tasks->creator_id = auth()->id();
                    $tasks->card_id = $card->id;
                    $tasks->status_id = 1;
                    $tasks->save();

                    if (!empty($task["subtasks"])) {
                        foreach ($task["subtasks"] as $sub_task_name) {
                            if ($sub_task_name["name"] != "") {
                                $assignee_id2 = User::select('id', DB::raw('CONCAT(first_name," ", last_name) AS full_name'))->where(DB::raw('CONCAT(first_name," ", last_name)'), (isset($task["assignee_name"]) && $task["assignee_name"] != '' ? $task["assignee_name"] : $request->card_form["assignee_name"]))->first()->id;
                                $sub_task = (isset($sub_task_name["id"]) ? Task::find($sub_task_name["id"]) : new Task());
                                $sub_task->name = $sub_task_name["name"];
                                $sub_task->assignee_id = $assignee_id2;
                                $sub_task->assignee_name = (isset($task["assignee_name"]) && $task["assignee_name"] != '' ? $task["assignee_name"] : $request->card_form["assignee_name"]);
                                $sub_task->due_date = (isset($sub_task_name["due_date"]) ? Carbon::parse($sub_task_name["due_date"])->toDateString() : (isset($sub_task_name["date"]) ? Carbon::parse($sub_task_name["date"])->toDateString() : (isset($request->card_form["deadline"]) ? Carbon::parse($request->card_form["deadline"])->toDateString() : Carbon::parse($request->card_form["due_date"])->toDateString())));
                                $sub_task->parent_id = $tasks->id;
                                $sub_task->creator_id = Auth::id();
                                $sub_task->card_id = $card->id;
                                $sub_task->status_id = 1;
                                $sub_task->save();
                            }
                        }
                    }

                    if (!empty($task["sub_tasks"])) {
                        foreach ($task["sub_tasks"] as $sub_task_name) {
                            $assignee_id2 = User::select('id', DB::raw('CONCAT(first_name," ", last_name) AS full_name'))->where(DB::raw('CONCAT(first_name," ", last_name)'), $tasks["assignee_name"])->first()->id;
                            $sub_task = (isset($sub_task_name["id"]) ? Task::find($sub_task_name["id"]) : new Task());
                            $sub_task->name = $sub_task_name["name"];
                            $sub_task->assignee_id = $assignee_id2;
                            $sub_task->assignee_name = $task["assignee_name"];
                            $sub_task->due_date = (isset($sub_task_name["due_date"]) ? Carbon::parse($sub_task_name["due_date"])->toDateString() : (isset($sub_task_name["date"]) ? Carbon::parse($sub_task_name["date"])->toDateString() : (isset($request->card_form["deadline"]) ? Carbon::parse($request->card_form["deadline"])->toDateString() : Carbon::parse($request->card_form["due_date"])->toDateString())));
                            $sub_task->parent_id = $tasks->id;
                            $sub_task->creator_id = Auth::id();
                            $sub_task->card_id = $card->id;
                            $sub_task->status_id = 1;
                            $sub_task->save();
                        }
                    }
                }
            }


            return response()->json([
                'Card' => $card->load([
                        'discussions',
                        'creator',
                        'tasks.subTasks',
                        'assignedUser',
                        'priorityStatus',
                        'status',
                        'document',
                        'recordings']
                )]);
        }
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, Card $card)
    {
        if ($request->has('name'))
            $card->name = $request->name;

        if ($request->has('assignee_name'))
            $card->assignee_id = User::select('id',DB::raw('CONCAT(first_name," ", last_name) AS full_name'))->where(DB::raw('CONCAT(first_name," ", last_name)'),$request->assignee_name)->first()->id;
        $card->assignee_name = $request->assignee_name;

        if ($request->has('client_name'))
            $card->client_id = Client::select('id',DB::raw('CONCAT(first_name," ", last_name) AS full_name'))->where(DB::raw('CONCAT(first_name," ", last_name)'),$request->client_name)->first()->id;
        $card->client_name = $request->client_name;

        if ($request->has('due_date'))
            $card->due_date = Carbon::parse($request->due_date)->addDay()->toDateString();

        if ($request->has('status_id'))
            $card->status_id = $request->status_id;

        if ($request->has('priority_id'))
            $card->priority_id = $request->priority_id;

        if ($request->has('description'))
            $card->description = $request->description;

        if ($request->has('team_names')) {
            /*$card->team_ids = implode(', ', $request->team_ids);*/
            $card->team_names  = implode(', ', $request->team_names);
        }

        if ($request->has('section_id'))
            $card->section_id = $request->section_id;

        $card->save();
        return response()->json(['card' => $card->load(['assignedUser', 'status', 'priorityStatus'])]);
    }


    public function destroy(Request $request,$card_id)
    {
        Card::destroy($card_id);

        return response()->json(['message'=>"success"]);
    }

    public function archive(Request $request,$card_id)
    {
        $card = Card::find($card_id);
        $card->archived = 1;
        $card->save();

        return response()->json([]);
    }

    public function unarchive(Request $request,$card_id)
    {
        $card = Card::find($card_id);
        $card->archived = 0;
        $card->save();

        return response()->json([]);
    }

    public function getStatuses()
    {
        $priorityStatus = PriorityStatus::get(['id', 'name','fcolor']);
        $progessStatus = Status::get(['id', 'name']);

        return response()->json([
            'priority_status' => $priorityStatus,
            'progress_status' => $progessStatus
        ]);
    }

    public function getOfficeClients(Request $request){

        $offices = array();

        $user_offices = OfficeUser::where('user_id',Auth::id())->get();

        foreach ($user_offices as $user_office){
            array_push($offices,$user_office->office_id);
        }

        $office_clients = Client::whereIn('office_id', $offices)->get(['id']);
        $office_clients = $office_clients->map(function ($client){
            return Client::select(DB::raw('CONCAT(first_name," ", last_name) AS full_name'))->where('id',$client->id)->first()->full_name;
        })->filter();

        $ca = [];

        foreach ($office_clients as $office_client){
            array_push($ca,$office_client);
        }

        return response()->json(['office_clients' => $ca]);
    }

    public function completeTasks(Request $request,$cardid){

        $task = Task::where('card_id',$cardid)->update(['status_id' => '1','completed_date'=>now()]);

        $card = Card::find($cardid);

        return response()->json([
            'Card' => $card->load([
                    'creator',
                    'tasks.subTasks',
                    'assignedUser',
                    'priorityStatus',
                    'status']
            )]);
    }

    public function getCardsDropDown()
    {
        $cards = Card::where('creator_id',Auth::id())->get();

        return response()->json(['cards' => $cards]);
    }

    public function uploadDocument(Request $request){
        $uploadedFile = $request->file('documentFile');

        $filename = $request->card_id.time().'-'.$uploadedFile->getClientOriginalName();

        $store = Storage::disk('public')->putFileAs(
            'pipeline/documents',
            $uploadedFile,
            $filename
        );

        $card = Card::find($request->card_id);
        $card->document = $filename;
        $card->save();

        if($card->client_id != '') {
            $document = new Document();
            $document->name = 'Pipeline: '.$filename;
            $document->file = '/'.$filename;
            $document->user_id = Auth::id();
            $document->client_id = $card->client_id;
            $document->card_id = $card->id;
            $document->save();
        }

        return response()->json(['id'=>$document->id,'name'=>$document->name,'filename'=>$filename]);
    }

    public function deleteDocument($document_id){

        // $card = Card::find($request->card_id);
        // $card->document = null;
        // $card->save();

        $document = Document::where('id', $document_id)->first();
        // dd($document);
        $document->delete();

        return response()->json(['filename'=>'null']);
    }

    public function copyCard(Request $request){
        $s = $request->input('section');

        $section = Section::where('id',$s["id"])->first();

        $card = Card::find($request->input('card'));
        $tasks = Task::where('card_id',$card->id)->get();

        $new_card = new Card();
        $new_card->name = $card->name;
        $new_card->due_date = $card->due_date;
        $new_card->team_ids = $card->team_ids;
        $new_card->assignee_id = $card->assignee_id;
        $new_card->status_id = $card->status_id;
        $new_card->priority_id = $card->priority_id;
        $new_card->section_id = $section["id"];
        $new_card->description = $card->descriptiion;
        $new_card->description2 = $card->descriptiion2;
        $new_card->summary_description = $card->summary_description;
        $new_card->assignee_name = $card->assignee_name;
        $new_card->team_names = $card->team_names;
        $new_card->client_id = $card->client_id;
        $new_card->client_name = $card->client_name;
        $new_card->archived = $card->archived;
        $new_card->creator_id = Auth::id();
        $new_card->insurer = $card->insurer;
        $new_card->policy = $card->policy;
        $new_card->upfront_revenue = $card->upfront_revenue;
        $new_card->ongoing_revenue = $card->ongoing_revenue;
        $new_card->dependency_id = $card->dependency_id;
        $new_card->enabled = $card->enabled;
        $new_card->save();

        if(count($tasks) > 0){
            foreach ($tasks  as $task){
                $new_task = new Task();
                $new_task->name = $task->name;
                $new_task->due_date = $task->due_date;
                $new_task->parent_id = $task->parent_id;
                $new_task->creator_id = Auth::id();
                $new_task->status_id = $task->status_id;
                $new_task->assignee_id = $task->assignee_id;
                $new_task->card_id = $new_card->id;
                $new_task->assignee_name = $task->assignee_name;
                $new_task->deadline_param_id = $task->deadline_param_id;
                $new_task->allowed_days = $task->allowed_days;
                $new_task->deadline_type = $task->deadline_type;
                $new_task->save();
            }
        }

        return response()->json([
            'board_id' => $section["board_id"],
            'section_id' => $section["id"],
            'card' => $new_card->load([
                    'creator',
                    'tasks.subTasks',
                    'assignedUser',
                    'priorityStatus',
                    'status',
                    'document',
                    'recordings']
            )]);
    }

    public function moveCard(Request $request){
        $b = $request->input('board');
        $s = $request->input('section');

        $card = Card::find($request->input('card'));
        $card->section_id = $s["id"];
        $card->save();

        return response()->json([
            'board_id' => (string)$b["id"],
            'section_id' => $s["id"],
            'card' => $card->load([
                    'creator',
                    'tasks.subTasks',
                    'assignedUser',
                    'priorityStatus',
                    'status',
                    'document',
                    'recordings']
            )]);
    }

    public function updateWorkDayStatus(Request $request,$card_id){
        $card = Card::find($card_id);
        $card->complete = ($card->complete == 0 ? 1 : 0);
        $card->completed_date = ($card->complete == 0 ? null : now());

        $board = Section::where('id',$card->section_id)->first();
        $section = Section::where('completed',1)->where('board_id',$board->board_id)->first();

        if($section) {
            $card->section_id = $section->id;
        }
        $card->save();

        $cd = ($card->completed_date != null ? Carbon::parse($card->completed_date)->format('Y-m-d') : '');

        return response()->json(['card' => $card,'completed_date'=>$cd]);
    }

    public function getCardDocuments($card_id){
        $card_documents = Document::where('card_id', $card_id)->get();

        return response()->json(['card_documents' => $card_documents]);
    }

    public function cardList(){

        $cardfields = CardSection::with('card_section_input.input.data')->where('card_id',2)->get();

        $parameters = [
            'card' => CustomCard::find(2),
            'card_sections' => CardSection::where('card_id',2)->orderBy('updated_at')->get(),
            'cardfields' => $cardfields
        ];

        // dd(CardSection::where('card_id',2)->orderBy('updated_at')->get());

        return view('workflows.list')->with($parameters);
    }

    public function cardCreate(CustomCard $card){
        $all_columns = Schema::getColumnListing('clients');
        asort($all_columns);
        $exclude_columns = [
            '',
            'id',
            'referrer_id',
            'introducer_id',
            'office_id',
            'process_id',
            'step_id',
            'is_progressing',
            'not_progressing_date',
            'needs_approval',
            'cif_code',
            'business_unit_id',
            'case_number',
            'is_qa',
            'qa_start_date',
            'qa_end_date',
            'qa_consultant',
            'hash_first_name',
            'hash_last_name',
            'hash_company',
            'hash_email',
            'hash_contact',
            'hash_id_number',
            'hash_cif_code',
            'hash_company_registration_number',
            'consultant_id',
            'committee_id',
            'project_id',
            'trigger_type_id',
            'instruction_date',
            'assigned_date',
            'viewed',
            'completed',
            'completed_date',
            'completed_by',
            'out_of_scope',
            'work_item_qa',
            'work_item_qa_date',
            'crm_id',
            'parent_id',
            'deleted_at'
        ];
        $get_columns = array_diff($all_columns, $exclude_columns);

        return view('workflows.create')->with(['cards' => $card->load('sections'),'fields' => json_encode($get_columns)]);
    }

    public function cardStore(Request $request,CustomCard $card){
        //dd($request);
        $section = new CardSection();
        $section->name = $request->input('name');
        $section->order = 0;
        // $section->show_name_in_tabs = $request->input('show_name_in_tab');
        // $section->group = $request->input('group_section');
        $section->card_id = 2;
        // $section->order = FormSection::where('form_id', $form->id)->max('order') + 1;
        $section->save();

        //loop over each activity input
        foreach ($request->input('inputs') as $input_key => $input_input) {
            $input = new CardSectionInputs();
            $form_input = $this->createInput($input_input['type']);

            $input->name = $input_input['name'];
            $input->order = $input_key + 1;
            $input->input_id = $form_input->id;
            $input->input_type = $this->getInputType($input_input['type']);
            $input->card_section_id = $section->id;
            // $input->kpi = (isset($input_input['kpi']) && $input_input['kpi'] == "on") ? 1 : null;
            // $input->email = (isset($input_input['email']) && $input_input['email'] == "on") ? 1 : 0;
            // $input->multiple_selection = (isset($activity_input['multiple_selection']) && $activity_input['multiple_selection'] == "on") ? 1 : 0;
            // $input->future_date = (isset($activity_input['future_date']) && $activity_input['future_date'] == "on") ? 1 : 0;
            // if ($input_input['type'] == 'heading' || $input_input['type'] == 'subheading') {
            //     $input->client_bucket = 1;
            // } else {
            //     $input->client_bucket = (isset($input_input['client_bucket']) && $input_input['client_bucket'] == "on") ? 1 : 0;
            // }
            // $input->level = (isset($input_input['level']) ? $input_input['level'] : 0);
            // $input->color = (isset($input_input['color']) && $input_input['color'] != '#hsla(0,0%,0%,0)' ? $input_input['color'] : null);
            // $input->grouped = (isset($input_input['grouping']) && $input_input['grouping'] == "on") ? 1 : 0;
            // $input->grouping = (isset($input_input['grouping_value'])) ? $input_input['grouping_value'] : 0;
            $input->save();

            if ($input_input['type'] == 'dropdown') {

                //only add dropdown items if there is input
                if (isset($input_input['dropdown_items'])) {
                    //dd($input_input['dropdown_items']);
                    //loop over each dropdown item
                    foreach ($input_input['dropdown_items'] as $dropdown_item) {
                        $card_dropdown_item = new CardInputDropdownItem;
                        $card_dropdown_item->card_input_dropdown_id = $form_input->id;
                        $card_dropdown_item->name = $dropdown_item;
                        $card_dropdown_item->save();
                    }
                }
            }
            
        }

        return redirect(route('card.list'))->with('flash_success', 'Card successfully saved.');
    }

    public function cardEdit($cardid){

        $card = CardSection::find($cardid);

        $section_inputs_array = [];
        foreach ($card->card_section_inputs as $inputs) {
            // dd($inputs->getCardTypeName());

            $section_input_array = [
                'id' => $inputs->id,
                'step_id' => $inputs->card_section_id,
                'name' => $inputs->name,
                'type' => $inputs->getCardTypeName(),
            ];
            
            array_push($section_inputs_array, $section_input_array);
        }
        // dd($section_inputs_array);


        $all_columns = Schema::getColumnListing('clients');
        asort($all_columns);
        $exclude_columns = [
            '',
            'id',
            'referrer_id',
            'introducer_id',
            'office_id',
            'process_id',
            'step_id',
            'is_progressing',
            'not_progressing_date',
            'needs_approval',
            'cif_code',
            'business_unit_id',
            'case_number',
            'is_qa',
            'qa_start_date',
            'qa_end_date',
            'qa_consultant',
            'hash_first_name',
            'hash_last_name',
            'hash_company',
            'hash_email',
            'hash_contact',
            'hash_id_number',
            'hash_cif_code',
            'hash_company_registration_number',
            'consultant_id',
            'committee_id',
            'project_id',
            'trigger_type_id',
            'instruction_date',
            'assigned_date',
            'viewed',
            'completed',
            'completed_date',
            'completed_by',
            'out_of_scope',
            'work_item_qa',
            'work_item_qa_date',
            'crm_id',
            'parent_id',
            'deleted_at'
        ];
        $get_columns = array_diff($all_columns, $exclude_columns);

         //dd($section_inputs_array);

        $paramaters = [
            'card' => $card,
            'inputs' => json_encode($section_inputs_array),
            'fields' => json_encode($get_columns),
        ];

        // dd($card);

        return view('workflows.edit')->with($paramaters);
    }

    public function cardUpdate(CardSection $card_section,Request $request){
        //dd($request->input());
        /*$form_section = FormSection::find($form_sections->id);*/
        // $card_section = CardSection::find($card_section_id);
        // dd($card_section);
        $existing_section = CardSection::where('name',$card_section->name)->first();

        if($existing_section != null){
            $section_id = $existing_section->id;

            $card_section->name = $request->input('name');
            // $form_section->show_name_in_tabs = $request->input('show_name_in_tab');
            // $form_section->group = ($request->input('group_section') != null ? $request->input('group_section') : '0');

            $card_section->save();
        }
        //dd($request->input('activities'));
        $pinputs = array();
        if($request->input("inputs") != null) {
            foreach ($request->input("inputs") as $input) {
                //dd($activities);
                array_push($pinputs, $input["id"]);
            }
        }
        CardSectionInputs::where('card_section_id',$card_section->id)->whereNotIn('id',$pinputs)->delete();


        // dd($request->input('inputs'));
        //loop over each activity input
        if($request->input("inputs") != null) {
            foreach ($request->input('inputs') as $activity_key => $activity_input) {

                $activity = $card_section->card_section_inputs()->where('id', $activity_input['id'])->get()->first();
                $activity_type = $card_section->card_section_inputs()->where('id', $activity_input['id'])->where('input_type', $this->getInputType($activity_input['type']))->get()->first();

                //if there is a previous activity matching the name and type, reactivate it else create a new one
                if (!$activity) {
                    $new_activity = true;
                    if (!$activity_type) {
                        $new_activity_type = true;
                        $activity = new CardSectionInputs;
                        $actionable = $this->createInput($activity_input['type']);
                    } else {
                        $new_activity_type = false;
                        $activity->restore();
                        $actionable = $activity->input;
                    }

                } else {
                    $new_activity = false;
                    if (!$activity_type) {
                        $new_activity_type = true;
                        $activity = CardSectionInputs::find($activity_input['id']);
                        $actionable = $this->createInput($activity_input['type']);
                    } else {
                        $new_activity_type = false;
                        $activity->restore();
                        $actionable = $activity->input;
                    }

                }
                // dd($card_section);

                $activity->name = $activity_input['name'];
                $activity->order = $activity_key + 1;
                $activity->input_id = (isset($actionable->id) ? $actionable->id : $actionable);
                $activity->input_type = $this->getInputType($activity_input['type']);
                $activity->card_section_id = $card_section->id;
                // $activity->mapped_field = (isset($activity_input['mapped_field']) ? $activity_input['mapped_field'] : null);
                // $activity->grouped = (isset($activity_input['grouping']) && $activity_input['grouping'] == "on") ? 1 : 0;
                // $activity->grouping = (isset($activity_input['grouping_value'])) ? $activity_input['grouping_value'] : 0;
                // $activity->kpi = (isset($activity_input['kpi']) && $activity_input['kpi'] == "on") ? 1 : null;
                // $activity->email = (isset($activity_input['email']) && $activity_input['email'] == "on") ? 1 : 0;
                // $activity->multiple_selection = (isset($activity_input['multiple_selection']) && $activity_input['multiple_selection'] == "on") ? 1 : 0;
                // $activity->future_date = (isset($activity_input['future_date']) && $activity_input['future_date'] == "on") ? 1 : 0;
                // if ($activity_input['type'] == 'heading' || $activity_input['type'] == 'subheading') {
                //     $activity->client_bucket = 1;
                // } else {
                //     $activity->client_bucket = (isset($activity_input['client_bucket']) && $activity_input['client_bucket'] == "on") ? 1 : 0;
                // }
                // $activity->level = (isset($activity_input['level']) ? $activity_input['level'] : 0);
                // $activity->color = (isset($activity_input['color']) && $activity_input['color'] != '#hsla(0,0%,0%,0)' ? $activity_input['color'] : null);
                $activity->save();

            }
        }
        return redirect(route('card.list'))->with('flash_success', 'Card updated successfully.');
    }

    public function cardDestroy(CardSection $card){
        $card_id = $card->card_id;

        $card->delete();

        return redirect(route('card.list'))->with('flash_success', 'Card successfully deleted.');
    }

    public function getInputType($type)
    {
        //activity type hook
        switch ($type) {
            case 'text':
                return 'App\CardInputText';
                break;
            case 'heading':
                return 'App\CardInputHeading';
                break;
            case 'amount':
                return 'App\CardInputAmount';
                break;
            case 'textarea':
                return 'App\CardInputTextarea';
                break;
            case 'date':
                return 'App\CardInputDate';
                break;
            case 'boolean':
                return 'App\CardInputBoolean';
                break;
            case 'document':
                return 'App\CardInputDocument';
                break;
            case 'dropdown':
                return 'App\CardInputDropdown';
                break;
            default:
                abort(500, 'Error');
                break;
        }
    }

    public function createInput($type)
    {
        //activity type hook
        switch ($type) {
            case 'text':
                return CardInputText::create();
                break;
            case 'heading':
                return CardInputHeading::create();
                break;
            case 'amount':
                return CardInputAmount::create();
                break;
            case 'textarea':
                return CardInputTextarea::create();
                break;
            case 'date':
                return CardInputDate::create();
                break;
            case 'boolean':
                return CardInputBoolean::create();
                break;
            case 'document':
                return CardInputDocument::create();
                break;
            case 'dropdown':
                return CardInputDropdown::create();
                break;
            default:
                abort(500, 'Error');
                break;
        }
    }

    public function getCardTypeDisplayName($input)
    {
        //activity type hook
        switch ($input) {
            case 'App\CardInputText':
                return 'text';
                break;
            case 'App\CardInputAmount':
                return 'amount';
                break;
            case 'App\CardInputTextarea':
                return 'textarea';
                break;
            case 'App\CardInputBoolean':
            case 'App\CardInputDate':
                return 'date';
                break;
            case 'App\CardInputHeading':
                return 'heading';
                break;
            case 'App\CardInputDocument':
                return 'document';
                break;
            case 'App\CardInputDropdown':
                return 'dropdown';
                break;
            default:
                return 'error';
                break;
        }
    }

    public function getCardSections()
    {
        $cards = CardSection::all();

        return response()->json(['cards' => $cards]);
    }

    public function getClients()
    {
        $clients = Client::select('id', 'first_name', 'last_name', 'company')->get();
        // dd($clients);

        return response()->json(['clients' => $clients]);
    }

    public function getDropdownItems()
    {
        $dropdown_items = CardInputDropdownItem::all();
        // dd($clients);

        return response()->json(['dropdown_items' => $dropdown_items]);
    }

    public function getCardInputs($card)
    {
        $card_inputs = CardSectionInputs::where('card_section_id',$card)->orderBy('order','ASC')->get();
        $input_array = [];
        $input_arr = [];

        foreach($card_inputs as $input){
            if($input->input_type == 'App\CardInputText'){
                $input_arr = [
                    'id' => $input->id,
                    'name' => $input->name,
                    'order' => $input->order,
                    'input_id' => $input->input_id,
                    'input_type' => 'text',
                    'card_section_id' => $input->card_section_id,
                ];
            };
            if($input->input_type == 'App\CardInputDate'){
                $input_arr = [
                    'id' => $input->id,
                    'name' => $input->name,
                    'order' => $input->order,
                    'input_id' => $input->input_id,
                    'input_type' => 'date',
                    'card_section_id' => $input->card_section_id,
                ];
            };
            if($input->input_type == 'App\CardInputAmount'){
                $input_arr = [
                    'id' => $input->id,
                    'name' => $input->name,
                    'order' => $input->order,
                    'input_id' => $input->input_id,
                    'input_type' => 'amount',
                    'card_section_id' => $input->card_section_id,
                ];
            };
            if($input->input_type == 'App\CardInputHeading'){
                $input_arr = [
                    'id' => $input->id,
                    'name' => $input->name,
                    'order' => $input->order,
                    'input_id' => $input->input_id,
                    'input_type' => 'heading',
                    'card_section_id' => $input->card_section_id,
                ];
            };
            if($input->input_type == 'App\CardInputBoolean'){
                $input_arr = [
                    'id' => $input->id,
                    'name' => $input->name,
                    'order' => $input->order,
                    'input_id' => $input->input_id,
                    'input_type' => 'boolean',
                    'card_section_id' => $input->card_section_id,
                ];
            };
            if($input->input_type == 'App\CardInputTextarea'){
                $input_arr = [
                    'id' => $input->id,
                    'name' => $input->name,
                    'order' => $input->order,
                    'input_id' => $input->input_id,
                    'input_type' => 'textarea',
                    'card_section_id' => $input->card_section_id,
                ];
            };
            if($input->input_type == 'App\CardInputDocument'){
                $input_arr = [
                    'id' => $input->id,
                    'name' => $input->name,
                    'order' => $input->order,
                    'input_id' => $input->input_id,
                    'input_type' => 'document',
                    'card_section_id' => $input->card_section_id,
                ];
            };
            if($input->input_type == 'App\CardInputDropdown'){
                $input_arr = [
                    'id' => $input->id,
                    'name' => $input->name,
                    'order' => $input->order,
                    'input_id' => $input->input_id,
                    'input_type' => 'dropdown',
                    'card_section_id' => $input->card_section_id,
                ];
            };
            array_push($input_array,$input_arr);
        };
        // dd($input_array);

        return response()->json(['card_inputs' => $input_array]);
    }

    public function getAllCardInputs()
    {
        $card_inputs = CardSectionInputs::orderBy('order','ASC')->get();
        $input_array = [];
        $input_arr = [];
        // dd($card_inputs);

        foreach($card_inputs as $input){
            if($input->input_type == 'App\CardInputText'){
                $input_arr = [
                    'id' => $input->id,
                    'name' => $input->name,
                    'order' => $input->order,
                    'input_id' => $input->input_id,
                    'input_type' => 'text',
                    'card_section_id' => $input->card_section_id,
                ];
            };
            if($input->input_type == 'App\CardInputDate'){
                $input_arr = [
                    'id' => $input->id,
                    'name' => $input->name,
                    'order' => $input->order,
                    'input_id' => $input->input_id,
                    'input_type' => 'date',
                    'card_section_id' => $input->card_section_id,
                ];
            };
            if($input->input_type == 'App\CardInputAmount'){
                $input_arr = [
                    'id' => $input->id,
                    'name' => $input->name,
                    'order' => $input->order,
                    'input_id' => $input->input_id,
                    'input_type' => 'amount',
                    'card_section_id' => $input->card_section_id,
                ];
            };
            if($input->input_type == 'App\CardInputHeading'){
                $input_arr = [
                    'id' => $input->id,
                    'name' => $input->name,
                    'order' => $input->order,
                    'input_id' => $input->input_id,
                    'input_type' => 'heading',
                    'card_section_id' => $input->card_section_id,
                ];
            };
            if($input->input_type == 'App\CardInputBoolean'){
                $input_arr = [
                    'id' => $input->id,
                    'name' => $input->name,
                    'order' => $input->order,
                    'input_id' => $input->input_id,
                    'input_type' => 'boolean',
                    'card_section_id' => $input->card_section_id,
                ];
            };
            if($input->input_type == 'App\CardInputTextarea'){
                $input_arr = [
                    'id' => $input->id,
                    'name' => $input->name,
                    'order' => $input->order,
                    'input_id' => $input->input_id,
                    'input_type' => 'textarea',
                    'card_section_id' => $input->card_section_id,
                ];
            };
            if($input->input_type == 'App\CardInputDocument'){
                $input_arr = [
                    'id' => $input->id,
                    'name' => $input->name,
                    'order' => $input->order,
                    'input_id' => $input->input_id,
                    'input_type' => 'document',
                    'card_section_id' => $input->card_section_id,
                ];
            };
            if($input->input_type == 'App\CardInputDropdown'){
                $input_arr = [
                    'id' => $input->id,
                    'name' => $input->name,
                    'order' => $input->order,
                    'input_id' => $input->input_id,
                    'input_type' => 'dropdown',
                    'card_section_id' => $input->card_section_id,
                ];
            };
            array_push($input_array,$input_arr);
        };
        // dd($input_array);

        return response()->json(['card_inputs' => $input_array]);
    }

    public function getCardInputValues()
    {
        $card_input_values = [];
        $text_inputs = CardInputTextData::all();
        $text_data = [];
        $date_inputs = CardInputDateData::all();
        $date_data = [];
        $boolean_inputs = CardInputBooleanData::all();
        $boolean_data = [];
        $amount_inputs = CardInputAmountData::all();
        $amount_data = [];
        $textarea_inputs = CardInputTextareaData::all();
        $textarea_data = [];
        $document_inputs = CardInputDocumentData::all();
        $document_data = [];
        $dropdown_inputs = CardInputDropdownData::all();
        $dropdown_data = [];
        $dropdown_items = CardInputDropdownItem::all();
        $dropdown_data = [];
        $input_array = [];
        $input_arr = [];

        foreach($text_inputs as $input){
                $input_arr = [
                    'id' => $input->id,
                    'data' => $input->data,
                    'card_input_id' => $input->card_input_text_id,
                    'input_type' => 'text',
                    'card_id' => $input->client_id,
                ];
            array_push($text_data,$input_arr);
        };

        foreach($date_inputs as $input){
            $input_arr = [
                'id' => $input->id,
                'data' => $input->data,
                'card_input_id' => $input->card_input_date_id,
                'input_type' => 'date',
                'card_id' => $input->client_id,
            ];
            array_push($date_data,$input_arr);
        };

        foreach($boolean_inputs as $input){
            $input_arr = [
                'id' => $input->id,
                'data' => $input->data,
                'card_input_id' => $input->card_input_boolean_id,
                'input_type' => 'boolean',
                'card_id' => $input->client_id,
            ];
            array_push($boolean_data,$input_arr);
        };

        foreach($amount_inputs as $input){
            $input_arr = [
                'id' => $input->id,
                'data' => $input->data,
                'card_input_id' => $input->card_input_amount_id,
                'input_type' => 'amount',
                'card_id' => $input->client_id,
            ];
            array_push($amount_data,$input_arr);
        };

        foreach($textarea_inputs as $input){
            $input_arr = [
                'id' => $input->id,
                'data' => $input->data,
                'card_input_id' => $input->card_input_textarea_id,
                'input_type' => 'textarea',
                'card_id' => $input->client_id,
            ];
            array_push($textarea_data,$input_arr);
        };

        foreach($document_inputs as $input){
            $input_arr = [
                'id' => $input->id,
                'data' => $input->document_id,
                'card_input_id' => $input->card_input_document_id,
                'input_type' => 'document',
                'card_id' => $input->client_id,
            ];
            array_push($document_data,$input_arr);
        };

        foreach($dropdown_inputs as $input){
            $input_arr = [
                'id' => $input->id,
                'data' => $input->card_input_dropdown_item_id,
                'card_input_id' => $input->card_input_dropdown_id,
                'input_type' => 'dropdown',
                'card_id' => $input->client_id,
            ];
            array_push($dropdown_data,$input_arr);
        };


        foreach($text_data as $data){
            array_push($input_array,$data);
        };
        foreach($date_data as $data){
            array_push($input_array,$data);
        };
        foreach($boolean_data as $data){
            array_push($input_array,$data);
        };
        foreach($amount_data as $data){
            array_push($input_array,$data);
        };
        foreach($textarea_data as $data){
            array_push($input_array,$data);
        }
        foreach($document_data as $data){
            array_push($input_array,$data);
        }
        foreach($dropdown_data as $data){
            array_push($input_array,$data);
        }
        // dd($input_array);

        return response()->json(['card_input_values' => $input_array]);
    }

    public function storeCardInput(Request $request){

        if($request->input_type == 'text'){
            $data = CardInputTextData::insert([
                'data' => $request->data,
                'card_input_text_id' => $request->card_input_id,
                'client_id' => $request->client_id,
                'user_id' => auth()->id(),
                'duration' => 120,
                'created_at' => now()
            ]);
            // dd($data);
        }
        if($request->input_type == 'textarea'){
            $data = CardInputTextareaData::insert([
                'data' => $request->data,
                'card_input_textarea_id' => $request->card_input_id,
                'client_id' => $request->client_id,
                'user_id' => auth()->id(),
                'duration' => 120,
                'created_at' => now()
            ]);
        }
        if($request->input_type == 'amount'){
            $data = CardInputAmountData::insert([
                'data' => $request->data,
                'card_input_amount_id' => $request->card_input_id,
                'client_id' => $request->client_id,
                'user_id' => auth()->id(),
                'duration' => 120,
                'created_at' => now()
            ]);
        }
        if($request->input_type == 'date'){
            $data = CardInputDateData::insert([
                'data' => $request->data,
                'card_input_date_id' => $request->card_input_id,
                'client_id' => $request->client_id,
                'user_id' => auth()->id(),
                'duration' => 120,
                'created_at' => now()
            ]);
        }
        if($request->input_type == 'boolean'){
            $data = CardInputBooleanData::insert([
                'data' => $request->data,
                'card_input_boolean_id' => $request->card_input_id,
                'client_id' => $request->client_id,
                'user_id' => auth()->id(),
                'duration' => 120,
                'created_at' => now()
            ]);
        }
        if($request->input_type == 'document'){

            $afile = $request->file();
            $name = Carbon::now()->format('Y-m-d')."-".strtotime(Carbon::now()).".".$afile->getClientOriginalExtension();
            $stored = $afile->storeAs('documents', $name);

            $document = new Document;
            $document->name = $name;
            $document->client_id = $request->client_link;
            $document->file = $name;
            $document->user_id = Auth::user()->id;
            $document->save();

            $data = CardInputDocumentData::insert([
                'document_id' => $document->id,
                'card_input_document_id' => $request->card_input_id,
                'client_id' => $request->client_id,
                // 'client_link' => $request->client_link,
                'user_id' => auth()->id(),
                'duration' => 120,
                'created_at' => now()
            ]);
        }
        if ($request->input_type == 'dropdown') {

            
            // foreach ($activity_input['dropdown_items'] as $dropdown_item) {
                $card_input_dropdown_item = new CardInputDropdownData;
                $card_input_dropdown_item->card_input_dropdown_id = $request->card_input_id;
                $card_input_dropdown_item->card_input_dropdown_item_id = $request->data;
                $card_input_dropdown_item->client_id = $request->client_id;
                $card_input_dropdown_item->user_id = Auth::user()->id;
                $card_input_dropdown_item->save();
            // }
        }

        return response()->json([
            'data' => $data
        ]);
    }
}
