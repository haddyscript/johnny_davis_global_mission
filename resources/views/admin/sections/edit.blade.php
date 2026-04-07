@extends('layouts.admin')

@section('title', 'Edit Section')

@section('content')
    <h1>Edit Section</h1>
    <form method="POST" action="{{ route('admin.sections.update', $section) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="page_id" class="form-label">Page</label>
            <select class="form-control" id="page_id" name="page_id" required>
                @foreach($pages as $page)
                    <option value="{{ $page->id }}" {{ $section->page_id == $page->id ? 'selected' : '' }}>{{ $page->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" class="form-control" id="slug" name="slug" value="{{ $section->slug }}" required>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $section->name }}" required>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <input type="text" class="form-control" id="type" name="type" value="{{ $section->type }}">
        </div>
        <div class="mb-3">
            <label for="sort_order" class="form-label">Sort Order</label>
            <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ $section->sort_order }}">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.sections.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection