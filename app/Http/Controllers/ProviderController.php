<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\requestTable;
use App\Models\request_Client;

class ProviderController extends Controller
{
    public function listing(Request $request)
    {
        // $query = requestTable::with(['requestClient']);
        $newCases = requestTable::with(['requestClient'])->where('status', 1)->paginate(10);
        $pendingCases = requestTable::with(['requestClient'])->where('status', 2)->paginate(10);
        $activeCases = requestTable::with(['requestClient'])->where('status', 3)->paginate(10);
        $concludeCases = requestTable::with(['requestClient'])->where('status', 4)->paginate(10);

        // Search Functionality

        $search = '';
        if(!empty($request->search)){
            $search = requestTable::with(['requestClient'])->where('status', 1)->where('first_name', 'like', '%' . $request->search . '%')->paginate(10);
        }
        return view('providerPage/provider', compact('newCases', 'pendingCases', 'activeCases', 'concludeCases', 'search'));
    }

    public function filter(Request $request, $status = '1', $category = 'all')
    {
        // for total count only
        $newCases = requestTable::with(['requestClient'])->where('status', 1)->paginate(10);
        $pendingCases = requestTable::with(['requestClient'])->where('status', 2)->paginate(10);
        $activeCases = requestTable::with(['requestClient'])->where('status', 3)->paginate(10);
        $concludeCases = requestTable::with(['requestClient'])->where('status', 4)->paginate(10);

        // $cases = '';
        if ($category == 'all') {
            $cases = requestTable::with(['requestClient'])->where('status', $this->getStatusId($status))->paginate(10);
        } else {
            $cases = requestTable::with(['requestClient'])->where('status', $this->getStatusId($status))->where('request_type_id', $this->getCategoryId($category))->paginate(10);
        }

        // dd($cases);
        $search = '';
        if (!empty($request->search)) {
            $search = requestTable::with(['requestClient'])->where('status', 1)->where('first_name', 'like', '%' . $request->search . '%')->paginate(10);
        }

        return view('providerPage/provider', compact('cases', 'search', 'newCases', 'pendingCases', 'activeCases', 'concludeCases'));
    }

    public function status(Request $request, $status = '1', $category = 'all')
    {
        if ($category == 'all') {
            $cases = requestTable::with(['requestClient'])->where('status', $this->getStatusId($status))->paginate(10);
        } else {
            $cases = requestTable::with(['requestClient'])->where('status', $this->getStatusId($status))->where('request_type_id', $this->getCategoryId($category))->paginate(10);
        }

        // for total count only
        $newCases = requestTable::with(['requestClient'])->where('status', 1)->paginate(10);
        $pendingCases = requestTable::with(['requestClient'])->where('status', 2)->paginate(10);
        $activeCases = requestTable::with(['requestClient'])->where('status', 3)->paginate(10);
        $concludeCases = requestTable::with(['requestClient'])->where('status', 4)->paginate(10);

        $search = '';
        if (!empty($request->search)) {
            $search = requestTable::with(['requestClient'])->where('status', 1)->where('first_name', 'like', '%' . $request->search . '%')->paginate(10);
        }
        return view('providerPage.provider', compact('cases', 'search', 'newCases', 'pendingCases', 'activeCases', 'concludeCases'));
    }

    //Get category id from the name of category
    private function getCategoryId($category)
    {
        // mapping of category names to request_type_id
        $categoryMapping = [
            'patient' => 1,
            'family' => 2,
            'business' => 3,
            'concierge' => 4,
        ];
        return $categoryMapping[$category] ?? null;
    }

    //Get status id from the name of status
    private function getStatusId($status)
    {
        // mapping of status names to status
        $statusMapping = [
            'new' => 1,
            'pending' => 2,
            'active' => 3,
            'conclude' => 4,
        ];
        return $statusMapping[$status] ?? null;
    }

    // Create Request Page for Provider implementation
    public function createRequest(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email',
            'dob' => 'required',
            'street' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'optional',
            'room' => 'optional',
            'notes' => 'optional',
        ]);
    }
}
