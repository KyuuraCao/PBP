<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mpinjam;
use App\Models\Mpinjam_detail;
use App\Models\Manggota;
use App\Models\Mbuku;
use Illuminate\Support\Facades\DB;

class Cpinjam extends Controller
{
    public function index()
    {
        $peminjaman = Mpinjam::with(['anggota', 'details.buku'])
            ->orderByDesc('id')
            ->get();

        $anggota = Manggota::where('status', 'Aktif')->get();
        $buku = Mbuku::where('status', 'Ada')->get();

        return view('pinjam.index', compact('peminjaman', 'anggota', 'buku'));
    }

    private function generateNoPinjam()
    {
        $prefix = 'PJM-' . date('Ymd');
        
        $lastPinjam = Mpinjam::where('no_pinjam', 'LIKE', $prefix . '%')
            ->orderBy('no_pinjam', 'desc')
            ->first();
        
        if ($lastPinjam) {
            $lastNumber = intval(substr($lastPinjam->no_pinjam, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return $prefix . '-' . $newNumber;
    }

    public function save(Request $request)
    {
        if (!auth()->check() || auth()->user()->level !== 'admin') {
            return redirect()->route('pinjam.index')->with('status', [
                'judul' => 'Gagal',
                'pesan' => 'Anda tidak memiliki akses untuk menambah data',
                'icon' => 'error'
            ]);
        }

        $request->validate([
            'id_anggota' => 'required|exists:anggota,id',
            'tanggal_pinjam' => 'required|date',
            'batas_pinjam' => 'required|date|after_or_equal:tanggal_pinjam',
            'id_buku' => 'required|array|min:1|max:5',
            'id_buku.*' => 'required|exists:buku,id',
        ], [
            'id_anggota.required' => 'Anggota harus dipilih',
            'id_buku.required' => 'Minimal pilih 1 buku',
            'id_buku.max' => 'Maksimal meminjam 5 buku',
            'batas_pinjam.after_or_equal' => 'Batas pinjam harus setelah tanggal pinjam',
        ]);

        DB::beginTransaction();
        try {
            $noPinjam = $this->generateNoPinjam();

            $peminjaman = Mpinjam::create([
                'no_pinjam' => $noPinjam,
                'id_anggota' => $request->id_anggota,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'batas_pinjam' => $request->batas_pinjam,
                'status' => 'pinjam'
            ]);

            foreach ($request->id_buku as $idBuku) {
                if ($idBuku) {
                    Mpinjam_detail::create([
                        'id_pinjam' => $peminjaman->id,
                        'id_buku' => $idBuku,
                        'status' => 'pinjam'
                    ]);

                    $buku = Mbuku::find($idBuku);
                    if ($buku && $buku->stok > 0) {
                        $buku->update([
                            'stok' => $buku->stok - 1,
                            'status' => $buku->stok - 1 > 0 ? 'Ada' : 'Dipinjam'
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('pinjam.index')->with('status', [
                'judul' => 'Berhasil',
                'pesan' => 'Data peminjaman berhasil disimpan dengan No: ' . $noPinjam,
                'icon' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', [
                'judul' => 'Gagal',
                'pesan' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'icon' => 'error'
            ])->withInput();
        }
    }

    public function kembali($id_pinjam, $id_buku)
    {
        if (!auth()->check() || auth()->user()->level !== 'admin') {
            return redirect()->route('pinjam.index')->with('status', [
                'judul' => 'Gagal',
                'pesan' => 'Anda tidak memiliki akses',
                'icon' => 'error'
            ]);
        }

        DB::beginTransaction();
        try {
            $detail = Mpinjam_detail::where('id_pinjam', $id_pinjam)
                ->where('id_buku', $id_buku)
                ->where('status', 'pinjam')
                ->first();

            if (!$detail) {
                return redirect()->back()->with('status', [
                    'judul' => 'Info',
                    'pesan' => 'Buku ini sudah dikembalikan sebelumnya',
                    'icon' => 'info'
                ]);
            }

            $detail->update([
                'status' => 'kembali',
                'tanggal_kembali' => now()
            ]);

            $buku = Mbuku::find($id_buku);
            if ($buku) {
                $buku->update([
                    'stok' => $buku->stok + 1,
                    'status' => 'Ada'
                ]);
            }

            $total = Mpinjam_detail::where('id_pinjam', $id_pinjam)->count();
            $dikembalikan = Mpinjam_detail::where('id_pinjam', $id_pinjam)
                ->where('status', 'kembali')
                ->count();

            $peminjaman = Mpinjam::find($id_pinjam);
            if ($dikembalikan == $total) {
                $peminjaman->update(['status' => 'selesai']);
            } elseif ($dikembalikan > 0) {
                $peminjaman->update(['status' => 'sebagian']);
            }

            DB::commit();

            return redirect()->back()->with('status', [
                'judul' => 'Berhasil',
                'pesan' => 'Buku berhasil dikembalikan',
                'icon' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', [
                'judul' => 'Gagal',
                'pesan' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->level !== 'admin') {
            return redirect()->route('pinjam.index')->with('status', [
                'judul' => 'Gagal',
                'pesan' => 'Anda tidak memiliki akses untuk mengubah data',
                'icon' => 'error'
            ]);
        }

        $request->validate([
            'no_pinjam' => 'required|unique:pinjam,no_pinjam,' . $id,
            'id_anggota' => 'required|exists:anggota,id',
            'tanggal_pinjam' => 'required|date',
            'batas_pinjam' => 'required|date|after_or_equal:tanggal_pinjam',
            'status' => 'required|in:pinjam,sebagian,selesai'
        ]);

        $peminjaman = Mpinjam::findOrFail($id);
        $peminjaman->update($request->all());

        return redirect()->route('pinjam.index')->with('status', [
            'judul' => 'Berhasil',
            'pesan' => 'Data peminjaman berhasil diupdate',
            'icon' => 'success'
        ]);
    }

    public function destroy($id)
    {
        if (!auth()->check() || auth()->user()->level !== 'admin') {
            return redirect()->route('pinjam.index')->with('status', [
                'judul' => 'Gagal',
                'pesan' => 'Anda tidak memiliki akses untuk menghapus data',
                'icon' => 'error'
            ]);
        }

        DB::beginTransaction();
        try {
            $peminjaman = Mpinjam::findOrFail($id);
            
            $detailPinjam = Mpinjam_detail::where('id_pinjam', $id)
                ->where('status', 'pinjam')
                ->get();
            
            foreach ($detailPinjam as $detail) {
                $buku = Mbuku::find($detail->id_buku);
                if ($buku) {
                    $buku->update([
                        'stok' => $buku->stok + 1,
                        'status' => 'Ada'
                    ]);
                }
            }
            
            Mpinjam_detail::where('id_pinjam', $id)->delete();
            $peminjaman->delete();

            DB::commit();

            return redirect()->route('pinjam.index')->with('status', [
                'judul' => 'Berhasil',
                'pesan' => 'Data peminjaman berhasil dihapus',
                'icon' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', [
                'judul' => 'Gagal',
                'pesan' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }
}