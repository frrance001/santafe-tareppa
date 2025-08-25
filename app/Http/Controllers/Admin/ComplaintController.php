<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;

class ComplaintController extends Controller
{
    public function index()
{
    $reports = Report::with(['driver', 'passenger'])->latest()->get();
    return view('admin.reports.index', compact('reports'));
}

    public function show($id)
    {
        $complaint = Complaint::with('user')->findOrFail($id);
        return view('admin.report-show', compact('complaint'));
    }

    public function resolve($id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->status = 'resolved';
        $complaint->save();

        return redirect()->back()->with('success', 'Complaint marked as resolved.');
    }
}
