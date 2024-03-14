<?php

namespace App\Exports;

use App\Models\RequestTable;
use App\Models\request_Client;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;


class PendingStatusExport implements FromCollection, WithCustomCsvSettings, WithHeadings
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
        $adminPendingData = $this->data->get();

        return collect($adminPendingData)->map(function ($adminPending) {

            return [
                'PatientName' => $adminPending->request->requestClient->first_name,
                'Date of Birth' => $adminPending->request->requestClient->date_of_birth,
                'Requestor' => $adminPending->request->first_name,
                'RequestedDate' => $adminPending->request->created_at,
                'Mobile' => $adminPending->request->phone_number,
                'Address' => $adminPending->request->requestClient->street . ',' . $adminPending->request->requestClient->city . ',' . $adminPending->request->requestClient->state,
            ];
        });
    }
}
