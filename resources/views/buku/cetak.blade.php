<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Buku</title>
    <link rel="stylesheet" 
    href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" 
    integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" 
    crossorigin="anonymous">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header-print {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #333;
        }

        .header-print h2 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header-print p {
            margin: 5px 0 0 0;
            font-size: 14px;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
        }

        .table thead th {
            vertical-align: middle;
            font-weight: bold;
            background-color: #343a40 !important;
            color: white !important;
            padding: 10px 8px;
            font-size: 11px;
        }

        .table tbody td {
            padding: 8px;
            vertical-align: middle;
            font-size: 11px;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .text-center {
            text-align: center;
        }

        .align-middle {
            vertical-align: middle !important;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 10px;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 3px;
        }

        .badge-success {
            color: #fff;
            background-color: #28a745;
        }

        .badge-secondary {
            color: #fff;
            background-color: #6c757d;
        }

        .badge-warning {
            color: #212529;
            background-color: #ffc107;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 15mm 10mm;
            }

            body {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .table {
                page-break-inside: auto;
            }

            .table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            .table thead {
                display: table-header-group;
            }

            .table tfoot {
                display: table-footer-group;
            }

            .header-print {
                page-break-after: avoid;
            }

            .thead-dark th {
                background-color: #343a40 !important;
                color: white !important;
            }

            .badge-success {
                background-color: #28a745 !important;
                color: white !important;
            }

            .badge-secondary {
                background-color: #6c757d !important;
                color: white !important;
            }

            .badge-warning {
                background-color: #ffc107 !important;
                color: #212529 !important;
            }
        }

        @media screen {
            body {
                padding: 20px;
                background-color: #f8f9fa;
            }

            .container-print {
                background: white;
                padding: 30px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                max-width: 1000px;
                margin: 0 auto;
            }
        }
    </style>
</head>

<body onload="window.print(); window.onafterprint = function() { window.close(); }">
    <div class="container-print">
        <!-- Header -->
        <div class="header-print">
            <h2>Daftar Buku Perpustakaan</h2>
            <p>Laporan Data Koleksi Buku</p>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th class="text-center" width="12%">Kode Buku</th>
                        <th class="text-center" width="25%">Judul Buku</th>
                        <th class="text-center" width="18%">Pengarang</th>
                        <th class="text-center" width="15%">Penerbit</th>
                        <th class="text-center" width="10%">Tahun</th>
                        <th class="text-center" width="8%">Stok</th>
                        <th class="text-center" width="10%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buku as $d)
                    <tr>
                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                        <td class="text-center align-middle">{{ $d->kode_buku }}</td>
                        <td class="align-middle">{{ $d->judul_buku }}</td>
                        <td class="align-middle">{{ $d->pengarang ?? '-' }}</td>
                        <td class="align-middle">{{ $d->penerbit ?? '-' }}</td>
                        <td class="text-center align-middle">{{ $d->tahun_terbit ?? '-' }}</td>
                        <td class="text-center align-middle">{{ $d->stok ?? '0' }}</td>
                        <td class="text-center align-middle">
                            <span class="badge badge-{{ $d->status == 'Ada' ? 'success' : ($d->status == 'Dipinjam' ? 'warning' : 'secondary') }}">
                                {{ $d->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data buku</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div style="margin-top: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div style="font-size: 11px; color: #6c757d;">
                    <p style="margin: 0;">Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</p>
                    <p style="margin: 5px 0 0 0;">Total Buku: <strong>{{ $buku->count() }}</strong> judul</p>
                </div>
                
                <div style="text-align: center; min-width: 200px;">
                    <p style="margin: 0 0 60px 0;">Petugas Perpustakaan,</p>
                    <p style="margin: 0; border-top: 1px solid #000; padding-top: 5px; display: inline-block; min-width: 150px;">
                        (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>