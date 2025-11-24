<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\LaporanDetail;
use App\Models\LaporanStok;

class SettingController extends Controller
{
    public function index()
    {
        $laporans = Laporan::orderBy('id_laporan', 'desc')->get();

        return view('settings.laporan', [
            'laporans' => $laporans
        ]);
    }

    public function show($id)
    {
        $laporan = Laporan::where('id_laporan', $id)->firstOrFail();

        $detail = LaporanDetail::where('laporan_id', $id)->get();
        $stok = LaporanStok::where('laporan_id', $id)->get();

        return view('settings.laporan_show', [
            'laporan' => $laporan,
            'detail'  => $detail,
            'stok'    => $stok
        ]);
    }

    public function print($id)
    {
        $laporan = Laporan::where('id_laporan', $id)->firstOrFail();

        $detail = LaporanDetail::where('laporan_id', $id)->get();
        $stok = LaporanStok::where('laporan_id', $id)->get();

        return view('settings.laporan_print', [
            'laporan' => $laporan,
            'detail'  => $detail,
            'stok'    => $stok
        ]);
    }
}
