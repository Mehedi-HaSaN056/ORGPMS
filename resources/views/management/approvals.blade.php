@extends('layouts.app')
@section('title','Pending Approvals')
@section('content')
<div class="page-header"><div><h1 class="page-title"><i class="bi bi-check-circle text-warning me-2"></i>Pending Approvals</h1></div></div>
<div class="card"><div class="card-body p-0">
<table class="table data-table mb-0">
<thead><tr><th>Plan Title</th><th>Department</th><th>Requested By</th><th>Priority</th><th>Due Date</th><th>Actions</th></tr></thead>
<tbody>
@forelse($plans as $plan)
<tr>
<td><a href="{{ route('plans.show',$plan) }}" class="fw-semibold text-decoration-none">{{ $plan->title }}</a></td>
<td>{{ $plan->department?->name }}</td>
<td>{{ $plan->creator?->name }}</td>
<td><span class="priority-badge priority-{{ $plan->priority }}">{{ $plan->priority }}</span></td>
<td>{{ $plan->due_date?->format('d M Y') ?? '—' }}</td>
<td>
<div class="d-flex gap-1">
<form method="POST" action="{{ route('plans.approve',$plan) }}" class="d-inline"><input type="hidden" name="_token" value="{{ csrf_token() }}"><button class="btn btn-sm btn-success">Approve</button></form>
<button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#reject{{ $plan->id }}">Reject</button>
<a href="{{ route('plans.show',$plan) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
</div>
<div class="modal fade" id="reject{{ $plan->id }}"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Reject Plan</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
<form method="POST" action="{{ route('plans.reject',$plan) }}">@csrf<div class="modal-body"><textarea name="comment" class="form-control" rows="3" placeholder="Reason..." required></textarea></div>
<div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-danger">Reject</button></div></form></div></div></div>
</td>
</tr>
@empty
<tr><td colspan="6" class="text-center py-5 text-muted"><i class="bi bi-check-all fs-2 d-block mb-2 text-success"></i>No pending approvals!</td></tr>
@endforelse
</tbody>
</table>
</div></div>
<div class="mt-3">{{ $plans->links() }}</div>
@endsection
