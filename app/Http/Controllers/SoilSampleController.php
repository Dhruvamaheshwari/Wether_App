<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SoilSample;

class SoilSampleController extends Controller
{
    public function index()
    {
        $samples = SoilSample::orderBy('created_at', 'desc')->get();
        return view('SoilAna', compact('samples'));
    }

    public function show($sample_id)
    {
        $sample = SoilSample::where('sample_id', $sample_id)->firstOrFail();
        return response()->json($sample);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'sample_id' => 'required|string',
            'email' => 'nullable|email',
            'location' => 'nullable|string',
            'sample_date' => 'nullable|date',
            'soil_type' => 'nullable|string',
            'crop_type' => 'nullable|string',
            'ph_value' => 'nullable|numeric',
            'nitrogen' => 'nullable|integer',
            'phosphorus' => 'nullable|integer',
            'potassium' => 'nullable|integer',
            'calcium' => 'nullable|integer',
            'magnesium' => 'nullable|integer',
            'sulfur' => 'nullable|integer',
            'moisture_value' => 'nullable|integer',
        ]);

        $soilSample = SoilSample::updateOrCreate(
            ['sample_id' => $validatedData['sample_id']],
            $validatedData
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Soil sample data saved successfully.',
            'data' => $soilSample
        ]);
    }
}
