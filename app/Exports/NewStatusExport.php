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
        return ['PatientName', 'Date Of Birth', 'Requestor', 'RequestedDate', 'PatientMobile', 'RequestorMobile','Address','Notes'];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $adminNewData = $this->data->get();
        return collect($adminNewData)->map(function ($adminNew) {
            // dd($adminNew);
            $patientName = null;
            $patientLastName = null;
            $dateOfBirth = null;
            $street = null;
            $city = null;
            $state = null;
            $patientMobile = null;

            if (isset($adminNew) && $adminNew->requestClient) {
                $patientName = $adminNew->requestClient->first_name;
            }
            if (isset($adminNew) && $adminNew->requestClient) {
                $patientLastName = $adminNew->requestClient->last_name;
            }
            if (isset($adminNew) && $adminNew->requestClient) {
                $patientMobile = $adminNew->requestClient->phone_number;
            }
            if (isset($adminNew) && $adminNew->requestClient) {
                $dateOfBirth = $adminNew->requestClient->date_of_birth;
            }
            if (isset($adminNew) && $adminNew->requestClient) {
                $street = $adminNew->requestClient->street;
            }
            if (isset($adminNew) && $adminNew->requestClient) {
                $city = $adminNew->requestClient->city;
            }
            if (isset($adminNew) && $adminNew->requestClient) {
                $state = $adminNew->requestClient->state;
            }
           
            return [
                'PatientName' => $patientName.' '.$patientLastName,
                'Date of Birth' => $dateOfBirth,
                'Requestor' => $adminNew->first_name.' '.$adminNew->last_name,
                'RequestedDate' => $adminNew->created_at,
                'PatientMobile' => $patientMobile,
                'RequestorMobile'=>$adminNew->phone_number,
                'Address' => $street . ',' . $city . ',' . $state,
                'Notes'=>$adminNew->requestClient->notes,
            ];
        });
    }
}
