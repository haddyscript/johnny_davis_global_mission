@extends('layouts.admin')

@section('page-title', 'Content Blocks')

@section('content')
<div class="admin-card">
    <div class="admin-toolbar">
        <div>
            <h2>Content Blocks</h2>
            <p class="text-muted">Manage text, links, images, and structured content blocks for each section.</p>
        </div>
        <div class="admin-actions">
            <a href="{{ route('admin.content-blocks.create') }}" class="admin-btn">+ New Block</a>
        </div>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Section</th>
                <th>Key</th>
                <th>Type</th>
                <th>Content</th>
                <th>URL</th>
                <th>Sort Order</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contentBlocks as $block)
                <tr>
                    <td>{{ $block->section->name }} <span class="text-muted">({{ $block->section->page->name }})</span></td>
                    <td>{{ $block->key }}</td>
                    <td>{{ $block->type }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($block->content, 60) }}</td>
                    <td>{{ $block->url }}</td>
                    <td>{{ $block->sort_order }}</td>
                    <td class="admin-table-actions">
                        <a href="{{ route('admin.content-blocks.edit', $block) }}" class="admin-btn-secondary">Edit</a>
                        <form method="POST" action="{{ route('admin.content-blocks.destroy', $block) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-btn-secondary" style="background:#fee2e2;color:#b91c1c;">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
