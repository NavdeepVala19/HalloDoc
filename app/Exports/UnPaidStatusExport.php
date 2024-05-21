<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UnPaidStatusExport implements FromCollection, WithCustomCsvSettings, WithHeadings
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
        return ['PatientName', 'Physician Name', 'RequestedDate', 'PatientMobile', 'RequestorMobile', 'Mobile', 'Address'];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $adminUnpaidData = $this->data->get();

        return collect($adminUnpaidData)->map(function ($adminUnpaid) {
            if (isset($adminUnpaid) && $adminUnpaid->requestClient) {
                return [
                    'PatientName' => $adminUnpaid->requestClient->first_name . ' ' . $adminUnpaid->requestClient->last_name,
                    'Physician Name' => $adminUnpaid->provider->first_name . ' ' . $adminUnpaid->provider->last_name,
                    'RequestedDate' => $adminUnpaid->created_at,
                    'PatientMobile' => $adminUnpaid->requestClient->phone_number,
                    'RequestorMobile' => $adminUnpaid->phone_number,
                    'Address' => $adminUnpaid->requestClient->street . ',' . $adminUnpaid->requestClient->city . ',' . $adminUnpaid->requestClient->state,
                ];
            }
        });
    }
}
