<?php

namespace App\Http\Controllers;

use App\Models\request_Client;
use App\Models\RequestTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\users;

class patientLoginController extends Controller
{
    public function loginScreen(){
        return view("patientSite/patientLogin");
    }

    public function userLogin(Request $request)
    {
       $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

       

        // Assuming $inputPassword is the password the user submitted via a form
        $inputPassword = $request->password;

        
        // And assuming you've retrieved the user's hashed password from the database
        
        $user = users::where('email', $request->email)->first();
        $hashedPassword = $user->password_hash; // or $user->password, depending on your column name
      
        
    

        // Now, you can check if the input password matches the hash
        if (Hash::check($inputPassword, $hashedPassword)) {
         // The passwords match...
        // Log the user in or perform the next steps

        // return redirect()->intended('loginScreen');

        try {
            return view ('patientSite/patientDashboard');
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
        

        }
         else {
            // The passwords don't match...
             // Handle the failed login attempt


             try {
                
             return view('patientSite/patientLogin');
             }
              catch (\Throwable $th) {
                //throw $th;

                dd($th);
             }
        }


}






    public function read(){
        $patientData = RequestTable::all();
        $patientData = RequestTable::paginate(10);
        return view ("patientSite/patientDashboard")->with('patientData', $patientData);

    }
}

