@extends('layouts.app')
@section('title', 'My Dashboard')
@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">
            @if($user->department)
            <span style="color:{{ $user->department->color }}"><i class="{{ $user->department->icon }}"></i></span>
            @endif
            Welcome, {{ $user->name }}
        </h1>
        <p class="page-subtitle">{{ $user->department?->name ?? 'No Department' }} &middot; {{ now()->format('l, d M Y') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('plans.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New Plan</a>
        <a href="{{ route('work-logs.index') }}" class="btn btn-outline-secondary"><i class="bi bi-journal-text me-1"></i>Work Log</a>
    </div>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary"><i class="bi bi-hourglass-split"></i></div>
        <div>
            <div class="stat-value">{{ $data['myPendingTasks'] }}</div>
            <div class="stat-label">Pending Tasks</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon success"><i class="bi bi-check-circle"></i></div>
        <div>
            <div class="stat-value">{{ $data['myCompletedTasks'] }}</div>
            <div class="stat-label">Completed Tasks</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon danger"><i class="bi bi-exclamation-triangle"></i></div>
        <div>
            <div class="stat-value">{{ $data['myOverdueTasks'] }}</div>
            <div class="stat-label">Overdue Tasks</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon warning"><i class="bi bi-bullseye"></i></div>
        <div>
            <div class="stat-value">{{ $data['myKpiScore'] }}%</div>
            <div class="stat-label">KPI Score (This Month)</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon info"><i class="bi bi-graph-up"></i></div>
        <div>
            <div class="stat-value">{{ $data['taskCompletionRate'] }}%</div>
            <div class="stat-label">Task Completion Rate</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon primary"><i class="bi bi-envelope"></i></div>
        <div>
            <div class="stat-value">{{ $data['unreadMessages'] }}</div>
            <div class="stat-label">Unread Messages</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Monthly Progress Chart -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <span><i class="bi bi-bar-chart me-2 text-primary"></i>Monthly Task Progress ({{ now()->year }})</span>
            </div>
            <div class="card-body">
                <div id="monthlyChart"></div>
            </div>
        </div>
    </div>

    <!-- KPI Gauge -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header"><span><i class="bi bi-bullseye me-2 text-warning"></i>My KPI Score</span></div>
            <div class="card-body text-center">
                <div id="kpiGauge"></div>
                <p class="text-muted small mt-2">{{ now()->format('F Y') }}</p>
                <a href="{{ route('kpi.index') }}" class="btn btn-outline-primary btn-sm mt-2">View All KPIs</a>
            </div>
        </div>
    </div>

    <!-- Upcoming Deadlines -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <span><i class="bi bi-calendar-event me-2 text-danger"></i>Upcoming Deadlines (7 Days)</span>
                <a href="{{ route('plans.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                @forelse($data['upcomingDeadlines'] as $task)
                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                    <div class="flex-shrink-0">
                        <span class="priority-badge priority-{{ $task->priority }}">{{ $task->priority }}</span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold small">{{ $task->title }}</div>
                        <div class="text-muted" style="font-size:.75rem">
                            <i class="bi bi-calendar me-1"></i>Due: {{ $task->due_date?->format('d M Y') }}
                            @if($task->plan)<span class="ms-2"><i class="bi bi-kanban me-1"></i>{{ $task->plan->title }}</span>@endif
                        </div>
                    </div>
                    <div>
                        <span class="status-badge status-{{ $task->status }}">{{ str_replace('_',' ',$task->status) }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted p-4">
                    <i class="bi bi-calendar-check fs-2 d-block mb-2"></i>No upcoming deadlines. Great job!
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Plans -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <span><i class="bi bi-kanban me-2 text-primary"></i>Recent Department Plans</span>
                <a href="{{ route('plans.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                @forelse($data['recentPlans'] as $plan)
                <div class="p-3 border-bottom">
                    <div class="d-flex align-items-start justify-content-between mb-1">
                        <a href="{{ route('plans.show',$plan) }}" class="fw-semibold small text-decoration-none text-primary">{{ $plan->title }}</a>
                        <span class="status-badge status-{{ $plan->approval_status }}">{{ $plan->approval_status }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-3 text-muted" style="font-size:.75rem">
                        <span><i class="bi bi-person me-1"></i>{{ $plan->creator?->name }}</span>
                        @if($plan->due_date)<span><i class="bi bi-calendar me-1"></i>{{ $plan->due_date->format('d M') }}</span>@endif
                    </div>
                    <div class="mt-2">
                        <div class="progress" style="height:4px">
                            <div class="progress-bar bg-primary" data-value="{{ $plan->progress }}" style="width:0%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted p-4">
                    <i class="bi bi-kanban fs-2 d-block mb-2"></i>No plans yet.
                    <br><a href="{{ route('plans.create') }}" class="btn btn-sm btn-primary mt-2">Create First Plan</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Monthly chart
const monthlyData = @json($data['monthlyProgress']);
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const cats   = monthlyData.map(d => months[d.month-1]);
OrgCharts.barChart(document.getElementById('monthlyChart'), [
    { name: 'Total Tasks', data: monthlyData.map(d => d.total) },
    { name: 'Completed',   data: monthlyData.map(d => d.completed) }
], cats);

// KPI Gauge
OrgCharts.kpiGauge(document.getElementById('kpiGauge'), {{ $data['myKpiScore'] }});
</script>
@endpush
