@extends('layouts.app')
@section('title','Add Employee')
@section('content')
<div class="page-header"><div><h1 class="page-title">Add Employee</h1></div><a href="{{ route('employees.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a></div>
<div class="row justify-content-center"><div class="col-lg-8"><div class="card"><div class="card-body">
<form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">@csrf
<div class="row g-3 mb-3">
<div class="col-md-6"><label class="form-label">Full Name *</label><input type="text" name="name" class="form-control" required></div>
<div class="col-md-6"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required></div>
<div class="col-md-6"><label class="form-label">Employee ID</label><input type="text" name="employee_id" class="form-control"></div>
<div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control"></div>
<div class="col-md-6"><label class="form-label">Designation</label><input type="text" name="designation" class="form-control"></div>
<div class="col-md-6"><label class="form-label">Department *</label><select name="department_id" class="form-select" required><option value="">— Select —</option>@foreach($departments as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label">Gender</label><select name="gender" class="form-select"><option value="">—</option><option value="male">Male</option><option value="female">Female</option><option value="other">Other</option></select></div>
<div class="col-md-4"><label class="form-label">Date of Birth</label><input type="date" name="date_of_birth" class="form-control"></div>
<div class="col-md-4"><label class="form-label">Joining Date</label><input type="date" name="joining_date" class="form-control"></div>
<div class="col-md-6"><label class="form-label">Role *</label><select name="role" class="form-select" required>@foreach($roles as $r)<option value="{{ $r->name }}">{{ ucfirst(str_replace('_',' ',$r->name)) }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label">Avatar</label><input type="file" name="avatar" class="form-control" accept="image/*"></div>
<div class="col-md-6"><label class="form-label">Password *</label><input type="password" name="password" class="form-control" required></div>
<div class="col-md-6"><label class="form-label">Confirm Password *</label><input type="password" name="password_confirmation" class="form-control" required></div>
<div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2"></textarea></div>
</div>
<div class="d-flex gap-2 justify-content-end"><a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary">Create Employee</button></div>
</form>
</div></div></div></div>
@endsection
