<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\requestTable;

class ProviderController extends Controller
{
    public function listing()
    {
        $newCases = requestTable::where('status', 1)->with(['requestClient'])->get();
        $pendingCases = requestTable::where('status', 2)->get();
        $activeCases = requestTable::where('status', 3)->get();
        $concludeCases = requestTable::where('status', 4)->get();

        return view('providerPage/provider', compact('newCases', 'pendingCases', 'activeCases', 'concludeCases'));
    }
}
