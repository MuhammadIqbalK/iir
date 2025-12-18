<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IncomingPartReport;
use Illuminate\Http\Request;

class IncomingPartReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return IncomingPartReport::latest()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'iirdate' => 'required|date',
            'itemnc' => 'required|string',
            'partname' => 'required|string',
            'nodoc' => 'required|string',
            'quantity' => 'required|integer',
            'samplesize' => 'required|integer',
            'gilevel' => 'required|integer',
            'examiner' => 'required|string',
            'start' => 'required',
            'end' => 'required',
            'duration' => 'required|string',
            'supplier' => 'required|string',
            'option' => 'required|string',
        ]);

        $report = IncomingPartReport::create($validated);

        return response()->json($report, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(IncomingPartReport $incomingPartReport)
    {
        return $incomingPartReport;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IncomingPartReport $incomingPartReport)
    {
        $validated = $request->validate([
            'iirdate' => 'sometimes|date',
            'itemnc' => 'sometimes|string',
            'partname' => 'sometimes|string',
            'nodoc' => 'sometimes|string',
            'quantity' => 'sometimes|integer',
            'samplesize' => 'sometimes|integer',
            'gilevel' => 'sometimes|integer',
            'examiner' => 'sometimes|string',
            'start' => 'sometimes',
            'end' => 'sometimes',
            'duration' => 'sometimes|string',
            'supplier' => 'sometimes|string',
            'option' => 'sometimes|string',
        ]);

        $incomingPartReport->update($validated);

        return $incomingPartReport;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IncomingPartReport $incomingPartReport)
    {
        $incomingPartReport->delete();

        return response()->noContent();
    }
}
