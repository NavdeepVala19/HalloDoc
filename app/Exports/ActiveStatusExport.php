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
        return ['PatientName', 'Date Of Birth', 'Requestor', 'PhysicianName', 'RequestedDate', 'PatientMobile', 'RequestorMobile', 'Address', 'Notes'];
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $adminActiveData = $this->data->get();

        return collect($adminActiveData)->map(function ($adminActive) {

            $patientName = null;
            $patientLastName = null;
            $dateOfBirth = null;
            $street = null;
            $city = null;
            $patientMobile = null;
            $state = null;

            if (isset($adminActive) && $adminActive->requestClient) {
                $patientName = $adminActive->requestClient->first_name;
            }
            if (isset($adminActive) && $adminActive->requestClient) {
                $patientLastName = $adminActive->requestClient->last_name;
            }
            if (isset($adminActive) && $adminActive->requestClient) {
                $dateOfBirth = $adminActive->requestClient->date_of_birth;
            }
            if (isset($adminActive) && $adminActive->requestClient) {
                $patientMobile = $adminActive->requestClient->phone_number;
            }
            if (isset($adminActive) && $adminActive->requestClient) {
                $street = $adminActive->requestClient->street;
            }
            if (isset($adminActive) && $adminActive->requestClient) {
                $city = $adminActive->requestClient->city;
            }
            if (isset($adminActive) && $adminActive->requestClient) {
                $state = $adminActive->requestClient->state;
            }


            return [
                'PatientName' => $patientName . ' ' . $patientLastName,
                'Date of Birth' => $dateOfBirth,
                'Requestor' => $adminActive->first_name . ' ' . $adminActive->last_name,
                'PhysicianName' => $adminActive->provider->first_name . ' ' . $adminActive->provider->last_name,
                'RequestedDate' => $adminActive->created_at,
                'PatientMobile' => $patientMobile,
                'RequestorMobile' => $adminActive->phone_number,
                'Address' => $street . ',' . $city . ',' . $state,
                'Notes' => $adminActive->requestClient->notes,
            ];
        });
    }
}