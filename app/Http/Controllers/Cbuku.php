<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mbuku;
use App\Models\Mkategori;
use App\Models\Mrak;

class Cbuku extends Controller
{
        public function index(Request $request)
    {
        // FIXED: Proper eager loading with correct relationships
        $query = Mbuku::with(['kategori', 'rak']);

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

        // Filter berdasarkan kategori_id
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter berdasarkan rak_id
        if ($request->filled('rak_id')) {
            $query->where('rak_id', $request->rak_id);
        }

        // Filter berdasarkan tahun terbit (dari - sampai)
        if ($request->filled('tahun_dari') && $request->filled('tahun_sampai')) {
            $query->whereBetween('tahun_terbit', [$request->tahun_dari, $request->tahun_sampai]);
        }

        // Urutkan berdasarkan kode buku terbaru
        $query->orderBy('kode_buku', 'DESC');

        $data = $query->get();
        
        // Ambil data kategori dan rak untuk dropdown
        $kategoriList = Mkategori::orderBy('kode', 'ASC')->get();
        $rakList = Mrak::orderBy('kode_rak', 'ASC')->get();
        
        return view('buku.index', compact('data', 'kategoriList', 'rakList'));
    }

    private function generateKodeBuku($kategoriKode = '900')
    {
        $prefix = date('y');
        $month = date('m');
        
        $lastBuku = Mbuku::where('kode_buku', 'LIKE', $kategoriKode . '-' . $prefix . $month . '%')
                         ->orderBy('kode_buku', 'desc')
                         ->first();
        
        if ($lastBuku) {
            $lastNumber = intval(substr($lastBuku->kode_buku, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return $kategoriKode . '-' . $prefix . $month . $newNumber;
    }

    // Method untuk generate kode buku via AJAX
    public function generateKode(Request $request)
    {
        $kategoriId = $request->kategori_id;
        $kategoriKode = '900'; // default
        
        if ($kategoriId) {
            $kategori = Mkategori::find($kategoriId);
            if ($kategori) {
                $kategoriKode = $kategori->kode;
            }
        }
        
        $kodeBuku = $this->generateKodeBuku($kategoriKode);
        
        return response()->json([
            'kode_buku' => $kodeBuku
        ]);
    }

    public function cetak(Request $request)
    {
        $query = Mbuku::with(['kategori', 'rak']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul_buku', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('kode_buku', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('pengarang', 'LIKE', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('rak_id')) {
            $query->where('rak_id', $request->rak_id);
        }

        if ($request->filled('tahun_dari') && $request->filled('tahun_sampai')) {
            $query->whereBetween('tahun_terbit', [$request->tahun_dari, $request->tahun_sampai]);
        }

        $buku = $query->orderBy('kode_buku', 'DESC')->get();
        
        return view('buku.cetak', compact('buku'));
    }

    public function excel(Request $request)
    {
        header("Content-type: application/vnd-ms-excel");
        header('Content-Disposition: attachment;filename="data_buku_' . date('Y-m-d_His') . '.xls"');
        header('Cache-Control: max-age=0');

        $query = Mbuku::with(['kategori', 'rak']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul_buku', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('kode_buku', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('pengarang', 'LIKE', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('rak_id')) {
            $query->where('rak_id', $request->rak_id);
        }

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
            // FIXED: Use correct table name
            'kategori_id' => 'nullable|exists:kategori_buku,id',
            'rak_id' => 'nullable|exists:rak,id',
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
            'kategori_id' => $request->kategori_id,
            'rak_id' => $request->rak_id,
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
            // FIXED: Use correct table name
            'kategori_id' => 'nullable|exists:kategori_buku,id',
            'rak_id' => 'nullable|exists:rak,id',
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
            'kategori_id' => $request->kategori_id,
            'rak_id' => $request->rak_id,
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