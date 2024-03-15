<?php

namespace App\Exports;

use App\Models\RequestTable;
use App\Models\request_Client;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class ToCloseStatusExport implements FromCollection, WithCustomCsvSettings, WithHeadings
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
        $adminToCloseData = $this->data->get();

        return collect($adminToCloseData)->map(function ($adminToClose) {

            return [
                'PatientName' => $adminToClose->request->requestClient->first_name,
                'Date of Birth' => $adminToClose->request->requestClient->date_of_birth,
                'Requestor' => $adminToClose->request->first_name,
                'RequestedDate' => $adminToClose->request->created_at,
                'Mobile' => $adminToClose->request->phone_number,
                'Address' => $adminToClose->request->requestClient->street . ',' . $adminToClose->request->requestClient->city . ',' . $adminToClose->request->requestClient->state,
            ];
        });
    }
}
