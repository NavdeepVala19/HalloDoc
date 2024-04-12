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
        return ['PatientName', 'Date Of Birth', 'PhysicianName', 'RequestedDate', 'PatientMobile', 'RequestorMobile', 'Address'];
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
            $PatientMobile = null;

            if (isset($adminConclude) && $adminConclude->requestClient) {
                $patientName = $adminConclude->requestClient->first_name;
            }
            if (isset($adminConclude) && $adminConclude->requestClient) {
                $dateOfBirth = $adminConclude->requestClient->date_of_birth;
            }
            if (isset($adminConclude) && $adminConclude->requestClient) {
                $PatientMobile = $adminConclude->requestClient->phone_number;
            }
            if (isset($adminConclude) && $adminConclude->requestClient) {
                $street = $adminConclude->requestClient->street;
            }
            if (isset($adminConclude) && $adminConclude->requestClient) {
                $city = $adminConclude->requestClient->city;
            }
            if (isset($adminConclude) && $adminConclude->requestClient) {
                $state = $adminConclude->requestClient->state;
            }

            return [
                'PatientName' => $patientName,
                'Date of Birth' => $dateOfBirth,
                'PhysicianName' => $adminConclude->provider->first_name . ' ' . $adminConclude->provider->last_name,
                'RequestedDate' => $adminConclude->created_at,
                'PatientMobile' => $PatientMobile,
                'RequestorMobile' => $adminConclude->phone_number,
                'Address' => $street . ',' . $city . ',' . $state,
            ];
        });
    }
}