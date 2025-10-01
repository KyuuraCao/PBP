<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mbuku;

class Cbuku extends Controller
{
    public function index()
    {
        $data = Mbuku::all();
        $nextKodeBuku = $this->generateKodeBuku();
        return view('buku.index', compact('data', 'nextKodeBuku'));
    }

    private function generateKodeBuku()
    {
        // Format: 900-25090001, 900-25090002, dst
        $prefix = date('y'); // 2 digit tahun (contoh: 25 untuk 2025)
        $month = date('m'); // 2 digit bulan
        
        // Cari kode buku terakhir dengan prefix yang sama
        $lastBuku = Mbuku::where('kode_buku', 'LIKE', '%-' . $prefix . $month . '%')
                         ->orderBy('kode_buku', 'desc')
                         ->first();
        
        if ($lastBuku) {
            // Ambil 4 digit terakhir dan tambah 1
            $lastNumber = intval(substr($lastBuku->kode_buku, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        // Generate nomor kategori/prefix (contoh: 900 untuk buku umum)
        // Anda bisa sesuaikan dengan kategori buku
        $categoryPrefix = '900';
        
        return $categoryPrefix . '-' . $prefix . $month . $newNumber;
    }

    public function save(Request $request)
    {
        if (!auth()->check() || auth()->user()->level !== 'admin') {
            return redirect()->route('buku.index')->with('status', [
                'judul' => 'Gagal', 
                'pesan' => 'Anda tidak memiliki akses untuk menambah data', 
                'icon' => 'error'
            ]);
        }

        $request->validate([
            'kode_buku' => 'required|unique:buku,kode_buku',
            'judul_buku' => 'required|string|max:255',
            'pengarang' => 'nullable|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|max:50',
            'kategori' => 'nullable|string|max:100',
            'jumlah_halaman' => 'nullable|integer|min:1',
            'stok' => 'nullable|integer|min:0',
            'status' => 'required|in:Ada,Dipinjam,Hilang'
        ]);

        Mbuku::create([
            'kode_buku' => $request->kode_buku,
            'judul_buku' => $request->judul_buku,
            'pengarang' => $request->pengarang,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'isbn' => $request->isbn,
            'kategori' => $request->kategori,
            'jumlah_halaman' => $request->jumlah_halaman,
            'stok' => $request->stok ?? 1,
            'status' => $request->status ?? 'Ada'
        ]);

        return redirect()->route('buku.index')->with('status', [
            'judul' => 'Berhasil', 
            'pesan' => 'Data buku berhasil disimpan dengan kode: ' . $request->kode_buku, 
            'icon' => 'success'
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->level !== 'admin') {
            return redirect()->route('buku.index')->with('status', [
                'judul' => 'Gagal', 
                'pesan' => 'Anda tidak memiliki akses untuk mengubah data', 
                'icon' => 'error'
            ]);
        }

        $buku = Mbuku::findOrFail($id);
        
        $request->validate([
            'kode_buku' => 'required|unique:buku,kode_buku,' . $id,
            'judul_buku' => 'required|string|max:255',
            'pengarang' => 'nullable|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|max:50',
            'kategori' => 'nullable|string|max:100',
            'jumlah_halaman' => 'nullable|integer|min:1',
            'stok' => 'nullable|integer|min:0',
            'status' => 'required|in:Ada,Dipinjam,Hilang'
        ]);

        $buku->update([
            'kode_buku' => $request->kode_buku,
            'judul_buku' => $request->judul_buku,
            'pengarang' => $request->pengarang,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'isbn' => $request->isbn,
            'kategori' => $request->kategori,
            'jumlah_halaman' => $request->jumlah_halaman,
            'stok' => $request->stok ?? 0,
            'status' => $request->status ?? 'Ada'
        ]);

        return redirect()->route('buku.index')->with('status', [
            'judul' => 'Berhasil', 
            'pesan' => 'Data buku berhasil diupdate', 
            'icon' => 'success'
        ]);
    }

    public function destroy($id)
    {
        if (!auth()->check() || auth()->user()->level !== 'admin') {
            return redirect()->route('buku.index')->with('status', [
                'judul' => 'Gagal', 
                'pesan' => 'Anda tidak memiliki akses untuk menghapus data', 
                'icon' => 'error'
            ]);
        }

        $buku = Mbuku::findOrFail($id);
        $buku->delete();

        return redirect()->route('buku.index')->with('status', [
            'judul' => 'Berhasil', 
            'pesan' => 'Data buku berhasil dihapus', 
            'icon' => 'success'
        ]);
    }
}