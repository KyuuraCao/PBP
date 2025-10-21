<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mrak;

class Crak extends Controller
{
    public function index()
    {
        $data = Mrak::orderBy('kode_rak', 'ASC')->get();
        return view('rak.index', compact('data'));
    }

    public function save(Request $request)
    {
        if (!auth()->check() || auth()->user()->level !== 'admin') {
            return redirect()->route('rak.index')->with('status', [
                'judul' => 'Gagal', 
                'pesan' => 'Anda tidak memiliki akses', 
                'icon' => 'error'
            ]);
        }

        $request->validate([
            'kode_rak' => 'required|unique:rak,kode_rak',
            'keterangan' => 'nullable|string|max:255'
        ]);

        Mrak::create([
            'kode_rak' => $request->kode_rak,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('rak.index')->with('status', [
            'judul' => 'Berhasil', 
            'pesan' => 'Data rak berhasil disimpan', 
            'icon' => 'success'
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->level !== 'admin') {
            return redirect()->route('rak.index')->with('status', [
                'judul' => 'Gagal', 
                'pesan' => 'Anda tidak memiliki akses', 
                'icon' => 'error'
            ]);
        }

        $rak = Mrak::findOrFail($id);
        
        $request->validate([
            'kode_rak' => 'required|unique:rak,kode_rak,' . $id,
            'keterangan' => 'nullable|string|max:255'
        ]);

        $rak->update([
            'kode_rak' => $request->kode_rak,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('rak.index')->with('status', [
            'judul' => 'Berhasil', 
            'pesan' => 'Data rak berhasil diupdate', 
            'icon' => 'success'
        ]);
    }

    public function destroy($id)
    {
        if (!auth()->check() || auth()->user()->level !== 'admin') {
            return redirect()->route('rak.index')->with('status', [
                'judul' => 'Gagal', 
                'pesan' => 'Anda tidak memiliki akses', 
                'icon' => 'error'
            ]);
        }

        $rak = Mrak::findOrFail($id);
        
        // Cek apakah rak masih digunakan
        if ($rak->buku()->count() > 0) {
            return redirect()->route('rak.index')->with('status', [
                'judul' => 'Gagal', 
                'pesan' => 'Rak tidak dapat dihapus karena masih digunakan pada data buku', 
                'icon' => 'error'
            ]);
        }

        $rak->delete();

        return redirect()->route('rak.index')->with('status', [
            'judul' => 'Berhasil', 
            'pesan' => 'Data rak berhasil dihapus', 
            'icon' => 'success'
        ]);
    }
}