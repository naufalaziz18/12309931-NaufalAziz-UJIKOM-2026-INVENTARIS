<!DOCTYPE html>
<html>

<head>
    <title>Laporan Kategori Inventory</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th {
            background-color: #2E2EFE;
            /* Biru selaras Excel lo */
            color: white;
            padding: 10px;
            text-transform: uppercase;
        }

        td {
            padding: 8px;
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: right;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>LAPORAN DATA KATEGORI INVENTORY</h2>
        {{-- Tambahkan format timezone Jakarta --}}
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th>NAMA KATEGORI</th>
                <th>PJ DIVISI</th>
                <th width="20%">TOTAL PRODUK</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $index => $category)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ strtoupper($category->name) }}</td>
                    <td>{{ strtoupper($category->division_pj ?? '-') }}</td>
                    <td class="text-center">{{ $category->products_count }} Items</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Sistem Inventory - Dicetak oleh {{ auth()->user()->name }}
    </div>
</body>

</html>