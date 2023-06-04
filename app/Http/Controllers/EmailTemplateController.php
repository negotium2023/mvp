<?php

namespace App\Http\Controllers;

use App\EmailTemplate;
use App\Http\Requests\StoreEmailTemplate;
use App\Http\Requests\UpdateEmailTemplate;
use App\MailLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\WorkflowEmail;

class EmailTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){

        $template = EmailTemplate::orderBy('id');

        if ($request->has('q')) {
            $template->where('name', 'LIKE', "%" . $request->input('q') . "%");
        }

        $parameters = [
            'template' => $template->get()
        ];


        return view('emailtemplates.index')->with($parameters);
    }

    public function create(){

        return view('emailtemplates.create');
    }

    public function store(StoreEmailTemplate $request){
        $template = new EmailTemplate();
        $template->name = $request->input('name');
        $template->email_subject = $request->input('subject');
        $template->email_content = $request->input('content');
        $template->user_id = auth()->id();
        $template->save();

        return redirect(route('emailtemplates.index'))->with('flash_success', 'Email Template captured successfully');
    }

    public function show($templateid){
        $template = EmailTemplate::where('id',$templateid)->get();

        $parameters = [
            'template' => $template
        ];

        return view('emailtemplates.show')->with($parameters);
    }

    public function edit($templateid){

        $template = EmailTemplate::where('id',$templateid)->get();

        $parameters = [
            'template' => $template
        ];

        return view('emailtemplates.edit')->with($parameters);
    }

    public function update(UpdateEmailTemplate $request,$templateid){
        $template = EmailTemplate::find($templateid);
        $template->name = $request->input('name');
        $template->email_subject = $request->input('subject');
        $template->email_content = $request->input('content');
        $template->user_id = auth()->id();
        $template->save();

        return redirect(route('emailtemplates.index'))->with('flash_success', 'Email Template saved successfully');
    }

    public function destroy($id)
    {
        EmailTemplate::destroy($id);
        return redirect()->route('emailtemplates.index')
            ->with('success','Email Templates deleted successfully');
    }

    public function ajaxedit($id)
    {
        $post = EmailTemplate::find($id)->toArray();
        return response()->json($post);
    }

    public function ajaxupdate(Request $request,$id)
    {
        $email = EmailTemplate::find($id);

        $request->session()->forget('email_template');
        $request->session()->put('email_template',$request->input('email_content'));

        //return response()->json($email);
    }

    public function getSubject(Request $request,$id){
        $post = EmailTemplate::find($id)->toArray(['email_subject']);
        return response()->json($post);
    }

    public function getTemplates(){
        $template = EmailTemplate::orderBy('name')->pluck('name', 'id')->prepend('Select Template','');

        return response()->json($template);
    }
    public function getTemplate(Request $request,$template_id){
        $template = EmailTemplate::find($template_id);

        $t =  [];
        $t['id'] = $template->id;
        $t['name'] = $template->name;
        $t['email_subject'] = $template->email_subject;
        $t['email_content'] = $template->email_content;

        return response()->json($t);
    }
    public function sendEmailTemplate(Request $request){

        if(!is_array($request->input("addresses"))){
            $addresses = explode(',',$request->input("addresses"));
        } else {
            $addresses = $request->input("addresses");
        }

        foreach ($addresses as $address){
            Mail::to($address)->send(new WorkflowEmail($request->input('subject'),$request->input('emailbody')));

            $mail = new MailLog();
            $mail->date = now();
            $mail->from = config('mail.from.name') . ' <' . config('mail.from.address') . '>';
            $mail->to = $address;
            $mail->subject = $request->input('subject');
            $mail->body = $request->input('emailbody');
            $mail->user_id = Auth::id();
            $mail->office_id = Auth::user()->office()->id;
            $mail->save();
        }

        return response()->json($addresses);
    }
}
