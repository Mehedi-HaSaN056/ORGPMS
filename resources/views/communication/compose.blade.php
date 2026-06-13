@extends('layouts.app')
@section('title','Compose Message')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Compose Message</h1></div>
    <a href="{{ route('messages.inbox') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<div class="row justify-content-center"><div class="col-lg-8"><div class="card"><div class="card-body">
<form method="POST" action="{{ route('messages.send') }}" enctype="multipart/form-data">@csrf
<div class="mb-3"><label class="form-label">Type</label><select name="type" class="form-select" id="msgType" onchange="toggleFields()"><option value="direct">Direct Message</option><option value="department">Department Message</option>@can('broadcast messages')<option value="broadcast">Broadcast (All)</option>@endcan</select></div>
<div id="recipientField" class="mb-3"><label class="form-label">To *</label><select name="receiver_id" class="form-select"><option value="">— Select Recipient —</option>@foreach($recipients as $r)<option value="{{ $r->id }}">{{ $r->name }} ({{ $r->department?->name }})</option>@endforeach</select></div>
<div id="deptField" class="mb-3" style="display:none"><label class="form-label">Department</label><select name="department_id" class="form-select"><option value="">— Select Department —</option>@foreach($departments as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach</select></div>
<div class="mb-3"><label class="form-label">Subject</label><input type="text" name="subject" class="form-control"></div>
<div class="mb-3"><label class="form-label">Priority</label><select name="priority" class="form-select"><option value="normal">Normal</option><option value="high">High</option><option value="urgent">Urgent</option></select></div>
<div class="mb-3"><label class="form-label">Message *</label><textarea name="body" class="form-control" rows="8" required></textarea></div>
<div class="mb-3"><label class="form-label">Attachment</label><input type="file" name="attachment" class="form-control"></div>
<div class="d-flex gap-2 justify-content-end"><a href="{{ route('messages.inbox') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Send</button></div>
</form>
</div></div></div></div>
<script>function toggleFields(){const t=document.getElementById('msgType').value;document.getElementById('recipientField').style.display=t==='direct'?'block':'none';document.getElementById('deptField').style.display=t==='department'?'block':'none';}</script>
@endsection
