@extends('layouts.admin')

@section('title', 'Sections')

@section('content')
    <h1>Sections</h1>
    <a href="{{ route('admin.sections.create') }}" class="btn btn-primary mb-3">Create Section</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
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
                    <td>{{ $section->id }}</td>
                    <td>{{ $section->page->name }}</td>
                    <td>{{ $section->slug }}</td>
                    <td>{{ $section->name }}</td>
                    <td>{{ $section->type }}</td>
                    <td>{{ $section->sort_order }}</td>
                    <td>
                        <a href="{{ route('admin.sections.show', $section) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('admin.sections.edit', $section) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form method="POST" action="{{ route('admin.sections.destroy', $section) }}" style="display:inline;">
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