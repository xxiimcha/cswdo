@extends('layouts.app')

@section('content')

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Reports</h1>
        </div>

        <a href="{{ route('generate.pdf') }}" class="btn btn-primary mb-3">Generate Report</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="reportTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="all-reports-tab" data-bs-toggle="tab" href="#all-reports" role="tab" aria-controls="all-reports" aria-selected="true">All Reports</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="active-reports-tab" data-bs-toggle="tab" href="#active-reports" role="tab" aria-controls="active-reports" aria-selected="false">Active Reports</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="archived-reports-tab" data-bs-toggle="tab" href="#archived-reports" role="tab" aria-controls="archived-reports" aria-selected="false">Archived Reports</a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="reportTabsContent">
            <!-- All Reports Tab -->
            <div class="tab-pane fade show active" id="all-reports" role="tabpanel" aria-labelledby="all-reports-tab">
                <div class="table-responsive">
                    <table class="table table-striped">
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
                    <div class="mt-3">
                        {{ $reports->links() }}
                    </div>
                </div>
            </div>

            <!-- Active Reports Tab -->
            <div class="tab-pane fade" id="active-reports" role="tabpanel" aria-labelledby="active-reports-tab">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activeReports as $report)
                                <tr>
                                    <td>{{ $report->title }}</td>
                                    <td>{{ $report->description }}</td>
                                    <td>{{ ucfirst($report->status) }}</td>
                                    <td>
                                        @if ($report->file_path)
                                            <a href="{{ route('reports.download', $report) }}" class="btn btn-sm btn-success">Download</a>
                                        @endif
                                        <form action="{{ route('reports.archive', $report) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-warning">Archive</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $activeReports->links() }}
                    </div>
                </div>
            </div>

            <!-- Archived Reports Tab -->
            <div class="tab-pane fade" id="archived-reports" role="tabpanel" aria-labelledby="archived-reports-tab">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($archivedReports as $report)
                                <tr>
                                    <td>{{ $report->title }}</td>
                                    <td>{{ $report->description }}</td>
                                    <td>{{ ucfirst($report->status) }}</td>
                                    <td>
                                        @if ($report->file_path)
                                            <a href="{{ route('reports.download', $report) }}" class="btn btn-sm btn-success">Download</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $archivedReports->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Optional: jQuery if you need -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

@endsection
