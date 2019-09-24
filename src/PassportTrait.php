<?php
/**
 * Created by PhpStorm.
 * User: manchidede
 * Date: 9/24/19
 * Time: 10:09 AM
 */

namespace Chidi\Laravel_Passport_Auth;

use Illuminate\Http\Request;


trait PassportTrait {
	/**
	 * Fields to be included in the response together with api token
	 * @var array
	 */
	protected $responseFields = ['email', 'name'];

	/**
	 * Authentication credentials.
	 * @var array
	 */
	protected $authCredentials = ['email', 'password'];

	/**
	 * scope(s) to be added to api token
	 * e.g. ['isAdmin']
	 * @var array
	 */
	protected $scope = [];

	/**
	 * message to be returned when authentication fails
	 * @var string
	 */
	protected $unauthenticatedMsg = 'Unauthorised credentials';

	/**
	 * message to be returned when user logs out
	 * @var string
	 */
	protected $logoutMsg = 'Logged Out Successfully';

	/**
	 * @var Request
	 */
	public $request;

	/**
	 * This method is called after login credentials have been verified.
	 * This is the right place to check if a user is verified or other security checks.
	 *
	 * return true if everything is fine.
	 * return a message string if something is wrong.
	 *
	 * e.g. if a user is not yet verified, the return would look like this,
	 * return 'Account not verified.'
	 *
	 * @param $user
	 *
	 * @return bool|string
	 */
	protected function shouldAuthenticate($user)
	{
		return true;
	}

	/**
	 * PassportTrait constructor.
	 *
	 * @param Request $request
	 */
	public function __construct(Request $request) {
		$this->request = $request;
	}

	/**
	 * @param $user
	 * @param array $scope
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	private function response($user, $scope = [])
	{
		$objToken = $user->createToken(env('APP_NAME'), $scope);
		$success['token'] =  $objToken->accessToken;
		$success['expires_in_sec'] = $objToken->token->expires_at;

		foreach ($this->responseFields as $field){
			$success[$field] = $user->{$field};
		}

		return response()->json($success, 200);
	}
}