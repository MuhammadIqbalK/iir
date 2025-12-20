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
    public function index()
    {
        $result = Examiner::select('*')
                        ->orderBy('name','asc')
                        ->get();

        return response()->json([
           'data' => $result,
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
            'employee_id' => 'required|string|unique:examiners,employee_id',
            'email' => 'nullable|email',
            'status' => 'sometimes|string',
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
            'employee_id' => 'sometimes|string|unique:examiners,employee_id,' . $examiner->id,
            'email' => 'nullable|email',
            'status' => 'sometimes|string',
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
