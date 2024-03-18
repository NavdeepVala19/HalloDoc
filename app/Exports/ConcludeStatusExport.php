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

            $patientName = null;
            $dateOfBirth = null;
            $street = null;
            $city = null;
            $state = null;

            if (isset($adminConclude->request) && $adminConclude->request->requestClient) {
                $patientName = $adminConclude->request->requestClient->first_name;
            }

            if (isset($adminConclude->request) && $adminConclude->request->requestClient) {
                $dateOfBirth = $adminConclude->request->requestClient->date_of_birth;
            }

            if (isset($adminConclude->request) && $adminConclude->request->requestClient) {
                $street = $adminConclude->request->requestClient->street;
            }
            if (isset($adminConclude->request) && $adminConclude->request->requestClient) {
                $city = $adminConclude->request->requestClient->city;
            }
            if (isset($adminConclude->request) && $adminConclude->request->requestClient) {
                $state = $adminConclude->request->requestClient->state;
            }

            return [
                'PatientName' => $patientName,
                'Date of Birth' => $dateOfBirth,
                'Requestor' => $adminConclude->request->first_name,
                'RequestedDate' => $adminConclude->request->created_at,
                'Mobile' => $adminConclude->request->phone_number,
                'Address' => $street . ',' . $city . ',' . $state,
            ];
        });
    }
}
