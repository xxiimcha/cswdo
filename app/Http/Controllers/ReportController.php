<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::all();
        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('reports');
        }

        Report::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'status' => 'active',
        ]);

        return redirect()->route('reports.index')->with('success', 'Report created successfully!');
    }

    public function archive(Report $report)
    {
        $report->update(['status' => 'archived']);
        return redirect()->route('reports.index')->with('success', 'Report archived successfully!');
    }

    public function download(Report $report)
    {
        if ($report->file_path && Storage::exists($report->file_path)) {
            return Storage::download($report->file_path);
        }

        return redirect()->route('reports.index')->with('error', 'File not found.');
    }
}
