# laravel_passport_authentication
This is a simple authentication package for laravel Applications using passport.

##### Note: 
Before you continue, make sure to setup laravel passport in your application according to [here](https://laravel.com/docs/5.8/passport).

## Installation

Use composer [package](https://packagist.org/packages/chidi/laravel-passport-auth) to install laravel-passport-auth.

```bash
composer require chidi/laravel-passport-auth
```

## Usage

### Step 1. Create a class and extend the PassportAuth class
```php
<?php

namespace App\Http\Controllers\Auth;

use Chidi\Laravel_Passport_Auth\PassportAuth;

class MyClassName extends PassportAuth {

}
```
### Step 2. Define the following three methods
#### a:
```php
/**
 * This method should return the login validation array
 * @return array
 */
public function LoginValidationArray()
{

}
```
#### b.
```php
/**
 * This method should return the registration array
 * @return array
 */
public function RegValidationArray() {

}
```

#### c.
```php
/**
 * After validation, this method is called for model creation.
 * It must return the model instance.
 * @param Request $request
 *
 * @return mixed
 */
public function CreateUser(Request $request) {

}
```
### Step 3. publish these three (3) routes in you routes/api.php

```php
Route::post('login', 'Auth\MyClassName@login');
Route::post('register', 'Auth\MyClassName@register');
Route::post('logout', 'Auth\MyClassName@logout');
```
#### Note: Make sure you replace "MyClassName" with the class you created in step 1.

## Example:

```php
<?php

namespace App\Http\Controllers\Auth;


use Chidi\Laravel_Passport_Auth\PassportAuth;
use Illuminate\Http\Request;
use App\User;

class MyPassportAuth extends PassportAuth {

	 /**
	 * You must implement the login validation array
	 * @return array
	 */
	public function LoginValidationArray()
	{
		return [
			'email'    => 'required|email',
			'password' => 'required'
		];
	}

	 /**
	 * You must implement the registration validation array
	 * @return array
	 */
	public function RegValidationArray() {
		return [
			'name'             => 'required',
			'email'            => 'required|email|unique:users',
			'password'         => 'required',
			'confirm_password' => 'required|same:password',
		];
	}

	 /**
	 * After validation, this method is called for model creation.
	 * it must be implemented
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public function CreateUser(Request $request) {
		return User::create([
			'name'     => $request->name,
			'email'    => $request->email,
			'password' => bcrypt($request->password)
		]);
	}

	 /**
	 * This method is called after login credentials have been verified.
	 * This is the right place to check if a user is verified or other security checks.
	 *
	 * return true if everything is fine.
	 * return a message string if something is wrong.
	 *
	 * e.g. if a user is not yet verified, the return would look like this,
	 * return 'Account not verified.'
	 * @param $user
	 *
	 * @return bool|string
	 */
	protected function shouldAuthenticate($user)
	{
		if($user->status === 1) return true;
		else return 'Account not active. Please contact the admin.';
	}
}
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
