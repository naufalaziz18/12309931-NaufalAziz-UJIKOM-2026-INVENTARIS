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

        /* Header Tabel warna biru/ungu sesuai Excel */
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
    </style>
</head>

<body>
    <div class="header">
        <h2>LAPORAN DETAIL PEMINJAMAN BARANG</h2>
        <p>Item: <strong>{{ $product->name }}</strong> | Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>NAMA PEMINJAM</th>
                <th>NAMA BARANG</th>
                <th>JUMLAH</th>
                <th>TANGGAL PINJAM</th>
                <th>BATAS KEMBALI</th>
                <th>KETERANGAN</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($product->borrows as $index => $borrow)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>

                    {{-- NAMA PEMINJAM: Di array lu ada field 'borrower_name' --}}
                    <td>{{ $borrow->borrower_name ?? 'N/A' }}</td>

                    {{-- NAMA BARANG --}}
                    <td>{{ $product->name }}</td>

                    {{-- JUMLAH: Nama kolomnya 'quantity' --}}
                    <td class="text-center">{{ $borrow->quantity }} Unit</td>

                    {{-- TANGGAL PINJAM --}}
                    <td>{{ \Carbon\Carbon::parse($borrow->created_at)->format('d/m/Y H:i') }}</td>

                    {{-- BATAS KEMBALI: Nama kolomnya 'return_date' --}}
                    <td>{{ $borrow->return_date ? \Carbon\Carbon::parse($borrow->return_date)->format('d/m/Y') : '-' }}</td>

                    {{-- KETERANGAN: Nama kolomnya 'description' --}}
                    <td>{{ $borrow->description ?? '-' }}</td>

                    {{-- STATUS: Isinya 'dikembalikan' --}}
                    <td class="text-center">
                        @if($borrow->status == 'dikembalikan')
                            SUDAH KEMBALI
                        @else
                            MASIH DIPINJAM
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