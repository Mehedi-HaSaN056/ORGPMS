@extends('layouts.app')
@section('title','Submit Request')
@section('content')
<div class="page-header"><div><h1 class="page-title">Submit Development Request</h1></div><a href="{{ route('dev-requests.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a></div>
<div class="row justify-content-center"><div class="col-lg-7"><div class="card"><div class="card-body">
<form method="POST" action="{{ route('dev-requests.store') }}">@csrf
<div class="mb-3"><label class="form-label">Department *</label><select name="department_id" class="form-select" required><option value="">— Select —</option>@foreach($departments as $d)<option value="{{ $d->id }}" {{ auth()->user()->department_id==$d->id?'selected':'' }}>{{ $d->name }}</option>@endforeach</select></div>
<div class="mb-3"><label class="form-label">Request Title *</label><input type="text" name="title" class="form-control" required></div>
<div class="mb-3"><label class="form-label">Detailed Description *</label><textarea name="description" class="form-control" rows="5" required></textarea></div>
<div class="row g-3 mb-3">
<div class="col-md-4"><label class="form-label">Type *</label><select name="type" class="form-select" required><option value="software">Software</option><option value="equipment">Equipment</option><option value="process">Process</option><option value="resource">Resource</option><option value="training">Training</option><option value="other">Other</option></select></div>
<div class="col-md-4"><label class="form-label">Priority *</label><select name="priority" class="form-select" required><option value="low">Low</option><option value="medium" selected>Medium</option><option value="high">High</option><option value="critical">Critical</option></select></div>
<div class="col-md-4"><label class="form-label">Est. Budget (BDT)</label><input type="number" name="estimated_budget" class="form-control" min="0"></div>
</div>
<div class="mb-3"><label class="form-label">Expected Implementation Date</label><input type="date" name="expected_date" class="form-control"></div>
<div class="d-flex gap-2 justify-content-end"><a href="{{ route('dev-requests.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary">Submit Request</button></div>
</form>
</div></div></div></div>
@endsection
