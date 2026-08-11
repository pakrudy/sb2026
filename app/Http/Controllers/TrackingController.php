<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $resi = trim($request->input('resi'));
        $barang = null;

        if ($resi) {
            $barang = Barang::with(['cabangAsal', 'cabangTujuan', 'box', 'riwayatStatus'])
                ->where('kode_barang', $resi)
                ->orWhere('no_wa_penerima', 'LIKE', '%' . $resi . '%')
                ->first();
        }

        return view('public.tracking', compact('barang', 'resi'));
    }
}
