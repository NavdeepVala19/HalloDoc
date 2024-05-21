<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements
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
            'Date_of_Birth',
            'RequestorName',
            'RequestedDate',
            'Mobile',
            'Address',
            'Notes',
        ];
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $adminAllData = $this->data;
        return collect($adminAllData)->map(function ($adminAll) {
            if (isset($adminAll)) {
                return [
                    'PatientName' => $adminAll->first_name . ' ' . $adminAll->last_name,
                    'Date_of_Birth' => $adminAll->date_of_birth,
                    'Requestor' => $adminAll->request_first_name . ' ' . $adminAll->request_last_name,
                    'RequestedDate' => $adminAll->created_at,
                    'Mobile' => $adminAll->phone_number,
                    'Address' => $adminAll->address,
                    'Notes' => $adminAll->notes,
                ];
            }
        });
    }
}
