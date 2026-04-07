@extends('layouts.admin')

@section('page-title', 'Edit Section')

@section('content')
<div class="admin-card form-card">
    <form method="POST" action="{{ route('admin.sections.update', $section) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="page_id" class="form-label">Page</label>
            <select id="page_id" name="page_id" class="form-select" required>
                @foreach($pages as $page)
                    <option value="{{ $page->id }}" {{ old('page_id', $section->page_id) == $page->id ? 'selected' : '' }}>{{ $page->name }}</option>
                @endforeach
            </select>
            @error('page_id')<div class="form-text" style="color:#b91c1c;">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="slug" class="form-label">Slug</label>
            <input id="slug" name="slug" type="text" class="form-control" value="{{ old('slug', $section->slug) }}" required>
            @error('slug')<div class="form-text" style="color:#b91c1c;">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="name" class="form-label">Name</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $section->name) }}" required>
            @error('name')<div class="form-text" style="color:#b91c1c;">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="type" class="form-label">Type</label>
            <input id="type" name="type" type="text" class="form-control" value="{{ old('type', $section->type) }}">
            @error('type')<div class="form-text" style="color:#b91c1c;">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="sort_order" class="form-label">Sort Order</label>
            <input id="sort_order" name="sort_order" type="number" class="form-control" value="{{ old('sort_order', $section->sort_order) }}">
            @error('sort_order')<div class="form-text" style="color:#b91c1c;">{{ $message }}</div>@enderror
        </div>
        <div class="admin-actions">
            <button type="submit" class="admin-btn">Update Section</button>
            <a href="{{ route('admin.sections.index') }}" class="admin-btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
