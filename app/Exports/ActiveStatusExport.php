<?php

namespace App\Exports;

use App\Models\RequestTable;
use App\Models\request_Client;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class ActiveStatusExport implements FromCollection, WithCustomCsvSettings, WithHeadings
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
        $adminActiveData = $this->data->get();

        return collect($adminActiveData)->map(function ($adminActive) {

            $patientName = null;
            $dateOfBirth = null;
            $street = null;
            $city = null;
            $state = null;

            if (isset($adminActive->request) && $adminActive->request->requestClient) {
                $patientName = $adminActive->request->requestClient->first_name;
            }

            if (isset($adminActive->request) && $adminActive->request->requestClient) {
                $dateOfBirth = $adminActive->request->requestClient->date_of_birth;
            }

            if (isset($adminActive->request) && $adminActive->request->requestClient) {
                $street = $adminActive->request->requestClient->street;
            }
            if (isset($adminActive->request) && $adminActive->request->requestClient) {
                $city = $adminActive->request->requestClient->city;
            }
            if (isset($adminActive->request) && $adminActive->request->requestClient) {
                $state = $adminActive->request->requestClient->state;
            }


            return [
                'PatientName' => $patientName,
                'Date of Birth' => $dateOfBirth,
                'Requestor' => $adminActive->request->first_name,
                'RequestedDate' => $adminActive->request->created_at,
                'Mobile' => $adminActive->request->phone_number,
                'Address' => $street . ',' . $city . ',' . $state,
            ];
        });
    }
}
