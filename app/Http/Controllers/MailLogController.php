<?php

namespace App\Http\Controllers;

use App\MailAttachmentLog;
use App\MailLog;
use App\OfficeUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MailLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){

        $users = OfficeUser::select('user_id')->where('office_id',Auth::user()->office()->id)->get();
//dd($users);
        $office_users = [];

        foreach($users as $user){
            array_push($office_users,$user->user_id);
        }
        //dd(collect($users)->toArray());
    //dd($office_users);
        $emails = MailLog::where('office_id',Auth::user()->office()->id);

        if($request->has('q') && $request->input('q') != ''){
            $emails = $emails->where('to','like','%'.$request->input('q').'%');
        }

        $emails = $emails->orderBy('date','desc')->get();

        $parameters = [
            'emails' => $emails
        ];

        return view('emaillogs.index')->with($parameters);
    }

    public function show($mailid){
        $mail = MailLog::where('id',$mailid)->first();
        $attachments = MailAttachmentLog::where('mail_id',$mailid)->get();
//dd($mail);
        $parameters = [
            'mails' => $mail,
            'attachments' => $attachments
        ];

        return view('emaillogs.show')->with($parameters);
    }
}
