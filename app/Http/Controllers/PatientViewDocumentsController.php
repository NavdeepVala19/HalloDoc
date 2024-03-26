<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\RequestTable;
use App\Models\RequestWiseFile;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\Support\MediaStream;
use ZipArchive;

class PatientViewDocumentsController extends Controller
{
    public function patientViewDocument($id)
    {
        $documents = RequestWiseFile::select(
            'request.first_name',
            'request.confirmation_no',
            'request_wise_file.file_name',
            'request_wise_file.created_at',
            'request_wise_file.id',
        )
        ->leftJoin('request','request.id','request_wise_file.request_id')
        ->where('request_id', $id)
        ->paginate(10);

        return view('patientSite/patientViewDocument', compact('documents'));
    }

    public function uploadDocs(Request $request)
    {
        // $documents = RequestWiseFile::where('request_id', $id)->get();

        $requestData = new RequestTable();
        $requestData->request_type_id = $request->request_type;
        $requestData->save();

        // store documents in request_wise_file table

        $request_file = new RequestWiseFile();
        $request_file->request_id = $requestData->id;
        $request_file->file_name = $request->file('docs')->getClientOriginalName();
        $path = $request->file('docs')->storeAs('public', $request->docs->getClientOriginalName());
        $request_file->save();

        return redirect('patientViewDocument');

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

