@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Reports</h1>
    <a href="{{ route('generate.pdf') }}" class="btn btn-primary mb-3">Generate Report</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr>
                    <td>{{ $report->title }}</td>
                    <td>{{ $report->description }}</td>
                    <td>{{ ucfirst($report->status) }}</td>
                    <td>
                        @if ($report->file_path)
                            <a href="{{ route('reports.download', $report) }}" class="btn btn-sm btn-success">Download</a>
                        @endif
                        <a href="{{ route('reports.show', $report) }}" class="btn btn-sm btn-info">View</a>
                        @if ($report->status === 'active')
                            <form action="{{ route('reports.archive', $report) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-warning">Archive</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
