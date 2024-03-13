<?php

namespace App\Exports;

use App\Models\RequestTable;
use App\Models\request_Client;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;


class UsersExport implements FromCollection, WithCustomCsvSettings, WithHeadings
{

    public function getCsvSettings(): array
    {
        return ['delimiter' => ','];
    }

    public function headings(): array
    {
        return ['PatientName', 'DOB', 'RequestorName', 'RequestedDate', 'Mobile', 'Address'];
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = request_Client::select('request_client.first_name', 'request_client.date_of_birth', 'request.first_name as request_first_name', 'request_client.created_at', 'request_client.phone_number', DB::raw("CONCAT(request_client.street,',',request_client.city,',',request_client.state) AS address"))
            ->leftJoin('request', 'request.id', '=', 'request_client.request_id')->get();
        return $data;
    }
}
