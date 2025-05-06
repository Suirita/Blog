<?php

namespace App\Http\Services;

use App\Models\User;
use Spatie\Permission\Models\Role;


class UserService
{
    function getAll()
    {
        $users = User::with('roles', 'articles', 'comments')->orderBy('created_at', 'desc')->get();
        $roles = Role::all();

        return ['users' => $users, 'roles' => $roles];
    }

    function create($data)
    {
        $user = new user();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->save();

        $user->assignRole($data['role']);
    }

    function edit($data, $user)
    {
        $user = User::find($user->id);
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['newPassword']);
        $user->save();

        $user->assignRole($data['role']);
    }

    function delete(User $user)
    {
        $user->delete();
    }
}
