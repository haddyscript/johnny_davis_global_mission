@extends('layouts.admin')

@section('page-title', 'Page Details')

@section('content')
<div class="admin-card">
    <div class="admin-toolbar">
        <div>
            <h2>{{ $page->name }}</h2>
            <p class="text-muted">Slug: {{ $page->slug }}</p>
        </div>
        <div class="admin-actions">
            <a href="{{ route('admin.pages.edit', $page) }}" class="admin-btn">Edit</a>
            <a href="{{ route('admin.pages.index') }}" class="admin-btn-secondary">Back</a>
        </div>
    </div>
    <div class="form-group">
        <label class="form-label">Description</label>
        <div class="form-control" style="min-height:120px;white-space:pre-wrap;">{{ $page->description }}</div>
    </div>
    <div class="form-group">
        <label class="form-label">Active</label>
        <div>{{ $page->is_active ? 'Yes' : 'No' }}</div>
    </div>
    <div class="form-group">
        <label class="form-label">Sort Order</label>
        <div>{{ $page->sort_order }}</div>
    </div>
</div>
@endsection
