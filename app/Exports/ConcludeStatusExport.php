<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;


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
            if (isset($adminConclude) && $adminConclude->requestClient) {
                return [
                    'PatientName' =>  $adminConclude->requestClient->first_name . ' ' .  $adminConclude->requestClient->last_name,
                    'Date of Birth' => $adminConclude->requestClient->date_of_birth,
                    'PhysicianName' => $adminConclude->provider->first_name . ' ' . $adminConclude->provider->last_name,
                    'RequestedDate' => $adminConclude->created_at,
                    'PatientMobile' => $adminConclude->requestClient->phone_number,
                    'RequestorMobile' => $adminConclude->phone_number,
                    'Address' => $adminConclude->requestClient->street . ',' . $adminConclude->requestClient->city . ',' .  $adminConclude->requestClient->state,
                ];
            }
        });
    }
}
