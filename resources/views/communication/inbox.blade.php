@extends('layouts.app')
@section('title','Messages')
@section('content')
<div class="page-header">
    <div><h1 class="page-title"><i class="bi bi-envelope text-primary me-2"></i>Inbox</h1><p class="page-subtitle">{{ $unreadCount }} unread message(s)</p></div>
    <a href="{{ route('messages.compose') }}" class="btn btn-primary"><i class="bi bi-pencil-square me-1"></i>Compose</a>
</div>
<div class="card"><div class="card-body p-0">
@forelse($messages as $msg)
<a href="{{ route('messages.show',$msg) }}" class="d-flex align-items-start gap-3 p-3 border-bottom text-decoration-none {{ !$msg->is_read && $msg->receiver_id===auth()->id()?'fw-semibold':'text-body' }}" style="{{ !$msg->is_read && $msg->receiver_id===auth()->id()?'background:var(--primary-light)':'' }}">
<img src="{{ $msg->sender?->avatar_url }}" class="rounded-circle flex-shrink-0" width="40" height="40" style="object-fit:cover">
<div class="flex-grow-1 min-w-0">
<div class="d-flex justify-content-between">
<span>{{ $msg->sender?->name }}</span>
<small class="text-muted">{{ $msg->created_at->diffForHumans() }}</small>
</div>
<div>{{ $msg->subject ?? '(No subject)' }}</div>
<div class="text-muted small text-truncate">{{ Str::limit(strip_tags($msg->body), 80) }}</div>
</div>
<div class="d-flex flex-column align-items-end gap-1">
<span class="badge rounded-pill {{ ['normal'=>'bg-secondary','high'=>'bg-warning','urgent'=>'bg-danger'][$msg->priority] }}">{{ $msg->priority }}</span>
@if(!$msg->is_read && $msg->receiver_id===auth()->id())<span class="rounded-circle bg-primary" style="width:8px;height:8px;display:inline-block"></span>@endif
</div>
</a>
@empty
<div class="text-center text-muted py-5"><i class="bi bi-envelope-open fs-2 d-block mb-2"></i>Your inbox is empty.</div>
@endforelse
</div></div>
<div class="mt-3">{{ $messages->links() }}</div>
@endsection
