<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * Map a row from the import file to a User model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Check if a user with the same email already exists.
        if (User::where('email', $row['email'])->exists()) {
            return null;
        }

        // Create the new user.
        $user = User::create([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => bcrypt($row['hashed_password']),
            'created_at'  => $row['created_at'],
            'updated_at'  => $row['updated_at'],
        ]);

        $user->assignRole($row['role']);

        return $user;
    }
}
