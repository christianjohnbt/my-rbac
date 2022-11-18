<?php

namespace Laratrust\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class TeamsController
{
    protected $teamModel;

    public function __construct()
    {
        $this->teamModel = Config::get('laratrust.models.team');
    }

    public function index()
    {
        return View::make('laratrust::panel.teams.index', [
            'teams' => $this->teamModel::simplePaginate(10),
        ]);
    }

    public function create()
    {
        return View::make('laratrust::panel.edit', [
            'model' => null,
            'type' => 'team',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:teams,name',
            'display_name' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $this->teamModel::create($data);

        Session::flash('laratrust-success', 'Team created successfully');
        return redirect(route('laratrust.teams.index'));
    }
    
    public function edit($id)
    {
        $permission = $this->permissionModel::findOrFail($id);

        return View::make('laratrust::panel.edit', [
            'model' => $permission,
            'type' => 'permission',
        ]);
    }

    public function update(Request $request, $id)
    {
        $permission = $this->permissionModel::findOrFail($id);

        $data = $request->validate([
            'display_name' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $permission->update($data);

        Session::flash('laratrust-success', 'Permission updated successfully');
        return redirect(route('laratrust.permissions.index'));
    }
}
