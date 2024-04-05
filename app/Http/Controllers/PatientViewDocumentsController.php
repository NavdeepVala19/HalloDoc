<?php

namespace App\Http\Controllers;


use ZipArchive;
use App\Models\RequestTable;
use Illuminate\Http\Request;
use App\Models\RequestWiseFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\Support\MediaStream;

class PatientViewDocumentsController extends Controller
{
    public function patientViewDocument($id)
    {
        // dd($id);

        $documents = RequestWiseFile::select(
            'request.first_name',
            'request.confirmation_no',
            'request_wise_file.file_name',
            'request_wise_file.created_at',
            'request_wise_file.id',
            'request_wise_file.request_id',

        )
            ->leftJoin('request', 'request.id', 'request_wise_file.request_id')
            ->where('request_id', $id)
            ->paginate(10);

        // dd($documents);
        
        return view('patientSite/patientViewDocument', compact('documents'));
    }

    public function uploadDocs(Request $request)
    {
    
       
        $userData = Auth::user();
        $email = $userData["email"];

        $reqestWiseData = RequestWiseFile::where('request_id', $request->request_wise_file_id)->get();

        // store documents in request_wise_file table
        $request_file = new RequestWiseFile();
        $request_file->request_id = $reqestWiseData->first()->request_id;
        $request_file->file_name = $request->file('document')->getClientOriginalName();
        $path = $request->file('document')->storeAs('public', $request->document->getClientOriginalName());
        $request_file->save();

        return back();
    }


    public function downloadOne($id = null)
    {
        $file = RequestWiseFile::where('id', $id)->first();
        $path = (public_path() . '/storage/' . $file->file_name);

        return response()->download($path);
    }



    public function downloadSelectedFiles(Request $request)
    {
        $ids = $request->input('selected_files');

        $zip = new ZipArchive;
        $zipFile = 'documents.zip';

        if ($zip->open(public_path($zipFile), ZipArchive::CREATE) === TRUE) {
            foreach ($ids as $id) {
                $file = RequestWiseFile::where('id', $id)->first();
                $path = (public_path() . '/storage/' . $file->file_name);

                $zip->addFile($path, $file->file_name);
            }
            $zip->close();
        }
        return response()->download(public_path($zipFile))->deleteFileAfterSend(true);
    }
}
