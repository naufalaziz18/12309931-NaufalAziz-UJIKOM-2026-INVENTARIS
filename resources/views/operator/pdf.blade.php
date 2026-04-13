<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #4f46e5; color: white; padding: 10px; border: 1px solid #000; text-transform: uppercase; }
        td { padding: 8px; border: 1px solid #000; }
        .text-center { text-align: center; }
        .status-back { color: #059669; font-weight: bold; }
        .status-borrow { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;">REKAPITULASI PEMINJAMAN BARANG</h2>
        <p>Dicetak pada: {{ date('d/m/Y H:i') }} | Operator: {{ auth()->user()->name }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>NAMA PEMINJAM</th>
                <th>NAMA BARANG</th>
                <th>JUMLAH</th>
                <th>TGL PINJAM</th>
                <th>BATAS KEMBALI</th>
                <th>KETERANGAN</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($borrows as $index => $b)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $b->borrower_name }}</td>
                <td>{{ $b->product->name ?? 'N/A' }}</td>
                <td class="text-center">{{ $b->quantity }} Unit</td>
                <td>{{ \Carbon\Carbon::parse($b->created_at)->format('d/m/Y H:i') }}</td>
                <td>{{ $b->return_date ? \Carbon\Carbon::parse($b->return_date)->format('d/m/Y') : '-' }}</td>
                <td>{{ $b->description ?? '-' }}</td>
                <td class="text-center">
                    @if($b->status == 'dikembalikan')
                        <span class="status-back">SUDAH KEMBALI</span>
                    @else
                        <span class="status-borrow">MASIH DIPINJAM</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Belum ada data transaksi peminjaman.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>