<head>
    <link rel="stylesheet" 
    href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>

<body onload="window.print(); window.onafterprint = closeWindow;">
<div class="table-responsive">
    <table class="table table-sm table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th class="text-center" width="5%">No</th>
                <th class="text-center" width="10%">Foto</th>
                <th class="text-center" width="15%">No Anggota</th>
                <th class="text-center" width="20%">Nama Lengkap</th>
                <th class="text-center" width="20%">Pekerjaan/Instansi</th>
                <th class="text-center" width="20%">Tanggal</th>
                <th class="text-center" width="20%">Alamat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($anggota as $d)
            <tr>
                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                <td class="text-center align-middle">
                    @if($d->foto)
                        <a href="#" data-toggle="modal" data-target="#viewDetail{{ $d->id_anggota }}">
                            <img src="{{ asset('uploads/foto/'.$d->foto) }}" width="50px" height="50px" alt="Foto" style="cursor: pointer; object-fit: cover; border-radius: 4px;">
                        </a>
                    @else
                        <div style="width: 50px; height: 50px; background-color: #e9ecef; display: flex; align-items: center; justify-content: center; border-radius: 4px; margin: 0 auto;">
                            <i class="fa fa-user"></i>
                        </div>
                    @endif
                </td>
                <td class="text-center align-middle">{{ $d->id_anggota }}</td>
                <td class="align-middle">{{ $d->nama }}</td>
                <td class="align-middle">{{ ($d->pekerjaan ?? '-') . ($d->instansi ? '/' . $d->instansi : '') }}</td>
                <td class="align-middle">
                    {{ $d->tanggal_daftar ? \Carbon\Carbon::parse($d->tanggal_daftar)->translatedFormat('d F Y') : '-' }}
                </td>
                <td class="align-middle">{{ $d->alamat ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Data tidak ditemukan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>


<style>
    @media print {
        @page {
            size: A4 Landscape;
            margin-top: 20mm;
            margin-bottom: 20mm;
            margin-left: 20mm;
            margin-right: 20mm;
        }

        body {
            margin: 0;
            -webkit-print-color-adjust: exact;
        }
    }
</style>

</body>