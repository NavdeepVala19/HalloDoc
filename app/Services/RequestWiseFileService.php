<?php

namespace App\Services;

use App\Models\RequestWiseFile;

class RequestWiseFileService
{
    public function storeDoc($request, $requestId)
    {
        $requestFile = new RequestWiseFile();
        $requestFile->request_id = $requestId;
        $requestFile->file_name = uniqid() . '_' . $request->file('docs')->getClientOriginalName();
        $requestFile->save();
        $request->file('docs')->storeAs('public', $requestFile->file_name);
    }
}
