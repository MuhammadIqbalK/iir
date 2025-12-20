<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Itemnc;
use Illuminate\Http\Request;

class ItemncController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = Itemnc::select('*')
                        ->orderBy('item12nc','asc')
                        ->get();

        return response()->json([
            'data' => $result
        ]);
    }

    public function itemncDropdown()
    {
        $result = Itemnc::select('id','item12nc','partname')
                        ->orderBy('item12nc','asc')
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
            'item12nc' => 'required|string|unique:itemncs,item12nc',
            'partname' => 'required|string',
            'quality' => 'nullable|string',
            'quantity' => 'required|integer',
            'status' => 'sometimes|string',
        ]);

        $itemnc = Itemnc::create($validated);

        return response()->json($itemnc, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Itemnc $itemnc)
    {
        return $itemnc;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Itemnc $itemnc)
    {
        $validated = $request->validate([
            'item12nc' => 'sometimes|string|unique:itemncs,item12nc,' . $itemnc->id,
            'partname' => 'sometimes|string',
            'quality' => 'nullable|string',
            'quantity' => 'sometimes|integer',
            'status' => 'sometimes|string',
        ]);

        $itemnc->update($validated);

        return $itemnc;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Itemnc $itemnc)
    {
        $itemnc->delete();

        return response()->noContent();
    }
}
