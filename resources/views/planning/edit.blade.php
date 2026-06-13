@extends('layouts.app')
@section('title','Edit Plan')
@section('content')
<div class="page-header"><div><h1 class="page-title">Edit Plan</h1></div><a href="{{ route('plans.show',$plan) }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a></div>
<div class="row justify-content-center"><div class="col-lg-8"><div class="card"><div class="card-body">
<form method="POST" action="{{ route('plans.update',$plan) }}">@csrf @method('PUT')
<div class="mb-3"><label class="form-label">Title *</label><input type="text" name="title" class="form-control" value="{{ $plan->title }}" required></div>
<div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4">{{ $plan->description }}</textarea></div>
<div class="row g-3 mb-3">
<div class="col-md-6"><label class="form-label">Assigned To</label><select name="assigned_to" class="form-select"><option value="">—</option>@foreach($employees as $e)<option value="{{ $e->id }}" {{ $plan->assigned_to==$e->id?'selected':'' }}>{{ $e->name }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label">Priority</label><select name="priority" class="form-select">@foreach(['low','medium','high','critical'] as $p)<option value="{{ $p }}" {{ $plan->priority==$p?'selected':'' }}>{{ ucfirst($p) }}</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select">@foreach(['pending','in_progress','completed','delayed','cancelled'] as $s)<option value="{{ $s }}" {{ $plan->status==$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label">Progress (%)</label><input type="range" name="progress" class="form-range mt-2" min="0" max="100" value="{{ $plan->progress }}" id="progressRange" oninput="document.getElementById('progressVal').textContent=this.value+'%'"><span id="progressVal" class="fw-bold text-primary">{{ $plan->progress }}%</span></div>
<div class="col-md-4"><label class="form-label">Due Date</label><input type="date" name="due_date" class="form-control" value="{{ $plan->due_date?->format('Y-m-d') }}"></div>
</div>
<div class="d-flex gap-2 justify-content-end"><a href="{{ route('plans.show',$plan) }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary">Update Plan</button></div>
</form>
</div></div></div></div>
@endsection
