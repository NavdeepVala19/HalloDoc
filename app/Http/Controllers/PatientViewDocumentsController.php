<?php

namespace App\Http\Controllers;

use App\Models\RequestWiseFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class PatientViewDocumentsController extends Controller
{

    /**
     * show user(patient) firstname , confirmation number, filename, created_at

     * @param mixed $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
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

    /**
     * upload document in request_wise_file at $request->request_wise_file_id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadDocs(Request $request)
    {
        $request->validate([
            'document' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc,docx|max:2048',
        ]);

        $requestWiseData = RequestWiseFile::where('request_id', $request->request_wise_file_id)->first();

        if ($request->document) {
            // store documents in request_wise_file table
            $requestFile = new RequestWiseFile();
            $requestFile->request_id = $requestWiseData->request_id;
            $requestFile->file_name = uniqid() . '_' . $request->file('document')->getClientOriginalName();
            $request->file('document')->storeAs('public', $requestFile->file_name);
            $requestFile->save();
            return back();
        } else {
            $request->validate([
                'document' => 'required|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
            ]);
        }
    }

    /**
     * download individual documents
     * @param mixed $id
     * @return mixed|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadOne($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $file = RequestWiseFile::where('id', $id)->first();
            $path = public_path() . '/storage/' . $file->file_name;
            return response()->download($path);
        } catch (\Throwable $th) {
            return view('errors.500');
        }
    }


    /**
     *  download multiple documents
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadSelectedFiles(Request $request)
    {
        try {
            if (!$request->input('selected_files')) {
                $data = RequestWiseFile::where('request_id', $request->requestId)->get();
                if ($data->isEmpty()) {
                    return redirect()->back()->with('noRecordFound', 'There are no records to download!');
                }
                $ids = RequestWiseFile::where('request_id', $request->requestId)->get()->pluck('id')->toArray();
            } else {
                $ids = $request->input('selected_files');
            }
            $zip = new ZipArchive();
            $zipFile = 'documents.zip';

            if ($zip->open(public_path($zipFile), ZipArchive::CREATE) === true) {
                foreach ($ids as $id) {
                    $file = RequestWiseFile::where('id', $id)->first();
                    $path = public_path() . '/storage/' . $file->file_name;
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
