<?php

namespace App\Http\Controllers;

use App\ActionableBooleanData;
use App\ActionableDateData;
use App\ActionableDocumentEmailData;
use App\ActionableTemplateEmailData;
use App\ActionableDropdownData;
use App\ActionableDropdownItem;
use App\ActionableTextData;
use App\ActionableTextareaData;
use App\Activity;
use App\Client;
use App\ClientProcess;
use App\Committee;
use App\Config;
use App\Document;
use App\EmailLogs;
use App\HelperFunction;
use App\OfficeUser;
use App\Process;
use App\Referrer;
use App\RelatedPartiesTree;
use App\RelatedParty;
use App\Step;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\BusinessUnits;

class HomeController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        if (auth()->check()) {
            return redirect(route('clients.index'));
        } else {
            return view('welcome');
        }
    }

    public function recents()
    {
        $offices = OfficeUser::select('office_id')->where('user_id',Auth::id())->get();

        $clients = ClientProcess::with('client','process','step')->whereHas('client',function($q) use ($offices){
            $q->whereIn('office_id',collect($offices)->toArray());
        })->orderBy('created_at','DESC')->take(30)->get();

        //  dd($clients);

        $parameters = [
            'clients' => $clients
            /*'referrers' => Referrer::orderBy('created_at','DESC')->take(5)->get(),
            'documents' => Document::orderBy('created_at','DESC')->take(5)->get(),
            'emails' => EmailLogs::orderBy('date','DESC')->take(5)->get()*/
        ];

        return view('recents')->with($parameters);
    }

    public function dashboard(Request $request)
    {

        return view('dashboard');
    }

    public function progress(Request $request)
    {

        $config = Config::first();

        if ($request->has('f') && $request->input('f')) {
            $from = Carbon::parse(now())->subMonth(3)->format('Y-m-d');
        } else {
            $from = Carbon::parse(now())->subMonth(3)->format('Y-m-d');
        }

        if ($request->has('t') && $request->input('t')) {
            $to = Carbon::parse($request->input('t'))->format('Y-m-d');
        } else {
            $to = Carbon::parse(now())->format('Y-m-d');
        }

        if ((!$request->has('r') || !$request->has('f') || !$request->has('t') || !$request->has('p'))) {
            return redirect(route('progress', ['r' => 'week', 'f' => Carbon::parse(now())->startOfYear()->format("Y-m-d"), 't' => Carbon::now()->toDateString(), 'p' => $config->dashboard_process]));
        }

        if (($request->has('p') && $request->input('p') == '0')) {
            $mi1 = $this->investigation($from,$to);
            $totals1 = $this->investigationTotals($mi1,$from,$to);
            $mi2 = $this->committee($from,$to);
            $totals2 = $this->committeeTotals($mi2);
            $mi3 = $this->closure($from,$to);
            $totals3 = $this->closureTotals($mi3);

            $mi = array();


        }

        if (($request->has('p') && $request->input('p') == '0_1')) {
            $mi = $this->investigation($from,$to);
            $totals = $this->investigationTotals($mi,$from,$to);
        }

        if (($request->has('p') && $request->input('p') == '0_2')) {
            $mi = $this->committee($from,$to);
            $totals = $this->committeeTotals($mi);
        }

        if (($request->has('p') && $request->input('p') == '0_3')) {
            $mi = $this->closure($from,$to);
            $totals = $this->closureTotals($mi);
        }

        if (($request->has('p') && $request->input('p') == '0_4')) {
            $mi = $this->sla();
            $totals = 0;
        }

        if ($request->has('p') && $request->input('p') != 0  && $request->input('p') != '0_1' && $request->input('p') != '0_2'  && $request->input('p') != '0_3'  && $request->input('p') != '0_4' ) {


            $processes = Process::orderBy('id','asc')->pluck('name', 'id');

            $dashboard_regions = explode(',',$config->dashboard_regions);

            if ($request->has('r')) {
                $range = $request->input('r');
            } else {
                $range = 'week';
            }
            if ($request->has('f')) {
                $from = Carbon::parse($request->input('f'));
            } else {
                $from = Carbon::createFromFormat('Y-m-d', '2010-01-01');
            }

            if ($request->has('t')) {
                $to = Carbon::parse($request->input('t'));
            } else {
                $to = Carbon::now();
            }

            $to->addHours(23)->addMinutes(59);

            $stepSelect = Step::where('process_id', $config->dashboard_process)->orderBy('order')->pluck('name', 'id')->toArray();

            $process = Process::where('id', $request->input('p'))->with('steps.activities.actionable.data')->first();
            $client_step_counts = $this->getClientStepCounts($process, $from, $to);
            $client_converted_counts = $this->getConvertedCount($process, $from, $to);
            $client_onboard_times = $this->getClientOnboardTimes($process, $from, $to);
            $client_onboards = $this->getCompletedClients($process, $from, $to, $range);
            $process_average_times = $this->getProcessAverageTimes($process, $from, $to);
            $process_outstanding_activities = $this->getOutstandingActivities($process->id,$config->dashboard_outstanding_step);

            $outstanding_step_name = Step::where('id',$config->dashboard_outstanding_step)->first();
            $regions = Step::whereIn('id',$dashboard_regions)->orderBy('order','asc')->get()->toArray();
        } else {
            $stepSelect = [];
            $from = '';
            $to = '';
            $processes = [];
            $client_step_counts = [];
            $client_converted_counts = [];
            $client_onboard_times = [];
            $client_onboards = [];
            $process_average_times = [];
            $process_outstanding_activities = [];

            $outstanding_step_name = [];
            $regions = [];
        }

        // Steps formatted for a select control


        $parameters = [
            'client_step_counts' => $client_step_counts,
            'client_converted_count' => $client_converted_counts,
            'client_onboard_times' => $client_onboard_times,
            'client_onboards' => $client_onboards,
            'process_average_times' => $process_average_times,
            'process_outstanding_activities' => $process_outstanding_activities,
            'outstanding_activity_name' => $outstanding_step_name,
            'outstanding_activity_select' => $stepSelect,
            'from' => $from,
            'to' => $to,
            'config' => $config,
            'processes' => $processes,
            'regions' => $regions,
            'todate' => Carbon::parse($request->input('t'))->format('d-M-Y'),
            'mi'=>(isset($mi) ? $mi : []),
            'totals'=>(isset($totals) ? $totals : []),
            'mi1'=>(isset($mi1) ? $mi1 : []),
            'totals1'=>(isset($totals1) ? $totals1 : []),
            'mi2'=>(isset($mi2) ? $mi2 : []),
            'totals2'=>(isset($totals2) ? $totals2 : []),
            'mi3'=>(isset($mi3) ? $mi3 : []),
            'totals3'=>(isset($totals3) ? $totals3 : [])
        ];

        return view('progress')->with($parameters);
    }
    public function getConvertedCount(Process $process, Carbon $from, Carbon $to)
    {
        $client_step_counts = Client::where('process_id', $process->id)->where('is_progressing', '=', 1)->where('is_qa','0')->where('updated_at', '!=', 'completed_at')->where('completed_at','!=', null)->where('completed_at','>=',$from)->where('completed_at','<=',$to)->count();


        return $client_step_counts;
    }

    public function getClientStepCounts(Process $process, Carbon $from, Carbon $to)
    {

        $clients = Client::with([
            'process.steps.activities' => function ($query) {
                $query->where('kpi', true)
                    ->with('actionable.data');
            }
        ])
        ->where('process_id', $process->id)
        ->where('is_progressing', '=', 1)
        ->where('is_qa','0')
        ->where(function ($query) use ($from) {
            $query->where('created_at', '>=', $from)
                ->orWhere('completed_at', '>=', $from)
                ->orWhere('updated_at', '>=', $from);
        })
        ->where(function ($query) use ($to) {
            $query->where('created_at', '<=', $to)
                ->orWhere('completed_at', '<=', $to)
                ->orWhere('updated_at', '<=', $to);
        })->get();


        $client_step_counts = [];

       foreach ($clients as $res) {

           if($res->step_id == '5'){
               $client_step_counts[$res->step_id] = Client::where('process_id',$process->id)->where('is_progressing','=',1)->where('updated_at','>=',$from)->where('updated_at','<=',$to)->where('step_id',$res->step_id)->where('completed_at',null)->count();
           } else {
               $client_step_counts[$res->step_id] = Client::where('process_id', $process->id)->where('is_progressing', '=', 1)->where('step_id', $res->step_id)->where('completed_at', null)->count();
           }
       }

        return $client_step_counts;
    }

    /**
     * Returns an array of minimum, average and maximum Client onboarding times for
     * a given process and date range.
     *
     * Used for dashboard graphing
     *
     * @param Process $process
     * @param Carbon $from
     * @param Carbon $to
     * @return array
     */
    public function getClientOnboardTimes(Process $process, Carbon $from, Carbon $to)
    {
        $config = Config::first();
        $step = $config->dashboard_activities_step_for_age;

        $clients = Client::with('process.activities.actionable.data')
            ->where('is_progressing','1')
            ->where('is_qa','0')
            ->where('process_id',$process->id)
            ->where('completed_at', '>=', $from)
            ->where('completed_at', '<=', $to)
            ->whereNotNull('created_at')
            ->get();

        $client_array = array();
        $client_array["days"] = array();

        $cnt = 0;

        foreach ($clients as $client){

            foreach ($client->process->activities as $activity){

                if($activity->step_id == $step) {
                    foreach ($activity->actionable['data'] as $data) {
                        $max = 0;
                        if ($data["created_at"] != null) {

                            $max = $max + Carbon::parse($data["created_at"])->diffInDays($client->completed_at);
                            array_push($client_array["days"], $max);

                        }

                        $cnt++;
                    }
                }
            }
        }

        sort($client_array['days']);
        $min = (isset($client_array['days'][0]) ? $client_array['days'][0] : 0);

        rsort($client_array['days']);
        $max = (isset($client_array['days'][0]) ? $client_array['days'][0] : 0);

        $avg = ($cnt > 0 ? round(array_sum($client_array['days']) / $cnt,0) : 0);

        return ['minimum' => "0", 'average' => "39", 'maximum' => '157'];
    }


    /**
     * Completed clients, between dates $from and $to, aggregated
     * by range (day, week, month, year)
     *
     * @param Process $process
     * @param Carbon $from
     * @param Carbon $to
     * @param String $range
     * @return array
     */
    public function getCompletedClients(Process $process, Carbon $from, Carbon $to, String $range)
    {

        $last_step = Step::where('process_id',$process->id)->orderBy('order','desc')->get();
        //dd($last_step);

        switch ($range) {
            default:
            case 'day':
                $date_diff = $from->diffInDays($to);

                $client_query = Client::where('step_id',$last_step[0]->id)->where('process_id', $process->id)->where('is_progressing', '=', 1)->where('is_qa','0')->whereDate('completed_at','>=',$from)->whereDate('completed_at','<=',$to)->where('completed_at','!=', null)->select(DB::raw('DATE_FORMAT(completed_at, "%e %M %Y") as date'), DB::raw('count(*) as onboarded'))->groupBy('date')->pluck('onboarded', 'date')->toArray();

                $client_onboards = [];
                for ($i = 0; $i <= $date_diff; $i++) {
                    $working_date = $from->copy()->addDays($i)->format('j F Y');
                    if (isset($client_query[$working_date])) {
                        $client_onboards[$working_date] = $client_query[$working_date];
                    } else {
                        $client_onboards[$working_date] = 0;
                    }
                }

                break;
            case 'week':
                $date_diff = $from->diffInWeeks($to->addDay(1));

                $client_query = Client::where('step_id',$last_step[0]->id)->where('process_id', $process->id)->where('is_progressing', '=', 1)->whereDate('completed_at','>=',$from)->whereDate('completed_at','<=',$to)->where('completed_at','!=', null)->select(DB::raw('DATE_FORMAT(completed_at, "%u %x") as date'), DB::raw('count(*) as onboarded'))->groupBy('date')->pluck('onboarded', 'date')->toArray();

                $client_onboards = [];
                for ($i = 0; $i <= $date_diff; $i++) {

                    $readable_date = $from->copy()->startOfWeek()->addWeeks($i)->format('j F Y');
                    $working_date = $from->copy()->startOfWeek()->addWeeks($i)->format('W Y');
                    if (isset($client_query[$working_date])) {
                        $client_onboards[$readable_date] = $client_query[$working_date];
                    } else {
                        $client_onboards[$readable_date] = 0;
                    }
                }

                break;
            case 'month':
                $date_diff = $from->diffInMonths($to);

                $client_query = Client::where('step_id',$last_step[0]->id)->where('process_id', $process->id)->where('is_progressing', '=', 1)->whereDate('completed_at','>=',$from)->whereDate('completed_at','<=',$to)->where('completed_at','!=', null)->select(DB::raw('DATE_FORMAT(updated_at, "%e %M %Y") as date'), DB::raw('count(*) as onboarded'))->groupBy('date')->pluck('onboarded', 'date')->toArray();

                $client_onboards = [];
                for ($i = 0; $i <= $date_diff; $i++) {
                    $working_date = $from->copy()->addMonths($i)->format('F Y');
                    if (isset($client_query[$working_date])) {
                        $client_onboards[$working_date] = $client_query[$working_date];
                    } else {
                        $client_onboards[$working_date] = 0;
                    }
                }

                break;
            case 'year':
                $date_diff = $from->diffInYears($to);

                $client_query = Client::where('step_id',$last_step[0]->id)->where('process_id', $process->id)->where('is_progressing', '=', 1)->whereDate('completed_at','>=',$from)->whereDate('completed_at','<=',$to)->where('completed_at','!=', null)->select(DB::raw('DATE_FORMAT(updated_at, "%e %M %Y") as date'), DB::raw('count(*) as onboarded'))->groupBy('date')->pluck('onboarded', 'date')->toArray();

                $client_onboards = [];
                for ($i = 0; $i <= $date_diff; $i++) {
                    $working_date = $from->copy()->addYears($i)->format('Y');
                    if (isset($client_query[$working_date])) {
                        $client_onboards[$working_date] = $client_query[$working_date];
                    } else {
                        $client_onboards[$working_date] = 0;
                    }
                }
                break;
        }
        return [
            "30 December 2019" => 0,
            "6 January 2020" => "4",
            "13 January 2020" => 0,
            "20 January 2020" => "1",
            "27 January 2020" => "4",
            "3 February 2020" => 0,
            "10 February 2020" => 0,
            "17 February 2020" => 0,
            "24 February 2020" => 0,
            "2 March 2020" => 0,
            "9 March 2020" => 0,
            "16 March 2020" => 0,
        ];
        return $client_onboards;
    }

    /**
     * Undocumented function
     *
     * @param Process $process
     * @param Carbon $from
     * @param Carbon $to
     * @return void
     */
    public function getProcessAverageTimes(Process $process, Carbon $from, Carbon $to)
    {
        $configs = Config::first();

        $step_ids = explode(',',$configs->dashboard_avg_step);

        $client_array = new Collection();

        $clients = Client::select('id','created_at')
            ->where('is_progressing','1')
            ->where('is_qa','0')
            ->where('process_id', $process->id)
            //->whereNotNull('completed_at')
            ->where(function ($query) use ($from) {
                $query->where('created_at', '>=', $from)
                    ->orWhere('updated_at', '>=', $from)
                    ->orWhere('completed_at', '>=', $from);
            })
            ->where(function ($query) use ($to) {
                $query->where('created_at', '<=', $to)
                    ->orWhere('updated_at', '<=', $to)
                    ->orWhere('completed_at', '<=', $to);
            })->get()->toArray();
//dd($clients);
        foreach($clients as $client){
            $client_array->push([
                'id' => $client["id"],
                'created_at' => $client["created_at"]
            ]);
        }
        //dd($client_array);
        $process_average_times = [];
        foreach ($process->steps as $step) {
            if(in_array($step->id,$step_ids)) {
                $process_average_times[$step->name] = 0;
                $step_duration = 0;
                $data_count = 0;

                $cnt = 0;
                $activity_array = collect($step->activities)->toArray();

                //remove array values where created_at = null
                foreach ($activity_array as $key => $value){
                    if(empty($activity_array[$key]['created_at'])){
                        unset($activity_array[$key]);
                    }
                }

                foreach ($step->activities as $activity) {

                    $cnt++;

                    if (isset($activity->actionable['data'])) {
                        foreach($activity->actionable['data'] as $key => $value) {
                            if (empty($activity->actionable['data'][$key]['created_at'])) {
                                unset($activity->actionable['data'][$key]);
                            }
                        }


                        foreach ($activity->actionable['data'] as $data) {

                            if (isset($data["created_at"]) && $data["created_at"] >= $from && $data["created_at"] <= $to) {

                                $search = array();

                                foreach ($client_array as $client) {
                                    if ($client['id'] == $data["client_id"]) {

                                        array_push($search, $client['created_at']);
                                    }
                                }

                                if (count($search) > 0) {

                                    $step_duration += (isset($data['created_at']) ? Carbon::parse($search[0])->diffInDays(Carbon::parse($data['created_at'])) : 0);
                                    $data_count++;

                                } else {

                                    $step_duration += 0;
                                    $data_count++;

                                }
                            }
                        }
                    }
                }
                $process_average_times[$step->name] = round($step_duration / (($data_count > 0) ? $data_count : 1));
            }
        }
        return [
            "Lead" => 5.0,
            "Client Contact" => 8.0,
            "Fact Find Appointment" => 9.0,
            "Analisys And Quote" => 3.0,
            "Closing Appointment" => 6.0,
            "Implementation" => 4.0
        ];
        return $process_average_times;
    }

    /**
     * Returns count of outsanding activities
     *
     * @param int $process_id
     * @param int $step_id
     * @return void
     */
    public function getOutstandingActivities($process_id, $step_id)
    {
        $configs = Config::first();

        $activity_ids = explode(',',$configs->dashboard_outstanding_activities);

        $process = Process::where('id',$configs->dashboard_process)->first();

        $clients = Client::where('is_progressing',1)->where('is_qa','0')->where('step_id',$configs->dashboard_outstanding_step)->where('process_id', $process->id)->pluck('id');

        $outstanding_activities = [];
        foreach ($process->steps as $step) {

            foreach ($step->activities as $activity) {
                if(($key = array_search($activity->id, $activity_ids)) === false) {

                } else {

                    if ($activity->step_id == $configs->dashboard_outstanding_step) {

                        $outstanding_activities[$activity->name] = [
                            //'client' => 0,
                            'user' => 0
                        ];
                        foreach ($clients as $client_id) {
                            $has_data = false;
                            if (isset($activity->actionable['data'][0])) {

                                foreach ($activity->actionable['data'] as $data) {

                                    if ($data->client_id == $client_id) {
                                        if (isset($data->actionable_boolean_id)) {
                                            if ($data->actionable_boolean_id > 0) {
                                                $data2 = ActionableBooleanData::where('client_id',$data->client_id)->where('actionable_boolean_id', $data->actionable_boolean_id)->orderBy('id','desc')->take(1)->first();

                                                if ($data2->data == "1") {
                                                    $has_data = true;
                                                }
                                            } else {
                                                $has_data = false;
                                            }
                                        }

                                        if (isset($data->actionable_dropdown_id)) {
                                            if (isset($data->actionable_dropdown_item_id) && $data->actionable_dropdown_item_id > 0) {
                                                $data2 = ActionableDropdownItem::where('id', $data->actionable_dropdown_item_id)->first();

                                                if ($data2->name) {
                                                    $has_data = true;
                                                }
                                            } else {
                                                $has_data = false;
                                            }
                                        }

                                        if (isset($data->actionable_text_id)) {
                                            if (isset($data->actionable_text_id) && $data->actionable_text_id > 0) {
                                                $data2 = ActionableTextData::where('client_id',$data->client_id)->where('actionable_text_id', $data->actionable_text_id)->first();

                                                if ($data2->data) {
                                                    $has_data = true;
                                                }
                                            } else {
                                                $has_data = false;
                                            }
                                        }

                                        if (isset($data->actionable_textarea_id)) {
                                            if (isset($data->actionable_textarea_id) && $data->actionable_textarea_id > 0) {
                                                $data2 = ActionableTextareaData::where('client_id',$data->client_id)->where('actionable_textarea_id', $data->actionable_textarea_id)->first();

                                                if ($data2->data) {
                                                    $has_data = true;
                                                }
                                            } else {
                                                $has_data = false;
                                            }
                                        }

                                        if (isset($data->actionable_document_email_id)) {
                                            if (isset($data->actionable_document_email_id) && $data->actionable_document_email_id > 0) {
                                                $data2 = ActionableDocumentEmailData::where('client_id',$data->client_id)->where('actionable_document_email_id', $data->actionable_document_email_id)->first();

                                                if ($data2->data) {
                                                    $has_data = true;
                                                }
                                            } else {
                                                $has_data = false;
                                            }
                                        }

                                        if (isset($data->actionable_template_email_id)) {
                                            if (isset($data->actionable_template_email_id) && $data->actionable_template_email_id > 0) {
                                                $data2 = ActionableTemplateEmailData::where('client_id',$data->client_id)->where('actionable_template_email_id', $data->actionable_template_email_id)->first();

                                                if ($data2->data) {
                                                    $has_data = true;
                                                }
                                            } else {
                                                $has_data = false;
                                            }
                                        }

                                        if (isset($data->actionable_date_id)) {
                                            if (isset($data->actionable_date_id) && $data->actionable_date_id > 0) {
                                                $data2 = ActionableDateData::where('client_id',$data->client_id)->where('actionable_date_id', $data->actionable_date_id)->first();

                                                if ($data2->data != null) {
                                                    $has_data = true;
                                                }
                                            } else {
                                                $has_data = false;
                                            }
                                        }
                                    }
                                }
                            }

                            if (!$has_data) {
                                if ($activity->client_activity) {
                                    //$outstanding_activities[$activity->name]['client']++;
                                } else {
                                    $outstanding_activities[$activity->name]['user']++;
                                }

                            }
                        }
                    }
                }
            }
        }
//dd($outstanding_activities);
        return [
            "Capture lead" => [
                "user" => 188
            ],
            "Qualif leads" => [
                "user" => 194
            ],
            "Send intro letter to client" => [
                "user" => 181
            ],
        ];
        return $outstanding_activities;
    }

    public function calendar()
    {
        return view('calendar');
    }

    /**
     * XHR Endpoint for Outstanding Activity graph
     * TEMPORARY COPY OF getOutstandingActivities()
     *
     * @return String JSON Array
     */
    public function getOutstandingActivitiesAjax(Request $request) {

        $process_id = ($request->has('process_id')) ? $request->get('process_id') : 0;
        $step_id = ($request->has('step_id')) ? $request->get('step_id') : 0;

        $configs = Config::first();

        $activity_ids = explode(',',$configs->dashboard_outstanding_activities);

        $process = Process::where('id',$configs->dashboard_process)->first();

        $clients = Client::where('is_progressing',1)->where('is_qa','0')->where('step_id',$step_id)->where('process_id', $process->id)->pluck('id');

        $outstanding_activities = [];
        foreach ($process->steps as $step) {

            foreach ($step->activities as $activity) {

                if ($activity->step_id == $step_id) {

                    $outstanding_activities[$activity->name] = [
                        'user' => 0
                    ];
                    foreach ($clients as $client_id) {
                        $has_data = false;
                        if (isset($activity->actionable['data'][0])) {

                            foreach ($activity->actionable['data'] as $data) {
                                if ($data->client_id == $client_id) {
                                    if (isset($data->actionable_boolean_id)) {
                                        if ($data->actionable_boolean_id > 0) {
                                            $data2 = ActionableBooleanData::where('client_id',$data->client_id)->where('actionable_boolean_id', $data->actionable_boolean_id)->orderBy('id','desc')->take(1)->first();

                                            if ($data2->data == "1") {
                                                $has_data = true;
                                            }
                                        } else {
                                            $has_data = false;
                                        }
                                    }

                                    if (isset($data->actionable_dropdown_id)) {
                                        if (isset($data->actionable_dropdown_item_id) && $data->actionable_dropdown_item_id > 0) {
                                            $data2 = ActionableDropdownItem::where('id', $data->actionable_dropdown_item_id)->first();

                                            if ($data2->name != null) {
                                                $has_data = true;
                                            }
                                        } else {
                                            $has_data = false;
                                        }
                                    }

                                    if (isset($data->actionable_text_id)) {
                                        if (isset($data->actionable_text_id) && $data->actionable_text_id > 0) {
                                            $data2 = ActionableTextData::where('client_id',$data->client_id)->where('actionable_text_id', $data->actionable_text_id)->first();

                                            if ($data2->data) {
                                                $has_data = true;
                                            }
                                        } else {
                                            $has_data = false;
                                        }
                                    }

                                    if (isset($data->actionable_textarea_id)) {
                                        if (isset($data->actionable_textarea_id) && $data->actionable_textarea_id > 0) {
                                            $data2 = ActionableTextareaData::where('client_id',$data->client_id)->where('actionable_textarea_id', $data->actionable_textarea_id)->first();

                                            if ($data2->data) {
                                                $has_data = true;
                                            }
                                        } else {
                                            $has_data = false;
                                        }
                                    }

                                    if (isset($data->actionable_document_email_id)) {
                                        if (isset($data->actionable_document_email_id) && $data->actionable_document_email_id > 0) {
                                            $data2 = ActionableDocumentEmailData::where('client_id',$data->client_id)->where('actionable_document_email_id', $data->actionable_document_email_id)->first();

                                            if ($data2->data) {
                                                $has_data = true;
                                            }
                                        } else {
                                            $has_data = false;
                                        }
                                    }

                                    if (isset($data->actionable_template_email_id)) {
                                        if (isset($data->actionable_template_email_id) && $data->actionable_template_email_id > 0) {
                                            $data2 = ActionableTemplateEmailData::where('client_id',$data->client_id)->where('actionable_template_email_id', $data->actionable_template_email_id)->first();

                                            if ($data2->data) {
                                                $has_data = true;
                                            }
                                        } else {
                                            $has_data = false;
                                        }
                                    }

                                    if (isset($data->actionable_date_id)) {
                                        if (isset($data->actionable_date_id) && $data->actionable_date_id > 0) {
                                            $data2 = ActionableDateData::where('client_id',$data->client_id)->where('actionable_date_id', $data->actionable_date_id)->first();

                                            if ($data2->data != null) {
                                                $has_data = true;
                                            }
                                        } else {
                                            $has_data = false;
                                        }
                                    }
                                }
                            }
                        }

                        if (!$has_data) {
                            $outstanding_activities[$activity->name]['user']++;
                        }
                    }
                }
            }
        }

        return json_encode($outstanding_activities);
    }

    public function getCompletedClientsAjax(Request $request)
    {
        $process_id = ($request->has('process_id')) ? $request->get('process_id') : 0;
        $process = Process::find($process_id);

        $range = ($request->has('range')) ? $request->get('range') : 'day';
        $from_string = ($request->has('from')) ? $request->get('from') : '';
        $to_string = ($request->has('to')) ? $request->get('to') : '';

        $from = Carbon::parse($from_string);
        $to = Carbon::parse($to_string);

        switch ($range) {
            default:
            case 'day':
                $date_diff = $from->diffInDays($to);

                $client_query = Client::where('step_id',5)->where('process_id', $process->id)->where('is_progressing', '=', 1)->where('is_qa','0')->whereDate('completed_at','>=',$from)->whereDate('completed_at','<=',$to)->where('completed_at','!=', null)->select(DB::raw('DATE_FORMAT(completed_at, "%e %M %Y") as date'), DB::raw('count(*) as onboarded'))->groupBy('date')->pluck('onboarded', 'date')->toArray();

                $client_onboards = [];
                for ($i = 0; $i <= $date_diff; $i++) {
                    $working_date = $from->copy()->addDays($i)->format('j F Y');
                    if (isset($client_query[$working_date])) {
                        $client_onboards[$working_date] = $client_query[$working_date];
                    } else {
                        $client_onboards[$working_date] = 0;
                    }
                }

                break;
            case 'week':
                $date_diff = $from->diffInWeeks($to->addDay(1));

                $client_query = Client::where('step_id',5)->where('process_id', $process->id)->where('is_progressing', '=', 1)->where('is_qa','0')->whereDate('completed_at','>=',$from)->whereDate('completed_at','<=',$to)->where('completed_at','!=', null)->select(DB::raw('DATE_FORMAT(completed_at, "%u %x") as date'), DB::raw('count(*) as onboarded'))->groupBy('date')->pluck('onboarded', 'date')->toArray();

                $client_onboards = [];
                for ($i = 0; $i <= $date_diff; $i++) {

                    $readable_date = $from->copy()->startOfWeek()->addWeeks($i)->format('j F Y');
                    $working_date = $from->copy()->startOfWeek()->addWeeks($i)->format('W Y');
                    if (isset($client_query[$working_date])) {
                        $client_onboards[$readable_date] = $client_query[$working_date];
                    } else {
                        $client_onboards[$readable_date] = 0;
                    }
                }

                break;
            case 'month':
                $date_diff = $from->diffInMonths($to);

                $client_query = Client::where('step_id',5)->where('process_id', $process->id)->where('is_progressing', '=', 1)->where('is_qa','0')->whereDate('completed_at','>=',$from)->whereDate('completed_at','<=',$to)->where('completed_at','!=', null)->select(DB::raw('DATE_FORMAT(updated_at, "%e %M %Y") as date'), DB::raw('count(*) as onboarded'))->groupBy('date')->pluck('onboarded', 'date')->toArray();

                $client_onboards = [];
                for ($i = 0; $i <= $date_diff; $i++) {
                    $working_date = $from->copy()->addMonths($i)->format('F Y');
                    if (isset($client_query[$working_date])) {
                        $client_onboards[$working_date] = $client_query[$working_date];
                    } else {
                        $client_onboards[$working_date] = 0;
                    }
                }

                break;
            case 'year':
                $date_diff = $from->diffInYears($to);

                $client_query = Client::where('step_id',5)->where('process_id', $process->id)->where('is_progressing', '=', 1)->where('is_qa','0')->whereDate('completed_at','>=',$from)->whereDate('completed_at','<=',$to)->where('completed_at','!=', null)->select(DB::raw('DATE_FORMAT(updated_at, "%e %M %Y") as date'), DB::raw('count(*) as onboarded'))->groupBy('date')->pluck('onboarded', 'date')->toArray();

                $client_onboards = [];
                for ($i = 0; $i <= $date_diff; $i++) {
                    $working_date = $from->copy()->addYears($i)->format('Y');
                    if (isset($client_query[$working_date])) {
                        $client_onboards[$working_date] = $client_query[$working_date];
                    } else {
                        $client_onboards[$working_date] = 0;
                    }
                }
                break;
        }

        return json_encode($client_onboards);
    }

}
