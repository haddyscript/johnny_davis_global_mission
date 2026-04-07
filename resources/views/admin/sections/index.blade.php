@extends('layouts.admin')

@section('page-title', 'Sections')

@section('content')
<div class="admin-card">
    <div class="admin-toolbar">
        <div>
            <h2>Sections</h2>
            <p class="text-muted">Add and update page sections that define site content structure.</p>
        </div>
        <div class="admin-actions">
            <a href="{{ route('admin.sections.create') }}" class="admin-btn">+ New Section</a>
        </div>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Page</th>
                <th>Slug</th>
                <th>Name</th>
                <th>Type</th>
                <th>Sort Order</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sections as $section)
                <tr>
                    <td>{{ $section->page->name }}</td>
                    <td>{{ $section->slug }}</td>
                    <td>{{ $section->name }}</td>
                    <td>{{ $section->type }}</td>
                    <td>{{ $section->sort_order }}</td>
                    <td class="admin-table-actions">
                        <a href="{{ route('admin.sections.edit', $section) }}" class="admin-btn-secondary">Edit</a>
                        <form method="POST" action="{{ route('admin.sections.destroy', $section) }}" style="display:inline;">
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
