<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client;

class ApiController extends Controller
{
    public function regstion(Request $request)
    {
    	$request->validate([
    		'name'     => 'required',
    		'email'    => 'required',
    		'password' => 'required',
    	]);

    	$user = User::FirstOrNew(['email'=>$request->email]);
    	$user->name     = $request->name;
    	$user->email    = $request->email;
    	$user->password = Hash::make($request->password);
    	$user->save();

    	$http = new Client;
		$response = $http->post(url('oauth/token'), [
		    'form_params' => [
		        'grant_type'    => 'password',
		        'client_id'     => '2',
		        'client_secret' => 'ukPLoI5VidLOeFtLEJrBhndWQZbNuc3VmPfeIE28',
		        'username'      => $request->email,
		        'password'      => $request->password,
		        'scope'         => '',
		    ],
		]);

		return json_decode((string) $response->getBody(), true);
    }

    public function login(Request $request)
    {
    	$request->validate([
    		'email'    => 'required',
    		'password' => 'required',
    	]);
    	$user = User::where('email',$request->email)->first();
    	if (!$user) {
    		return response(['data'=>'User not fount']);
    	}
    	if (Hash::check($request->password, $user->password)) {
    		$http = new Client;

			$response = $http->post(url('oauth/token'), [
			    'form_params' => [
			        'grant_type'    => 'password',
			        'client_id'     => '2',
			        'client_secret' => 'ukPLoI5VidLOeFtLEJrBhndWQZbNuc3VmPfeIE28',
			        'username'      => $request->email,
			        'password'      => $request->password,
			        'scope'         => '',
			    ],
			]);
			return json_decode((string) $response->getBody(), true);
    	}
    }




}
