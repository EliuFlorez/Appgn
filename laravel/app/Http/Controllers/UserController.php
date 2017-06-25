<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Session;

class UserController extends Controller
{
    public function __construct() 
    {
		$this->middleware(['auth.jwt', 'isAdmin'], ['except' => ['index']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::all();

		return response()->json(['data' => $data], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $options = Role::get();
        
		return response()->json(['options' => $options], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:120',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::create($request->only('email', 'name', 'password'));

        if (isset($request['roles'])) 
		{
			$roles = $request['roles'];
            foreach ($roles as $role) 
			{
				$userRole = Role::where('id', '=', $role)->firstOrFail();
				$user->assignRole($userRole);
            }
        }        

		return response()->json(['message' => 'Successfully created!'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		//
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = User::findOrFail($id);
		
        $options = Role::get();
        
		return response()->json(['data' => $data, 'options' => $options], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
		
        $this->validate($request, [
            'name' => 'required|max:120',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'required|min:6|confirmed'
        ]);

        $input = $request->only(['name', 'email', 'password']);
        $roles = $request['roles'];
        
		$user->fill($input)->save();

        if (isset($roles)) 
		{
            $user->roles()->sync($roles);
        }
        else {
            $user->roles()->detach();
        }
		
		return response()->json(['message' => 'Successfully edited.'], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
		
        $user->delete();
        
		return response()->json(['message' => 'Successfully deleted.'], 200);
    }
}
