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
               	<th>Id Anggota</th>
               	<th>Nama Anggota</th>
               	<th>Foto</th>
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
               	<td>{{ $d->id_anggota }}</td>
               	<td>{{ $d->nama }}</td>
               <td>
                    @if($d->foto)
                        <a href="#" data-toggle="modal" data-target="#viewFoto{{ $d->id_anggota }}">
                            <img src="{{ asset('uploads/foto/'.$d->foto) }}" width="50px" height="50px" alt="Foto" style="cursor: pointer;">
                        </a>
                    @endif
                </td>
                     @auth
                         @if(auth()->user()->level === 'admin')

            		<td>
               		    <button type="button" class="btn btn-success btn-sm" title="Edit data" data-toggle="modal" data-target="#edit{{ $d->id }}"><i class="fa fa-edit"></i></button>
                    </td>
                     @endif
                        @endauth
                    </tr>
                    <!-- Modal edit-->
                    <div class="modal fade" id="edit{{ $d->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  		<div class="modal-dialog">
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
                                        <div class="form-group">
                                            <label>ID Anggota</label>
                                            <input type="text" value="{{ $d->id_anggota }}" class="form-control" name="id_anggota" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Nama Anggota</label>
                                            <input type="text" value="{{ $d->nama }}" class="form-control" name="nama" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Foto</label>
                                            <input type="file" class="form-control" accept=".jpg, .jpeg, .png" name="foto">
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

                    <!-- Modal View Foto -->
                                <div class="modal fade" id="viewFoto{{ $d->id_anggota }}" tabindex="-1" aria-labelledby="viewFotoLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Foto {{ $d->nama }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ asset('uploads/foto/'.$d->foto) }}" class="img-fluid" alt="Foto {{ $d->nama }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal tambah-->
<div class="modal fade" id="tambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
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
                    <div class="form-group">
                        <label>ID Anggota</label>
                        <input type="text" class="form-control" name="id_anggota" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Anggota</label>
                        <input type="text" class="form-control" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label>Foto</label>
                        <input type="file" class="form-control" accept=".jpg, .jpeg, .png" name="foto" required>
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
