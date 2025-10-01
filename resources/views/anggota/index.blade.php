@extends('layout.app')

@section('title', 'Data Anggota')
@section('konten')
<div class="card">
	<div class="card-body">

        @auth
          @if(auth()->user()->level === 'admin')
            <div>
                <button type="button" class="btn btn-primary btn-sm mb-2" data-toggle="modal" data-target="#tambah" title="Tambah data anggota"><i class="fa fa-plus-square"></i> &nbsp;Tambah Data</button>
            </div>
          @endif
        @endauth

	<div class="table-responsive">
		<table class="table table-sm table-bordered">
			<thead>
				<tr>
             	<th>No</th>
               	<th>Foto</th>
               	<th>No Anggota</th>
               	<th>Nama Lengkap</th>
               	<th>Pekerjaan/Instansi</th>
               	<th>Alamat</th>
                @auth
                    @if(auth()->user()->level === 'admin')
               	<th>Aksi</th>
                    @endif
                @endauth
          		</tr>
         	</thead>
          	<tbody>
          		@foreach($data as $d)
            	<tr>
            		<td>{{ $loop->iteration }}</td>
               	<td>
                    @if($d->foto)
                        <a href="#" data-toggle="modal" data-target="#viewDetail{{ $d->id_anggota }}">
                            <img src="{{ asset('uploads/foto/'.$d->foto) }}" width="50px" height="50px" alt="Foto" style="cursor: pointer; object-fit: cover; border-radius: 4px;">
                        </a>
                    @else
                        <div style="width: 50px; height: 50px; background-color: #e9ecef; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                            <i class="fa fa-user"></i>
                        </div>
                    @endif
                </td>
               	<td>{{ $d->id_anggota }}</td>
               	<td>{{ $d->nama }}</td>
               	<td>{{ ($d->pekerjaan ?? '-') . ($d->instansi ? '/' . $d->instansi : '') }}</td>
               	<td>{{ $d->alamat ?? '-' }}</td>
                @auth
                    @if(auth()->user()->level === 'admin')
            		<td>
               		    <button type="button" class="btn btn-success btn-sm" title="Edit data" data-toggle="modal" data-target="#edit{{ $d->id }}"><i class="fa fa-edit"></i></button>
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

<!-- Modals Section - All modals outside the table -->
@foreach($data as $d)
<!-- Modal Detail/View Foto dengan Informasi Lengkap -->
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
                    <div class="col-md-4 text-center mb-3">
                        @if($d->foto)
                            <img src="{{ asset('uploads/foto/'.$d->foto) }}" class="img-fluid rounded" alt="Foto {{ $d->nama }}" style="max-height: 300px; object-fit: cover;">
                        @else
                            <div style="height: 300px; background-color: #e9ecef; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                <i class="fa fa-user fa-5x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="40%"><strong>No Anggota</strong></td>
                                <td width="5%">:</td>
                                <td>{{ $d->id_anggota }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama Lengkap</strong></td>
                                <td>:</td>
                                <td>{{ $d->nama }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Kelamin</strong></td>
                                <td>:</td>
                                <td>{{ $d->jenis_kelamin ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td>:</td>
                                <td>{{ $d->alamat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nomor HP</strong></td>
                                <td>:</td>
                                <td>{{ $d->nomor_hp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td>:</td>
                                <td>{{ $d->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Pendidikan Terakhir</strong></td>
                                <td>:</td>
                                <td>{{ $d->pendidikan_terakhir ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Pekerjaan</strong></td>
                                <td>:</td>
                                <td>{{ $d->pekerjaan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Instansi</strong></td>
                                <td>:</td>
                                <td>{{ $d->instansi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>:</td>
                                <td>
                                    <span class="badge badge-{{ $d->status == 'Aktif' ? 'success' : 'secondary' }}">
                                        {{ $d->status ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Daftar</strong></td>
                                <td>:</td>
                                <td>{{ $d->tanggal_daftar ? date('d-m-Y', strtotime($d->tanggal_daftar)) : '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Berlaku Hingga</strong></td>
                                <td>:</td>
                                <td>{{ $d->berlaku_hingga ? date('d-m-Y', strtotime($d->berlaku_hingga)) : '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> &nbsp;Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal edit-->
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
                                <input type="text" value="{{ $d->id_anggota }}" class="form-control" name="id_anggota" readonly style="background-color: #e9ecef;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" value="{{ $d->nama }}" class="form-control" name="nama" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <select class="form-control" name="jenis_kelamin" required>
                                    <option value="">~Pilih~</option>
                                    <option value="Laki-laki" {{ $d->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ $d->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alamat</label>
                                <input type="text" value="{{ $d->alamat }}" class="form-control" name="alamat" placeholder="Alamat">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nomor HP</label>
                                <input type="text" value="{{ $d->nomor_hp }}" class="form-control" name="nomor_hp" placeholder="Nomor HP">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alamat Email</label>
                                <input type="email" value="{{ $d->email }}" class="form-control" name="email" placeholder="Alamat Email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="Aktif" {{ $d->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Tidak Aktif" {{ $d->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pendidikan Terakhir</label>
                                <select class="form-control" name="pendidikan_terakhir">
                                    <option value="">Pilih</option>
                                    <option value="SD" {{ $d->pendidikan_terakhir == 'SD' ? 'selected' : '' }}>SD</option>
                                    <option value="SMP" {{ $d->pendidikan_terakhir == 'SMP' ? 'selected' : '' }}>SMP</option>
                                    <option value="SMA/SMK" {{ $d->pendidikan_terakhir == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                                    <option value="D3" {{ $d->pendidikan_terakhir == 'D3' ? 'selected' : '' }}>D3</option>
                                    <option value="S1" {{ $d->pendidikan_terakhir == 'S1' ? 'selected' : '' }}>S1</option>
                                    <option value="S2" {{ $d->pendidikan_terakhir == 'S2' ? 'selected' : '' }}>S2</option>
                                    <option value="S3" {{ $d->pendidikan_terakhir == 'S3' ? 'selected' : '' }}>S3</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pekerjaan</label>
                                <input type="text" value="{{ $d->pekerjaan }}" class="form-control" name="pekerjaan" placeholder="Pekerjaan saat ini">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Instansi</label>
                                <input type="text" value="{{ $d->instansi }}" class="form-control" name="instansi" placeholder="Tempat bekerja">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Daftar</label>
                                <input type="date" value="{{ $d->tanggal_daftar }}" class="form-control" name="tanggal_daftar">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Berlaku Hingga</label>
                                <input type="date" value="{{ $d->berlaku_hingga }}" class="form-control" name="berlaku_hingga">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Foto</label>
                        <input type="file" class="form-control" accept=".jpg, .jpeg, .png" name="foto">
                        @if($d->foto)
                            <small class="text-muted">Foto saat ini: {{ $d->foto }}</small>
                        @endif
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

<!-- Modal Hapus-->
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
@endforeach

<!-- Modal tambah-->
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
                                <input type="text" class="form-control" name="id_anggota" value="{{ $nextIdAnggota }}" readonly style="background-color: #e9ecef;">
                                <small class="form-text text-muted">
                                    <i class="fa fa-info-circle"></i> Nomor anggota otomatis di-generate
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-control" name="jenis_kelamin" required>
                                    <option value="">~Pilih~</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alamat</label>
                                <input type="text" class="form-control" name="alamat" placeholder="Alamat">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nomor HP</label>
                                <input type="text" class="form-control" name="nomor_hp" placeholder="Nomor HP">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alamat Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Alamat Email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="Aktif" selected>Aktif</option>
                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pendidikan Terakhir</label>
                                <select class="form-control" name="pendidikan_terakhir">
                                    <option value="">Pilih</option>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA/SMK">SMA/SMK</option>
                                    <option value="D3">D3</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pekerjaan</label>
                                <input type="text" class="form-control" name="pekerjaan" placeholder="Pekerjaan saat ini">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Instansi</label>
                                <input type="text" class="form-control" name="instansi" placeholder="Tempat bekerja">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Daftar</label>
                                <input type="date" class="form-control" name="tanggal_daftar" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Berlaku Hingga</label>
                                <input type="date" class="form-control" name="berlaku_hingga">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Foto <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" accept=".jpg, .jpeg, .png" name="foto" required>
                        <small class="form-text text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB</small>
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
@endsection