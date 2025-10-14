<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak kategori Kategori</title>
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
        }

        .table tbody td {
            padding: 8px;
            vertical-align: middle;
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

        .kode-kategori {
            background-color: #f8f9fa;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            border: 1px solid #dee2e6;
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

            /* Pastikan warna background tetap muncul saat print */
            .thead-dark th {
                background-color: #343a40 !important;
                color: white !important;
            }

            .kode-kategori {
                background-color: #f8f9fa !important;
                border: 1px solid #dee2e6 !important;
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
                max-width: 900px;
                margin: 0 auto;
            }
        }
    </style>
</head>

<body onload="window.print(); window.onafterprint = function() { window.close(); }">
    <div class="container-print">
        <!-- Header -->
        <div class="header-print">
            <h2>Master Kategori Buku</h2>
            <p>Daftar Kategori Perpustakaan</p>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center" width="8%">No</th>
                        <th class="text-center" width="12%">Kode</th>
                        <th class="text-center" width="25%">Nama Kategori</th>
                        <th class="text-center" width="55%">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategori as $d)
                    <tr>
                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                        <td class="text-center align-middle">
                            <span class="kode-kategori">{{ $d->kode }}</span>
                        </td>
                        <td class="align-middle"><strong>{{ $d->nama_kategori }}</strong></td>
                        <td class="align-middle">{{ $d->deskripsi ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">Tidak ada kategori kategori</td>
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
                    <p style="margin: 5px 0 0 0;">Total Kategori: <strong>{{ $kategori->count() }}</strong> kategori</p>
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