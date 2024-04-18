<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;


class ExcelController extends Controller
{
    public function exportAll()
    {
        return Excel::download(new UsersExport, 'AllData.xls');
    }

}