@extends('layouts.app')
@section('title','Plans')
@section('content')
<div class="page-header">
    <div><h1 class="page-title"><i class="bi bi-kanban text-primary me-2"></i>Plans</h1></div>
    <a href="{{ route('plans.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New Plan</a>
</div>
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="Search plans..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><select name="status" class="form-select"><option value="">All Status</option>@foreach(['pending','in_progress','completed','delayed','cancelled'] as $s)<option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>@endforeach</select></div>
            <div class="col-md-2"><select name="priority" class="form-select"><option value="">All Priority</option>@foreach(['low','medium','high','critical'] as $p)<option value="{{ $p }}" {{ request('priority')==$p?'selected':'' }}>{{ ucfirst($p) }}</option>@endforeach</select></div>
            @if(auth()->user()->is_management)
            <div class="col-md-2"><select name="department" class="form-select"><option value="">All Depts</option>@foreach($departments as $d)<option value="{{ $d->id }}" {{ request('department')==$d->id?'selected':'' }}>{{ $d->name }}</option>@endforeach</select></div>
            @endif
            <div class="col-md-2"><button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-search me-1"></i>Filter</button></div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table data-table mb-0">
            <thead><tr><th>Title</th><th>Department</th><th>Priority</th><th>Status</th><th>Approval</th><th>Progress</th><th>Due Date</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($plans as $plan)
            <tr>
                <td><a href="{{ route('plans.show',$plan) }}" class="text-decoration-none fw-semibold">{{ $plan->title }}</a><br><small class="text-muted">By {{ $plan->creator?->name }}</small></td>
                <td><span style="color:{{ $plan->department?->color }}"><i class="{{ $plan->department?->icon }}"></i></span> {{ $plan->department?->name }}</td>
                <td><span class="priority-badge priority-{{ $plan->priority }}">{{ $plan->priority }}</span></td>
                <td><span class="status-badge status-{{ $plan->status }}">{{ str_replace('_',' ',$plan->status) }}</span></td>
                <td><span class="status-badge status-{{ $plan->approval_status }}">{{ $plan->approval_status }}</span></td>
                <td style="min-width:100px"><div class="progress"><div class="progress-bar bg-primary" data-value="{{ $plan->progress }}" style="width:0%"></div></div><small>{{ $plan->progress }}%</small></td>
                <td><small>{{ $plan->due_date?->format('d M Y') ?? '—' }}</small></td>
                <td>
                    <a href="{{ route('plans.show',$plan) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('plans.edit',$plan) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted py-5"><i class="bi bi-kanban fs-2 d-block mb-2"></i>No plans found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $plans->withQueryString()->links() }}</div>
@endsection
