<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserService
{
    protected $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Hanya admin yang boleh akses User Module
     */
    public function checkAdmin($user)
    {
        if ($user->role !== 'admin') {
            abort(response()->json([
                'message' => 'Hanya admin yang boleh mengakses.'
            ], 403));
        }
    }

    /** GET /users */
    public function getAll(Request $request)
    {
        $this->checkAdmin($request->user());

        return $this->repo->searchAndPaginate(
            $request->search,
            $request->role
        );
    }

    /** POST /users */
    public function store(Request $request)
    {
        $this->checkAdmin($request->user());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => [
                'required',
                Rule::in(['admin', 'staff', 'customer']),
            ],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        return $this->repo->create($validated);
    }

    /** GET /users/{id} */
    public function show(Request $request, $id)
    {
        $this->checkAdmin($request->user());

        $user = $this->repo->find($id);

        if (!$user) {
            abort(response()->json(['message' => 'User tidak ditemukan'], 404));
        }

        return $user;
    }

    /** PUT /users/{id} */
    public function update(Request $request, $id)
    {
        $this->checkAdmin($request->user());

        $user = $this->repo->find($id);
        if (!$user) {
            abort(response()->json(['message' => 'User tidak ditemukan'], 404));
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|string|email|max:255|unique:users,email,{$id}",
            'password' => 'nullable|string|min:8',
            'role' => [
                'required',
                Rule::in(['admin', 'staff', 'customer']),
            ],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']); // jangan overwrite dengan null
        }

        return $this->repo->update($user, $validated);
    }

    /** DELETE /users/{id} */
    public function delete(Request $request, $id)
    {
        $this->checkAdmin($request->user());

        $user = $this->repo->find($id);
        if (!$user) {
            abort(response()->json(['message' => 'User tidak ditemukan'], 404));
        }

        return $this->repo->delete($user);
    }
}
