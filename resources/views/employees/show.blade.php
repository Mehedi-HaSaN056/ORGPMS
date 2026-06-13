@extends('layouts.app')
@section('title', $employee->name)
@section('content')
<div class="page-header">
    <div><h1 class="page-title">{{ $employee->name }}</h1><p class="page-subtitle">{{ $employee->designation }} &middot; {{ $employee->department?->name }}</p></div>
    <div class="d-flex gap-2">
        @can('edit employees')<a href="{{ route('employees.edit',$employee) }}" class="btn btn-outline-secondary"><i class="bi bi-pencil me-1"></i>Edit</a>@endcan
        <a href="{{ route('employees.index') }}" class="btn btn-outline-primary"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
</div>
<div class="row g-4">
<div class="col-lg-4">
<div class="card mb-4 text-center"><div class="card-body py-4">
<img src="{{ $employee->avatar_url }}" class="rounded-circle mb-3" width="100" height="100" style="object-fit:cover;border:4px solid var(--primary-light)">
<h5>{{ $employee->name }}</h5>
<p class="text-muted">{{ $employee->designation }}</p>
<span class="badge" style="background:{{ $employee->department?->color }}20;color:{{ $employee->department?->color }}">{{ $employee->department?->name }}</span>
<hr>
<div class="row g-2 text-center mt-1">
<div class="col-4"><div class="fw-bold fs-5 text-primary">{{ $stats['taskCompletion'] }}%</div><div class="text-muted small">Tasks</div></div>
<div class="col-4"><div class="fw-bold fs-5 text-warning">{{ $stats['avgKpi'] }}%</div><div class="text-muted small">Avg KPI</div></div>
<div class="col-4"><div class="fw-bold fs-5 text-success">{{ $employee->achievements->count() }}</div><div class="text-muted small">Badges</div></div>
</div>
</div></div>

<div class="card mb-4"><div class="card-header">Info</div><div class="card-body">
<table class="table table-sm mb-0">
<tr><td class="text-muted">Employee ID</td><td>{{ $employee->employee_id }}</td></tr>
<tr><td class="text-muted">Email</td><td>{{ $employee->email }}</td></tr>
<tr><td class="text-muted">Phone</td><td>{{ $employee->phone ?? '—' }}</td></tr>
<tr><td class="text-muted">Joined</td><td>{{ $employee->joining_date?->format('d M Y') }}</td></tr>
<tr><td class="text-muted">Status</td><td><span class="status-badge status-{{ $employee->status }}">{{ $employee->status }}</span></td></tr>
</table>
</div></div>

@if($employee->achievements->count())
<div class="card"><div class="card-header">Achievements</div><div class="card-body">
<div class="d-flex flex-wrap gap-2">
@foreach($employee->achievements as $a)
<div class="text-center" title="{{ $a->description }}">
<div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-1" style="width:42px;height:42px;background:{{ $a->badge_color }}20"><i class="{{ $a->badge_icon }}" style="color:{{ $a->badge_color }};font-size:1.2rem"></i></div>
<div style="font-size:.65rem;color:var(--text-muted)">{{ $a->title }}</div>
</div>
@endforeach
</div>
</div></div>
@endif
</div>

<div class="col-lg-8">
<div class="card mb-4"><div class="card-header"><span>Performance Overview</span></div>
<div class="card-body"><div id="empKpiChart"></div></div></div>

<div class="card"><div class="card-header">Recent KPIs</div>
<div class="card-body p-0">
@foreach($employee->kpis->take(5) as $kpi)
<div class="d-flex align-items-center gap-3 p-3 border-bottom">
<div class="flex-grow-1">
<div class="small fw-semibold">{{ $kpi->title }}</div>
<div class="text-muted" style="font-size:.72rem">{{ date('F',mktime(0,0,0,$kpi->month,1)) }} {{ $kpi->year }}</div>
<div class="progress mt-1"><div class="progress-bar {{ $kpi->score>=75?'bg-success':($kpi->score>=50?'bg-warning':'bg-danger') }}" data-value="{{ $kpi->score }}" style="width:0%"></div></div>
</div>
<div class="text-end"><div class="fw-bold">{{ $kpi->score }}%</div><span class="status-badge status-{{ $kpi->status }}">{{ $kpi->status }}</span></div>
</div>
@endforeach
</div></div>
</div>
</div>
@endsection
@push('scripts')
<script>
fetch("{{ route('kpi.chart',$employee) }}")
.then(r=>r.json())
.then(d => {
    if(!d.length) return;
    const months=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    OrgCharts.lineChart(document.getElementById('empKpiChart'),[{name:'KPI Score',data:d.map(x=>x.score)}],d.map(x=>months[x.month-1]));
});
</script>
@endpush
