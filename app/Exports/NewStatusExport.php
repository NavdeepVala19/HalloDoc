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

        return collect($adminNewData)->map(function ($adminNew) {
            $patientName = null;
            $dateOfBirth = null;
            $street = null;
            $city = null;
            $state = null;

            if (isset($adminNew->request) && $adminNew->request->requestClient) {
                $patientName = $adminNew->request->requestClient->first_name;
            }

            if (isset($adminNew->request) && $adminNew->request->requestClient) {
                $dateOfBirth = $adminNew->request->requestClient->date_of_birth;
            }

            if (isset($adminNew->request) && $adminNew->request->requestClient) {
                $street = $adminNew->request->requestClient->street;
            }
            if (isset($adminNew->request) && $adminNew->request->requestClient) {
                $city = $adminNew->request->requestClient->city;
            }
            if (isset($adminNew->request) && $adminNew->request->requestClient) {
                $state = $adminNew->request->requestClient->state;
            }
           
            return [
                'PatientName' => $patientName,
                'Date of Birth' => $dateOfBirth,
                'Requestor' => $adminNew->request->first_name,
                'RequestedDate' => $adminNew->request->created_at,
                'Mobile' => $adminNew->request->phone_number,
                'Address' => $street . ',' . $city . ',' . $state,
            ];
        });
    }
}
