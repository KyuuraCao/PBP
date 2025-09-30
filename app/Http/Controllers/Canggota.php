<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manggota;

class Canggota extends Controller
{
    public function index()
    {
        $data = Manggota::all();
        return view('anggota.index', compact('data'));
    }

    public function save(Request $request)
    {
        // Cek apakah user adalah admin
        if (!auth()->check() || auth()->user()->level === 'admin') {
            return redirect()->route('anggota.index')->with('status', [
                'judul' => 'Gagal', 
                'pesan' => 'Anda tidak memiliki akses untuk menambah data', 
                'icon' => 'error'
            ]);
        }

        $request->validate([
            'id_anggota' => 'required|unique:anggota,id_anggota',
            'nama' => 'required',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $filename = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('uploads/foto'), $filename);
        }

        Manggota::create([
            'id_anggota' => $request->id_anggota,
            'nama' => $request->nama,
            'foto' => $filename
        ]);

        return redirect()->route('anggota.index')->with('status', [
            'judul' => 'Berhasil', 
            'pesan' => 'Data berhasil disimpan dengan ID: ' . $request->id_anggota, 
            'icon' => 'success'
        ]);
    }

    public function update(Request $request, $id)
    {
        // Cek apakah user adalah admin
        if (!auth()->check() || auth()->user()->level != 'admin') {
            return redirect()->route('anggota.index')->with('status', [
                'judul' => 'Gagal', 
                'pesan' => 'Anda tidak memiliki akses untuk mengubah data', 
                'icon' => 'error'
            ]);
        }

        $anggota = Manggota::findOrFail($id);
        
        $request->validate([
            'id_anggota' => 'required|unique:anggota,id_anggota,' . $id,
            'nama' => 'required',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $filename = $anggota->foto;
        
        if ($request->hasFile('foto')) {
            if ($anggota->foto && file_exists(public_path('uploads/foto/' . $anggota->foto))) {
                unlink(public_path('uploads/foto/' . $anggota->foto));
            }
            
            $foto = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('uploads/foto'), $filename);
        }

        $anggota->update([
            'id_anggota' => $request->id_anggota,
            'nama' => $request->nama,
            'foto' => $filename
        ]);

        return redirect()->route('anggota.index')->with('status', [
            'judul' => 'Berhasil', 
            'pesan' => 'Data berhasil diupdate', 
            'icon' => 'success'
        ]);
    }

    public function destroy($id)
    {
        // Cek apakah user adalah admin
        if (!auth()->check() || auth()->user()->level != 'admin') {
            return redirect()->route('anggota.index')->with('status', [
                'judul' => 'Gagal', 
                'pesan' => 'Anda tidak memiliki akses untuk menghapus data', 
                'icon' => 'error'
            ]);
        }

        $anggota = Manggota::findOrFail($id);
        
        if ($anggota->foto && file_exists(public_path('uploads/foto/' . $anggota->foto))) {
            unlink(public_path('uploads/foto/' . $anggota->foto));
        }
        
        $anggota->delete();

        return redirect()->route('anggota.index')->with('status', [
            'judul' => 'Berhasil', 
            'pesan' => 'Data berhasil dihapus', 
            'icon' => 'success'
        ]);
    }
}