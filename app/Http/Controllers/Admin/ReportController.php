<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with(['driver', 'passenger'])->latest()->get();

        return view('admin.reports.index', compact('reports'));
    }
}
