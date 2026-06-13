@extends('layouts.app')
@section('title','Add Department')
@section('content')
<div class="page-header"><div><h1 class="page-title">Add Department</h1></div><a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a></div>
<div class="row justify-content-center"><div class="col-lg-6"><div class="card"><div class="card-body">
<form method="POST" action="{{ route('admin.departments.store') }}">@csrf
<div class="mb-3"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" required></div>
<div class="mb-3"><label class="form-label">Code *</label><input type="text" name="code" class="form-control" required placeholder="e.g. IT, QC, HR"></div>
<div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
<div class="row g-2 mb-3">
<div class="col"><label class="form-label">Color</label><input type="color" name="color" class="form-control form-control-color" value="#4f46e5"></div>
<div class="col"><label class="form-label">Icon (Bootstrap Icons)</label><input type="text" name="icon" class="form-control" value="bi-building" placeholder="bi-building"></div>
</div>
<div class="d-flex gap-2 justify-content-end"><a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary">Create</button></div>
</form>
</div></div></div></div>
@endsection
