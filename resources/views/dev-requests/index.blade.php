@extends('layouts.app')
@section('title','Development Requests')
@section('content')
<div class="page-header">
    <div><h1 class="page-title"><i class="bi bi-lightbulb text-warning me-2"></i>Development Requests</h1></div>
    <a href="{{ route('dev-requests.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New Request</a>
</div>
<div class="card"><div class="card-body p-0">
<table class="table data-table mb-0">
<thead><tr><th>Title</th><th>Type</th><th>Department</th><th>Priority</th><th>Status</th><th>Budget</th><th>Date</th><th></th></tr></thead>
<tbody>
@forelse($requests as $req)
<tr>
<td><a href="{{ route('dev-requests.show',$req) }}" class="text-decoration-none fw-semibold">{{ $req->title }}</a><br><small class="text-muted">{{ $req->requester?->name }}</small></td>
<td><span class="badge bg-secondary">{{ $req->type }}</span></td>
<td>{{ $req->department?->name }}</td>
<td><span class="priority-badge priority-{{ $req->priority }}">{{ $req->priority }}</span></td>
<td><span class="status-badge status-{{ $req->status }}">{{ str_replace('_',' ',$req->status) }}</span></td>
<td>{{ $req->estimated_budget ? 'BDT '.number_format($req->estimated_budget) : '—' }}</td>
<td><small>{{ $req->created_at->format('d M Y') }}</small></td>
<td><a href="{{ route('dev-requests.show',$req) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
</tr>
@empty
<tr><td colspan="8" class="text-center py-5 text-muted"><i class="bi bi-lightbulb fs-2 d-block mb-2"></i>No requests yet.</td></tr>
@endforelse
</tbody>
</table>
</div></div>
<div class="mt-3">{{ $requests->links() }}</div>
@endsection
