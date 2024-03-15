<?php

namespace App\Exports;

use App\Models\RequestTable;
use App\Models\request_Client;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;


class UnPaidStatusExport implements FromCollection, WithCustomCsvSettings, WithHeadings
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
        $adminUnpaidData = $this->data->get();

        return collect($adminUnpaidData)->map(function ($adminUnpaid) {

            return [
                'PatientName' => $adminUnpaid->request->requestClient->first_name,
                'Date of Birth' => $adminUnpaid->request->requestClient->date_of_birth,
                'Requestor' => $adminUnpaid->request->first_name,
                'RequestedDate' => $adminUnpaid->request->created_at,
                'Mobile' => $adminUnpaid->request->phone_number,
                'Address' => $adminUnpaid->request->requestClient->street . ',' . $adminUnpaid->request->requestClient->city . ',' . $adminUnpaid->request->requestClient->state,
            ];
        });
    }
}
