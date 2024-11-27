@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Report</h1>

    <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">Report Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="file">Upload Report (optional)</label>
            <input type="file" name="file" id="file" class="form-control-file">
        </div>

        <button type="submit" class="btn btn-primary">Save Report</button>
    </form>
</div>
@endsection
