<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Data Anggota</title>
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
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Foto</th>
                <th width="15%">No Anggota</th>
                <th width="20%">Nama Lengkap</th>
                <th width="20%">Pekerjaan/Instansi</th>
                <th width="15%">Tanggal</th>
                <th width="15%">Alamat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($anggota as $d)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">
                    @if($d->foto)
                        {{ $d->foto }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">{{ $d->id_anggota }}</td>
                <td>{{ $d->nama }}</td>
                <td>{{ ($d->pekerjaan ?? '-') . ($d->instansi ? '/' . $d->instansi : '') }}</td>
                <td>
                    {{ $d->tanggal_daftar ? \Carbon\Carbon::parse($d->tanggal_daftar)->translatedFormat('d-M-y') : '-' }}
                </td>
                <td>{{ $d->alamat ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Data tidak ditemukan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>