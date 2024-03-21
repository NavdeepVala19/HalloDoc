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

            $patientName = null;
            $dateOfBirth = null;
            $street = null;
            $city = null;
            $state = null;

            if (isset($adminPending->request) && $adminPending->request->requestClient) {
                $patientName = $adminPending->request->requestClient->first_name;
            }

            if (isset($adminPending->request) && $adminPending->request->requestClient) {
                $dateOfBirth = $adminPending->request->requestClient->date_of_birth;
            }

            if (isset($adminPending->request) && $adminPending->request->requestClient) {
                $street = $adminPending->request->requestClient->street;
            }
            if (isset($adminPending->request) && $adminPending->request->requestClient) {
                $city = $adminPending->request->requestClient->city;
            }
            if (isset($adminPending->request) && $adminPending->request->requestClient) {
                $state = $adminPending->request->requestClient->state;
            }



            return [
                'PatientName' => $patientName,
                'Date of Birth' => $dateOfBirth,
                'Requestor' => $adminPending->request->first_name,
                'RequestedDate' => $adminPending->request->created_at,
                'Mobile' => $adminPending->request->phone_number,
                'Address' => $street . ',' . $city . ',' . $state,
            ];
        });
    }
}
