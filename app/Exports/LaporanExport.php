<?php

namespace App\Exports;

use App\Models\Laporan;
use App\Models\LaporanDetail;
use App\Models\LaporanStok;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class LaporanExport implements FromView
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $laporan = Laporan::where('id_laporan', $this->id)->firstOrFail();
        $detail  = LaporanDetail::where('laporan_id', $this->id)->get();
        $stok    = LaporanStok::where('laporan_id', $this->id)->get();

        return view('settings.laporan_excel', compact('laporan', 'detail', 'stok'));
    }
}
