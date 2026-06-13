@extends('layouts.app')
@section('title', $kpi->title)
@section('content')
<div class="page-header">
    <div><h1 class="page-title">{{ $kpi->title }}</h1><p class="page-subtitle">{{ $kpi->user?->name }} &middot; {{ date('F',mktime(0,0,0,$kpi->month,1)) }} {{ $kpi->year }}</p></div>
    <a href="{{ route('kpi.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<div class="row g-4">
<div class="col-lg-4 text-center">
<div class="card"><div class="card-body py-5">
<div id="kpiDetailGauge"></div>
<h3 class="mt-2">{{ $kpi->score }}%</h3>
<p class="text-muted">Achievement Score</p>
<span class="status-badge status-{{ $kpi->status }} fs-6">{{ $kpi->status }}</span>
@if(auth()->user()->is_management && $kpi->status !== 'approved')
<div class="mt-3"><form method="POST" action="{{ route('kpi.approve',$kpi) }}">@csrf<button class="btn btn-success">Approve KPI</button></form></div>
@endif
</div></div>
</div>
<div class="col-lg-8">
<div class="card"><div class="card-body">
<table class="table table-borderless">
<tr><td class="text-muted">Employee</td><td class="fw-semibold">{{ $kpi->user?->name }}</td></tr>
<tr><td class="text-muted">Department</td><td>{{ $kpi->department?->name }}</td></tr>
<tr><td class="text-muted">Target</td><td>{{ $kpi->target }} {{ $kpi->metric_unit }}</td></tr>
<tr><td class="text-muted">Achieved</td><td class="fw-bold text-success">{{ $kpi->achieved }} {{ $kpi->metric_unit }}</td></tr>
<tr><td class="text-muted">Evaluated By</td><td>{{ $kpi->evaluator?->name }}</td></tr>
<tr><td class="text-muted">Period</td><td>{{ date('F',mktime(0,0,0,$kpi->month,1)) }} {{ $kpi->year }}</td></tr>
</table>
@if($kpi->remarks)<div class="alert alert-light"><strong>Remarks:</strong> {{ $kpi->remarks }}</div>@endif
</div></div>
</div>
</div>
@endsection
@push('scripts')
<script>OrgCharts.kpiGauge(document.getElementById('kpiDetailGauge'), {{ $kpi->score }}, '{{ $kpi->score >= 75 ? "#10b981" : ($kpi->score >= 50 ? "#f59e0b" : "#ef4444") }}');</script>
@endpush
