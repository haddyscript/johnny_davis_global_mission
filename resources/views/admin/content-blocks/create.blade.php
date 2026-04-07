@extends('layouts.admin')

@section('title', 'Create Content Block')

@section('content')
    <h1>Create Content Block</h1>
    <form method="POST" action="{{ route('admin.content-blocks.store') }}">
        @csrf
        <div class="mb-3">
            <label for="section_id" class="form-label">Section</label>
            <select class="form-control" id="section_id" name="section_id" required>
                @foreach($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->page->name }} - {{ $section->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="key" class="form-label">Key</label>
            <input type="text" class="form-control" id="key" name="key" required>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select class="form-control" id="type" name="type" required>
                <option value="text">Text</option>
                <option value="image">Image</option>
                <option value="link">Link</option>
                <option value="list">List</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" name="content" rows="4"></textarea>
        </div>
        <div class="mb-3">
            <label for="url" class="form-label">URL</label>
            <input type="url" class="form-control" id="url" name="url">
        </div>
        <div class="mb-3">
            <label for="sort_order" class="form-label">Sort Order</label>
            <input type="number" class="form-control" id="sort_order" name="sort_order" value="0">
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
        <a href="{{ route('admin.content-blocks.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection