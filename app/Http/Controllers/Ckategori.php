<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mkategori;

class Ckategori extends Controller
{
    public function index()
    {
        $data = Mkategori::orderBy('kode', 'asc')->get();
        return view('kategori.index', compact('data'));
    }

    public function save(Request $request)
    {
        if (!auth()->check() || auth()->user()->level !== 'admin') {
            return redirect()->route('kategori.index')->with('status', [
                'judul' => 'Gagal', 
                'pesan' => 'Anda tidak memiliki akses untuk menambah data', 
                'icon' => 'error'
            ]);
        }

        $request->validate([
            'kode' => 'required|unique:kategori_buku,kode|max:10',
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ], [
            'kode.required' => 'Kode kategori wajib diisi',
            'kode.unique' => 'Kode kategori sudah digunakan',
            'nama_kategori.required' => 'Nama kategori wajib diisi'
        ]);

        Mkategori::create([
            'kode' => $request->kode,
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('kategori.index')->with('status', [
            'judul' => 'Berhasil', 
            'pesan' => 'Kategori "' . $request->nama_kategori . '" berhasil ditambahkan', 
            'icon' => 'success'
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->level !== 'admin') {
            return redirect()->route('kategori.index')->with('status', [
                'judul' => 'Gagal', 
                'pesan' => 'Anda tidak memiliki akses untuk mengubah data', 
                'icon' => 'error'
            ]);
        }

        $kategori = Mkategori::findOrFail($id);
        
        $request->validate([
            'kode' => 'required|unique:kategori_buku,kode,' . $id . '|max:10',
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ], [
            'kode.required' => 'Kode kategori wajib diisi',
            'kode.unique' => 'Kode kategori sudah digunakan',
            'nama_kategori.required' => 'Nama kategori wajib diisi'
        ]);

        $kategori->update([
            'kode' => $request->kode,
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('kategori.index')->with('status', [
            'judul' => 'Berhasil', 
            'pesan' => 'Data kategori berhasil diupdate', 
            'icon' => 'success'
        ]);
    }

    public function destroy($id)
    {
        if (!auth()->check() || auth()->user()->level !== 'admin') {
            return redirect()->route('kategori.index')->with('status', [
                'judul' => 'Gagal', 
                'pesan' => 'Anda tidak memiliki akses untuk menghapus data', 
                'icon' => 'error'
            ]);
        }

        try {
            $kategori = Mkategori::findOrFail($id);
            
            // Cek apakah kategori masih digunakan di tabel buku
            $bukuCount = \App\Models\Mbuku::where('kategori', $kategori->nama_kategori)->count();
            
            if ($bukuCount > 0) {
                return redirect()->route('kategori.index')->with('status', [
                    'judul' => 'Gagal', 
                    'pesan' => 'Kategori tidak dapat dihapus karena masih digunakan oleh ' . $bukuCount . ' buku', 
                    'icon' => 'error'
                ]);
            }
            
            $kategori->delete();

            return redirect()->route('kategori.index')->with('status', [
                'judul' => 'Berhasil', 
                'pesan' => 'Kategori berhasil dihapus', 
                'icon' => 'success'
            ]);
        } catch (\Exception $e) {
            return redirect()->route('kategori.index')->with('status', [
                'judul' => 'Gagal', 
                'pesan' => 'Terjadi kesalahan: ' . $e->getMessage(), 
                'icon' => 'error'
            ]);
        }
    }
}