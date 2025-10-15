@extends('layout.app')

@section('title', 'Data Buku')
@section('konten')
<div class="card">
    <div class="card-body">

        @auth
          @if(auth()->user()->level === 'admin')
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tambah" title="Tambah data buku">
                            <i class="fa fa-plus-square"></i> &nbsp;Tambah Data
                        </button>
                    </div>
                    <div>
                        <a href="{{ route('buku.excel', request()->all()) }}" class="btn btn-success btn-sm" title="Export ke Excel">
                            <i class="fa fa-file-excel"></i> &nbsp;Export Excel
                        </a>
                        <button type="button" class="btn btn-danger btn-sm ml-2" data-toggle="modal" data-target="#filterCetak" title="Cetak Data">
                            <i class="fa fa-print"></i> &nbsp;Cetak
                        </button>
                    </div>
                </div>
          @endif
        @endauth

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('status'))
            <div class="alert alert-{{ session('status')['icon'] == 'success' ? 'success' : 'danger' }} alert-dismissible fade show" role="alert" id="autoCloseAlert">
                <i class="fa fa-{{ session('status')['icon'] == 'success' ? 'check-circle' : 'exclamation-circle' }}"></i> 
                <strong>{{ session('status')['judul'] }}!</strong> {{ session('status')['pesan'] }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fa fa-exclamation-triangle"></i> Terdapat kesalahan input:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="small text-white">Status</label>
                                <select class="form-control form-control-sm" name="status" onchange="this.form.submit()"
                                    style="background-color: #3a4149; color: white; border-color: #4a5259;">
                                    <option value="">Semua Status</option>
                                    <option value="Ada" {{ request('status') == 'Ada' ? 'selected' : '' }}>Ada</option>
                                    <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                    <option value="Hilang" {{ request('status') == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="small text-white">Kategori</label>
                                <input type="text" class="form-control form-control-sm" name="kategori" 
                                    value="{{ request('kategori') }}" placeholder="Filter kategori..."
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
                                        value="{{ request('tahun_dari') }}" placeholder="Dari" min="1900" max="{{ date('Y') }}"
                                        style="background-color: #3a4149; color: white; border-color: #4a5259;">
                                    <div class="input-group-append input-group-prepend">
                                        <span class="input-group-text" style="background-color: #3a4149; color: white; border-color: #4a5259;">s/d</span>
                                    </div>
                                    <input type="number" class="form-control form-control-sm" name="tahun_sampai" 
                                        value="{{ request('tahun_sampai') }}" placeholder="Sampai" min="1900" max="{{ date('Y') }}"
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
                            @if(request()->anyFilled(['search', 'status', 'kategori', 'tahun_dari', 'tahun_sampai']))
                                <span class="badge badge-info ml-2">
                                    <i class="fa fa-filter"></i> Filter Aktif
                                </span>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- INFO JUMLAH DATA --}}
        <div class="mb-2 d-flex justify-content-between align-items-center">
            <small class="text-muted">
                <i class="fa fa-info-circle"></i> Menampilkan <strong>{{ $data->count() }}</strong> data buku
            </small>
            @if(request()->anyFilled(['search', 'status', 'kategori', 'tahun_dari', 'tahun_sampai']))
                <small class="text-info">
                    <i class="fa fa-filter"></i> Hasil filter diterapkan
                </small>
            @endif
        </div>

        {{-- TABEL UTAMA --}}
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th class="text-center" width="15%">Kode Buku</th>
                        <th class="text-center" width="25%">Judul Buku</th>
                        <th class="text-center" width="20%">Pengarang</th>
                        <th class="text-center" width="15%">Kategori</th>
                        <th class="text-center" width="10%">Status</th>
                        @auth
                            @if(auth()->user()->level === 'admin')
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
                        <td class="text-center align-middle">{{ $d->kategori ?? '-' }}</td>
                        <td class="text-center align-middle">
                            <span class="badge badge-{{ $d->status == 'Ada' ? 'success' : ($d->status == 'Dipinjam' ? 'warning' : 'danger') }}">
                                {{ $d->status }}
                            </span>
                        </td>
                        @auth
                            @if(auth()->user()->level === 'admin')
                        <td class="text-center align-middle">
                            <button type="button" class="btn btn-success btn-sm" title="Edit data" data-toggle="modal" data-target="#edit{{ $d->id }}"><i class="fa fa-edit"></i></button>
                            <button type="button" class="btn btn-info btn-sm" title="Detail data" data-toggle="modal" data-target="#detail{{ $d->id }}"><i class="fa fa-eye"></i></button>
                            <button type="button" class="btn btn-danger btn-sm" title="Hapus data" data-toggle="modal" data-target="#hapus{{ $d->id }}"><i class="fa fa-trash"></i></button>
                        </td>
                            @endif
                        @endauth
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fa fa-info-circle"></i> Tidak ada data buku
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- MODAL FILTER CETAK --}}
<div class="modal fade" id="filterCetak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="GET" action="{{ route('buku.cetak') }}" target="_blank">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-print"></i> Filter Data untuk Cetak</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <small><i class="fa fa-info-circle"></i> Pilih filter untuk mencetak data sesuai kriteria. Kosongkan untuk cetak semua data.</small>
                    </div>
                    <div class="form-group">
                        <label>Pencarian</label>
                        <input type="text" class="form-control" name="search" placeholder="Cari judul, kode, atau pengarang...">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="">Semua</option>
                            <option value="Ada">Ada</option>
                            <option value="Dipinjam">Dipinjam</option>
                            <option value="Hilang">Hilang</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <input type="text" class="form-control" name="kategori" placeholder="Filter kategori...">
                    </div>
                    <div class="form-group">
                        <label>Tahun Terbit</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="number" class="form-control" name="tahun_dari" placeholder="Dari" min="1900" max="{{ date('Y') }}">
                                <small class="text-muted">Dari</small>
                            </div>
                            <div class="col-md-6">
                                <input type="number" class="form-control" name="tahun_sampai" placeholder="Sampai" min="1900" max="{{ date('Y') }}">
                                <small class="text-muted">Sampai</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <i class="fa fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fa fa-print"></i> Cetak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL UNTUK SETIAP DATA --}}
@foreach($data as $d)

<!-- Modal Detail -->
<div class="modal fade" id="detail{{ $d->id }}" tabindex="-1" aria-labelledby="detailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Buku</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
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
                        <td><strong>Kategori</strong></td>
                        <td>:</td>
                        <td>{{ $d->kategori ?? '-' }}</td>
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
                            <span class="badge badge-{{ $d->status == 'Ada' ? 'success' : ($d->status == 'Dipinjam' ? 'warning' : 'danger') }}">
                                {{ $d->status }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> &nbsp;Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="edit{{ $d->id }}" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('buku.update', $d->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Buku</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kode Buku</label>
                                <input type="text" value="{{ $d->kode_buku }}" class="form-control" name="kode_buku" readonly style="background-color: #414448;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Judul Buku <span class="text-danger">*</span></label>
                                <input type="text" value="{{ old('judul_buku', $d->judul_buku) }}" class="form-control @error('judul_buku') is-invalid @enderror" name="judul_buku" required>
                                @error('judul_buku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pengarang</label>
                                <input type="text" value="{{ old('pengarang', $d->pengarang) }}" class="form-control @error('pengarang') is-invalid @enderror" name="pengarang">
                                @error('pengarang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Penerbit</label>
                                <input type="text" value="{{ old('penerbit', $d->penerbit) }}" class="form-control @error('penerbit') is-invalid @enderror" name="penerbit">
                                @error('penerbit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tahun Terbit</label>
                                <input type="number" value="{{ old('tahun_terbit', $d->tahun_terbit) }}" class="form-control @error('tahun_terbit') is-invalid @enderror" name="tahun_terbit" min="1900" max="{{ date('Y') }}">
                                @error('tahun_terbit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ISBN</label>
                                <input type="text" value="{{ old('isbn', $d->isbn) }}" class="form-control @error('isbn') is-invalid @enderror" name="isbn">
                                @error('isbn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kategori</label>
                                <input type="text" value="{{ old('kategori', $d->kategori) }}" class="form-control @error('kategori') is-invalid @enderror" name="kategori">
                                @error('kategori')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah Halaman</label>
                                <input type="number" value="{{ old('jumlah_halaman', $d->jumlah_halaman) }}" class="form-control @error('jumlah_halaman') is-invalid @enderror" name="jumlah_halaman" min="1">
                                @error('jumlah_halaman')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Stok</label>
                                <input type="number" value="{{ old('stok', $d->stok) }}" class="form-control @error('stok') is-invalid @enderror" name="stok" min="0">
                                @error('stok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control @error('status') is-invalid @enderror" name="status">
                                    <option value="Ada" {{ old('status', $d->status) == 'Ada' ? 'selected' : '' }}>Ada</option>
                                    <option value="Dipinjam" {{ old('status', $d->status) == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                    <option value="Hilang" {{ old('status', $d->status) == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-undo"></i> &nbsp;Close</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> &nbsp;Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="hapus{{ $d->id }}" tabindex="-1" aria-labelledby="hapusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Konfirmasi Hapus Data</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
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
                        <small><i class="fa fa-info-circle"></i> Data yang dihapus tidak dapat dikembalikan!</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> &nbsp;Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> &nbsp;Ya, Hapus Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endforeach

<!-- Modal Tambah -->
<div class="modal fade" id="tambah" tabindex="-1" aria-labelledby="tambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('buku.save') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Buku</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kode Buku</label>
                                <input type="text" class="form-control" name="kode_buku" value="{{ $nextKodeBuku ?? '' }}" readonly style="background-color: #48515a;">
                                <small class="form-text text-muted">
                                    <i class="fa fa-info-circle"></i> Kode buku otomatis di-generate
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Judul Buku <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('judul_buku') is-invalid @enderror" name="judul_buku" placeholder="Judul Buku" value="{{ old('judul_buku') }}" required>
                                @error('judul_buku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pengarang</label>
                                <input type="text" class="form-control @error('pengarang') is-invalid @enderror" name="pengarang" placeholder="Nama Pengarang" value="{{ old('pengarang') }}">
                                @error('pengarang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Penerbit</label>
                                <input type="text" class="form-control @error('penerbit') is-invalid @enderror" name="penerbit" placeholder="Nama Penerbit" value="{{ old('penerbit') }}">
                                @error('penerbit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tahun Terbit</label>
                                <input type="number" class="form-control @error('tahun_terbit') is-invalid @enderror" name="tahun_terbit" placeholder="Tahun" value="{{ old('tahun_terbit') }}" min="1900" max="{{ date('Y') }}">
                                @error('tahun_terbit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ISBN</label>
                                <input type="text" class="form-control @error('isbn') is-invalid @enderror" name="isbn" placeholder="ISBN" value="{{ old('isbn') }}">
                                @error('isbn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kategori</label>
                                <input type="text" class="form-control @error('kategori') is-invalid @enderror" name="kategori" placeholder="Kategori Buku" value="{{ old('kategori') }}">
                                @error('kategori')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah Halaman</label>
                                <input type="number" class="form-control @error('jumlah_halaman') is-invalid @enderror" name="jumlah_halaman" placeholder="Jumlah Halaman" value="{{ old('jumlah_halaman') }}" min="1">
                                @error('jumlah_halaman')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Stok</label>
                                <input type="number" class="form-control @error('stok') is-invalid @enderror" name="stok" placeholder="Jumlah Stok" value="{{ old('stok', 1) }}" min="0">
                                @error('stok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control @error('status') is-invalid @enderror" name="status">
                                    <option value="Ada" {{ old('status') == 'Ada' || old('status') === null ? 'selected' : '' }}>Ada</option>
                                    <option value="Dipinjam" {{ old('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                    <option value="Hilang" {{ old('status') == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-undo"></i> &nbsp;Close</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> &nbsp;Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            }, 3000);
        });
    });
</script>

@endsection