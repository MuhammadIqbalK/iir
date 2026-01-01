<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Examiner;
use Illuminate\Http\Request;

class ExaminerController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        $query = Examiner::query();

        if ($request->has('name') && $request->name != '') {
            $query->where('name', 'ILIKE', '%' . $request->name . '%');
        }

        $total = $query->count();
        $result = $query->orderBy('name', 'asc')
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

    public function examinerDropdown()
    {
        $result = Examiner::select('id','name')
                        ->orderBy('name','asc')
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
            'name' => 'required|string',
        ]);

        $examiner = Examiner::create($validated);

        return response()->json($examiner, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Examiner $examiner)
    {
        return $examiner;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Examiner $examiner)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
        ]);

        $examiner->update($validated);

        return $examiner;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Examiner $examiner)
    {
        $examiner->delete();

        return response()->noContent();
    }
}
