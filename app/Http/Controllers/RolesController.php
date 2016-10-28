<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function show($id)
    {
        $role = Role::findOrFail($id);
        $users = User::byRolesName($role->name);
        return view('roles.show', compact('users', 'role'));
    }
}
