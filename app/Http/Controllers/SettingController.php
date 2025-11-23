<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $query = Setting::query();

        // Filter tanggal laporan
        if ($request->filled('tanggal_laporan')) {
            $query->whereDate('tanggal_laporan', $request->tanggal_laporan);
        }

        // Ambil data laporan
        $laporan = $query->orderBy('tanggal_laporan', 'asc')->get();

        return view('settings.laporan', compact('laporan'));
    }
}
