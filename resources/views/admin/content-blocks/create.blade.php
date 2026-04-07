@extends('layouts.admin')

@section('page-title', 'Create Content Block')

@section('content')
<div class="admin-card form-card">
    <form method="POST" action="{{ route('admin.content-blocks.store') }}">
        @csrf
        <div class="form-group">
            <label for="section_id" class="form-label">Section</label>
            <select id="section_id" name="section_id" class="form-select" required>
                @foreach($sections as $section)
                    <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>{{ $section->page->name }} — {{ $section->name }}</option>
                @endforeach
            </select>
            @error('section_id')<div class="form-text" style="color:#b91c1c;">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="key" class="form-label">Key</label>
            <input id="key" name="key" type="text" class="form-control" value="{{ old('key') }}" required>
            @error('key')<div class="form-text" style="color:#b91c1c;">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="type" class="form-label">Type</label>
            <select id="type" name="type" class="form-select" required>
                <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Text</option>
                <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>Image</option>
                <option value="link" {{ old('type') == 'link' ? 'selected' : '' }}>Link</option>
                <option value="list" {{ old('type') == 'list' ? 'selected' : '' }}>List</option>
            </select>
            @error('type')<div class="form-text" style="color:#b91c1c;">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="content" class="form-label">Content</label>
            <textarea id="content" name="content" class="form-control" rows="4">{{ old('content') }}</textarea>
            @error('content')<div class="form-text" style="color:#b91c1c;">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="url" class="form-label">URL</label>
            <input id="url" name="url" type="url" class="form-control" value="{{ old('url') }}">
            @error('url')<div class="form-text" style="color:#b91c1c;">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="sort_order" class="form-label">Sort Order</label>
            <input id="sort_order" name="sort_order" type="number" class="form-control" value="{{ old('sort_order', 0) }}">
            @error('sort_order')<div class="form-text" style="color:#b91c1c;">{{ $message }}</div>@enderror
        </div>
        <div class="admin-actions">
            <button type="submit" class="admin-btn">Create Block</button>
            <a href="{{ route('admin.content-blocks.index') }}" class="admin-btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
