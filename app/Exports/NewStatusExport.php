<?php

namespace App\Exports;

use App\Models\RequestTable;
use App\Models\request_Client;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;


class NewStatusExport implements FromCollection, WithCustomCsvSettings, WithHeadings
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


        $adminNewData = $this->data->get();
        // dd($adminNewData);

        return collect($adminNewData)->map(function ($adminNew) {

            return [
                'PatientName' => $adminNew->requestClient->first_name,
                'Date of Birth' => $adminNew->requestClient->date_of_birth,
                'Requestor' => $adminNew->request->first_name,
                'RequestedDate' => $adminNew->request->created_at,
                'Mobile' => $adminNew->request->phone_number,
                'Address' => $adminNew->request->requestClient->street . ',' . $adminNew->request->requestClient->city . ',' . $adminNew->request->requestClient->state,
            ];
        });
    }
}
