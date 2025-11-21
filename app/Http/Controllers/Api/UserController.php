<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return response()->json(
            $this->service->getAll($request)
        );
    }

    public function store(Request $request)
    {
        return response()->json(
            $this->service->store($request),
            201
        );
    }

    public function show(Request $request, $id)
    {
        return response()->json(
            $this->service->show($request, $id)
        );
    }

    public function update(Request $request, $id)
    {
        return response()->json(
            $this->service->update($request, $id)
        );
    }

    public function destroy(Request $request, $id)
    {
        $this->service->delete($request, $id);

        return response()->json(['message' => 'User berhasil dihapus'], 200);
    }
}
