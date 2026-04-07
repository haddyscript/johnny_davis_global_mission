<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::with('page')->orderBy('sort_order')->get();

        return view('admin.sections.index', compact('sections'));
    }

    public function create()
    {
        $pages = Page::all();

        return view('admin.sections.create', compact('pages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'page_id' => 'required|exists:pages,id',
            'slug' => 'required',
            'name' => 'required',
            'type' => 'nullable',
            'sort_order' => 'integer',
        ]);

        Section::create($request->all());

        return redirect()->route('admin.sections.index')->with('success', 'Section created successfully.');
    }

    public function show(Section $section)
    {
        return view('admin.sections.show', compact('section'));
    }

    public function edit(Section $section)
    {
        $pages = Page::all();

        return view('admin.sections.edit', compact('section', 'pages'));
    }

    public function update(Request $request, Section $section)
    {
        $request->validate([
            'page_id' => 'required|exists:pages,id',
            'slug' => 'required',
            'name' => 'required',
            'type' => 'nullable',
            'sort_order' => 'integer',
        ]);

        $section->update($request->all());

        return redirect()->route('admin.sections.index')->with('success', 'Section updated successfully.');
    }

    public function destroy(Section $section)
    {
        $section->delete();

        return redirect()->route('admin.sections.index')->with('success', 'Section deleted successfully.');
    }
}
