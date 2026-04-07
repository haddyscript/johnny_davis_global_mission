@extends('layouts.admin')

@section('page-title', 'Section Details')

@section('content')
<div class="admin-card">
    <div class="admin-toolbar">
        <div>
            <h2>{{ $section->name }}</h2>
            <p class="text-muted">Page: {{ $section->page->name }}</p>
        </div>
        <div class="admin-actions">
            <a href="{{ route('admin.sections.edit', $section) }}" class="admin-btn">Edit</a>
            <a href="{{ route('admin.sections.index') }}" class="admin-btn-secondary">Back</a>
        </div>
    </div>
    <div class="form-group">
        <label class="form-label">Slug</label>
        <div>{{ $section->slug }}</div>
    </div>
    <div class="form-group">
        <label class="form-label">Type</label>
        <div>{{ $section->type }}</div>
    </div>
    <div class="form-group">
        <label class="form-label">Sort Order</label>
        <div>{{ $section->sort_order }}</div>
    </div>
</div>
@endsection
