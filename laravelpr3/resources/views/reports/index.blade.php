<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>All Reports</title></head>
<body>
<h1>Reports</h1>

@foreach($reports as $report)
    <div style="border:1px solid #ccc;margin:8px;padding:8px">
        <h3><a href="{{ route('reports.show', $report) }}">{{ $report->title }}</a></h3>
        <p>{{ Str::limit($report->description, 150) }}</p>
        @if($report->photo_path)
            <img src="{{ asset('storage/'.$report->photo_path) }}" alt="" style="max-width:200px;">
        @endif
        <p>Location: {{ $report->latitude ?? '—' }}, {{ $report->longitude ?? '—' }}</p>
        <p>Status: {{ $report->status }}</p>
    </div>
@endforeach

{{ $reports->links() }}
</body>
</html>
