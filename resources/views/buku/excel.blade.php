<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Data Buku Perpustakaan</title>
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
        /* Untuk memastikan warna tercetak di Excel */
        thead th {
            mso-pattern: black none;
        }
        .status-ada {
            background-color: #d4edda;
            color: #155724;
            font-weight: bold;
        }
        .status-dipinjam {
            background-color: #fff3cd;
            color: #856404;
            font-weight: bold;
        }
        .status-hilang {
            background-color: #f8d7da;
            color: #721c24;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Kode Buku</th>
                <th width="25%">Judul Buku</th>
                <th width="15%">Pengarang</th>
                <th width="15%">Penerbit</th>
                <th width="8%">Tahun Terbit</th>
                <th width="10%">ISBN</th>
                <th width="10%">Kategori</th>
                <th width="8%">Jumlah Halaman</th>
                <th width="6%">Stok</th>
                <th width="8%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($buku as $d)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ $d->kode_buku }}</td>
                <td>{{ $d->judul_buku }}</td>
                <td>{{ $d->pengarang ?? '-' }}</td>
                <td>{{ $d->penerbit ?? '-' }}</td>
                <td class="text-center">{{ $d->tahun_terbit ?? '-' }}</td>
                <td class="text-center">{{ $d->isbn ?? '-' }}</td>
                <td>{{ $d->kategori ?? '-' }}</td>
                <td class="text-center">{{ $d->jumlah_halaman ?? '-' }}</td>
                <td class="text-center">{{ $d->stok ?? '0' }}</td>
                <td class="text-center status-{{ strtolower($d->status) }}">
                    {{ $d->status }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center">Data tidak ditemukan</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9" style="text-align: right; font-weight: bold; background-color: #f8f9fa;">Total Buku:</td>
                <td class="text-center" style="font-weight: bold; background-color: #f8f9fa;">{{ $buku->sum('stok') ?? 0 }}</td>
                <td style="background-color: #f8f9fa;"></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>