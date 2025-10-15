<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mbuku;

class Cbuku extends Controller
{
    public function index(Request $request)
    {
        // Query builder dengan filter
        $query = Mbuku::query();

        // Filter berdasarkan pencarian (judul, kode buku, pengarang)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul_buku', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('kode_buku', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('pengarang', 'LIKE', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter berdasarkan tahun terbit (dari - sampai)
        if ($request->filled('tahun_dari') && $request->filled('tahun_sampai')) {
            $query->whereBetween('tahun_terbit', [$request->tahun_dari, $request->tahun_sampai]);
        }

        // Urutkan berdasarkan kode buku terbaru
        $query->orderBy('kode_buku', 'DESC');

        $data = $query->get();
        $nextKodeBuku = $this->generateKodeBuku();
        
        return view('buku.index', compact('data', 'nextKodeBuku'));
    }

    private function generateKodeBuku()
    {
        $prefix = date('y');
        $month = date('m');
        
        $lastBuku = Mbuku::where('kode_buku', 'LIKE', '%-' . $prefix . $month . '%')
                         ->orderBy('kode_buku', 'desc')
                         ->first();
        
        if ($lastBuku) {
            $lastNumber = intval(substr($lastBuku->kode_buku, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        $categoryPrefix = '900';
        
        return $categoryPrefix . '-' . $prefix . $month . $newNumber;
    }

    public function cetak(Request $request)
    {
        // Query dengan SEMUA filter untuk cetak
        $query = Mbuku::query();

        // Filter pencarian
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul_buku', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('kode_buku', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('pengarang', 'LIKE', '%' . $request->search . '%');
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter tahun terbit
        if ($request->filled('tahun_dari') && $request->filled('tahun_sampai')) {
            $query->whereBetween('tahun_terbit', [$request->tahun_dari, $request->tahun_sampai]);
        }

        $buku = $query->orderBy('kode_buku', 'DESC')->get();
        
        // Kirim info filter ke view
        $filterInfo = [
            'search' => $request->search,
            'status' => $request->status,
            'kategori' => $request->kategori,
            'tahun_dari' => $request->tahun_dari,
            'tahun_sampai' => $request->tahun_sampai,
        ];
        
        return view('buku.cetak', compact('buku', 'filterInfo'));
    }

    public function excel(Request $request)
    {
        header("Content-type: application/vnd-ms-excel");
        header('Content-Disposition: attachment;filename="data_buku_' . date('Y-m-d_His') . '.xls"');
        header('Cache-Control: max-age=0');

        // Query dengan SEMUA filter untuk export
        $query = Mbuku::query();

        // Filter pencarian
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul_buku', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('kode_buku', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('pengarang', 'LIKE', '%' . $request->search . '%');
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter tahun terbit
        if ($request->filled('tahun_dari') && $request->filled('tahun_sampai')) {
            $query->whereBetween('tahun_terbit', [$request->tahun_dari, $request->tahun_sampai]);
        }

        $buku = $query->orderBy('kode_buku', 'DESC')->get();
        
        return view('buku.excel', compact('buku'));
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