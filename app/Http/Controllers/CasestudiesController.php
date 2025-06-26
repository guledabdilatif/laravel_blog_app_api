<?php

namespace App\Http\Controllers;

use App\Models\Casestudies;
use Illuminate\Http\Request;

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




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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

    /**
     * Display the specified resource.
     */
  public function show($id)
{
    $casestudy = \App\Models\Casestudies::findOrFail($id);

    foreach ([
        'wireframe_image',
        'mockup_image',
        'prototype_image',
        'final_design_image'
    ] as $field) {
        $casestudy->$field = $casestudy->$field
            ? asset('storage/' . $casestudy->$field)
            : null;
    }

    return response()->json($casestudy);
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Casestudies $casestudies)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Casestudies $casestudies)
    {
        //
    }
}
