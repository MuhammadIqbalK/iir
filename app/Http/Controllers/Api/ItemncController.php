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
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        $query = Itemnc::query();

        if ($request->filled('item12nc')) {
            $query->where('item12nc',$request->item12nc);
        }

        
        if ($request->has('partname') && $request->partname != '') {
            $query->where('partname', 'ILIKE', '%' . $request->partname . '%');
        }
        

        $total = $query->count();
        $result = $query->orderBy('item12nc', 'asc')
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
            'type' => 'nullable|string',
            'quantity' => 'required|integer',
            'unit' => 'nullable|string',
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
            'item12nc' => 'required|string|unique:itemncs,item12nc,' . $itemnc->id,
            'partname' => 'required|string',
            'type' => 'nullable|string',
            'quantity' => 'required|integer',
            'unit' => 'nullable|string',
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
