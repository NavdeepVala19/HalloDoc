<?php

namespace App\Exports;

use App\Models\RequestTable;
use App\Models\request_Client;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;


class UsersExport implements FromCollection, WithCustomCsvSettings, WithHeadings
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
        return ['PatientName', 'Date_of_Birth', 'RequestorName', 'RequestedDate', 'Mobile', 'Address','Notes'];
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $adminAllData = $this->data;
        // dd($adminAllData);
        return collect($adminAllData)->map(function ($adminAll) {
            $patientName = null;
            $patientLastName = null;
            $dateOfBirth = null;
            $address = null;
            $patientMobile = null;
            $patientMobile = null;
            $notes = null;
            $requestedDate = null;
            $requestorFirstName = null;
            $requestorLastName = null;

            if (isset($adminAll)) {
                $patientName = $adminAll->first_name;
            }
            if (isset($adminAll)) {
                $patientLastName = $adminAll->last_name;
            }
            if (isset($adminAll) ) {
                $patientMobile = $adminAll->phone_number;
            }
            if (isset($adminAll)) {
                $dateOfBirth = $adminAll->date_of_birth;
            }
            if (isset($adminAll)) {
                $address = $adminAll->address;
            }
            if (isset($adminAll)) {
                $requestorFirstName = $adminAll->request_first_name;
            }
            if (isset($adminAll)) {
                $requestorLastName = $adminAll->request_last_name;
            }
            if (isset($adminAll)) {
                $notes = $adminAll->notes;
            }
            if (isset($adminAll)) {
                $requestedDate = $adminAll->created_at;
            }


            return [
                'PatientName' => $patientName . ' ' . $patientLastName,
                'Date_of_Birth' => $dateOfBirth,
                'Requestor' => $requestorFirstName.' '. $requestorLastName,
                'RequestedDate' => $requestedDate,
                'Mobile' => $patientMobile,
                'Address' => $address,
                'Notes' => $notes,
            ];
        });
    }
}