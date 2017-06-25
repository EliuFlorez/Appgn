<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class AuthController extends Controller
{
	public function signup(Request $request) 
	{
		$this->validate($request, [
			'name' => 'required',
			'email' => 'required|email|unique:users',
			'password' => 'required'
		]);

		$user = new User([
			'name' => $request->input('name'),
			'email' => $request->input('email'),
			'password' => bcrypt($request->input('password'))
		]);

		$user->save();

		return response()->json(['message' => 'Successfully created user!'], 201);
	}

	public function signin(Request $request) 
	{
		$this->validate($request, [
			'email' => 'required|email',
			'password' => 'required'
		]);
		
		$credentials = $request->only('email', 'password');
		
		try {
			if (!$token = JWTAuth::attempt($credentials)) 
			{
				return response()->json(['error' => 'Invalid Credentials!'], 401);
			}
		} 
		catch (JWTException $e) {
			return response()->json(['error' => 'Could not create token!'. $e->getMessage()], 500);
		}
		
		return response()->json(['token' => $token], 200);
	}

	public function getAuthenticatedUser()
	{
		try {
			if (! $user = JWTAuth::parseToken()->authenticate()) 
			{
				return response()->json(['user_not_found'], 404);
			}
		} 
		catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
			return response()->json(['token_expired'], $e->getStatusCode());
		} 
		catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
			return response()->json(['token_invalid'], $e->getStatusCode());
		} 
		catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
			return response()->json(['token_absent'], $e->getStatusCode());
		}

		// the token is valid and we have found the user via the sub claim
		return response()->json(compact('user'));
	}

	public function updateUser(Request $request, $id)
	{
		$this->validate($request, [
			'email' => 'email',
			'name' => 'required'
		]);

		if (!$user = JWTAuth::parseToken()->authenticate()) 
		{
			return response()->json(['user_not_found'], 404);
		} 
		else {
			if ($user->id == $id) 
			{
				$user->name = $request->input("name");
				$user->save();
			} 
			else {
				return response()->json(['User id does not match token'], 401);
			}
		}

		return response()->json(compact('user'));
	}
}
