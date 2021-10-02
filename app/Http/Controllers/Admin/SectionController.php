<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SectionRequest;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $sections = Section::all();
            return view('pages.settings.sections.index',compact('sections'));
        }catch (\Exception $ex){
            return view('404');
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SectionRequest $request)
    {
        try {
            DB::beginTransaction();
            $section = $request->only(['section_name','description']);
            $state = Section::create($section);
            if($state){
                DB::commit();
                return redirect()->route('sections.index')->with('Add',__(('messages.Section has been added successfully')));
            }

            return redirect()->route('sections.index')->with('Error',__(('messages.Section has not been added')));
        }catch (\Exception $ex){
            DB::rollBack();
            return redirect()->route('sections.index')->with('Error',__(('messages.An error occurred, please try again later')));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit(SectionRequest $request,$id)
    {

    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(SectionRequest $request)
    {
        try {
            $section = Section::find($request->id);
            if (isset($section) && $section->count() > 0) {
                $section->update($request->only(['section_name','description']));
                return redirect()->route('sections.index')->with('Edit',__(('messages.Section has been updated successfully')));
                return redirect()->route('sections.index')->with('Error',__(('messages.Section has not been updated')));
            }else{
                return redirect()->route('sections.index')->with('Error',__(('messages.This section not found')));
            }

        }catch (\Exception $ex){
            return redirect()->route('sections.index')->with('Error',__(('messages.An error occurred, please try again later')));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(SectionRequest $request)
    {
        try {
            $section = Section::find($request->id);

            if(!$section)
                return redirect()->route('sections.index')->with('Error',__(('messages.This section not found')));

            $state = $section->delete();
            if(!$state)
                return redirect()->route('sections.index')->with('Error',__(('messages.Section has not been deleted')));
            return redirect()->route('sections.index')->with('Delete',__(('messages.Section has been deleted successfully')));

            }catch (\Exception $ex){
                return redirect()->route('sections.index')->with('Error',__(('messages.An error occurred, please try again later')));
            }
    }

    }

