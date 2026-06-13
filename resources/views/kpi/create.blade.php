@extends('layouts.app')
@section('title','Add KPI')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Add KPI Entry</h1></div>
    <a href="{{ route('kpi.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<div class="row justify-content-center"><div class="col-lg-7"><div class="card"><div class="card-body">
<form method="POST" action="{{ route('kpi.store') }}">@csrf
<div class="row g-3 mb-3">
    <div class="col-md-6"><label class="form-label">Employee *</label><select name="user_id" class="form-select" required><option value="">— Select —</option>@foreach($employees as $e)<option value="{{ $e->id }}">{{ $e->name }}</option>@endforeach</select></div>
    <div class="col-md-6"><label class="form-label">Department *</label><select name="department_id" class="form-select" required>@foreach($departments as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach</select></div>
</div>
<div class="mb-3"><label class="form-label">KPI Title *</label><input type="text" name="title" class="form-control" required></div>
<div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
<div class="row g-3 mb-3">
    <div class="col-md-3"><label class="form-label">Target *</label><input type="number" name="target" class="form-control" step="0.01" min="0" required></div>
    <div class="col-md-3"><label class="form-label">Achieved *</label><input type="number" name="achieved" class="form-control" step="0.01" min="0" required></div>
    <div class="col-md-3"><label class="form-label">Unit *</label><input type="text" name="metric_unit" class="form-control" value="%" placeholder="%, count, etc" required></div>
    <div class="col-md-3"><label class="form-label">Period *</label><select name="period" class="form-select"><option value="monthly">Monthly</option><option value="quarterly">Quarterly</option><option value="yearly">Yearly</option></select></div>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-6"><label class="form-label">Year *</label><input type="number" name="year" class="form-control" value="{{ now()->year }}" required></div>
    <div class="col-md-6"><label class="form-label">Month *</label><select name="month" class="form-select" required>@for($m=1;$m<=12;$m++)<option value="{{ $m }}" {{ now()->month==$m?'selected':'' }}>{{ date('F',mktime(0,0,0,$m,1)) }}</option>@endfor</select></div>
</div>
<div class="mb-3"><label class="form-label">Remarks</label><textarea name="remarks" class="form-control" rows="2"></textarea></div>
<div class="d-flex gap-2 justify-content-end">
    <a href="{{ route('kpi.index') }}" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Save KPI</button>
</div>
</form>
</div></div></div></div>
@endsection
