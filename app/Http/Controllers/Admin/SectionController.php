<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Section::with('page')->orderBy('sort_order');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('slug', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('page_id')) {
            $query->where('page_id', $request->page_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $sections = $query->paginate(15)->withQueryString();
        $pages    = Page::orderBy('sort_order')->get();
        $types    = Section::distinct()->whereNotNull('type')->orderBy('type')->pluck('type');

        $stats = [
            'total'       => Section::count(),
            'pages_count' => Section::distinct('page_id')->count('page_id'),
            'types_count' => Section::distinct('type')->whereNotNull('type')->count('type'),
        ];

        return view('admin.sections.index', compact('sections', 'pages', 'types', 'stats'));
    }

    public function create()
    {
        $pages = Page::orderBy('sort_order')->get();

        return view('admin.sections.create', compact('pages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'page_id'    => 'required|exists:pages,id',
            'slug'       => 'required',
            'name'       => 'required',
            'type'       => 'nullable',
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
        $pages = Page::orderBy('sort_order')->get();

        return view('admin.sections.edit', compact('section', 'pages'));
    }

    public function update(Request $request, Section $section)
    {
        $request->validate([
            'page_id'    => 'required|exists:pages,id',
            'slug'       => 'required',
            'name'       => 'required',
            'type'       => 'nullable',
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
