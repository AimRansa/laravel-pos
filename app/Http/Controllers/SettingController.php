<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\LaporanDetail;
use App\Models\LaporanStok;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class SettingController extends Controller
{
    public function index()
    {
        $laporans = Laporan::orderBy('id_laporan', 'desc')->get();
        return view('settings.laporan', compact('laporans'));
    }

    public function show($id)
    {
        $laporan = Laporan::findOrFail($id);
        $detail  = LaporanDetail::where('laporan_id', $id)->get();
        $stok    = LaporanStok::where('laporan_id', $id)->get();

        return view('settings.laporan_show', compact('laporan','detail','stok'));
    }

    public function print($id)
    {
        $laporan = Laporan::findOrFail($id);
        $detail  = LaporanDetail::where('laporan_id', $id)->get();
        $stok    = LaporanStok::where('laporan_id', $id)->get();

        return view('settings.laporan_print', compact('laporan','detail','stok'));
    }

    public function exportPDF($id)
    {
        $laporan = Laporan::findOrFail($id);
        $detail  = LaporanDetail::where('laporan_id', $id)->get();
        $stok    = LaporanStok::where('laporan_id', $id)->get();

        $pdf = Pdf::loadView(
            'settings.laporan_print_pdf',
            compact('laporan','detail','stok')
        )->setPaper('A4', 'portrait');

        return $pdf->download("laporan-{$id}.pdf");
    }

    public function exportExcel($id)
    {
        return Excel::download(new LaporanExport($id), "laporan-{$id}.xlsx");
    }
}
