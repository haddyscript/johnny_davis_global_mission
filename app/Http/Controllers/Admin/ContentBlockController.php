<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentBlock;
use App\Models\Section;
use Illuminate\Http\Request;

class ContentBlockController extends Controller
{
    public function index(Request $request)
    {
        $query = ContentBlock::with('section.page')->orderBy('sort_order');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('key', 'like', '%'.$request->search.'%')
                  ->orWhere('content', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $contentBlocks = $query->paginate(15)->withQueryString();
        $sections      = Section::with('page')->orderBy('sort_order')->get();

        $typeCounts = ContentBlock::selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type');

        $stats = [
            'total' => ContentBlock::count(),
            'text'  => $typeCounts->get('text', 0),
            'image' => $typeCounts->get('image', 0),
            'link'  => $typeCounts->get('link', 0),
            'list'  => $typeCounts->get('list', 0),
        ];

        return view('admin.content-blocks.index', compact('contentBlocks', 'sections', 'stats'));
    }

    public function create()
    {
        $sections = Section::with('page')->orderBy('sort_order')->get();

        return view('admin.content-blocks.create', compact('sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'key'        => 'required',
            'type'       => 'required',
            'content'    => 'nullable',
            'url'        => 'nullable|url',
            'sort_order' => 'integer',
        ]);

        ContentBlock::create($request->all());

        return redirect()->route('admin.content-blocks.index')->with('success', 'Content block created successfully.');
    }

    public function show(ContentBlock $contentBlock)
    {
        return view('admin.content-blocks.show', compact('contentBlock'));
    }

    public function edit(ContentBlock $contentBlock)
    {
        $sections = Section::with('page')->orderBy('sort_order')->get();

        return view('admin.content-blocks.edit', compact('contentBlock', 'sections'));
    }

    public function update(Request $request, ContentBlock $contentBlock)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'key'        => 'required',
            'type'       => 'required',
            'content'    => 'nullable',
            'url'        => 'nullable|url',
            'sort_order' => 'integer',
        ]);

        $contentBlock->update($request->all());

        return redirect()->route('admin.content-blocks.index')->with('success', 'Content block updated successfully.');
    }

    public function destroy(ContentBlock $contentBlock)
    {
        $contentBlock->delete();

        return redirect()->route('admin.content-blocks.index')->with('success', 'Content block deleted successfully.');
    }
}
