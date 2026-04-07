@extends('layouts.admin')

@section('title', 'Content Blocks')

@section('content')
    <h1>Content Blocks</h1>
    <a href="{{ route('admin.content-blocks.create') }}" class="btn btn-primary mb-3">Create Content Block</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
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
                    <td>{{ $block->id }}</td>
                    <td>{{ $block->section->name }} ({{ $block->section->page->name }})</td>
                    <td>{{ $block->key }}</td>
                    <td>{{ $block->type }}</td>
                    <td>{{ Str::limit($block->content, 50) }}</td>
                    <td>{{ $block->url }}</td>
                    <td>{{ $block->sort_order }}</td>
                    <td>
                        <a href="{{ route('admin.content-blocks.show', $block) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('admin.content-blocks.edit', $block) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form method="POST" action="{{ route('admin.content-blocks.destroy', $block) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection