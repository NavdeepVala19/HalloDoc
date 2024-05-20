<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFamilyRequest;
use App\Http\Requests\CreatePatientRequest;
use App\Services\FamilyRequestSubmitService;

// this controller is responsible for creating/storing the family request

class FamilyRequestController extends Controller
{
 /**
  * display family/friend request page
  * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
  */
    public function familyRequests()
    {
        return view('patientSite/familyRequest');
    }

    /**
     * it stores request in request_client and request table and if user(patient) is new it stores details in all_user,users, make role_id 3 in user_roles table
     * and send email to create account using same email
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */

    public function create(CreatePatientRequest $request , CreateFamilyRequest $familyRequestValidation, FamilyRequestSubmitService $familyRequestSubmitService)
    {

        $familyRequest = $familyRequestSubmitService->storeRequest($request);
        $redirectMsg = $familyRequest ? 'Request is Submitted' : 'Email for Create Account is Sent and Request is Submitted';

        return redirect()->route('submit.request')->with('message', $redirectMsg);

        // $patientData = $request->all();
        // $patientRules = $request->rules();

        // $familyData = $familyRequestValidation->all();
        // $familyRules = $familyRequestValidation->rules();

        // // Create validator instances for both requests
        // $patientValidator = Validator::make($patientData, $patientRules);

        // $familyValidator = Validator::make($familyData, $familyRules);

        // // Check if any validation fails
        // if ($patientValidator->fails() || $familyValidator->fails()) {
        //     // Merge errors from both validators
        //     $errors = $patientValidator->errors()->merge($familyValidator->errors());

        //     // Handle validation failure, return error response or perform desired action
        //     return response()->json(['errors' => $errors], 422);
        // }

        // // Validate both requests simultaneously and return errors if any
        // $data = array_merge($request->all(), $familyRequestValidation->all());
    
        // // Define the rules for both requests
        // $rules = array_merge($request->rules(), $familyRequestValidation->rules());

        // // Validate the merged data with the merged rules
        // $validator = Validator::make($data, $rules);

        // if ($validator->fails()) {
        //     dd($validator->errors());
        //     return back()->withErrors($validator->errors());
        // }



    }
}
