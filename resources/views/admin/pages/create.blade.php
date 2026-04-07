@extends('layouts.admin')

@section('title', 'Create Page')

@section('content')
    <h1>Create Page</h1>
    <form method="POST" action="{{ route('admin.pages.store') }}">
        @csrf
        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" class="form-control" id="slug" name="slug" required>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <div class="mb-3">
            <label for="is_active" class="form-check-label">Active</label>
            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
        </div>
        <div class="mb-3">
            <label for="sort_order" class="form-label">Sort Order</label>
            <input type="number" class="form-control" id="sort_order" name="sort_order" value="0">
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection