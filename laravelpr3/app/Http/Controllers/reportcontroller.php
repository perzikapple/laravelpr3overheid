<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Toon de admin pagina met alle meldingen.
     */
    public function index(Request $request)
    {
        $sort = $request->query('sort', 'desc');
        $reports = Report::orderBy('created_at', $sort)->get();

        return view('admin', compact('reports', 'sort'));
    }

    /**
     * Sla een nieuwe melding op vanuit de homepagina.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Foto opslaan (optioneel)
        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('reports', 'public');
        }

        $data['status'] = 'open';

        Report::create($data);

        return redirect()->route('bedankt');
    }

    /**
     * Update de status van een melding (in admin).
     */
    public function update($id, Request $request)
    {
        $report = Report::findOrFail($id);
        $report->status = $request->input('status');
        $report->save();

        return redirect()->route('admin');
    }

    /**
     * Verwijder een melding (in admin).
     */
    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return redirect()->route('admin');
    }
}
