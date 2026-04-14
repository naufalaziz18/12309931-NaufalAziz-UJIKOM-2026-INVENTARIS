<!DOCTYPE html>
<html>

<head>
    <title>Lending Report - {{ $product->name }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            text-transform: uppercase;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #4338ca;
            color: white;
            padding: 8px;
            border: 1px solid #333;
            text-transform: uppercase;
            text-align: center;
        }

        td {
            padding: 8px;
            border: 1px solid #333;
            text-align: left;
            vertical-align: middle;
        }

        .text-center {
            text-align: center;
        }

        /* Warna untuk status */
        .status-returned {
            color: #059669;
            font-weight: bold;
        }

        .status-pending {
            color: #d97706;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>LAPORAN DETAIL PEMINJAMAN BARANG</h2>
        <p>Item: <strong>{{ $product->name }}</strong> | Tanggal Cetak: {{ date('d/m/Y H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">NO</th>
                <th>NAMA PEMINJAM</th>
                <th>JUMLAH</th>
                <th>TANGGAL PINJAM</th>
                <th>BATAS KEMBALI</th>
                <th>KETERANGAN</th>
                <th>STATUS</th>
                <th>TANGGAL KEMBALI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($product->borrows as $index => $borrow)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>

                    {{-- NAMA PEMINJAM --}}
                    <td>{{ strtoupper($borrow->borrower_name) ?? 'N/A' }}</td>

                    {{-- JUMLAH --}}
                    <td class="text-center">{{ $borrow->quantity }} Unit</td>

                    {{-- TANGGAL PINJAM --}}
                    <td>{{ \Carbon\Carbon::parse($borrow->created_at)->format('d/m/Y H:i') }}</td>

                    {{-- BATAS KEMBALI --}}
                    <td>{{ $borrow->return_date ? \Carbon\Carbon::parse($borrow->return_date)->format('d/m/Y') : '-' }}</td>

                    {{-- KETERANGAN --}}
                    <td>{{ $borrow->description ?? '-' }}</td>

                    {{-- STATUS --}}
                    <td class="text-center">
                        @if($borrow->status == 'dikembalikan')
                            <span class="status-returned">RETURNED</span>
                        @else
                            <span class="status-pending">PENDING</span>
                        @endif
                    </td>

                    {{-- TANGGAL & JAM KEMBALI --}}
                    <td class="text-center">
                        @if($borrow->status == 'dikembalikan')
                            {{-- Menggunakan actual_return_date dari controller lu --}}
                            {{ $borrow->actual_return_date ? \Carbon\Carbon::parse($borrow->actual_return_date)->format('d/m/Y H:i') : $borrow->updated_at->format('d/m/Y H:i') }} WIB
                        @else
                            <span style="color: #94a3b8;">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Data peminjaman tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>