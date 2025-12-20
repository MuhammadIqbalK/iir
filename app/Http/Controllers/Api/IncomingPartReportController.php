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
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search = [
            'itemnc' => $request->get('itemnc', ''),
            'supplier' => $request->get('supplier', ''),
            'option' => $request->get('option', ''),
            'startdate' => $request->get('startdate', ''),
            'enddate' => $request->get('enddate', ''),
        ];


        $result = IncomingPartReport::getAll($page, $perPage, $search);

        return response()->json([
            'data' => $result['data'],
            'total' => $result['total'],
            'page' => $page,
            'per_page' => $perPage
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'iirdate' => 'required|date',
            'itemnc' => 'required|string',
            'nodoc' => 'required|string',
            'quantity' => 'required|integer',
            'samplesize' => 'required|integer',
            'gilevel' => 'required|integer',
            'examiner_id' => 'required|integer',
            'start' => 'required',
            'end' => 'required',
            'duration' => 'required|string',
            'supplier_code' => 'required|string',
            'option' => 'required|string',
        ]);

        $report = IncomingPartReport::create($validated);

        return response()->json($report, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $result = IncomingPartReport::getById($id);

        return response()->json([
            'data' => $result
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IncomingPartReport $incomingPartReport)
    {
        $validated = $request->validate([
            'iirdate' => 'required|date',
            'itemnc' => 'required|string',
            'nodoc' => 'required|string',
            'quantity' => 'required|integer',
            'samplesize' => 'required|integer',
            'gilevel' => 'required|integer',
            'examiner_id' => 'required|integer',
            'start' => 'required',
            'end' => 'required',
            'duration' => 'required|string',
            'supplier_code' => 'required|string',
            'option' => 'required|string',
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

        return response()->json('iir is deleted succesfully', 200);
    }
}
