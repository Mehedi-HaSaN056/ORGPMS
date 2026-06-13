@extends('layouts.app')
@section('title','Leaderboard')
@section('content')
<div class="page-header">
    <div><h1 class="page-title"><i class="bi bi-trophy text-warning me-2"></i>Employee Leaderboard</h1></div>
</div>
<div class="row g-3 mb-4">
@foreach($employees->take(3) as $i => $item)
<div class="col-md-4">
<div class="card text-center" style="border-top:4px solid {{ ['#f59e0b','#94a3b8','#cd7c2f'][$i] }}">
<div class="card-body py-4">
<div class="fs-1 mb-2">{{ ['🥇','🥈','🥉'][$i] }}</div>
<img src="{{ $item['user']->avatar_url }}" class="rounded-circle mb-2" width="64" height="64" style="object-fit:cover">
<h5>{{ $item['user']->name }}</h5>
<p class="text-muted small">{{ $item['user']->department?->name }}</p>
<div class="fs-2 fw-bold text-primary">{{ $item['score'] }}%</div>
<div class="text-muted small">Overall Score</div>
</div>
</div>
</div>
@endforeach
</div>
<div class="card"><div class="card-body p-0">
<table class="table data-table mb-0">
<thead><tr><th>#</th><th>Employee</th><th>Department</th><th>Tasks Done</th><th>Avg KPI</th><th>Badges</th><th>Score</th></tr></thead>
<tbody>
@foreach($employees as $i => $item)
<tr>
<td><span class="fw-bold" style="color:{{ $i<3?['#f59e0b','#94a3b8','#cd7c2f'][$i]:'inherit' }}">{{ $i+1 }}</span></td>
<td><div class="d-flex align-items-center gap-2"><img src="{{ $item['user']->avatar_url }}" class="rounded-circle" width="32" height="32" style="object-fit:cover"><span class="fw-semibold">{{ $item['user']->name }}</span></div></td>
<td>{{ $item['user']->department?->name }}</td>
<td>{{ $item['completedTasks'] }}</td>
<td><span class="fw-bold {{ $item['kpiAvg']>=75?'text-success':($item['kpiAvg']>=50?'text-warning':'text-danger') }}">{{ $item['kpiAvg'] }}%</span></td>
<td><span class="badge bg-warning text-dark"><i class="bi bi-award me-1"></i>{{ $item['badges'] }}</span></td>
<td><span class="fw-bold text-primary fs-5">{{ $item['score'] }}%</span></td>
</tr>
@endforeach
</tbody>
</table>
</div></div>
@endsection
