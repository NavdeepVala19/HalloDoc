<?php

namespace App\Exports;

use App\Models\RequestTable;
use App\Models\request_Client;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;


class ConcludeStatusExport implements FromCollection, WithCustomCsvSettings, WithHeadings
{

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    public function getCsvSettings(): array
    {
        return ['delimiter' => ','];
    }

    public function headings(): array
    {
        return ['PatientName', 'Date Of Birth', 'Requestor', 'RequestedDate', 'Mobile', 'Address'];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $adminConcludeData = $this->data->get();

        return collect($adminConcludeData)->map(function ($adminConclude) {

            return [
                'PatientName' => $adminConclude->request->requestClient->first_name,
                'Date of Birth' => $adminConclude->request->requestClient->date_of_birth,
                'Requestor' => $adminConclude->request->first_name,
                'RequestedDate' => $adminConclude->request->created_at,
                'Mobile' => $adminConclude->request->phone_number,
                'Address' => $adminConclude->request->requestClient->street . ',' . $adminConclude->request->requestClient->city . ',' . $adminConclude->request->requestClient->state,
            ];
        });
    }
}
