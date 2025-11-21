<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $service;

    public function __construct(CustomerService $service)
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

        return response()->json(['message' => 'Customer berhasil dihapus'], 200);
    }
}
