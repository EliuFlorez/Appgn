<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Session;

class PermissionController extends Controller
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
        $data = Permission::all();

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
            'name' => 'required|max:40',
        ]);

        $name = $request['name'];
        $permission = new Permission();
        $permission->name = $name;

        $roles = $request['roles'];
        
        $permission->save();

        if (!empty($request['roles'])) 
		{
            foreach ($roles as $role) 
			{
                $r = Role::where('id', '=', $role)->firstOrFail(); //Match input role to db record
                $permission = Permission::where('name', '=', $name)->first();   
                $r->givePermissionTo($permission);
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
        $data = Permission::findOrFail($id);
		
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
        $permission = Permission::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|max:40',
        ]);
        
        $input = $request->all();
		
        $permission->fill($input)->save();

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
        $permission = Permission::findOrFail($id);
        
        if ($permission->name == "Administer roles & permissions") 
		{
            return response()->json(['message' => 'Cannot delete this Permission!'], 200);
        }
        
        $permission->delete();

		return response()->json(['message' => 'Permission deleted!'], 200);
    }
}
