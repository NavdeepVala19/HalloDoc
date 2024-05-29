<?php

namespace App\Http\Controllers;

use App\Models\RequestWiseFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use ZipArchive;

class PatientViewDocumentsController extends Controller
{
    /**
     * show user(patient) firstname , confirmation number, filename, created_at
     *
     * @param mixed $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function patientViewDocument($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $documents = $this->getDocumentsByRequestId($id);
            return view('patientSite/patientViewDocument', compact('documents'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    /**
     * upload document in request_wise_file at $request->request_wise_file_id
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadDocs(Request $request)
    {
        $this->validateDocument($request);
        $requestWiseData = $this->getRequestWiseFileData($request->request_wise_file_id);

        $this->storeDocument($request, $requestWiseData->request_id);

        return back();
    }

    /**
     * download individual documents
     *
     * @param mixed $id
     *
     * @return mixed|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadOne($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $file = $this->getFileById($id);
            return $this->downloadFile($file->file_name);
        } catch (\Throwable $th) {
            return view('errors.500');
        }
    }

    /**
     *  download multiple documents
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadSelectedFiles(Request $request)
    {
        try {
            $ids = $this->getSelectedFileIds($request);
            $zipFile = $this->createZipFile($ids);
            return response()->download(public_path($zipFile))->deleteFileAfterSend(true);
        } catch (\Throwable $th) {
            return view('errors.500');
        }
    }

    // Helper methods

    private function getDocumentsByRequestId($id)
    {
        return RequestWiseFile::with('request')->where('request_id', $id)->paginate(10);
    }

    private function validateDocument(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:jpg,png,jpeg,pdf,doc,docx|max:2048',
        ]);
    }

    private function getRequestWiseFileData($requestId)
    {
        return RequestWiseFile::where('request_id', $requestId)->first();
    }

    private function storeDocument(Request $request, $requestId)
    {
        $fileName = uniqid() . '_' . $request->file('document')->getClientOriginalName();
        $request->file('document')->storeAs('public', $fileName);

        $requestFile = new RequestWiseFile();
        $requestFile->request_id = $requestId;
        $requestFile->file_name = $fileName;
        $requestFile->save();
    }

    private function getFileById($id)
    {
        return RequestWiseFile::where('id', $id)->first();
    }

    private function downloadFile($fileName)
    {
        $path = public_path() . '/storage/' . $fileName;
        return response()->download($path);
    }

    private function getSelectedFileIds(Request $request)
    {
        return $request->selected_files ?: RequestWiseFile::where('request_id', $request->requestId)->pluck('id')->toArray();
    }

    private function createZipFile($ids)
    {
        $zip = new ZipArchive();
        $zipFile = 'documents.zip';

        if ($zip->open(public_path($zipFile), ZipArchive::CREATE) === true) {
            foreach ($ids as $id) {
                $file = $this->getFileById($id);
                $path = public_path() . '/storage/' . $file->file_name;
                $zip->addFile($path, $file->file_name);
            }
            $zip->close();
        }

        return $zipFile;
    }
}
