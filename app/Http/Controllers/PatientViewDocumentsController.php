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

    public function patientViewDocument()
    {
        $documents = DB::table('request_wise_file')->paginate(10);
        return view('patientSite/patientViewDocument', compact('documents'));
    }

    public function uploadDocs(Request $request)
    {
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


    public function download($id = null)
    {

        $file = RequestWiseFile::where('id', $id)->first();
        $path = (public_path() . '/storage/' . $file->file_name);

        return response()->download($path);
    }



    public function downloadSelectedFiles(RequestWiseFile $request)
    {
        $selectedFiles = $request->input('selected_files', []);

        if (empty($selectedFiles)) {
            return redirect()->back()->with('error', 'No files selected for download.');
        }

        $files = [];
        foreach ($selectedFiles as $filename) {
            $filePath = public_path('files/' . $filename);

            if (file_exists($filePath)) {
                $files[] = $filePath;
            }
        }

        if (empty($files)) {
            return redirect()->back()->with('error', 'No valid files selected for download.');
        }

        $zipFile = public_path('download/selected_files.zip');

        $zip = new ZipArchive();
        $zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($files as $file) {
            $zip->addFile($file, basename($file));
        }

        $zip->close();

        return response()->download($zipFile)->deleteFileAfterSend(true);
    }


}

