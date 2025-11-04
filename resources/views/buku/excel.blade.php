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
        .status-dipinjam {
            background-color: #ffc107;
            color: #000000;
            font-weight: bold;
        }
        .status-ada {
            background-color: #28a745;
            color: #ffffff;
            font-weight: bold;
        }
        .status-hilang {
            background-color: #dc3545;
            color: #ffffff;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="10%">Kode Buku</th>
                <th width="20%">Judul Buku</th>
                <th width="12%">Pengarang</th>
                <th width="15%">Kategori</th>
                <th width="10%">Rak</th>
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
                <td>
                    @if(isset($d->kategori) && is_object($d->kategori))
                        {{ $d->kategori->nama_kategori ?? '-' }}
                    @elseif(isset($d->kategori_id))
                        {{ \App\Models\MKategori::find($d->kategori_id)->nama_kategori ?? '-' }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">
                    @if(isset($d->rak) && is_object($d->rak))
                        {{ $d->rak->kode_rak ?? '-' }}
                    @elseif(isset($d->rak_id))
                        {{ \App\Models\Rak::find($d->rak_id)->kode_rak ?? '-' }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">{{ $d->stok ?? '0' }}</td>
                <td class="text-center status-{{ strtolower($d->status ?? 'ada') }}">
                    {{ $d->status ?? 'Ada' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Data tidak ditemukan</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align: right; font-weight: bold; background-color: #f8f9fa;">Total Buku:</td>
                <td class="text-center" style="font-weight: bold; background-color: #f8f9fa;">{{ $buku->sum('stok') ?? 0 }}</td>
                <td style="background-color: #f8f9fa;"></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>