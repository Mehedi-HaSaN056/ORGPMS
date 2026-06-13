@extends('layouts.app')
@section('title','Departments')
@section('content')
<div class="page-header"><div><h1 class="page-title"><i class="bi bi-diagram-3 text-primary me-2"></i>Departments</h1></div><a href="{{ route('admin.departments.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Department</a></div>
<div class="row g-3">
@foreach($departments as $dept)
<div class="col-md-4 col-lg-3">
<div class="card h-100" style="border-top:3px solid {{ $dept->color }}">
<div class="card-body">
<div class="d-flex align-items-center gap-2 mb-2">
<div class="rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;background:{{ $dept->color }}20"><i class="{{ $dept->icon }}" style="color:{{ $dept->color }}"></i></div>
<div><div class="fw-semibold">{{ $dept->name }}</div><div class="text-muted small">{{ $dept->code }}</div></div>
</div>
<div class="text-muted small mb-3">{{ $dept->description }}</div>
<div class="d-flex justify-content-between align-items-center">
<span class="badge" style="background:{{ $dept->color }}20;color:{{ $dept->color }}">{{ $dept->users_count }} staff</span>
<div class="d-flex gap-1">
<a href="{{ route('admin.departments.edit',$dept) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
<form method="POST" action="{{ route('admin.departments.destroy',$dept) }}" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')"><i class="bi bi-trash"></i></button></form>
</div>
</div>
</div>
</div>
</div>
@endforeach
</div>
@endsection
