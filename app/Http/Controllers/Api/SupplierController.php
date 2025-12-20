<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        $query = Supplier::query();

        if ($request->has('supplier_name') && $request->supplier_name != '') {
            $query->where('supplier_name', 'ILIKE', '%' . $request->supplier_name . '%');
        }

        if ($request->has('supplier_code') && $request->supplier_code != '') {
            $query->where('supplier_code', 'ILIKE', '%' . $request->supplier_code . '%');
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $total = $query->count();
        $result = $query->orderBy('supplier_name', 'asc')
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        return response()->json([
            'data' => $result,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage
        ]);
    }

    public function supplierDropdown()
    {
        $result = Supplier::select('id', 'supplier_code', 'supplier_name')
            ->where('status', 'Active')
            ->orderBy('supplier_name', 'asc')
            ->get();

        return response()->json([
            'data' => $result
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string',
            'supplier_code' => 'required|string|unique:suppliers,supplier_code',
            'contact_person' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'status' => 'required|string',
        ]);

        $supplier = Supplier::create($validated);

        return response()->json($supplier, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return $supplier;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string',
            'supplier_code' => 'required|string|unique:suppliers,supplier_code,' . $supplier->id,
            'contact_person' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'status' => 'required|string',
        ]);

        $supplier->update($validated);

        return $supplier;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return response()->noContent();
    }
}
