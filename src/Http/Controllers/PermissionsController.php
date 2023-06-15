<?php

namespace Laratrust\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class PermissionsController
{
    protected $permissionModel;
    protected $modelClasses;

    public function __construct()
    {
        $path = app_path('Models') . '/*.php';
        $this->modelClasses = collect(glob($path))->map(fn($file) => basename($file, '.php').'s')->filter(function($v, $k){ return !in_array($v, ['Roles', 'Permissions']); })->toArray();
        $this->permissionModel = Config::get('laratrust.models.permission');
    }

    public function index()
    {
        return View::make('laratrust::panel.permissions.index', [
            'permissions' => $this->permissionModel::simplePaginate(10),
        ]);
    }

    public function create()
    {
        return View::make('laratrust::panel.edit', [
            'model' => null,
            'type' => 'permission',
            'modelClasses' => $this->modelClasses
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
            'display_name' => 'required|string',
            'module_name' => 'required|string',
            'permission_to' => 'required|string',
            'description' => 'nullable|string',
        ]);
        
        $this->permissionModel::create($request->except('permission_to'));
        Session::flash('laratrust-success', 'Permission created successfully');
        return redirect(route('laratrust.permissions.index'));
    }

    public function edit($id)
    {
        $permission = $this->permissionModel::findOrFail($id);
        return View::make('laratrust::panel.edit', [
            'model' => $permission,
            'type' => 'permission',
            'modelClasses' => $this->modelClasses
        ]);
    }

    public function update(Request $request, $id)
    {
        $permission = $this->permissionModel::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|unique:permissions,name',
            'display_name' => 'nullable|string',
            'module_name' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $permission->update($data);

        Session::flash('laratrust-success', 'Permission updated successfully');
        return redirect(route('laratrust.permissions.index'));
    }

    public function destroy($id)
    {
        $usersAssignedToRole = DB::table(Config::get('laratrust.tables.permission_user'))
            ->where(Config::get('laratrust.foreign_keys.permission'), $id)
            ->count();
        $this->permissionModel::findOrFail($id);

        if ($usersAssignedToRole > 0)
        {
            Session::flash('laratrust-warning', 'Permission is attached to one or more users. It can not be deleted.');
        }
        else
        {
            Session::flash('laratrust-success', 'Permission deleted successfully.');
            $this->permissionModel::destroy($id);
        }
        return redirect(route('laratrust.permissions.index'));
    }
}
