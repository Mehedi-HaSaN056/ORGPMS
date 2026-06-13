@extends('layouts.app')
@section('title', $request->title)
@section('content')
<div class="page-header"><div><h1 class="page-title">{{ $request->title }}</h1></div><a href="{{ route('dev-requests.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a></div>
<div class="row g-4">
<div class="col-lg-8"><div class="card"><div class="card-body">
<div class="d-flex gap-2 mb-3"><span class="priority-badge priority-{{ $request->priority }}">{{ $request->priority }}</span><span class="badge bg-secondary">{{ $request->type }}</span><span class="status-badge status-{{ $request->status }}">{{ str_replace('_',' ',$request->status) }}</span></div>
<p>{{ $request->description }}</p>
@if($request->management_response)<div class="alert alert-info"><strong>Management Response:</strong> {{ $request->management_response }}</div>@endif
@if($request->budget_comment)<div class="alert alert-warning"><strong>Budget Note:</strong> {{ $request->budget_comment }}</div>@endif
@if(auth()->user()->is_management)
<hr><form method="POST" action="{{ route('management.dev-request.update', $request) }}">@csrf @method('PATCH')
<div class="row g-2 mb-2"><div class="col-md-4"><select name="status" class="form-select"><option value="under_review">Under Review</option><option value="approved">Approved</option><option value="rejected">Rejected</option><option value="implemented">Implemented</option></select></div></div>
<div class="mb-2"><textarea name="management_response" class="form-control" rows="2" placeholder="Management response..."></textarea></div>
<div class="mb-2"><textarea name="budget_comment" class="form-control" rows="2" placeholder="Budget comment..."></textarea></div>
<button type="submit" class="btn btn-primary">Update Status</button>
</form>
@endif
</div></div></div>
<div class="col-lg-4"><div class="card"><div class="card-header">Details</div><div class="card-body">
<table class="table table-sm mb-0">
<tr><td class="text-muted">Requested By</td><td>{{ $request->requester?->name }}</td></tr>
<tr><td class="text-muted">Department</td><td>{{ $request->department?->name }}</td></tr>
<tr><td class="text-muted">Est. Budget</td><td>{{ $request->estimated_budget ? 'BDT '.number_format($request->estimated_budget) : '—' }}</td></tr>
<tr><td class="text-muted">Expected Date</td><td>{{ $request->expected_date?->format('d M Y') ?? '—' }}</td></tr>
<tr><td class="text-muted">Reviewed By</td><td>{{ $request->reviewer?->name ?? '—' }}</td></tr>
</table>
</div></div></div>
</div>
@endsection
