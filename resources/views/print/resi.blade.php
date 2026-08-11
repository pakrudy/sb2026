<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Resi - {{ $barang->kode_barang }}</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }
        body {
            font-family: 'Courier New', Courier, monospace, sans-serif;
            width: 76mm;
            margin: 0 auto;
            padding: 5mm;
            font-size: 11px;
            color: #000;
            background: #fff;
        }
        .header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 5px;
            margin-bottom: 8px;
        }
        .header h2 {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
        }
        .header p {
            margin: 2px 0 0 0;
            font-size: 9px;
            text-transform: uppercase;
        }
        .barcode-box {
            text-align: center;
            margin: 10px 0;
        }
        .barcode-box svg {
            max-width: 100%;
            height: 50px;
        }
        .code-title {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-top: 4px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .info-table td {
            padding: 2px 0;
            vertical-align: top;
        }
        .info-table td.label {
            width: 35%;
            font-size: 10px;
            color: #333;
        }
        .info-table td.val {
            font-weight: bold;
            font-size: 11px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        .footer {
            text-align: center;
            font-size: 9px;
            margin-top: 10px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 15px; text-align: center;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #16a34a; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
            🖨️ Cetak Resi (Thermal 80mm)
        </button>
        <button onclick="window.close()" style="padding: 8px 16px; background: #64748b; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; margin-left: 5px;">
            Tutup Window
        </button>
    </div>

    <div class="header">
        <h2>EKSPEDISI SUKSES BERSAMA</h2>
        <p>Cargo Indonesia ➔ Hong Kong</p>
    </div>

    <div class="barcode-box">
        {!! $barcodeSvg !!}
        <div class="code-title">{{ $barang->kode_barang }}</div>
    </div>

    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td class="label">Cabang Asal:</td>
            <td class="val">{{ $barang->cabangAsal->nama_cabang }} ({{ $barang->kategori_cabang_snapshot }})</td>
        </tr>
        <tr>
            <td class="label">Cabang HK:</td>
            <td class="val" style="font-size: 13px;">{{ $barang->cabangTujuan->nama_cabang }}</td>
        </tr>
        <tr>
            <td class="label">Tgl Kirim:</td>
            <td class="val">{{ $barang->tanggal_pengiriman ? $barang->tanggal_pengiriman->format('d/m/Y H:i') : date('d/m/Y') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td class="label">Penerima (HK):</td>
            <td class="val">{{ $barang->nama_penerima }}</td>
        </tr>
        <tr>
            <td class="label">No WA HK:</td>
            <td class="val">{{ $barang->no_wa_penerima }}</td>
        </tr>
        <tr>
            <td class="label">Pengirim (ID):</td>
            <td class="val">{{ $barang->nama_pengirim }} ({{ $barang->no_wa_pengirim }})</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td class="label">Berat Paket:</td>
            <td class="val" style="font-size: 14px;">{{ number_format($barang->berat_kg, 2, ',', '.') }} kg</td>
        </tr>
        <tr>
            <td class="label">Total Bayar:</td>
            <td class="val" style="font-size: 14px; font-weight: 900;">Rp {{ number_format($barang->biaya_kirim, 0, ',', '.') }}</td>
        </tr>
    </table>

    @if($barang->catatan)
    <div class="divider"></div>
    <div style="font-size: 9px; font-style: italic;">
        <strong>Catatan:</strong> {{ $barang->catatan }}
    </div>
    @endif

    <div class="footer">
        Terima kasih atas kepercayaan Anda!<br>
        Lacak status: ekspedisi-sukses-bersama.com
    </div>

</body>
</html>
