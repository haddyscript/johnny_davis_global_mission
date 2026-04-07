@extends('layouts.admin')

@section('page-title', 'Pages')

@section('content')
<div class="admin-card">
    <div class="admin-toolbar">
        <div>
            <h2>Pages</h2>
            <p class="text-muted">Manage the core content pages that appear throughout your site.</p>
        </div>
        <div class="admin-actions">
            <a href="{{ route('admin.pages.create') }}" class="admin-btn">+ New Page</a>
        </div>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Slug</th>
                <th>Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Sort Order</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pages as $page)
                <tr>
                    <td>{{ $page->slug }}</td>
                    <td>{{ $page->name }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($page->description, 70) }}</td>
                    <td><span class="admin-badge">{{ $page->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>{{ $page->sort_order }}</td>
                    <td class="admin-table-actions">
                        <a href="{{ route('admin.pages.edit', $page) }}" class="admin-btn-secondary">Edit</a>
                        <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" style="display:inline;">
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
