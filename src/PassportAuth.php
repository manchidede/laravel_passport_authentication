<?php
/**
 * Created by PhpStorm.
 * User: manchidede
 * Date: 9/23/19
 * Time: 4:55 PM
 */

namespace Chidi\Laravel_Passport_Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;


abstract class PassportAuth extends Controller implements PassportAuthInterface {

	use PassportTrait;

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */

	public function login(Request $request)
	{
		$validator = Validator::make($request->all(), $this->LoginValidationArray());

		if ($validator->fails()) {
			return response()->json( [ 'error' => $validator->errors() ], 422 );
		}

		$credentials = [];

		foreach ($this->authCredentials as $credential){
			$credentials[$credential] = $request->{$credential};
		}

		if(Auth::attempt($credentials)){
			$shouldAuth = $this->shouldAuthenticate(Auth::user());
			if($shouldAuth !== true){
				$msg = $shouldAuth;
				Auth::logout();
			}
			else return $this->response(Auth::user(), $this->scope);

		}else $msg = $this->unauthenticatedMsg;

		return response()->json(['message' => $msg], 401);
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */

	public function register(Request $request)
	{
		$validator = Validator::make($request->all(), $this->RegValidationArray());

		if ($validator->fails()) {
			return response()->json(['error'=>$validator->errors()], 422);
		}

		$user = $this->CreateUser($request);

		return $this->response($user);
	}

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function logout()
	{
		Auth::user()->token()->revoke();
		return response()->json(['Message' => $this->logoutMsg]);
	}
}