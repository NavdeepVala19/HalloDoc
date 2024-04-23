<?php

namespace App\Http\Controllers;


use ZipArchive;
use App\Models\RequestTable;
use Illuminate\Http\Request;
use App\Models\RequestWiseFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Spatie\MediaLibrary\Support\MediaStream;

class PatientViewDocumentsController extends Controller
{
    public function patientViewDocument($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $documents = RequestWiseFile::select(
                'request.first_name',
                'request.confirmation_no',
                'request_wise_file.file_name',
                'request_wise_file.id',
                'request_wise_file.request_id',
                DB::raw('DATE(request_wise_file.created_at) as created_date'),
            )
                ->leftJoin('request', 'request.id', 'request_wise_file.request_id')
                ->where('request_id', $id)
                ->paginate(10);

            return view('patientSite/patientViewDocument', compact('documents'));
        } catch (\Throwable $th) {
            return view('errors.404');

        }
    }

    public function uploadDocs(Request $request)
    {
        $request->validate([
            'document' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc,docx|max:2048',
        ]);
  
        $userData = Auth::user();
        $email = $userData["email"];

        $requestWiseData = RequestWiseFile::where('request_id', $request->request_wise_file_id)->get();

        if (!empty($request->document)) {
            // store documents in request_wise_file table
            $request_file = new RequestWiseFile();
            $request_file->request_id = $requestWiseData->first()->request_id;
            $request_file->file_name = uniqid() . '_' .$request->file('document')->getClientOriginalName();
            $path = $request->file('document')->storeAs('public', $request_file->file_name);
            $request_file->save();
            return back();
        }
        else{
            $request->validate([
                'document' => 'required|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
            ]);
        }
    }


    public function downloadOne($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $file = RequestWiseFile::where('id', $id)->first();
            $path = (public_path() . '/storage/' . $file->file_name);
            return response()->download($path);
        } catch (\Throwable $th) {
            return view('errors.500');

        }
    }


    public function downloadSelectedFiles(Request $request)
    {
        try {
           
        if (empty($request->input('selected_files'))) {
            $data = RequestWiseFile::where('request_id', $request->requestId)->get();
            if ($data->isEmpty()) {
                return redirect()->back()->with('noRecordFound', 'There are no records to download!');
            }
            $ids = RequestWiseFile::where('request_id', $request->requestId)->get()->pluck('id')->toArray();

        } else {
            $ids = $request->input('selected_files');
        }

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
        } catch (\Throwable $th) {
            return view('errors.500');
        }

    }
}