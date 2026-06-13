@extends('layouts.app')
@section('title','Edit Employee')
@section('content')
<div class="page-header"><div><h1 class="page-title">Edit: {{ $employee->name }}</h1></div><a href="{{ route('employees.show',$employee) }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a></div>
<div class="row justify-content-center"><div class="col-lg-8"><div class="card"><div class="card-body">
<form method="POST" action="{{ route('employees.update',$employee) }}" enctype="multipart/form-data">@csrf @method('PUT')
<div class="row g-3 mb-3">
<div class="col-md-6"><label class="form-label">Full Name *</label><input type="text" name="name" class="form-control" value="{{ $employee->name }}" required></div>
<div class="col-md-6"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" value="{{ $employee->email }}" required></div>
<div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ $employee->phone }}"></div>
<div class="col-md-6"><label class="form-label">Designation</label><input type="text" name="designation" class="form-control" value="{{ $employee->designation }}"></div>
<div class="col-md-6"><label class="form-label">Department *</label><select name="department_id" class="form-select" required>@foreach($departments as $d)<option value="{{ $d->id }}" {{ $employee->department_id==$d->id?'selected':'' }}>{{ $d->name }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active" {{ $employee->status=='active'?'selected':'' }}>Active</option><option value="inactive" {{ $employee->status=='inactive'?'selected':'' }}>Inactive</option><option value="suspended" {{ $employee->status=='suspended'?'selected':'' }}>Suspended</option></select></div>
<div class="col-md-6"><label class="form-label">Role</label><select name="role" class="form-select"><option value="">— Keep Current —</option>@foreach($roles as $r)<option value="{{ $r->name }}" {{ $employee->hasRole($r->name)?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$r->name)) }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label">Avatar</label><input type="file" name="avatar" class="form-control" accept="image/*"></div>
</div>
<div class="d-flex gap-2 justify-content-end"><a href="{{ route('employees.show',$employee) }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary">Update Employee</button></div>
</form>
</div></div></div></div>
@endsection
