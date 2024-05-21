<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SearchRecordExport implements
    FromCollection,
    WithCustomCsvSettings,
    WithHeadings
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
        return [
            'PatientName',
            'Requestor',
            'Email',
            'Phone_Number',
            'Address',
            'Zip',
            'Request Status',
            'Physician',
            'Physician Note',
            'Admin Note',
            'Patient Note',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $patientData = $this->data->get();
        function getRequestType($id)
        {
            $requestType = [
                1 => 'Patient',
                2 => 'Family/Friend',
                3 => 'Concierge',
                4 => 'Business',
            ];

            return $requestType[$id];
        }
        function getStatusType($id)
        {
            $status = [
                1 => 'Unassigned',
                2 => 'Cancelled',
                3 => 'Accepted',
                4 => 'MDEnRoute',
                5 => 'MDOnSite',
                6 => 'Conclude',
                7 => 'Closed',
                8 => 'Clear',
                9 => 'Unpaid',
                10 => 'Block',
                11 => 'CancelledByPatient',
            ];
            return $status[$id];
        }
        return collect($patientData)->map(function ($patient) {
            return [
                // Map patient data to desired Excel columns
                'PatientName' => $patient->first_name,
                'Requestor' => getRequestType($patient->request_type_id),
                'Email' => $patient->email,
                'Phone_Number' => $patient->phone_number,
                'Address' => $patient->street . ',' . $patient->city . ', ' . $patient->state,
                'Zip' => $patient->zipcode,
                'Request Status' => getStatusType($patient->status),
                'Physician' => $patient->physician_first_name,
                'Physician Note' => $patient->physician_notes,
                'Admin Note' => $patient->admin_notes,
                'Patient Note' => $patient->patient_notes,
            ];
        });
    }
}
