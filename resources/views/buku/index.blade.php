@extends('layout.app')

@section('title', 'Data Buku')
@section('konten')

    {{-- Temporary debug --}}
    @php
        if (isset($data) && $data->count() > 0) {
            $firstItem = $data->first();
            echo '<!-- DEBUG: First item kategori_id: ' . ($firstItem->kategori_id ?? 'NULL') . ' -->';
            echo '<!-- DEBUG: First item kategori type: ' . gettype($firstItem->kategori) . ' -->';
            echo '<!-- DEBUG: First item kategori value: ' . json_encode($firstItem->kategori) . ' -->';
            echo '<!-- DEBUG: First item rak type: ' . gettype($firstItem->rak) . ' -->';
            echo '<!-- DEBUG: First item rak value: ' . json_encode($firstItem->rak) . ' -->';
            echo '<!-- DEBUG: Relationships loaded: ' . json_encode($firstItem->getRelations()) . ' -->';

            // Debug untuk melihat struktur data
            if (isset($firstItem->kategori) && is_object($firstItem->kategori)) {
                echo '<!-- DEBUG: Kategori object properties: ' .
                    json_encode(get_object_vars($firstItem->kategori)) .
                    ' -->';
            }
        }
    @endphp

    <div class="card">
        <div class="card-body">

            @auth
                @if (auth()->user()->level === 'admin')
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tambah"
                                title="Tambah data buku">
                                <i class="fa fa-plus-square"></i> &nbsp;Tambah Data
                            </button>
                        </div>
                        <div>
                            <a href="{{ route('buku.excel', request()->all()) }}" class="btn btn-success btn-sm"
                                title="Export ke Excel">
                                <i class="fa fa-file-excel"></i> &nbsp;Export Excel
                            </a>
                            <button type="button" class="btn btn-danger btn-sm ml-2" data-toggle="modal"
                                data-target="#filterCetak" title="Cetak Data">
                                <i class="fa fa-print"></i> &nbsp;Cetak
                            </button>
                        </div>
                    </div>
                @endif
            @endauth

            <!-- Modal Filter Cetak -->
            <div class="modal fade" id="filterCetak" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">
                                <i class="fa fa-print"></i> Filter Cetak Data Buku
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <form method="GET" action="{{ route('buku.cetak') }}" target="_blank">
                            <div class="modal-body">
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i>
                                    <small>Pilih filter yang diinginkan untuk mencetak data buku. Kosongkan jika ingin
                                        mencetak semua data.</small>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Status Buku</label>
                                            <select class="form-control" name="status">
                                                <option value="">Semua Status</option>
                                                <option value="Ada" {{ request('status') == 'Ada' ? 'selected' : '' }}>
                                                    Ada</option>
                                                <option value="Dipinjam"
                                                    {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam
                                                </option>
                                                <option value="Hilang"
                                                    {{ request('status') == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Kategori</label>
                                            <select class="form-control" name="kategori_id">
                                                <option value="">Semua Kategori</option>
                                                @foreach ($kategoriList as $kat)
                                                    <option value="{{ $kat->id }}"
                                                        {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                                        {{ $kat->nama_kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Rak</label>
                                            <select class="form-control" name="rak_id">
                                                <option value="">Semua Rak</option>
                                                @foreach ($rakList as $r)
                                                    <option value="{{ $r->id }}"
                                                        {{ request('rak_id') == $r->id ? 'selected' : '' }}>
                                                        {{ $r->kode_rak }} - {{ $r->keterangan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Pencarian</label>
                                            <input type="text" class="form-control" name="search"
                                                value="{{ request('search') }}" placeholder="Judul / Kode / Pengarang...">
                                            <small class="form-text text-muted">
                                                <i class="fa fa-search"></i> Cari berdasarkan judul, kode, atau pengarang
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Tahun Terbit (Dari)</label>
                                            <input type="number" class="form-control" name="tahun_dari"
                                                value="{{ request('tahun_dari') }}" placeholder="Contoh: 2000"
                                                min="1900" max="{{ date('Y') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Tahun Terbit (Sampai)</label>
                                            <input type="number" class="form-control" name="tahun_sampai"
                                                value="{{ request('tahun_sampai') }}"
                                                placeholder="Contoh: {{ date('Y') }}" min="1900"
                                                max="{{ date('Y') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Orientasi Kertas</label>
                                            <select class="form-control" name="orientasi">
                                                <option value="portrait">Portrait (Vertikal)</option>
                                                <option value="landscape" selected>Landscape (Horizontal)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Ukuran Kertas</label>
                                            <select class="form-control" name="ukuran_kertas">
                                                <option value="A4" selected>A4</option>
                                                <option value="Letter">Letter</option>
                                                <option value="Legal">Legal</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Kolom yang Ditampilkan</label>
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="col_no" name="kolom[]" value="no" checked>
                                                                <label class="custom-control-label"
                                                                    for="col_no">No</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="col_kode" name="kolom[]" value="kode_buku"
                                                                    checked>
                                                                <label class="custom-control-label" for="col_kode">Kode
                                                                    Buku</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="col_judul" name="kolom[]" value="judul_buku"
                                                                    checked>
                                                                <label class="custom-control-label" for="col_judul">Judul
                                                                    Buku</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="col_pengarang" name="kolom[]" value="pengarang"
                                                                    checked>
                                                                <label class="custom-control-label"
                                                                    for="col_pengarang">Pengarang</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="col_penerbit" name="kolom[]" value="penerbit">
                                                                <label class="custom-control-label"
                                                                    for="col_penerbit">Penerbit</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="col_tahun" name="kolom[]" value="tahun_terbit">
                                                                <label class="custom-control-label" for="col_tahun">Tahun
                                                                    Terbit</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="col_kategori" name="kolom[]" value="kategori"
                                                                    checked>
                                                                <label class="custom-control-label"
                                                                    for="col_kategori">Kategori</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="col_rak" name="kolom[]" value="rak" checked>
                                                                <label class="custom-control-label"
                                                                    for="col_rak">Rak</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="col_stok" name="kolom[]" value="stok" checked>
                                                                <label class="custom-control-label"
                                                                    for="col_stok">Stok</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="col_status" name="kolom[]" value="status"
                                                                    checked>
                                                                <label class="custom-control-label"
                                                                    for="col_status">Status</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">
                                                <i class="fa fa-info-circle"></i> Pilih kolom yang ingin ditampilkan pada
                                                laporan cetak
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                                    <i class="fa fa-times"></i> Batal
                                </button>
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fa fa-print"></i> Cetak Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <style>
                .custom-control-label {
                    cursor: pointer;
                    user-select: none;
                }

                .custom-checkbox .custom-control-input:checked~.custom-control-label::before {
                    background-color: #dc3545;
                    border-color: #dc3545;
                }
            </style>

            {{-- Alerts --}}
            @if (session('status'))
                <div class="alert alert-{{ session('status')['icon'] == 'success' ? 'success' : 'danger' }} alert-dismissible fade show"
                    role="alert" id="autoCloseAlert">
                    <i
                        class="fa fa-{{ session('status')['icon'] == 'success' ? 'check-circle' : 'exclamation-circle' }}"></i>
                    <strong>{{ session('status')['judul'] }}!</strong> {{ session('status')['pesan'] }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="fa fa-exclamation-triangle"></i> Terdapat kesalahan input:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- FORM FILTER --}}
            <div class="card mb-3" style="background-color: #2d3338;">
                <div class="card-body">
                    <h6 class="mb-3 text-white"><i class="fa fa-filter"></i> Filter Data Buku</h6>
                    <form id="filterForm" method="GET" action="{{ route('buku.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="small text-white">Status</label>
                                    <select class="form-control form-control-sm" name="status"
                                        onchange="this.form.submit()"
                                        style="background-color: #3a4149; color: white; border-color: #4a5259;">
                                        <option value="">Semua Status</option>
                                        <option value="Ada" {{ request('status') == 'Ada' ? 'selected' : '' }}>Ada
                                        </option>
                                        <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>
                                            Dipinjam</option>
                                        <option value="Hilang" {{ request('status') == 'Hilang' ? 'selected' : '' }}>
                                            Hilang</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="small text-white">Kategori</label>
                                    <select class="form-control form-control-sm" name="kategori_id"
                                        onchange="this.form.submit()"
                                        style="background-color: #3a4149; color: white; border-color: #4a5259;">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($kategoriList as $kat)
                                            <option value="{{ $kat->id }}"
                                                {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                                {{ $kat->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="small text-white">Rak</label>
                                    <select class="form-control form-control-sm" name="rak_id"
                                        onchange="this.form.submit()"
                                        style="background-color: #3a4149; color: white; border-color: #4a5259;">
                                        <option value="">Semua Rak</option>
                                        @foreach ($rakList as $r)
                                            <option value="{{ $r->id }}"
                                                {{ request('rak_id') == $r->id ? 'selected' : '' }}>
                                                {{ $r->kode_rak }} - {{ $r->keterangan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="small text-white">Pencarian</label>
                                    <input type="text" class="form-control form-control-sm" name="search"
                                        value="{{ request('search') }}" placeholder="Judul / Kode / Pengarang..."
                                        style="background-color: #3a4149; color: white; border-color: #4a5259;">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="small text-white">Tahun Terbit</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" class="form-control form-control-sm" name="tahun_dari"
                                            value="{{ request('tahun_dari') }}" placeholder="Dari" min="1900"
                                            max="{{ date('Y') }}"
                                            style="background-color: #3a4149; color: white; border-color: #4a5259;">
                                        <div class="input-group-append input-group-prepend">
                                            <span class="input-group-text"
                                                style="background-color: #3a4149; color: white; border-color: #4a5259;">s/d</span>
                                        </div>
                                        <input type="number" class="form-control form-control-sm" name="tahun_sampai"
                                            value="{{ request('tahun_sampai') }}" placeholder="Sampai" min="1900"
                                            max="{{ date('Y') }}"
                                            style="background-color: #3a4149; color: white; border-color: #4a5259;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label class="small text-white d-block">&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-search"></i> Cari
                                </button>
                                <a href="{{ route('buku.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fa fa-undo"></i> Reset Filter
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- INFO JUMLAH DATA --}}
            <div class="mb-2">
                <small class="text-muted">
                    <i class="fa fa-info-circle"></i> Menampilkan <strong>{{ $data->count() }}</strong> data buku
                </small>
            </div>

            {{-- TABEL UTAMA --}}
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th class="text-center" width="12%">Kode Buku</th>
                            <th class="text-center" width="20%">Judul Buku</th>
                            <th class="text-center" width="15%">Pengarang</th>
                            <th class="text-center" width="12%">Kategori</th>
                            <th class="text-center" width="10%">Rak</th>
                            <th class="text-center" width="8%">Stok</th>
                            <th class="text-center" width="8%">Status</th>
                            @auth
                                @if (auth()->user()->level === 'admin')
                                    <th class="text-center" width="10%">Aksi</th>
                                @endif
                            @endauth
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $d)
                            <tr>
                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                <td class="text-center align-middle">{{ $d->kode_buku }}</td>
                                <td class="align-middle">{{ $d->judul_buku }}</td>
                                <td class="align-middle">{{ $d->pengarang ?? '-' }}</td>
                                <td class="text-center align-middle">
                                    {{-- SOLUSI SEDERHANA: Cek langsung dari kategoriList --}}
                                    @php
                                        $kategoriNama = '-';
                                        if ($d->kategori_id) {
                                            $kategori = $kategoriList->where('id', $d->kategori_id)->first();
                                            $kategoriNama = $kategori ? $kategori->nama_kategori : '-';
                                        }
                                    @endphp
                                    {{ $kategoriNama }}
                                </td>
                                <td class="text-center align-middle">
                                    @php
                                        $rakKode = '-';
                                        if ($d->rak_id) {
                                            $rak = $rakList->where('id', $d->rak_id)->first();
                                            $rakKode = $rak ? $rak->kode_rak : '-';
                                        }
                                    @endphp
                                    {{ $rakKode }}
                                </td>
                                <td class="text-center align-middle">{{ $d->stok ?? 0 }}</td>
                                <td class="text-center align-middle">
                                    <span
                                        class="badge badge-{{ $d->status == 'Ada' ? 'success' : ($d->status == 'Dipinjam' ? 'warning' : 'danger') }}">
                                        {{ $d->status }}
                                    </span>
                                </td>
                                @auth
                                    @if (auth()->user()->level === 'admin')
                                        <td class="text-center align-middle">
                                            <button type="button" class="btn btn-success btn-sm" title="Edit data"
                                                data-toggle="modal" data-target="#edit{{ $d->id }}"><i
                                                    class="fa fa-edit"></i></button>
                                            <button type="button" class="btn btn-info btn-sm" title="Detail data"
                                                data-toggle="modal" data-target="#detail{{ $d->id }}"><i
                                                    class="fa fa-eye"></i></button>
                                            <button type="button" class="btn btn-danger btn-sm" title="Hapus data"
                                                data-toggle="modal" data-target="#hapus{{ $d->id }}"><i
                                                    class="fa fa-trash"></i></button>
                                        </td>
                                    @endif
                                @endauth
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fa fa-info-circle"></i> Tidak ada data buku
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Debug info di view --}}
            <div class="alert alert-info d-none"> {{-- ganti d-none menjadi d-block untuk melihat debug --}}
                @if (isset($data) && $data->count() > 0)
                    @foreach ($data as $index => $item)
                        <div>
                            <strong>Buku {{ $index + 1 }}:</strong><br>
                            ID: {{ $item->id }},
                            Kode: {{ $item->kode_buku }},
                            Kategori ID: {{ $item->kategori_id }},
                            Kategori Loaded: {{ $item->relationLoaded('kategori') ? 'Yes' : 'No' }},
                            Kategori Data: {{ $item->kategori ? json_encode($item->kategori) : 'NULL' }}
                        </div>
                        <hr>
                    @endforeach
                @endif
            </div>
            {{-- MODAL TAMBAH --}}
            <div class="modal fade" id="tambah" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('buku.save') }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Data Buku</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kategori <span class="text-danger">*</span></label>
                                            <select class="form-control" name="kategori_id" id="kategori_id" required>
                                                <option value="">Pilih Kategori</option>
                                                @foreach ($kategoriList as $kat)
                                                    <option value="{{ $kat->id }}" data-kode="{{ $kat->kode }}">
                                                        {{ $kat->nama_kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kode Buku</label>
                                            <input type="text" class="form-control" name="kode_buku" id="kode_buku"
                                                readonly style="background-color: #48515a;">
                                            <small class="form-text text-muted">
                                                <i class="fa fa-info-circle"></i> Otomatis dari kategori
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Judul Buku <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="judul_buku" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Rak</label>
                                            <select class="form-control" name="rak_id">
                                                <option value="">Pilih Rak</option>
                                                @foreach ($rakList as $r)
                                                    <option value="{{ $r->id }}">{{ $r->kode_rak }} -
                                                        {{ $r->keterangan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Pengarang</label>
                                            <input type="text" class="form-control" name="pengarang">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Penerbit</label>
                                            <input type="text" class="form-control" name="penerbit">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tahun Terbit</label>
                                            <input type="number" class="form-control" name="tahun_terbit"
                                                min="1900" max="{{ date('Y') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ISBN</label>
                                            <input type="text" class="form-control" name="isbn">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Jumlah Halaman</label>
                                            <input type="number" class="form-control" name="jumlah_halaman"
                                                min="1">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Stok</label>
                                            <input type="number" class="form-control" name="stok" value="1"
                                                min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" name="status">
                                                <option value="Ada" selected>Ada</option>
                                                <option value="Dipinjam">Dipinjam</option>
                                                <option value="Hilang">Hilang</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                                    <i class="fa fa-undo"></i> Close
                                </button>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-save"></i> Simpan Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @foreach ($data as $d)
                <!-- Modal Detail -->
                <div class="modal fade" id="detail{{ $d->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Detail Buku</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="35%"><strong>Kode Buku</strong></td>
                                        <td width="5%">:</td>
                                        <td>{{ $d->kode_buku }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Judul Buku</strong></td>
                                        <td>:</td>
                                        <td>{{ $d->judul_buku }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Kategori</strong></td>
                                        <td>:</td>
                                        <td>
                                            @php
                                                $kategoriNama = '-';
                                                if ($d->kategori_id) {
                                                    $kat = $kategoriList->firstWhere('id', $d->kategori_id);
                                                    $kategoriNama = $kat ? $kat->nama_kategori : '-';
                                                }
                                            @endphp
                                            {{ $kategoriNama }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Rak</strong></td>
                                        <td>:</td>
                                        <td>
                                            @php
                                                $rakInfo = '-';
                                                if ($d->rak_id) {
                                                    $rakItem = $rakList->firstWhere('id', $d->rak_id);
                                                    $rakInfo = $rakItem
                                                        ? $rakItem->kode_rak . ' - ' . $rakItem->keterangan
                                                        : '-';
                                                }
                                            @endphp
                                            {{ $rakInfo }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Pengarang</strong></td>
                                        <td>:</td>
                                        <td>{{ $d->pengarang ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Penerbit</strong></td>
                                        <td>:</td>
                                        <td>{{ $d->penerbit ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tahun Terbit</strong></td>
                                        <td>:</td>
                                        <td>{{ $d->tahun_terbit ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>ISBN</strong></td>
                                        <td>:</td>
                                        <td>{{ $d->isbn ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jumlah Halaman</strong></td>
                                        <td>:</td>
                                        <td>{{ $d->jumlah_halaman ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Stok</strong></td>
                                        <td>:</td>
                                        <td>{{ $d->stok ?? '0' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status</strong></td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $d->status == 'Ada' ? 'success' : ($d->status == 'Dipinjam' ? 'warning' : 'danger') }}">
                                                {{ $d->status }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                                    <i class="fa fa-times"></i> Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Edit -->
                <div class="modal fade" id="edit{{ $d->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('buku.update', $d->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Data Buku</h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Kategori <span class="text-danger">*</span></label>
                                                <select class="form-control" name="kategori_id" required>
                                                    <option value="">Pilih Kategori</option>
                                                    @foreach ($kategoriList as $kat)
                                                        <option value="{{ $kat->id }}"
                                                            {{ $d->kategori_id == $kat->id ? 'selected' : '' }}>
                                                            {{ $kat->nama_kategori }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Kode Buku</label>
                                                <input type="text" value="{{ $d->kode_buku ?? '' }}"
                                                    class="form-control" name="kode_buku" readonly
                                                    style="background-color: #414448;">
                                                <small class="form-text text-muted">
                                                    <i class="fa fa-info-circle"></i> Kode buku otomatis
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Judul Buku <span class="text-danger">*</span></label>
                                                <input type="text" value="{{ $d->judul_buku ?? '' }}"
                                                    class="form-control" name="judul_buku" required>
                                                @if ($errors->has('judul_buku'))
                                                    <small class="text-danger">{{ $errors->first('judul_buku') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Rak</label>
                                                <select class="form-control" name="rak_id">
                                                    <option value="">Pilih Rak</option>
                                                    @foreach ($rakList as $r)
                                                        @php
                                                            $isSelectedRak = false;

                                                            // Handle berbagai kemungkinan tipe data rak
                                                            if (isset($d->rak)) {
                                                                if (
                                                                    is_object($d->rak) &&
                                                                    property_exists($d->rak, 'id') &&
                                                                    $d->rak->id == $r->id
                                                                ) {
                                                                    $isSelectedRak = true;
                                                                } elseif (is_numeric($d->rak) && $d->rak == $r->id) {
                                                                    $isSelectedRak = true;
                                                                } elseif (is_string($d->rak) && $d->rak == $r->id) {
                                                                    $isSelectedRak = true;
                                                                }
                                                            }

                                                            // Fallback ke rak_id jika rak relationship tidak ada
                                                            if (
                                                                !$isSelectedRak &&
                                                                isset($d->rak_id) &&
                                                                $d->rak_id == $r->id
                                                            ) {
                                                                $isSelectedRak = true;
                                                            }
                                                        @endphp
                                                        <option value="{{ $r->id }}"
                                                            {{ $isSelectedRak ? 'selected' : '' }}>
                                                            {{ $r->kode_rak }} - {{ $r->keterangan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Pengarang</label>
                                                <input type="text" value="{{ $d->pengarang ?? '' }}"
                                                    class="form-control" name="pengarang" placeholder="Nama pengarang">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Penerbit</label>
                                                <input type="text" value="{{ $d->penerbit ?? '' }}"
                                                    class="form-control" name="penerbit" placeholder="Nama penerbit">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tahun Terbit</label>
                                                <input type="number" value="{{ $d->tahun_terbit ?? '' }}"
                                                    class="form-control" name="tahun_terbit" min="1900"
                                                    max="{{ date('Y') }}" placeholder="Tahun terbit">
                                                @if ($errors->has('tahun_terbit'))
                                                    <small
                                                        class="text-danger">{{ $errors->first('tahun_terbit') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>ISBN</label>
                                                <input type="text" value="{{ $d->isbn ?? '' }}" class="form-control"
                                                    name="isbn" placeholder="Nomor ISBN">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Jumlah Halaman</label>
                                                <input type="number" value="{{ $d->jumlah_halaman ?? '' }}"
                                                    class="form-control" name="jumlah_halaman" min="1"
                                                    placeholder="Jumlah halaman">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Stok <span class="text-danger">*</span></label>
                                                <input type="number" value="{{ $d->stok ?? 0 }}" class="form-control"
                                                    name="stok" min="0" required>
                                                @if ($errors->has('stok'))
                                                    <small class="text-danger">{{ $errors->first('stok') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Status <span class="text-danger">*</span></label>
                                                <select class="form-control" name="status" required>
                                                    <option value="Ada"
                                                        {{ ($d->status ?? 'Ada') == 'Ada' ? 'selected' : '' }}>Ada
                                                    </option>
                                                    <option value="Dipinjam"
                                                        {{ ($d->status ?? '') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam
                                                    </option>
                                                    <option value="Hilang"
                                                        {{ ($d->status ?? '') == 'Hilang' ? 'selected' : '' }}>Hilang
                                                    </option>
                                                </select>
                                                @if ($errors->has('status'))
                                                    <small class="text-danger">{{ $errors->first('status') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Debug info untuk development --}}
                                    @if (config('app.debug'))
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                                    <small>
                                                        <strong>Debug Info:</strong><br>
                                                        Kategori: {{ gettype($d->kategori ?? 'null') }} -
                                                        {{ is_object($d->kategori ?? null) ? json_encode($d->kategori) : $d->kategori ?? 'null' }}<br>
                                                        Rak: {{ gettype($d->rak ?? 'null') }} -
                                                        {{ is_object($d->rak ?? null) ? json_encode($d->rak) : $d->rak ?? 'null' }}
                                                    </small>
                                                    <button type="button" class="close" data-dismiss="alert">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                                        <i class="fa fa-undo"></i> Batal
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fa fa-save"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Modal Hapus -->
                <div class="modal fade" id="hapus{{ $d->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Konfirmasi Hapus Data</h5>
                                <button type="button" class="close text-white" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="{{ route('buku.destroy', $d->id) }}">
                                @csrf
                                @method('DELETE')
                                <div class="modal-body">
                                    <div class="text-center mb-3">
                                        <i class="fa fa-exclamation-triangle fa-4x text-warning"></i>
                                    </div>
                                    <p class="text-center">Apakah Anda yakin ingin menghapus data buku berikut?</p>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td width="35%"><strong>Kode Buku</strong></td>
                                            <td width="5%">:</td>
                                            <td>{{ $d->kode_buku }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Judul</strong></td>
                                            <td>:</td>
                                            <td>{{ $d->judul_buku }}</td>
                                        </tr>
                                    </table>
                                    <div class="alert alert-warning" role="alert">
                                        <small><i class="fa fa-info-circle"></i> Data yang dihapus tidak dapat
                                            dikembalikan!</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                                        <i class="fa fa-times"></i> Batal
                                    </button>
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i> Ya, Hapus Data
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $(document).ready(function() {
                    // Auto generate kode buku ketika kategori dipilih
                    $('#kategori_id').on('change', function() {
                        var kategoriId = $(this).val();

                        if (kategoriId) {
                            $.ajax({
                                url: '{{ route('buku.generateKode') }}',
                                type: 'GET',
                                data: {
                                    kategori_id: kategoriId
                                },
                                success: function(response) {
                                    $('#kode_buku').val(response.kode_buku);
                                },
                                error: function() {
                                    alert('Gagal generate kode buku');
                                }
                            });
                        } else {
                            $('#kode_buku').val('');
                        }
                    });

                    // Auto close alerts
                    setTimeout(function() {
                        $('.alert').fadeOut('slow');
                    }, 3000);
                });
            </script>

        @endsection
