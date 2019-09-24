<?php
/**
 * Created by PhpStorm.
 * User: manchidede
 * Date: 9/23/19
 * Time: 6:44 PM
 */

namespace Chidi\Laravel_Passport_Auth;

use Illuminate\Http\Request;

interface PassportAuthInterface {
	/**
	 * @return array
	 */
	public function LoginValidationArray();

	/**
	 * @return array
	 */
	public function RegValidationArray();

	/**
	 * create and return model
	 *
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public function CreateUser(Request $request);
}