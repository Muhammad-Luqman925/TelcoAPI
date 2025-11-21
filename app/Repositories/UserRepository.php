<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function searchAndPaginate($search, $role, $perPage = 10)
    {
        $query = User::query();

        if ($search) {
            $query->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
        }

        if ($role) {
            $query->where('role', $role);
        }

        return $query->paginate($perPage);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function find($id)
    {
        return User::find($id);
    }

    public function update(User $user, array $data)
    {
        $user->update($data);
        return $user;
    }

    public function delete(User $user)
    {
        return $user->delete();
    }
}
