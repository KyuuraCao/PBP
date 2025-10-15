@extends('layout.app')

@section('title', 'Data Anggota')
@section('konten')

<div class="card">
    <div class="card-body">

        @auth
            @if(auth()->user()->level === 'admin')
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tambah" title="Tambah data anggota">
                            <i class="fa fa-plus-square"></i> &nbsp;Tambah Data
                        </button>
                    </div>
                    <div>
                        <a href="{{ route('anggota.excel', request()->all()) }}" class="btn btn-success btn-sm" title="Export ke Excel">
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
                <h6 class="mb-3 text-white"><i class="fa fa-filter"></i> Filter Data Anggota</h6>
                <form id="filterForm" method="GET" action="{{ route('anggota.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="small text-white">Pencarian Nama</label>
                                <input type="text" class="form-control form-control-sm" name="search" 
                                    value="{{ request('search') }}" placeholder="Cari nama..."
                                    style="background-color: #173659; color: rgb(107, 103, 103); border-color: #4a5259;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="small text-white">Tanggal Daftar</label>
                                <div class="input-group input-group-sm">
                                    <input type="date" class="form-control form-control-sm" name="dari" 
                                        value="{{ request('dari') }}" onchange="this.form.submit()"
                                        style="background-color: #3a4149; color: white; border-color: #4a5259;">
                                    <div class="input-group-append input-group-prepend">
                                        <span class="input-group-text" style="background-color: #3a4149; color: white; border-color: #4a5259;">s/d</span>
                                    </div>
                                    <input type="date" class="form-control form-control-sm" name="sampai" 
                                        value="{{ request('sampai') }}" onchange="this.form.submit()"
                                        style="background-color: #3a4149; color: white; border-color: #4a5259;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa fa-search"></i> Cari
                            </button>
                            <a href="{{ route('anggota.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-undo"></i> Reset Filter
                            </a>
                            @if(request()->anyFilled(['search', 'dari', 'sampai']))
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
                <i class="fa fa-info-circle"></i> Menampilkan <strong>{{ $data->count() }}</strong> data anggota
            </small>
            @if(request()->anyFilled(['search', 'status', 'jenis_kelamin', 'pendidikan_terakhir', 'dari', 'sampai']))
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
                        <th class="text-center" width="10%">Foto</th>
                        <th class="text-center" width="15%">No Anggota</th>
                        <th class="text-center" width="20%">Nama Lengkap</th>
                        <th class="text-center" width="20%">Pekerjaan/Instansi</th>
                        <th class="text-center" width="20%">Alamat</th>
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
                        <td class="align-middle">{{ $d->alamat ?? '-' }}</td>
                        @auth
                            @if(auth()->user()->level === 'admin')
                        <td class="text-center align-middle">
                            <button type="button" class="btn btn-success btn-sm" title="Edit data" data-toggle="modal" data-target="#edit{{ $d->id }}">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" title="Hapus data" data-toggle="modal" data-target="#hapus{{ $d->id }}">
                                <i class="fa fa-trash"></i>
                            </button>
                            <button type="button" class="btn btn-info btn-sm" 
                                title="Cetak Kartu" 
                                onclick="window.open('{{ route('anggota.kartu', $d->id) }}', '_blank')">
                                <i class="fa fa-id-card"></i>
                            </button>
                        </td>
                            @endif
                        @endauth
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fa fa-info-circle"></i> Tidak ada data anggota
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- END TABEL --}}

    </div>
</div>

@foreach($data as $d)

<!-- Modal Edit -->
<div class="modal fade" id="edit{{ $d->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('anggota.update', $d->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Anggota</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nomor Anggota</label>
                                <input type="text" value="{{ $d->id_anggota }}" class="form-control" name="id_anggota" readonly style="background-color: #414448;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" value="{{ old('nama', $d->nama) }}" class="form-control @error('nama') is-invalid @enderror" name="nama" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <select class="form-control @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin" required>
                                    <option value="">~Pilih~</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin', $d->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin', $d->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alamat</label>
                                <input type="text" value="{{ old('alamat', $d->alamat) }}" class="form-control @error('alamat') is-invalid @enderror" name="alamat" placeholder="Alamat">
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nomor HP</label>
                                <input type="text" value="{{ old('nomor_hp', $d->nomor_hp) }}" class="form-control @error('nomor_hp') is-invalid @enderror" name="nomor_hp" placeholder="Nomor HP">
                                @error('nomor_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alamat Email</label>
                                <input type="email" value="{{ old('email', $d->email) }}" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Alamat Email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control @error('status') is-invalid @enderror" name="status">
                                    <option value="Aktif" {{ old('status', $d->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Tidak Aktif" {{ old('status', $d->status) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pendidikan Terakhir</label>
                                <select class="form-control @error('pendidikan_terakhir') is-invalid @enderror" name="pendidikan_terakhir">
                                    <option value="">Pilih</option>
                                    <option value="SD" {{ old('pendidikan_terakhir', $d->pendidikan_terakhir) == 'SD' ? 'selected' : '' }}>SD</option>
                                    <option value="SMP" {{ old('pendidikan_terakhir', $d->pendidikan_terakhir) == 'SMP' ? 'selected' : '' }}>SMP</option>
                                    <option value="SMA/SMK" {{ old('pendidikan_terakhir', $d->pendidikan_terakhir) == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                                    <option value="D3" {{ old('pendidikan_terakhir', $d->pendidikan_terakhir) == 'D3' ? 'selected' : '' }}>D3</option>
                                    <option value="S1" {{ old('pendidikan_terakhir', $d->pendidikan_terakhir) == 'S1' ? 'selected' : '' }}>S1</option>
                                    <option value="S2" {{ old('pendidikan_terakhir', $d->pendidikan_terakhir) == 'S2' ? 'selected' : '' }}>S2</option>
                                    <option value="S3" {{ old('pendidikan_terakhir', $d->pendidikan_terakhir) == 'S3' ? 'selected' : '' }}>S3</option>
                                </select>
                                @error('pendidikan_terakhir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pekerjaan</label>
                                <input type="text" value="{{ old('pekerjaan', $d->pekerjaan) }}" class="form-control @error('pekerjaan') is-invalid @enderror" name="pekerjaan" placeholder="Pekerjaan saat ini">
                                @error('pekerjaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Instansi</label>
                                <input type="text" value="{{ old('instansi', $d->instansi) }}" class="form-control @error('instansi') is-invalid @enderror" name="instansi" placeholder="Tempat bekerja">
                                @error('instansi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Berlaku Hingga</label>
                                <input type="date" value="{{ old('berlaku_hingga', $d->berlaku_hingga) }}" class="form-control @error('berlaku_hingga') is-invalid @enderror" name="berlaku_hingga">
                                @error('berlaku_hingga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Foto</label>
                        <input type="file" class="form-control @error('foto') is-invalid @enderror" accept=".jpg, .jpeg, .png" name="foto">
                        @if($d->foto)
                            <small class="text-muted">Foto saat ini: {{ $d->foto }}</small>
                        @endif
                        @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
<div class="modal fade" id="hapus{{ $d->id }}" tabindex="-1" aria-labelledby="hapusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="hapusModalLabel">Konfirmasi Hapus Data</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('anggota.destroy', $d->id) }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fa fa-exclamation-triangle fa-4x text-warning"></i>
                    </div>
                    <p class="text-center">Apakah Anda yakin ingin menghapus data anggota berikut?</p>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td width="35%"><strong>No Anggota</strong></td>
                            <td width="5%">:</td>
                            <td>{{ $d->id_anggota }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nama</strong></td>
                            <td>:</td>
                            <td>{{ $d->nama }}</td>
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

<!-- Modal Detail -->
<div class="modal fade" id="viewDetail{{ $d->id_anggota }}" tabindex="-1" aria-labelledby="viewDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Anggota</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        @if($d->foto)
                            <img src="{{ asset('uploads/foto/'.$d->foto) }}" 
                                 alt="Foto {{ $d->nama }}" 
                                 class="img-fluid rounded mb-2" 
                                 style="max-height: 200px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                 style="width: 100%; height: 200px;">
                                <i class="fa fa-user fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="35%">No Anggota</th>
                                <td>: {{ $d->id_anggota }}</td>
                            </tr>
                            <tr>
                                <th>Nama Lengkap</th>
                                <td>: {{ $d->nama }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td>: {{ $d->jenis_kelamin ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>: {{ $d->alamat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>No Telepon</th>
                                <td>: {{ $d->nomor_hp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Pekerjaan</th>
                                <td>: {{ $d->pekerjaan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Instansi</th>
                                <td>: {{ $d->instansi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Pendidikan</th>
                                <td>: {{ $d->pendidikan_terakhir ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($d->status == 'Aktif')
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-secondary">Tidak Aktif</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Tanggal Daftar</th>
                                <td>: {{ $d->tanggal_daftar ? \Carbon\Carbon::parse($d->tanggal_daftar)->format('d/m/Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Berlaku Hingga</th>
                                <td>: {{ $d->berlaku_hingga ? \Carbon\Carbon::parse($d->berlaku_hingga)->format('d/m/Y') : '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    <i class="fa fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@endforeach

{{-- MODAL FILTER CETAK (Hanya 1x) --}}
<div class="modal fade" id="filterCetak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="GET" action="{{ route('anggota.cetak') }}" target="_blank">
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
                        <input type="text" class="form-control" name="search" placeholder="Cari nama, ID, atau alamat...">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="">Semua</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <select class="form-control" name="jenis_kelamin">
                            <option value="">Semua</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pendidikan Terakhir</label>
                        <select class="form-control" name="pendidikan_terakhir">
                            <option value="">Semua</option>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA/SMK">SMA/SMK</option>
                            <option value="D3">D3</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Daftar</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="date" class="form-control" name="dari" placeholder="Dari">
                                <small class="text-muted">Dari</small>
                            </div>
                            <div class="col-md-6">
                                <input type="date" class="form-control" name="sampai" placeholder="Sampai">
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

{{-- MODAL TAMBAH (Hanya 1x) --}}
<div class="modal fade" id="tambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('anggota.save') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Anggota</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nomor Anggota</label>
                                <input type="text" class="form-control" name="id_anggota" value="{{ $nextIdAnggota }}" readonly style="background-color: #48515a;">
                                <small class="form-text text-muted">
                                    <i class="fa fa-info-circle"></i> Nomor anggota otomatis di-generate
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" placeholder="Nama Lengkap" value="{{ old('nama') }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-control @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin" required>
                                    <option value="">~Pilih~</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alamat</label>
                                <input type="text" class="form-control @error('alamat') is-invalid @enderror" name="alamat" placeholder="Alamat" value="{{ old('alamat') }}">
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nomor HP</label>
                                <input type="text" class="form-control @error('nomor_hp') is-invalid @enderror" name="nomor_hp" placeholder="Nomor HP" value="{{ old('nomor_hp') }}">
                                @error('nomor_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alamat Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Alamat Email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control @error('status') is-invalid @enderror" name="status">
                                    <option value="Aktif" {{ old('status') == 'Aktif' || old('status') === null ? 'selected' : '' }}>Aktif</option>
                                    <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pendidikan Terakhir</label>
                                <select class="form-control @error('pendidikan_terakhir') is-invalid @enderror" name="pendidikan_terakhir">
                                    <option value="">Pilih</option>
                                    <option value="SD" {{ old('pendidikan_terakhir') == 'SD' ? 'selected' : '' }}>SD</option>
                                    <option value="SMP" {{ old('pendidikan_terakhir') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                    <option value="SMA/SMK" {{ old('pendidikan_terakhir') == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                                    <option value="D3" {{ old('pendidikan_terakhir') == 'D3' ? 'selected' : '' }}>D3</option>
                                    <option value="S1" {{ old('pendidikan_terakhir') == 'S1' ? 'selected' : '' }}>S1</option>
                                    <option value="S2" {{ old('pendidikan_terakhir') == 'S2' ? 'selected' : '' }}>S2</option>
                                    <option value="S3" {{ old('pendidikan_terakhir') == 'S3' ? 'selected' : '' }}>S3</option>
                                </select>
                                @error('pendidikan_terakhir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pekerjaan</label>
                                <input type="text" class="form-control @error('pekerjaan') is-invalid @enderror" name="pekerjaan" placeholder="Pekerjaan saat ini" value="{{ old('pekerjaan') }}">
                                @error('pekerjaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Instansi</label>
                                <input type="text" class="form-control @error('instansi') is-invalid @enderror" name="instansi" placeholder="Tempat bekerja" value="{{ old('instansi') }}">
                                @error('instansi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Daftar</label>
                                <input type="date" class="form-control @error('tanggal_daftar') is-invalid @enderror" name="tanggal_daftar" value="{{ old('tanggal_daftar', date('Y-m-d')) }}">
                                @error('tanggal_daftar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Berlaku Hingga</label>
                                <input type="date" class="form-control @error('berlaku_hingga') is-invalid @enderror" name="berlaku_hingga" value="{{ old('berlaku_hingga') }}">
                                @error('berlaku_hingga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Foto <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('foto') is-invalid @enderror" accept=".jpg, .jpeg, .png" name="foto" required>
                        <small class="form-text text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB</small>
                        @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
            }, 3000); // 5000 ms = 5 detik
        });
    });
</script>

@endsection