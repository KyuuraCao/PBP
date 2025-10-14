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
                                <a href="{{ route('buku.excel') }}" class="btn btn-success btn-sm" title="Export ke Excel">
                                    <i class="fa fa-file-excel"></i> &nbsp;Export Excel
                                </a>
                                <a href="{{ route('buku.cetak') }}" target="_blank" class="btn btn-danger btn-sm ml-2" title="Cetak Data">
                                    <i class="fa fa-print"></i> &nbsp;Cetak
                                </a>
                            </div>
                        </div>
          @endif
        @endauth

        {{-- Alert untuk Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Alert untuk Error Message --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Alert untuk Status Message --}}
        @if(session('status'))
            <div class="alert alert-{{ session('status')['icon'] == 'success' ? 'success' : 'danger' }} alert-dismissible fade show" role="alert" id="autoCloseAlert">
                <i class="fa fa-{{ session('status')['icon'] == 'success' ? 'check-circle' : 'exclamation-circle' }}"></i> 
                <strong>{{ session('status')['judul'] }}!</strong> {{ session('status')['pesan'] }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Alert untuk Validation Errors --}}
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

        {{-- Script untuk Auto Close Alert --}}
        @if(session('status'))
        <script>
            setTimeout(function() {
                var alert = document.getElementById('autoCloseAlert');
                if (alert) {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }
            }, 3000);
        </script>
        @endif

        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Kode Buku</th>
                        <th class="text-center">Judul Buku</th>
                        <th class="text-center">Pengarang</th>
                        <th class="text-center">Status</th>
                        @auth
                            @if(auth()->user()->level === 'admin')
                        <th class="text-center">Aksi</th>
                            @endif
                        @endauth
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $d)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $d->kode_buku }}</td>
                        <td class="text-center">{{ $d->judul_buku }}</td>
                        <td class="text-center">{{ $d->pengarang ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge badge-{{ $d->status == 'Ada' ? 'success' : 'secondary' }}">
                                {{ $d->status }}
                            </span>
                        </td>
                        @auth
                            @if(auth()->user()->level === 'admin')
                        <td class="text-center">
                            <button type="button" class="btn btn-success btn-sm" title="Edit data" data-toggle="modal" data-target="#edit{{ $d->id }}"><i class="fa fa-edit"></i></button>
                            <button type="button" class="btn btn-info btn-sm" title="Detail data" data-toggle="modal" data-target="#detail{{ $d->id }}"><i class="fa fa-eye"></i></button>
                            <button type="button" class="btn btn-danger btn-sm" title="Hapus data" data-toggle="modal" data-target="#hapus{{ $d->id }}"><i class="fa fa-trash"></i></button>
                        </td>
                            @endif
                        @endauth
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modals Section -->
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
                            <span class="badge badge-{{ $d->status == 'Ada' ? 'success' : 'secondary' }}">
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
                                <input type="text" value="{{ $d->kode_buku }}" class="form-control" name="kode_buku" readonly style="background-color: #e9ecef;">
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
                                <input type="text" class="form-control" name="kode_buku" value="{{ $nextKodeBuku ?? '' }}" readonly style="background-color: #e9ecef;">
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

{{-- Script untuk membuka kembali modal jika ada error --}}
@if($errors->any() && session('modal'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#{{ session("modal") }}').modal('show');
    });
</script>
@endif

@endsection