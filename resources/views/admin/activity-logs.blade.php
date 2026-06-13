@extends('layouts.app')
@section('title','Activity Logs')
@section('content')
<div class="page-header"><div><h1 class="page-title"><i class="bi bi-activity text-success me-2"></i>Activity Logs</h1></div></div>
<div class="card"><div class="card-body p-0">
<table class="table data-table mb-0">
<thead><tr><th>User</th><th>Action</th><th>Description</th><th>IP</th><th>Time</th></tr></thead>
<tbody>
@foreach($logs as $log)
<tr>
<td><div class="d-flex align-items-center gap-2"><img src="{{ $log->user?->avatar_url }}" class="rounded-circle" width="28" height="28" style="object-fit:cover"><span>{{ $log->user?->name ?? 'System' }}</span></div></td>
<td><span class="badge bg-primary">{{ strtoupper($log->action) }}</span></td>
<td>{{ $log->description }}</td>
<td>{{ $log->ip_address }}</td>
<td><small>{{ $log->created_at->diffForHumans() }}</small></td>
</tr>
@endforeach
</tbody>
</table>
</div></div>
<div class="mt-3">{{ $logs->links() }}</div>
@endsection
