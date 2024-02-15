<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\requestTable;
use App\Models\request_Client;
use App\Models\MedicalReport;

class ProviderController extends Controller
{
    public function status(Request $request, $status = 'new')
    {
        // for total count only
        $newCasesCount = requestTable::with(['requestClient'])->where('status', 1)->count();
        $pendingCasesCount = requestTable::with(['requestClient'])->where('status', 2)->count();
        $activeCasesCount = requestTable::with(['requestClient'])->where('status', 3)->count();
        $concludeCasesCount = requestTable::with(['requestClient'])->where('status', 4)->count();

        $cases = requestTable::with(['requestClient'])->where('status', $this->getStatusId($status))->paginate(10);

        if ($this->getStatusId($status) == '1') {
            return view('providerPage.providerTabs.newListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        } else if ($this->getStatusId($status) == '2') {
            return view('providerPage.providerTabs.pendingListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        } else if ($this->getStatusId($status) == '3') {
            return view('providerPage.providerTabs.activeListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        } else if ($this->getStatusId($status) == '4') {
            return view('providerPage.providerTabs.concludeListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        }
    }


    public function filter(Request $request, $status = 'new', $category = 'all')
    {
        $newCasesCount = requestTable::with(['requestClient'])->where('status', 1)->count();
        $pendingCasesCount = requestTable::with(['requestClient'])->where('status', 2)->count();
        $activeCasesCount = requestTable::with(['requestClient'])->where('status', 3)->count();
        $concludeCasesCount = requestTable::with(['requestClient'])->where('status', 4)->count();


        // $cases = '';
        if ($category == 'all') {
            $cases = requestTable::with(['requestClient'])->where('status', $this->getStatusId($status))->paginate(10);
        } else {
            $cases = requestTable::with(['requestClient'])->where('status', $this->getStatusId($status))->where('request_type_id', $this->getCategoryId($category))->paginate(10);
        }

        if ($this->getStatusId($status) == '1') {
            return view('providerPage.providerTabs.newListing', compact('cases',  'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        } else if ($this->getStatusId($status) == '2') {
            return view('providerPage.providerTabs.pendingListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        } else if ($this->getStatusId($status) == '3') {
            return view('providerPage.providerTabs.activeListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        } else if ($this->getStatusId($status) == '4') {
            return view('providerPage.providerTabs.concludeListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        }
    }



    public function search(Request $request, $status = 'new', $category = 'all')
    {
        $newCasesCount = requestTable::with(['requestClient'])->where('status', 1)->count();
        $pendingCasesCount = requestTable::with(['requestClient'])->where('status', 2)->count();
        $activeCasesCount = requestTable::with(['requestClient'])->where('status', 3)->count();
        $concludeCasesCount = requestTable::with(['requestClient'])->where('status', 4)->count();

        // dd($this->getCategoryId($category));
        if ($category == 'all') {
            $cases = requestTable::with(['requestClient'])
                ->where('status', $this->getStatusId($status))
                ->where('first_name', 'like', '%' . $request->search . '%')->paginate(10);
        } else {
            $cases = requestTable::with(['requestClient'])
                ->where('status', $this->getStatusId($status))->where('request_type_id', $this->getCategoryId($category))
                ->where('first_name', 'like', '%' . $request->search . '%')->paginate(10);
        }

        if ($this->getStatusId($status) == '1') {
            return view('providerPage.providerTabs.newListing', compact('cases',  'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        } else if ($this->getStatusId($status) == '2') {
            return view('providerPage.providerTabs.pendingListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        } else if ($this->getStatusId($status) == '3') {
            return view('providerPage.providerTabs.activeListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        } else if ($this->getStatusId($status) == '4') {
            return view('providerPage.providerTabs.concludeListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        }
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

    // Encounter pop-up consult selected, perform these operation
    public function encounter(Request $request)
    {
        requestTable::where('id', $request->caseId)->update(['status' => 4], ['call_type' => 'consult']);
        return redirect()->route('provider-status', ['status' => 'conclude']);
    }

    public function encounterFormView(Request $request, $id = "null")
    {
        $data = requestTable::where('id', $id)->first();

        // dd($data);
        return view('providerPage.encounterForm', compact('data'));
    }
    public function encounterForm(Request $request)
    {

        $request->validate([
            'first_name' => 'required',
            'email' => 'required|email|unique:medical_report'
        ]);

        // dd($request);

        $medicalReport = new MedicalReport();

        $medicalReport->request_id = $request->request_id;
        $medicalReport->first_name = $request->first_name;
        $medicalReport->last_name = $request->last_name;
        $medicalReport->email = $request->email;
        $medicalReport->location = $request->location;
        $medicalReport->service_date = $request->service_date;
        $medicalReport->date_of_birth = $request->date_of_birth;
        $medicalReport->mobile = $request->mobile;
        $medicalReport->present_illness_history = $request->present_illness_history;
        $medicalReport->medical_history = $request->medical_history;
        $medicalReport->medications = $request->medications;
        $medicalReport->allergies = $request->allergies;
        $medicalReport->temperature = $request->temperature;
        $medicalReport->heart_rate = $request->heart_rate;
        $medicalReport->repository_rate = $request->repository_rate;
        $medicalReport->sis_BP = $request->sis_BP;
        $medicalReport->dia_BP = $request->dia_BP;
        $medicalReport->oxygen = $request->oxygen;
        $medicalReport->pain = $request->pain;
        $medicalReport->heent = $request->heent;
        $medicalReport->cv = $request->cv;
        $medicalReport->chest = $request->chest;
        $medicalReport->abd = $request->abd;
        $medicalReport->extr = $request->extr;
        $medicalReport->skin = $request->skin;
        $medicalReport->neuro = $request->neuro;
        $medicalReport->other = $request->other;
        $medicalReport->diagnosis = $request->diagnosis;
        $medicalReport->treatment_plan = $request->treatment_plan;
        $medicalReport->medication_dispensed = $request->medication_dispensed;
        $medicalReport->procedure = $request->procedure;
        $medicalReport->followUp = $request->followUp;

        $medicalReport->save();
        return redirect()->route('provider-status', ['status' => 'conclude'])->compact('');
    }
}
