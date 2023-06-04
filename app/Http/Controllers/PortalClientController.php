<?php

namespace App\Http\Controllers;

use App\ClientPortal;
use Illuminate\Http\Request;
use App\Client;
use App\Process;
use App\ClientProcess;
use App\Step;


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
use App\ClientActivity;
use App\ClientComment;
use App\ClientCRFForm;
use App\ClientHelper;
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
use App\Notification;
use App\OfficeUser;
use App\ProcessArea;
use App\Project;
use App\Referrer;
use App\RelatedPartiesTree;
use App\RelatedParty;
use App\Template;
use App\TriggerType;
use App\UserNotification;
use App\WhatsappTemplate;
use Carbon\Carbon;
use Faker\Provider\DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\TemplateMail;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\User;
use App\ClientForm;
use App\BusinessUnits;
use App\Presentation;
use App\FormInputTextData;
use App\FormInputBooleanData;
use App\FormInputDateData;
use App\FormInputDropdownItem;
use App\FormInputTextareaData;
use App\FormSectionInputs;
use LasseRafn\InitialAvatarGenerator\InitialAvatar;
use App\Mail\ClientMail;


class PortalClientController extends Controller
{
    public function __construct()
    {
        // Client must login before they can acccess this Controller
        $this->middleware('auth:clients');
    }

    public function updatePassword(Request $request)
    {
        if($request->has('client')) {
            $customValidationMessages = [
                'chpwd.regex' => 'Password must contain the following: a number, a upper case character, a lower case character, and a special character',
                'chpwd.min' => 'Password must be at least 8 characters long',
                'confirmpwd.same' => 'Passwords do not match.'
            ];

            $validated = $request->validate([
                'chpwd' => [
                    'required',
                    'string',
                    'min:8',             // must be at least 10 characters in length
                    'regex:/[a-z]/',      // must contain at least one lowercase letter
                    'regex:/[A-Z]/',      // must contain at least one uppercase letter
                    'regex:/[0-9]/',      // must contain at least one digit
                    'regex:/[@$!%*#?&]/', // must contain a special character
                ],
                'confirmpwd' => [
                    'required',
                    'same:chpwd'
                ]
            ],$customValidationMessages);
        }

        $hash_password = Hash::make($request->input('chpwd'));

        $clientPortal = Auth::guard('clients')->user();

        $client = ClientPortal::find($clientPortal->id);
        $client->password = $hash_password;
        $client->password_changed = 1;
        $client->save();


        return redirect(route('portal.client'));
    }

    public function index(Request $request)
    {
        $clientPortal = Auth::guard('clients')->user();

        $client_id = $clientPortal->client_id;

        $client = Client::find($client_id);

        $children_and_dependencies = FormSectionInputs::orderBy('form_section_id')->where('form_section_id', 19)->get();
        $banking_details = FormSectionInputs::orderBy('form_section_id')->where('form_section_id', 4)->get();
        $principal_life_details = FormSectionInputs::orderBy('form_section_id')->where('form_section_id', 5)->get();
        // $principal_life_details = FormSectionInputs::orderBy('form_section_id')->where('form_section_id', 5)->get();
        $spouse_details = FormSectionInputs::orderBy('form_section_id')->where('form_section_id', 6)->get();

        // Get FormData for the variable passed by value
        $this->getFormData($children_and_dependencies, $client->id);
        $this->getFormData($banking_details, $client->id);
        $this->getFormData($principal_life_details, $client->id);
        $this->getFormData($spouse_details, $client->id);

        $parameters = [
            'clientPortal' => $clientPortal,
            'client' => $client,
            'bankingDetails' => $banking_details,
            'childrenAndDependencies' => $children_and_dependencies,
            'principalLifeDetails' => $principal_life_details,
            'spouseDetails' => $spouse_details,
        ];

        if($clientPortal->password_changed == '0'){
            return view('auth.portal.client.changepassword')->with($parameters);
        }

        return view('portal.client.index')->with($parameters);
    }

    public function documents(Request $request)
    {
        $clientPortal = Auth::guard('clients')->user();
        $client_id = $clientPortal->client_id;
        
        $client = Client::find($client_id);

        $parameters = [
            'client' => $client
        ];

        return view('portal.client.documents')->with($parameters);
    }

    public function createdocument()
    {
        $clientPortal = Auth::guard('clients')->user();
        $client_id = $clientPortal->client_id;

        $client = Client::find($client_id);

        $parameters = [
            'client' => $client
        ];

        return view('portal.client.createdocument')->with($parameters);
    }

    public function storedocument(Request $request){
        $clientPortal = Auth::guard('clients')->user();
        $client_id = $clientPortal->client_id;

        $client = Client::find($client_id);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $name = Carbon::now()->format('Y-m-d')."-".strtotime(Carbon::now()).".".$file->getClientOriginalExtension();
            $stored = $file->storeAs('documents', $name);
        }

        $document = new Document;
        $document->name = $request->input('name');
        $document->file = $name;
        $document->user_id = 0;


            $document->client_id = $client_id;
            $document->display_in_client_portal = 1;

        $document->save();

        $parameters = [
            'client' => $client
        ];

            return redirect(route('portal.client.documents'))->with($parameters);

    }

    // Pass value by reference so this function modifies the value
    public function getFormData(&$form_data, $client_id)
    {
        foreach ($form_data as $key => $form_data_value)
        {
            $form_data[$key]['value'] = '';

            switch ($form_data_value->input_type) {
                case 'App\FormInputText':
                    $form_data[$key]['value'] = $this->getFormInputText($form_data_value->input_id, $client_id);
                break;

                case 'App\FormInputDate':
                    $form_data[$key]['value'] = $this->getFormInputDate($form_data_value->input_id, $client_id);
                break;

                case 'App\FormInputDropdown':
                    $form_data[$key]['value'] = $this->getFormInputDropDown($form_data_value->input_id, $client_id);
                break;
            }
        }
    }

    // Move this to a helper file, so it can be used everywhere
    public function getFormInputText($input_id, $client_id)
    {
        $formData = FormInputTextData::select('id', 'data', 'form_input_text_id')->where('form_input_text_id', $input_id)->where('client_id', $client_id)->orderBy('id', 'desc')->first();
        $value = isset($formData->data) ? trim($formData->data) : '';

        return $value;
    }

    // Move this to a helper file, so it can be used everywhere
    public function getFormInputDropDown($input_id, $client_id)
    {
        $form_input_drop_down_items = FormInputDropdownItem::where('form_input_dropdown_id', $input_id)->get();
        $form_input_drop_down_data = FormInputDropdownData::select('id', 'form_input_dropdown_id', 'form_input_dropdown_item_id')->where('form_input_dropdown_id', $input_id)->where('client_id', $client_id)->orderBy('id', 'desc')->first();

        $value = '';
        if(isset($form_input_drop_down_data)) {
            foreach ($form_input_drop_down_items as $form_input_drop_down_item) {
                if ($form_input_drop_down_item->id == $form_input_drop_down_data->form_input_dropdown_item_id) {
                    $value = isset($form_input_drop_down_item->name) ? trim($form_input_drop_down_item->name) : '';
                }
            }
        }

        return $value;
    }

    // Move this to a helper file, so it can be used everywhere
    public function getFormInputDate($input_id, $client_id)
    {
        $formData = FormInputDateData::select('id', 'data', 'form_input_date_id')->where('form_input_date_id', $input_id)->where('client_id', $client_id)->orderBy('id', 'desc')->first();
        $value = isset($formData->data) ? trim($formData->data) : '';

        return $value;
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

    public function getDocument(Request $request)
    {

        if (file_exists(storage_path('app/documents/' . $request->input('q')))) {
            return response()->file(storage_path('app/documents/' . $request->input('q')));
        } else if(file_exists(public_path('storage/documents/processed_applications'.$request->input('q')))) {
            return response()->file(public_path('storage/documents/processed_applications'.$request->input('q')));
        } else if(file_exists(public_path('storage/pipeline/documents'.$request->input('q')))) {
            return response()->file(public_path('storage/pipeline/documents/'.$request->input('q')));
        }else{
            abort(404);
        }
    }

    public function getAvatar(Request $request)
    {
        if ($request->has('q') && file_exists(storage_path('app/avatars/' . $request->input('q')))) {
            return response()->file(storage_path('app/avatars/' . $request->input('q')));
        } else {
            return response()->file(storage_path('app/avatars/default.png'));
        }
    }
}
