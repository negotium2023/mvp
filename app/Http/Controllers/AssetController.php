<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAvatar(Request $request)
    {
        if ($request->has('q') && file_exists(storage_path('app/avatars/' . $request->input('q')))) {
            return response()->file(storage_path('app/avatars/' . $request->input('q')));
        } else {
            return response()->file(storage_path('app/avatars/default.png'));
        }
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

    public function getCrf(Request $request)
    {
        if (file_exists(storage_path('app/crf/' . $request->input('q')))) {
            return response()->file(storage_path('app/crf/' . $request->input('q')));
        } else {
            abort(404);
        }
    }

    public function getTemplate(Request $request)
    {
        if (file_exists(storage_path('app/templates/' . $request->input('q')))) {
            return response()->file(storage_path('app/templates/' . $request->input('q')));
        } else {
            abort(404);
        }
    }
}
