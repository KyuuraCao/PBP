<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Data Kategori Buku</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
            font-size: 11pt;
        }
        th {
            background-color: #343a40;
            color: white;
            font-weight: bold;
            border: 1px solid #000000;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }
        td {
            border: 1px solid #000000;
            padding: 6px 8px;
            vertical-align: middle;
        }
        .text-center {
            text-align: center;
        }
        .bg-dark {
            background-color: #343a40;
        }
        .kode-cell {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }
        /* Untuk memastikan warna tercetak di Excel */
        thead th {
            mso-pattern: black none;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th width="8%">No</th>
                <th width="12%">Kode Kategori</th>
                <th width="25%">Nama Kategori</th>
                <th width="55%">Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kategori as $d)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="kode-cell">{{ $d->kode }}</td>
                <td><strong>{{ $d->nama_kategori }}</strong></td>
                <td>{{ $d->deskripsi ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Data tidak ditemukan</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right; font-weight: bold; background-color: #f8f9fa;">Total Kategori:</td>
                <td class="text-center" style="font-weight: bold; background-color: #f8f9fa;">{{ $kategori->count() }} kategori</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>