@extends('layouts.admin')

@section('page-title', 'Create Page')

@section('content')
<div class="admin-card form-card">
    <form method="POST" action="{{ route('admin.pages.store') }}">
        @csrf
        <div class="form-group">
            <label for="slug" class="form-label">Slug</label>
            <input id="slug" name="slug" type="text" class="form-control" value="{{ old('slug') }}" required>
            @error('slug')<div class="form-text" style="color:#b91c1c;">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="name" class="form-label">Name</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name') }}" required>
            @error('name')<div class="form-text" style="color:#b91c1c;">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            @error('description')<div class="form-text" style="color:#b91c1c;">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Status</label>
            <div>
                <label style="margin-right:16px;"><input type="checkbox" name="is_active" value="1" checked> Active</label>
            </div>
        </div>
        <div class="form-group">
            <label for="sort_order" class="form-label">Sort Order</label>
            <input id="sort_order" name="sort_order" type="number" class="form-control" value="{{ old('sort_order', 0) }}">
            @error('sort_order')<div class="form-text" style="color:#b91c1c;">{{ $message }}</div>@enderror
        </div>
        <div class="admin-actions">
            <button type="submit" class="admin-btn">Create Page</button>
            <a href="{{ route('admin.pages.index') }}" class="admin-btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
