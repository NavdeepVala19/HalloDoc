<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ActiveStatusExport implements
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
            'Date Of Birth',
            'Requestor',
            'PhysicianName',
            'RequestedDate',
            'PatientMobile',
            'RequestorMobile',
            'Address',
            'Notes',
        ];
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $adminActiveData = $this->data->get();

        return collect($adminActiveData)->map(function ($adminActive) {
            if (isset ($adminActive) && $adminActive->requestClient) {
                return [
                    'PatientName' => $adminActive->requestClient->first_name.' ' .$adminActive->requestClient->last_name,
                    'Date Of Birth' => $adminActive->requestClient->date_of_birth,
                    'Requestor' => $adminActive->first_name.' '.$adminActive->last_name,
                    'PhysicianName' => $adminActive->provider->first_name.' '.$adminActive->provider->last_name,
                    'RequestedDate' => $adminActive->created_at,
                    'PatientMobile' => $adminActive->requestClient->phone_number,
                    'RequestorMobile' => $adminActive->phone_number,
                    'Address' => $adminActive->requestClient->street.','.$adminActive->requestClient->city.',' .$adminActive->requestClient->state,
                    'Notes' => $adminActive->requestClient->notes,
                ];
            }
        });
    }
}
