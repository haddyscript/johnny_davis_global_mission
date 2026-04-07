@extends('layouts.admin')

@section('title', 'Pages')

@section('content')
    <h1>Pages</h1>
    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary mb-3">Create Page</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Slug</th>
                <th>Name</th>
                <th>Description</th>
                <th>Active</th>
                <th>Sort Order</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pages as $page)
                <tr>
                    <td>{{ $page->id }}</td>
                    <td>{{ $page->slug }}</td>
                    <td>{{ $page->name }}</td>
                    <td>{{ $page->description }}</td>
                    <td>{{ $page->is_active ? 'Yes' : 'No' }}</td>
                    <td>{{ $page->sort_order }}</td>
                    <td>
                        <a href="{{ route('admin.pages.show', $page) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" style="display:inline;">
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