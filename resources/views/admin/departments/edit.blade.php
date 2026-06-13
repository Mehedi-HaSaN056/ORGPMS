@extends('layouts.app')
@section('title','Edit Department')
@section('content')
<div class="page-header"><div><h1 class="page-title">Edit: {{ $department->name }}</h1></div><a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a></div>
<div class="row justify-content-center"><div class="col-lg-6"><div class="card"><div class="card-body">
<form method="POST" action="{{ route('admin.departments.update',$department) }}">@csrf @method('PUT')
<div class="mb-3"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" value="{{ $department->name }}" required></div>
<div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3">{{ $department->description }}</textarea></div>
<div class="row g-2 mb-3">
<div class="col"><label class="form-label">Color</label><input type="color" name="color" class="form-control form-control-color" value="{{ $department->color }}"></div>
<div class="col"><label class="form-label">Icon</label><input type="text" name="icon" class="form-control" value="{{ $department->icon }}"></div>
</div>
<div class="mb-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $department->is_active?'checked':'' }}><label class="form-check-label">Active</label></div></div>
<div class="d-flex gap-2 justify-content-end"><a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary">Update</button></div>
</form>
</div></div></div></div>
@endsection
