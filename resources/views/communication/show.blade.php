@extends('layouts.app')
@section('title','Message')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">{{ $message->subject ?? '(No subject)' }}</h1></div>
    <a href="{{ route('messages.inbox') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Inbox</a>
</div>
<div class="row justify-content-center"><div class="col-lg-8">
<div class="card mb-4"><div class="card-body">
<div class="d-flex align-items-start gap-3 mb-4">
<img src="{{ $message->sender?->avatar_url }}" class="rounded-circle" width="48" height="48" style="object-fit:cover">
<div>
<div class="fw-semibold">{{ $message->sender?->name }}</div>
<div class="text-muted small">{{ $message->sender?->email }} &middot; {{ $message->created_at->format('d M Y H:i') }}</div>
<span class="badge {{ ['normal'=>'bg-secondary','high'=>'bg-warning','urgent'=>'bg-danger'][$message->priority] }} mt-1">{{ $message->priority }}</span>
</div>
</div>
<div class="border-top pt-3" style="min-height:150px;line-height:1.7">{{ $message->body }}</div>
@if($message->attachment)
<div class="mt-3 border-top pt-3"><a href="{{ asset('storage/'.$message->attachment) }}" class="btn btn-outline-secondary btn-sm" target="_blank"><i class="bi bi-paperclip me-1"></i>Download Attachment</a></div>
@endif
</div></div>

<div class="card"><div class="card-header">Reply</div><div class="card-body">
<form method="POST" action="{{ route('messages.send') }}">@csrf
<input type="hidden" name="type" value="direct">
<input type="hidden" name="receiver_id" value="{{ $message->sender_id }}">
<input type="hidden" name="parent_id" value="{{ $message->id }}">
<input type="hidden" name="priority" value="normal">
<input type="hidden" name="subject" value="Re: {{ $message->subject }}">
<textarea name="body" class="form-control mb-3" rows="4" placeholder="Write your reply..." required></textarea>
<button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Send Reply</button>
</form>
</div></div>

@foreach($message->replies as $reply)
<div class="card mt-3"><div class="card-body">
<div class="d-flex gap-3"><img src="{{ $reply->sender?->avatar_url }}" class="rounded-circle flex-shrink-0" width="36" height="36" style="object-fit:cover">
<div><div class="fw-semibold small">{{ $reply->sender?->name }} <span class="text-muted fw-normal">{{ $reply->created_at->diffForHumans() }}</span></div><p class="mb-0 mt-1">{{ $reply->body }}</p></div>
</div>
</div></div>
@endforeach
</div></div>
@endsection
