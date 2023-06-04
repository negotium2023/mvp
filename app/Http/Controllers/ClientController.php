<?php

namespace App\Http\Controllers;

use App\ActionableBooleanData;
use App\ActionableDateData;
use App\ActionableDropdownData;
use App\ActionableDropdownItem;
use App\ActionableMultipleAttachmentData;
use App\ActionableNotificationData;
use App\ActionableDocumentData;
use App\ActionableTextData;
use App\ActionableTextareaData;
use App\ActionableTemplateEmailData;
use App\ActionableDocumentEmailData;
use App\ActionableIntegerData;
use App\ActionablePercentageData;
use App\ActionableAmountData;
use App\Actions;
use App\ActionsAssigned;
use App\Activity;
use App\ActivityInClientBasket;
use App\ActivityLog;
use App\ActivityStepVisibilityRule;
use App\ActivityVisibilityRule;
use App\BillboardMessage;
use App\Client;
use App\ClientActivity;
use App\ClientComment;
use App\ClientCRFForm;
use App\ClientHelper;
use App\ClientProcess;
use App\ClientUser;
use App\ClientVisibleActivity;
use App\ClientVisibleStep;
use App\Committee;
use App\Config;
use App\Document;
use App\EmailTemplate;
use App\Events\NotificationEvent;
use App\FormInputAmountData;
use App\FormInputCheckboxData;
use App\FormInputDropdownData;
use App\FormInputIntegerData;
use App\FormInputPercentageData;
use App\FormInputRadioData;
use App\FormLog;
use App\Forms;
use App\FormSection;
use App\HelperFunction;
use App\Http\Requests\StoreClientFormRequest;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\StoreFollowRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Log;
use App\Mail\AssignedConsultantNotify;
use App\Mail\NotificationMail;
use App\MailAttachmentLog;
use App\MailLog;
use App\Notification;
use App\OfficeUser;
use App\Process;
use App\ProcessArea;
use App\Project;
use App\Referrer;
use App\RelatedPartiesTree;
use App\RelatedParty;
use App\Step;
use App\Template;
use App\TriggerType;
use App\UserNotification;
use App\WhatsappTemplate;
use Carbon\Carbon;
use Faker\Provider\DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\TemplateMail;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\User;
use App\ClientForm;
use App\BusinessUnits;
use App\ClientPortal;
use App\Presentation;
use App\FormInputTextData;
use App\FormInputBooleanData;
use App\FormInputDateData;
use App\FormInputTextareaData;
use App\Mail\ActivateLoginForClient;
use LasseRafn\InitialAvatarGenerator\InitialAvatar;
use App\Mail\ClientMail;
use App\Mail\SendLoginForClient;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    private $helper;
    public function __construct()
    {
        $this->middleware('auth')->except(['sendtemplate', 'sendnotification']);
        $this->middleware('auth:api')->only(['sendtemplate', 'sendnotification']);
        $this->middleware('permission:maintain_client')->except(['create', 'store']);

        $this->progress_colours = [
            'not_started' => 'background-color: rgba(64,159,255, 0.15)',
            'started' => 'background-color: rgba(255,255,70, 0.15)',
            'progress_completed' => 'background-color: rgba(60,255,70, 0.15)',
        ];

        $this->helper = new ClientHelper();
    }

    public function index(Request $request)
    {
        $client = new Client();
        $client_birthdays = $client->getBirthdaysTodayTomorrow($request);
        $offices = OfficeUser::select('office_id')->where('user_id',Auth::id())->get();
        $user_offices = [];

        foreach($offices as $office){
            array_push($user_offices,$office->office_id);
        }

        $np = 0;
        $qa = 0;

        $clients = new Client();
        $clients->unHide();

        $clients = $clients->with(['processes','referrer', 'process.steps.activities.actionable.data', 'introducer', 'consultant', 'committee', 'project'])->select('*', DB::raw('CONCAT(first_name," ",COALESCE(`last_name`,"")) as full_name'),
            DB::raw('ROUND(DATEDIFF(completed_at, created_at), 0) as completed_days'),
            DB::raw('CAST(AES_DECRYPT(`hash_company`, "Qwfe345dgfdg") AS CHAR(50)) hash_company'),
            DB::raw('CAST(AES_DECRYPT(`hash_first_name`, "Qwfe345dgfdg") AS CHAR(50)) hash_first_name'),
            DB::raw('CAST(AES_DECRYPT(`hash_last_name`, "Qwfe345dgfdg") AS CHAR(50)) hash_last_name'),
            DB::raw('CAST(AES_DECRYPT(`hash_cif_code`, "Qwfe345dgfdg") AS CHAR(50)) hash_cif_code'),
            DB::raw('CAST(`case_number` AS CHAR(50)) AS case_number'),
            DB::raw('CAST(`contact` AS CHAR(50)) AS contact'),
            DB::raw('CAST(`reference` AS CHAR(50)) AS reference'),
            DB::raw('CAST(`email` AS CHAR(50)) AS email')
        )->whereHas('processes');

        if (Auth::check() && Auth::user()->is('consultant')){
            $clients = $clients->where('consultant_id', \auth()->id());
        }

        $clients = $clients->whereIn('office_id', collect($offices)->toArray());

        if ($request->has('q') && $request->input('q') != '') {
            $search_array = explode(' ', $request->q);

            foreach ($search_array as $search) {
                $clients = $clients->where('first_name', 'like', '%' . $request->input('q') . '%')
                    ->orWhere('last_name', 'like', '%' . $request->input('q') . '%')
                    ->orWhere(DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'like', '%' . $request->input('q') . '%')
                    ->orWhere('hash_company', 'like', '%' . $request->input('q') . '%')
                    ->orWhere('hash_first_name', 'like', '%' . $request->input('q') . '%')
                    ->orWhere('hash_last_name', 'like', '%' . $request->input('q') . '%')
                    ->orWhere('hash_cif_code', 'like', '%' . $request->input('q') . '%')
                    ->orWhere('email', 'like', '%' . $request->input('q') . '%')
                    ->orWhere('contact', 'like', '%' . $request->input('q') . '%')
                    ->orWhere('reference', 'like', '%' . $request->input('q') . '%')
                    ->orWhere('case_number', 'like', '%' . $request->input('q') . '%');
            }
        }

        if($request->has('c') && $request->input('c') == 'yes') {
            $clients = $clients->whereHas('processes',function($q){
                $q->whereNotNull('completed_at');
            });
        } elseif($request->has('c') && $request->input('c') == 'no') {
            $clients = $clients->whereHas('processes',function($q){
                $q->whereNull('completed_at');
            });
        }

        if ($request->has('f') && $request->input('f') != '') {
            $clients = $clients->where('instruction_date','>=',$request->input('f'));
        }

        if ($request->has('t') && $request->input('t') != '') {
            $clients = $clients->where('instruction_date','<=',$request->input('t'));
        }

        $clients = $clients->paginate(15);

        if ($request->has('step') && $request->input('step') != 'all' && $request->input('step') != '') {
            if ($request->input('step') == 1000) {
                if (($request->has('f') && $request->input('f') != '') && ($request->has('t') && $request->input('t') != '')){
                    return redirect('clients?c=no&f='.$request->input('f').'&t='.$request->input('t'));
                } else {
                    return redirect('clients?c=no');
                }
            }
            if ($request->input('step') == 1001) {
                if (($request->has('f') && $request->input('f') != '') && ($request->has('t') && $request->input('t') != '')){
                    return redirect('clients?c=yes&f='.$request->input('f').'&t='.$request->input('t'));
                } else {
                    return redirect('clients?c=yes');
                }
            }
            if ($request->input('step') == 1002) {
                return redirect('clients?qa=yes');
            }
        }

        $parameters = [
            'np' => $np,
            'qa' => $qa,
            'client_list' => Client::select('id', DB::raw('CONCAT(first_name," ",COALESCE(`last_name`,"")) as full_name'))->whereIn('office_id', collect($offices)->toArray())->pluck('full_name','id')->prepend('Please select',''),
            'messages' => BillboardMessage::whereHas('client')->where('status_id',1)->orderBy('id','desc')->whereIn('office_id',collect($offices)->toArray())->get(),
            // 'clients_birthdays' => (isset($client_birthday_arr) ? (empty($cb_array) ? $client_birthday_arr : $cb_array) : []),
            'clients_birthdays' => $client_birthdays,
            'clients' => $clients,
            // 'clients' => (isset($client_arr) ? (empty($c_array) ? $client_arr : $c_array) : []),
            'in_details_basket' => (isset($client) ? $this->helper->detailedClientBasket($client, 1)['cd'] : []),
            'message_users' => User::where('id', '!=', Auth::id())->get(),
            'whatsapp_templates' => WhatsappTemplate::pluck('name','id')->prepend('Select',''),
            'user_offices' => $user_offices
        ];

        return view('client.index')->with($parameters);
    }

    /**
     * Display client create form with parameters
     *
     * @param Client $client
     * @return void
     */
    public function create()
    {
        //TODO show process steps on process selection

        $offices = OfficeUser::select('office_id')->where('user_id',Auth::id())->get();

        $office_processes = ProcessArea::select('process_id')->whereIn('office_id',collect($offices)->toArray())->get();

        $last_process = Client::where('referrer_id', auth()->id())->orderBy('created_at', 'desc')->first();

        if ($last_process) {
            $last_process = $last_process->process_id;
        } else {
            $last_process = '';
        }

        $processes = [];

        $cps = Process::with('pgroup')->whereIn('id',collect($office_processes)->toArray())->orWhere('global',1)->where('process_type_id',1)->get();

        //dd($cps);

        foreach($cps as $cp){
            if(isset($cp->pgroup)) {
                $processes[$cp->pgroup->name][] = [
                    "id" => $cp->id,
                    "name" => $cp->name
                ];
            } else {
                $processes['None'][] = [
                    "id" => $cp->id,
                    "name" => $cp->name
                ];
            }
        }

        ksort($processes);

        $configs = Config::first();
        /*$referrers = Referrer::select(DB::raw("CONCAT(first_name,' ',COALESCE(`last_name`,'')) AS full_name"), 'id')->orderBy('first_name')->orderBy('last_name')->pluck('full_name', 'id');
        $businessunits = BusinessUnits::all()->pluck('name', 'id');
        $triggertype= TriggerType::all()->pluck('name', 'id')->prepend('Select','0');
        $project = Project::where('name','!=','')->whereNotNull('name')->get();
        $committee = Committee::where('name','!=','')->whereNotNull('name')->pluck('name','id')->prepend('Select','0');*/

        $inputs = new Forms();
        $forms = $inputs->getClientDetailsInputs(2);
//dd($forms);
        $parameters = [
            'config' => $configs,
            'processes' => $processes,
            'last_process' => $last_process,
            /*'referrers' => $referrers,
            'businessunits' => $businessunits,
            'triggertype' => $triggertype,
            'project' => $project,
            'committee' => $committee,*/
            /*'projects_down_down' => $project->keyBy('id')->map(function($proj){ return $proj->name; })->push('other'),*/
            'forms' => $forms
        ];

        return view('client.create')->with($parameters);
    }

    public function store(StoreClientRequest $request)
    {
        $client = new Client;
        $client->first_name = $request->input('first_name');
        $client->last_name = $request->input('last_name');
        $client->initials = $request->input('initials');
        $client->known_as = $request->input('known_as');
        $client->id_number = $request->input('id_number');
        $client->email = $request->input('email');
        $client->contact = ($request->input('contact') == '' ? '' : str_replace(' ','',substr($request->input('contact'),0,1) == '0' ? '+27'.substr($request->input('contact'),1) : $request->input('contact') ));
        $client->introducer_id = auth()->id();
        $client->office_id = auth()->user()->office()->id ?? 1;
        $client->process_id = $request->input('process');
        $client->reference = $request->input('reference');
        $client->step_id = Step::where('process_id',$request->input('process'))->orderBy('order','asc')->first()->id;
        $client->needs_approval = !auth()->user()->can('maintain_client');
        if(Auth::check() && Auth::user()->is('consultant')){
            $client->consultant_id = Auth::id();
            $client->assigned_date = now();
        }

        $client->save();

        Client::where('id',$client->id)->update([
            'hash_first_name' => DB::raw("AES_ENCRYPT('".addslashes($request->input('first_name'))."','Qwfe345dgfdg')"),
            'hash_last_name' => DB::raw("AES_ENCRYPT('".addslashes($request->input('last_name'))."','Qwfe345dgfdg')"),
            'hash_id_number' => DB::raw("AES_ENCRYPT('".addslashes($request->input('id_number'))."','Qwfe345dgfdg')"),
            'hash_email' => DB::raw("AES_ENCRYPT('".addslashes($request->input('company_email'))."','Qwfe345dgfdg')"),
            'hash_contact' => DB::raw("AES_ENCRYPT('".addslashes($request->input('contact'))."','Qwfe345dgfdg')")
        ]);

        // Call this to autopopulate from CBP, just comment out if you would like to manuaaly fill the forms
        /*if($client->id_number != '') {
            $this->autoPopulateFromCBP($client->id);
        }*/

        $cp = new ClientProcess();
        $cp->client_id = $client->id;
        $cp->process_id = $request->input('process');
        $cp->step_id = Step::where('process_id',$request->input('process'))->orderBy('order','asc')->first()->id;
        $cp->save();

        $extra = Forms::where('id',2)->first();
        if($extra) {
            $sections = FormSection::with('form_section_inputs.input')->where('form_id', $extra->id)->get();
//dd($sections);
            foreach ($sections as $section) {
                foreach ($section->form_section_inputs as $activity) {

                    if ($request->has($activity->id) && !is_null($request->input($activity->id))) {

                        switch ($activity->input_type) {
                            case 'App\FormInputBoolean':
                                FormInputBooleanData::insert([
                                    'data' => $request->input($activity->id),
                                    'form_input_boolean_id' => $activity->input_id,
                                    'client_id' => $client->id,
                                    'user_id' => auth()->id(),
                                    'duration' => 120,
                                    'created_at' => now()
                                ]);
                                break;
                            case 'App\FormInputDate':
                                FormInputDateData::insert([
                                    'data' => $request->input($activity->id),
                                    'form_input_date_id' => $activity->input_id,
                                    'client_id' => $client->id,
                                    'user_id' => auth()->id(),
                                    'duration' => 120,
                                    'created_at' => now()
                                ]);
                                break;
                            case 'App\FormInputText':
                                FormInputTextData::insert([
                                    'data' => $request->input($activity->id),
                                    'form_input_text_id' => $activity->input_id,
                                    'client_id' => $client->id,
                                    'user_id' => auth()->id(),
                                    'duration' => 120,
                                    'created_at' => now()
                                ]);
                                break;
                            case 'App\FormInputPercentage':
                                FormInputPercentageData::insert([
                                    'data' => $request->input($activity->id),
                                    'form_input_percentage_id' => $activity->input_id,
                                    'client_id' => $client->id,
                                    'user_id' => auth()->id(),
                                    'duration' => 120,
                                    'created_at' => now()
                                ]);
                                break;
                            case 'App\FormInputAmount':
                                FormInputAmountData::insert([
                                    'data' => $request->input($activity->id),
                                    'form_input_amount_id' => $activity->input_id,
                                    'client_id' => $client->id,
                                    'user_id' => auth()->id(),
                                    'duration' => 120,
                                    'created_at' => now()
                                ]);
                                break;
                            case 'App\FormInputInteger':
                                FormInputIntegerData::insert([
                                    'data' => $request->input($activity->id),
                                    'form_input_integer_id' => $activity->input_id,
                                    'client_id' => $client->id,
                                    'user_id' => auth()->id(),
                                    'duration' => 120,
                                    'created_at' => now()
                                ]);
                                break;
                            case 'App\FormInputTextarea':
                                FormInputTextareaData::insert([
                                    'data' => $request->input($activity->id),
                                    'form_input_textarea_id' => $activity->input_id,
                                    'client_id' => $client->id,
                                    'user_id' => auth()->id(),
                                    'duration' => 120,
                                    'created_at' => now()
                                ]);
                                break;
                            case 'App\FormInputDropdown':
                                foreach ($request->input($activity->id) as $key => $value) {
                                    FormInputDropdownData::insert([
                                        'form_input_dropdown_id' => $activity->input_id,
                                        'form_input_dropdown_item_id' => $value,
                                        'client_id' => $client->id,
                                        'user_id' => auth()->id(),
                                        'duration' => 120,
                                        'created_at' => now()
                                    ]);
                                }
                                break;
                            default:
                                //todo capture defaults
                                break;
                        }

                    }
                }
            }
        }

        //$adminuser = User::whereHas('roles', function($q){$q->where('name', 'admin');})->get()->toArray();

        //Mail::to($adminuser)->send(new ClientNotifyMail($client));
        return redirect(route('clients.index'))->with('flash_success', 'Client captured successfully');
        /*return redirect(route('clients.show', $client->id))->with('flash_success', 'Client captured successfully');*/
    }

    public function autoPopulateFromCBP($client_id)
    {
        // Auto populate form data from CBP - Block Start
        $bureau = new BureauController();

        // Get IDV
        $IDV = $bureau->getIDVList($client_id);
        $cpbIDV = null;

        if(isset($IDV->getData()->Record->Name)){
            $cpbIDV = $IDV->getData()->Record;
        }

        // Get Email address
        $emailData = $bureau->getEmailList($client_id);
        $cpbEmail = '';
        if(isset($emailData->getData()->Record->EmailAddress)){
            $cpbEmail = $emailData->getData()->Record->EmailAddress;
        }

        // Get Telephone
        $telephoneData = $bureau->getTelephoneList($client_id);
        $cpbTelephone = '';
        if(isset($telephoneData->getData()->Record->TelNumber)){
            $cpbTelephone = $telephoneData->getData()->Record->TelNumber;
        }

        // Get Address
        $AddressData = $bureau->getAddressList($client_id);
        $cpbAddress = null;
        if(isset($AddressData->getData()->Record->StreetLine)){
            $cpbAddress = $AddressData->getData()->Record;
        }

        // Get Employment
        $employmentData = $bureau->getEmploymentList($client_id);
        $employment = null;
        if(isset($employmentData->getData()->Record->EmployerName)){
            $employment = $employmentData->getData()->Record;
        }

        // Get Spouse
        $spouseData = $bureau->getSpouseList($client_id);
        $cpbSpouse = null;
        if(isset($spouseData->getData()->Record->FirstNames)){
            $cpbSpouse = $spouseData->getData()->Record;
        }

        $client = Client::find($client_id);
        if(isset($cpbIDV)) {
            $client->first_name = $client->first_name == '' ? $cpbIDV->Name : $client->first_name;
            $client->last_name = $client->last_name == '' ? $cpbIDV->Surname : $client->last_name;
            $initialsArray = explode(' ', $cpbIDV->Name);
            $initials = '';
            foreach ($initialsArray as $initial){
                $initials .= substr($initial, 0, 1);
            }
            $client->initials = $client->initials == '' ? $initials : $client->initials;
            $maritalStatus = 0;
            if($cpbIDV->MaritalStatus == 'Single'){
                $maritalStatus = 26;
            }
            if($cpbIDV->MaritalStatus == 'Married'){
                $maritalStatus = 25;
            }
            if($cpbIDV->MaritalStatus == 'Divorced'){
                $maritalStatus = 27;
            }
            if($maritalStatus > 0) {
                $actinableDropDownData = new FormInputDropdownData();
                $actinableDropDownData->form_input_dropdown_id = 8;
                $actinableDropDownData->form_input_dropdown_item_id = $maritalStatus;
                $actinableDropDownData->client_id = $client_id;
                $actinableDropDownData->user_id = Auth::id();
                $actinableDropDownData->duration = 120;
                $actinableDropDownData->save();
            }

            // Country of Issue
            $formInputTextData = new FormInputTextData();
            $formInputTextData->data = $cpbIDV->BirthPlaceCountryCode;
            $formInputTextData->form_input_text_id = 18;
            $formInputTextData->client_id = $client_id;
            $formInputTextData->user_id = Auth::id();
            $formInputTextData->duration = 120;
            $formInputTextData->save();
        }

        if(isset($cpbEmail)){
            $client->email = $client->email == '' ? $cpbEmail : $client->email;
        }

        if(isset($cpbTelephone)){
            $client->contact = $client->contact == '' ? $cpbTelephone : $client->contact;
        }

        if(isset($cpbAddress)){
            // Suite/Unit Number
            $formInputTextData = new FormInputTextData();
            $formInputTextData->data = $cpbAddress->BoxLine;
            $formInputTextData->form_input_text_id = 22;
            $formInputTextData->client_id = $client_id;
            $formInputTextData->user_id = Auth::id();
            $formInputTextData->duration = 120;
            $formInputTextData->save();

            // Complex Name
            $formInputTextData = new FormInputTextData();
            $formInputTextData->data = $cpbAddress->BuildingLine;
            $formInputTextData->form_input_text_id = 23;
            $formInputTextData->client_id = $client_id;
            $formInputTextData->user_id = Auth::id();
            $formInputTextData->duration = 120;
            $formInputTextData->save();

            // Street Number
            $formInputTextData = new FormInputTextData();
            $formInputTextData->data = $cpbAddress->StreetNumber;
            $formInputTextData->form_input_text_id = 24;
            $formInputTextData->client_id = $client_id;
            $formInputTextData->user_id = Auth::id();
            $formInputTextData->duration = 120;
            $formInputTextData->save();

            // Street Name
            $formInputTextData = new FormInputTextData();
            $formInputTextData->data = $cpbAddress->StreetName;
            $formInputTextData->form_input_text_id = 25;
            $formInputTextData->client_id = $client_id;
            $formInputTextData->user_id = Auth::id();
            $formInputTextData->duration = 120;
            $formInputTextData->save();

            // Suburb
            $formInputTextData = new FormInputTextData();
            $formInputTextData->data = $cpbAddress->Suburb;
            $formInputTextData->form_input_text_id = 26;
            $formInputTextData->client_id = $client_id;
            $formInputTextData->user_id = Auth::id();
            $formInputTextData->duration = 120;
            $formInputTextData->save();

            // City
            $formInputTextData = new FormInputTextData();
            $formInputTextData->data = $cpbAddress->Suburb;
            $formInputTextData->form_input_text_id = 27;
            $formInputTextData->client_id = $client_id;
            $formInputTextData->user_id = Auth::id();
            $formInputTextData->duration = 120;
            $formInputTextData->save();

            // Region
            $formInputTextData = new FormInputTextData();
            $formInputTextData->data = $cpbAddress->Province;
            $formInputTextData->form_input_text_id = 28;
            $formInputTextData->client_id = $client_id;
            $formInputTextData->user_id = Auth::id();
            $formInputTextData->duration = 120;
            $formInputTextData->save();

            // Code
            $formInputTextData = new FormInputTextData();
            $formInputTextData->data = $cpbAddress->PostCode;
            $formInputTextData->form_input_text_id = 101;
            $formInputTextData->client_id = $client_id;
            $formInputTextData->user_id = Auth::id();
            $formInputTextData->duration = 120;
            $formInputTextData->save();

            // $cpbAddress->StreetLine;
            // $cpbAddress->Town;
            // $cpbAddress->Country;
        }

        if(isset($cpbSpouse)){
            // Spouse ID Number
            $formInputTextData = new FormInputTextData();
            $formInputTextData->data = $cpbSpouse->RelativesID;
            $formInputTextData->form_input_text_id = 44;
            $formInputTextData->client_id = $client_id;
            $formInputTextData->user_id = Auth::id();
            $formInputTextData->duration = 120;
            $formInputTextData->save();

            // Spouse FirstNames
            $formInputTextData = new FormInputTextData();
            $formInputTextData->data = $cpbSpouse->FirstNames;
            $formInputTextData->form_input_text_id = 40;
            $formInputTextData->client_id = $client_id;
            $formInputTextData->user_id = Auth::id();
            $formInputTextData->duration = 120;
            $formInputTextData->save();

            // Spouse Surname
            $formInputTextData = new FormInputTextData();
            $formInputTextData->data = $cpbSpouse->Surname;
            $formInputTextData->form_input_text_id = 40;
            $formInputTextData->client_id = $client_id;
            $formInputTextData->user_id = Auth::id();
            $formInputTextData->duration = 120;
            $formInputTextData->save();
        }

        $client->save();

        // Auto populate form data from CBP - Block End
    }

    /**
     * Display client edit form with parameters
     *
     * @param Client $client
     * @return void
     */
    public function edit(Client $client,$process_id,$step_id)
    {
        $offices = OfficeUser::select('office_id')->where('user_id',Auth::id())->get();

        $user_offices = [];

        foreach($offices as $office){
            array_push($user_offices,$office->office_id);
        }

        $office_processes = ProcessArea::select('process_id')->whereIn('office_id',collect($offices)->toArray())->get();

        $last_process = Client::where('referrer_id', auth()->id())->orderBy('created_at', 'desc')->first();

        if ($last_process) {
            $last_process = $last_process->process_id;
        } else {
            $last_process = '';
        }

        $sections = $tmp = FormSection::with('form_section_inputs')->where('form_id', 2)->get();

        $cd = $this->helper->clientBucketDetailIds($sections ,$client);

        $processes = [];

        $cps = Process::with('pgroup')->whereIn('id',collect($office_processes)->toArray())->orWhere('global',1)->where('process_type_id',1)->get();

        //dd($cps);
        foreach($cps as $cp){
            if(isset($cp->pgroup)) {
                $processes[$cp->pgroup->name][] = [
                    "id" => $cp->id,
                    "name" => $cp->name
                ];
            } else {
                $processes['None'][] = [
                    "id" => $cp->id,
                    "name" => $cp->name
                ];
            }
        }

        ksort($processes);

        $referrers = Referrer::select(DB::raw("CONCAT(first_name,' ',COALESCE(`last_name`,'')) AS full_name"), 'id')->orderBy('first_name')->orderBy('last_name')->pluck('full_name', 'id');
        $businessunits = BusinessUnits::all()->pluck('name', 'id');
        $triggertype= TriggerType::all()->pluck('name', 'id')->prepend('Select','0');
        $project = Project::where('name','!=','')->whereNotNull('name')->get();
        $committee = Committee::where('name','!=','')->whereNotNull('name')->pluck('name','id')->prepend('Select','0');
        $smart_id = $this->smartID($client);

        $forms = [];

        $form = Forms::find(2);
        if($form) {
            $forms = $form->getClientDetailsInputValues($client->id, $form->id);
        }
//dd($this->helper->clientDetails($client, 1)['forms']);
        $parameters = [
            'client' => $client,
            'processes' => $processes,
            'last_process' => $last_process,
            'referrers' => $referrers,
            'businessunits' => $businessunits,
            'triggertype' => $triggertype,
            'project' => $project,
            'committee' => $committee,
            'process_id' => $process_id,
            'step' => Step::where('id',$step_id)->first()->toArray(),
            'projects_down_down' => $project->keyBy('id')->map(function($proj){ return $proj->name; })->push('other'),
            'date_of_birth' => $smart_id['date'],
            'gender' => $smart_id['gender'],
            'citizenship' => $smart_id['citizenship'],
            'forms'=>$forms,
            'client_details' => $this->helper->clientDetails($client, 1)['forms'],
            'cd' => $cd,
            'message_users' => User::where('id', '!=', Auth::id())->get(),
            'in_details_basket' => $this->helper->detailedClientBasket($client, 1)['cd'],
            'number_to_word' => ['1'=>'One','2'=>'Two','3'=>'three','4'=>'Four'],
            'whatsapp_templates' => WhatsappTemplate::pluck('name','id')->prepend('Select',''),
            'user_offices' => $user_offices,
            'client_list' => Client::select('id', DB::raw('CONCAT(first_name," ",COALESCE(`last_name`,"")) as full_name'))->whereIn('office_id', collect($offices)->toArray())->pluck('full_name','id')->prepend('Please select',''),
        ];

        return view('client.edit')->with($parameters);
    }

    /**
     * Update client details from Request
     *
     * @param UpdateClientRequest $request
     * @param Client $client
     * @return void
     */
    public function update(UpdateClientRequest $request, Client $client)
    {

        //dd($request->input());
        if($request->has('first_name')) {
            $mclient = Client::find($client->id);
            $mclient->first_name = $request->input('first_name');
            $mclient->last_name = $request->input('last_name');
            $mclient->initials = $request->input('initials');
            $mclient->known_as = $request->input('known_as');
            $mclient->id_number = $request->input('id_number');
            $mclient->email = $request->input('email');
            $mclient->contact = $request->input('contact');
            $mclient->reference = $request->input('reference');
            $mclient->save();

            Client::where('id', $client->id)->update([
                'hash_first_name' => DB::raw("AES_ENCRYPT('" . addslashes($request->input('first_name')) . "','Qwfe345dgfdg')"),
                'hash_last_name' => DB::raw("AES_ENCRYPT('" . addslashes($request->input('last_name')) . "','Qwfe345dgfdg')"),
                'hash_id_number' => DB::raw("AES_ENCRYPT('" . addslashes($request->input('id_number')) . "','Qwfe345dgfdg')"),
                'hash_email' => DB::raw("AES_ENCRYPT('" . addslashes($request->input('company_email')) . "','Qwfe345dgfdg')"),
                'hash_contact' => DB::raw("AES_ENCRYPT('" . addslashes($request->input('contact')) . "','Qwfe345dgfdg')")
            ]);
        }

        $forms = FormSection::where('form_id',2)->get();

        if($forms) {
            foreach ($forms as $form) {
                $id = $client->id;
                $form_section = FormSection::find($form->id);
                $form_section = $form_section->load(['form_section_inputs.input.data' => function ($query) use ($id) {
                    $query->where('client_id', $id);
                }]);

                $all_activities_completed = false;
                foreach ($form_section->form_section_inputs as $activity) {

                    if (is_null($request->input($activity->id))) {
                        if ($request->input('old_' . $activity->id) != $request->input($activity->id)) {

                            if (is_array($request->input($activity->id))) {

                                $old = explode(',', $request->input('old_' . $activity->id));
                                $diff = array_diff($old, $request->input($activity->id));
                                //dd($diff);

                            } else {
                                $old = $request->input('old_' . $activity->id);

                            }

                            switch ($activity->input_type) {
                                case 'App\FormInputBoolean':
                                    FormInputBooleanData::where('form_input_boolean_id', $activity->input_id)->where('client_id', $client->id)->delete();

                                    break;
                                case 'App\FormInputDate':
                                    FormInputDateData::where('form_input_date_id', $activity->input_id)->where('client_id', $client->id)->delete();

                                    break;
                                case 'App\FormInputText':
                                    FormInputTextData::where('form_input_text_id', $activity->input_id)->where('client_id', $client->id)->delete();

                                    break;
                                case 'App\FormInputAmount':
                                    FormInputAmountData::where('form_input_amount_id', $activity->input_id)->where('client_id', $client->id)->delete();

                                    break;
                                case 'App\FormInputPercentage':
                                    FormInputPercentageData::where('form_input_percentage_id', $activity->input_id)->where('client_id', $client->id)->delete();

                                    break;
                                case 'App\FormInputInteger':
                                    FormInputIntegerData::where('form_input_integer_id', $activity->input_id)->where('client_id', $client->id)->delete();

                                    break;
                                case 'App\FormInputTextarea':
                                    FormInputTextareaData::where('form_input_textarea_id', $activity->input_id)->where('client_id', $client->id)->delete();

                                    break;
                                case 'App\FormInputDropdown':
                                    FormInputDropdownData::where('form_input_dropdown_id', $activity->input_id)->where('client_id', $client->id)->delete();

                                    break;
                                default:
                                    //todo capture defaults
                                    break;
                            }
                        }
                    }

                    if ($request->has($activity->id) && !is_null($request->input($activity->id))) {
                        //If value did not change, do not save it again or add it to log
                        if ($request->input('old_' . $activity->id) === $request->input($activity->id)) {
                            continue;
                        }
                        if (is_array($request->input($activity->id))) {

                            $old = explode(',', $request->input('old_' . $activity->id));
                            $diff = array_diff($old, $request->input($activity->id));
                            //dd($diff);

                        } else {
                            $old = $request->input('old_' . $activity->id);
                        }

                        switch ($activity->input_type) {
                            case 'App\FormInputBoolean':
                                FormInputBooleanData::where('client_id', $client->id)->where('form_input_boolean_id', $activity->input_id)->where('data', $old)->delete();

                                FormInputBooleanData::insert([
                                    'data' => $request->input($activity->id),
                                    'form_input_boolean_id' => $activity->input_id,
                                    'client_id' => $client->id,
                                    'user_id' => auth()->id(),
                                    'duration' => 120,
                                    'created_at' => now()
                                ]);
                                break;
                            case 'App\FormInputDate':
                                FormInputDateData::insert([
                                    'data' => $request->input($activity->id),
                                    'form_input_date_id' => $activity->input_id,
                                    'client_id' => $client->id,
                                    'user_id' => auth()->id(),
                                    'duration' => 120,
                                    'created_at' => now()
                                ]);
                                break;
                            case 'App\FormInputText':

                                FormInputTextData::insert([
                                    'data' => $request->input($activity->id),
                                    'form_input_text_id' => $activity->input_id,
                                    'client_id' => $client->id,
                                    'user_id' => auth()->id(),
                                    'duration' => 120,
                                    'created_at' => now()
                                ]);
                                break;
                            case 'App\FormInputAmount':

                                FormInputAmountData::insert([
                                    'data' => $request->input($activity->id),
                                    'form_input_amount_id' => $activity->input_id,
                                    'client_id' => $client->id,
                                    'user_id' => auth()->id(),
                                    'duration' => 120,
                                    'created_at' => now()
                                ]);
                                break;
                            case 'App\FormInputPercentage':
                                FormInputPercentageData::insert([
                                    'data' => $request->input($activity->id),
                                    'form_input_percentage_id' => $activity->input_id,
                                    'client_id' => $client->id,
                                    'user_id' => auth()->id(),
                                    'duration' => 120,
                                    'created_at' => now()
                                ]);
                                break;
                            case 'App\FormInputInteger':

                                FormInputIntegerData::insert([
                                    'data' => $request->input($activity->id),
                                    'form_input_integer_id' => $activity->input_id,
                                    'client_id' => $client->id,
                                    'user_id' => auth()->id(),
                                    'duration' => 120,
                                    'created_at' => now()
                                ]);
                                break;
                            case 'App\FormInputTextarea':

                                FormInputTextareaData::insert([
                                    'data' => $request->input($activity->id),
                                    'form_input_textarea_id' => $activity->input_id,
                                    'client_id' => $client->id,
                                    'user_id' => auth()->id(),
                                    'duration' => 120,
                                    'created_at' => now()
                                ]);
                                break;
                            case 'App\FormInputDropdown':
                                foreach ($request->input($activity->id) as $key => $value) {
                                    if (in_array($value, $old, true)) {

                                    } else {
                                        FormInputDropdownData::insert([
                                            'form_input_dropdown_id' => $activity->input_id,
                                            'form_input_dropdown_item_id' => $value,
                                            'client_id' => $client->id,
                                            'user_id' => auth()->id(),
                                            'duration' => 120,
                                            'created_at' => now()
                                        ]);
                                    }

                                    if (!empty($diff)) {
                                        FormInputDropdownData::where('client_id', $client->id)->where('form_input_dropdown_id', $activity->input_id)->whereIn('form_input_dropdown_item_id', $diff)->delete();
                                    }
                                }
                                break;
                            default:
                                //todo capture defaults
                                break;
                        }

                    }
                }
            }
        }
        return redirect()->back()->with(['flash_success' => "Client updated successfully."]);
    }

    /**
     * Delete client from Request
     *
     * @param UpdateClientRequest $request
     * @param Client $client
     * @return void
     */
    public function destroy($id)
    {
        Client::destroy($id);
        return redirect()->route("clients.index")->with('flash_success','Client deleted successfully');

    }

    /**
     * Restor client from Request
     *
     * @param UpdateClientRequest $request
     * @param Client $client
     * @return void
     */
    public function restore($id)
    {
        Client::onlyTrashed()->where('id',$id)->restore();

        return redirect('clients')->with('flash_success','Client successfully restored');

    }

    /** **/
    /*public function show(Request $request,$client_id,$process_id,$step_id)
    {
        $client = Client::find($client_id);

        $process = Process::find($process_id);


        if($step_id != null) {
            $step = Step::withTrashed()->find($step_id);
        } else {
            $s = Step::where('process_id',$process_id)->orderBy('order')->first()->withTrashed();
            $step = Step::withTrashed()->find($s->id);
        }
        $activity_step = $step;

        $action_id = null;

        $parameters = $this->clientProcessProgress($client,$process,$step,$request,$activity_step, $action_id);

        return view('client.overview')->with($parameters);
    }*/

    /**
     * Display client overview from Request
     *
     * @param UpdateClientRequest $request
     * @param Client $client
     * @return void
     */
    public function overview(Request $request,$client_id,$process_id,$step_id)
    {
        $client = Client::find($client_id);

        $process = Process::withTrashed()->find($process_id);

        /*$client_process = ClientProcess::where('client_id',$client->id)->where('process_id',$process->id)->first();*/

        if($step_id != null) {
            $step = Step::withTrashed()->find($step_id);
        } else {
            $s = Step::where('process_id',$process_id)->orderBy('order')->first()->withTrashed();
            $step = Step::withTrashed()->find($s->id);
        }
        $activity_step = $step;

        $action_id = null;

        $parameters = $this->clientProcessProgress($client,$process,$step,$request,$activity_step, $action_id);

        return view('client.overview')->with($parameters);
    }

    /**
     * Display client details from Request
     *
     * @param UpdateClientRequest $request
     * @param Client $client
     * @return void
     */
    public function details(Request $request,$client_id,$process_id,$step_id)
    {
        $client = Client::find($client_id);

        $process = Process::withTrashed()->find($process_id);

        /*$client_process = ClientProcess::where('client_id',$client->id)->where('process_id',$process->id)->first();*/

        if($step_id != null) {
            $step = Step::withTrashed()->find($step_id);
        } else {
            $s = Step::where('process_id',$process_id)->orderBy('order')->first()->withTrashed();
            $step = Step::withTrashed()->find($s->id);
        }

        $activity_step = $step;

        $action_id = null;

        $parameters = $this->clientProcessProgress($client,$process,$step,$request,$activity_step, $action_id);

        return view('client.details')->with($parameters);
    }

    private function smartID(Client $client){
        if (isset($client->id_number)){
            $partial_d_o_b = substr($client->id_number, 0,2).'-'.substr($client->id_number, 2,2).'-'.substr($client->id_number, 4,2);
            $date_of_birth = ((substr($client->id_number, 0,2) > 45) && (substr($client->id_number, 0,2) <= 99))?'19'.$partial_d_o_b:'20'.$partial_d_o_b;
            $gender = (substr($client->id_number,6,4) < 5000) ? "Female" : '';
            $gender = (substr($client->id_number,6,4) >= 5000) ? "Male":'';
            $citizenship = substr($client->id_number, 10,1) ? "Permanent Resident" : "SA Citizen";
        }else{
            $date_of_birth = '';
            $gender = '';
            $citizenship = '';
        }

        return [
            'date' => $date_of_birth,
            'gender' => $gender,
            'citizenship' => $citizenship
        ];

    }

    public function processes(Request $request,Client $client,$process_id,$step_id){

        $process = Process::withTrashed()->find($process_id);

        if($step_id != null) {
            $step = Step::withTrashed()->find($step_id);
        } else {
            $s = Step::where('process_id',$process_id)->orderBy('order')->first()->withTrashed();
            $step = Step::withTrashed()->find($s->id);
        }
        $activity_step = $step;

        $action_id = null;

        $parameters = $this->clientProcessProgress($client,$process,$step,$request,$activity_step, $action_id);
        $parameters["is_form"] = 0;

        if(count($parameters['client_processes']) > 1) {
            return view('client.applications')->with($parameters);
        } elseif(count($parameters['client_processes']) == 0) {
            return view('client.applications')->with($parameters);
        } else {
            return redirect()->route('clients.stepprogress', ['client' => $client->id,'process'=>$process_id,'step'=>$step_id]);
            //return view('client.stepprogress')->with($parameters);
        }

    }

    public function progress(Client $client)
    {
        $client->with('process.office.area.region.division');

        $parameters = [
            'client' => $client,
            'process_progress' => $client->getProcessProgress(),
            'steps' => Step::all(),
            'documents' => Document::orderBy('name')->where('client_id', $client->id)->orWhere('client_id', null)->pluck('name', 'id'),
            'templates' => Template::orderBy('name')->pluck('name', 'id')
        ];

        return view('clients.progress')->with($parameters);
    }

    /** **/
    public function stepProgress(Request $request,$client_id, $process_id, $step_id)
    {
        $client = Client::find($client_id);
        $client->process_id = $process_id;
        $client->step_id = $step_id;
        $client->save();

        $process = Process::find($process_id);

        $client_process = ClientProcess::where('client_id',$client->id)->where('process_id',$process->id)->first();

        $step = Step::withTrashed()->find($step_id);

        $activity_step = $step;

        $action_id = null;

        $parameters = $this->clientProcessProgress($client,$process,$step,$request,$activity_step, $action_id);

        return view('client.stepprogress')->with($parameters);
    }

    /** **/
    public function storeProgress(Client $client, Request $request)
    {
//dd($request->input());
        //dd($client->getNextVisibleStep($client->id,$request->input('process_id'),$request->input('step_id')));
        if($request->has('step_id') && $request->input('step_id') != ''){
            $log = new Log;
            $log->client_id = $client->id;
            $log->user_id = auth()->id();
            $log->save();

            $id = $client->id;
            $step = Step::find($request->input('step_id'));
            $step->load(['activities.actionable.data' => function ($query) use ($id) {
                $query->where('client_id', $id);
            }]);

            $all_activities_completed = false;

            foreach ($step->activities as $activity) {
                if(is_null($request->input($activity->id))){
                    if($request->input('old_'.$activity->id) != $request->input($activity->id)){

                        if(is_array($request->input($activity->id))){

                            $old = explode(',',$request->input('old_'.$activity->id));
                            $diff = array_diff($old,$request->input($activity->id));
                            //dd($diff);

                            foreach($request->input($activity->id) as $key => $value) {
                                $activity_log = new ActivityLog;
                                $activity_log->log_id = $log->id;
                                $activity_log->activity_id = $activity->id;
                                $activity_log->activity_name = $activity->name;
                                $activity_log->old_value = $request->input('old_' . $activity->id);
                                $activity_log->new_value = $value;
                                $activity_log->save();
                            }
                        } else {
                            $old = $request->input('old_'.$activity->id);

                            $activity_log = new ActivityLog;
                            $activity_log->log_id = $log->id;
                            $activity_log->activity_id = $activity->id;
                            $activity_log->activity_name = $activity->name;
                            $activity_log->old_value = $request->input('old_'.$activity->id);
                            $activity_log->new_value = $request->input($activity->id);
                            $activity_log->save();
                        }


                        switch ($activity->actionable_type) {
                            case 'App\ActionableBoolean':
                                ActionableBooleanData::where('actionable_boolean_id',$activity->actionable_id)->where('client_id',$client->id)->delete();

                                break;
                            case 'App\ActionableDate':
                                ActionableDateData::where('actionable_date_id',$activity->actionable_id)->where('client_id',$client->id)->delete();

                                break;
                            case 'App\ActionableText':

                                ActionableTextData::where('actionable_text_id',$activity->actionable_id)->where('client_id',$client->id)->delete();

                                break;
                            case 'App\ActionableAmount':

                                ActionableAmountData::where('actionable_amount_id',$activity->actionable_id)->where('client_id',$client->id)->delete();

                                break;
                            case 'App\ActionablePercentage':

                                ActionablePercentageData::where('actionable_percentage_id',$activity->actionable_id)->where('client_id',$client->id)->delete();

                                break;
                            case 'App\ActionableInteger':

                                ActionableIntegerData::where('actionable_integer_id',$activity->actionable_id)->where('client_id',$client->id)->delete();

                                break;
                            case 'App\ActionableTextarea':
                                ActionableTextareaData::where('actionable_textarea_id',$activity->actionable_id)->where('client_id',$client->id)->delete();

                                break;
                            case 'App\ActionableDropdown':
                                ActionableDropdownData::where('actionable_dropdown_id',$activity->actionable_id)->where('client_id',$client->id)->delete();

                                break;
                            default:
                                //todo capture defaults
                                break;
                        }
                    }
                }

                $all_steps = Step::select('id')->where('process_id',$request->input('process_id'))->get();

                ClientVisibleStep::where('client_id',$client->id)->whereIn('step_id',collect($all_steps)->toArray())->delete();

                $invisible_steps = explode(',',$request->input('step_invisibil'));

                foreach ($all_steps as $all_step){
                    if(in_array($all_step->id,$invisible_steps)){

                    } else {
                        ClientVisibleStep::insert([
                            'client_id' => $client->id,
                            'step_id' => $all_step->id
                        ]);
                    }
                }

                if ($request->has($activity->id) && !is_null($request->input($activity->id))) {
                    //If value did not change, do not save it again or add it to log
                    if($request->input('old_'.$activity->id) == $request->input($activity->id)){
                        continue;
                    }
                    if(is_array($request->input($activity->id))){

                        $old = explode(',',$request->input('old_'.$activity->id));
                        $diff = array_diff($old,$request->input($activity->id));
                        //dd($diff);

                        foreach($request->input($activity->id) as $key => $value) {
                            $activity_log = new ActivityLog;
                            $activity_log->log_id = $log->id;
                            $activity_log->activity_id = $activity->id;
                            $activity_log->activity_name = $activity->name;
                            $activity_log->old_value = $request->input('old_' . $activity->id);
                            $activity_log->new_value = $value;
                            $activity_log->save();
                        }
                    } else {
                        $old = $request->input('old_'.$activity->id);

                        $activity_log = new ActivityLog;
                        $activity_log->log_id = $log->id;
                        $activity_log->activity_id = $activity->id;
                        $activity_log->activity_name = $activity->name;
                        $activity_log->old_value = $request->input('old_'.$activity->id);
                        $activity_log->new_value = $request->input($activity->id);
                        $activity_log->save();
                    }

                    //activity type hook
                    //dd($request);



                    switch ($activity->actionable_type) {
                        case 'App\ActionableBoolean':
                            ActionableBooleanData::where('client_id',$client->id)->where('actionable_boolean_id',$activity->actionable_id)->where('data',$old)->delete();
                            ClientVisibleActivity::where('client_id',$client->id)->where('preceding_activity',$activity->id)->delete();

                            ActionableBooleanData::insert([
                                'data' => $request->input($activity->id),
                                'actionable_boolean_id' => $activity->actionable_id,
                                'client_id' => $client->id,
                                'user_id' => auth()->id(),
                                'duration' => 120,
                                'created_at' => now()
                            ]);

                            $ar = ActivityVisibilityRule::where('preceding_activity',$activity->id)->where('activity_value',$request->input($activity->id))->get();

                            foreach ($ar as $ars) {
                                ClientVisibleActivity::insert([
                                    'client_id' => $client->id,
                                    'preceding_activity' => $activity->id,
                                    'activity_id' => $ars->activity_id
                                ]);
                            }

                            /*ActivityVisibilityRule::where('preceding_activity',$activity->id)->where('activity_value',$request->input($activity->id))->update(['visible'=>1]);
                            ActivityVisibilityRule::where('preceding_activity',$activity->id)->where('activity_value','!=',$request->input($activity->id))->update(['visible'=>0]);*/
                            break;
                        case 'App\ActionableDate':
                            ActionableDateData::where('client_id',$client->id)->where('actionable_date_id',$activity->actionable_id)->where('data',$old)->delete();
                            ClientVisibleActivity::where('client_id',$client->id)->where('preceding_activity',$activity->id)->delete();

                            ActionableDateData::insert([
                                'data' => $request->input($activity->id),
                                'actionable_date_id' => $activity->actionable_id,
                                'client_id' => $client->id,
                                'user_id' => auth()->id(),
                                'duration' => 120,
                                'created_at' => now()
                            ]);

                            $ar = ActivityStepVisibilityRule::where('activity_id',$activity->id)->where('activity_value',$request->input($activity->id))->get();

                            foreach ($ar as $ars) {
                                ClientVisibleStep::insert([
                                    'client_id' => $client->id,
                                    'step_id' => $ars->activity_step
                                ]);
                            }
                            /*ActivityVisibilityRule::where('preceding_activity',$activity->id)->where('activity_value',$request->input($activity->id))->update(['visible'=>1]);
                            ActivityVisibilityRule::where('preceding_activity',$activity->id)->where('activity_value','!=',$request->input($activity->id))->update(['visible'=>0]);*/
                            break;
                        case 'App\ActionableAmount':
                            ActionableAmountData::where('client_id',$client->id)->where('actionable_amount_id',$activity->actionable_id)->where('data',$old)->delete();
                            ClientVisibleActivity::where('client_id',$client->id)->where('preceding_activity',$activity->id)->delete();

                            ActionableAmountData::insert([
                                'data' => $request->input($activity->id),
                                'actionable_amount_id' => $activity->actionable_id,
                                'client_id' => $client->id,
                                'user_id' => auth()->id(),
                                'duration' => 120,
                                'created_at' => now()
                            ]);

                            $ar = ActivityVisibilityRule::where('preceding_activity',$activity->id)->where('activity_value',$request->input($activity->id))->get();

                            foreach ($ar as $ars) {
                                ClientVisibleActivity::insert([
                                    'client_id' => $client->id,
                                    'preceding_activity' => $activity->id,
                                    'activity_id' => $ars->activity_id
                                ]);
                            }
                            /*ActivityVisibilityRule::where('preceding_activity',$activity->id)->where('activity_value',$request->input($activity->id))->update(['visible'=>1]);
                            ActivityVisibilityRule::where('preceding_activity',$activity->id)->where('activity_value','!=',$request->input($activity->id))->update(['visible'=>0]);*/
                            break;
                        case 'App\ActionablePercentage':
                            ActionablePercentageData::where('client_id',$client->id)->where('actionable_percentage_id',$activity->actionable_id)->where('data',$old)->delete();
                            ClientVisibleActivity::where('client_id',$client->id)->where('preceding_activity',$activity->id)->delete();

                            ActionablePercentageData::insert([
                                'data' => $request->input($activity->id),
                                'actionable_percentage_id' => $activity->actionable_id,
                                'client_id' => $client->id,
                                'user_id' => auth()->id(),
                                'duration' => 120,
                                'created_at' => now()
                            ]);

                            $ar = ActivityVisibilityRule::where('preceding_activity',$activity->id)->where('activity_value',$request->input($activity->id))->get();

                            foreach ($ar as $ars) {
                                ClientVisibleActivity::insert([
                                    'client_id' => $client->id,
                                    'preceding_activity' => $activity->id,
                                    'activity_id' => $ars->activity_id
                                ]);
                            }

                            /*ActivityVisibilityRule::where('preceding_activity',$activity->id)->where('activity_value',$request->input($activity->id))->update(['visible'=>1]);
                            ActivityVisibilityRule::where('preceding_activity',$activity->id)->where('activity_value','!=',$request->input($activity->id))->update(['visible'=>0]);*/
                            break;
                        case 'App\ActionableInteger':
                            ActionableIntegerData::where('client_id',$client->id)->where('actionable_integer_id',$activity->actionable_id)->where('data',$old)->delete();
                            ClientVisibleActivity::where('client_id',$client->id)->where('preceding_activity',$activity->id)->delete();

                            ActionableIntegerData::insert([
                                'data' => $request->input($activity->id),
                                'actionable_integer_id' => $activity->actionable_id,
                                'client_id' => $client->id,
                                'user_id' => auth()->id(),
                                'duration' => 120,
                                'created_at' => now()
                            ]);

                            $ar = ActivityVisibilityRule::where('preceding_activity',$activity->id)->where('activity_value',$request->input($activity->id))->get();

                            foreach ($ar as $ars) {
                                ClientVisibleActivity::insert([
                                    'client_id' => $client->id,
                                    'preceding_activity' => $activity->id,
                                    'activity_id' => $ars->activity_id
                                ]);
                            }
                            /*ActivityVisibilityRule::where('preceding_activity',$activity->id)->where('activity_value',$request->input($activity->id))->update(['visible'=>1]);
                            ActivityVisibilityRule::where('preceding_activity',$activity->id)->where('activity_value','!=',$request->input($activity->id))->update(['visible'=>0]);*/
                            break;

                        case 'App\ActionableText':
                            ActionableTextData::where('client_id',$client->id)->where('actionable_text_id',$activity->actionable_id)->where('data',$old)->delete();
                            ClientVisibleActivity::where('client_id',$client->id)->where('preceding_activity',$activity->id)->delete();

                            ActionableTextData::insert([
                                'data' => $request->input($activity->id),
                                'actionable_text_id' => $activity->actionable_id,
                                'client_id' => $client->id,
                                'user_id' => auth()->id(),
                                'duration' => 120,
                                'created_at' => now()
                            ]);

                            $ar = ActivityVisibilityRule::where('preceding_activity',$activity->id)->where('activity_value',$request->input($activity->id))->get();

                            foreach ($ar as $ars) {
                                ClientVisibleActivity::insert([
                                    'client_id' => $client->id,
                                    'preceding_activity' => $activity->id,
                                    'activity_id' => $ars->activity_id
                                ]);
                            }
                            /*ActivityVisibilityRule::where('preceding_activity',$activity->id)->where('activity_value',$request->input($activity->id))->update(['visible'=>1]);
                            ActivityVisibilityRule::where('preceding_activity',$activity->id)->where('activity_value','!=',$request->input($activity->id))->update(['visible'=>0]);*/
                            break;
                        case 'App\ActionableTextarea':
                            ActionableTextareaData::where('client_id',$client->id)->where('actionable_textarea_id',$activity->actionable_id)->where('data',$old)->delete();
                            ClientVisibleActivity::where('client_id',$client->id)->where('preceding_activity',$activity->id)->delete();

                            $replace1 = str_replace('&nbsp;',' ',$request->input($activity->id));
                            $replace2 = str_replace('&ndash;','-',$replace1);
                            $replace3 = str_replace('&bull;', '- ',$replace2);
                            $replace4 = str_replace('&ldquo;','"',$replace3);
                            $replace5 = str_replace('&rdquo;','"',$replace4);
                            $replace6 = str_replace("&rsquo;","'",$replace5);
                            $replace7 = str_replace("&lsquo;","'",$replace6);

                            $data = $replace7;

                            ActionableTextareaData::insert([
                                'data' => $data,
                                'actionable_textarea_id' => $activity->actionable_id,
                                'client_id' => $client->id,
                                'user_id' => auth()->id(),
                                'duration' => 120,
                                'created_at' => now()
                            ]);

                            $ar = ActivityVisibilityRule::where('preceding_activity',$activity->id)->where('activity_value',$request->input($activity->id))->get();

                            foreach ($ar as $ars) {
                                ClientVisibleActivity::insert([
                                    'client_id' => $client->id,
                                    'preceding_activity' => $activity->id,
                                    'activity_id' => $ars->activity_id
                                ]);
                            }
                            /*ActivityVisibilityRule::where('preceding_activity',$activity->id)->where('activity_value',$request->input($activity->id))->update(['visible'=>1]);
                            ActivityVisibilityRule::where('preceding_activity',$activity->id)->where('activity_value','!=',$request->input($activity->id))->update(['visible'=>0]);*/
                            break;
                        case 'App\ActionableDropdown':



                            foreach($request->input($activity->id) as $key => $value){
                                $dropdown_item_value = ActionableDropdownItem::where('id',$value)->first();


                                if(in_array($value,$old,true)) {

                                } else {
                                    if($dropdown_item_value) {
                                        $ar = ActivityVisibilityRule::where('preceding_activity', $activity->id)->where('activity_value', $dropdown_item_value->name)->get();
                                        foreach ($ar as $ars) {
                                            ClientVisibleActivity::where('client_id', $client->id)->where('preceding_activity', $ars->activity_id)->delete();
                                        }

                                        $as = ActivityStepVisibilityRule::where('activity_id', $activity->id)->where('activity_value', $dropdown_item_value->name)->get();
                                        foreach ($as as $ass) {
                                            ClientVisibleStep::where('client_id', $client->id)->where('step_id', $ass->activity_step)->delete();
                                        }
                                    }

                                    ActionableDropdownData::insert([
                                        'actionable_dropdown_id' => $activity->actionable_id,
                                        'actionable_dropdown_item_id' => $value,
                                        'client_id' => $client->id,
                                        'user_id' => auth()->id(),
                                        'duration' => 120,
                                        'created_at' => now()
                                    ]);

                                    if($ar) {

                                        foreach ($ar as $ars) {
                                            ClientVisibleActivity::insert([
                                                'client_id' => $client->id,
                                                'preceding_activity' => $activity->id,
                                                'activity_id' => $ars->activity_id
                                            ]);
                                        }
                                    }
                                    /*ActivityVisibilityRule::where('preceding_activity',$activity->id)->where('activity_value',$request->input($activity->id))->update(['visible'=>1]);
                                    ActivityVisibilityRule::where('preceding_activity',$activity->id)->where('activity_value','!=',$request->input($activity->id))->update(['visible'=>0]);*/
                                }

                                if(!empty($diff)){
                                    ActionableDropdownData::where('client_id',$client->id)->where('actionable_dropdown_id',$activity->actionable_id)->whereIn('actionable_dropdown_item_id',$diff)->delete();

                                    $dropdown_item_value_diff = ActionableDropdownItem::whereIn('id',$diff)->get();

                                    foreach($dropdown_item_value_diff as $ddd) {
                                        $ar = ActivityVisibilityRule::where('preceding_activity', $activity->id)->where('activity_value', $ddd->name)->get();
                                        foreach ($ar as $ars) {
                                            ClientVisibleActivity::where('client_id', $client->id)->where('activity_id', $ars->activity_id)->delete();
                                        }
                                    }
                                }



                            }
                            break;
                        /*case 'App\ActionableMultipleAttachment':
                            ActionableMultipleAttachmentData::insert([
                                'email' => $request->input($activity->id),
                                'template_id' => $request->input('template_email_'.$activity->id),
                                'actionable_ma_id' => $activity->actionable_id,
                                'client_id' => $client->id,
                                'user_id' => auth()->id(),
                                'duration' => 120
                            ]);
                            break;*/
                        default:
                            //todo capture defaults
                            break;
                    }

                }
            }

            $client_process_progress = ClientProcess::where('client_id',$client->id)->where('process_id',$request->input('process_id'))->first();

            //Move process step to the next step if all activities completed
            $max_step = Step::orderBy('order','desc')->where('process_id', $request->input('process_id'))->first();

            $n_step = Step::select('id')->orderBy('order','asc')->where('process_id', $request->input('process_id'))->where('order','>',$step->order)->whereNull('deleted_at')->first();

            $load_next_step = false;
            //dd($n_step);
            if($client->isStepActivitiesCompleted($step) && $client_process_progress->step_id != $max_step["id"] && $step->id != $max_step["id"]){

                $n_visible_step = $client->getNextVisibleStep($client->id,$request->input('process_id'),$request->input('step_id'));

                $client = Client::find($client->id);
                $client->step_id = ($n_visible_step ? $n_visible_step : $n_step->id);
                $client->save();

                $cpp = ClientProcess::find($client_process_progress->id);
                $cpp->step_id = ($n_visible_step ? $n_visible_step : $n_step->id);
                $cpp->save();

                $load_next_step = true;
            }
            if($client->isStepActivitiesCompleted($step) && $step->id == $max_step["id"]){
                $client = Client::find($client->id);
                $client->step_id = $max_step['id'];
                $client->save();

                $cpp = ClientProcess::find($client_process_progress->id);
                $cpp->step_id = $max_step['id'];
                $cpp->save();

                $load_next_step = false;
            }
            if($client->step_id == $request->input('step_id')){
                $client = Client::find($client->id);
                $client->step_id = $step->id;
                $client->save();

                $cpp = ClientProcess::find($client_process_progress->id);
                $cpp->step_id = $step->id;
                $cpp->save();

                $load_next_step = false;
            }

            //Handle files
            foreach($request->files as $key => $file):
                $file_activity = Activity::find($key);
                switch($file_activity->actionable_type){
                    case 'App\ActionableDocument':
                        $afile = $request->file($key);
                        $name = Carbon::now()->format('Y-m-d')."-".strtotime(Carbon::now()).".".$afile->getClientOriginalExtension();
                        $stored = $afile->storeAs('documents', $name);

                        $document = new Document;
                        $document->name = $file_activity->name;
                        $document->file = $name;
                        $document->user_id = auth()->id();
                        $document->client_id = $client->id;
                        $document->save();

                        ActionableDocumentData::insert([
                            'actionable_document_id' => $file_activity->actionable_id,
                            'document_id' => $document->id,
                            'client_id' => $client->id,
                            'user_id' => auth()->id(),
                            'duration' => 120
                        ]);
                        break;
                    default:
                        //todo capture detaults
                        break;
                }

            endforeach;

            //$notification = $this->activityNotification($client, $log->id);

        }

        //Move process step to the next step if all activities completed



        if($load_next_step == true) {
            return redirect()->route('clients.stepprogress', ['client' => $client,'process'=>$request->input('process_id'), 'step' => ($n_visible_step ? $n_visible_step : $n_step->id)]);
        }

        return redirect()->back()->with(['flash_success' => "Activity values successffully captured."]);
    }

    public function calculators(Request $request,$client_id, $process_id, $step_id)
    {
        $client = Client::find($client_id);

        $process = Process::find($process_id);

        $client_process = ClientProcess::where('client_id',$client->id)->where('process_id',$process->id)->first();

        $step = Step::withTrashed()->find($step_id);

        $activity_step = $step;

        $action_id = null;

        $parameters = $this->clientProcessProgress($client,$process,$step,$request,$activity_step, $action_id);

        return view('client.calculators')->with($parameters);
    }

    public function stepProgressAction(Request $request,$client_id, $process_id, $step_id, $action_id )
    {

        $client = Client::find($client_id);

        $process = Process::find($process_id);

        //$client_process = ClientProcess::where('client_id',$client->id)->where('process_id',$process->id)->first();

        $step = Step::find($step_id);

        $activity_step = $step;

        $parameters = $this->clientProcessProgress($client,$process,$step,$request,$activity_step, $action_id);

        return view('client.stepprogressactions')->with($parameters);
    }

    public function storeActions(Client $client, Request $request)
    {

        if($request->has('step_id') && $request->input('step_id') != ''){
            $log = new Log;
            $log->client_id = $client->id;
            $log->user_id = auth()->id();
            $log->save();

            $id = $client->id;

            $step = Step::find($request->input('step_id'));
            $step->load(['activities.actionable.data' => function ($query) use ($id) {
                $query->where('client_id', $id);
            }]);

            $all_activities_completed = false;
            foreach ($step->activities as $activity) {
                if (is_null($request->input($activity->id))) {
                    if ($request->input('old_' . $activity->id) != $request->input($activity->id)) {

                        if (is_array($request->input($activity->id))) {

                            $old = explode(',', $request->input('old_' . $activity->id));
                            $diff = array_diff($old, $request->input($activity->id));
                            //dd($diff);

                            foreach ($request->input($activity->id) as $key => $value) {
                                $activity_log = new ActivityLog;
                                $activity_log->log_id = $log->id;
                                $activity_log->activity_id = $activity->id;
                                $activity_log->activity_name = $activity->name;
                                $activity_log->old_value = $request->input('old_' . $activity->id);
                                $activity_log->new_value = $value;
                                $activity_log->save();
                            }
                        } else {
                            $old = $request->input('old_' . $activity->id);

                            $activity_log = new ActivityLog;
                            $activity_log->log_id = $log->id;
                            $activity_log->activity_id = $activity->id;
                            $activity_log->activity_name = $activity->name;
                            $activity_log->old_value = $request->input('old_' . $activity->id);
                            $activity_log->new_value = $request->input($activity->id);
                            $activity_log->save();
                        }

                        switch ($activity->actionable_type) {
                            case 'App\ActionableBoolean':
                                ActionableBooleanData::where('actionable_boolean_id', $activity->actionable_id)->where('client_id', $client->id)->delete();

                                break;
                            case 'App\ActionableDate':
                                ActionableDateData::where('actionable_date_id', $activity->actionable_id)->where('client_id', $client->id)->delete();

                                break;
                            case 'App\ActionableText':
                                ActionableTextData::where('actionable_text_id', $activity->actionable_id)->where('client_id', $client->id)->delete();

                                break;
                            case 'App\ActionableDropdown':
                                ActionableDropdownData::where('actionable_dropdown_id', $activity->actionable_id)->where('client_id', $client->id)->delete();

                                break;
                            default:
                                //todo capture defaults
                                break;
                        }
                    }
                }

                if ($request->has($activity->id) && !is_null($request->input($activity->id))) {
                    //If value did not change, do not save it again or add it to log
                    if ($request->input('old_' . $activity->id) == $request->input($activity->id)) {
                        continue;
                    }
                    if (is_array($request->input($activity->id))) {

                        $old = explode(',', $request->input('old_' . $activity->id));
                        $diff = array_diff($old, $request->input($activity->id));
                        //dd($diff);

                        foreach ($request->input($activity->id) as $key => $value) {
                            $activity_log = new ActivityLog;
                            $activity_log->log_id = $log->id;
                            $activity_log->activity_id = $activity->id;
                            $activity_log->activity_name = $activity->name;
                            $activity_log->old_value = $request->input('old_' . $activity->id);
                            $activity_log->new_value = $value;
                            $activity_log->save();
                        }
                    } else {
                        $old = $request->input('old_' . $activity->id);

                        $activity_log = new ActivityLog;
                        $activity_log->log_id = $log->id;
                        $activity_log->activity_id = $activity->id;
                        $activity_log->activity_name = $activity->name;
                        $activity_log->old_value = $request->input('old_' . $activity->id);
                        $activity_log->new_value = $request->input($activity->id);
                        $activity_log->save();
                    }

                    //activity type hook
                    //dd($request);
                    switch ($activity->actionable_type) {
                        case 'App\ActionableBoolean':
                            ActionableBooleanData::where('client_id', $client->id)->where('actionable_boolean_id', $activity->actionable_id)->where('data', $old)->delete();

                            ActionableBooleanData::insert([
                                'data' => $request->input($activity->id),
                                'actionable_boolean_id' => $activity->actionable_id,
                                'client_id' => $client->id,
                                'user_id' => auth()->id(),
                                'duration' => 120,
                                'created_at' => now()
                            ]);
                            break;
                        case 'App\ActionableDate':
                            ActionableDateData::insert([
                                'data' => $request->input($activity->id),
                                'actionable_date_id' => $activity->actionable_id,
                                'client_id' => $client->id,
                                'user_id' => auth()->id(),
                                'duration' => 120,
                                'created_at' => now()
                            ]);
                            break;
                        case 'App\ActionableText':

                            ActionableTextData::insert([
                                'data' => $request->input($activity->id),
                                'actionable_text_id' => $activity->actionable_id,
                                'client_id' => $client->id,
                                'user_id' => auth()->id(),
                                'duration' => 120,
                                'created_at' => now()
                            ]);
                            break;
                        case 'App\ActionableDropdown':
                            foreach ($request->input($activity->id) as $key => $value) {
                                if (in_array($value, $old, true)) {

                                } else {
                                    ActionableDropdownData::insert([
                                        'actionable_dropdown_id' => $activity->actionable_id,
                                        'actionable_dropdown_item_id' => $value,
                                        'client_id' => $client->id,
                                        'user_id' => auth()->id(),
                                        'duration' => 120,
                                        'created_at' => now()
                                    ]);
                                }

                                if (!empty($diff)) {
                                    ActionableDropdownData::where('client_id', $client->id)->where('actionable_dropdown_id', $activity->actionable_id)->whereIn('actionable_dropdown_item_id', $diff)->delete();
                                }


                            }
                            break;
                        default:
                            //todo capture defaults
                            break;
                    }

                }
            }

            //Handle files
            foreach ($request->files as $key => $file):
                $file_activity = Activity::find($key);
                switch ($file_activity->actionable_type) {
                    case 'App\ActionableDocument':
                        $afile = $request->file($key);
                        $name = Carbon::now()->format('Y-m-d') . "-" . strtotime(Carbon::now()) . "." . $afile->getClientOriginalExtension();
                        $stored = $afile->storeAs('documents', $name);

                        $document = new Document;
                        $document->name = $file_activity->name;
                        $document->file = $name;
                        $document->user_id = auth()->id();
                        $document->client_id = $client->id;
                        $document->save();

                        ActionableDocumentData::insert([
                            'actionable_document_id' => $file_activity->actionable_id,
                            'document_id' => $document->id,
                            'client_id' => $client->id,
                            'user_id' => auth()->id(),
                            'duration' => 120
                        ]);
                        break;
                    default:
                        //todo capture detaults
                        break;
                }

            endforeach;

            //$notification = $this->activityNotification($client, $log->id);

        }

        return redirect()->back()->with(['flash_success' => "Client details captured."]);
    }

    public function activityProgress(Request $request, $client_id, $process_id, $step_id)
    {
        $client = Client::find($client_id);

        $process = Process::find($process_id);

        $client_process = ClientProcess::where('client_id',$client->id)->where('process_id',$process->id)->first();

        $step = Step::find($client_process->step_id);

        $activity_step = Step::find($step_id);

        $action_id = null;

        $parameters = $this->clientProcessProgress($client,$process,$step,$request,$activity_step,$action_id);

        return view('clients.activityprogress')->with($parameters);
    }

    public function actions(Request $request,$clientid, Step $step){

        $client = Client::withTrashed()->find($clientid);

        $client->with('process.office.area.region.division');

        $client_progress = $client->process->getStageHex(0);

        if($client->step_id == $step->id)
            $client_progress = $client->process->getStageHex(1);

        if($client->step_id > $step->id)
            $client_progress = $client->process->getStageHex(2);

        $steps = Step::where('process_id', $client->process_id)->orderBy('order','asc')->get();
        $c_step_order = Step::where('id',$client->step_id)->withTrashed()->first();

        $step_data = [];
        foreach ($steps as $a_step):
            $progress_color = $client->process->getStageHex(0);
            $step_stage = 0;

            if($c_step_order->order == $a_step->order) {
                $progress_color = $client->process->getStageHex(1);
                $step_stage = 1;
            }

            if($c_step_order->order > $a_step->order) {
                $progress_color = $client->process->getStageHex(2);
                $step_stage = 2;
            }


            $tmp_step = [
                'id' => $a_step->id,
                'name' => $a_step->name,
                'process_id' => $a_step->process_id,
                'progress_color' => $progress_color,
                'order' => $a_step->order,
                'stage' => $step_stage
            ];

            array_push($step_data, $tmp_step);

        endforeach;
        $max_step = Step::orderBy('order','desc')->where('process_id', $client->process_id)->first();

        //$n_step = Step::select('id')->orderBy('order','asc')->where('process_id', $client->process_id)->where('order','>',$step->order)->whereNull('deleted_at')->first();
        $next_step = $step->id;
        if($next_step == $max_step->id)
            $next_step = $max_step->id;
        else
            $next_step = (isset($n_step->id) ? $n_step->id : $step->id);
        $template_email_options = EmailTemplate::orderBy('name')->pluck('name', 'id');


        $activities_comments = DB::select("SELECT activity_id,COUNT(activity_id) as activity_count,`private` as pr,user_id FROM client_activities_comments where client_id = '".$client->id."' and deleted_at is null group by activity_id,private,user_id");
        $activity_comment = array();
        foreach ($activities_comments as $value) {
            if ((isset($value->pr) && $value->pr > 0) && (isset($value->user_id) && Auth::id() == $value->user_id)) {
                $activity_comment[$value->activity_id] = (isset($activity_comment[$value->activity_id]) ? $activity_comment[$value->activity_id] : 0) + $value->activity_count;
            } else {
                if (isset($value->pr) && $value->pr == 0){
                    $activity_comment[$value->activity_id] = (isset($activity_comment[$value->activity_id]) ? $activity_comment[$value->activity_id] : 0) + $value->activity_count;
                }
            }
        }

        /* Get the raw data for assigned activities */
        $result = ActionsAssigned::with('client')->whereHas('activity', function($q) use ($clientid){
            $q->where('status','0')
                ->where('client_id',$clientid);
        })->where('completed',0)->get();
        $configs = Config::first();

        /*  Separate out the collection into an array we can manipulate better in the template */
        $activities = [];

        $due_date = '';

        foreach($result as $activity) {

            // User IDs are comma-separated in the database
            $split_users = explode(',', $activity->users);
            $auser_array = array();
            if ($activity->client) {
                // List of Activities
                foreach ($activity->activity as $activity_id) {
                    foreach ($split_users as $user_id) {

                        // User Name
                        $user = User::where('id', trim($user_id))->first();

                        $user_name = $user["first_name"] . ' ' . $user["last_name"];

                        if(!in_array($user_name,$auser_array)) {
                            array_push($auser_array, $user_name);
                        }
                    }

                    if ($activity_id != null && $activity_id->status != 1) {
                        $clientid = $activity->client["id"];
                        $parent_activity = Activity::withTrashed()->with(['actionable.data' => function ($query) use ($clientid) {
                            $query->where('client_id', $clientid);
                        }])->where('id', $activity_id->activity_id)->first();

                        if(isset($activities[($activity->client["company"] != null && $activity->client["company"] != '' ? $activity->client["company"] : $activity->client["first_name"] . ' ' . $activity->client["last_name"])][$activity_id->activity_id]["due_date"]) && $activities[($activity->client["company"] != null && $activity->client["company"] != '' ? $activity->client["company"] : $activity->client["first_name"] . ' ' . $activity->client["last_name"])][$activity_id->activity_id]["due_date"] > $activity->due_date){
                            $due_date = $activities[($activity->client["company"] != null && $activity->client["company"] != '' ? $activity->client["company"] : $activity->client["first_name"] . ' ' . $activity->client["last_name"])][$activity_id->activity_id]["due_date"];
                        } else {
                            $due_date = $activity->due_date;
                        }

                        if (isset($parent_activity->actionable['data'][0])) {
                            //foreach ($parent_activity->actionable['data'] as $data) {

                            //Get the current timestamp.
                            $now = strtotime(now());

                            //Calculate the difference.
                            $difference = $now - strtotime($due_date);

                            //Convert seconds into days.
                            $days = floor($difference / (60 * 60 * 24));

                            if ($days < -$configs->action_threshold) {
                                $class = $activity->client->process->getStageHex(2);
                            } elseif ($days <= $configs->action_threshold) {
                                if (Carbon::parse(now()) > Carbon::parse($due_date)) {
                                    $class = $activity->client->process->getStageHex(0);
                                } elseif (Carbon::parse(now()) >= Carbon::parse($due_date)->subDay($configs->action_threshold)) {
                                    $class = $activity->client->process->getStageHex(1);
                                }
                            } elseif ($days > $configs->action_threshold) {
                                $class = $activity->client->process->getStageHex(0);
                            } else {
                                $class = $activity->client->process->getStageHex(0);
                            }

                            if (Auth::check() && Auth::user()->isNot("admin") && Auth::id() == $user_id) {
                                $activities[($activity->client["company"] != null && $activity->client["company"] != '' ? $activity->client["company"] : $activity->client["first_name"] . ' ' . $activity->client["last_name"])] [$activity_id->activity_id] = [
                                    'client_id' => $activity->client["id"],
                                    'step_id' => $activity->step_id,
                                    'action_id' => $activity->id,
                                    'user' => (isset($activities[($activity->client["company"] != null && $activity->client["company"] != '' ? $activity->client["company"] : $activity->client["first_name"] . ' ' . $activity->client["last_name"])][$activity_id->activity_id]["user"]) ? array_unique(array_merge($activities[($activity->client["company"] != null && $activity->client["company"] != '' ? $activity->client["company"] : $activity->client["first_name"] . ' ' . $activity->client["last_name"])][$activity_id->activity_id]["user"],$auser_array)) : $auser_array),
                                    'activity_id' => trim($activity_id->activity_id),
                                    'activity_name' => Activity::withTrashed()->where('id', trim($activity_id->activity_id))->first()->name,
                                    'due_date' => $due_date,
                                    'class' => $class];
                            } elseif (Auth::check() && Auth::user()->is("admin")) {
                                $activities[($activity->client["company"] != null && $activity->client["company"] != '' ? $activity->client["company"] : $activity->client["first_name"] . ' ' . $activity->client["last_name"])] [$activity_id->activity_id] = [
                                    'client_id' => $activity->client["id"],
                                    'step_id' => $activity->step_id,
                                    'action_id' => $activity->id,
                                    'user' => (isset($activities[($activity->client["company"] != null && $activity->client["company"] != '' ? $activity->client["company"] : $activity->client["first_name"] . ' ' . $activity->client["last_name"])][$activity_id->activity_id]["user"]) ? array_unique(array_merge($activities[($activity->client["company"] != null && $activity->client["company"] != '' ? $activity->client["company"] : $activity->client["first_name"] . ' ' . $activity->client["last_name"])][$activity_id->activity_id]["user"],$auser_array)) : $auser_array),
                                    'activity_id' => trim($activity_id->activity_id),
                                    'activity_name' => Activity::withTrashed()->where('id', trim($activity_id->activity_id))->first()->name,
                                    'due_date' => $due_date,
                                    'created_date' => '',
                                    'updated_date' => '',
                                    'class' => $class];
                            }
                        } else {

                            $now2 = strtotime(now());

                            //Calculate the difference.
                            $difference2 = $now2 - strtotime($due_date);

                            //Convert seconds into days.
                            $days2 = floor($difference2 / (60 * 60 * 24));

                            if ($days2 < -$configs->action_threshold) {
                                $class = $activity->client->process->getStageHex(2);
                            } elseif ($days2 <= $configs->action_threshold) {
                                if (Carbon::parse(now()) > Carbon::parse($due_date)) {
                                    $class = $activity->client->process->getStageHex(0);
                                } elseif (Carbon::parse(now()) >= Carbon::parse($due_date)->subDay($configs->action_threshold)) {
                                    $class = $activity->client->process->getStageHex(1);
                                }
                            } elseif ($days2 > $configs->action_threshold) {
                                $class = $activity->client->process->getStageHex(0);
                            } else {
                                $class = $activity->client->process->getStageHex(0);
                            }

                            if (Auth::check() && Auth::user()->isNot("admin") && Auth::id() == $user_id) {
                                $activities[($activity->client["company"] != null && $activity->client["company"] != '' ? $activity->client["company"] : $activity->client["first_name"] . ' ' . $activity->client["last_name"])] [$activity_id->activity_id] = [
                                    'client_id' => $activity->client["id"],
                                    'step_id' => $activity->step_id,
                                    'action_id' => $activity->id,
                                    'user' => (isset($activities[($activity->client["company"] != null && $activity->client["company"] != '' ? $activity->client["company"] : $activity->client["first_name"] . ' ' . $activity->client["last_name"])][$activity_id->activity_id]["user"]) ? array_unique(array_merge($activities[($activity->client["company"] != null && $activity->client["company"] != '' ? $activity->client["company"] : $activity->client["first_name"] . ' ' . $activity->client["last_name"])][$activity_id->activity_id]["user"],$auser_array)) : $auser_array),
                                    'activity_id' => trim($activity_id->activity_id),
                                    'activity_name' => Activity::withTrashed()->where('id', trim($activity_id->activity_id))->first()->name,
                                    'due_date' => $due_date,
                                    'created_date' => '',
                                    'updated_date' => '',
                                    'class' => $class];
                            } elseif (Auth::check() && Auth::user()->is("admin")) {
                                $activities[($activity->client["company"] != null && $activity->client["company"] != '' ? $activity->client["company"] : $activity->client["first_name"] . ' ' . $activity->client["last_name"])] [$activity_id->activity_id] = [
                                    'client_id' => $activity->client["id"],
                                    'step_id' => $activity->step_id,
                                    'action_id' => $activity->id,
                                    'user' => (isset($activities[($activity->client["company"] != null && $activity->client["company"] != '' ? $activity->client["company"] : $activity->client["first_name"] . ' ' . $activity->client["last_name"])][$activity_id->activity_id]["user"]) ? array_unique(array_merge($activities[($activity->client["company"] != null && $activity->client["company"] != '' ? $activity->client["company"] : $activity->client["first_name"] . ' ' . $activity->client["last_name"])][$activity_id->activity_id]["user"],$auser_array)) : $auser_array),
                                    'activity_id' => trim($activity_id->activity_id),
                                    'activity_name' => Activity::withTrashed()->where('id', trim($activity_id->activity_id))->first()->name,
                                    'due_date' => $due_date,
                                    'created_date' => '',
                                    'updated_date' => '',
                                    'class' => $class];
                            }
                        }
                    }
                }
            }
        }
        //dd($actions_data);
        $parameters = [

            'actions_data' => $activities,
            'actions' => Actions::where('status',1)->pluck('name','id')->prepend('Please select' , 0),
            'view_process_dropdown' => $client->clientProcessIfActivitiesExist(),
            'client' => $client,
            'activity_comment' => $activity_comment,
            'step' => $step,
            'active' => $step,
            'max_step' => $max_step->id,
            'next_step' => $next_step,
            'process_progress' => $client->getProcessStepProgress($step),
            'steps' => $step_data,
            //'steps' => Step::where('process_id', $client->process_id)->get(),
            'users_drop' => User::select(DB::raw("CONCAT(first_name,' ',COALESCE(`last_name`,'')) AS full_name"), 'id')->orderBy('first_name')->orderBy('last_name')->pluck('full_name', 'id'),
            'documents' => Document::orderBy('name')->where('client_id', $client->id)->orWhere('client_id', null)->pluck('name', 'id'),
            'document_options' => Document::orderBy('name')->pluck('name', 'id'),
            'templates' => Template::where('template_type_id','2')->orderBy('name')->pluck('name', 'id'),
            'client_progress' => $client_progress,
            'template_email_options' => $template_email_options,
        ];

        return view('clients.actions.index')->with($parameters);
    }

    /**
     * Display client documents from Request
     *
     * @param UpdateClientRequest $request
     * @param Client $client
     * @return void
     */
    public function documents(Request $request, $client_id, $process_id, $step_id)
    {
        $client = Client::find($client_id);

        $process = Process::withTrashed()->find($process_id);

        $client_process = ClientProcess::where('client_id',$client->id)->where('process_id',$process->id)->first();

        $step = Step::withTrashed()->find($client_process->step_id);

        $activity_step = $step;

        $action_id = null;

        $is_cilent_portal = false;
        /*$clientPortal = ClientPortal::where('client_id', $client->id)->where('email', $client->email)->first();
        if(isset($clientPortal->id)){
            $is_cilent_portal = true;
        }*/

        $parameters = $this->clientProcessProgress($client,$process,$step,$request,$activity_step, $action_id);
        $parameters['is_cilent_portal'] = $is_cilent_portal;

        return view('client.documents')->with($parameters);
    }

    public function autocompleteClientProcess(Request $request, Client $clientid, Process $processid,$newprocess)
    {
        //autocomplete all entries for current process

        $steps = Step::with('activities.actionable.data')->where('process_id',$processid->id)->get();
        /*return $step;
        exit;*/
        $activities_auto_completed = [];
        //return null;
        //todo just change switch logic to assign fake data for all activities
        foreach($steps as $step) {
            foreach ($step->activities as $activity) {

                //Check if activity is not already set/completed
                if (!$clientid->isActivitieCompleted($activity)) {
                    //if(!isset($activity->actionable['data'][0])){
                    $found = false;
                    foreach ($activity->actionable['data'] as $datum) {
                        if ($datum->client_id == $clientid->id) {
                            $found = true;
                            break;
                        }
                    }

                    if (!$found) {
                        $activities_auto_completed[] = $activity->id;
                        $actionable_text_data = $activity->actionable_type . 'Data';
                        $actionable_data = new $actionable_text_data;
                        $actionable_data->client_id = $clientid->id;
                        $actionable_data->user_id = auth()->id();
                        $actionable_data->duration = 120;
                        switch ($activity->getTypeName()) {
                            case 'text':
                                $actionable_data->data = null;
                                $actionable_data->actionable_text_id = $activity->actionable_id;
                                $actionable_data->save();
                                break;
                            case 'template_email':
                                $actionable_data->template_id = 1;
                                $actionable_data->email = null;
                                $actionable_data->actionable_template_email_id = $activity->actionable_id;
                                $actionable_data->save();
                                break;
                            case 'document_email':
                                //return 'document_email';
                                break;
                            case 'document':
                                $actionable_data->actionable_document_id = $activity->actionable_id;
                                $actionable_data->document_id = 1;
                                $actionable_data->save();
                                break;
                            case 'dropdown':
                                $item = ActionableDropdownItem::where('actionable_dropdown_id', $activity->actionable_id)->take(1)->first();

                                $actionable_data->actionable_dropdown_id = $activity->actionable_id;
                                $actionable_data->actionable_dropdown_item_id = 0;
                                $actionable_data->save();
                                break;
                            case 'date':
                                $actionable_data->data = null;
                                $actionable_data->actionable_date_id = $activity->actionable_id;
                                $actionable_data->save();
                                break;
                            case 'boolean':
                                $actionable_data->data = null;
                                $actionable_data->actionable_boolean_id = $activity->actionable_id;
                                $actionable_data->save();
                                break;
                            case 'notification':
                                $notification = new Notification;
                                $notification->name = $clientid->company . ' has been updated: ' . $activity->name;
                                $notification->link = route('clients.progress', $clientid);
                                $notification->save();

                                Mail::to(auth()->user()->email)->send(new NotificationMail($notification->name, $notification->link));

                                $actionable_data->actionable_notification_id = $activity->actionable_id;
                                $actionable_data->notification_id = $notification->id;
                                $actionable_data->save();
                                break;
                            case 'multiple_attachment':
                                $actionable_data->template_id = 1;
                                $actionable_data->email = null;
                                $actionable_data->actionable_ma_id = $activity->actionable_id;
                                $actionable_data->save();
                                break;
                            default:
                                return 'error';
                                break;
                        }
                    }

                }
            }
        }

        $new_process_id = $newprocess;

        //check if selected process has any activities for this client
        $highest_step_id = Step::with(['activities.actionable.data' => function ($query) use ($clientid) {
            $query->where('client_id', $clientid->id);
        }])->where('process_id',$new_process_id)->get();

        $process_activities=array();
        if(count($highest_step_id) > 0) {

            foreach ($highest_step_id as $highest_step) {
                if($clientid->isStepActivitiesCompleted(Step::find($highest_step->id))) {
                    foreach ($highest_step->process->activities as $activity){
                        //get the step ids of the process if the client was previously in the selected process
                        foreach ($activity->actionable['data'] as $data) {
                            //push the step id into the array
                            array_push($process_activities,["step_id" => $highest_step->id,"name" => $highest_step->name,"order" => $highest_step->order]);
                        }
                    }
                }
            }


            //sort the array in descending order
            usort($process_activities, function ($item1, $item2) {
                return $item2['order'] <=> $item1['order'];
            });

        }

        $process_first_step = Step::where('process_id',$new_process_id)->orderBy('order','asc')->first();

        $new_step_id = ( isset($process_activities[0]) ? $process_activities[0]["step_id"] : $process_first_step->id );

        //update client with new process id and step id
        //$client = Client::find($clientid);
        $clientid->process_id = $new_process_id;
        $clientid->step_id = $new_step_id;
        $clientid->save();

        return response()->json();
    }

    public function keepClientProcess(Request $request, Client $clientid, $process,$newprocess){

        $processid = Process::withTrashed()->find($process);

        $new_process_id = $newprocess;

        //check if selected process has any activities for this client
        $highest_step_id = Step::with(['activities.actionable.data' => function ($query) use ($clientid) {
            $query->where('client_id', $clientid->id);
        }])->where('process_id',$new_process_id)->get();

        $process_activities=array();
        if(count($highest_step_id) > 0) {

            foreach ($highest_step_id as $highest_step) {
                if($clientid->isStepActivitiesCompleted(Step::find($highest_step->id))) {
                    foreach ($highest_step->process->activities as $activity){
                        //get the step ids of the process if the client was previously in the selected process
                        foreach ($activity->actionable['data'] as $data) {
                            //push the step id into the array

                            //return $data;
                            if($data["client_id"] != null && $data["data"] != null) {
                                array_push($process_activities, ["step_id" => $highest_step->id, "name" => $highest_step->name, "order" => $highest_step->order]);
                            }
                        }
                    }
                }
            }


            //sort the array in descending order
            usort($process_activities, function ($item1, $item2) {
                return $item1['order'] <=> $item2['order'];
            });

        }

        $process_first_step = Step::where('process_id',$new_process_id)->orderBy('order','asc')->first();

        $new_step_id = ( isset($process_activities[0]) && count($process_activities[0]) > 0 ? $process_activities[0]["step_id"] : $process_first_step->id );

        ClientProcess::where('client_id',$clientid->id)->where('process_id',$processid->id)->update(['active'=>0]);

        //update client with new process id and step id
        //$client = Client::find($clientid);
        $clientid->process_id = $new_process_id;
        $clientid->step_id = $new_step_id;
        $clientid->save();

        $check = ClientProcess::where('client_id',$clientid->id)->where('process_id',$processid->id)->get();

        if($check) {
            $client_new = new ClientProcess();
            $client_new->client_id = $clientid->id;
            $client_new->process_id = $new_process_id;
            $client_new->step_id = $new_step_id;
            $client_new->active = 1;
            $client_new->save();
        }

        return response()->json(['new_step_id'=>$new_step_id]);
    }

    public function follow(Client $client, StoreFollowRequest $request)
    {
        if ($request->input('follow')) {
            ClientUser::insert([
                'client_id' => $client->id,
                'user_id' => auth()->id(),
                'created_at' => now()
            ]);
        } else {
            ClientUser::where('client_id', $client->id)->where('user_id', auth()->id())->delete();
        }

        return redirect()->back()->with('flash_success', 'Follow status updated successfully.');
    }

    public function complete($client_id,$step_id,$newdate)
    {
        $client = Client::find($client_id);
        $client->completed_at = $newdate;
        $client->step_id = $step_id;
        $client->save();

        return response()->json();
    }

    public function uncomplete($client_id,$step_id)
    {
        $client = Client::find($client_id);
        $client->completed_at = null;
        $client->step_id = $step_id;
        $client->save();

        return response()->json();
    }

    public function changecomplete($client_id,$step_id,$newdate)
    {
        $client = Client::find($client_id);
        $client->completed_at = $newdate;
        $client->step_id = $step_id;
        $client->save();

        return response()->json();
    }

    public function viewTemplate(Client $client, Template $template)
    {

        $processed_template = $this->processTemplate($client, $template->id, $template->file, $template->name);

        return response()->download(storage_path('app/templates/' . $processed_template));

    }

    public function viewDocument(Client $client, Document $document)
    {

        $processed_document = $this->processDocument($client, $document->id, $document->file, $document->name);

        return response()->download(storage_path('app/documents/' . $processed_document));

    }

    public function sendTemplate(Client $client, Activity $activity, Request $request)
    {
        $template = Template::find($request->input('template_file'));

        $processed_templates = array();
        $processed_templates[0]['file'] = $this->processTemplate($client, $template->id, $template->file, $template->name);
        $processed_templates[0]['type'] = 'template';
        $processed_templates[0]['name'] = $template->name;

        $actionable_template_email = $activity->actionable;

        ActionableTemplateEmailData::where('email',$client->email)->where('actionable_template_email_id',$actionable_template_email->id)->where('client_id',$client->id)->delete();

        ActionableTemplateEmailData::insert([
            'template_id' => $template->id,
            'email' => $client->email,
            'actionable_template_email_id' => $actionable_template_email->id,
            'client_id' => $client->id,
            'user_id' => auth()->id(),
            'duration' => 120,
            //'file' => $processed_template, To Do Add file name
        ]);

        if($request->session()->has('email_template') && $request->session()->get('email_template') != null) {
            $email_subject = $request->input('subject');
            $email_content = $request->session()->get('email_template');
        } else {
            $email_subject = $request->input('subject');

            if($request->has('email_content') && $request->input('email_content') != ""){
                $email_content = $request->input('email_content');
            } else {
                $email_content = EmailTemplate::where('id', $request->input('template_email'))->first()->email_content;
            }
        }

        $emails = explode(",", $request->input('email'));

        foreach ($emails as $email):
            Mail::to(trim($email))->send(new TemplateMail($client, $processed_templates, $email_subject, $email_content));

            $mail = new MailLog();
            $mail->date = now();
            $mail->from = config('mail.from.name') . ' <' . config('mail.from.address') . '>';
            $mail->to = $email;
            $mail->subject = $email_subject;
            $mail->body = $email_content;
            $mail->user_id = Auth::id();
            $mail->office_id = Auth::user()->office()->id;
            $mail->save();

            foreach ($processed_templates as $template):
                $attachment = new MailAttachmentLog();
                $attachment->mail_id = $mail->id;
                if ($template['type'] == 'template'){
                    $attachment->attachment = 'app/templates/' . $template['file'];
                } else {
                    $attachment->attachment = 'app/documents/' . $template['file'];
                }
                $attachment->name = $template['name'];
                $attachment->save();

            endforeach;
        endforeach;

        if($request->session()->has('email_template')){
            $request->session()->forget('email_template');
        }

        return response()->json(['success' => 'Template sent successfully.']);

    }

    public function sendDocument(Client $client, Activity $activity, Request $request)
    {
        $documents= Document::find($request->input('document_file'));

        $processed_documents= array();
        $processed_documents[0]['file'] = $this->processDocument($client, $documents->id, $documents->file, $documents->name);
        $processed_documents[0]['type'] = 'document';
        $processed_documents[0]['name'] = $documents->name;
//dd($this->processDocument($client, $documents->file));
        $actionable_documents_email = $activity->actionable;

        ActionableDocumentEmailData::where('email',$client->email)->where('actionable_document_email_id',$actionable_documents_email->id)->where('client_id',$client->id)->delete();

        ActionableDocumentEmailData::insert([
            'document_id' => $documents->id,
            'email' => $client->email,
            'actionable_document_email_id' => $actionable_documents_email->id,
            'client_id' => $client->id,
            'user_id' => auth()->id(),
            'duration' => 120,
            //'file' => $processed_template, To Do Add file name
        ]);

        if($request->session()->has('email_template') && $request->session()->get('email_template') != null) {
            $email_subject = $request->input('subject');
            $email_content = $request->session()->get('email_template');
        } else {
            $email_subject = $request->input('subject');

            if($request->has('email_content') && $request->input('email_content') != ""){
                $email_content = $request->input('email_content');
            } else {
                $email_content = EmailTemplate::where('id', $request->input('template_email'))->first()->email_content;
            }
        }

        $emails = explode(",", $request->input('email'));

        foreach ($emails as $email):
            Mail::to(trim($email))->send(new TemplateMail($client, $processed_documents, $email_subject, $email_content));

            $mail = new MailLog();
            $mail->date = now();
            $mail->from = config('mail.from.name') . ' <' . config('mail.from.address') . '>';
            $mail->to = $email;
            $mail->subject = $email_subject;
            $mail->body = $email_content;
            $mail->user_id = Auth::id();
            $mail->office_id = Auth::user()->office()->id;
            $mail->save();

            foreach ($processed_documents as $template):
                $attachment = new MailAttachmentLog();
                $attachment->mail_id = $mail->id;
                if ($template['type'] == 'template'){
                    $attachment->attachment = 'app/templates/' . $template['file'];
                } else {
                    $attachment->attachment = 'app/documents/' . $template['file'];
                }
                $attachment->name = $template['name'];
                $attachment->save();

            endforeach;
        endforeach;

        return response()->json(['success' => 'Template sent successfully.']);
    }

    public function sendDocuments(Client $client, Activity $activity, Request $request, Step $step)
    {
        //Todo
        $actionable_template_email = $activity->actionable;

        ActionableMultipleAttachmentData::where('email', $client->email)->where('actionable_ma_id', $actionable_template_email->id)->where('client_id', $client->id)->delete();
        //Send to all templates
        $processed_templates = array();
        $counter = 0;
        if ($request->input('templates')) {
            $templates = explode(',', $request->input('templates'));
            foreach ($templates as $template_id):
                if ($template_id != null && $template_id != '' && $template_id > 0) {
                    $template = Template::find($template_id);

                    $processed_templates[$counter]['file'] = $this->processTemplate($client, $template->id, $template->file, $template->name);
                    $processed_templates[$counter]['type'] = 'template';
                    $processed_templates[$counter]['name'] = $template->name;

                    ActionableMultipleAttachmentData::insert([
                        'template_id' => $template->id,
                        'email' => $request->input('email'),
                        'actionable_ma_id' => $actionable_template_email->id,
                        'client_id' => $client->id,
                        'user_id' => auth()->id(),
                        'duration' => 120,
                        'attachment_type' => 'template'
                        //'file' => $processed_template, To Do Add file name
                    ]);
                    $counter++;
                }
            endforeach;
        }

        if ($request->input('documents')) {
            $documents = explode(',', $request->input('documents'));
            foreach ($documents as $document_id):
                $document = Document::find($document_id);
                $processed_templates[$counter]['file'] = $this->processDocument($client, $document->id, $document->file, $document->name);
                $processed_templates[$counter]['type'] = 'document';
                $processed_templates[$counter]['name'] = $document->name;

                ActionableMultipleAttachmentData::insert([
                    'template_id' => $document->id,
                    'email' => $request->input('email'),
                    'actionable_ma_id' => $actionable_template_email->id,
                    'client_id' => $client->id,
                    'user_id' => auth()->id(),
                    'duration' => 120,
                    'attachment_type' => 'document'
                    //'file' => $processed_template, To Do Add file name
                ]);
                $counter++;
            endforeach;
        }

        //$email_signature = EmailSignature::where('user_id','=',auth()->id())->get();

        if ($request->session()->has('email_template') && $request->session()->get('email_template') != null) {
            $email_subject = $request->input('subject');
            $email_content = $request->session()->get('email_template');
        } else {
            $email_subject = $request->input('subject');
            if ($request->has('email_content') && $request->input('email_content') != "") {
                $email_content = $request->input('email_content');
            } else {
                $email_content = EmailTemplate::where('id', $request->input('template_email'))->first()->email_content;
            }
        }


        /*$a = new ActionableMultipleAttachmentData();
        $a->template_id = $request->input('template_email');
        $a->email = $request->input('email');
        $a->actionable_ma_id = $activity->actionable_id;
        $a->client_id = $client->id;
        $a->user_id = auth()->id();
        $a->duration = '120';
        $a->save();*/
        //'file' => $processed_template, To Do Add file name

        $emails = explode(",", $request->input('email'));

        foreach ($emails as $email):
            //Mail::to(trim($email))->send(new TemplateMail($client, $processed_templates, $email_content,$email_signature));
            Mail::to(trim($email))->send(new TemplateMail($client, $processed_templates, $email_subject, $email_content));

            $mail = new MailLog();
            $mail->date = now();
            $mail->from = config('mail.from.name') . ' <' . config('mail.from.address') . '>';
            $mail->to = $email;
            $mail->subject = $email_subject;
            $mail->body = $email_content;
            $mail->user_id = Auth::id();
            $mail->office_id = Auth::user()->office()->id;
            $mail->save();

            foreach ($processed_templates as $template):
                $attachment = new MailAttachmentLog();
                $attachment->mail_id = $mail->id;
                if ($template['type'] == 'template'){
                        $attachment->attachment = 'app/templates/' . $template['file'];
                } else {
                    $attachment->attachment = 'app/documents/' . $template['file'];
                }
                $attachment->name = $template['name'];
                $attachment->save();

            endforeach;

        endforeach;

        $request->session()->forget('email_template');

        return response()->json(['success' => 'Documents sent successfully.', 'docs' => $processed_templates]);
    }

    /*
    * @param Client
    * @param template file
    * @return the new generated processed template
    * Use to process a docx document, if document type is not .docx, return as it is
    */
    public function processTemplate(Client $client, $template_id, $template_file, $template_name)
    {

        $filename = $template_name;
        $ext = pathinfo($template_file, PATHINFO_EXTENSION);

        //Only process docx files for now
        if ($ext == "docx") {
            return $this->processWordTemplate($client, $template_file, $template_name);
        } elseif($ext == "pptx"){
            return $this->processPowerpointTemplate($client->id,$client->process_id,$template_id);
        } elseif($ext == "pdf"){
            return $this->processPdfTemplate($client->id,$client->process_id,$template_id);
        } else {
            return $template_file;
        }
    }

    public function processWordTemplate(Client $client, $template_file, $template_name)
    {

        $filename = $template_name;
        $ext = pathinfo($template_file, PATHINFO_EXTENSION);

        $client->load('referrer', 'introducer', 'business_unit');

        $templateProcessor = new TemplateProcessor(storage_path('app/templates/' . $template_file));
        $templateProcessor->setValue('date', date("Y/m/d"));
        $templateProcessor->setValue(
            ['client.first_name', 'client.last_name', 'client.email', 'client.contact', 'client.company', 'client.email', 'client.id_number', 'client.company_registration_number', 'client.cif_code', 'client.business_unit','client.committee','client.project','client.trigger_type','client.case_number','client.qa_start_date','client.qa_end_date','client.instruction_date','client.assigned_date','client.completed_date','client.out_of_scope'],
            [$client->first_name, $client->last_name, $client->email, $client->contact, $client->company, $client->email, $client->id_number, $client->company_registration_number, $client->cif_code, ($client->business_unit_id > 0 ? $client->business_unit->name : ''), ($client->committee_id ? $client->committee->name : ''), ($client->project_id > 0 ? $client->project->name : ''), ($client->trigger_type_id > 0 ? $client->trigger->name : ''),$client->case_number,$client->qa_start_date,$client->qa_end_date,$client->instruction_date,$client->assigned_date,$client->completed_date,($client->out_of_scope == '1' ? 'Yes' : 'No')]
        );

        $templateProcessor->setValue(
            ['introducer.first_name', 'introducer.last_name', 'introducer.email', 'introducer.contact'],
            isset($client->introducer) ?
                [
                    $client->introducer->first_name,
                    $client->introducer->last_name,
                    $client->introducer->email,
                    $client->introducer->contact
                ] :
                ['', '', '', '']
        );

        $client_id = $client->id;
        $process_id = $client->process_id;
        $var_array = array();
        $value_array = array();

        $steps = Step::with(['activities.actionable.data'=>function ($q) use ($client_id){
            $q->where('client_id',$client_id);
        }])->where('process_id',$process_id)->get();

        foreach($steps as $step) {

            foreach ($step["activities"] as $activity) {
                $var = '';
                switch ($activity['actionable_type']){
                    case 'App\ActionableDropdown':
                        $var = 'activity.'.strtolower(str_replace(' ', '_', $activity->name));
                        array_push($var_array,$var);
                        break;

                    default:
                        $var = 'activity.'.strtolower(str_replace(' ', '_', $activity->name));
                        array_push($var_array, $var);
                        break;
                }

                if (isset($activity["actionable"]->data) && count($activity["actionable"]->data) > 0) {
                    foreach ($activity["actionable"]->data as $value) {

                        switch ($activity['actionable_type']){
                            case 'App\ActionableDropdown':

                                $data = ActionableDropdownItem::where('id',$value->actionable_dropdown_item_id)->first();
                                if($data){
                                    array_push($value_array, $data["name"]);
                                } else {
                                    array_push($value_array, '');
                                }
                                break;
                            case 'App\ActionableBoolean':
                                $items = ActionableBooleanData::where('client_id',$client_id)->where('actionable_boolean_id',$value->actionable_boolean_id)->first();

                                if($items){
                                    array_push($value_array, ($items->data == '0' ? 'No' : 'Yes'));
                                } else {
                                    array_push($value_array, '');
                                }

                                break;
                            default:

                                array_push($value_array, $value->data);
                                break;
                        }
                    }
                } else {

                    switch ($activity['actionable_type']){
                        case 'App\ActionableDropdown':
                            $items = ActionableDropdownItem::where('actionable_dropdown_id',$activity["actionable_id"])->get();
                            if($items){
                                foreach ($items as $item) {

                                    array_push($value_array, '');

                                }
                            } else {
                                array_push($value_array, '');
                            }

                            break;
                        default:

                            array_push($value_array, '');
                            break;
                    }
                }
            }
        }

        $templateProcessor->setValue(
            $var_array,$value_array
        );

        //Create directory to store processed templates, for future reference or to check what was sent to the client
        $processed_template_path = 'processedtemplates/' . date("Y") . DIRECTORY_SEPARATOR . date("m");
        if (!File::exists(storage_path('app/templates/' . $processed_template_path))) {
            Storage::makeDirectory('templates/' . $processed_template_path);
        }

        $processed_template = $processed_template_path . DIRECTORY_SEPARATOR . str_replace(' ','_',strtolower($filename)) . "_" . $client->id . "_" . date("Y_m_d_H_i_s") . "_" . uniqid() . ".docx";

        $templateProcessor->saveAs(storage_path('app/templates/' . $processed_template));

        return $processed_template;

    }

    public function processPowerpointTemplate($client_id,$process_id,$template_id)
    {
        // Grab the client
        $client = Client::where('id',$client_id);

        // Client details in an array
        $client = $client->first()->toArray();

        // What will eventually be sent to the report
        $output = [];
        $processData = [];

        // We have a client
        if($client) {
            // loop over steps to get the activity names, storing them in an assoc. array
            $steps = Step::with(['process'=> function($q) use ($process_id) {
                $q->where('id',$process_id);
            }])->orderBy('id')->get();

            foreach($steps as $step) {
                $activities = Activity::with(['actionable.data'=>function($query) use ($client){
                    $query->where('client_id',$client["id"])->orderBy('created_at','desc');
                }])->get();

                foreach($activities as $activity) {
                    if (strpos($activity['actionable_type'], 'Actionable') !== false) {
                        $completed_activity_clients_data = null;
                        switch ($activity['actionable_type']) {
                            case 'App\ActionableBoolean':
                                $filter_data = ['' => 'All', 0 => 'No', 1 => 'Yes', 2 => 'Not Completed'];
                                $completed_activity_clients_data = ActionableBooleanData::where('actionable_boolean_id', $activity['actionable_id'])
                                    ->select('client_id', 'data')
                                    ->distinct()
                                    ->pluck('data', 'client_id');
                                break;
                            case 'App\ActionableDate':
                                $filter_data = ['' => 'All', 1 => 'Completed', 0 => 'Not Completed'];
                                $completed_activity_clients_data = ActionableDateData::where('actionable_date_id', $activity['actionable_id'])
                                    ->select('client_id', 'data')
                                    ->distinct()
                                    ->pluck('data', 'client_id');
                                break;
                            case 'App\ActionableText':
                                $filter_data = ['' => 'All', 1 => 'Completed', 0 => 'Not Completed'];
                                $completed_activity_clients_data = ActionableTextData::where('actionable_text_id', $activity['actionable_id'])
                                    ->select('client_id', 'data')
                                    ->distinct()
                                    ->pluck('data', 'client_id');
                                break;
                            case 'App\ActionableTextarea':
                                $filter_data = ['' => 'All', 1 => 'Completed', 0 => 'Not Completed'];
                                $completed_activity_clients_data = ActionableTextareaData::where('actionable_textarea_id', $activity['actionable_id'])
                                    ->select('client_id', 'data')
                                    ->distinct()
                                    ->pluck('data', 'client_id');
                                break;
                            case 'App\ActionableDropdown':
                                $filter_data = ActionableDropdownItem::where('actionable_dropdown_id', $activity['actionable_id'])
                                    ->select('name', 'id')
                                    ->distinct()
                                    ->pluck('name', 'id')
                                    ->prepend('All', 0);

                                /*if ($request->has('activity') && $request->input('activity') != '') {*/
                                $completed_activity_clients_data = ActionableDropdownData::where('actionable_dropdown_id', $activity['actionable_id'])

                                    ->select('client_id', 'actionable_dropdown_item_id')
                                    ->distinct()
                                    //->get()->toArray();
                                    ->pluck('actionable_dropdown_item_id', 'client_id');
                                /*} else {
                                    $completed_activity_clients_data = ActionableDropdownData::where('actionable_dropdown_id', $activity['actionable_id'])
                                        ->select('client_id', 'actionable_dropdown_item_id')
                                        ->distinct()
                                        //->get()->toArray();
                                        ->pluck('actionable_dropdown_item_id', 'client_id');
                                }*/

                                // $tmp_filter_data2 = $filter_data->toArray();
                                // foreach($tmp_filter_data2 as $key=>$value):
                                //     array_push($tmp_filter_data, $key);
                                // endforeach;
                                break;
                            case 'App\ActionableDocument':
                                $filter_data = ['' => 'All', 1 => 'Completed', 0 => 'Not Completed'];
                                $completed_activity_clients_data = ActionableDocumentData::where('actionable_document_id', $activity['actionable_id'])
                                    ->select('client_id', 'actionable_document_id')
                                    ->distinct()
                                    ->pluck('actionable_document_id', 'client_id');
                                break;
                            case 'App\ActionableTemplateEmail':
                                $filter_data = Template::orderBy('name')
                                    ->select('name', 'id')
                                    ->distinct()
                                    ->pluck('name', 'id')
                                    ->prepend('All', 0);
                                $completed_activity_clients_data = ActionableTemplateEmailData::where('actionable_template_email_id', $activity['actionable_id'])
                                    ->select('client_id', 'template_id')
                                    ->distinct()
                                    ->pluck('template_id', 'client_id');
                                break;
                            case 'App\ActionableNotification':
                                $filter_data = ['' => 'All', 1 => 'Sent', 0 => 'Not Sent'];
                                $completed_activity_clients_data = ActionableNotificationData::where('actionable_notification_id', $activity['actionable_id'])
                                    ->select('client_id', 'actionable_notification_id')
                                    ->distinct()
                                    ->pluck('actionable_notification_id', 'client_id');
                                break;
                            case 'App\ActionableMultipleAttachment':
                                $filter_data = Template::orderBy('name')
                                    ->select('name', 'id')
                                    ->distinct()
                                    ->pluck('name', 'id')
                                    ->prepend('All', 0);
                                $completed_activity_clients_data = ActionableMultipleAttachmentData::where('actionable_ma_id', $activity['actionable_id'])
                                    ->select('client_id', 'template_id')
                                    ->distinct()
                                    ->pluck('template_id', 'client_id');
                                break;
                            default:
                                //todo capture defaults
                                break;
                        }

                        $data_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? $completed_activity_clients_data[$client['id']] : '';
                        $completed_value = '';
                        $selected_drop_down_names = '';

                        $data = '';
                        $yn_value = '';
                        switch ($activity['actionable_type']) {
                            case 'App\ActionableBoolean':
                                $completed_value = isset($completed_activity_clients_data[$client['id']]) ? 'Yes' : 'No';
                                $activity_value = isset($completed_activity_clients_data[$client['id']]) ? $completed_activity_clients_data[$client['id']] : 2;
                                if (isset($completed_activity_clients_data[$client['id']]) && $completed_activity_clients_data[$client['id']] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($completed_activity_clients_data[$client['id']]) && $completed_activity_clients_data[$client['id']] == '0') {
                                    $yn_value = "No";
                                }
                                break;
                            case 'App\ActionableDate':
                                $completed_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? $completed_activity_clients_data[$client['id']] : '';
                                $activity_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? 1 : 0;
                                break;
                            case 'App\ActionableText':
                                $completed_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? $completed_activity_clients_data[$client["id"]] : '';
                                $activity_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? 1 : 0;
                                break;
                            case 'App\ActionableTextarea':
                                $completed_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? $completed_activity_clients_data[$client["id"]] : '';
                                $activity_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? 1 : 0;
                                break;
                            case 'App\ActionableDropdown':
                                $data_value = '';
                                /*if ($request->has('s') && $request->input('s') != '') {
                                    $selected_drop_down_items = ActionableDropdownData::with('item')->where('actionable_dropdown_id', $activity['actionable_id'])
                                        ->where('client_id', $client['id'])
                                        ->select('actionable_dropdown_item_id')
                                        ->distinct()
                                        ->get()->toArray();

                                    foreach ($selected_drop_down_items as $key => $selected_drop_down_item):
                                        //dd($selected_drop_down_item);
                                        if (in_array($selected_drop_down_item['actionable_dropdown_item_id'], $tmp_filter_data)) {
                                            if ($key == sizeof($selected_drop_down_items) - 1)
                                                $data_value .= $selected_drop_down_item['item']['name'];
                                            else
                                                $data_value .= $selected_drop_down_item['item']['name'] . ', ';
                                        }
                                    endforeach;
                                }*/
                                $data = ActionableDropdownData::with('item')->where('client_id', $client['id'])->where('actionable_dropdown_id', $activity['actionable_id'])->first();
                                //dd($data->item->name);
                                $activity_data_value = isset($completed_activity_clients_data[$client['id']]) ? (isset($data->item->name) && $data->item->name != null ? $data->item->name : '') : '';
                                $completed_value = isset($completed_activity_clients_data[$client['id']]) ? (isset($data->item->name) && $data->item->name != null ? $data->item->name : '') : '';
                                $activity_value = isset($completed_activity_clients_data[$client['id']]) ? $completed_activity_clients_data[$client['id']] : 0;
                                break;
                            case 'App\ActionableDocument':
                                $completed_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? 'Completed' : 'Not Completed';

                                $activity_value = isset($completed_activity_clients_data[$client['id']]) ? $completed_activity_clients_data[$client['id']] : 0;
                                break;
                            case 'App\ActionableTemplateEmail':
                                $completed_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? 'Completed' : 'Not Completed';
                                $activity_value = isset($completed_activity_clients_data[$client['id']]) ? $completed_activity_clients_data[$client['id']] : 0;
                                break;
                            case 'App\ActionableNotification':
                                $completed_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? 'Completed' : 'Not Completed';
                                $activity_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? 1 : 0;
                                break;
                            case 'App\ActionableMultipleAttachment':
                                $completed_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? 'Completed' : 'Not Completed';
                                $activity_value = isset($completed_activity_clients_data[$client['id']]) ? $completed_activity_clients_data[$client['id']] : 0;
                                break;
                            default:
                                //todo capture defaults
                                break;

                        }


                        $processData[1] [] = ['name' => strtolower(str_replace(' ', '_', $activity['name'])),
                            'data' => $completed_value];


                    }
                }
            }
        }

        // Get the pptx template
        $template = Template::where('id', $template_id)->first();

        $presentation = new Presentation($template->file, ['client' => $client, 'activities' => $processData[1]]);

        // do whatevs
        $presentation->run();
        $downloadFile = $presentation->getDownloadPath();

        $headers = array(
            'Content-Type: application/vnd.ms-powerpoint',
        );

        //Create directory to store processed templates, for future reference or to check what was sent to the client
        $processed_template_path = 'processedtemplates/' . date("Y") . DIRECTORY_SEPARATOR . date("m");
        if (!File::exists(storage_path('app/templates/' . $processed_template_path))) {
            Storage::makeDirectory('templates/' . $processed_template_path);
        }

        $processed_template = $processed_template_path . DIRECTORY_SEPARATOR . str_replace(' ','_',strtolower($template->name)) . "_" . $client["id"] . "_" . date("Y_m_d_H_i_s") . "_" . uniqid() . ".pptx";

        $destinationPath= storage_path()."/app/templates/test.pptx";
        $success = \File::copy($downloadFile,storage_path('/app/templates/'.$processed_template));

        return $processed_template;
    }

    public function processDocument(Client $client, $template_id, $template_file, $document_name)
    {
        $filename = $document_name;
        $ext = pathinfo($template_file, PATHINFO_EXTENSION);

        //Only process docx files for now
        if ($ext == "docx") {
            return $this->processWordDocument($client, $template_file, $document_name);
        } elseif($ext == "pptx"){
            return $this->processPowerpointDocument($client->id,$client->process_id,$template_id);
        } else {
            return $template_file;
        }
    }

    public function processWordDocument(Client $client, $template_file, $document_name)
    {
        $filename = $document_name;
        $ext = pathinfo($template_file, PATHINFO_EXTENSION);

        //Only process docx files for now
        if ($ext != "docx") {
            return $template_file;
        }

        $client->load('referrer', 'introducer','business_unit');

        $templateProcessor = new TemplateProcessor(storage_path('app/documents/' . $template_file));
        $templateProcessor->setValue('date', date("Y/m/d"));
        $templateProcessor->setValue(
            ['client.first_name', 'client.last_name', 'client.email', 'client.contact', 'client.company', 'client.email', 'client.id_number', 'client.company_registration_number', 'client.cif_code', 'client.business_unit'],
            [$client->first_name, $client->last_name, $client->email, $client->contact, $client->company, $client->email, $client->id_number, $client->company_registration_number, $client->cif_code, $client->business_unit->name]
        );

        $templateProcessor->setValue(
            ['referrer.first_name', 'referrer.last_name', 'referrer.email', 'referrer.avatar'],
            isset($client->referrer) ?
                [
                    $client->referrer->first_name,
                    $client->referrer->last_name,
                    $client->referrer->email,
                    $client->referrer->contact
                ] :
                ['', '', '', '']
        );

        $templateProcessor->setValue(
            ['introducer.first_name', 'introducer.last_name', 'introducer.email', 'introducer.contact'],
            isset($client->introducer) ?
                [
                    $client->introducer->first_name,
                    $client->introducer->last_name,
                    $client->introducer->email,
                    $client->introducer->contact
                ] :
                ['', '', '', '']
        );

        $client_id = $client->id;
        $process_id = $client->process_id;
        $var_array = array();
        $value_array = array();

        $steps = Step::with(['activities.actionable.data'=>function ($q) use ($client_id){
            $q->where('client_id',$client_id);
        }])->where('process_id',$process_id)->get();

        foreach($steps as $step) {

            foreach ($step["activities"] as $activity) {
                $var = '';
                switch ($activity['actionable_type']){
                    case 'App\ActionableDropdown':
                        $var = 'activity.'.strtolower(str_replace(' ', '_', $activity->name));
                        array_push($var_array,$var);
                        break;

                    default:
                        $var = 'activity.'.strtolower(str_replace(' ', '_', $activity->name));
                        array_push($var_array, $var);
                        break;
                }

                if (isset($activity["actionable"]->data) && count($activity["actionable"]->data) > 0) {
                    foreach ($activity["actionable"]->data as $value) {

                        switch ($activity['actionable_type']){
                            case 'App\ActionableDropdown':

                                $data = ActionableDropdownItem::where('id',$value->actionable_dropdown_item_id)->first();
                                if($data){
                                    array_push($value_array, $data["name"]);
                                } else {
                                    array_push($value_array, '');
                                }
                                break;
                            case 'App\ActionableBoolean':
                                $items = ActionableBooleanData::where('client_id',$client_id)->where('actionable_boolean_id',$value->actionable_boolean_id)->first();

                                if($items){
                                    array_push($value_array, ($items->data == '0' ? 'No' : 'Yes'));
                                } else {
                                    array_push($value_array, '');
                                }

                                break;
                            default:

                                array_push($value_array, $value->data);
                                break;
                        }
                    }
                } else {

                    switch ($activity['actionable_type']){
                        case 'App\ActionableDropdown':
                            $items = ActionableDropdownItem::where('actionable_dropdown_id',$activity["actionable_id"])->get();
                            if($items){
                                foreach ($items as $item) {

                                    array_push($value_array, '');

                                }
                            } else {
                                array_push($value_array, '');
                            }

                            break;
                        default:

                            array_push($value_array, '');
                            break;
                    }
                }
            }
        }

        $templateProcessor->setValue(
            $var_array,$value_array
        );

        //Create directory to store processed templates, for future reference or to check what was sent to the client
        $processed_template_path = 'processeddocuments/' . date("Y") . DIRECTORY_SEPARATOR . date("m");
        if (!File::exists(storage_path('app/documents/' . $processed_template_path))) {
            Storage::makeDirectory('documents/' . $processed_template_path);
        }

        $processed_template = $processed_template_path . DIRECTORY_SEPARATOR . str_replace(' ','_',strtolower($filename)) . "_" . $client->id . "_" . date("Y_m_d_H_i_s") . "_" . uniqid() . ".docx";

        $templateProcessor->saveAs(storage_path('app/documents/' . $processed_template));

        return $processed_template;

    }

    public function processPowerpointDocument($client_id,$process_id,$template_id)
    {
        // Grab the client
        $client = Client::where('id',$client_id);

        // Client details in an array
        $client = $client->first()->toArray();

        // What will eventually be sent to the report
        $output = [];
        $processData = [];

        // We have a client
        if($client) {
            // loop over steps to get the activity names, storing them in an assoc. array
            $steps = Step::with(['process'=> function($q) use ($process_id) {
                $q->where('id',$process_id);
            }])->orderBy('id')->get();

            foreach($steps as $step) {
                $activities = Activity::with(['actionable.data'=>function($query) use ($client){
                    $query->where('client_id',$client["id"])->orderBy('created_at','desc');
                }])->get();

                foreach($activities as $activity) {
                    if (strpos($activity['actionable_type'], 'Actionable') !== false) {
                        $completed_activity_clients_data = null;
                        switch ($activity['actionable_type']) {
                            case 'App\ActionableBoolean':
                                $filter_data = ['' => 'All', 0 => 'No', 1 => 'Yes', 2 => 'Not Completed'];
                                $completed_activity_clients_data = ActionableBooleanData::where('actionable_boolean_id', $activity['actionable_id'])
                                    ->select('client_id', 'data')
                                    ->distinct()
                                    ->pluck('data', 'client_id');
                                break;
                            case 'App\ActionableDate':
                                $filter_data = ['' => 'All', 1 => 'Completed', 0 => 'Not Completed'];
                                $completed_activity_clients_data = ActionableDateData::where('actionable_date_id', $activity['actionable_id'])
                                    ->select('client_id', 'data')
                                    ->distinct()
                                    ->pluck('data', 'client_id');
                                break;
                            case 'App\ActionableText':
                                $filter_data = ['' => 'All', 1 => 'Completed', 0 => 'Not Completed'];
                                $completed_activity_clients_data = ActionableTextData::where('actionable_text_id', $activity['actionable_id'])
                                    ->select('client_id', 'data')
                                    ->distinct()
                                    ->pluck('data', 'client_id');
                                break;
                            case 'App\ActionableTextarea':
                                $filter_data = ['' => 'All', 1 => 'Completed', 0 => 'Not Completed'];
                                $completed_activity_clients_data = ActionableTextareaData::where('actionable_textarea_id', $activity['actionable_id'])
                                    ->select('client_id', 'data')
                                    ->distinct()
                                    ->pluck('data', 'client_id');
                                break;
                            case 'App\ActionableDropdown':
                                $filter_data = ActionableDropdownItem::where('actionable_dropdown_id', $activity['actionable_id'])
                                    ->select('name', 'id')
                                    ->distinct()
                                    ->pluck('name', 'id')
                                    ->prepend('All', 0);

                                /*if ($request->has('activity') && $request->input('activity') != '') {*/
                                $completed_activity_clients_data = ActionableDropdownData::where('actionable_dropdown_id', $activity['actionable_id'])

                                    ->select('client_id', 'actionable_dropdown_item_id')
                                    ->distinct()
                                    //->get()->toArray();
                                    ->pluck('actionable_dropdown_item_id', 'client_id');
                                /*} else {
                                    $completed_activity_clients_data = ActionableDropdownData::where('actionable_dropdown_id', $activity['actionable_id'])
                                        ->select('client_id', 'actionable_dropdown_item_id')
                                        ->distinct()
                                        //->get()->toArray();
                                        ->pluck('actionable_dropdown_item_id', 'client_id');
                                }*/

                                // $tmp_filter_data2 = $filter_data->toArray();
                                // foreach($tmp_filter_data2 as $key=>$value):
                                //     array_push($tmp_filter_data, $key);
                                // endforeach;
                                break;
                            case 'App\ActionableDocument':
                                $filter_data = ['' => 'All', 1 => 'Completed', 0 => 'Not Completed'];
                                $completed_activity_clients_data = ActionableDocumentData::where('actionable_document_id', $activity['actionable_id'])
                                    ->select('client_id', 'actionable_document_id')
                                    ->distinct()
                                    ->pluck('actionable_document_id', 'client_id');
                                break;
                            case 'App\ActionableTemplateEmail':
                                $filter_data = Template::orderBy('name')
                                    ->select('name', 'id')
                                    ->distinct()
                                    ->pluck('name', 'id')
                                    ->prepend('All', 0);
                                $completed_activity_clients_data = ActionableTemplateEmailData::where('actionable_template_email_id', $activity['actionable_id'])
                                    ->select('client_id', 'template_id')
                                    ->distinct()
                                    ->pluck('template_id', 'client_id');
                                break;
                            case 'App\ActionableNotification':
                                $filter_data = ['' => 'All', 1 => 'Sent', 0 => 'Not Sent'];
                                $completed_activity_clients_data = ActionableNotificationData::where('actionable_notification_id', $activity['actionable_id'])
                                    ->select('client_id', 'actionable_notification_id')
                                    ->distinct()
                                    ->pluck('actionable_notification_id', 'client_id');
                                break;
                            case 'App\ActionableMultipleAttachment':
                                $filter_data = Template::orderBy('name')
                                    ->select('name', 'id')
                                    ->distinct()
                                    ->pluck('name', 'id')
                                    ->prepend('All', 0);
                                $completed_activity_clients_data = ActionableMultipleAttachmentData::where('actionable_ma_id', $activity['actionable_id'])
                                    ->select('client_id', 'template_id')
                                    ->distinct()
                                    ->pluck('template_id', 'client_id');
                                break;
                            default:
                                //todo capture defaults
                                break;
                        }

                        $data_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? $completed_activity_clients_data[$client['id']] : '';
                        $completed_value = '';
                        $selected_drop_down_names = '';

                        $data = '';
                        $yn_value = '';
                        switch ($activity['actionable_type']) {
                            case 'App\ActionableBoolean':
                                $completed_value = isset($completed_activity_clients_data[$client['id']]) ? 'Yes' : 'No';
                                $activity_value = isset($completed_activity_clients_data[$client['id']]) ? $completed_activity_clients_data[$client['id']] : 2;
                                if (isset($completed_activity_clients_data[$client['id']]) && $completed_activity_clients_data[$client['id']] == '1') {
                                    $yn_value = "Yes";
                                }
                                if (isset($completed_activity_clients_data[$client['id']]) && $completed_activity_clients_data[$client['id']] == '0') {
                                    $yn_value = "No";
                                }
                                break;
                            case 'App\ActionableDate':
                                $completed_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? $completed_activity_clients_data[$client['id']] : '';
                                $activity_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? 1 : 0;
                                break;
                            case 'App\ActionableText':
                                $completed_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? $completed_activity_clients_data[$client["id"]] : '';
                                $activity_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? 1 : 0;
                                break;
                            case 'App\ActionableTextarea':
                                $completed_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? $completed_activity_clients_data[$client["id"]] : '';
                                $activity_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? 1 : 0;
                                break;
                            case 'App\ActionableDropdown':
                                $data_value = '';
                                /*if ($request->has('s') && $request->input('s') != '') {
                                    $selected_drop_down_items = ActionableDropdownData::with('item')->where('actionable_dropdown_id', $activity['actionable_id'])
                                        ->where('client_id', $client['id'])
                                        ->select('actionable_dropdown_item_id')
                                        ->distinct()
                                        ->get()->toArray();

                                    foreach ($selected_drop_down_items as $key => $selected_drop_down_item):
                                        //dd($selected_drop_down_item);
                                        if (in_array($selected_drop_down_item['actionable_dropdown_item_id'], $tmp_filter_data)) {
                                            if ($key == sizeof($selected_drop_down_items) - 1)
                                                $data_value .= $selected_drop_down_item['item']['name'];
                                            else
                                                $data_value .= $selected_drop_down_item['item']['name'] . ', ';
                                        }
                                    endforeach;
                                }*/
                                $data = ActionableDropdownData::with('item')->where('client_id', $client['id'])->where('actionable_dropdown_id', $activity['actionable_id'])->first();
                                //dd($data->item->name);
                                $activity_data_value = isset($completed_activity_clients_data[$client['id']]) ? (isset($data->item->name) && $data->item->name != null ? $data->item->name : '') : '';
                                $completed_value = isset($completed_activity_clients_data[$client['id']]) ? (isset($data->item->name) && $data->item->name != null ? $data->item->name : '') : '';
                                $activity_value = isset($completed_activity_clients_data[$client['id']]) ? $completed_activity_clients_data[$client['id']] : 0;
                                break;
                            case 'App\ActionableDocument':
                                $completed_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? 'Completed' : 'Not Completed';

                                $activity_value = isset($completed_activity_clients_data[$client['id']]) ? $completed_activity_clients_data[$client['id']] : 0;
                                break;
                            case 'App\ActionableTemplateEmail':
                                $completed_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? 'Completed' : 'Not Completed';
                                $activity_value = isset($completed_activity_clients_data[$client['id']]) ? $completed_activity_clients_data[$client['id']] : 0;
                                break;
                            case 'App\ActionableNotification':
                                $completed_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? 'Completed' : 'Not Completed';
                                $activity_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? 1 : 0;
                                break;
                            case 'App\ActionableMultipleAttachment':
                                $completed_value = isset($completed_activity_clients_data[$client['id']]) && trim($completed_activity_clients_data[$client['id']]) != '' ? 'Completed' : 'Not Completed';
                                $activity_value = isset($completed_activity_clients_data[$client['id']]) ? $completed_activity_clients_data[$client['id']] : 0;
                                break;
                            default:
                                //todo capture defaults
                                break;

                        }


                        $processData[1] [] = ['name' => strtolower(str_replace(' ', '_', $activity['name'])),
                            'data' => $completed_value];


                    }
                }
            }
        }

        // Get the pptx template
        $template = Document::where('id', $template_id)->first();

        $presentation = new Presentation($template->file, ['client' => $client, 'activities' => $processData[1]]);

        // do whatevs
        $presentation->run();
        $downloadFile = $presentation->getDownloadPath();

        $headers = array(
            'Content-Type: application/vnd.ms-powerpoint',
        );

        //Create directory to store processed templates, for future reference or to check what was sent to the client
        $processed_template_path = 'processeddocuments/' . date("Y") . DIRECTORY_SEPARATOR . date("m");
        if (!File::exists(storage_path('app/documents/' . $processed_template_path))) {
            Storage::makeDirectory('documents/' . $processed_template_path);
        }

        $processed_template = $processed_template_path . DIRECTORY_SEPARATOR . str_replace(' ','_',strtolower($template->name)) . "_" . $client["id"] . "_" . date("Y_m_d_H_i_s") . "_" . uniqid() . ".pptx";

        $success = \File::copy($downloadFile,storage_path('/app/documents/'.$processed_template));

        return $processed_template;
    }

    public function sendNotification(Client $client, Activity $activity, Request $request)
    {
        //Notification update
        $log = new Log;
        $log->client_id = $client->id;
        $log->user_id = auth()->id();
        $log->save();

        $client->load('users');

        $activity_log = new ActivityLog;
        $activity_log->log_id = $log->id;
        $activity_log->activity_id = $activity->id;
        $activity_log->activity_name = $activity->name;
        $activity_log->old_value = $request->input('old_'.$activity->id);
        $activity_log->new_value = implode(',',$request->input('notification_user'));
        $activity_log->save();


        $notification = new Notification;
        $notification->name = $client->company . ' has been updated: ' . $activity->name;
        $notification->link = route('activitieslog', $log->id);
        $notification->type = '1';
        //$notification->link = route('clients.progress', $client).'/1';
        $notification->save();

        Mail::to(auth()->user()->email)->send(new NotificationMail($notification->name, $notification->link));

        $actionable_notification_data = new ActionableNotificationData;
        $actionable_notification_data->actionable_notification_id = $activity->actionable_id;
        $actionable_notification_data->notification_id = $notification->id;
        $actionable_notification_data->client_id = $client->id;
        $actionable_notification_data->user_id = auth()->id();
        $actionable_notification_data->duration = 120;
        $actionable_notification_data->save();

        $user_notifications = [];

        if (!is_null($client->introducer_id)) {
            array_push($user_notifications, [
                'user_id' => $client->introducer_id,
                'notification_id' => $notification->id
            ]);
        }

        if (!is_null($client->user_id)) {
            array_push($user_notifications, [
                'user_id' => $activity->user_id,
                'notification_id' => $notification->id
            ]);
        }

        if ( $request->has('notification_user') && (!empty($request->input('notification_user')))){
            $notification_users = $request->input('notification_user');
            foreach($notification_users as $notification_user):
                array_push($user_notifications, [
                    'user_id' => (int)$notification_user,
                    'notification_id' => $notification->id
                ]);
            endforeach;
        }

        NotificationEvent::dispatch($client->introducer_id, $notification);  

        foreach ($client->users as $user) {
            array_push($user_notifications, [
                'user_id' => $user->id,
                'notification_id' => $notification->id
            ]);

            NotificationEvent::dispatch($user->id, $notification);
        }

        UserNotification::insert($user_notifications);

        return response()->json(['success' => 'Template sent successfully.']);
    }

    public function activityNotification(Client $client, $log_id)
    {
        $client->load('users');

        $notification = new Notification;
        $notification->name = $client->company . ' Notification for : ' . $client->company;
        $notification->link = route('activitieslog', $log_id);
        $notification->type = '1';
        $notification->save();

        Mail::to(auth()->user()->email)->send(new NotificationMail($notification->name, $notification->link));

        $user_notifications = [];
        array_push($user_notifications, [
            'user_id' => $client->introducer_id,
            'notification_id' => $notification->id
        ]);

        NotificationEvent::dispatch($client->introducer_id, $notification);

        foreach ($client->users as $user) {
            array_push($user_notifications, [
                'user_id' => $user->id,
                'notification_id' => $notification->id
            ]);

            NotificationEvent::dispatch($user->id, $notification);
        }

        UserNotification::insert($user_notifications);

        return true;
    }

    public function getComment(Client $client, Request $request)
    {
        $comments = ClientComment::where('client_id',$client->id)->orderBy('created_at','DESC')->get();

        $comment_array = array();

        foreach ($comments as $comment){
            array_push($comment_array,[
                'id' => $comment->id,
                'title' => $comment->title,
                'comment' => $comment->comment,
                'user_id' => $comment->user_id,
                'user_name' => $comment->user->first_name.' '.$comment->user->last_name,
                'cdate' => Carbon::parse($comment->created_at)->format('Y-m-d')
            ]);
        }

        return response()->json(['message' => 'Success','data'=>$comment_array]);
        //return redirect()->back()->with('flash_success', 'Comment added successfully');
    }

    public function storeComment(Client $client, Request $request)
    {
        $comment = new ClientComment;
        $comment->client_id = $client->id;
        $comment->user_id = auth()->id();
        $comment->title = $request->input('title');
        $comment->comment = $request->input('comment');
        $comment->save();

        return response()->json('Comment added successfully');
    }

    public function deleteComment(Request $request,$comment)
    {
        ClientComment::destroy($request->input('comment'));

        return response()->json('Comment deleted successfully');
    }

    public function createClientActivity($token, Request $request)
    {
        $client_activity = ClientActivity::where('token', $token)->firstOrFail();

        $client_activity->load('activity.actionable.data', 'client');

        $activity = [
            'name' => $client_activity->activity->name,
            'type' => $client_activity->activity->getTypeName(),
            'id' => $client_activity->activity_id,
            'value' => (isset($client_activity->activity->actionable['data'][0])) ? $client_activity->activity->actionable['data'][0] : ''
        ];

        $parameters = [
            'client' => $client_activity->client->load('introducer'),
            'activity' => $activity,
            'client_activity' => $client_activity
        ];

        return view('clients.clientactivity')->with($parameters);
    }

    public function storeClientActivity($token, Request $request)
    {
        $client_activity = ClientActivity::where('token', $token)->firstOrFail();

        $client_activity->load('client','activity');

        //activity type hook
        switch ($client_activity->activity->actionable_type) {
            case 'App\ActionableBoolean':
                ActionableBooleanData::insert([
                    'data' => $request->input($client_activity->activity->id),
                    'actionable_boolean_id' => $client_activity->activity->actionable_id,
                    'client_id' => $client_activity->client->id,
                    'user_id' => auth()->id(),
                    'duration' => 120
                ]);
                break;
            case 'App\ActionableDate':
                ActionableDateData::insert([
                    'data' => $request->input($client_activity->activity->id),
                    'actionable_date_id' => $client_activity->activity->actionable_id,
                    'client_id' => $client_activity->client->id,
                    'user_id' => auth()->id(),
                    'duration' => 120
                ]);
                break;
            case 'App\ActionableText':
                ActionableTextData::insert([
                    'data' => $request->input($client_activity->activity->id),
                    'actionable_text_id' => $client_activity->activity->actionable_id,
                    'client_id' => $client_activity->client->id,
                    'user_id' => auth()->id(),
                    'duration' => 120
                ]);
                break;
            case 'App\ActionableDocument':
                ActionableDocumentData::insert([
                    'actionable_document_id' => $client_activity->activity->actionable_id,
                    'document_id' => $request->input($client_activity->activity->id),
                    'client_id' => $client_activity->client->id,
                    'user_id' => auth()->id()
                ]);
                break;
            case 'App\ActionableDropdown':
                ActionableDropdownData::insert([
                    'actionable_dropdown_id' => $client_activity->activity->actionable_id,
                    'actionable_dropdown_item_id' => $request->input($client_activity->activity->id),
                    'client_id' => $client_activity->client->id,
                    'user_id' => auth()->id(),
                    'duration' => 120
                ]);
                break;
            default:
                //todo capture defaults
                break;
        }

        return redirect()->back()->with(['flash_success' => "Information updated successfully."]);
    }

    public function storeProgressing(Client $client){
        $client->is_progressing = !$client->is_progressing;
        if($client->is_progressing == 0) {
            $client->not_progressing_date = now();
        } else {
            $client->not_progressing_date = null;
        }

        $client->save();

        return redirect()->back()->with(['flash_info'=>'Client status updated successfully.']);
    }

    public function approval(Client $client, Request $request){
        $client->needs_approval = false;
        $client->save();

        if(!$request->input('status')){
            $client->delete();

            return redirect(route('clients.index'))->with(['flash_info'=>'Client declined successfully.']);
        }

        return redirect()->back()->with(['flash_info'=>'Client approved successfully.']);
    }

    public function completeStep(Client $client, Process $process, Step $step){
        $step->load('activities.actionable.data');
        //dd($step);
        $activities_auto_completed = [];
        //return null;
        //todo just change switch logic to assign fake data for all activities
        foreach($step->activities as $activity){

            //Check if activity is not already set/completed
            if(!$client->isActivitieCompleted($activity)){
                //if(!isset($activity->actionable['data'][0])){
                $found  = false;
                foreach ($activity->actionable['data'] as $datum){
                    if($datum->client_id == $client->id){
                        $found = true;
                        break;
                    }
                }

                if(!$found){
                    $activities_auto_completed[] = $activity->id;
                    $actionable_text_data = $activity->actionable_type.'Data';
                    $actionable_data = new $actionable_text_data;
                    $actionable_data->client_id = $client->id;
                    $actionable_data->user_id = auth()->id();
                    $actionable_data->duration = 120;
                    switch($activity->getTypeName()){
                        case 'text':
                            $actionable_data->data = null;
                            $actionable_data->actionable_text_id = $activity->actionable_id;
                            $actionable_data->save();
                            break;
                        case 'textarea':
                            $actionable_data->data = null;
                            $actionable_data->actionable_textarea_id = $activity->actionable_id;
                            $actionable_data->save();
                            break;
                        case 'template_email':
                            $actionable_data->template_id = 1;
                            $actionable_data->email = null;
                            $actionable_data->actionable_template_email_id = $activity->actionable_id;
                            $actionable_data->save();
                            break;
                        case 'document_email':
                            //return 'document_email';
                            break;
                        case 'document':
                            $actionable_data->actionable_document_id = $activity->actionable_id;
                            $actionable_data->document_id = 1;
                            $actionable_data->save();
                            break;
                        case 'dropdown':
                            $item = ActionableDropdownItem::where('actionable_dropdown_id', $activity->actionable_id)->take(1)->first();

                            $actionable_data->actionable_dropdown_id = $activity->actionable_id;
                            $actionable_data->actionable_dropdown_item_id = 0;
                            $actionable_data->save();
                            break;
                        case 'date':
                            $actionable_data->data = null;
                            $actionable_data->actionable_date_id = $activity->actionable_id;
                            $actionable_data->save();
                            break;
                        case 'boolean':
                            $actionable_data->data = null;
                            $actionable_data->actionable_boolean_id = $activity->actionable_id;
                            $actionable_data->save();
                            break;
                        case 'notification':
                            $notification = new Notification;
                            $notification->name = $client->company . ' has been updated: ' . $activity->name;
                            $notification->link = route('clients.progress', $client);
                            $notification->save();

                            Mail::to(auth()->user()->email)->send(new NotificationMail($notification->name, $notification->link));

                            $actionable_data->actionable_notification_id = $activity->actionable_id;
                            $actionable_data->notification_id = $notification->id;
                            $actionable_data->save();
                            break;
                        case 'multiple_attachment':
                            $actionable_data->template_id = 1;
                            $actionable_data->email = null;
                            $actionable_data->actionable_ma_id = $activity->actionable_id;
                            $actionable_data->save();
                            break;
                        default:
                            //return 'error';
                            break;
                    }
                }

            }
        }

        $client_process_progress = ClientProcess::where('client_id',$client->id)->where('process_id',$process->id)->first();

        //Move process step to the next step if all activities completed
        $max_step = Step::orderBy('order','desc')->where('process_id', $client->process_id)->first();

        $n_step = Step::select('id')->orderBy('order','asc')->where('process_id', $client->process_id)->where('order','>',$step->order)->whereNull('deleted_at')->first();

        if($client->isStepActivitiesCompleted($step) && $step->order < $max_step->order){
            $client = Client::find($client->id);
            //$client->step_id = $client->step_id + 1;
            $client->step_id = $n_step->id;
            $client->save();

            $cpp = ClientProcess::find($client_process_progress->id);
            $cpp->step_id = $n_step->id;;
            $cpp->save();
        }

        if(!$n_step) {
            if ($client->isStepActivitiesCompleted($step)) {
                $client = Client::find($client->id);
                //$client->step_id = $client->step_id + 1;
                $client->process_id = $process->id;
                $client->step_id = $max_step->id;
                $client->save();

                $cpp = ClientProcess::find($client_process_progress->id);
                $cpp->step_id = $max_step->id;
                $cpp->save();
            }
        }

        return response()->json(['success' => 'Template sent successfully.', 'activities_auto_completed' => $activities_auto_completed]);
    }

    public function forms(Request $request,Client $client)
    {
        if((strpos($request->headers->get('referer'),'reports') !== false) || (strpos($request->headers->get('referer'),'custom_report') !== false)) {
            $request->session()->put('path_route',$request->headers->get('referer'));
            $path = '1';
            $path_route = $request->session()->get('path_route');
        } else {
            $request->session()->forget('path_route');
            $path = '0';
            $path_route = '';
        }

        $step = Step::withTrashed()->find($client->step_id);
        $process_progress = $client->getProcessStepProgress($step);

        $client_progress = $client->process->getStageHex(0);

        if($client->step_id == $step->id)
            $client_progress = $client->process->getStageHex(1);

        if($client->step_id > $step->id)
            $client_progress = $client->process->getStageHex(2);

        $steps = Step::where('process_id', $client->process_id)->orderBy('order')->get();

        $step_data = [];
        foreach ($steps as $a_step):
            $progress_color = $client->process->getStageHex(0);

            if($client->step_id == $a_step->id)
                $progress_color = $client->process->getStageHex(1);

            if($client->step_id > $a_step->id)
                $progress_color = $client->process->getStageHex(2);


            $tmp_step = [
                'id' => $a_step->id,
                'name' => $a_step->name,
                'process_id' => $a_step->process_id,
                'progress_color' => $progress_color
            ];

            array_push($step_data, $tmp_step);
        endforeach;

        $clientforms = ClientForm::orderBy('id','desc')->get();
        $clientforms2 = ClientForm::orderBy('id','desc')->where('client_id',$client->id)->get();

        $clientcrfforms2 = ClientForm::where('name','CRF Form')->where('client_id',$client->id)->get();
        $clientcrfforms = ClientCRFForm::where('client_id',$client->id)->first();

        $r = $client->load('forms.user',"crfforms");
        //dd($clientforms2);
        return view('clients.forms.index')->with([
            'client' => $client->load('forms.user',"crfforms"),
            'process_progress' => $process_progress,
            'view_process_dropdown' => $client->clientProcessIfActivitiesExist(),
            'steps' => $step_data,
            'forms' => $clientforms,
            'crfform' => $clientcrfforms,
            'crf' => count(collect($clientcrfforms)->toArray()),
            'crf2' => count(collect($clientcrfforms2)->toArray()),
            'list' => $clientforms2,
            'path' => $path,
            'path_route' => $path_route
        ]);
    }

    public function uploadforms(Client $client){
        $step = Step::withTrashed()->find($client->step_id);
        $process_progress = $client->getProcessStepProgress($step);

        $client_progress = $client->process->getStageHex(0);

        if($client->step_id == $step->id)
            $client_progress = $client->process->getStageHex(1);

        if($client->step_id > $step->id)
            $client_progress = $client->process->getStageHex(2);

        $steps = Step::where('process_id', $client->process_id)->get();

        $step_data = [];
        foreach ($steps as $a_step):
            $progress_color = $client->process->getStageHex(0);

            if($client->step_id == $a_step->id)
                $progress_color = $client->process->getStageHex(1);

            if($client->step_id > $a_step->id)
                $progress_color = $client->process->getStageHex(2);


            $tmp_step = [
                'id' => $a_step->id,
                'name' => $a_step->name,
                'process_id' => $a_step->process_id,
                'progress_color' => $progress_color
            ];

            array_push($step_data, $tmp_step);
        endforeach;

        $clientforms = ClientForm::orderBy('id','desc')->get();
        $clientcrfforms2 = ClientForm::where('name','CRF Form')->where('client_id',$client->id)->get();
        $clientcrfforms = ClientCRFForm::where('client_id',$client->id)->first();



        return view('clients.forms.upload')->with([
            'client' => $client->load('forms.user'),
            'process_progress' => $process_progress,
            'steps' => $step_data,
            'forms' => $clientforms,
            'crfform' => $clientcrfforms,
            'crf' => count(collect($clientcrfforms)->toArray()),
            'crf2' => count(collect($clientcrfforms2)->toArray())
        ]);
    }

    public function storeuploadforms(StoreClientFormRequest $request,$clientid){

        if ($request->hasFile('file')) {
            $request->file('file')->store('crf');
        }

        $document = new ClientForm();
        if($request->input('form_type') == "CRF Form"){
            $document->name = "CRF Form";
            $document->form_type = "CRF Form";
        } else {
            $document->name = $request->input('name');
            $document->form_type = "Other";
        }
        $document->file = $request->file('file')->hashName();
        $document->user_id = auth()->id();
        $document->client_id = $clientid;

        $document->save();

        return redirect(route('clients.forms', $clientid))->with('flash_success', 'Form uploaded successfully');
    }

    public function edituploadforms(Client $client,$formid){
        $step = Step::withTrashed()->find($client->step_id);
        $process_progress = $client->getProcessStepProgress($step);

        $client_progress = $client->process->getStageHex(0);

        if($client->step_id == $step->id)
            $client_progress = $client->process->getStageHex(1);

        if($client->step_id > $step->id)
            $client_progress = $client->process->getStageHex(2);

        $steps = Step::where('process_id', $client->process_id)->get();

        $step_data = [];
        foreach ($steps as $a_step):
            $progress_color = $client->process->getStageHex(0);

            if($client->step_id == $a_step->id)
                $progress_color = $client->process->getStageHex(1);

            if($client->step_id > $a_step->id)
                $progress_color = $client->process->getStageHex(2);


            $tmp_step = [
                'id' => $a_step->id,
                'name' => $a_step->name,
                'process_id' => $a_step->process_id,
                'progress_color' => $progress_color
            ];

            array_push($step_data, $tmp_step);
        endforeach;

        $clientforms = ClientForm::where('id',$formid)->get();
        $clientcrfforms2 = ClientForm::where('name','CRF Form')->where('client_id',$client->id)->get();
        $clientcrfforms = ClientCRFForm::where('client_id',$client->id)->first();



        return view('clients.forms.editupload')->with([
            'client' => $client->load('forms.user'),
            'process_progress' => $process_progress,
            'steps' => $step_data,
            'forms' => $clientforms,
            'crfform' => $clientcrfforms,
            'crf' => count(collect($clientcrfforms)->toArray()),
            'crf2' => count(collect($clientcrfforms2)->toArray())
        ]);
    }

    public function updateuploadforms(Request $request,$client, $form){

        $document = ClientForm::find($form);

        if ($request->hasFile('file')) {
            $request->file('file')->store('crf');
            $document->file = $request->file('file')->hashName();
        }

        if($request->input('form_type') == "CRF Form"){
            $document->name = "CRF Form";
            $document->form_type = "CRF Form";
        } else {
            $document->name = $request->input('name');
            $document->form_type = "Other";
        }
        $document->user_id = auth()->id();

        $document->save();

        return redirect(route('clients.forms', $client))->with('flash_success', 'Form updated successfully');

    }

    public function searchClients($search){


        $results = collect();

        $clients = new Client();
        $clients->unHide();

        $results = $results->merge(
            $clients->select('id',DB::raw("IF(`hash_id_number` is null,'',CAST(AES_DECRYPT(`hash_id_number`, 'Qwfe345dgfdg') AS CHAR(50))) as `id_number`"),DB::raw('CAST(AES_DECRYPT(`hash_company`, "Qwfe345dgfdg") AS CHAR(50)) `name`'),DB::raw('CAST(AES_DECRYPT(`hash_company`, "Qwfe345dgfdg") AS CHAR(50)) human_company'))
                ->having(DB::raw('human_company'),'like', '%'.$search.'%')
                ->get()
                ->map(function ($item) {
                    return $item;
                })

        );

        $results = $results->merge(
            $clients->select('id',DB::raw("IF(`hash_id_number` is null,'',CAST(AES_DECRYPT(`hash_id_number`, 'Qwfe345dgfdg') AS CHAR(50))) as `id_number`"),DB::raw("CONCAT(CAST(AES_DECRYPT(`hash_first_name`, 'Qwfe345dgfdg') AS CHAR(50)),' ',CAST(AES_DECRYPT(`hash_last_name`, 'Qwfe345dgfdg') AS CHAR(50))) as `name`"),DB::raw('CAST(AES_DECRYPT(`hash_first_name`, "Qwfe345dgfdg") AS CHAR(50)) human_first_name'),DB::raw('CAST(AES_DECRYPT(`hash_last_name`, "Qwfe345dgfdg") AS CHAR(50)) human_last_name'))
                ->having(DB::raw('human_first_name'),'like', '%'.$search.'%')
                ->orHaving(DB::raw('human_last_name'),'like', '%'.$search.'%')
                ->get()
                ->map(function ($item) {
                    return $item;
                })

        );

        $results = $results->merge(
            $clients->select('id',DB::raw("IF(`hash_id_number` is null,'',CAST(AES_DECRYPT(`hash_id_number`, 'Qwfe345dgfdg') AS CHAR(50))) as `id_number`"),DB::raw("CONCAT(CAST(AES_DECRYPT(`hash_first_name`, 'Qwfe345dgfdg') AS CHAR(50)),' ',CAST(AES_DECRYPT(`hash_last_name`, 'Qwfe345dgfdg') AS CHAR(50))) as `name`"),DB::raw('CAST(AES_DECRYPT(`hash_first_name`, "Qwfe345dgfdg") AS CHAR(50)) human_first_name'),DB::raw('CAST(AES_DECRYPT(`hash_last_name`, "Qwfe345dgfdg") AS CHAR(50)) human_last_name'),DB::raw('CAST(AES_DECRYPT(`hash_cif_code`, "Qwfe345dgfdg") AS CHAR(50)) human_cif_code'))
                ->having(DB::raw('human_cif_code'),'like', '%'.$search.'%')
                ->get()
                ->map(function ($item) {
                    return $item;
                })

        );

        $data = array();

        foreach ($results as $client){
            array_push($data,[
                'id' => $client->id,
                'name' => $client->name,
                'id_number' => ''
            ]);
        }

        return $results;
    }

    public function getClients(){
        $clients = Client::where('is_progressing',1)->where('is_qa','0')->orderBy('first_name')->get();

        $data = array();

        foreach ($clients as $client){
            array_push($data,[
                'id' => $client->id,
                'name' => ($client->company != null && $client->company != 'N/A' && $client->company != 'n/a' ? $client->company : $client->first_name.' '.$client->last_name),
                'id_number' => ($client->id_number != null ? $client->id_number : '&nbsp;')
            ]);
        }

        return response()->json($data);
    }

    public function getClientDetail($clientid){
        $clients = Client::where('id',$clientid)->get();



        $users = User::select('id',DB::raw("CONCAT(CAST(AES_DECRYPT(`hash_first_name`, 'Qwfe345dgfdg') AS CHAR(50)),' ',CAST(AES_DECRYPT(`hash_last_name`, 'Qwfe345dgfdg') AS CHAR(50))) as full_name"))->groupBy('id')->groupBy('hash_first_name')->groupBy('hash_last_name')->get();

        foreach ($users as $user){
            $cnt = Client::select(DB::raw("count(id) as cnt"))->whereNull('qa_end_date')->where('is_qa',0)->where('consultant_id',$user->id)->first();
            $users_array[$user->id] = $user->full_name.' ( Open cases: '.$cnt->cnt.' )';
        }

        foreach ($clients as $client){
            $data = [
                'id' => $client->id,
                'qa' => $client->is_qa,
                'contact' => (substr($client->contact,0,1) == '0' ? '+27'.substr($client->contact,1) : $client->contact ),
                'clname' => ($client->company != null && $client->company != 'N/A' && $client->company != 'n/a' ? $client->company : $client->first_name.' '.$client->last_name),
                'id_number' => ($client->id_number != null ? $client->id_number : '&nbsp;'),
                'consultant' => ($client->consultant_id != null ? User::select(DB::raw("CONCAT(CAST(AES_DECRYPT(`hash_first_name`, 'Qwfe345dgfdg') AS CHAR(50)),' ',CAST(AES_DECRYPT(`hash_last_name`, 'Qwfe345dgfdg') AS CHAR(50))) as full_name"))->where('id',$client->consultant_id)->first()->full_name : 0),
                'users' => $users_array
            ];
        }

        return response()->json($data);
    }

    public function storeConsultant(Request $request,$clientid){
        $client = Client::find($clientid);
        $client->consultant_id = $request->input('userid');
        $client->assigned_date = now();
        $client->save();

        if($client->id) {
            $log = new Log;
            $log->client_id = $client->id;
            $log->user_id = auth()->id();
            $log->save();

            $notification = new Notification;
            $notification->name = ($client->company != null ? $client->company : $client->first_name . ' ' . $client->last_name) .' has been assigned to you.';
            $notification->link = route('clients.show', $client->id);
            $notification->type = '3';
            $notification->save();

            Mail::to(auth()->user()->email)->send(new NotificationMail($notification->name, $notification->link));

            $user_notifications = [];

            $qauser = User::where('id',$request->input('userid'))->get();
            foreach ($qauser as $user) {
                array_push($user_notifications, [
                    'user_id' => $user->id,
                    'notification_id' => $notification->id
                ]);

                NotificationEvent::dispatch($user->id, $notification);

                Mail::to(trim($user->email))->send(new AssignedConsultantNotify($client));
            }

            UserNotification::insert($user_notifications);

            return response()->json(['message' => 'Success', 'consultant' => ($client->consultant_id != null ? User::select(DB::raw("CONCAT(CAST(AES_DECRYPT(`hash_first_name`, 'Qwfe345dgfdg') AS CHAR(50)),' ',CAST(AES_DECRYPT(`hash_last_name`, 'Qwfe345dgfdg') AS CHAR(50))) as full_name"))->where('id', $client->consultant_id)->first()->full_name : 0), 'clname' => ($client->company != null && $client->company != 'N/A' && $client->company != 'n/a' ? $client->company : $client->first_name . ' ' . $client->last_name)]);
        } else {
            return response()->json(['message'=>'Error','consultant' => ($client->consultant_id != null ? User::select(DB::raw("CONCAT(CAST(AES_DECRYPT(`hash_first_name`, 'Qwfe345dgfdg') AS CHAR(50)),' ',CAST(AES_DECRYPT(`hash_last_name`, 'Qwfe345dgfdg') AS CHAR(50))) as full_name"))->where('id',$client->consultant_id)->first()->full_name : 0),'clname' => ($client->company != null && $client->company != 'N/A' && $client->company != 'n/a' ? $client->company : $client->first_name.' '.$client->last_name)]);
        }
    }

    public function checkClientActivities($client){

        $completed = false;

        $clientobj = Client::where('id',$client)->first();

        $steps = Step::where('process_id',$clientobj->process_id)->get();

        $i = 0;
        foreach ($steps as $step){
            $stepd = Step::where('id',$step->id)->first();
            if($clientobj->isStepActivitiesCompleted($stepd)){
                $i++;
            }
        }
//return $i;
        if(count($steps) == $i) {
            return response()->json(['message' => 'Success']);
        } else {
            return response()->json(['message' => 'Error']);
        }
    }

    public function completeClient($client){

        $c = Client::find($client);
        $c->completed_date = now();
        $c->completed_by = Auth::id();
        $c->completed = 1;
        $c->save();

        return response()->json(['message' => 'Success']);

    }

    public function WorkItemQA($id)
    {

        $client = Client::find($id);
        $client->work_item_qa = 1;
        $client->work_item_qa_date = now();
        $client->qa_consultant = auth()->id();
        $client->save();

        return response()->json(['data' => 'success']);
    }

    public function clientBucketActivityIds($steps, $client){
        $tmp_act = $steps->map(function ($step){
            return $step->activities->map(function ($activ){
                if($activ->client_bucket){
                    return  $activ->id;
                }
            });
        })->flatten()->toArray();

        $tmp_act = array_values(array_filter($tmp_act));

        $parent_activities_in_client_basket = ActivityInClientBasket::where('client_id', $client->id)->select('activity_id', 'in_client_basket')
            ->get();

        $flag = $parent_activities_in_client_basket->map(function ($activity_id){
            return $activity_id->activity_id;
        })->toArray();

        foreach ($tmp_act as $key => $item){
            if (in_array($item, $flag)) {
                unset($tmp_act[$key]);
            }
        }

        $active_activities = $parent_activities_in_client_basket->where('in_client_basket', 1)->map(function ($activity){
            return $activity->activity_id;
        })->values()->toArray();

        return array_merge($tmp_act, $active_activities);
    }

    public function getClientCurrentProcesses($client_id){
        $data = array();

        $client_processes = ClientProcess::where('client_id',$client_id)->whereHas('process')->whereNull('completed_at')->get();

        foreach ($client_processes as $client_process){
            array_push($data,['name'=>$client_process->process->name,'process_id'=>$client_process->process_id,'step_id'=>$client_process->step_id]);
        }

        return response()->json($data);
    }

    public function getClientClosedProcesses($client_id){
        $data = array();

        $client_processes = ClientProcess::where('client_id',$client_id)->whereHas('process')->whereNotNull('completed_at')->get();

        foreach ($client_processes as $client_process){
            array_push($data,['name'=>$client_process->process->name,'process_id'=>$client_process->process_id,'step_id'=>$client_process->step_id]);
        }

        return response()->json($data);
    }

    public function getClientProcesses($client_id){
        $data = array();

        $client_processes = ClientProcess::where('client_id',$client_id)->whereHas('process')->get();

        foreach ($client_processes as $client_process){
            array_push($data,['name'=>$client_process->process->name,'process_id'=>$client_process->process_id,'step_id'=>$client_process->step_id]);
        }

        return response()->json($data);
    }

    public function getActivityMirrorValues($client_id,$activity_id){
        $activity = new Activity();
        $activity_values = $activity->getActivityMirrorValue($activity_id,$client_id);
        //dd($activity_values);

        return response()->json($activity_values);
    }

    public function getClientSigniFlowDocuments(Request $request){
        $client_id = $request->client_id;
        $process_ids = $request->process_ids;

        foreach ($process_ids as $process_id){
            // Call the api
        }

        // Return json result
    }

    public function generateAvatar($client_id){
        $client = Client::where('id',$client_id)->first();

        $avatar = new InitialAvatar();

        return $avatar->name(($client->first_name != null || $client->last_name != null ? $client->first_name.' '.$client->last_name : $client->company))
            ->length(2)
            ->fontSize(0.5)
            ->size(320) // 48 * 2
            ->background('#4680a6')
            ->color('#fff')
            ->generate()
            ->stream('png', 100);
    }

    public function clientProcessProgress(Client $client,Process $process, Step $step, Request $request, Step $activity_step, $action_id)
    {

        $offices = OfficeUser::select('office_id')->where('user_id',Auth::id())->get();
        $user_offices = [];

        foreach($offices as $office){
            array_push($user_offices,$office->office_id);
        }

        $client->withTrashed();

        if ($client->viewed == 0 && $client->consultant_id == Auth::id()) {
            $client->viewed = 1;
            $client->save();
        }

        $step->withTrashed();

        $process_progress = $client->getProcessStepProgress($step);

        $completed = "";
        $not_completed = "";

        $activity_progress_name = $activity_step->name;
        $activity_progress = $client->getProcessStepProgress($activity_step);

        $stepw = $activity_progress[0];

        foreach ($stepw['activities'] as $activity):
            if (isset($activity['value'])) {
                $completed .= '<li>' . $activity['name'] . '</li>';
            } else {
                $not_completed .= '<li>' . $activity['name'] . '</li>';
            }
        endforeach;

        $client->load('referrer', 'office.area.region.division', 'users', 'comments.user', 'consultant');

        //get step times for graph
        $client_actual_times = [];
        foreach ($process->steps as $actual_time_step) {
            $client_actual_times[$actual_time_step->name] = 0;

            foreach ($actual_time_step->activities as $activity) {
                if (isset($activity->actionable->data[0])) {
                    $client_actual_times[$actual_time_step->name] += 1;
                }
            }
        }

        //Not used
        /*if ($client->step_id == $step->id)
            $client_progress = $process->getStageHex(1);

        if ($client->step_id > $step->id)
            $client_progress = $process->getStageHex(2);*/

        $client_process = ClientProcess::where('client_id', $client->id)->where('process_id', $process->id)->first();

        $steps = Step::where('process_id', $process->id)->orderBy('order', 'asc')->get();

        //Not used
        /*$c_step_order = Step::where('id', $client_process->step_id)->withTrashed()->first();*/


        $max_step = Step::orderBy('order', 'desc')->where('process_id', $process->id)->first();

        //get next step id where order == current order+1
        $n_step = Step::select('id')->orderBy('order', 'asc')->where('process_id', $process->id)->where('order', '>', $step->order)->whereNull('deleted_at')->first();

        $next_step = $step->id;

        if ($next_step == $max_step->id){
            $next_step = $max_step->id;
    } else {
            $next_step = (isset($n_step->id) ? $n_step->id : $step->id);
    }
        $configs = Config::first();

        $actions = ActionsAssigned::with('client')->whereHas('activity', function($q){
            $q->where('status',0);
        })->where('completed',0)->where('clients',$client->id)->orderBy('id','desc')->get();

        $activities = [];
        $auser_array = [];
        $aduedate_array = array();


        //get assigned activities
        foreach ($actions as $activity) {
            $split_users = explode(',', $activity->users);

            if ($activity->client) {

                foreach ($activity->activity as $activity_id) {
                    //$auser_array = array();
                    foreach ($split_users as $user_id) {

                        // User Name
                        $user = User::where('id', trim($user_id))->first();

                        $user_name = (isset($user["first_name"]) ? $user["first_name"] . ' ' : '') . (isset($user["last_name"]) ?  $user["last_name"] : '');

                        if(!in_array($user_name,$auser_array)) {
                            array_push($auser_array, $user_name);
                        }
                    }

                    if ($activity_id != null && $activity_id->status != 1) {

                        //Get the current timestamp.
                        $now = strtotime(now());

                        //Calculate the difference.
                        $difference = $now - strtotime($activity->due_date);

                        //Convert seconds into days.
                        $days = floor($difference / (60 * 60 * 24));

                        if ($days < -$configs->action_threshold) {
                            $class = $activity->client->process->getStageHex(2);
                        } elseif ($days <= $configs->action_threshold) {
                            if (Carbon::parse(now()) >= Carbon::parse($activity->due_date)->subDay($configs->action_threshold)) {
                                $class = $activity->client->process->getStageHex(2);
                            } else {
                                $class = $activity->client->process->getStageHex(1);
                            }
                        } elseif ($days > $configs->action_threshold) {
                            $class = $activity->client->process->getStageHex(0);
                        } else {
                            $class = $activity->client->process->getStageHex(0);
                        }

                        if (Auth::check() && Auth::user()->isNot("manager") && Auth::id() == $user_id) {
                            $activities[$activity_id->activity_id] = [
                                'step_id' => $activity->step_id,
                                'action_id' => $activity->id,
                                'user' => (isset($activities[$activity_id->activity_id]["user"]) ? array_unique(array_merge($activities[$activity_id->activity_id]["user"],$auser_array)) : $auser_array),
                                'activity_id' => trim($activity_id->activity_id),
                                'due_date' => $activity->due_date,
                                'class' => $class];
                        } elseif (Auth::check() && Auth::user()->is("manager")) {
                            $activities[$activity_id->activity_id] = [
                                'step_id' => $activity->step_id,
                                'action_id' => $activity->id,
                                'user' => (isset($activities[$activity_id->activity_id]["user"]) ? array_unique(array_merge($activities[$activity_id->activity_id]["user"],$auser_array)) : $auser_array),
                                'activity_id' => trim($activity_id->activity_id),
                                'due_date' => $activity->due_date,
                                'class' => $class];
                        }
                    }
                }
            }
        }


        $has_permission = true;

        if($client->introducer_id == Auth::id() || $client->consultant_id == Auth::id()){
            $has_permission = true;
        }

        if(Auth::check() && (Auth::user()->is('manager') || Auth::user()->is('admin'))){
            $has_permission = true;
        }

        /*if(Auth::check() && Auth::user()->is('qa') && $client->is_qa == 1 && $client->consultant_id == Auth::id()){
            $has_permission = true;
        }*/

        //get client basket
        $activities_in_client_basket = $this->helper->clientBucketActivityIds($steps, $client, $process->id);

        $client_basket_activities = $steps->keyBy('name')->map(function ($step) use ($activities_in_client_basket){
            return $step->activities->filter(function ($activ) use ($activities_in_client_basket){
                return in_array($activ->id, array_unique($activities_in_client_basket));
            })->values();
        });

        /*$cb = $this->helper->clientBucketActivityIds($steps, $client, $client->process_id);*/
        $cb = $activities_in_client_basket;

        $grids = Activity::select('position')->whereNotNull('position')->where('step_id',$step->id)->groupBy('position')->orderBy('position','desc')->get();
        $grid_array = array();
        foreach($grids as $grid){
            array_push($grid_array,$grid['position']);
        }

        $global_helper = new HelperFunction();

        $activity_rule = ActivityVisibilityRule::select('*')->get();

        $act_vis = [];
        $act_invis = [];
        if($activity_rule) {

            $aarray = array();

            $activity_visibility = ClientVisibleActivity::select('activity_id')->where('client_id',$client->id)->get();

            foreach ($activity_visibility as $act){
                array_push($aarray,$act->activity_id);
            }

            foreach ($activity_rule as $av) {
                if (in_array($av->activity_id,$aarray,true)) {
                    array_push($act_vis, $av->activity_id);
                } else {
                    array_push($act_invis, $av->activity_id);
                }
            }
        }

        $step_rule = ActivityStepVisibilityRule::select('*')->get();

        $step_vis = [];
        $step_invis = [];
        if($step_rule) {

            $sarray = array();

            $step_visibility = ClientVisibleStep::select('step_id')->where('client_id',$client->id)->get();

            foreach ($step_visibility as $ste){
                array_push($sarray,$ste->step_id);
            }

            foreach ($step_rule as $sv) {
                if (in_array($sv->activity_step,$sarray,true)) {
                    array_push($step_vis, $sv->activity_step);
                } else {
                    if(!in_array($sv->activity_step,$step_invis,true)) {
                        array_push($step_invis, $sv->activity_step);
                    }
                }
            }
        };


        $data = [
            'config'=>Config::first(),
            'activities' => $activities,
            'step_dropdown' => Step::where('process_id',$client->process_id)->pluck('name','id'),
            'view_process_dropdown' => $client->clientProcessIfActivitiesExist(),
            'process' => $client->startNewProcessDropdown(),
            'users' => User::select('id',DB::raw("CONCAT(CAST(AES_DECRYPT(`hash_first_name`, 'Qwfe345dgfdg') AS CHAR(50)),' ',CAST(AES_DECRYPT(`hash_last_name`, 'Qwfe345dgfdg') AS CHAR(50))) as full_name"))->orderBy('first_name')->orderBy('last_name')->pluck('full_name', 'id'),
            /*'activity_comment' => $activity_comment,*/
            'completed' => $completed,
            'not_completed' => $not_completed,
            'client' => $client,
            'process_id'=>$process->id,
            'process_progress' => $process_progress,
            'process_progress2' => $activity_progress,
            'steps' => $this->helper->steps_data($client, $process),
            'step' => $step,
            'active' => $step,
            'activity_progress_name'=> $activity_progress_name,
            'max_step' => $max_step->id,
            'next_step' => $next_step,
            'client_actual_times' => $client_actual_times,
            'documents' => Document::orderBy('name')->where('client_id', $client->id)->orWhere('client_id', null)->pluck('name', 'id'),
            'document_options' => Document::orderBy('name')->where('client_id', $client->id)->orWhere('client_id', null)->pluck('name', 'id'),
            'templates' => Template::where('template_type_id','2')->where('process_id',$client->process_id)->orderBy('name')->pluck('name', 'id'),
            'template_email_options' => EmailTemplate::orderBy('name')->pluck('name', 'id'),
            'path' => $global_helper->getPath($request)['path'],
            'path_route' => $global_helper->getPath($request)['path_route'],
            /*'status' =>$status,*/
            'max_group' => ($client->groupCompletedActivities($step,$client->id) > 0 ? $client->groupCompletedActivities($step,$client->id) :1),
            /*'qa_complete' => $qa_complete,*/
            'user_has_permission' => $has_permission,
            'client_basket_activities' => $client_basket_activities,
            //'client_basket_details' => $client_basket_details,
            'grid_count' => count(Activity::select('position')->where('position','!=',0)->where('position','!=',5)->where('step_id',$step->id)->groupBy('position')->orderBy('position','desc')->get()),
            'grid_array' => $grid_array,
            'forms' => $this->helper->detailedClientBasket($client, 1)['forms'],
            'client_details' => $this->helper->clientDetails($client, 1)['forms'],
            'in_basket' => $cb,
            'in_details_basket' => $this->helper->detailedClientBasket($client, 1)['cd'],
            'message_users' => User::where('id', '!=', Auth::id())->get(),
            'activity_invisibil' => $act_invis,
            'step_invisibil' => $step_invis,
            'client_processes' => ClientProcess::select('process_id','step_id')->where('client_id',$client->id)->whereHas('process')->distinct()->get(),
            'number_to_word' => ['1'=>'One','2'=>'Two','3'=>'three','4'=>'Four'],
            'whatsapp_templates' => WhatsappTemplate::pluck('name','id')->prepend('Select',''),
            'user_offices' => $user_offices,
            'client_list' => Client::select('id', DB::raw('CONCAT(first_name," ",COALESCE(`last_name`,"")) as full_name'))->whereIn('office_id', collect($offices)->toArray())->pluck('full_name','id')->prepend('Please select',''),
        ];

        return $data;
    }

    public function sendMail(Request $request, $client_id){

        $client = Client::where('id',$client_id)->first();

        $subject = $request->input('mail_subject');
        $message =  $request->input('mail_message');

        $data = [
            'subject' => $subject,
            'message' => $message
        ];

        Mail::to($client->email)->send(new ClientMail($data));

        $mail = new MailLog();
        $mail->date = now();
        $mail->from = config('mail.from.name').' <'.config('mail.from.address').'>';
        $mail->to = $client->email;
        $mail->subject = $subject;
        $mail->body = $message;
        $mail->user_id = Auth::id();
        $mail->office_id = Auth::user()->office()->id;
        $mail->save();

        return redirect()->back()->with(['flash_success' => 'Email successfully sent.']);
    }

    public function sendWhatsapp(Request $request, $client_id){

        $client = Client::where('id',$client_id)->first();

        $whatsapp_message = $request->input('whatsapp_message');

        $data = [
            'client' => $client,
            'whatsapp_message' => $whatsapp_message
        ];

        $send = new WhatsappController();
        $send->sendWhatsAppMessage($data);

        return response()->json();
    }

    public function toggleClientPortal($document_id)
    {
        $document = Document::find($document_id);
        if($document->display_in_client_portal == 1){
            $document->display_in_client_portal = 0;
        } else {
            $document->display_in_client_portal = 1;
        }

        $document->save();

        return redirect()->back()->with(['success', 'Success']);
    }

    function randomPassword($length,$count, $characters) {

// $length - the length of the generated password
// $count - number of passwords to be generated
// $characters - types of characters to be used in the password

// define variables used within the function
        $symbols = array();
        $passwords = array();
        $used_symbols = '';
        $pass = '';

// an array of different character types
        $symbols["lower_case"] = 'abcdefghijklmnopqrstuvwxyz';
        $symbols["upper_case"] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $symbols["numbers"] = '1234567890';
        $symbols["special_symbols"] = '!?~@#-_+<>[]{}';

        $characters = explode(",",$characters); // get characters types to be used for the passsword
        foreach ($characters as $key=>$value) {
            $used_symbols .= $symbols[$value]; // build a string with all characters
        }
        $symbols_length = strlen($used_symbols) - 1; //strlen starts from 0 so to get number of characters deduct 1

        for ($p = 0; $p < $count; $p++) {
            $pass = '';
            for ($i = 0; $i < $length; $i++) {
                $n = rand(0, $symbols_length); // get a random character from the string with all characters
                $pass .= $used_symbols[$n]; // add the character to the password string
            }
            $passwords[] = $pass;
        }

        return $passwords; // return the generated password
    }


    public function activateLoginForClient($client_id)
    {
        $client = Client::find($client_id);

            $password = $this->randomPassword(8,1,"lower_case,upper_case,numbers,special_symbols");;

        $hash_password = Hash::make($password[0]);

        $clientPortal = ClientPortal::where('email', $client->email)->where('id_number', $client->id_number)->first();

        $clientPortal = null;
        if(!isset($clientPortal->id)){
            $clientPortal = new ClientPortal();
            $clientPortal->email = $client->email;
            $clientPortal->id_number = $client->id_number;
            $clientPortal->client_id = $client->id;
        } else {
            $clientPortal = ClientPortal::find($clientPortal->id);
        }
        $clientPortal->password = $hash_password;
        $clientPortal->save();

        Mail::to($client->email)->send(new ActivateLoginForClient($password[0]));

        $mail = new MailLog();
        $mail->date = now();
        $mail->from = config('mail.from.name').' <'.config('mail.from.address').'>';
        $mail->to = $client->email;
        $mail->subject = 'Activate Login For Client';
        $mail->body = '';
        $mail->user_id = Auth::id();
        $mail->office_id = Auth::user()->office()->id;
        $mail->save();

        return redirect()->back()->with(['flash_success' => 'Client login details emailed to client.']);
    }

    public function sendLoginForClient($client_id)
    {
        $client = Client::find($client_id);

        Mail::to($client->email)->send(new SendLoginForClient());

        $mail = new MailLog();
        $mail->date = now();
        $mail->from = config('mail.from.name').' <'.config('mail.from.address').'>';
        $mail->to = $client->email;
        $mail->subject = 'Send Login For Client';
        $mail->body = '';
        $mail->user_id = Auth::id();
        $mail->office_id = Auth::user()->office()->id;
        $mail->save();

        return redirect()->back()->with(['flash_success' => 'Client login details emailed to client.']);
    }

    public function getClientEmail(Request $request){
        
        $client = Client::where('company',$request->input('client'))->orWhere(DB::raw('CONCAT(first_name," ", last_name)'),$request->input('client'))->first();
//dd($client);
        return response()->json([$client->email]);       

    }
}