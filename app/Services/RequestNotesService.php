<?php

namespace App\Services;

use App\Models\RequestNotes;

class RequestNotesService
{
    public function createEntry($request, $requestId)
    {
        RequestNotes::create([
            'request_id' => $requestId,
            'physician_notes' => $request->note,
            'created_by' => 'physician',
        ]);
    }
}
