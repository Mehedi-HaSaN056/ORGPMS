@extends('layouts.app')
@section('title','Login Logs')
@section('content')
<div class="page-header"><div><h1 class="page-title"><i class="bi bi-shield-check text-info me-2"></i>Login Security Logs</h1></div></div>
<div class="card"><div class="card-body p-0">
<table class="table data-table mb-0">
<thead><tr><th>User</th><th>Status</th><th>IP Address</th><th>Browser</th><th>Platform</th><th>Login Time</th><th>Logout Time</th></tr></thead>
<tbody>
@foreach($logs as $log)
<tr>
<td><div class="d-flex align-items-center gap-2"><img src="{{ $log->user?->avatar_url }}" class="rounded-circle" width="28" height="28" style="object-fit:cover"><span>{{ $log->user?->name }}</span></div></td>
<td><span class="badge {{ $log->is_successful?'bg-success':'bg-danger' }}">{{ $log->is_successful?'Success':'Failed' }}</span>@if($log->failure_reason)<br><small class="text-muted">{{ $log->failure_reason }}</small>@endif</td>
<td>{{ $log->ip_address }}</td>
<td><small>{{ $log->browser }}</small></td>
<td><small>{{ $log->platform }}</small></td>
<td><small>{{ $log->logged_in_at?->format('d M Y H:i') }}</small></td>
<td><small>{{ $log->logged_out_at?->format('d M Y H:i') ?? 'Active' }}</small></td>
</tr>
@endforeach
</tbody>
</table>
</div></div>
<div class="mt-3">{{ $logs->links() }}</div>
@endsection
