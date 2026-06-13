@extends('layouts.app')
@section('title','Create Plan')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Create New Plan</h1></div>
    <a href="{{ route('plans.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card">
<div class="card-body">
<form method="POST" action="{{ route('plans.store') }}" enctype="multipart/form-data">
@csrf
<div class="mb-3"><label class="form-label">Plan Title *</label><input type="text" name="title" class="form-control" value="{{ old('title') }}" required></div>
<div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea></div>
<div class="row g-3 mb-3">
    <div class="col-md-6"><label class="form-label">Department *</label><select name="department_id" class="form-select" required>@foreach($departments as $d)<option value="{{ $d->id }}" {{ old('department_id')==$d->id?'selected':'' }}>{{ $d->name }}</option>@endforeach</select></div>
    <div class="col-md-6"><label class="form-label">Assign To</label><select name="assigned_to" class="form-select"><option value="">— Select Employee —</option>@foreach($employees as $e)<option value="{{ $e->id }}">{{ $e->name }}</option>@endforeach</select></div>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-4"><label class="form-label">Priority *</label><select name="priority" class="form-select" required><option value="low">Low</option><option value="medium" selected>Medium</option><option value="high">High</option><option value="critical">Critical</option></select></div>
    <div class="col-md-4"><label class="form-label">Start Date</label><input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}"></div>
    <div class="col-md-4"><label class="form-label">Due Date</label><input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}"></div>
</div>
<div class="mb-3"><label class="form-label">Attachments</label><input type="file" name="attachments[]" class="form-control" multiple></div>
<div class="d-flex gap-2 justify-content-end">
    <a href="{{ route('plans.index') }}" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Create Plan</button>
</div>
</form>
</div>
</div>
</div>
</div>
@endsection
