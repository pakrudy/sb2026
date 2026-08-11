<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Picqer\Barcode\BarcodeGeneratorSVG;

class PrintController extends Controller
{
    public function resi(int $id)
    {
        $barang = Barang::with(['cabangAsal', 'cabangTujuan'])->findOrFail($id);

        $generator = new BarcodeGeneratorSVG();
        $barcodeSvg = $generator->getBarcode($barang->kode_barang, $generator::TYPE_CODE_128, 2, 60);

        return view('print.resi', compact('barang', 'barcodeSvg'));
    }
}
