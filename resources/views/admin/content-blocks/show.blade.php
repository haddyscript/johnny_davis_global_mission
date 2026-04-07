@extends('layouts.admin')

@section('page-title', 'Content Block Details')

@section('content')
<div class="admin-card">
    <div class="admin-toolbar">
        <div>
            <h2>{{ $contentBlock->key }}</h2>
            <p class="text-muted">Section: {{ $contentBlock->section->name }} ({{ $contentBlock->section->page->name }})</p>
        </div>
        <div class="admin-actions">
            <a href="{{ route('admin.content-blocks.edit', $contentBlock) }}" class="admin-btn">Edit</a>
            <a href="{{ route('admin.content-blocks.index') }}" class="admin-btn-secondary">Back</a>
        </div>
    </div>
    <div class="form-group">
        <label class="form-label">Type</label>
        <div>{{ $contentBlock->type }}</div>
    </div>
    <div class="form-group">
        <label class="form-label">Content</label>
        <div class="form-control" style="min-height:120px;white-space:pre-wrap;">{{ $contentBlock->content }}</div>
    </div>
    <div class="form-group">
        <label class="form-label">URL</label>
        <div>{{ $contentBlock->url }}</div>
    </div>
    <div class="form-group">
        <label class="form-label">Sort Order</label>
        <div>{{ $contentBlock->sort_order }}</div>
    </div>
</div>
@endsection
