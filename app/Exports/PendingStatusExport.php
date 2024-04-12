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
        return ['PatientName', 'Date Of Birth', 'Requestor', 'PhysicianName', 'RequestedDate', 'Mobile', 'Address', 'Notes'];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $adminPendingData = $this->data->get();

        return collect($adminPendingData)->map(function ($adminPending) {
            $patientName = null;
            $patientLastName = null;
            $dateOfBirth = null;
            $street = null;
            $city = null;
            $state = null;

            if (isset($adminPending) && $adminPending->requestClient) {
                $patientName = $adminPending->requestClient->first_name;
            }
            if (isset($adminPending) && $adminPending->requestClient) {
                $patientLastName = $adminPending->requestClient->last_name;
            }
            if (isset($adminPending) && $adminPending->requestClient) {
                $dateOfBirth = $adminPending->requestClient->date_of_birth;
            }

            if (isset($adminPending) && $adminPending->requestClient) {
                $street = $adminPending->requestClient->street;
            }
            if (isset($adminPending) && $adminPending->requestClient) {
                $city = $adminPending->requestClient->city;
            }
            if (isset($adminPending) && $adminPending->requestClient) {
                $state = $adminPending->requestClient->state;
            }
            return [
                'PatientName' => $patientName . ' ' . $patientLastName,
                'Date of Birth' => $dateOfBirth,
                'Requestor' => $adminPending->first_name . ' ' . $adminPending->first_name,
                'PhysicianName' => $adminPending->provider->first_name . ' ' . $adminPending->provider->last_name,
                'RequestedDate' => $adminPending->created_at,
                'Mobile' => $adminPending->phone_number,
                'Address' => $street . ',' . $city . ',' . $state,
                'Notes' => $adminPending->requestClient->notes,
            ];
        });
    }
}