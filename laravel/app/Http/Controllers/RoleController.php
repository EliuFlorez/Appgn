<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Session;

class RoleController extends Controller
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
        $data = Role::all();

		return response()->json(['data' => $data], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $options = Permission::all();

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
            'name' => 'required|unique:roles|max:10',
            'permissions' => 'required',
		]);

        $name = $request['name'];
        $role = new Role();
        $role->name = $name;

        $permissions = $request['permissions'];

        $role->save();

        foreach ($permissions as $permission) 
		{
            $rolePermission = Permission::where('id', '=', $permission)->firstOrFail();
            $role = Role::where('name', '=', $name)->first();
            $role->givePermissionTo($rolePermission);
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
        $data = Role::findOrFail($id);
		
        $options = Permission::all();

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
        $role = Role::findOrFail($id);
		
        $this->validate($request, [
            'name' => 'required|max:10|unique:roles,name,'.$id,
            'permissions' => 'required',
        ]);

        $input = $request->except(['permissions']);
        $permissions = $request['permissions'];
        
		$role->fill($input)->save();
        
		$permissionAll = Permission::all();

        foreach ($permissionAll as $p) 
		{
            $role->revokePermissionTo($p);
        }

        foreach ($permissions as $permission) 
		{
            $rolePermission = Permission::where('id', '=', $permission)->firstOrFail(); //Get corresponding form permission in db
            $role->givePermissionTo($rolePermission);
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
        $role = Role::findOrFail($id);
        
		$role->delete();
		
		return response()->json(['message' => 'Successfully deleted.'], 200);
    }
}
