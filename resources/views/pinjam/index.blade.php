@extends('layout.app')

@section('title', 'Data Peminjaman')
@section('konten')

<div class="card">
    <div class="card-body">

        @auth
            @if (auth()->user()->level === 'admin')
                <div class="mb-3">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tambah">
                        <i class="fa fa-plus-square"></i> Tambah Data
                    </button>
                </div>
            @endif
        @endauth

        {{-- Alerts --}}
        @if (session('status'))
            <div class="alert alert-{{ session('status')['icon'] == 'success' ? 'success' : 'danger' }} alert-dismissible fade show"
                role="alert">
                <i class="fa fa-{{ session('status')['icon'] == 'success' ? 'check-circle' : 'exclamation-circle' }}"></i>
                <strong>{{ session('status')['judul'] }}!</strong> {{ session('status')['pesan'] }}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
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
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center" width="3%">No</th>
                        <th class="text-center" width="10%">No Pinjam</th>
                        <th class="text-center" width="15%">Nama Anggota</th>
                        <th class="text-center" width="10%">Tanggal Pinjam</th>
                        <th class="text-center" width="8%">Status</th>
                        <th class="text-center" width="42%">Daftar Buku</th>
                        @auth
                            @if (auth()->user()->level === 'admin')
                                <th class="text-center" width="12%">Aksi</th>
                            @endif
                        @endauth
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $p)
                        <tr>
                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                            <td class="text-center align-middle">{{ $p->no_pinjam }}</td>
                            <td class="align-middle">{{ $p->anggota->nama ?? '-' }}</td>
                            <td class="text-center align-middle">{{ $p->tanggal_pinjam->format('d-m-Y') }}</td>
                            <td class="text-center align-middle">
                                @if($p->status == 'pinjam')
                                    <span class="badge badge-danger">Dipinjam</span>
                                @elseif($p->status == 'sebagian')
                                    <span class="badge badge-warning">Sebagian</span>
                                @else
                                    <span class="badge badge-success">Selesai</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <ul class="mb-0 pl-3">
                                    @foreach ($p->details as $d)
                                        <li>
                                            {{ $d->buku->judul_buku ?? '-' }}
                                            @if($d->status == 'pinjam')
                                                <span class="text-danger">(Dipinjam)</span>
                                            @else
                                                <span class="text-success">(Kembali)</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            @auth
                                @if (auth()->user()->level === 'admin')
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-info btn-sm" title="Detail" 
                                            data-toggle="modal" data-target="#detail{{ $p->id }}">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm" title="Edit" 
                                            data-toggle="modal" data-target="#edit{{ $p->id }}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" title="Hapus" 
                                            data-toggle="modal" data-target="#hapus{{ $p->id }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                @endif
                            @endauth
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fa fa-info-circle"></i> Tidak ada data peminjaman
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MODAL TAMBAH --}}
        <div class="modal fade" id="tambah" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="{{ route('pinjam.save') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Data Peminjaman</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Anggota <span class="text-danger">*</span></label>
                                        <select class="form-control" name="id_anggota" required>
                                            <option value="">Pilih Anggota</option>
                                            @foreach ($anggota as $a)
                                                <option value="{{ $a->id }}">{{ $a->nama }} - {{ $a->nim }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tanggal Pinjam <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tanggal_pinjam" 
                                            value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Batas Pinjam <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="batas_pinjam" 
                                            value="{{ date('Y-m-d', strtotime('+7 days')) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Pilih Buku (Maks 5) <span class="text-danger">*</span></label>
                                <div id="bukuContainer">
                                    <div class="input-group mb-2">
                                        <select class="form-control" name="id_buku[]" required>
                                            <option value="">Pilih Buku</option>
                                            @foreach ($buku as $b)
                                                <option value="{{ $b->id }}">
                                                    {{ $b->kode_buku }} - {{ $b->judul_buku }} (Stok: {{ $b->stok }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-danger btn-sm" onclick="removeBook(this)" disabled>
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-success btn-sm mt-2" onclick="addBook()">
                                    <i class="fa fa-plus"></i> Tambah Buku
                                </button>
                                <small class="form-text text-muted">
                                    <i class="fa fa-info-circle"></i> Maksimal meminjam 5 buku sekaligus
                                </small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                                <i class="fa fa-times"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @foreach ($peminjaman as $p)
            {{-- MODAL DETAIL --}}
            <div class="modal fade" id="detail{{ $p->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detail Peminjaman</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="30%"><strong>No. Pinjam</strong></td>
                                    <td width="5%">:</td>
                                    <td>{{ $p->no_pinjam }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Anggota</strong></td>
                                    <td>:</td>
                                    <td>{{ $p->anggota->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Pinjam</strong></td>
                                    <td>:</td>
                                    <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Batas Pinjam</strong></td>
                                    <td>:</td>
                                    <td>{{ $p->batas_pinjam->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>:</td>
                                    <td>
                                        @if($p->status == 'pinjam')
                                            <span class="badge badge-danger">Dipinjam</span>
                                        @elseif($p->status == 'sebagian')
                                            <span class="badge badge-warning">Sebagian</span>
                                        @else
                                            <span class="badge badge-success">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <hr>
                            <h6 class="mb-3"><strong>Daftar Buku yang Dipinjam:</strong></h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center" width="5%">No</th>
                                            <th>Kode Buku</th>
                                            <th>Judul Buku</th>
                                            <th class="text-center" width="15%">Status</th>
                                            <th class="text-center" width="15%">Tgl Kembali</th>
                                            <th class="text-center" width="12%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($p->details as $d)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $d->buku->kode_buku ?? '-' }}</td>
                                                <td>{{ $d->buku->judul_buku ?? '-' }}</td>
                                                <td class="text-center">
                                                    @if($d->status == 'pinjam')
                                                        <span class="badge badge-danger">Dipinjam</span>
                                                    @else
                                                        <span class="badge badge-success">Kembali</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{ $d->tanggal_kembali ? $d->tanggal_kembali->format('d/m/Y') : '-' }}
                                                </td>
                                                <td class="text-center">
                                                    @if($d->status == 'pinjam')
                                                        <form method="POST" action="{{ route('pinjam.kembali', [$p->id, $d->id_buku]) }}" 
                                                            style="display:inline;" onsubmit="return confirm('Kembalikan buku ini?')">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm" title="Kembalikan">
                                                                <i class="fa fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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

            {{-- MODAL EDIT --}}
            <div class="modal fade" id="edit{{ $p->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('pinjam.update', $p->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Data Peminjaman</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>No. Pinjam</label>
                                    <input type="text" class="form-control" name="no_pinjam" 
                                        value="{{ $p->no_pinjam }}" readonly style="background-color: #414448;">
                                </div>
                                <div class="form-group">
                                    <label>Anggota <span class="text-danger">*</span></label>
                                    <select class="form-control" name="id_anggota" required>
                                        <option value="">Pilih Anggota</option>
                                        @foreach ($anggota as $a)
                                            <option value="{{ $a->id }}" {{ $p->id_anggota == $a->id ? 'selected' : '' }}>
                                                {{ $a->nama }} - {{ $a->nim }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Tanggal Pinjam <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="tanggal_pinjam" 
                                        value="{{ $p->tanggal_pinjam->format('Y-m-d') }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Batas Pinjam <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="batas_pinjam" 
                                        value="{{ $p->batas_pinjam->format('Y-m-d') }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select class="form-control" name="status" required>
                                        <option value="pinjam" {{ $p->status == 'pinjam' ? 'selected' : '' }}>Pinjam</option>
                                        <option value="sebagian" {{ $p->status == 'sebagian' ? 'selected' : '' }}>Sebagian</option>
                                        <option value="selesai" {{ $p->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                </div>
                                <div class="alert alert-info">
                                    <small><i class="fa fa-info-circle"></i> Untuk mengubah buku yang dipinjam, gunakan menu Detail</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                                    <i class="fa fa-times"></i> Batal
                                </button>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-save"></i> Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- MODAL HAPUS --}}
            <div class="modal fade" id="hapus{{ $p->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Konfirmasi Hapus Data</h5>
                            <button type="button" class="close text-white" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="{{ route('pinjam.destroy', $p->id) }}">
                            @csrf
                            @method('DELETE')
                            <div class="modal-body">
                                <div class="text-center mb-3">
                                    <i class="fa fa-exclamation-triangle fa-4x text-warning"></i>
                                </div>
                                <p class="text-center">Apakah Anda yakin ingin menghapus data peminjaman berikut?</p>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="35%"><strong>No. Pinjam</strong></td>
                                        <td width="5%">:</td>
                                        <td>{{ $p->no_pinjam }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Anggota</strong></td>
                                        <td>:</td>
                                        <td>{{ $p->anggota->nama ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jumlah Buku</strong></td>
                                        <td>:</td>
                                        <td>{{ $p->details->count() }} Buku</td>
                                    </tr>
                                </table>
                                <div class="alert alert-warning" role="alert">
                                    <small><i class="fa fa-info-circle"></i> Data yang dihapus tidak dapat dikembalikan!</small>
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

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let bookCount = 1;
    const maxBooks = 5;

    function addBook() {
        if (bookCount >= maxBooks) {
            alert('Maksimal meminjam 5 buku!');
            return;
        }

        const bukuSelect = `
            <div class="input-group mb-2">
                <select class="form-control" name="id_buku[]" required>
                    <option value="">Pilih Buku</option>
                    @foreach ($buku as $b)
                        <option value="{{ $b->id }}">
                            {{ $b->kode_buku }} - {{ $b->judul_buku }} (Stok: {{ $b->stok }})
                        </option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeBook(this)">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
        `;

        $('#bukuContainer').append(bukuSelect);
        bookCount++;

        updateRemoveButtons();
    }

    function removeBook(button) {
        if (bookCount > 1) {
            $(button).closest('.input-group').remove();
            bookCount--;
            updateRemoveButtons();
        }
    }

    function updateRemoveButtons() {
        $('.input-group button[onclick^="removeBook"]').prop('disabled', bookCount === 1);
    }

    $(document).ready(function() {
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 3000);
    });
</script>

@endsection