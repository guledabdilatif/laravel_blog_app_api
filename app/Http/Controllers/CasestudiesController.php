<?php

namespace App\Http\Controllers;

use App\Models\Casestudies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CasestudiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Casestudies::latest()->get()->map(function ($cs) {
            foreach (
                [
                    'wireframe_image',
                    'mockup_image',
                    'prototype_image',
                    'final_design_image'
                ] as $field
            ) {
                $cs->$field = $cs->$field ? asset('storage/' . $cs->$field) : null;
            }
            return $cs;
        });
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'role' => 'nullable|string',
            'goal' => 'nullable|string',
            'what_i_learned' => 'nullable|string',
            'tech_stack' => 'nullable|string',
            'wireframe_image' => 'nullable|image',
            'mockup_image' => 'nullable|image',
            'prototype_image' => 'nullable|image',
            'final_design_image' => 'nullable|image',
        ]);
        $data = $request->all();

        if ($request->hasFile('wireframe_image')) {
            $data['wireframe_image'] = $request->file('wireframe_image')->store('case_study_images/wireframes', 'public');
        }

        if ($request->hasFile('mockup_image')) {
            $data['mockup_image'] = $request->file('mockup_image')->store('case_study_images/mockups', 'public');
        }

        if ($request->hasFile('prototype_image')) {
            $data['prototype_image'] = $request->file('prototype_image')->store('case_study_images/prototypes', 'public');
        }

        if ($request->hasFile('final_design_image')) {
            $data['final_design_image'] = $request->file('final_design_image')->store('case_study_images/final_designs', 'public');
        }

        $caseStudy = Casestudies::create($data);

        return response()->json($caseStudy, 201);
    }

    public function show($id)
    {
        $casestudy = \App\Models\Casestudies::findOrFail($id);

        foreach (
            [
                'wireframe_image',
                'mockup_image',
                'prototype_image',
                'final_design_image'
            ] as $field
        ) {
            $casestudy->$field = $casestudy->$field
                ? asset('storage/' . $casestudy->$field)
                : null;
        }

        return response()->json($casestudy);
    }

    public function update(Request $request, $id)
{
    // Validate inputs
    $request->validate([
        'title' => 'sometimes|required|string',
        'role' => 'sometimes|nullable|string',
        'goal' => 'sometimes|nullable|string',
        'what_i_learned' => 'sometimes|nullable|string',
        'tech_stack' => 'sometimes|nullable|string',
        'wireframe_image' => 'sometimes|nullable|image',
        'mockup_image' => 'sometimes|nullable|image',
        'prototype_image' => 'sometimes|nullable|image',
        'final_design_image' => 'sometimes|nullable|image',
    ]);

    $casestudy = Casestudies::findOrFail($id);

    // Capture only fields that exist in the request
    $data = [];

    foreach (['title', 'role', 'goal', 'what_i_learned', 'tech_stack'] as $field) {
        if ($request->exists($field)) {
            $data[$field] = $request->input($field);
        }
    }

    // Handle image fields
    $imageFields = [
        'wireframe_image' => 'case_study_images/wireframes',
        'mockup_image' => 'case_study_images/mockups',
        'prototype_image' => 'case_study_images/prototypes',
        'final_design_image' => 'case_study_images/final_designs',
    ];

    foreach ($imageFields as $field => $folder) {
        if ($request->hasFile($field)) {
            // Delete old image if exists
            if ($casestudy->$field && Storage::disk('public')->exists($casestudy->$field)) {
                Storage::disk('public')->delete($casestudy->$field);
            }

            // Store new image
            $data[$field] = $request->file($field)->store($folder, 'public');
        }
    }

    // Log to help with debugging (optional)
    // \Log::info('Update data for casestudy ID ' . $id, $data);

    // Update if we have data
    if (!empty($data)) {
        $casestudy->update($data);
        $casestudy->refresh();
    }

    // Update asset URLs for response
    foreach (array_keys($imageFields) as $field) {
        $casestudy->$field = $casestudy->$field
            ? asset('storage/' . $casestudy->$field)
            : null;
    }

    return response()->json([
        'message' => 'Case study updated successfully.',
        'data' => $casestudy
    ]);
}






    public function destroy($id)
    {
        $casestudy = Casestudies::findOrFail($id);

        $imageFields = [
            'wireframe_image',
            'mockup_image',
            'prototype_image',
            'final_design_image',
        ];

        foreach ($imageFields as $field) {
            if ($casestudy->$field && Storage::disk('public')->exists($casestudy->$field)) {
                Storage::disk('public')->delete($casestudy->$field);
            }
        }
        $casestudy->delete();

        return response()->json(['message' => 'Case study deleted successfully.'], 200);
    }
}
