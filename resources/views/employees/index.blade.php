@extends('layouts.app')
@section('title','Employees')
@section('content')
<div class="page-header">
    <div><h1 class="page-title"><i class="bi bi-people text-primary me-2"></i>Employees</h1></div>
    @can('create employees')<a href="{{ route('employees.create') }}" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i>Add Employee</a>@endcan
</div>
<div class="card mb-3"><div class="card-body">
<form method="GET" class="row g-2">
    <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="Search by name, email, ID..." value="{{ request('search') }}"></div>
    @if(auth()->user()->is_management)<div class="col-md-3"><select name="department" class="form-select"><option value="">All Departments</option>@foreach($departments as $d)<option value="{{ $d->id }}" {{ request('department')==$d->id?'selected':'' }}>{{ $d->name }}</option>@endforeach</select></div>@endif
    <div class="col-md-2"><select name="status" class="form-select"><option value="">All Status</option><option value="active">Active</option><option value="inactive">Inactive</option><option value="suspended">Suspended</option></select></div>
    <div class="col-md-2"><button type="submit" class="btn btn-outline-primary w-100">Filter</button></div>
</form>
</div></div>
<div class="row g-3">
@forelse($employees as $emp)
<div class="col-md-6 col-lg-4">
<div class="card h-100">
<div class="card-body text-center">
    <img src="{{ $emp->avatar_url }}" class="rounded-circle mb-3" width="70" height="70" style="object-fit:cover;border:3px solid var(--border)">
    <h6 class="mb-0">{{ $emp->name }}</h6>
    <p class="text-muted small mb-2">{{ $emp->designation ?? '—' }}</p>
    <span class="badge rounded-pill mb-2" style="background:{{ $emp->department?->color ?? '#6b7280' }}20;color:{{ $emp->department?->color ?? '#6b7280' }}">{{ $emp->department?->name ?? 'No Dept' }}</span>
    <div class="mb-3">
        <div class="progress" style="height:6px"><div class="progress-bar bg-success" data-value="{{ $emp->task_completion_rate }}" style="width:0%"></div></div>
        <small class="text-muted">{{ $emp->task_completion_rate }}% Task Completion</small>
    </div>
    <a href="{{ route('employees.show',$emp) }}" class="btn btn-sm btn-outline-primary w-100">View Profile</a>
</div>
</div>
</div>
@empty
<div class="col-12 text-center text-muted py-5"><i class="bi bi-people fs-2 d-block mb-2"></i>No employees found.</div>
@endforelse
</div>
<div class="mt-3">{{ $employees->withQueryString()->links() }}</div>
@endsection
