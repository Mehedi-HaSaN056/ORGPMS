@extends('layouts.app')
@section('title', $plan->title)
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $plan->title }}</h1>
        <p class="page-subtitle">{{ $plan->department?->name }} &middot; Created by {{ $plan->creator?->name }}</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('plans.edit',$plan) }}" class="btn btn-outline-secondary"><i class="bi bi-pencil me-1"></i>Edit</a>
        @if(auth()->user()->is_management && $plan->approval_status === 'pending')
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal"><i class="bi bi-check-circle me-1"></i>Approve</button>
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal"><i class="bi bi-x-circle me-1"></i>Reject</button>
        @endif
        <a href="{{ route('plans.index') }}" class="btn btn-outline-primary"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
</div>
<div class="row g-4">
<div class="col-lg-8">
<div class="card mb-4">
<div class="card-body">
<div class="row g-3 mb-4">
    <div class="col-sm-4 text-center"><div class="text-muted small mb-1">Priority</div><span class="priority-badge priority-{{ $plan->priority }} fs-6">{{ $plan->priority }}</span></div>
    <div class="col-sm-4 text-center"><div class="text-muted small mb-1">Status</div><span class="status-badge status-{{ $plan->status }} fs-6">{{ str_replace('_',' ',$plan->status) }}</span></div>
    <div class="col-sm-4 text-center"><div class="text-muted small mb-1">Approval</div><span class="status-badge status-{{ $plan->approval_status }} fs-6">{{ $plan->approval_status }}</span></div>
</div>
<div class="mb-3"><strong>Description</strong><p class="mt-1 text-muted">{{ $plan->description ?? 'No description provided.' }}</p></div>
<div class="mb-3">
    <div class="d-flex justify-content-between mb-1"><span class="fw-semibold">Progress</span><span class="fw-bold text-primary">{{ $plan->progress }}%</span></div>
    <div class="progress" style="height:12px"><div class="progress-bar bg-primary" data-value="{{ $plan->progress }}" style="width:0%"></div></div>
</div>
@if($plan->management_comment)<div class="alert alert-info mt-3"><i class="bi bi-chat-quote me-2"></i><strong>Management Comment:</strong> {{ $plan->management_comment }}</div>@endif
</div>
</div>

@if($plan->tasks->count())
<div class="card">
<div class="card-header"><span><i class="bi bi-list-task me-2"></i>Tasks ({{ $plan->tasks->count() }})</span></div>
<div class="card-body p-0">
@foreach($plan->tasks as $task)
<div class="d-flex align-items-center gap-3 p-3 border-bottom">
    <span class="status-badge status-{{ $task->status }}">{{ str_replace('_',' ',$task->status) }}</span>
    <span class="flex-grow-1">{{ $task->title }}</span>
    <span class="text-muted small">{{ $task->due_date?->format('d M') }}</span>
</div>
@endforeach
</div>
</div>
@endif
</div>

<div class="col-lg-4">
<div class="card mb-3">
<div class="card-header">Details</div>
<div class="card-body">
<table class="table table-sm mb-0">
<tr><td class="text-muted">Assigned To</td><td>{{ $plan->assignee?->name ?? '—' }}</td></tr>
<tr><td class="text-muted">Approved By</td><td>{{ $plan->approver?->name ?? '—' }}</td></tr>
<tr><td class="text-muted">Start Date</td><td>{{ $plan->start_date?->format('d M Y') ?? '—' }}</td></tr>
<tr><td class="text-muted">Due Date</td><td>{{ $plan->due_date?->format('d M Y') ?? '—' }}</td></tr>
<tr><td class="text-muted">Completed</td><td>{{ $plan->completed_at?->format('d M Y') ?? '—' }}</td></tr>
</table>
</div>
</div>

@if($plan->attachments->count())
<div class="card">
<div class="card-header">Attachments</div>
<div class="card-body p-0">
@foreach($plan->attachments as $att)
<a href="{{ $att->url }}" class="d-flex align-items-center gap-2 p-3 border-bottom text-decoration-none" target="_blank">
    <i class="bi bi-file-earmark text-primary fs-5"></i>
    <div><div class="small fw-semibold">{{ $att->original_name }}</div><div class="text-muted" style="font-size:.7rem">{{ $att->formatted_size }}</div></div>
</a>
@endforeach
</div>
</div>
@endif
</div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal"><div class="modal-dialog"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Approve Plan</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
<form method="POST" action="{{ route('plans.approve',$plan) }}">@csrf
<div class="modal-body"><div class="mb-3"><label class="form-label">Comment (optional)</label><textarea name="comment" class="form-control" rows="3"></textarea></div></div>
<div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-success">Approve</button></div>
</form></div></div></div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal"><div class="modal-dialog"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title text-danger">Reject Plan</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
<form method="POST" action="{{ route('plans.reject',$plan) }}">@csrf
<div class="modal-body"><div class="mb-3"><label class="form-label">Reason for Rejection *</label><textarea name="comment" class="form-control" rows="3" required></textarea></div></div>
<div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-danger">Reject</button></div>
</form></div></div></div>
@endsection
