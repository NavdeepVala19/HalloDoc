<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;


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
        return ['PatientName', 'Date Of Birth', 'Requestor', 'RequestedDate', 'PatientMobile', 'RequestorMobile', 'Address', 'Notes'];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $adminNewData = $this->data->get();
        return collect($adminNewData)->map(function ($adminNew) {
            if (isset($adminNew) && $adminNew->requestClient) {
                return [
                    'PatientName' => $adminNew->requestClient->first_name . ' ' . $adminNew->requestClient->last_name,
                    'Date of Birth' => $adminNew->requestClient->date_of_birth,
                    'Requestor' => $adminNew->first_name . ' ' . $adminNew->last_name,
                    'RequestedDate' => $adminNew->created_at,
                    'PatientMobile' => $adminNew->requestClient->phone_number,
                    'RequestorMobile' => $adminNew->phone_number,
                    'Address' => $adminNew->requestClient->street . ',' . $adminNew->requestClient->city . ',' . $adminNew->requestClient->state,
                    'Notes' => $adminNew->requestClient->notes,
                ];
            }
        });
    }
}
