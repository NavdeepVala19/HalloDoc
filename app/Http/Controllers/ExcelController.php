<?php

namespace App\Http\Controllers;

use App\Exports\ActiveStatusExport;
use App\Exports\ConcludeStatusExport;
use App\Exports\UsersExport;
use App\Models\RequestTable;
use Illuminate\Http\Request;
use App\Exports\NewStatusExport;
use App\Exports\PendingStatusExport;
use App\Exports\ToCloseStatusExport;
use App\Exports\UnPaidStatusExport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function exportAll()
    {
        return Excel::download(new UsersExport, 'AllData.csv');
    }

    public function exportNewData()
    {
        return Excel::download(new NewStatusExport, 'NewData.csv');
    }



    public function pendingDataExport()
    {
        return Excel::download(new PendingStatusExport, 'PendingData.csv');
    }


    public function activeDataExport(){
        return Excel::download(new ActiveStatusExport, 'ActiveData.csv');
    }


    public function concludeDataExport(){
        return Excel::download(new ConcludeStatusExport, 'ConcludeData.csv');
    }

    public function toCloseDataExport(){
        return Excel::download(new ToCloseStatusExport, 'ToCloseData.csv');
    }

    public function unPaidDataExport(){
        return Excel::download(new UnPaidStatusExport, 'UnPaidData.csv');
    }

}
