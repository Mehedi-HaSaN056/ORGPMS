@extends('layouts.app')
@section('title','Work Logs')
@section('content')
<div class="page-header"><div><h1 class="page-title"><i class="bi bi-journal-text text-primary me-2"></i>Work Logs</h1></div></div>
<div class="card mb-4"><div class="card-header">Log Today's Work</div><div class="card-body">
<form method="POST" action="{{ route('work-logs.store') }}">@csrf
<div class="row g-2">
<div class="col-md-5"><textarea name="description" class="form-control" rows="2" placeholder="What did you work on today?" required></textarea></div>
<div class="col-md-2"><input type="number" name="hours_spent" class="form-control" placeholder="Hours" min="0" max="24" step="0.5"></div>
<div class="col-md-2"><input type="date" name="log_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
<div class="col-md-1"><button type="submit" class="btn btn-primary h-100 w-100">Log</button></div>
</div>
</form>
</div></div>
<div class="card"><div class="card-body p-0">
<table class="table data-table mb-0">
<thead><tr><th>Date</th><th>Description</th><th>Hours</th></tr></thead>
<tbody>
@forelse($logs as $log)
<tr><td>{{ $log->log_date->format('d M Y') }}</td><td>{{ $log->description }}</td><td>{{ $log->hours_spent ?? '—' }}h</td></tr>
@empty
<tr><td colspan="3" class="text-center py-5 text-muted">No work logs yet.</td></tr>
@endforelse
</tbody>
</table>
</div></div>
<div class="mt-3">{{ $logs->links() }}</div>
@endsection
