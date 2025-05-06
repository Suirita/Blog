<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * Retrieve a collection of users with the necessary relationships.
     */
    public function collection()
    {
        return User::with('roles')->get();
    }

    /**
     * Map each user to an array with the desired fields.
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->password,
            $user->roles->first()->name,
            $user->created_at,
            $user->updated_at,
        ];
    }

    /**
     * Define the headings for your export.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Hashed Password',
            'Role',
            'Created At',
            'Updated At',
        ];
    }
}
