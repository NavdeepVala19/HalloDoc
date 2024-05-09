<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SearchRecordExport implements FromCollection, WithCustomCsvSettings, WithHeadings
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
        return ['PatientName', 'Requestor', 'Email', 'Phone_Number', 'Address', 'Zip', 'Request Status', 'Physician', 'Physician Note', 'Admin Note', 'Patient Note'];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $patientData = $this->data->get();

        return collect($patientData)->map(function ($patient) {
            $requestor = '';
            switch ($patient->request_type_id) {
                case 1:
                    $requestor = 'Patient';
                    break;
                case 2:
                    $requestor = 'Family/Friend';
                    break;
                case 3:
                    $requestor = 'Concierge';
                    break;
                case 4:
                    $requestor = 'Business';
                    break;
                default:
                    $requestor = '';
                    break;
            }

            $request_status = '';
            switch ($patient->status) {
                case 1:
                    $request_status = 'Unassigned';
                    break;

                case 2:
                    $request_status = 'Cancelled';
                    break;

                case 3:
                    $request_status = 'Accepted';
                    break;

                case 4:
                    $request_status = 'MDEnRoute';
                    break;

                case 5:
                    $request_status = 'MDOnSite';
                    break;

                case 6:
                    $request_status = 'Conclude';
                    break;

                case 7:
                    $request_status = 'Closed';
                    break;

                case 8:
                    $request_status = 'Clear';
                    break;

                case 9:
                    $request_status = 'Unpaid';
                    break;

                case 10:
                    $request_status = 'Block';
                    break;
                case 11:
                    $request_status = 'CancelledByPatient';
                    break;

                default:
                    $request_status = '';
                    break;
            }

            return [
                // Map patient data to desired Excel columns
                'PatientName' => $patient->first_name,
                'Requestor' => $requestor,
                'Email' => $patient->email,
                'Phone_Number' => $patient->phone_number,
                'Address' => $patient->street . ',' . $patient->city . ', ' . $patient->state,
                'Zip' => $patient->zipcode,
                'Request Status' => $request_status,
                'Physician' => $patient->physician_first_name,
                'Physician Note' => $patient->physician_notes,
                'Admin Note' => $patient->admin_notes,
                'Patient Note' => $patient->patient_notes,

            ];
        });
    }
}
