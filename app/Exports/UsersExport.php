<?php

namespace App\Exports;

use App\Models\RequestTable;
use App\Models\request_Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;


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
        return request_Client::select('first_name', 'date_of_birth', 'created_at', 'phone_number', 'street')->get();
        // $data = request_Client::with('request')->get();
        // dd($data->first());

        // return request_Client::with('request')->select(
        //     'request_client.first_name AS client_first_name',
        //     'date_of_birth',
        //     'request.first_name AS request_first_name',
        //     'created_at',
        //     'phone_number',
        //     'street'
        // )->get();
    }
}
