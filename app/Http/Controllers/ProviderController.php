<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\requestTable;

class ProviderController extends Controller
{
    public function listing(Request $request,$status = '1' , $category = 'all')
    {
        // $query = requestTable::with(['requestClient']);

        $newCases = requestTable::with(['requestClient'])->where('status', 1)->paginate(10);
        $pendingCases = requestTable::with(['requestClient'])->where('status', 2)->paginate(10);
        $activeCases = requestTable::with(['requestClient'])->where('status', 3)->paginate(10);
        $concludeCases = requestTable::with(['requestClient'])->where('status', 4)->paginate(10);

        $newCases = requestTable::with(['requestClient'])->where('status', $status)->paginate(10);
        $pendingCases = requestTable::with(['requestClient'])->where('status', $status)->paginate(10);
        $activeCases = requestTable::with(['requestClient'])->where('status', $status)->paginate(10);
        $concludeCases = requestTable::with(['requestClient'])->where('status', $status)->paginate(10);

        // $newCases = ($category == 'all')
        //     ? requestTable::with(['requestClient'])->where('status', 1)->paginate(10)
        //     : requestTable::with(['requestClient'])->where('request_type_id', $this->getCategoryId($category))->where('status', 1)->paginate(10);

        // $pendingCases = ($category == 'all')
        //     ? requestTable::with(['requestClient'])->where('status', 2)->paginate(10)
        //     : requestTable::with(['requestClient'])->where('request_type_id', $this->getCategoryId($category))->where('status', 2)->paginate(10);

        // $activeCases = ($category == 'all')
        //     ? requestTable::with(['requestClient'])->where('status', 3)->paginate(10)
        //     : requestTable::with(['requestClient'])->where('request_type_id', $this->getCategoryId($category))->where('status', 3)->paginate(10);

        // $concludeCases = ($category == 'all')
        //     ? requestTable::with(['requestClient'])->where('status', 4)->paginate(10)
        //     : requestTable::with(['requestClient'])->where('request_type_id', $this->getCategoryId($category))->where('status', 4)->paginate(10);

        return view('providerPage/provider', compact('newCases', 'pendingCases', 'activeCases', 'concludeCases'));
    }

    // public function filter(Request $request, $category = 'all')
    // {
    //     // $query = requestTable::with(['requestClient']);
    //     $newCases = ($category == 'all')
    //         ? requestTable::where('status', 1)->paginate(10)
    //         : requestTable::where('request_type_id', $this->getCategoryId($category))->where('status', 1)->paginate(10);

    //     $pendingCases = ($category == 'all')
    //         ? requestTable::where('status', 2)->paginate(10)
    //         : requestTable::where('request_type_id', $this->getCategoryId($category))->where('status', 2)->paginate(10);

    //     $activeCases = ($category == 'all')
    //         ? requestTable::where('status', 3)->paginate(10)
    //         : requestTable::where('request_type_id', $this->getCategoryId($category))->where('status', 3)->paginate(10);

    //     $concludeCases = ($category == 'all')
    //         ? requestTable::where('status', 4)->paginate(10)
    //         : requestTable::where('request_type_id', $this->getCategoryId($category))->where('status', 4)->paginate(10);

    //     return view('providerPage/provider', compact('newCases', 'pendingCases', 'activeCases', 'concludeCases',));
    // }

    private function getCategoryId($category)
    {
        // Define your mapping of category names to request_type_id
        $categoryMapping = [
            'patient' => 1,
            'family' => 2,
            'business' => 3,
            'concierge' => 4,
        ];

        return $categoryMapping[$category] ?? null;
    }
}
