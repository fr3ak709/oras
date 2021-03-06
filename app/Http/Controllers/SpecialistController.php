<?php
namespace AIVIKS\Http\Controllers;
use AIVIKS\Http\Controllers\Controller;
use AIVIKS\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SpecialistController extends Controller {
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('amAdmin');
    }

    public function index(){
        $users = User::all();
        return view('specialists/EditUsers', ['users'=>$users]);
    }

    public function destroy($id) {
        $specialist = User::findOrFail($id);
        if($specialist->role=='Administrator') {
            return redirect()->back()->with("error","Bandodte išrinti administratorių");
        }
        $email = $specialist->email;
        $specialist->delete();
        return redirect()->back()->with("success", $email." vartotojas paanaikintas");
    }

    public function showChangePasswordForm(){
        return view('auth.changepassword');
    }

    public function changePassword(Request $request){
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }
 
        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }
 
        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);
 
        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();
 
        return redirect()->back()->with("success","Password changed successfully !");
    }
}