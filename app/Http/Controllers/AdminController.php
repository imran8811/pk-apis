<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;
use App\Models\Product;
use App\Models\ProductImages;

class AdminController extends Controller {

    public function loginAdmin(Request $request){
        $formFields = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $user = AdminUser::where('email', $request->email)->first();
        if(!$user || !Hash::check($formFields['password'], $user->password)){
            return response([
                'message' => 'Invalid Credentials'
            ], 401);
        }
        $token = $user->createToken('myapptoken')->plainTextToken;
        $response = [
            'type' => 'success',
            'user' => $user,
            'token' => $token
        ];
        return response($response);
    }

    public function createNewAdminUser(Request $request){
      $formFields = $request->validate([
          'name' => 'required',
          'email' => 'required',
          'password' => 'required',
      ]);

      $profile = AdminUser::create([
          'name' => $formFields['name'],
          'email' => $formFields['email'],
          'password' => bcrypt($formFields['password']),
      ]);

      $response = [
          'type' => 'success'
      ];

      return response ($response);
    }

    public function adminLogout(Request $request){
      $request->user()->tokens()->delete();
      return response ([
        'type' => 'success',
        'message' => 'Logged out'
      ], 200);
    }
}
