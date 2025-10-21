@extends('layout.app')

@section('title', 'Data Rak')
@section('konten')
<div class="card">
    <div class="card-body">

        @auth
          @if(auth()->user()->level === 'admin')
                <div class="mb-3">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tambah" title="Tambah data rak">
                        <i class="fa fa-plus-square"></i> &nbsp;Tambah Data
                    </button>
                </div>
          @endif
        @endauth

        {{-- Alerts --}}
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

        {{-- INFO JUMLAH DATA --}}
        <div class="mb-2">
            <small class="text-muted">
                <i class="fa fa-info-circle"></i> Menampilkan <strong>{{ $data->count() }}</strong> data rak
            </small>
        </div>

        {{-- TABEL UTAMA --}}
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th class="text-center" width="20%">Kode Rak</th>
                        <th class="text-center" width="40%">Keterangan</th>
                        <th class="text-center" width="15%">Jumlah Buku</th>
                        @auth
                            @if(auth()->user()->level === 'admin')
                                <th class="text-center" width="20%">Aksi</th>
                            @endif
                        @endauth
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $d)
                    <tr>
                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                        <td class="text-center align-middle">
                            <span class="badge badge-info">{{ $d->kode_rak }}</span>
                        </td>
                        <td class="align-middle">{{ $d->keterangan ?? '-' }}</td>
                        <td class="text-center align-middle">
                            <span class="badge badge-primary">{{ $d->buku->count() }} buku</span>
                        </td>
                        @auth
                            @if(auth()->user()->level === 'admin')
                        <td class="text-center align-middle">
                            <button type="button" class="btn btn-success btn-sm" title="Edit data" data-toggle="modal" data-target="#edit{{ $d->id }}">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-info btn-sm" title="Detail data" data-toggle="modal" data-target="#detail{{ $d->id }}">
                                <i class="fa fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" title="Hapus data" data-toggle="modal" data-target="#hapus{{ $d->id }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                            @endif
                        @endauth
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="fa fa-info-circle"></i> Tidak ada data rak
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="tambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('rak.save') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Rak</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode Rak <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('kode_rak') is-invalid @enderror" 
                               name="kode_rak" placeholder="Contoh: RAK-A1" value="{{ old('kode_rak') }}" required>
                        @error('kode_rak')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fa fa-info-circle"></i> Format: RAK-A1, RAK-B2, dll.
                        </small>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                  name="keterangan" rows="3" placeholder="Keterangan lokasi atau informasi tambahan">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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

{{-- MODAL EDIT, DETAIL & HAPUS untuk setiap data --}}
@foreach($data as $d)

<!-- Modal Detail -->
<div class="modal fade" id="detail{{ $d->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Rak</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="35%"><strong>Kode Rak</strong></td>
                        <td width="5%">:</td>
                        <td><span class="badge badge-info">{{ $d->kode_rak }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Keterangan</strong></td>
                        <td>:</td>
                        <td>{{ $d->keterangan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jumlah Buku</strong></td>
                        <td>:</td>
                        <td><span class="badge badge-primary">{{ $d->buku->count() }} buku</span></td>
                    </tr>
                </table>

                @if($d->buku->count() > 0)
                <hr>
                <h6 class="mb-3"><i class="fa fa-book"></i> Daftar Buku di Rak Ini</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th>Kode Buku</th>
                                <th>Judul Buku</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($d->buku as $buku)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $buku->kode_buku }}</td>
                                <td>{{ $buku->judul_buku }}</td>
                                <td class="text-center">
                                    <span class="badge badge-{{ $buku->status == 'Ada' ? 'success' : ($buku->status == 'Dipinjam' ? 'warning' : 'danger') }}">
                                        {{ $buku->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
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
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('rak.update', $d->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Rak</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode Rak <span class="text-danger">*</span></label>
                        <input type="text" value="{{ $d->kode_rak }}" class="form-control @error('kode_rak') is-invalid @enderror" 
                               name="kode_rak" required>
                        @error('kode_rak')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                  name="keterangan" rows="3">{{ $d->keterangan }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
            <form method="POST" action="{{ route('rak.destroy', $d->id) }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fa fa-exclamation-triangle fa-4x text-warning"></i>
                    </div>
                    <p class="text-center">Apakah Anda yakin ingin menghapus data rak berikut?</p>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td width="35%"><strong>Kode Rak</strong></td>
                            <td width="5%">:</td>
                            <td>{{ $d->kode_rak }}</td>
                        </tr>
                        <tr>
                            <td><strong>Keterangan</strong></td>
                            <td>:</td>
                            <td>{{ $d->keterangan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Jumlah Buku</strong></td>
                            <td>:</td>
                            <td>{{ $d->buku->count() }} buku</td>
                        </tr>
                    </table>
                    @if($d->buku->count() > 0)
                        <div class="alert alert-danger" role="alert">
                            <small><i class="fa fa-exclamation-circle"></i> Rak ini masih memiliki {{ $d->buku->count() }} buku dan tidak dapat dihapus!</small>
                        </div>
                    @else
                        <div class="alert alert-warning" role="alert">
                            <small><i class="fa fa-info-circle"></i> Data yang dihapus tidak dapat dikembalikan!</small>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <i class="fa fa-times"></i> Batal
                    </button>
                    @if($d->buku->count() == 0)
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i> Ya, Hapus Data
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

@endforeach

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