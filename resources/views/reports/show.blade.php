@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $report->title }}</h1>
    <p><strong>Description:</strong> {{ $report->description }}</p>
    <p><strong>Status:</strong> {{ ucfirst($report->status) }}</p>

    @if ($report->file_path)
        <div class="embed-responsive embed-responsive-16by9 mb-3">
            <iframe
                src="{{ Storage::url($report->file_path) }}"
                style="width: 100%; height: 600px; border: none;"
                allowfullscreen>
            </iframe>
        </div>
        <a href="{{ route('reports.download', $report) }}" class="btn btn-primary">Download Report</a>
    @endif
    <a href="{{ route('reports.index') }}" class="btn btn-secondary">Back to Reports</a>
</div>
@endsection
