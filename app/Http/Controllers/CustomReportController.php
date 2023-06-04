<?php

namespace App\Http\Controllers;

use App\ActionableAmountData;
use App\ActionableBooleanData;
use App\ActionableDateData;
use App\ActionableDocumentData;
use App\ActionableDropdownItem;
use App\ActionableMultipleAttachmentData;
use App\ActionableNotificationData;
use App\ActionableTemplateEmailData;
use App\ActionableTextData;
use App\ActionableDropdownData;
use App\Activity;
use App\ActivityRelatedPartyLink;
use App\Committee;
use App\Config;
use App\Exports\CustomReportExport;
use App\Process;
use App\RelatedPartiesTree;
use App\RelatedParty;
use App\RelatedPartyBooleanData;
use App\RelatedPartyDateData;
use App\RelatedPartyDocumentData;
use App\RelatedPartyDropdownData;
use App\RelatedPartyMultipleAttachment;
use App\RelatedPartyMultipleAttachmentData;
use App\RelatedPartyNotificationData;
use App\RelatedPartyTemplateEmailData;
use App\RelatedPartyTextareaData;
use App\RelatedPartyTextData;
use App\Template;
use App\TriggerType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\CustomReport;
use App\CustomReportColumns;
use Illuminate\Support\Facades\Auth;
use App\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Step;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use App\ActionableTextareaData;
use Maatwebsite\Excel\Excel;

class CustomReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $parameters = [
            'reports' => CustomReport::orderBy('name')->with('user')->get()
        ];
        return view('customreports.index')->with($parameters);
    }

    public function create(){

        $configs = Config::first();

        $parameters = [
            'process' => Process::where('process_type_id',1)->pluck('name','id')
        ];
        return view('customreports.create')->with($parameters);
    }

    public function store(Request $request){
        $creport = new CustomReport();
        $creport->name = $request->input('name');
        $creport->process_id = $request->input('process');
        $creport->user_id = Auth::id();
        $creport->group_report = ($request->input('group_report') != null && $request->input('group_report') == 'on' ? 1 : 0);
        $creport->save();

        $creport_id = $creport->id;

        foreach($request->input('activity') as $key => $value){
            $activity = new CustomReportColumns();
            $activity->custom_report_id = $creport_id;
            $activity->activity_id = $value;
            $activity->save();
        }

        return redirect(route('custom_report.index'))->with('flash_success', 'Custom report created successfully');
    }

    public function edit($custom_report_id){

        $report = CustomReport::where('id',$custom_report_id)->get();

        $parameters = [
            'reports' => $report,
            'process' => Process::where('process_type_id',1)->pluck('name','id')

        ];

        return view('customreports.edit')->with($parameters);
    }

    public function update($custom_report_id, Request $request){

        $creport = CustomReport::find($custom_report_id);
        $creport->name = $request->input('name');
        $creport->process_id = $request->input('process');
        $creport->user_id = Auth::id();
        $creport->group_report = ($request->input('group_report') != null && $request->input('group_report') == 'on' ? 1 : 0);
        $creport->save();

        CustomReportColumns::where('custom_report_id',$custom_report_id)->delete();

        foreach($request->input('activity') as $key => $value){
            $activity = new CustomReportColumns();
            $activity->custom_report_id = $custom_report_id;
            $activity->activity_id = $value;
            $activity->save();
        }

        return redirect(route('custom_report.index'))->with('flash_success', 'Custom report updated successfully');
    }

    public function show(Request $request,$custom_report_id){

        $request->session()->forget('path_route');

        $np = 0;
        $qa = 0;
        $total = 0;
        $rows = 0;

        $report_name = '';
        $report_columns = array();
        $activity_id = array();
        $rp_activity_id = array();
        $data = array();

        $report = CustomReport::with('custom_report_columns.activity_name')->where('id',$custom_report_id)->withTrashed()->first();

        $clients = Client::with(['referrer', 'process.steps2.activities.actionable.data', 'introducer', 'consultant', 'committee', 'trigger'])->select('*', DB::raw('CONCAT(first_name," ",COALESCE(`last_name`,"")) as full_name'),
            DB::raw('ROUND(DATEDIFF(completed_at, created_at), 0) as completed_days'),
            DB::raw('CAST(AES_DECRYPT(`hash_company`, "Qwfe345dgfdg") AS CHAR(50)) hash_company'),
            DB::raw('CAST(AES_DECRYPT(`hash_first_name`, "Qwfe345dgfdg") AS CHAR(50)) hash_first_name'),
            DB::raw('CAST(AES_DECRYPT(`hash_last_name`, "Qwfe345dgfdg") AS CHAR(50)) hash_last_name'),
            DB::raw('CAST(AES_DECRYPT(`hash_cif_code`, "Qwfe345dgfdg") AS CHAR(50)) hash_cif_code'),
            DB::raw('CAST(`case_number` AS CHAR(50)) AS case_number')
        );

        if ($request->has('q') && $request->input('q') != '') {

            $clients = $clients->having('hash_company', 'like', "%" . $request->input('q') . "%")
                ->orHaving('hash_first_name', 'like', "%" . $request->input('q') . "%")
                ->orHaving('hash_last_name', 'like', "%" . $request->input('q') . "%")
                ->orHaving('hash_cif_code', 'like', "%" . $request->input('q') . "%")
                ->orHaving('case_number', 'like', "%" . $request->input('q') . "%");
        }

        $clients = $clients->get();

        if ($request->has('user') && $request->input('user') != null) {
            $clients = $clients->filter(function ($client) use ($request) {
                return $client->consultant_id == $request->input('user');
            });
        }

        if ($request->has('committee') && $request->input('committee') != null) {
            $clients = $clients->filter(function ($client) use ($request) {
                return $client->committee_id == $request->input('committee');
            });
        }

        if ($request->has('trigger') && $request->input('trigger') != '') {
            $p = $request->input('trigger');
            $clients = $clients->filter(function ($client) use ($p) {
                return $client->trigger_type_id == $p;
            });
        }

        if ($request->has('f') && $request->input('f') != '') {
            $p = $request->input('f');
            $clients = $clients->filter(function ($client) use ($p) {
                return $client->instruction_date >= $p;
            });
        }

        if ($request->has('t') && $request->input('t') != '') {
            $p = $request->input('t');
            $clients = $clients->filter(function ($client) use ($p) {
                return Carbon::parse($client->instruction_date)->format("Y-m-d") <= $p;
            });
        }

        $deleted_rps = RelatedPartiesTree::select('related_party_id')->get();

        $related_parties = RelatedParty::with(['client', 'referrer', 'process.steps2.activities.actionable.data', 'introducer', 'committee', 'trigger'])->select('*', DB::raw('CONCAT(first_name," ",COALESCE(`last_name`,"")) as full_name'),
            DB::raw('ROUND(DATEDIFF(completed_at, created_at), 0) as completed_days'),
            DB::raw('CAST(AES_DECRYPT(`hash_company`, "Qwfe345dgfdg") AS CHAR(50)) hash_company'),
            DB::raw('CAST(AES_DECRYPT(`hash_first_name`, "Qwfe345dgfdg") AS CHAR(50)) hash_first_name'),
            DB::raw('CAST(AES_DECRYPT(`hash_last_name`, "Qwfe345dgfdg") AS CHAR(50)) hash_last_name'),
            DB::raw('CAST(AES_DECRYPT(`hash_cif_code`, "Qwfe345dgfdg") AS CHAR(50)) hash_cif_code'),
            DB::raw('CAST(`case_number` AS CHAR(50)) AS case_number')
        )->whereIn('id', collect($deleted_rps)->toArray());

        if ($request->has('q') && $request->input('q') != '') {

            $related_parties = $related_parties->having('hash_company', 'like', "%" . $request->input('q') . "%")
                ->orHaving('hash_first_name', 'like', "%" . $request->input('q') . "%")
                ->orHaving('hash_last_name', 'like', "%" . $request->input('q') . "%")
                ->orHaving('hash_cif_code', 'like', "%" . $request->input('q') . "%")
                ->orHaving('case_number', 'like', "%" . $request->input('q') . "%");
        }

        $related_parties = $related_parties->get();

        if ($request->has('user') && $request->input('user') != null) {
            $related_parties = $related_parties->filter(function ($related_party) use ($request) {
                return $related_party->client->consultant_id == $request->input('user');
            });
        }

        if ($request->has('committee') && $request->input('committee') != null) {
            $related_parties = $related_parties->filter(function ($related_party) use ($request) {
                return $related_party->committee_id == $request->input('committee');
            });
        }

        if ($request->has('trigger') && $request->input('trigger') != null) {
            $related_parties = $related_parties->filter(function ($related_party) use ($request) {
                return $related_party->trigger_type_id == $request->input('trigger');
            });
        }

        if ($request->has('f') && $request->input('f') != '') {
            $p = $request->input('f');
            $related_parties = $related_parties->filter(function ($related_party) use ($p) {
                return $related_party->instruction_date >= $p;
            });
        }

        if ($request->has('t') && $request->input('t') != '') {
            $p = $request->input('t');
            $related_parties = $related_parties->filter(function ($related_party) use ($p) {
                return Carbon::parse($related_party->instruction_date)->format("Y-m-d") <= $p;
            });
        }

        if(isset($report) && $report->group_report == '0') {

                $report_name = $report->name;
                foreach ($report->custom_report_columns as $report_activity) {
                    array_push($report_columns, $report_activity->activity_name["name"]);
                    array_push($activity_id, $report_activity->activity_name["id"]);

                    $rp_activity = ActivityRelatedPartyLink::select('related_activity')->where('primary_activity', $report_activity->activity_name["id"])->first();

                    if ($rp_activity) {
                        array_push($rp_activity_id, $rp_activity->related_activity);
                    }
                }

            foreach ($clients as $client) {
                if ($client) {
                    $data = [];

                    foreach ($activity_id as $key => $value) {
                        $activity = Activity::where('id', $value)->first();

                        switch ($activity["actionable_type"]) {
                            case 'App\ActionableBoolean':
                                $yn_value = '';

                                $data2 = ActionableBooleanData::where('client_id', $client->id)->where('actionable_boolean_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableDate':
                                $data_value = '';

                                $data2 = ActionableDateData::where('client_id', $client->id)->where('actionable_date_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                $data_value = $data2["data"];

                                array_push($data, $data_value);
                                break;
                            case 'App\ActionableText':
                                $data_value = '';

                                $data2 = ActionableTextData::where('client_id', $client->id)->where('actionable_text_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                $data_value = $data2["data"];

                                array_push($data, $data_value);
                                break;
                            case 'App\ActionableTextarea':
                                $data_value = '';

                                $data2 = ActionableTextareaData::where('client_id', $client->id)->where('actionable_textarea_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                $data_value = $data2["data"];

                                array_push($data, $data_value);
                                break;
                            case 'App\ActionableDropdown':
                                $data_value = '';

                                $data2 = ActionableDropdownData::with('item')->where('client_id', $client->id)->where('actionable_dropdown_id', $activity->actionable_id)->get();

                                foreach ($data2 as $key => $value):
                                    if (count($data2) > 1) {
                                        $data_value .= $value["item"]["name"] . ', ';
                                    } else {
                                        $data_value .= $value["item"]["name"];
                                    }
                                endforeach;
                                array_push($data, $data_value);
                                break;
                            case 'App\ActionableDocument':
                                $yn_value = '';

                                $data2 = ActionableDocumentData::where('client_id', $client->id)->where('actionable_document_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["document_id"])) {
                                    $yn_value = "Yes";
                                } else {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableTemplateEmail':
                                $yn_value = '';

                                $data2 = ActionableTemplateEmailData::where('client_id', $client->id)->where('actionable_template_email_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableNotification':
                                $yn_value = '';

                                $data2 = ActionableNotificationData::where('client_id', $client->id)->where('actionable_notification_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableMultipleAttachment':
                                $yn_value = '';

                                $data2 = ActionableMultipleAttachmentData::where('client_id', $client->id)->where('actionable_ma_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            default:
                                //todo capture defaults
                                break;
                        }

                    }
                    $client_data[$client->id] = [
                        'type' => 'P',
                        'company' => ($client->company != null ? $client->company : $client->first_name . ' ' . $client->last_name),
                        'id' => $client->id,
                        'case_nr' => $client->case_number,
                        'cif_code' => $client->cif_code,
                        'committee' => isset($client->committee) ? $client->committee->name : null,
                        'trigger' => ($client->trigger_type_id > 0 ? $client->trigger->name : ''),
                        'instruction_date' => $client->instruction_date,
                        'completed_at' => ($client->completed_at != null ? $client->completed_at : ''),
                        'date_submitted_qa' => $client->qa_start_date,
                        'assigned' => ($client->consultant_id != null ? 1 : 0),
                        'consultant' => isset($client->consultant_id) ? $client->consultant->first_name . ' ' . $client->consultant->last_name : null,
                        'data' => $data
                    ];

                    $total++;
                }
            }

            foreach ($related_parties as $related_party) {
                $rp_data = [];

                foreach ($rp_activity_id as $key => $value) {
                    $activity = Activity::where('id', $value)->first();

                    switch ($activity["actionable_type"]) {
                        case 'App\RelatedPartyBoolean':
                            $yn_value = '';

                            $data2 = RelatedPartyBooleanData::where('related_party_id', $related_party->id)->where('related_party_boolean_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyDate':
                            //dd($activity);
                            $data_value = '';

                            $data2 = RelatedPartyDateData::where('related_party_id', $related_party->id)->where('related_party_date_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            $data_value = $data2["data"];

                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyText':
                            $data_value = '';

                            $data2 = RelatedPartyTextData::where('related_party_id', $related_party->id)->where('related_party_text_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            $data_value = $data2["data"];

                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyTextarea':
                            $data_value = '';

                            $data2 = RelatedPartyTextareaData::where('related_party_id', $related_party->id)->where('related_party_textarea_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            $data_value = $data2["data"];

                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyDropdown':
                            $data_value = '';

                            $data2 = RelatedPartyDropdownData::with('item')->where('related_party_id', $related_party->id)->where('related_party_dropdown_id', $activity->actionable_id)->get();

                            foreach ($data2 as $key => $value):
                                if (count($data2) > 1) {
                                    $data_value .= $value["item"]["name"] . ', ';
                                } else {
                                    $data_value .= $value["item"]["name"];
                                }
                            endforeach;
                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyDocument':
                            $yn_value = '';

                            $data2 = RelatedPartyDocumentData::where('related_party_id', $related_party->id)->where('related_party_document_id', $activity->actionable_id)->take(1)->first();

                            if (isset($data2["document_id"])) {
                                $yn_value = "Yes";
                            } else {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyTemplateEmail':
                            $yn_value = '';

                            $data2 = RelatedPartyTemplateEmailData::where('related_party_id', $related_party->id)->where('related_party_template_email_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyNotification':
                            $yn_value = '';

                            $data2 = RelatedPartyNotificationData::where('related_party_id', $related_party->id)->where('related_party_notification_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyMultipleAttachment':
                            $yn_value = '';

                            $data2 = RelatedPartyMultipleAttachmentData::where('related_party_id', $related_party->id)->where('related_party_ma_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        default:
                            //todo capture defaults
                            break;
                    }

                }
                $client_data[$related_party->client_id]['rp'][$related_party->id] = [
                    'type' => 'R',
                    'company' => ($related_party->company != null ? $related_party->company : $related_party->first_name . ' ' . $related_party->last_name),
                    'id' => $related_party->id,
                    'client_id' => $related_party->client_id,
                    'case_nr' => $related_party->case_number,
                    'cif_code' => $related_party->cif_code,
                    'committee' => isset($related_party->committee) ? $related_party->committee->name : null,
                    'trigger' => ($related_party->trigger_type_id > 0 ? $related_party->trigger->name : ''),
                    'instruction_date' => $related_party->instruction_date,
                    'completed_at' => ($related_party->completed_at != null ? $related_party->completed_at : ''),
                    'date_submitted_qa' => $related_party->qa_start_date,
                    'assigned' => ($related_party->consultant_id != null ? 1 : 0),
                    'consultant' => Client::with('consultant')->where('id', $related_party->client_id)->first(),
                    'data' => $rp_data,
                    'process' => $related_party->process_id,
                    'step' => $related_party->step_id
                ];

                $total++;
            }
        } else {
            $report_name = $report->name;
            foreach ($report->custom_report_columns as $report_activity) {

                $rows = Activity::select(DB::raw("DISTINCT grouping"))->where('step_id',$report_activity->activity_name["step_id"])->where('grouping','>',0)->get()->count();

                array_push($report_columns, $report_activity->activity_name["name"]);

                if($report_activity->activity_name["grouping"] > 0) {
                    array_push($activity_id, $report_activity->activity_name["id"]);
                } else {
                    array_push($activity_id, $report_activity->activity_name["id"]);
                }


                $rp_activity = ActivityRelatedPartyLink::select('related_activity')->where('primary_activity', $report_activity->activity_name["id"])->first();

                if ($rp_activity) {
                    array_push($rp_activity_id, $rp_activity->related_activity);
                }
            }

            foreach ($clients as $client) {
                if ($client) {
                    $data = [];

                    foreach ($activity_id as $key => $value) {
                        $activity = Activity::where('id', $value)->first();

                        switch ($activity["actionable_type"]) {
                            case 'App\ActionableBoolean':
                                $yn_value = '';

                                $data2 = ActionableBooleanData::where('client_id', $client->id)->where('actionable_boolean_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableDate':
                                $data_value = '';

                                $data2 = ActionableDateData::where('client_id', $client->id)->where('actionable_date_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                $data_value = $data2["data"];

                                array_push($data, $data_value);
                                break;
                            case 'App\ActionableText':
                                $data_value = '';

                                $data2 = ActionableTextData::where('client_id', $client->id)->where('actionable_text_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                $data_value = $data2["data"];

                                $data[$activity["id"]] = $data_value;
                                //array_push($data, $data_value);
                                break;
                            case 'App\ActionableTextarea':
                                $data_value = '';

                                $data2 = ActionableTextareaData::where('client_id', $client->id)->where('actionable_textarea_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                $data_value = $data2["data"];

                                array_push($data, $data_value);
                                break;
                            case 'App\ActionableDropdown':
                                $data_value = '';

                                $data2 = ActionableDropdownData::with('item')->where('client_id', $client->id)->where('actionable_dropdown_id', $activity->actionable_id)->get();

                                foreach ($data2 as $key => $value):
                                    if (count($data2) > 1) {
                                        $data_value .= $value["item"]["name"] . ', ';
                                    } else {
                                        $data_value .= $value["item"]["name"];
                                    }
                                endforeach;
                                array_push($data, $data_value);
                                break;
                            case 'App\ActionableDocument':
                                $yn_value = '';

                                $data2 = ActionableDocumentData::where('client_id', $client->id)->where('actionable_document_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["document_id"])) {
                                    $yn_value = "Yes";
                                } else {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableTemplateEmail':
                                $yn_value = '';

                                $data2 = ActionableTemplateEmailData::where('client_id', $client->id)->where('actionable_template_email_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableNotification':
                                $yn_value = '';

                                $data2 = ActionableNotificationData::where('client_id', $client->id)->where('actionable_notification_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableMultipleAttachment':
                                $yn_value = '';

                                $data2 = ActionableMultipleAttachmentData::where('client_id', $client->id)->where('actionable_ma_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            default:
                                //todo capture defaults
                                break;
                        }

                    }
                    $client_data[$client->id] = [
                        'type' => 'P',
                        'company' => ($client->company != null ? $client->company : $client->first_name . ' ' . $client->last_name),
                        'id' => $client->id,
                        'case_nr' => $client->case_number,
                        'cif_code' => $client->cif_code,
                        'committee' => isset($client->committee) ? $client->committee->name : null,
                        'trigger' => ($client->trigger_type_id > 0 ? $client->trigger->name : ''),
                        'instruction_date' => $client->instruction_date,
                        'completed_at' => ($client->completed_at != null ? $client->completed_at : ''),
                        'date_submitted_qa' => $client->qa_start_date,
                        'assigned' => ($client->consultant_id != null ? 1 : 0),
                        'consultant' => isset($client->consultant_id) ? $client->consultant->first_name . ' ' . $client->consultant->last_name : null,
                        'data' => $data
                    ];

                    $total++;
                }
            }

            foreach ($related_parties as $related_party) {
                $rp_data = [];

                foreach ($rp_activity_id as $key => $value) {
                    $activity = Activity::where('id', $value)->first();

                    switch ($activity["actionable_type"]) {
                        case 'App\RelatedPartyBoolean':
                            $yn_value = '';

                            $data2 = RelatedPartyBooleanData::where('related_party_id', $related_party->id)->where('related_party_boolean_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyDate':
                            //dd($activity);
                            $data_value = '';

                            $data2 = RelatedPartyDateData::where('related_party_id', $related_party->id)->where('related_party_date_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            $data_value = $data2["data"];

                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyText':
                            $data_value = '';

                            $data2 = RelatedPartyTextData::where('related_party_id', $related_party->id)->where('related_party_text_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            $data_value = $data2["data"];

                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyTextarea':
                            $data_value = '';

                            $data2 = RelatedPartyTextareaData::where('related_party_id', $related_party->id)->where('related_party_textarea_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            $data_value = $data2["data"];

                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyDropdown':
                            $data_value = '';

                            $data2 = RelatedPartyDropdownData::with('item')->where('related_party_id', $related_party->id)->where('related_party_dropdown_id', $activity->actionable_id)->get();

                            foreach ($data2 as $key => $value):
                                if (count($data2) > 1) {
                                    $data_value .= $value["item"]["name"] . ', ';
                                } else {
                                    $data_value .= $value["item"]["name"];
                                }
                            endforeach;
                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyDocument':
                            $yn_value = '';

                            $data2 = RelatedPartyDocumentData::where('related_party_id', $related_party->id)->where('related_party_document_id', $activity->actionable_id)->take(1)->first();

                            if (isset($data2["document_id"])) {
                                $yn_value = "Yes";
                            } else {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyTemplateEmail':
                            $yn_value = '';

                            $data2 = RelatedPartyTemplateEmailData::where('related_party_id', $related_party->id)->where('related_party_template_email_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyNotification':
                            $yn_value = '';

                            $data2 = RelatedPartyNotificationData::where('related_party_id', $related_party->id)->where('related_party_notification_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyMultipleAttachment':
                            $yn_value = '';

                            $data2 = RelatedPartyMultipleAttachmentData::where('related_party_id', $related_party->id)->where('related_party_ma_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        default:
                            //todo capture defaults
                            break;
                    }

                }
                $client_data[$related_party->client_id]['rp'][$related_party->id] = [
                    'type' => 'R',
                    'company' => ($related_party->company != null ? $related_party->company : $related_party->first_name . ' ' . $related_party->last_name),
                    'id' => $related_party->id,
                    'client_id' => $related_party->client_id,
                    'case_nr' => $related_party->case_number,
                    'cif_code' => $related_party->cif_code,
                    'committee' => isset($related_party->committee) ? $related_party->committee->name : null,
                    'trigger' => ($related_party->trigger_type_id > 0 ? $related_party->trigger->name : ''),
                    'instruction_date' => $related_party->instruction_date,
                    'completed_at' => ($related_party->completed_at != null ? $related_party->completed_at : ''),
                    'date_submitted_qa' => $related_party->qa_start_date,
                    'assigned' => ($related_party->consultant_id != null ? 1 : 0),
                    'consultant' => Client::with('consultant')->where('id', $related_party->client_id)->first(),
                    'data' => $rp_data,
                    'process' => $related_party->process_id,
                    'step' => $related_party->step_id
                ];

                $total++;
            }
        }


        $parameters = [
            'np' => $np,
            'qa' => $qa,
            'report_id' => $custom_report_id,
            'report_name' => $report_name,
            'fields' => $report_columns,
            'clients' => (isset($client_data) ? $client_data : []),
            'committee' => Committee::orderBy('name')->pluck('name', 'id')->prepend('All committees', 'all'),
            'trigger' => TriggerType::orderBy('name')->pluck('name', 'id')->prepend('All trigger types', 'all'),
            'assigned_user' => Client::all()->keyBy('consultant_id')->map(function ($consultant){
                return isset($consultant->consultant)?$consultant->consultant->first_name.' '.$consultant->consultant->last_name:null;
            })->sort(),
            'activity' => '',
            'total' => $total
        ];
        return view('customreports.show')->with($parameters);
    }

    public function destroy($custom_report_id){
        CustomReport::destroy($custom_report_id);
        return redirect()->route("custom_report.index")->with('flash_success','Custom report deleted successfully');
    }

    public function export(Excel $excel,Request $request,$custom_report_id){

        $np = 0;
        $qa = 0;
        $total = 0;
        $rows = 0;

        $report_name = '';
        $report_columns = array();
        $activity_id = array();
        $rp_activity_id = array();
        $data = array();

        $report = CustomReport::with('custom_report_columns.activity_name')->where('id',$custom_report_id)->withTrashed()->first();

        $clients = Client::with(['referrer', 'process.steps2.activities.actionable.data', 'introducer', 'consultant', 'committee', 'trigger'])->select('*', DB::raw('CONCAT(first_name," ",COALESCE(`last_name`,"")) as full_name'),
            DB::raw('ROUND(DATEDIFF(completed_at, created_at), 0) as completed_days'),
            DB::raw('CAST(AES_DECRYPT(`hash_company`, "Qwfe345dgfdg") AS CHAR(50)) hash_company'),
            DB::raw('CAST(AES_DECRYPT(`hash_first_name`, "Qwfe345dgfdg") AS CHAR(50)) hash_first_name'),
            DB::raw('CAST(AES_DECRYPT(`hash_last_name`, "Qwfe345dgfdg") AS CHAR(50)) hash_last_name'),
            DB::raw('CAST(AES_DECRYPT(`hash_cif_code`, "Qwfe345dgfdg") AS CHAR(50)) hash_cif_code'),
            DB::raw('CAST(`case_number` AS CHAR(50)) AS case_number')
        );

        if ($request->has('q') && $request->input('q') != '') {

            $clients = $clients->having('hash_company', 'like', "%" . $request->input('q') . "%")
                ->orHaving('hash_first_name', 'like', "%" . $request->input('q') . "%")
                ->orHaving('hash_last_name', 'like', "%" . $request->input('q') . "%")
                ->orHaving('hash_cif_code', 'like', "%" . $request->input('q') . "%")
                ->orHaving('case_number', 'like', "%" . $request->input('q') . "%");
        }

        $clients = $clients->get();

        if ($request->has('user') && $request->input('user') != null) {
            $clients = $clients->filter(function ($client) use ($request) {
                return $client->consultant_id == $request->input('user');
            });
        }

        if ($request->has('committee') && $request->input('committee') != null) {
            $clients = $clients->filter(function ($client) use ($request) {
                return $client->committee_id == $request->input('committee');
            });
        }

        if ($request->has('trigger') && $request->input('trigger') != '') {
            $p = $request->input('trigger');
            $clients = $clients->filter(function ($client) use ($p) {
                return $client->trigger_type_id == $p;
            });
        }

        if ($request->has('f') && $request->input('f') != '') {
            $p = $request->input('f');
            $clients = $clients->filter(function ($client) use ($p) {
                return $client->instruction_date >= $p;
            });
        }

        if ($request->has('t') && $request->input('t') != '') {
            $p = $request->input('t');
            $clients = $clients->filter(function ($client) use ($p) {
                return Carbon::parse($client->instruction_date)->format("Y-m-d") <= $p;
            });
        }

        $deleted_rps = RelatedPartiesTree::select('related_party_id')->get();

        $related_parties = RelatedParty::with(['client', 'referrer', 'process.steps2.activities.actionable.data', 'introducer', 'committee', 'trigger'])->select('*', DB::raw('CONCAT(first_name," ",COALESCE(`last_name`,"")) as full_name'),
            DB::raw('ROUND(DATEDIFF(completed_at, created_at), 0) as completed_days'),
            DB::raw('CAST(AES_DECRYPT(`hash_company`, "Qwfe345dgfdg") AS CHAR(50)) hash_company'),
            DB::raw('CAST(AES_DECRYPT(`hash_first_name`, "Qwfe345dgfdg") AS CHAR(50)) hash_first_name'),
            DB::raw('CAST(AES_DECRYPT(`hash_last_name`, "Qwfe345dgfdg") AS CHAR(50)) hash_last_name'),
            DB::raw('CAST(AES_DECRYPT(`hash_cif_code`, "Qwfe345dgfdg") AS CHAR(50)) hash_cif_code'),
            DB::raw('CAST(`case_number` AS CHAR(50)) AS case_number')
        )->whereIn('id', collect($deleted_rps)->toArray());

        if ($request->has('q') && $request->input('q') != '') {

            $related_parties = $related_parties->having('hash_company', 'like', "%" . $request->input('q') . "%")
                ->orHaving('hash_first_name', 'like', "%" . $request->input('q') . "%")
                ->orHaving('hash_last_name', 'like', "%" . $request->input('q') . "%")
                ->orHaving('hash_cif_code', 'like', "%" . $request->input('q') . "%")
                ->orHaving('case_number', 'like', "%" . $request->input('q') . "%");
        }

        $related_parties = $related_parties->get();

        if ($request->has('user') && $request->input('user') != null) {
            $related_parties = $related_parties->filter(function ($related_party) use ($request) {
                return $related_party->client->consultant_id == $request->input('user');
            });
        }

        if ($request->has('committee') && $request->input('committee') != null) {
            $related_parties = $related_parties->filter(function ($related_party) use ($request) {
                return $related_party->committee_id == $request->input('committee');
            });
        }

        if ($request->has('trigger') && $request->input('trigger') != null) {
            $related_parties = $related_parties->filter(function ($related_party) use ($request) {
                return $related_party->trigger_type_id == $request->input('trigger');
            });
        }

        if ($request->has('f') && $request->input('f') != '') {
            $p = $request->input('f');
            $related_parties = $related_parties->filter(function ($related_party) use ($p) {
                return $related_party->instruction_date >= $p;
            });
        }

        if ($request->has('t') && $request->input('t') != '') {
            $p = $request->input('t');
            $related_parties = $related_parties->filter(function ($related_party) use ($p) {
                return Carbon::parse($related_party->instruction_date)->format("Y-m-d") <= $p;
            });
        }

        if(isset($report) && $report->group_report == '0') {

            $report_name = $report->name;
            foreach ($report->custom_report_columns as $report_activity) {
                array_push($report_columns, $report_activity->activity_name["name"]);
                array_push($activity_id, $report_activity->activity_name["id"]);

                $rp_activity = ActivityRelatedPartyLink::select('related_activity')->where('primary_activity', $report_activity->activity_name["id"])->first();

                if ($rp_activity) {
                    array_push($rp_activity_id, $rp_activity->related_activity);
                }
            }

            foreach ($clients as $client) {
                if ($client) {
                    $data = [];

                    foreach ($activity_id as $key => $value) {
                        $activity = Activity::where('id', $value)->first();

                        switch ($activity["actionable_type"]) {
                            case 'App\ActionableBoolean':
                                $yn_value = '';

                                $data2 = ActionableBooleanData::where('client_id', $client->id)->where('actionable_boolean_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableDate':
                                $data_value = '';

                                $data2 = ActionableDateData::where('client_id', $client->id)->where('actionable_date_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                $data_value = $data2["data"];

                                array_push($data, $data_value);
                                break;
                            case 'App\ActionableText':
                                $data_value = '';

                                $data2 = ActionableTextData::where('client_id', $client->id)->where('actionable_text_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                $data_value = $data2["data"];

                                array_push($data, $data_value);
                                break;
                            case 'App\ActionableTextarea':
                                $data_value = '';

                                $data2 = ActionableTextareaData::where('client_id', $client->id)->where('actionable_textarea_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                $data_value = $data2["data"];

                                array_push($data, $data_value);
                                break;
                            case 'App\ActionableDropdown':
                                $data_value = '';

                                $data2 = ActionableDropdownData::with('item')->where('client_id', $client->id)->where('actionable_dropdown_id', $activity->actionable_id)->get();

                                foreach ($data2 as $key => $value):
                                    if (count($data2) > 1) {
                                        $data_value .= $value["item"]["name"] . ', ';
                                    } else {
                                        $data_value .= $value["item"]["name"];
                                    }
                                endforeach;
                                array_push($data, $data_value);
                                break;
                            case 'App\ActionableDocument':
                                $yn_value = '';

                                $data2 = ActionableDocumentData::where('client_id', $client->id)->where('actionable_document_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["document_id"])) {
                                    $yn_value = "Yes";
                                } else {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableTemplateEmail':
                                $yn_value = '';

                                $data2 = ActionableTemplateEmailData::where('client_id', $client->id)->where('actionable_template_email_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableNotification':
                                $yn_value = '';

                                $data2 = ActionableNotificationData::where('client_id', $client->id)->where('actionable_notification_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableMultipleAttachment':
                                $yn_value = '';

                                $data2 = ActionableMultipleAttachmentData::where('client_id', $client->id)->where('actionable_ma_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            default:
                                //todo capture defaults
                                break;
                        }

                    }
                    $client_data[$client->id] = [
                        'type' => 'P',
                        'company' => ($client->company != null ? $client->company : $client->first_name . ' ' . $client->last_name),
                        'id' => $client->id,
                        'case_nr' => $client->case_number,
                        'cif_code' => $client->cif_code,
                        'committee' => isset($client->committee) ? $client->committee->name : null,
                        'trigger' => ($client->trigger_type_id > 0 ? $client->trigger->name : ''),
                        'instruction_date' => $client->instruction_date,
                        'completed_at' => ($client->completed_at != null ? $client->completed_at : ''),
                        'date_submitted_qa' => $client->qa_start_date,
                        'assigned' => ($client->consultant_id != null ? 1 : 0),
                        'consultant' => isset($client->consultant_id) ? $client->consultant->first_name . ' ' . $client->consultant->last_name : null,
                        'data' => $data
                    ];

                    $total++;
                }
            }

            foreach ($related_parties as $related_party) {
                $rp_data = [];

                foreach ($rp_activity_id as $key => $value) {
                    $activity = Activity::where('id', $value)->first();

                    switch ($activity["actionable_type"]) {
                        case 'App\RelatedPartyBoolean':
                            $yn_value = '';

                            $data2 = RelatedPartyBooleanData::where('related_party_id', $related_party->id)->where('related_party_boolean_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyDate':
                            //dd($activity);
                            $data_value = '';

                            $data2 = RelatedPartyDateData::where('related_party_id', $related_party->id)->where('related_party_date_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            $data_value = $data2["data"];

                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyText':
                            $data_value = '';

                            $data2 = RelatedPartyTextData::where('related_party_id', $related_party->id)->where('related_party_text_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            $data_value = $data2["data"];

                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyTextarea':
                            $data_value = '';

                            $data2 = RelatedPartyTextareaData::where('related_party_id', $related_party->id)->where('related_party_textarea_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            $data_value = $data2["data"];

                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyDropdown':
                            $data_value = '';

                            $data2 = RelatedPartyDropdownData::with('item')->where('related_party_id', $related_party->id)->where('related_party_dropdown_id', $activity->actionable_id)->get();

                            foreach ($data2 as $key => $value):
                                if (count($data2) > 1) {
                                    $data_value .= $value["item"]["name"] . ', ';
                                } else {
                                    $data_value .= $value["item"]["name"];
                                }
                            endforeach;
                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyDocument':
                            $yn_value = '';

                            $data2 = RelatedPartyDocumentData::where('related_party_id', $related_party->id)->where('related_party_document_id', $activity->actionable_id)->take(1)->first();

                            if (isset($data2["document_id"])) {
                                $yn_value = "Yes";
                            } else {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyTemplateEmail':
                            $yn_value = '';

                            $data2 = RelatedPartyTemplateEmailData::where('related_party_id', $related_party->id)->where('related_party_template_email_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyNotification':
                            $yn_value = '';

                            $data2 = RelatedPartyNotificationData::where('related_party_id', $related_party->id)->where('related_party_notification_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyMultipleAttachment':
                            $yn_value = '';

                            $data2 = RelatedPartyMultipleAttachmentData::where('related_party_id', $related_party->id)->where('related_party_ma_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        default:
                            //todo capture defaults
                            break;
                    }

                }
                $client_data[$related_party->client_id]['rp'][$related_party->id] = [
                    'type' => 'R',
                    'company' => ($related_party->company != null ? $related_party->company : $related_party->first_name . ' ' . $related_party->last_name),
                    'id' => $related_party->id,
                    'client_id' => $related_party->client_id,
                    'case_nr' => $related_party->case_number,
                    'cif_code' => $related_party->cif_code,
                    'committee' => isset($related_party->committee) ? $related_party->committee->name : null,
                    'trigger' => ($related_party->trigger_type_id > 0 ? $related_party->trigger->name : ''),
                    'instruction_date' => $related_party->instruction_date,
                    'completed_at' => ($related_party->completed_at != null ? $related_party->completed_at : ''),
                    'date_submitted_qa' => $related_party->qa_start_date,
                    'assigned' => ($related_party->consultant_id != null ? 1 : 0),
                    'consultant' => Client::with('consultant')->where('id', $related_party->client_id)->first(),
                    'data' => $rp_data,
                    'process' => $related_party->process_id,
                    'step' => $related_party->step_id
                ];

                $total++;
            }
        } else {
            $report_name = $report->name;
            foreach ($report->custom_report_columns as $report_activity) {

                $rows = Activity::select(DB::raw("DISTINCT grouping"))->where('step_id',$report_activity->activity_name["step_id"])->where('grouping','>',0)->get()->count();

                array_push($report_columns, $report_activity->activity_name["name"]);

                if($report_activity->activity_name["grouping"] > 0) {
                    array_push($activity_id, $report_activity->activity_name["id"]);
                } else {
                    array_push($activity_id, $report_activity->activity_name["id"]);
                }


                $rp_activity = ActivityRelatedPartyLink::select('related_activity')->where('primary_activity', $report_activity->activity_name["id"])->first();

                if ($rp_activity) {
                    array_push($rp_activity_id, $rp_activity->related_activity);
                }
            }

            foreach ($clients as $client) {
                if ($client) {
                    $data = [];

                    foreach ($activity_id as $key => $value) {
                        $activity = Activity::where('id', $value)->first();

                        switch ($activity["actionable_type"]) {
                            case 'App\ActionableBoolean':
                                $yn_value = '';

                                $data2 = ActionableBooleanData::where('client_id', $client->id)->where('actionable_boolean_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableDate':
                                $data_value = '';

                                $data2 = ActionableDateData::where('client_id', $client->id)->where('actionable_date_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                $data_value = $data2["data"];

                                array_push($data, $data_value);
                                break;
                            case 'App\ActionableText':
                                $data_value = '';

                                $data2 = ActionableTextData::where('client_id', $client->id)->where('actionable_text_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                $data_value = $data2["data"];

                                $data[$activity["id"]] = $data_value;
                                //array_push($data, $data_value);
                                break;
                            case 'App\ActionableTextarea':
                                $data_value = '';

                                $data2 = ActionableTextareaData::where('client_id', $client->id)->where('actionable_textarea_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                $data_value = $data2["data"];

                                array_push($data, $data_value);
                                break;
                            case 'App\ActionableDropdown':
                                $data_value = '';

                                $data2 = ActionableDropdownData::with('item')->where('client_id', $client->id)->where('actionable_dropdown_id', $activity->actionable_id)->get();

                                foreach ($data2 as $key => $value):
                                    if (count($data2) > 1) {
                                        $data_value .= $value["item"]["name"] . ', ';
                                    } else {
                                        $data_value .= $value["item"]["name"];
                                    }
                                endforeach;
                                array_push($data, $data_value);
                                break;
                            case 'App\ActionableDocument':
                                $yn_value = '';

                                $data2 = ActionableDocumentData::where('client_id', $client->id)->where('actionable_document_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["document_id"])) {
                                    $yn_value = "Yes";
                                } else {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableTemplateEmail':
                                $yn_value = '';

                                $data2 = ActionableTemplateEmailData::where('client_id', $client->id)->where('actionable_template_email_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableNotification':
                                $yn_value = '';

                                $data2 = ActionableNotificationData::where('client_id', $client->id)->where('actionable_notification_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableMultipleAttachment':
                                $yn_value = '';

                                $data2 = ActionableMultipleAttachmentData::where('client_id', $client->id)->where('actionable_ma_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            default:
                                //todo capture defaults
                                break;
                        }

                    }
                    $client_data[$client->id] = [
                        'type' => 'P',
                        'company' => ($client->company != null ? $client->company : $client->first_name . ' ' . $client->last_name),
                        'id' => $client->id,
                        'case_nr' => $client->case_number,
                        'cif_code' => $client->cif_code,
                        'committee' => isset($client->committee) ? $client->committee->name : null,
                        'trigger' => ($client->trigger_type_id > 0 ? $client->trigger->name : ''),
                        'instruction_date' => $client->instruction_date,
                        'completed_at' => ($client->completed_at != null ? $client->completed_at : ''),
                        'date_submitted_qa' => $client->qa_start_date,
                        'assigned' => ($client->consultant_id != null ? 1 : 0),
                        'consultant' => isset($client->consultant_id) ? $client->consultant->first_name . ' ' . $client->consultant->last_name : null,
                        'data' => $data
                    ];

                    $total++;
                }
            }

            foreach ($related_parties as $related_party) {
                $rp_data = [];

                foreach ($rp_activity_id as $key => $value) {
                    $activity = Activity::where('id', $value)->first();

                    switch ($activity["actionable_type"]) {
                        case 'App\RelatedPartyBoolean':
                            $yn_value = '';

                            $data2 = RelatedPartyBooleanData::where('related_party_id', $related_party->id)->where('related_party_boolean_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyDate':
                            //dd($activity);
                            $data_value = '';

                            $data2 = RelatedPartyDateData::where('related_party_id', $related_party->id)->where('related_party_date_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            $data_value = $data2["data"];

                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyText':
                            $data_value = '';

                            $data2 = RelatedPartyTextData::where('related_party_id', $related_party->id)->where('related_party_text_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            $data_value = $data2["data"];

                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyTextarea':
                            $data_value = '';

                            $data2 = RelatedPartyTextareaData::where('related_party_id', $related_party->id)->where('related_party_textarea_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            $data_value = $data2["data"];

                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyDropdown':
                            $data_value = '';

                            $data2 = RelatedPartyDropdownData::with('item')->where('related_party_id', $related_party->id)->where('related_party_dropdown_id', $activity->actionable_id)->get();

                            foreach ($data2 as $key => $value):
                                if (count($data2) > 1) {
                                    $data_value .= $value["item"]["name"] . ', ';
                                } else {
                                    $data_value .= $value["item"]["name"];
                                }
                            endforeach;
                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyDocument':
                            $yn_value = '';

                            $data2 = RelatedPartyDocumentData::where('related_party_id', $related_party->id)->where('related_party_document_id', $activity->actionable_id)->take(1)->first();

                            if (isset($data2["document_id"])) {
                                $yn_value = "Yes";
                            } else {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyTemplateEmail':
                            $yn_value = '';

                            $data2 = RelatedPartyTemplateEmailData::where('related_party_id', $related_party->id)->where('related_party_template_email_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyNotification':
                            $yn_value = '';

                            $data2 = RelatedPartyNotificationData::where('related_party_id', $related_party->id)->where('related_party_notification_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyMultipleAttachment':
                            $yn_value = '';

                            $data2 = RelatedPartyMultipleAttachmentData::where('related_party_id', $related_party->id)->where('related_party_ma_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        default:
                            //todo capture defaults
                            break;
                    }

                }
                $client_data[$related_party->client_id]['rp'][$related_party->id] = [
                    'type' => 'R',
                    'company' => ($related_party->company != null ? $related_party->company : $related_party->first_name . ' ' . $related_party->last_name),
                    'id' => $related_party->id,
                    'client_id' => $related_party->client_id,
                    'case_nr' => $related_party->case_number,
                    'cif_code' => $related_party->cif_code,
                    'committee' => isset($related_party->committee) ? $related_party->committee->name : null,
                    'trigger' => ($related_party->trigger_type_id > 0 ? $related_party->trigger->name : ''),
                    'instruction_date' => $related_party->instruction_date,
                    'completed_at' => ($related_party->completed_at != null ? $related_party->completed_at : ''),
                    'date_submitted_qa' => $related_party->qa_start_date,
                    'assigned' => ($related_party->consultant_id != null ? 1 : 0),
                    'consultant' => Client::with('consultant')->where('id', $related_party->client_id)->first(),
                    'data' => $rp_data,
                    'process' => $related_party->process_id,
                    'step' => $related_party->step_id
                ];

                $total++;
            }
        }


        $parameters = [
            'np' => $np,
            'qa' => $qa,
            'report_id' => $custom_report_id,
            'report_name' => $report_name,
            'fields' => $report_columns,
            'clients' => (isset($client_data) ? $client_data : []),
            'committee' => Committee::orderBy('name')->pluck('name', 'id')->prepend('All committees', 'all'),
            'trigger' => TriggerType::orderBy('name')->pluck('name', 'id')->prepend('All trigger types', 'all'),
            'assigned_user' => Client::all()->keyBy('consultant_id')->map(function ($consultant){
                return isset($consultant->consultant)?$consultant->consultant->first_name.' '.$consultant->consultant->last_name:null;
            })->sort(),
            'activity' => '',
            'total' => $total
        ];

        return $excel->download(new CustomReportExport($client_data,$report_columns), 'clients_'.date('Y_m_d_H_i_s').'.xlsx');
    }

    public function pdfexport($custom_report_id,Request $request)
    {
        $request->session()->forget('path_route');

        $np = 0;
        $qa = 0;
        $total = 0;
        $rows = 0;

        $report_name = '';
        $report_columns = array();
        $activity_id = array();
        $rp_activity_id = array();
        $data = array();

        $report = CustomReport::with('custom_report_columns.activity_name')->where('id',$custom_report_id)->withTrashed()->first();

        $clients = Client::with(['referrer', 'process.steps2.activities.actionable.data', 'introducer', 'consultant', 'committee', 'trigger'])->select('*', DB::raw('CONCAT(first_name," ",COALESCE(`last_name`,"")) as full_name'),
            DB::raw('ROUND(DATEDIFF(completed_at, created_at), 0) as completed_days'),
            DB::raw('CAST(AES_DECRYPT(`hash_company`, "Qwfe345dgfdg") AS CHAR(50)) hash_company'),
            DB::raw('CAST(AES_DECRYPT(`hash_first_name`, "Qwfe345dgfdg") AS CHAR(50)) hash_first_name'),
            DB::raw('CAST(AES_DECRYPT(`hash_last_name`, "Qwfe345dgfdg") AS CHAR(50)) hash_last_name'),
            DB::raw('CAST(AES_DECRYPT(`hash_cif_code`, "Qwfe345dgfdg") AS CHAR(50)) hash_cif_code'),
            DB::raw('CAST(`case_number` AS CHAR(50)) AS case_number')
        );

        if ($request->has('q') && $request->input('q') != '') {

            $clients = $clients->having('hash_company', 'like', "%" . $request->input('q') . "%")
                ->orHaving('hash_first_name', 'like', "%" . $request->input('q') . "%")
                ->orHaving('hash_last_name', 'like', "%" . $request->input('q') . "%")
                ->orHaving('hash_cif_code', 'like', "%" . $request->input('q') . "%")
                ->orHaving('case_number', 'like', "%" . $request->input('q') . "%");
        }

        $clients = $clients->get();

        if ($request->has('user') && $request->input('user') != null) {
            $clients = $clients->filter(function ($client) use ($request) {
                return $client->consultant_id == $request->input('user');
            });
        }

        if ($request->has('committee') && $request->input('committee') != null) {
            $clients = $clients->filter(function ($client) use ($request) {
                return $client->committee_id == $request->input('committee');
            });
        }

        if ($request->has('trigger') && $request->input('trigger') != '') {
            $p = $request->input('trigger');
            $clients = $clients->filter(function ($client) use ($p) {
                return $client->trigger_type_id == $p;
            });
        }

        if ($request->has('f') && $request->input('f') != '') {
            $p = $request->input('f');
            $clients = $clients->filter(function ($client) use ($p) {
                return $client->instruction_date >= $p;
            });
        }

        if ($request->has('t') && $request->input('t') != '') {
            $p = $request->input('t');
            $clients = $clients->filter(function ($client) use ($p) {
                return Carbon::parse($client->instruction_date)->format("Y-m-d") <= $p;
            });
        }

        $deleted_rps = RelatedPartiesTree::select('related_party_id')->get();

        $related_parties = RelatedParty::with(['client', 'referrer', 'process.steps2.activities.actionable.data', 'introducer', 'committee', 'trigger'])->select('*', DB::raw('CONCAT(first_name," ",COALESCE(`last_name`,"")) as full_name'),
            DB::raw('ROUND(DATEDIFF(completed_at, created_at), 0) as completed_days'),
            DB::raw('CAST(AES_DECRYPT(`hash_company`, "Qwfe345dgfdg") AS CHAR(50)) hash_company'),
            DB::raw('CAST(AES_DECRYPT(`hash_first_name`, "Qwfe345dgfdg") AS CHAR(50)) hash_first_name'),
            DB::raw('CAST(AES_DECRYPT(`hash_last_name`, "Qwfe345dgfdg") AS CHAR(50)) hash_last_name'),
            DB::raw('CAST(AES_DECRYPT(`hash_cif_code`, "Qwfe345dgfdg") AS CHAR(50)) hash_cif_code'),
            DB::raw('CAST(`case_number` AS CHAR(50)) AS case_number')
        )->whereIn('id', collect($deleted_rps)->toArray());

        if ($request->has('q') && $request->input('q') != '') {

            $related_parties = $related_parties->having('hash_company', 'like', "%" . $request->input('q') . "%")
                ->orHaving('hash_first_name', 'like', "%" . $request->input('q') . "%")
                ->orHaving('hash_last_name', 'like', "%" . $request->input('q') . "%")
                ->orHaving('hash_cif_code', 'like', "%" . $request->input('q') . "%")
                ->orHaving('case_number', 'like', "%" . $request->input('q') . "%");
        }

        $related_parties = $related_parties->get();

        if ($request->has('user') && $request->input('user') != null) {
            $related_parties = $related_parties->filter(function ($related_party) use ($request) {
                return $related_party->client->consultant_id == $request->input('user');
            });
        }

        if ($request->has('committee') && $request->input('committee') != null) {
            $related_parties = $related_parties->filter(function ($related_party) use ($request) {
                return $related_party->committee_id == $request->input('committee');
            });
        }

        if ($request->has('trigger') && $request->input('trigger') != null) {
            $related_parties = $related_parties->filter(function ($related_party) use ($request) {
                return $related_party->trigger_type_id == $request->input('trigger');
            });
        }

        if ($request->has('f') && $request->input('f') != '') {
            $p = $request->input('f');
            $related_parties = $related_parties->filter(function ($related_party) use ($p) {
                return $related_party->instruction_date >= $p;
            });
        }

        if ($request->has('t') && $request->input('t') != '') {
            $p = $request->input('t');
            $related_parties = $related_parties->filter(function ($related_party) use ($p) {
                return Carbon::parse($related_party->instruction_date)->format("Y-m-d") <= $p;
            });
        }

        if(isset($report) && $report->group_report == '0') {

            $report_name = $report->name;
            foreach ($report->custom_report_columns as $report_activity) {
                array_push($report_columns, $report_activity->activity_name["name"]);
                array_push($activity_id, $report_activity->activity_name["id"]);

                $rp_activity = ActivityRelatedPartyLink::select('related_activity')->where('primary_activity', $report_activity->activity_name["id"])->first();

                if ($rp_activity) {
                    array_push($rp_activity_id, $rp_activity->related_activity);
                }
            }

            foreach ($clients as $client) {
                if ($client) {
                    $data = [];

                    foreach ($activity_id as $key => $value) {
                        $activity = Activity::where('id', $value)->first();

                        switch ($activity["actionable_type"]) {
                            case 'App\ActionableBoolean':
                                $yn_value = '';

                                $data2 = ActionableBooleanData::where('client_id', $client->id)->where('actionable_boolean_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableDate':
                                $data_value = '';

                                $data2 = ActionableDateData::where('client_id', $client->id)->where('actionable_date_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                $data_value = $data2["data"];

                                array_push($data, $data_value);
                                break;
                            case 'App\ActionableText':
                                $data_value = '';

                                $data2 = ActionableTextData::where('client_id', $client->id)->where('actionable_text_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                $data_value = $data2["data"];

                                array_push($data, $data_value);
                                break;
                            case 'App\ActionableTextarea':
                                $data_value = '';

                                $data2 = ActionableTextareaData::where('client_id', $client->id)->where('actionable_textarea_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                $data_value = $data2["data"];

                                array_push($data, $data_value);
                                break;
                            case 'App\ActionableDropdown':
                                $data_value = '';

                                $data2 = ActionableDropdownData::with('item')->where('client_id', $client->id)->where('actionable_dropdown_id', $activity->actionable_id)->get();

                                foreach ($data2 as $key => $value):
                                    if (count($data2) > 1) {
                                        $data_value .= $value["item"]["name"] . ', ';
                                    } else {
                                        $data_value .= $value["item"]["name"];
                                    }
                                endforeach;
                                array_push($data, $data_value);
                                break;
                            case 'App\ActionableDocument':
                                $yn_value = '';

                                $data2 = ActionableDocumentData::where('client_id', $client->id)->where('actionable_document_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["document_id"])) {
                                    $yn_value = "Yes";
                                } else {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableTemplateEmail':
                                $yn_value = '';

                                $data2 = ActionableTemplateEmailData::where('client_id', $client->id)->where('actionable_template_email_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableNotification':
                                $yn_value = '';

                                $data2 = ActionableNotificationData::where('client_id', $client->id)->where('actionable_notification_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableMultipleAttachment':
                                $yn_value = '';

                                $data2 = ActionableMultipleAttachmentData::where('client_id', $client->id)->where('actionable_ma_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            default:
                                //todo capture defaults
                                break;
                        }

                    }
                    $client_data[$client->id] = [
                        'type' => 'P',
                        'company' => ($client->company != null ? $client->company : $client->first_name . ' ' . $client->last_name),
                        'id' => $client->id,
                        'case_nr' => $client->case_number,
                        'cif_code' => $client->cif_code,
                        'committee' => isset($client->committee) ? $client->committee->name : null,
                        'trigger' => ($client->trigger_type_id > 0 ? $client->trigger->name : ''),
                        'instruction_date' => $client->instruction_date,
                        'completed_at' => ($client->completed_at != null ? $client->completed_at : ''),
                        'date_submitted_qa' => $client->qa_start_date,
                        'assigned' => ($client->consultant_id != null ? 1 : 0),
                        'consultant' => isset($client->consultant_id) ? $client->consultant->first_name . ' ' . $client->consultant->last_name : null,
                        'data' => $data
                    ];

                    $total++;
                }
            }

            foreach ($related_parties as $related_party) {
                $rp_data = [];

                foreach ($rp_activity_id as $key => $value) {
                    $activity = Activity::where('id', $value)->first();

                    switch ($activity["actionable_type"]) {
                        case 'App\RelatedPartyBoolean':
                            $yn_value = '';

                            $data2 = RelatedPartyBooleanData::where('related_party_id', $related_party->id)->where('related_party_boolean_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyDate':
                            //dd($activity);
                            $data_value = '';

                            $data2 = RelatedPartyDateData::where('related_party_id', $related_party->id)->where('related_party_date_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            $data_value = $data2["data"];

                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyText':
                            $data_value = '';

                            $data2 = RelatedPartyTextData::where('related_party_id', $related_party->id)->where('related_party_text_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            $data_value = $data2["data"];

                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyTextarea':
                            $data_value = '';

                            $data2 = RelatedPartyTextareaData::where('related_party_id', $related_party->id)->where('related_party_textarea_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            $data_value = $data2["data"];

                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyDropdown':
                            $data_value = '';

                            $data2 = RelatedPartyDropdownData::with('item')->where('related_party_id', $related_party->id)->where('related_party_dropdown_id', $activity->actionable_id)->get();

                            foreach ($data2 as $key => $value):
                                if (count($data2) > 1) {
                                    $data_value .= $value["item"]["name"] . ', ';
                                } else {
                                    $data_value .= $value["item"]["name"];
                                }
                            endforeach;
                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyDocument':
                            $yn_value = '';

                            $data2 = RelatedPartyDocumentData::where('related_party_id', $related_party->id)->where('related_party_document_id', $activity->actionable_id)->take(1)->first();

                            if (isset($data2["document_id"])) {
                                $yn_value = "Yes";
                            } else {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyTemplateEmail':
                            $yn_value = '';

                            $data2 = RelatedPartyTemplateEmailData::where('related_party_id', $related_party->id)->where('related_party_template_email_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyNotification':
                            $yn_value = '';

                            $data2 = RelatedPartyNotificationData::where('related_party_id', $related_party->id)->where('related_party_notification_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyMultipleAttachment':
                            $yn_value = '';

                            $data2 = RelatedPartyMultipleAttachmentData::where('related_party_id', $related_party->id)->where('related_party_ma_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        default:
                            //todo capture defaults
                            break;
                    }

                }
                $client_data[$related_party->client_id]['rp'][$related_party->id] = [
                    'type' => 'R',
                    'company' => ($related_party->company != null ? $related_party->company : $related_party->first_name . ' ' . $related_party->last_name),
                    'id' => $related_party->id,
                    'client_id' => $related_party->client_id,
                    'case_nr' => $related_party->case_number,
                    'cif_code' => $related_party->cif_code,
                    'committee' => isset($related_party->committee) ? $related_party->committee->name : null,
                    'trigger' => ($related_party->trigger_type_id > 0 ? $related_party->trigger->name : ''),
                    'instruction_date' => $related_party->instruction_date,
                    'completed_at' => ($related_party->completed_at != null ? $related_party->completed_at : ''),
                    'date_submitted_qa' => $related_party->qa_start_date,
                    'assigned' => ($related_party->consultant_id != null ? 1 : 0),
                    'consultant' => '',
                    'data' => $rp_data,
                    'process' => $related_party->process_id,
                    'step' => $related_party->step_id
                ];

                $total++;
            }
        } else {
            $report_name = $report->name;
            foreach ($report->custom_report_columns as $report_activity) {

                $rows = Activity::select(DB::raw("DISTINCT grouping"))->where('step_id',$report_activity->activity_name["step_id"])->where('grouping','>',0)->get()->count();

                array_push($report_columns, $report_activity->activity_name["name"]);

                if($report_activity->activity_name["grouping"] > 0) {
                    array_push($activity_id, $report_activity->activity_name["id"]);
                } else {
                    array_push($activity_id, $report_activity->activity_name["id"]);
                }


                $rp_activity = ActivityRelatedPartyLink::select('related_activity')->where('primary_activity', $report_activity->activity_name["id"])->first();

                if ($rp_activity) {
                    array_push($rp_activity_id, $rp_activity->related_activity);
                }
            }

            foreach ($clients as $client) {
                if ($client) {
                    $data = [];

                    foreach ($activity_id as $key => $value) {
                        $activity = Activity::where('id', $value)->first();

                        switch ($activity["actionable_type"]) {
                            case 'App\ActionableBoolean':
                                $yn_value = '';

                                $data2 = ActionableBooleanData::where('client_id', $client->id)->where('actionable_boolean_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableDate':
                                $data_value = '';

                                $data2 = ActionableDateData::where('client_id', $client->id)->where('actionable_date_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                $data_value = $data2["data"];

                                array_push($data, $data_value);
                                break;
                            case 'App\ActionableText':
                                $data_value = '';

                                $data2 = ActionableTextData::where('client_id', $client->id)->where('actionable_text_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                $data_value = $data2["data"];

                                $data[$activity["id"]] = $data_value;
                                //array_push($data, $data_value);
                                break;
                            case 'App\ActionableTextarea':
                                $data_value = '';

                                $data2 = ActionableTextareaData::where('client_id', $client->id)->where('actionable_textarea_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                $data_value = $data2["data"];

                                array_push($data, $data_value);
                                break;
                            case 'App\ActionableDropdown':
                                $data_value = '';

                                $data2 = ActionableDropdownData::with('item')->where('client_id', $client->id)->where('actionable_dropdown_id', $activity->actionable_id)->get();

                                foreach ($data2 as $key => $value):
                                    if (count($data2) > 1) {
                                        $data_value .= $value["item"]["name"] . ', ';
                                    } else {
                                        $data_value .= $value["item"]["name"];
                                    }
                                endforeach;
                                array_push($data, $data_value);
                                break;
                            case 'App\ActionableDocument':
                                $yn_value = '';

                                $data2 = ActionableDocumentData::where('client_id', $client->id)->where('actionable_document_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["document_id"])) {
                                    $yn_value = "Yes";
                                } else {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableTemplateEmail':
                                $yn_value = '';

                                $data2 = ActionableTemplateEmailData::where('client_id', $client->id)->where('actionable_template_email_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableNotification':
                                $yn_value = '';

                                $data2 = ActionableNotificationData::where('client_id', $client->id)->where('actionable_notification_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            case 'App\ActionableMultipleAttachment':
                                $yn_value = '';

                                $data2 = ActionableMultipleAttachmentData::where('client_id', $client->id)->where('actionable_ma_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                                if (isset($data2["data"]) && $data2["data"] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($data2["data"]) && $data2["data"] == '0') {
                                    $yn_value = "No";
                                }
                                array_push($data, $yn_value);
                                break;
                            default:
                                //todo capture defaults
                                break;
                        }

                    }
                    $client_data[$client->id] = [
                        'type' => 'P',
                        'company' => ($client->company != null ? $client->company : $client->first_name . ' ' . $client->last_name),
                        'id' => $client->id,
                        'client_id'=>'',
                        'case_nr' => $client->case_number,
                        'cif_code' => $client->cif_code,
                        'committee' => isset($client->committee) ? $client->committee->name : null,
                        'trigger' => ($client->trigger_type_id > 0 ? $client->trigger->name : ''),
                        'instruction_date' => $client->instruction_date,
                        'completed_at' => ($client->completed_at != null ? $client->completed_at : ''),
                        'date_submitted_qa' => $client->qa_start_date,
                        'assigned' => ($client->consultant_id != null ? 1 : 0),
                        'consultant' => '',
                        'data' => $data
                    ];

                    $total++;
                }
            }

            foreach ($related_parties as $related_party) {
                $rp_data = [];

                foreach ($rp_activity_id as $key => $value) {
                    $activity = Activity::where('id', $value)->first();

                    switch ($activity["actionable_type"]) {
                        case 'App\RelatedPartyBoolean':
                            $yn_value = '';

                            $data2 = RelatedPartyBooleanData::where('related_party_id', $related_party->id)->where('related_party_boolean_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyDate':
                            //dd($activity);
                            $data_value = '';

                            $data2 = RelatedPartyDateData::where('related_party_id', $related_party->id)->where('related_party_date_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            $data_value = $data2["data"];

                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyText':
                            $data_value = '';

                            $data2 = RelatedPartyTextData::where('related_party_id', $related_party->id)->where('related_party_text_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            $data_value = $data2["data"];

                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyTextarea':
                            $data_value = '';

                            $data2 = RelatedPartyTextareaData::where('related_party_id', $related_party->id)->where('related_party_textarea_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            $data_value = $data2["data"];

                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyDropdown':
                            $data_value = '';

                            $data2 = RelatedPartyDropdownData::with('item')->where('related_party_id', $related_party->id)->where('related_party_dropdown_id', $activity->actionable_id)->get();

                            foreach ($data2 as $key => $value):
                                if (count($data2) > 1) {
                                    $data_value .= $value["item"]["name"] . ', ';
                                } else {
                                    $data_value .= $value["item"]["name"];
                                }
                            endforeach;
                            array_push($rp_data, $data_value);
                            break;
                        case 'App\RelatedPartyDocument':
                            $yn_value = '';

                            $data2 = RelatedPartyDocumentData::where('related_party_id', $related_party->id)->where('related_party_document_id', $activity->actionable_id)->take(1)->first();

                            if (isset($data2["document_id"])) {
                                $yn_value = "Yes";
                            } else {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyTemplateEmail':
                            $yn_value = '';

                            $data2 = RelatedPartyTemplateEmailData::where('related_party_id', $related_party->id)->where('related_party_template_email_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyNotification':
                            $yn_value = '';

                            $data2 = RelatedPartyNotificationData::where('related_party_id', $related_party->id)->where('related_party_notification_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        case 'App\RelatedPartyMultipleAttachment':
                            $yn_value = '';

                            $data2 = RelatedPartyMultipleAttachmentData::where('related_party_id', $related_party->id)->where('related_party_ma_id', $activity->actionable_id)->orderBy('created_at', 'desc')->take(1)->first();

                            if (isset($data2["data"]) && $data2["data"] == '1') {
                                $yn_value = "Yes";
                            }
                            if (isset($data2["data"]) && $data2["data"] == '0') {
                                $yn_value = "No";
                            }
                            array_push($rp_data, $yn_value);
                            break;
                        default:
                            //todo capture defaults
                            break;
                    }

                }
                $client_data[$related_party->client_id]['rp'][$related_party->id] = [
                    'type' => 'R',
                    'company' => ($related_party->company != null ? $related_party->company : $related_party->first_name . ' ' . $related_party->last_name),
                    'id' => $related_party->id,
                    'client_id' => $related_party->client_id,
                    'case_nr' => $related_party->case_number,
                    'cif_code' => $related_party->cif_code,
                    'committee' => isset($related_party->committee) ? $related_party->committee->name : null,
                    'trigger' => ($related_party->trigger_type_id > 0 ? $related_party->trigger->name : ''),
                    'instruction_date' => $related_party->instruction_date,
                    'completed_at' => ($related_party->completed_at != null ? $related_party->completed_at : ''),
                    'date_submitted_qa' => $related_party->qa_start_date,
                    'assigned' => ($related_party->consultant_id != null ? 1 : 0),
                    'consultant' => '',
                    'data' => $rp_data
                ];

                $total++;
            }
        }


        $parameters = [
            'np' => $np,
            'qa' => $qa,
            'report_id' => $custom_report_id,
            'report_name' => $report_name,
            'fields' => $report_columns,
            'clients' => (isset($client_data) ? $client_data : []),
            'committee' => Committee::orderBy('name')->pluck('name', 'id')->prepend('All committees', 'all'),
            'trigger' => TriggerType::orderBy('name')->pluck('name', 'id')->prepend('All trigger types', 'all'),
            'assigned_user' => Client::all()->keyBy('consultant_id')->map(function ($consultant){
                return isset($consultant->consultant)?$consultant->consultant->first_name.' '.$consultant->consultant->last_name:null;
            })->sort(),
            'activity' => '',
            'total' => $total
        ];

        $pdf = PDF::loadView('pdf.customreport2', $parameters)->setPaper('a4')->setOrientation('landscape');
        return $pdf->download('clients_'.date('Y_m_d_H_i_s').'.pdf');
    }

    public function getActivities($processid){

        $step_arr = array();
        $activities_arr = array();

        $steps = Step::where('process_id',$processid)->orderBy('order','asc')->get();

        foreach($steps as $step){
            $step_arr2 = array();

            $step_arr2['id'] = $step->id;
            $step_arr2['name'] = $step->name;

            //array_push($step_arr, $step_arr2);

            $activities = Activity::select('activities.id','activities.name','activities.actionable_type','activities.grouping')->leftJoin('steps','steps.id','activities.step_id')->where('steps.process_id',$processid)->where('steps.deleted_at',null)->where('activities.deleted_at',null)->where('activities.step_id',$step->id)->orderBy('activities.step_id','asc')->orderBy('activities.order','asc')->get();
            //dd($activities);
            $step_arr2['activity'] = array();
            foreach($activities as $activity){

                if($activity->grouping != null && $activity->grouping > 0) {
                    if($activity->grouping == 1) {
                        array_push($step_arr2['activity'], [
                            'id' => $activity->id,
                            'name' => $activity->name,
                            'type' => $activity->actionable_type,
                            'step' => $activity->step_id,
                            'grouping' => '1'
                        ]);
                    }
                } else {
                    array_push($step_arr2['activity'], [
                        'id' => $activity->id,
                        'name' => $activity->name,
                        'type' => $activity->actionable_type,
                        'step' => $activity->step_id,
                        'grouping' => '0'
                    ]);
                }


            }

            array_push($step_arr,$step_arr2);
        }



        return response()->json($step_arr);
    }

    public function getSelectedActivities($custom_report_id){

        $sa = array();

        $process = CustomReport::where('id',$custom_report_id)->first();

        $selected_activities = CustomReportColumns::select('activity_id')->where('custom_report_id',$custom_report_id)->get();

        foreach($selected_activities as $result){
            array_push($sa,$result->activity_id);
        }

        $step_arr = array();
        $activities_arr = array();

        $steps = Step::where('process_id',$process->process_id)->orderBy('order','asc')->get();

        foreach($steps as $step){
            $step_arr2 = array();

            $step_arr2['id'] = $step->id;
            $step_arr2['name'] = $step->name;

            $activities = Activity::select('activities.id','activities.name','activities.actionable_type','activities.grouping')->leftJoin('steps','steps.id','activities.step_id')->where('activities.step_id',$step->id)->where('steps.process_id',$process->process_id)->where('steps.deleted_at',null)->where('activities.deleted_at',null)->where('activities.step_id',$step->id)->orderBy('activities.step_id','asc')->orderBy('activities.order','asc')->get();

            $step_arr2['activity'] = array();
            foreach($activities as $activity){

                if(($key = array_search($activity->id, $sa)) === false) {
                    if($activity->grouping != null && $activity->grouping > 0) {
                        if($activity->grouping == 1) {
                            array_push($step_arr2['activity'], [
                                'id' => $activity->id,
                                'name' => $activity->name,
                                'type' => $activity->actionable_type,
                                'selected' => '1',
                                'grouping' => '1'
                            ]);
                        }
                    } else {
                        array_push($step_arr2['activity'], [
                            'id' => $activity->id,
                            'name' => $activity->name,
                            'type' => $activity->actionable_type,
                            'selected' => '1',
                            'grouping' => '0'
                        ]);
                    }
                } else {
                    if($activity->grouping != null && $activity->grouping > 0) {
                        if($activity->grouping == 1) {
                            array_push($step_arr2['activity'], [
                                'id' => $activity->id,
                                'name' => $activity->name,
                                'type' => $activity->actionable_type,
                                'selected' => '0',
                                'grouping' => '1'
                            ]);
                        }
                    } else {
                        array_push($step_arr2['activity'], [
                            'id' => $activity->id,
                            'name' => $activity->name,
                            'type' => $activity->actionable_type,
                            'selected' => '0',
                            'grouping' => '0'
                        ]);
                    }
                }

            }

            array_push($step_arr,$step_arr2);
        }
        return $step_arr;

        return response()->json($step_arr);
    }
}
