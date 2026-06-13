@extends('layouts.app')
@section('title','Department Report')
@section('content')
<div class="page-header"><div><h1 class="page-title">Department Report {{ $year }}</h1></div>
<a href="{{ request()->fullUrlWithQuery(['format'=>'pdf']) }}" class="btn btn-danger"><i class="bi bi-file-pdf me-1"></i>Export PDF</a>
</div>
<div class="card"><div class="card-body p-0">
<table class="table data-table mb-0">
<thead><tr><th>Department</th><th>Employees</th><th>Total Tasks</th><th>Completed Tasks</th><th>Completion Rate</th><th>Total Plans</th><th>Approved Plans</th><th>Avg KPI</th></tr></thead>
<tbody>
@foreach($report as $row)
<tr>
<td><span class="fw-semibold" style="color:{{ $row['color'] }}">{{ $row['name'] }}</span></td>
<td>{{ $row['employees'] }}</td>
<td>{{ $row['total_tasks'] }}</td>
<td>{{ $row['completed_tasks'] }}</td>
<td><div class="progress"><div class="progress-bar bg-success" data-value="{{ $row['completion_rate'] }}" style="width:0%"></div></div>{{ $row['completion_rate'] }}%</td>
<td>{{ $row['total_plans'] }}</td>
<td>{{ $row['approved_plans'] }}</td>
<td><span class="fw-bold {{ $row['avg_kpi']>=75?'text-success':($row['avg_kpi']>=50?'text-warning':'text-danger') }}">{{ $row['avg_kpi'] }}%</span></td>
</tr>
@endforeach
</tbody>
</table>
</div></div>
@endsection
