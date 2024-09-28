<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            return $this->getRoles();
        }
        return view('users.roles.role_view');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $permissions = Permission::get();
        return view('users.roles.role_create')->with(['Permissions'=> $permissions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        //Validate name
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required'
        ]);
        $role = Role::create(['name' => strtolower(trim($request->name))]);
        $role->syncPermissions($request->permission);
        if($role)
        {
            // toast('New Role Added Successfully.','success');
            return view('users.roles.role_view');
        }
        // toast('Error on Saving role','error');
        return back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Role $role)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Role $role)
    {
        // return view('users.permissions.permission_edit')->with(['permission'=>$permission]);
        if($request->ajax())
        {
            return $this->getRolesPermissions($role);
        }
        return view('users.roles.role_edit')->with(['role' => $role]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
        public function update(Role $role, Request $request)
        {
            $this->validate($request, [
                'name' => 'required',
                'permission' => 'required',
            ]);
            $role->update($request->only('name'));
            $role->syncPermissions($request->permission);
            if($role)
            {
                // toast('Role Updated Successfully.','success');
                return view('users.roles.role_edit')->with(['role' => $role]);
                // return 'hello';
            }
            // toast('Error on Updating role','error');
            return back()->withInput();
        }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Role $role)
    {
        if($request->ajax() && $role->delete())
        {
            return response(["message" => "Role Deleted Successfully"], 200);
        }
        return response(["message" => "Data Delete Error! Please Try again"], 201);
    }

    private function getRoles()
    {
        $data = Role::withCount(['users', 'permissions'])->get();
        return DataTables::of($data)
                ->addColumn('name', function($row){
                    return ucfirst($row->name);
                })
                ->addColumn('users_count', function($row){
                    return $row->users_count;
                })
                ->addColumn('permissions_count', function($row){
                    return $row->permissions_count;
                })
                ->addColumn('action', function($row){
                    $action = "";
                    $action.="<a class='btn btn-xs btn-warning action-btn mb-2' id='btnEdit' href='".route('users.roles.edit', $row->id)."'><i class='fas fa-edit mt-2'></i></a>";
                    $action.=" <a class='btn btn-xs btn-danger action-btn' id='btnDel' data-id='".$row->id."'><i class='fas fa-trash mt-2'></i></a>";
                    return $action;
                })
                ->make('true');
    }

    private function getRolesPermissions($role)
    {
        $permissions = $role->permissions;
        return DataTables::of($permissions)->make('true');
    }
}
