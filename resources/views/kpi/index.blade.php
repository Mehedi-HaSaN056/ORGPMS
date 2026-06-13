@extends('layouts.app')
@section('title','KPI Tracking')
@section('content')
<div class="page-header">
    <div><h1 class="page-title"><i class="bi bi-bullseye text-warning me-2"></i>KPI Tracking</h1></div>
    @can('create kpi')<a href="{{ route('kpi.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add KPI</a>@endcan
</div>
<div class="card mb-4"><div class="card-body">
<form method="GET" class="row g-2">
    <div class="col-md-2"><input type="number" name="year" class="form-control" placeholder="Year" value="{{ request('year', now()->year) }}"></div>
    <div class="col-md-2"><select name="month" class="form-select"><option value="">All Months</option>@for($m=1;$m<=12;$m++)<option value="{{ $m }}" {{ request('month')==$m?'selected':'' }}>{{ date('F',mktime(0,0,0,$m,1)) }}</option>@endfor</select></div>
    @if(auth()->user()->is_management)
    <div class="col-md-3"><select name="department" class="form-select"><option value="">All Departments</option>@foreach($departments as $d)<option value="{{ $d->id }}" {{ request('department')==$d->id?'selected':'' }}>{{ $d->name }}</option>@endforeach</select></div>
    <div class="col-md-3"><select name="user_id" class="form-select"><option value="">All Employees</option>@foreach($employees as $e)<option value="{{ $e->id }}" {{ request('user_id')==$e->id?'selected':'' }}>{{ $e->name }}</option>@endforeach</select></div>
    @endif
    <div class="col-md-2"><button type="submit" class="btn btn-outline-primary w-100">Filter</button></div>
</form>
</div></div>
<div class="card"><div class="card-body p-0">
<table class="table data-table mb-0">
<thead><tr><th>Employee</th><th>Department</th><th>KPI Title</th><th>Target</th><th>Achieved</th><th>Score</th><th>Period</th><th>Status</th><th>Action</th></tr></thead>
<tbody>
@forelse($kpis as $kpi)
<tr>
    <td>{{ $kpi->user?->name }}</td>
    <td>{{ $kpi->department?->name }}</td>
    <td><a href="{{ route('kpi.show',$kpi) }}" class="text-decoration-none">{{ $kpi->title }}</a></td>
    <td>{{ $kpi->target }} {{ $kpi->metric_unit }}</td>
    <td>{{ $kpi->achieved }} {{ $kpi->metric_unit }}</td>
    <td>
        <div class="d-flex align-items-center gap-2">
            <div class="progress flex-grow-1"><div class="progress-bar {{ $kpi->score>=75?'bg-success':($kpi->score>=50?'bg-warning':'bg-danger') }}" data-value="{{ $kpi->score }}" style="width:0%"></div></div>
            <span class="fw-bold small">{{ $kpi->score }}%</span>
        </div>
    </td>
    <td>{{ date('M',mktime(0,0,0,$kpi->month,1)) }} {{ $kpi->year }}</td>
    <td><span class="status-badge status-{{ $kpi->status }}">{{ $kpi->status }}</span></td>
    <td>
        <a href="{{ route('kpi.show',$kpi) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
        @if(auth()->user()->is_management && $kpi->status !== 'approved')
        <form method="POST" action="{{ route('kpi.approve',$kpi) }}" class="d-inline">@csrf<button class="btn btn-sm btn-success" title="Approve"><i class="bi bi-check"></i></button></form>
        @endif
    </td>
</tr>
@empty
<tr><td colspan="9" class="text-center py-5 text-muted"><i class="bi bi-bullseye fs-2 d-block mb-2"></i>No KPIs found.</td></tr>
@endforelse
</tbody>
</table>
</div></div>
<div class="mt-3">{{ $kpis->withQueryString()->links() }}</div>
@endsection
