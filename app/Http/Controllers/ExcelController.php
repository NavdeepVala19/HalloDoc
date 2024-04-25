<?php

namespace App\Http\Controllers;

use request;
use App\Exports\UsersExport;
use App\Models\request_Client;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class ExcelController extends Controller
{
    public function exportAll()
    {
        $data = request_Client::select(
            'request_client.first_name',
            'request_client.last_name',
            'request_client.date_of_birth',
            'request.first_name as request_first_name',
            'request_client.created_at',
            'request_client.phone_number',
            DB::raw("CONCAT(request_client.street,',',request_client.city,',',request_client.state) AS address"),
            'request_client.notes'
        )
        ->leftJoin('request', 'request.id', '=', 'request_client.request_id')->get();

        $exportAll = new UsersExport($data);
        return Excel::download($exportAll, 'AllData.xls');
    }

}