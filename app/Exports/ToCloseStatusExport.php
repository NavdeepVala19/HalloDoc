<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ToCloseStatusExport implements FromCollection, WithCustomCsvSettings, WithHeadings
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
        return ['PatientName', 'Date Of Birth', 'Date of Service', 'Address', 'Notes'];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $adminToCloseData = $this->data->get();

        return collect($adminToCloseData)->map(function ($adminToClose) {
            $patient = $adminToClose->requestClient ?? [];
            $provider = $adminToClose->provider ?? [];

            return [
                'PatientName' => ($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''),
                'Date of Birth' => ($patient['date_of_birth'] ?? ''),
                'PhysicianName' => ($provider['first_name'] ?? '') . ' ' . ($provider['last_name'] ?? ''),
                'Address' => ($patient['street'] ?? '') . ',' . ($patient['city'] ?? '') . ',' . ($patient['state'] ?? ''),
                'Notes' => ($patient['notes'] ?? ''),
            ];
        });
    }
}
